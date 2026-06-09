(function () {
  function getConfig() {
    return window.TARUKODA_STRIPE || {};
  }

  function isEnabled() {
    return Boolean(getConfig().publishableKey?.trim());
  }

  function getEndpoint() {
    return (
      document.body.dataset.stripeCheckoutEndpoint ||
      getConfig().checkoutEndpoint ||
      "api/stripe-checkout.php"
    );
  }

  function buildCheckoutError(response, data) {
    if (response.status === 405 && getConfig().usesLiveServer) {
      return "Live Server ei käita PHP-d. Käivita teises terminalis: php -S 127.0.0.1:8080";
    }

    if (response.status === 503) {
      return data.message || "Loo api/stripe-config.php (vaata api/stripe-config.example.php).";
    }

    if (response.status === 0 || response.type === "opaque") {
      return "Stripe server ei vasta. Käivita: php -S 127.0.0.1:8080";
    }

    return data.message || "Makse alustamine ebaõnnestus.";
  }

  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
  }

  async function startCheckout(payload) {
    const headers = {
      "Content-Type": "application/json",
      Accept: "application/json",
    };
    const csrf = getCsrfToken();
    if (csrf) {
      headers["X-CSRF-TOKEN"] = csrf;
    }

    const response = await fetch(getEndpoint(), {
      method: "POST",
      headers,
      credentials: "same-origin",
      body: JSON.stringify(payload),
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || !data.success || !data.url) {
      throw new Error(buildCheckoutError(response, data));
    }

    window.location.href = data.url;
  }

  window.TarukodaStripe = {
    isEnabled,
    startCheckout,
  };
})();
