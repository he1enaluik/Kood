(function () {
  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
  }

  function getMailConfig() {
    return window.TARUKODA_MAIL || {};
  }

  function isStaticHost() {
    const port = window.location.port;
    return (
      window.location.hostname.endsWith(".surge.sh") ||
      port === "5500" ||
      port === "5501"
    );
  }

  function getEndpoints() {
    const body = document.body;
    const stripeConfig = window.TARUKODA_STRIPE || {};
    const apiBase =
      stripeConfig.usesLiveServer && stripeConfig.checkoutEndpoint
        ? stripeConfig.checkoutEndpoint.replace(/\/api\/stripe-checkout\.php$/, "")
        : "";

    return {
      contact: body.dataset.contactEndpoint || (apiBase ? `${apiBase}/api/contact.php` : "api/contact.php"),
      order: body.dataset.orderEndpoint || (apiBase ? `${apiBase}/api/order.php` : "api/order.php"),
    };
  }

  function showFormMessage(form, message, isError = false) {
    let messageEl = form.querySelector(".form-message");

    if (!messageEl) {
      messageEl = document.createElement("p");
      messageEl.className = "form-message";
      form.prepend(messageEl);
    }

    messageEl.textContent = message;
    messageEl.hidden = false;
    messageEl.classList.toggle("form-message--error", isError);
    messageEl.classList.toggle("form-message--success", !isError);
  }

  function setSubmitting(form, isSubmitting) {
    const button = form.querySelector('[type="submit"]');

    if (button) {
      button.disabled = isSubmitting;
      button.dataset.defaultLabel = button.dataset.defaultLabel || button.textContent;
      button.textContent = isSubmitting ? "Saadan..." : button.dataset.defaultLabel;
    }
  }

  function saveLocalMessage(type, payload) {
    const key = "tarukoda-local-inbox";
    const inbox = JSON.parse(localStorage.getItem(key) || "[]");

    inbox.push({
      type,
      payload,
      sentAt: new Date().toISOString(),
    });

    localStorage.setItem(key, JSON.stringify(inbox));
  }

  function isLocalPhp() {
    return Boolean(window.TARUKODA_STRIPE?.isLocalPhp);
  }

  async function parseJsonResponse(response) {
    const text = await response.text();

    try {
      return JSON.parse(text);
    } catch {
      const match = text.match(/\{[\s\S]*\}/);
      if (match) {
        try {
          return JSON.parse(match[0]);
        } catch {
          return {};
        }
      }
      return {};
    }
  }

  async function postJson(url, payload) {
    const headers = {
      "Content-Type": "application/json",
      Accept: "application/json",
    };

    const csrf = getCsrfToken();
    if (csrf) {
      headers["X-CSRF-TOKEN"] = csrf;
    }

    const response = await fetch(url, {
      method: "POST",
      headers,
      body: JSON.stringify(payload),
    });

    const data = await parseJsonResponse(response);

    if (!response.ok || !data.success) {
      const error = new Error(data.message || "Sõnumi saatmine ebaõnnestus.");
      error.status = response.status;
      throw error;
    }

    return data;
  }

  async function sendViaWeb3Forms(payload) {
    const config = getMailConfig();
    const accessKey = config.web3formsKey?.trim();

    if (!accessKey) {
      return null;
    }

    const response = await fetch("https://api.web3forms.com/submit", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
      body: JSON.stringify({
        access_key: accessKey,
        ...payload,
      }),
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || !data.success) {
      throw new Error(data.message || "E-kirja saatmine ebaõnnestus.");
    }

    return { success: true, message: payload.successMessage };
  }

  function buildContactWeb3Payload(formPayload) {
    const firstname = formPayload.firstname || "";
    const lastname = formPayload.lastname || "";
    const name = `${firstname} ${lastname}`.trim() || formPayload.email;

    return {
      subject: `Tarukoda kontakt: ${name}`,
      from_name: name,
      email: formPayload.email,
      message: formPayload.message,
      successMessage: "Sõnum saadetud! Vastame esimesel võimalusel.",
    };
  }

  function buildOrderWeb3Payload(formPayload) {
    const cartLines = (formPayload.cart || [])
      .map(
        (item) =>
          `- ${item.name} × ${item.quantity} = ${Number(item.lineTotal).toFixed(2).replace(".", ",")} €`
      )
      .join("\n");

    const message = [
      "Uus tellimus - Tarukoda",
      "",
      "KLIENDI ANDMED",
      "---------------",
      `Nimi: ${formPayload.firstname} ${formPayload.lastname}`,
      `E-post: ${formPayload.email}`,
      `Telefon: ${formPayload.phone}`,
      `Aadress: ${formPayload.address}, ${formPayload.postcode} ${formPayload.city}`,
      formPayload.notes ? `Märkused: ${formPayload.notes}` : "",
      "",
      "TOOTED",
      "------",
      cartLines,
      "",
      `Vahesumma: ${Number(formPayload.subtotal).toFixed(2).replace(".", ",")} €`,
      `Tarne: ${Number(formPayload.shipping).toFixed(2).replace(".", ",")} €`,
      `Kokku: ${Number(formPayload.total).toFixed(2).replace(".", ",")} €`,
    ]
      .filter(Boolean)
      .join("\n");

    return {
      subject: `Tarukoda tellimus: ${formPayload.firstname} ${formPayload.lastname}`,
      from_name: `${formPayload.firstname} ${formPayload.lastname}`,
      email: formPayload.email,
      message,
      successMessage: "Tellimus esitatud! Võtame sinuga peagi ühendust.",
    };
  }

  async function submitContact(formPayload, endpoint) {
    const web3Payload = buildContactWeb3Payload(formPayload);
    const web3Key = getMailConfig().web3formsKey?.trim();

    if (web3Key) {
      const result = await sendViaWeb3Forms(web3Payload);

      if (isLocalPhp()) {
        postJson(endpoint, formPayload).catch(() => {});
      }

      return result;
    }

    if (isLocalPhp()) {
      return await postJson(endpoint, formPayload);
    }

    throw new Error(
      "E-posti saatmine pole seadistatud. Lisa Web3Forms võti faili js/site-config.local.js (loo võti aadressil helena.luik2007@gmail.com → web3forms.com)."
    );
  }

  async function submitMessage(type, formPayload, endpoint) {
    const web3Payload =
      type === "contact"
        ? buildContactWeb3Payload(formPayload)
        : buildOrderWeb3Payload(formPayload);

    const useWeb3Forms =
      !isLocalPhp() &&
      getMailConfig().web3formsKey?.trim() &&
      (!getCsrfToken() || isStaticHost());

    if (useWeb3Forms) {
      const web3Result = await sendViaWeb3Forms(web3Payload);
      if (web3Result) {
        return web3Result;
      }
    }

    try {
      return await postJson(endpoint, formPayload);
    } catch (error) {
      if (getMailConfig().web3formsKey?.trim() && isStaticHost()) {
        return await sendViaWeb3Forms(web3Payload);
      }

      if (error.status === 405 || error.status === 404) {
        saveLocalMessage(type, formPayload);

        throw new Error(
          isStaticHost()
            ? "Lisa Web3Forms võti faili js/site-config.local.js (vt site-config.local.js.example) või käivita: php -S 127.0.0.1:8080"
            : "PHP server ei tööta. Käivita: php -S 127.0.0.1:8080"
        );
      }

      throw error;
    }
  }

  function initContactForm() {
    const form = document.querySelector(".contact-page__form");

    if (!form || form.dataset.bound === "true") {
      return;
    }

    form.dataset.bound = "true";

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      if (!form.reportValidity()) {
        return;
      }

      const formData = new FormData(form);
      const payload = Object.fromEntries(formData.entries());

      try {
        setSubmitting(form, true);
        const result = await submitContact(payload, getEndpoints().contact);
        showFormMessage(form, result.message, false);
        form.reset();
      } catch (error) {
        showFormMessage(form, error.message, true);
      } finally {
        setSubmitting(form, false);
      }
    });
  }

  function initOrderForm() {
    const form = document.querySelector(".order-page__form");

    if (!form || form.dataset.bound === "true") {
      return;
    }

    form.dataset.bound = "true";

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      if (!form.reportValidity()) {
        return;
      }

      const cartItems = window.TarukodaCart?.getCartWithProducts() || [];

      if (!cartItems.length) {
        showFormMessage(form, "Ostukorv on tühi.", true);
        return;
      }

      const formData = new FormData(form);
      const payload = Object.fromEntries(formData.entries());
      const subtotal = window.TarukodaCart.getSubtotal(cartItems);
      const shipping = window.TarukodaCart.SHIPPING;
      const total = subtotal + shipping;

      payload.cart = cartItems.map((item) => ({
        name: item.product.name,
        quantity: item.quantity,
        lineTotal: item.lineTotal,
      }));
      payload.subtotal = subtotal;
      payload.shipping = shipping;
      payload.total = total;

      try {
        setSubmitting(form, true);

        if (window.TarukodaStripe?.isEnabled?.()) {
          await window.TarukodaStripe.startCheckout(payload);
          return;
        }

        const result = await submitMessage("order", payload, getEndpoints().order);
        showFormMessage(form, result.message, false);
        form.reset();
        window.TarukodaCart?.clearCart();
      } catch (error) {
        showFormMessage(form, error.message, true);
      } finally {
        setSubmitting(form, false);
      }
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    initContactForm();
    initOrderForm();

  });
})();
