(function () {
  const MOBILE_PRODUCTS_QUERY = "(max-width: 480px)";
  const MOBILE_PRODUCTS_PAGE_SIZE = 4;

  function isMobileProductsView() {
    return window.matchMedia(MOBILE_PRODUCTS_QUERY).matches;
  }

  function initProductsFilter() {
    const grid = document.getElementById("products-grid");
    if (!grid) return;

    const cards = [...grid.querySelectorAll(".product-card")];
    const categoryInput = document.getElementById("filter-category");
    const categoryButtons = [...document.querySelectorAll("[data-filter-category]")];
    const originInput = document.getElementById("filter-origin");
    const sortSelect = document.getElementById("filter-sort");
    const countEl = document.getElementById("products-count");
    const emptyEl = document.getElementById("products-empty");
    const loadMoreBtn = document.getElementById("products-load-more");
    let mobileVisibleCount = MOBILE_PRODUCTS_PAGE_SIZE;

    if (!categoryInput || !originInput || !sortSelect || !cards.length) return;

    cards.forEach((card) => {
      const cat = card.dataset.category;
      const origin = card.dataset.origin;

      if (cat) card.classList.add(`product-card--cat-${cat}`);
      if (origin) card.classList.add(`product-card--origin-${origin}`);
    });

    function setCategory(value) {
      categoryInput.value = value;
      categoryButtons.forEach((btn) => {
        btn.classList.toggle("is-active", btn.dataset.filterCategory === value);
      });

      grid.classList.remove(
        "products-page__grid--cat-mesi",
        "products-page__grid--cat-kunlad",
        "products-page__grid--cat-kinke",
        "products-page__grid--cat-hooaeg"
      );

      if (value !== "all") {
        grid.classList.add(`products-page__grid--cat-${value}`);
      }
    }

    const params = new URLSearchParams(window.location.search);
    const categoryParam = params.get("category");

    if (categoryParam && categoryButtons.some((b) => b.dataset.filterCategory === categoryParam)) {
      setCategory(categoryParam);
    }

    function cardMatches(card, category, origin) {
      const matchCategory =
        category === "all" || card.classList.contains(`product-card--cat-${category}`);
      const matchOrigin =
        origin === "all" || card.classList.contains(`product-card--origin-${origin}`);
      return matchCategory && matchOrigin;
    }

    function applyMobilePagination(visible) {
      if (!isMobileProductsView()) {
        visible.forEach((card) => {
          card.classList.remove("product-card--page-hidden");
          card.style.display = "";
        });
        if (loadMoreBtn) loadMoreBtn.hidden = true;
        return;
      }

      visible.forEach((card, index) => {
        const showOnPage = index < mobileVisibleCount;
        card.classList.toggle("product-card--page-hidden", !showOnPage);
        card.style.display = showOnPage ? "" : "none";
      });

      if (loadMoreBtn) {
        loadMoreBtn.hidden = visible.length <= mobileVisibleCount;
      }
    }

    function applyFilters(resetMobilePage) {
      const category = categoryInput.value;
      const origin = originInput.value;
      const sort = sortSelect.value;

      if (resetMobilePage) {
        mobileVisibleCount = MOBILE_PRODUCTS_PAGE_SIZE;
      }

      let visible = cards.filter((card) => cardMatches(card, category, origin));

      if (sort === "price-asc") {
        visible.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
      } else if (sort === "price-desc") {
        visible.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
      }

      cards.forEach((card) => {
        const show = visible.includes(card);
        card.classList.toggle("product-card--hidden", !show);
        if (!show) {
          card.classList.remove("product-card--page-hidden");
          card.style.display = "none";
        }
      });

      visible.forEach((card) => grid.appendChild(card));
      applyMobilePagination(visible);

      const count = visible.length;

      if (countEl) {
        countEl.textContent = count === 1 ? "Näitan 1 toodet" : `Näitan ${count} toodet`;
      }

      if (emptyEl) emptyEl.hidden = count > 0;
      grid.hidden = false;
    }

    categoryButtons.forEach((btn) => {
      btn.addEventListener("click", () => {
        setCategory(btn.dataset.filterCategory);
        applyFilters(true);
      });
    });

    sortSelect.addEventListener("change", () => applyFilters(true));

    if (loadMoreBtn) {
      loadMoreBtn.addEventListener("click", () => {
        mobileVisibleCount += MOBILE_PRODUCTS_PAGE_SIZE;
        applyFilters(false);
      });
    }

    window.addEventListener("resize", () => applyFilters(false));

    applyFilters(true);
  }

  window.TarukodaProductsFilter = {
    init: initProductsFilter,
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initProductsFilter);
  } else {
    initProductsFilter();
  }
})();
