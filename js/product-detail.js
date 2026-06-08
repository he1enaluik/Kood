(function () {
  function getSlugFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return params.get("toode") || "niidumesi";
  }

  function renderGallery(gallery) {
    const gallerySection = document.querySelector(".product-detail__gallery");
    const inner = document.querySelector(".product-detail__inner");
    const thumbsContainer = document.getElementById("product-detail-thumbs");
    const mainImage = document.getElementById("product-detail-main-image");

    if (!gallery.length) {
      if (gallerySection) gallerySection.hidden = true;
      inner?.classList.add("product-detail__inner--no-gallery");
      return;
    }

    if (gallerySection) gallerySection.hidden = false;
    inner?.classList.remove("product-detail__inner--no-gallery");

    if (!thumbsContainer || !mainImage) return;

    const encode = window.TarukodaProducts.encodeAssetPath;

    mainImage.src = encode(gallery[0].src);
    mainImage.alt = gallery[0].alt;

    thumbsContainer.innerHTML = gallery
      .map(
        (item, index) => `
        <button
          type="button"
          class="product-detail__thumb${index === 0 ? " is-active" : ""}"
          role="tab"
          aria-selected="${index === 0 ? "true" : "false"}"
          aria-controls="product-detail-main-image"
          data-image="${encode(item.src)}"
          data-alt="${item.alt}"
        >
          <img src="${encode(item.src)}" alt="" width="88" height="88">
        </button>
      `
      )
      .join("");

    initGallery();
  }

  function initGallery() {
    const mainImage = document.getElementById("product-detail-main-image");
    const thumbs = document.querySelectorAll(".product-detail__thumb");
    const prevBtn = document.querySelector(".product-detail__arrow--prev");
    const nextBtn = document.querySelector(".product-detail__arrow--next");

    function selectImage(index) {
      const thumb = thumbs[index];
      if (!thumb || !mainImage) return;

      mainImage.src = thumb.dataset.image;
      mainImage.alt = thumb.dataset.alt;

      thumbs.forEach((item, i) => {
        item.classList.toggle("is-active", i === index);
        item.setAttribute("aria-selected", i === index ? "true" : "false");
      });
    }

    function getActiveIndex() {
      return [...thumbs].findIndex((thumb) => thumb.classList.contains("is-active"));
    }

    thumbs.forEach((thumb, index) => {
      thumb.addEventListener("click", () => selectImage(index));
    });

    prevBtn?.addEventListener("click", () => {
      const activeIndex = getActiveIndex();
      selectImage((activeIndex - 1 + thumbs.length) % thumbs.length);
    });

    nextBtn?.addEventListener("click", () => {
      const activeIndex = getActiveIndex();
      selectImage((activeIndex + 1) % thumbs.length);
    });
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
    if (desc) desc.textContent = product.description;
    if (origin) origin.textContent = product.origin;
    if (weight) weight.textContent = product.weight;
    if (addBtn) addBtn.dataset.slug = product.slug;

    renderGallery(product.gallery);
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
