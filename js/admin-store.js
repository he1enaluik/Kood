(function () {
  const STORAGE_KEY = "tarukoda-admin-products";
  const API_PATH = "/admin/api/products";

  function isLaravel() {
    return document.body.dataset.authBackend === "laravel";
  }

  function isServerAdmin() {
    return Boolean(window.TARUKODA_IS_ADMIN);
  }

  function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
  }

  function defaultState() {
    return { overrides: {}, deletedSlugs: [] };
  }

  function loadLocalState() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : defaultState();
      return {
        overrides: parsed.overrides && typeof parsed.overrides === "object" ? parsed.overrides : {},
        deletedSlugs: Array.isArray(parsed.deletedSlugs) ? parsed.deletedSlugs : [],
      };
    } catch {
      return defaultState();
    }
  }

  function saveLocalState(state) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  }

  let serverState = null;

  async function loadState() {
    if (isLaravel() && isServerAdmin()) {
      if (serverState) {
        return serverState;
      }

      const response = await fetch(API_PATH, {
        credentials: "same-origin",
        headers: { Accept: "application/json" },
      });

      if (!response.ok) {
        return defaultState();
      }

      serverState = await response.json();
      return serverState;
    }

    return loadLocalState();
  }

  async function persistState(state) {
    if (isLaravel() && isServerAdmin()) {
      const response = await fetch(API_PATH, {
        method: "PUT",
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": getCsrfToken(),
        },
        body: JSON.stringify(state),
      });

      if (!response.ok) {
        throw new Error("Toodete salvestamine ebaõnnestus.");
      }

      serverState = await response.json();
      notifyChange();
      return serverState;
    }

    saveLocalState(state);
    notifyChange();
    return state;
  }

  function getBaseProducts() {
    return window.TARUKODA_PRODUCTS_BASE || window.TARUKODA_PRODUCTS || {};
  }

  function buildCatalog(state) {
    const base = getBaseProducts();
    const catalog = {};

    Object.keys(base).forEach((slug) => {
      if (state.deletedSlugs.includes(slug)) {
        return;
      }

      catalog[slug] = {
        ...base[slug],
        ...(state.overrides[slug] || {}),
        slug,
      };
    });

    Object.entries(state.overrides).forEach(([slug, product]) => {
      if (state.deletedSlugs.includes(slug) || catalog[slug]) {
        return;
      }

      catalog[slug] = { ...product, slug };
    });

    return catalog;
  }

  async function getCatalog() {
    const state = await loadState();
    return buildCatalog(state);
  }

  function syncGlobals(catalog) {
    window.TARUKODA_PRODUCTS = catalog;
    if (window.TarukodaProducts) {
      window.TarukodaProducts.get = (slug) => catalog[slug] || null;
      window.TarukodaProducts.all = () => catalog;
    }
  }

  async function refreshGlobals() {
    const catalog = await getCatalog();
    syncGlobals(catalog);
    return catalog;
  }

  function notifyChange() {
    window.dispatchEvent(new CustomEvent("tarukoda-products-changed"));
  }

  function slugify(value) {
    return String(value || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-+|-+$/g, "");
  }

  async function saveProduct(product) {
    const state = await loadState();
    const slug = slugify(product.slug || product.name);

    if (!slug) {
      throw new Error("Toote URL-i (slug) ei saanud luua.");
    }

    const entry = {
      slug,
      name: product.name.trim(),
      price: Number(product.price),
      category: product.category,
      origin_filter: product.origin_filter,
      origin: product.origin.trim(),
      weight: product.weight?.trim() || "500 g",
      short_desc: product.short_desc.trim(),
      description: product.description?.trim() || product.short_desc.trim(),
      image: product.image.trim(),
      badge: product.badge?.trim() || null,
      gallery: product.gallery || [{ src: product.image.trim(), alt: product.name.trim() }],
    };

    state.overrides[slug] = entry;
    state.deletedSlugs = state.deletedSlugs.filter((item) => item !== slug);

    await persistState(state);
    await refreshGlobals();
    return entry;
  }

  async function deleteProduct(slug) {
    const state = await loadState();

    if (!state.deletedSlugs.includes(slug)) {
      state.deletedSlugs.push(slug);
    }

    delete state.overrides[slug];
    await persistState(state);
    await refreshGlobals();
  }

  window.TarukodaAdminStore = {
    getCatalog,
    refreshGlobals,
    saveProduct,
    deleteProduct,
    slugify,
    isServerAdmin,
  };

  document.addEventListener("DOMContentLoaded", () => {
    if (window.TARUKODA_PRODUCTS && !window.TARUKODA_PRODUCTS_BASE) {
      window.TARUKODA_PRODUCTS_BASE = { ...window.TARUKODA_PRODUCTS };
    }

    refreshGlobals();
  });
})();
