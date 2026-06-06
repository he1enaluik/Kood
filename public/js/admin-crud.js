(function () {
  const store = () => window.TarukodaAdminStore;

  function isAdmin() {
    if (document.body.dataset.isAdmin === "true") {
      return true;
    }
    return window.TarukodaAuth?.isAdmin?.() ?? false;
  }

  function ensureAdminBar() {
    if (!isAdmin() || document.getElementById("admin-bar")) {
      return;
    }

    const bar = document.createElement("div");
    bar.id = "admin-bar";
    bar.className = "admin-bar";
    bar.innerHTML = `
      <div class="admin-bar__inner container">
        <span class="admin-bar__label">Admin režiim</span>
        <span class="admin-bar__hint">Saad tooteid ja sündmusi muuta, lisada ja kustutada.</span>
      </div>
    `;
    document.body.prepend(bar);
    document.body.classList.add("has-admin-bar");
  }

  function openModal(title, fields, values, onSubmit) {
    const overlay = document.createElement("div");
    overlay.className = "admin-modal";
    overlay.innerHTML = `
      <div class="admin-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="admin-modal-title">
        <h2 id="admin-modal-title" class="admin-modal__title">${title}</h2>
        <form class="admin-modal__form" id="admin-modal-form"></form>
        <div class="admin-modal__actions">
          <button type="button" class="admin-modal__btn admin-modal__btn--ghost" data-action="cancel">Tühista</button>
          <button type="submit" form="admin-modal-form" class="admin-modal__btn admin-modal__btn--primary">Salvesta</button>
        </div>
      </div>
    `;

    const form = overlay.querySelector("#admin-modal-form");
    fields.forEach((field) => {
      const label = document.createElement("label");
      label.className = "admin-modal__field";
      label.innerHTML = `
        <span class="admin-modal__label">${field.label}</span>
        ${
          field.type === "textarea"
            ? `<textarea name="${field.name}" rows="4" ${field.required ? "required" : ""}></textarea>`
            : `<input type="${field.type || "text"}" name="${field.name}" ${field.required ? "required" : ""}${field.step ? ` step="${field.step}"` : ""}>`
        }
      `;
      const input = label.querySelector(`[name="${field.name}"]`);
      if (values[field.name] != null) {
        input.value = values[field.name];
      }
      form.appendChild(label);
    });

    function close() {
      overlay.remove();
    }

    overlay.addEventListener("click", (event) => {
      if (event.target === overlay) {
        close();
      }
    });

    overlay.querySelector('[data-action="cancel"]').addEventListener("click", close);

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());
      onSubmit(data, close);
    });

    document.body.appendChild(overlay);
    form.querySelector("input, textarea")?.focus();
  }

  const productFields = [
    { name: "name", label: "Nimi", required: true },
    { name: "slug", label: "Slug (URL)", required: true },
    { name: "price", label: "Hind (€)", type: "number", step: "0.01", required: true },
    { name: "category", label: "Kategooria (mesi, kinke, kunlad, hooaeg)", required: true },
    { name: "origin_filter", label: "Päritolu filter (poltsamaa, jogevamaa, laane)", required: true },
    { name: "origin", label: "Päritolu tekst", required: true },
    { name: "short_desc", label: "Lühikirjeldus", type: "textarea", required: true },
    { name: "description", label: "Pikk kirjeldus", type: "textarea", required: true },
    { name: "image", label: "Pildi tee", required: true },
    { name: "badge", label: "Märk (nt UUS, tühi = puudub)" },
    { name: "weight", label: "Kaal", required: true },
  ];

  function renderProductCard(slug, product) {
    const encode = store().encodePath;
    const badge = product.badge
      ? `<span class="product-card__badge">${product.badge}</span>`
      : "";

    return `
      <article class="product-card" data-slug="${slug}" data-category="${product.category}" data-origin="${product.origin_filter}" data-price="${Number(product.price).toFixed(2)}">
        ${isAdmin() ? `<div class="admin-card-actions"><button type="button" class="admin-card-actions__btn" data-edit-product="${slug}">Muuda</button><button type="button" class="admin-card-actions__btn admin-card-actions__btn--danger" data-delete-product="${slug}">Kustuta</button></div>` : ""}
        <div class="product-card__media">
          <img class="product-card__image" src="${encode(product.image)}" alt="${product.name}" width="295" height="359" loading="lazy">
          ${badge}
        </div>
        <div class="product-card__top">
          <h2 class="product-card__name">${product.name}</h2>
          <span class="product-card__price">${store().formatPrice(product.price)}</span>
        </div>
        <p class="product-card__desc">${product.short_desc}</p>
        <p class="product-card__meta">Päritolu: ${product.origin}</p>
        <a href="toote-detail.html?toode=${encodeURIComponent(slug)}" class="product-card__btn">Vaata lähemalt</a>
      </article>
    `;
  }

  function bindProductFilters() {
    const grid = document.getElementById("products-grid");
    if (!grid) return;

    const categorySelect = document.getElementById("filter-category");
    const originSelect = document.getElementById("filter-origin");
    const sortSelect = document.getElementById("filter-sort");
    const countEl = document.getElementById("products-count");
    const emptyEl = document.getElementById("products-empty");

    function applyFilters() {
      const cards = [...grid.querySelectorAll(".product-card")];
      const category = categorySelect?.value || "all";
      const origin = originSelect?.value || "all";
      const sort = sortSelect?.value || "default";

      let visible = cards.filter((card) => {
        const matchCategory = category === "all" || card.dataset.category === category;
        const matchOrigin = origin === "all" || card.dataset.origin === origin;
        return matchCategory && matchOrigin;
      });

      if (sort === "price-asc") {
        visible.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
      } else if (sort === "price-desc") {
        visible.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
      }

      cards.forEach((card) => {
        card.hidden = !visible.includes(card);
      });
      visible.forEach((card) => grid.appendChild(card));

      const count = visible.length;
      if (countEl) {
        countEl.textContent = count === 1 ? "Näitan 1 toodet" : `Näitan ${count} toodet`;
      }
      if (emptyEl) {
        emptyEl.hidden = count > 0;
      }
      grid.hidden = count === 0;
    }

    [categorySelect, originSelect, sortSelect].forEach((select) => {
      select?.addEventListener("change", applyFilters);
    });

    applyFilters();
  }

  function renderProductsGrid() {
    const grid = document.getElementById("products-grid");
    if (!grid || !store()) return;

    const products = store().getProducts();
    grid.innerHTML = Object.entries(products)
      .map(([slug, product]) => renderProductCard(slug, product))
      .join("");

    if (isAdmin()) {
      const addWrap = document.getElementById("admin-add-product-wrap");
      if (!addWrap) {
        const wrap = document.createElement("div");
        wrap.id = "admin-add-product-wrap";
        wrap.className = "admin-add-wrap";
        wrap.innerHTML = `<button type="button" class="admin-add-btn" id="admin-add-product">+ Lisa toode</button>`;
        grid.parentElement?.insertBefore(wrap, grid);
        wrap.querySelector("#admin-add-product").addEventListener("click", () => openProductForm(null));
      }
    }

    bindProductGridActions();
    bindProductFilters();
  }

  function openProductForm(slug) {
    const products = store().getProducts();
    const existing = slug ? products[slug] : null;
    const values = existing
      ? {
          name: existing.name,
          slug,
          price: existing.price,
          category: existing.category,
          origin_filter: existing.origin_filter,
          origin: existing.origin,
          short_desc: existing.short_desc,
          description: existing.description,
          image: existing.image,
          badge: existing.badge || "",
          weight: existing.weight || "500 g",
        }
      : {
          category: "mesi",
          origin_filter: "poltsamaa",
          weight: "500 g",
          image: "pildid/ChatGPT Image Jun 3, 2026, 04_06_26 PM.png",
        };

    openModal(slug ? "Muuda toodet" : "Lisa toode", productFields, values, (data, close) => {
      const nextSlug = store().slugify(data.slug || data.name);
      if (!nextSlug) return;

      const nextProducts = { ...products };
      if (slug && slug !== nextSlug) {
        delete nextProducts[slug];
      }

      nextProducts[nextSlug] = {
        slug: nextSlug,
        name: data.name.trim(),
        price: parseFloat(data.price),
        category: data.category.trim(),
        origin_filter: data.origin_filter.trim(),
        origin: data.origin.trim(),
        short_desc: data.short_desc.trim(),
        description: data.description.trim(),
        image: data.image.trim(),
        badge: data.badge.trim() || null,
        weight: data.weight.trim(),
        gallery: existing?.gallery || [
          { src: data.image.trim(), alt: data.name.trim() },
        ],
      };

      store().saveProducts(nextProducts);
      renderProductsGrid();
      close();
    });
  }

  function bindProductGridActions() {
    document.querySelectorAll("[data-edit-product]").forEach((btn) => {
      btn.addEventListener("click", () => openProductForm(btn.dataset.editProduct));
    });

    document.querySelectorAll("[data-delete-product]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const slug = btn.dataset.deleteProduct;
        if (!confirm(`Kustuta toode "${slug}"?`)) return;
        const products = { ...store().getProducts() };
        delete products[slug];
        store().saveProducts(products);
        renderProductsGrid();
      });
    });
  }

  function renderEventCard(event) {
    const encode = store().encodePath;
    return `
      <article class="event-card" data-event-id="${event.id}">
        ${isAdmin() ? `<div class="admin-card-actions"><button type="button" class="admin-card-actions__btn" data-edit-event="${event.id}">Muuda</button><button type="button" class="admin-card-actions__btn admin-card-actions__btn--danger" data-delete-event="${event.id}">Kustuta</button></div>` : ""}
        <div class="event-card__media">
          <img class="event-card__image" src="${encode(event.image)}" alt="${event.alt}" loading="lazy">
          <div class="event-card__date">
            <img class="event-card__date-icon" src="Ikoonid/Kalender.svg" alt="" width="18" height="18">
            <span class="event-card__day">${event.day}</span>
            <span class="event-card__month">${event.month}</span>
            <span class="event-card__year">${event.year}</span>
          </div>
        </div>
        <div class="event-card__body">
          <p class="event-card__time">
            <img class="event-card__time-icon" src="Ikoonid/Kell.svg" alt="" width="16" height="16">
            ${event.time}
          </p>
          <h3 class="product-card__name">${event.title}</h3>
          <p class="product-card__desc">${event.desc}</p>
          <a href="${event.link || "#"}" class="event-card__link">Loe lähemalt <span aria-hidden="true">→</span></a>
        </div>
      </article>
    `;
  }

  const eventFields = [
    { name: "title", label: "Pealkiri", required: true },
    { name: "desc", label: "Kirjeldus", type: "textarea", required: true },
    { name: "time", label: "Kellaaeg", required: true },
    { name: "day", label: "Päev", required: true },
    { name: "month", label: "Kuu", required: true },
    { name: "year", label: "Aasta", required: true },
    { name: "image", label: "Pildi tee", required: true },
    { name: "alt", label: "Pildi alt-tekst", required: true },
    { name: "link", label: "Link" },
  ];

  function renderEvents() {
    const track = document.querySelector(".events__track");
    if (!track || !store()) return;

    const events = store().getEvents();
    track.innerHTML = events.map(renderEventCard).join("");

    if (isAdmin()) {
      const section = track.closest(".events");
      let addWrap = section?.querySelector("#admin-add-event-wrap");
      if (!addWrap && section) {
        addWrap = document.createElement("div");
        addWrap.id = "admin-add-event-wrap";
        addWrap.className = "admin-add-wrap admin-add-wrap--center";
        addWrap.innerHTML = `<button type="button" class="admin-add-btn" id="admin-add-event">+ Lisa sündmus</button>`;
        section.querySelector(".events__slider")?.after(addWrap);
        addWrap.querySelector("#admin-add-event").addEventListener("click", () => openEventForm(null));
      }
    }

    bindEventActions();
  }

  function openEventForm(id) {
    const events = store().getEvents();
    const existing = events.find((event) => event.id === id);
    const values = existing || {
      year: "2026",
      link: "#",
      image: "pildid/ChatGPT Image Jun 4, 2026, 03_18_14 PM.png",
    };

    openModal(id ? "Muuda sündmust" : "Lisa sündmus", eventFields, values, (data, close) => {
      const next = [...events];
      const payload = {
        id: existing?.id || `evt-${Date.now()}`,
        title: data.title.trim(),
        desc: data.desc.trim(),
        time: data.time.trim(),
        day: data.day.trim(),
        month: data.month.trim(),
        year: data.year.trim(),
        image: data.image.trim(),
        alt: data.alt.trim(),
        link: data.link.trim() || "#",
      };

      if (existing) {
        const index = next.findIndex((event) => event.id === existing.id);
        next[index] = payload;
      } else {
        next.push(payload);
      }

      store().saveEvents(next);
      renderEvents();
      close();
    });
  }

  function bindEventActions() {
    document.querySelectorAll("[data-edit-event]").forEach((btn) => {
      btn.addEventListener("click", () => openEventForm(btn.dataset.editEvent));
    });

    document.querySelectorAll("[data-delete-event]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.dataset.deleteEvent;
        if (!confirm("Kustuta see sündmus?")) return;
        store().saveEvents(store().getEvents().filter((event) => event.id !== id));
        renderEvents();
      });
    });
  }

  function init() {
    ensureAdminBar();
    if (document.getElementById("products-grid")) {
      renderProductsGrid();
    }
    if (document.querySelector(".events__track")) {
      renderEvents();
    }
  }

  document.addEventListener("DOMContentLoaded", init);
  window.addEventListener("tarukoda-auth-changed", init);
  window.addEventListener("tarukoda-products-changed", () => {
    if (document.getElementById("products-grid")) renderProductsGrid();
  });
  window.addEventListener("tarukoda-events-changed", renderEvents);
})();
