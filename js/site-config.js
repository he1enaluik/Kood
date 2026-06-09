/**
 * Tarukoda kohalik seadistus.
 *
 * 1. Kopeeri js/site-config.local.js.example → js/site-config.local.js
 * 2. Lisa Web3Forms võti (tasuta, web3forms.com) — vajalik Surge'il ja Live Serveril
 * 3. Lisa Stripe testvõtmed — vajalik maksete jaoks
 */
(function () {
  const port = window.location.port;
  const host = window.location.hostname;
  const isLiveServer = new Set(["5500", "5501"]).has(port);
  const isSurge = host.endsWith(".surge.sh");
  const isLocalPhp =
    port === "8080" ||
    port === "8000" ||
    (host === "127.0.0.1" && port === "") ||
    (host === "localhost" && (port === "" || port === "8080" || port === "8000"));

  const phpApiBase = isLiveServer ? "http://127.0.0.1:8080" : "";

  window.TARUKODA_MAIL = {
    web3formsKey: "",
  };

  window.TARUKODA_STRIPE = {
    publishableKey: "",
    checkoutEndpoint: phpApiBase
      ? `${phpApiBase}/api/stripe-checkout.php`
      : "api/stripe-checkout.php",
    usesLiveServer: isLiveServer,
    isStaticHost: isSurge || isLiveServer,
    isLocalPhp,
  };
})();
