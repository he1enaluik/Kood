(function () {
  function renderOrderPage() {
    const itemsContainer = document.getElementById("order-cart-items");
    const emptyMessage = document.getElementById("order-cart-empty");
    const totalsContainer = document.getElementById("order-cart-totals");
    const form = document.querySelector(".order-page__form");

    if (!itemsContainer || !totalsContainer) return;

    const items = window.TarukodaCart.getCartWithProducts();
    const encode = window.TarukodaProducts.encodeAssetPath;
    const formatPrice = window.TarukodaProducts.formatPrice;

    const productsUrl =
      document.querySelector(".order-page")?.dataset.productsUrl || "tooted.html";

    if (!items.length) {
      itemsContainer.innerHTML = "";
      if (emptyMessage) {
        emptyMessage.innerHTML = `Ostukorv on tühi. <a href="${productsUrl}">Vaata tooteid</a>`;
        emptyMessage.hidden = false;
      }
      totalsContainer.innerHTML = "";
      if (form) form.hidden = true;
      return;
    }

    if (emptyMessage) emptyMessage.hidden = true;
    if (form) form.hidden = false;

    itemsContainer.innerHTML = items
      .map(
        (item) => `
        <article class="order-page__item" data-slug="${item.slug}">
          <img
            class="order-page__item-image"
            src="${encode(item.product.image)}"
            alt="${item.product.name}"
            width="80"
            height="96"
          >
          <div class="order-page__item-info">
            <h2 class="order-page__item-name">${item.product.name}</h2>
            <p class="order-page__item-meta">${item.product.weight} • ${item.quantity} tk</p>
            <button type="button" class="order-page__item-remove" data-remove="${item.slug}">Eemalda</button>
          </div>
          <span class="order-page__item-price">${formatPrice(item.lineTotal)}</span>
        </article>
      `
      )
      .join("");

    const subtotal = window.TarukodaCart.getSubtotal(items);
    const shipping = window.TarukodaCart.SHIPPING;
    const total = subtotal + shipping;

    totalsContainer.innerHTML = `
      <div class="order-page__total-row">
        <dt>Vahesumma</dt>
        <dd>${formatPrice(subtotal)}</dd>
      </div>
      <div class="order-page__total-row">
        <dt>Tarne</dt>
        <dd>${formatPrice(shipping)}</dd>
      </div>
      <div class="order-page__total-row order-page__total-row--final">
        <dt>Kokku</dt>
        <dd>${formatPrice(total)}</dd>
      </div>
    `;

    itemsContainer.querySelectorAll("[data-remove]").forEach((button) => {
      button.addEventListener("click", () => {
        window.TarukodaCart.removeItem(button.dataset.remove);
        renderOrderPage();
      });
    });
  }

  document.addEventListener("DOMContentLoaded", renderOrderPage);
  window.addEventListener("tarukoda-cart-updated", renderOrderPage);
})();
