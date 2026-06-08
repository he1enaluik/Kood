/**
 * Stripe testkeskkonna seadistus.
 *
 * Live Server (port 5500) ei käita PHP-d — makse-API jookseb eraldi:
 *   npm run stripe:server
 */
(function () {
  const liveServerPorts = new Set(["5500", "5501"]);
  const isLiveServer = liveServerPorts.has(window.location.port);
  const checkoutEndpoint = isLiveServer
    ? "http://localhost:8080/api/stripe-checkout.php"
    : "api/stripe-checkout.php";

  if (!window.TARUKODA_STRIPE) {
    window.TARUKODA_STRIPE = {
      publishableKey: "",
      checkoutEndpoint,
      usesLiveServer: isLiveServer,
    };
  }
})();
