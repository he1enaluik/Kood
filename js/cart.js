(function () {
  const STORAGE_KEY = "tarukoda-cart";
  const SHIPPING = 3.5;

  function getCart() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : [];
      return Array.isArray(parsed) ? parsed : [];
    } catch {
      return [];
    }
  }

  function saveCart(items) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    updateBadge();
    window.dispatchEvent(new CustomEvent("tarukoda-cart-updated"));
  }

  function addItem(slug, quantity) {
    const qty = Math.max(1, Math.min(20, Number(quantity) || 1));
    const cart = getCart();
    const existing = cart.find((item) => item.slug === slug);

    if (existing) {
      existing.quantity = Math.min(20, existing.quantity + qty);
    } else {
      cart.push({ slug, quantity: qty });
    }

    saveCart(cart);
    return cart;
  }

  function removeItem(slug) {
    const cart = getCart().filter((item) => item.slug !== slug);
    saveCart(cart);
    return cart;
  }

  function updateQuantity(slug, quantity) {
    const qty = Math.max(1, Math.min(20, Number(quantity) || 1));
    const cart = getCart().map((item) =>
      item.slug === slug ? { ...item, quantity: qty } : item
    );
    saveCart(cart);
    return cart;
  }

  function clearCart() {
    saveCart([]);
  }

  function getItemCount() {
    return getCart().reduce((sum, item) => sum + item.quantity, 0);
  }

  function getCartWithProducts() {
    const products = window.TARUKODA_PRODUCTS || {};

    return getCart()
      .map((item) => {
        const product = products[item.slug];
        if (!product) return null;
        return {
          ...item,
          product,
          lineTotal: product.price * item.quantity,
        };
      })
      .filter(Boolean);
  }

  function getSubtotal(items) {
    return items.reduce((sum, item) => sum + item.lineTotal, 0);
  }

  function updateBadge() {
    const count = getItemCount();
    document.querySelectorAll("[data-cart-badge]").forEach((badge) => {
      if (count > 0) {
        badge.textContent = String(count);
        badge.hidden = false;
      } else {
        badge.textContent = "";
        badge.hidden = true;
      }
    });
  }

  window.TarukodaCart = {
    STORAGE_KEY,
    SHIPPING,
    getCart,
    saveCart,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    getItemCount,
    getCartWithProducts,
    getSubtotal,
    updateBadge,
  };

  document.addEventListener("DOMContentLoaded", updateBadge);
})();
