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

  window.TARUKODA_STRIPE = {
    publishableKey:
      "pk_test_51TfPwUKuzkXTZt1CJwnedMi88WhXbjd9PSp7RvGlKNd3kaKHyRzVNepEZXI27eOOwQzqoHFIAyHK3Inx7udZeJG100wuk73SAh",
    checkoutEndpoint,
    usesLiveServer: isLiveServer,
  };
})();
