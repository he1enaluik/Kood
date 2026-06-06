(function () {
  const PRODUCTS_KEY = "tarukoda-admin-products";
  const EVENTS_KEY = "tarukoda-admin-events";

  const DEFAULT_EVENTS = [
    {
      id: "evt-1",
      image: "pildid/ChatGPT Image Jun 4, 2026, 03_18_14 PM.png",
      alt: "Mesilane lillel",
      day: "17",
      month: "Mai",
      year: "2026",
      time: "10:00",
      title: "Kevadised mesilasperede ülevaatused",
      desc: "Valmistame mesilaspered ette suviseks korjehooajaks.",
      link: "#",
    },
    {
      id: "evt-2",
      image: "pildid/ChatGPT Image Jun 4, 2026, 07_20_29 PM.png",
      alt: "Mesindustalu mesitarudega",
      day: "8",
      month: "Juuni",
      year: "2026",
      time: "9:00",
      title: "Avatud talude päev Tarukojas",
      desc: "Tule tutvu meie mesila, toodete ja mesindusega lähemalt.",
      link: "#",
    },
    {
      id: "evt-3",
      image: "pildid/ChatGPT Image Jun 4, 2026, 07_32_18 PM.png",
      alt: "Meedegusteerimine meepurkidega",
      day: "22",
      month: "Juuli",
      year: "2026",
      time: "11:00",
      title: "Meedegusteerimine ja toote tutvustus",
      desc: "Maitse erinevaid meeliike ja saa teada Tarukoja toodetest.",
      link: "#",
    },
  ];

  function readJson(key, fallback) {
    try {
      const raw = localStorage.getItem(key);
      return raw ? JSON.parse(raw) : fallback;
    } catch {
      return fallback;
    }
  }

  function writeJson(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
  }

  function cloneProducts(source) {
    return JSON.parse(JSON.stringify(source || {}));
  }

  function getDefaultProducts() {
    return cloneProducts(window.TARUKODA_PRODUCTS || {});
  }

  function syncProductGlobals(products) {
    window.TARUKODA_PRODUCTS = products;
    if (window.TarukodaProducts) {
      window.TarukodaProducts.all = products;
      window.TarukodaProducts.get = (slug) => products[slug] || null;
    }
  }

  function getProducts() {
    const stored = readJson(PRODUCTS_KEY, null);
    const products = stored || getDefaultProducts();
    syncProductGlobals(products);
    return products;
  }

  function saveProducts(products) {
    writeJson(PRODUCTS_KEY, products);
    syncProductGlobals(products);
    window.dispatchEvent(new CustomEvent("tarukoda-products-changed"));
  }

  function getEvents() {
    return readJson(EVENTS_KEY, DEFAULT_EVENTS);
  }

  function saveEvents(events) {
    writeJson(EVENTS_KEY, events);
    window.dispatchEvent(new CustomEvent("tarukoda-events-changed"));
  }

  function slugify(text) {
    return String(text || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-|-$/g, "");
  }

  function encodePath(path) {
    return window.TarukodaProducts?.encodeAssetPath?.(path) || path;
  }

  function formatPrice(price) {
    return window.TarukodaProducts?.formatPrice?.(price) || `${Number(price).toFixed(2).replace(".", ",")} €`;
  }

  window.TarukodaAdminStore = {
    DEFAULT_EVENTS,
    getProducts,
    saveProducts,
    getEvents,
    saveEvents,
    slugify,
    encodePath,
    formatPrice,
    resetProducts() {
      localStorage.removeItem(PRODUCTS_KEY);
      syncProductGlobals(getDefaultProducts());
      window.dispatchEvent(new CustomEvent("tarukoda-products-changed"));
    },
    resetEvents() {
      localStorage.removeItem(EVENTS_KEY);
      window.dispatchEvent(new CustomEvent("tarukoda-events-changed"));
    },
  };

  getProducts();
})();
