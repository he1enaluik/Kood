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
      return "Live Server ei käita PHP-d. Käivita teises terminalis: npm run stripe:server";
    }

    if (response.status === 0 || response.type === "opaque") {
      return "Stripe server ei vasta. Käivita: npm run stripe:server";
    }

    return data.message || "Makse alustamine ebaõnnestus.";
  }

  async function startCheckout(payload) {
    const response = await fetch(getEndpoint(), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
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
