(function () {
  function getProductBase() {
    const wrap = document.querySelector("[data-product-base]");
    return wrap?.dataset.productBase || "toote-detail.html?toode=";
  }

  function productUrl(slug) {
    const base = getProductBase();
    return `${base}${slug}`;
  }

  function searchProducts(query) {
    const products = window.TARUKODA_PRODUCTS || {};
    const q = query.trim().toLowerCase();

    if (q.length < 2) {
      return [];
    }

    return Object.values(products)
      .filter((product) => {
        const haystack = [
          product.name,
          product.short_desc,
          product.description,
          product.origin,
          product.category,
        ]
          .join(" ")
          .toLowerCase();

        return haystack.includes(q);
      })
      .slice(0, 6);
  }

  function renderResults(results, dropdown, input) {
    const encode = window.TarukodaProducts?.encodeAssetPath || ((path) => path);
    const formatPrice = window.TarukodaProducts?.formatPrice || ((price) => `${price} €`);

    if (!results.length) {
      dropdown.innerHTML = `<p class="header__search-empty">Tooteid ei leitud</p>`;
      dropdown.hidden = false;
      input.setAttribute("aria-expanded", "true");
      return;
    }

    dropdown.innerHTML = results
      .map(
        (product) => `
        <a href="${productUrl(product.slug)}" class="header__search-result" role="option">
          ${
            product.image
              ? `<img
            class="header__search-result-image"
            src="${encode(product.image)}"
            alt=""
            width="40"
            height="48"
          >`
              : `<span class="header__search-result-image header__search-result-image--placeholder" aria-hidden="true"></span>`
          }
          <span class="header__search-result-info">
            <span class="header__search-result-name">${product.name}</span>
            <span class="header__search-result-desc">${product.short_desc}</span>
          </span>
          <span class="header__search-result-price">${formatPrice(product.price)}</span>
        </a>
      `
      )
      .join("");

    dropdown.hidden = false;
    input.setAttribute("aria-expanded", "true");
  }

  function closeDropdown(dropdown, input) {
    dropdown.hidden = true;
    dropdown.innerHTML = "";
    input.setAttribute("aria-expanded", "false");
  }

  function initSearch() {
    const searchWrap = document.querySelector(".header__search");
    const toggle = document.getElementById("header-search-toggle");
    const input = document.getElementById("header-search");
    const dropdown = document.getElementById("header-search-results");

    if (!searchWrap || !toggle || !input || !dropdown) {
      return;
    }

    function isOpen() {
      return searchWrap.classList.contains("is-open");
    }

    function openSearch() {
      searchWrap.classList.add("is-open");
      toggle.setAttribute("aria-expanded", "true");
      toggle.setAttribute("aria-label", "Sulge otsing");

      window.requestAnimationFrame(() => {
        input.focus();
      });
    }

    function closeSearch() {
      searchWrap.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Ava otsing");
      closeDropdown(dropdown, input);
      input.value = "";
      input.blur();
    }

    toggle.addEventListener("click", () => {
      if (isOpen()) {
        closeSearch();
      } else {
        openSearch();
      }
    });

    input.addEventListener("input", () => {
      const results = searchProducts(input.value);

      if (!input.value.trim()) {
        closeDropdown(dropdown, input);
        return;
      }

      renderResults(results, dropdown, input);
    });

    input.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        closeSearch();
      }
    });

    document.addEventListener("click", (event) => {
      if (!event.target.closest(".header__search")) {
        if (isOpen()) {
          closeSearch();
        } else {
          closeDropdown(dropdown, input);
        }
      }
    });
  }

  document.addEventListener("DOMContentLoaded", initSearch);
})();
