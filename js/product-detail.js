(function () {
  function getSlugFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get("toode") || "niidumesi";
  }

  function buildDescription(product) {
    const firstSentence = product.description?.split(/(?<=\.)\s+/)[0] || "";
    const extra =
      firstSentence && !product.short_desc.includes(firstSentence.slice(0, 24))
        ? ` ${firstSentence}`
        : "";
    return `${product.short_desc}${extra}`;
  }

  function getPrimaryImage(product) {
    if (product.gallery?.length) return product.gallery[0];
    if (product.image) return { src: product.image, alt: product.name };
    return null;
  }

  function renderThumbs(product) {
    const thumbsContainer = document.getElementById("product-detail-thumbs");
    if (!thumbsContainer) return;

    const primary = getPrimaryImage(product);
    if (!primary) {
      thumbsContainer.hidden = true;
      thumbsContainer.innerHTML = "";
      return;
    }

    const encode = window.TarukodaProducts.encodeAssetPath;
    thumbsContainer.hidden = false;
    thumbsContainer.innerHTML = Array.from({ length: 4 }, (_, index) => `
      <button
        type="button"
        class="product-detail__thumb${index === 0 ? " product-detail__thumb--active" : ""}"
        data-index="${index}"
        aria-label="Vaata pilti ${index + 1}"
      >
        <img
          src="${encode(primary.src)}"
          alt="${primary.alt}"
          width="94"
          height="94"
          loading="lazy"
        >
      </button>
    `).join("");

    thumbsContainer.querySelectorAll(".product-detail__thumb").forEach((button) => {
      button.addEventListener("click", () => {
        thumbsContainer.querySelectorAll(".product-detail__thumb").forEach((el) => {
          el.classList.remove("product-detail__thumb--active");
        });
        button.classList.add("product-detail__thumb--active");
      });
    });
  }

  function renderGallery(product) {
    const mainWrap = document.querySelector(".product-detail__main");
    const mainImage = document.getElementById("product-detail-main-image");
    const primary = getPrimaryImage(product);

    if (!mainWrap) return;

    if (!primary) {
      mainWrap.classList.add("product-detail__main--placeholder");
      if (mainImage) mainImage.hidden = true;
      renderThumbs(product);
      return;
    }

    mainWrap.classList.remove("product-detail__main--placeholder");
    if (!mainImage) return;

    mainImage.hidden = false;
    mainImage.src = window.TarukodaProducts.encodeAssetPath(primary.src);
    mainImage.alt = primary.alt;
    renderThumbs(product);
  }

  function initQuantityStepper() {
    const qtyValue = document.getElementById("product-qty");
    const minusBtn = document.getElementById("qty-minus");
    const plusBtn = document.getElementById("qty-plus");
    let quantity = 1;

    minusBtn?.addEventListener("click", () => {
      if (quantity > 1) {
        quantity -= 1;
        qtyValue.textContent = quantity;
      }
    });

    plusBtn?.addEventListener("click", () => {
      if (quantity < 20) {
        quantity += 1;
        qtyValue.textContent = quantity;
      }
    });

    return () => quantity;
  }

  function loadProduct() {
    const slug = getSlugFromUrl();
    const product = window.TarukodaProducts.get(slug);

    if (!product) {
      window.location.href = "tooted.html";
      return null;
    }

    document.title = `${product.name} | Tarukoda`;

    const title = document.getElementById("product-detail-title");
    const price = document.getElementById("product-detail-price");
    const desc = document.getElementById("product-detail-desc");
    const origin = document.getElementById("product-detail-origin");
    const weight = document.getElementById("product-detail-weight");
    const addBtn = document.getElementById("product-add-to-cart");

    if (title) title.textContent = product.name;
    if (price) price.textContent = window.TarukodaProducts.formatPrice(product.price);
    if (desc) desc.textContent = buildDescription(product);
    if (origin) origin.textContent = product.origin;
    if (weight) weight.textContent = product.weight;
    if (addBtn) addBtn.dataset.slug = product.slug;

    renderGallery(product);
    return product;
  }

  document.addEventListener("DOMContentLoaded", () => {
    const product = loadProduct();
    if (!product) return;

    const getQuantity = initQuantityStepper();
    const addBtn = document.getElementById("product-add-to-cart");

    addBtn?.addEventListener("click", () => {
      window.TarukodaCart.addItem(product.slug, getQuantity());
      window.location.href = "tellimus.html";
    });
  });
})();
