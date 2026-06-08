(function () {
  const CATEGORIES = [
    { value: "mesi", label: "Mesi" },
    { value: "kunlad", label: "Mesivahaküünlad" },
    { value: "kinke", label: "Kinkekomplektid" },
    { value: "hooaeg", label: "Hooajatooted" },
  ];

  const ORIGINS = [
    { value: "poltsamaa", label: "Põltsamaa" },
    { value: "jogevamaa", label: "Jõgevamaa" },
    { value: "laane", label: "Lääne-Eesti" },
  ];

  function isAdmin() {
    if (window.TARUKODA_IS_ADMIN) {
      return true;
    }

    return window.TarukodaAuth?.isAdmin?.() === true;
  }

  function encodePath(path) {
    return window.TarukodaProducts?.encodeAssetPath?.(path) || path;
  }

  function formatPrice(price) {
    return window.TarukodaProducts?.formatPrice?.(price) || `${price} €`;
  }

  function getDetailBase() {
    const searchBase = document.querySelector(".header__search")?.dataset.productBase;
    if (searchBase) {
      return searchBase;
    }

    return document.body.dataset.authBackend === "laravel"
      ? "/toode/"
      : "toote-detail.html?toode=";
  }

  function buildCard(product) {
    const article = document.createElement("article");
    article.className = "product-card";
    article.dataset.category = product.category;
    article.dataset.origin = product.origin_filter;
    article.dataset.price = String(product.price);
    article.dataset.slug = product.slug;

    const media = document.createElement("div");
    media.className = "product-card__media";

    if (product.image) {
      const img = document.createElement("img");
      img.className = "product-card__image";
      img.src = encodePath(product.image);
      img.alt = product.name;
      img.width = 295;
      img.height = 359;
      img.loading = "lazy";
      media.appendChild(img);
    } else {
      media.classList.add("product-card__media--no-image");
      media.setAttribute("aria-hidden", "true");
    }

    if (product.badge) {
      const badge = document.createElement("span");
      badge.className = "product-card__badge";
      badge.textContent = product.badge;
      media.appendChild(badge);
    }

    const top = document.createElement("div");
    top.className = "product-card__top";

    const name = document.createElement("h2");
    name.className = "product-card__name";
    name.textContent = product.name;

    const price = document.createElement("span");
    price.className = "product-card__price";
    price.textContent = formatPrice(product.price);

    top.append(name, price);

    const desc = document.createElement("p");
    desc.className = "product-card__desc";
    desc.textContent = product.short_desc;

    const detailBase = getDetailBase();
    const link = document.createElement("a");
    link.className = "product-card__btn";
    link.textContent = "Vaata lähemalt";
    link.href = detailBase.includes("?")
      ? `${detailBase}${product.slug}`
      : `${detailBase}${product.slug}`;

    const adminActions = document.createElement("div");
    adminActions.className = "product-card__admin-actions";

    const editBtn = document.createElement("button");
    editBtn.type = "button";
    editBtn.className = "product-card__admin-btn";
    editBtn.textContent = "Muuda";
    editBtn.addEventListener("click", () => openForm(product));

    const deleteBtn = document.createElement("button");
    deleteBtn.type = "button";
    deleteBtn.className = "product-card__admin-btn product-card__admin-btn--danger";
    deleteBtn.textContent = "Kustuta";
    deleteBtn.addEventListener("click", async () => {
      if (!window.confirm(`Kas kustutada toode "${product.name}"?`)) {
        return;
      }

      await window.TarukodaAdminStore.deleteProduct(product.slug);
      await renderGrid();
    });

    adminActions.append(editBtn, deleteBtn);

    article.append(media, top, desc, link, adminActions);
    return article;
  }

  async function renderGrid() {
    const grid = document.getElementById("products-grid");
    if (!grid || !window.TarukodaAdminStore) {
      return;
    }

    const catalog = await window.TarukodaAdminStore.getCatalog();
    const products = Object.values(catalog).sort((a, b) => a.name.localeCompare(b.name, "et"));

    grid.innerHTML = "";
    products.forEach((product) => {
      grid.appendChild(buildCard(product));
    });

    if (window.TarukodaProductsFilter?.init) {
      window.TarukodaProductsFilter.init();
    }
  }

  function ensureAdminBar(box) {
    let bar = document.getElementById("admin-products-bar");

    if (bar) {
      return bar;
    }

    bar = document.createElement("div");
    bar.id = "admin-products-bar";
    bar.className = "admin-products-bar";
    bar.innerHTML = `
      <p class="admin-products-bar__title">Admin režiim — tooted</p>
      <button type="button" class="admin-products-bar__btn" id="admin-add-product">Lisa toode</button>
    `;

    const header = box.querySelector(".products-page__header");
    header?.insertAdjacentElement("afterend", bar);

    bar.querySelector("#admin-add-product")?.addEventListener("click", () => openForm());
    return bar;
  }

  function ensureDialog() {
    let dialog = document.getElementById("admin-product-dialog");

    if (dialog) {
      return dialog;
    }

    dialog = document.createElement("dialog");
    dialog.id = "admin-product-dialog";
    dialog.className = "admin-product-dialog";
    dialog.innerHTML = `
      <form method="dialog" class="admin-product-form" id="admin-product-form">
        <h2 class="admin-product-form__title" id="admin-product-form-title">Lisa toode</h2>
        <label class="admin-product-form__field">
          <span>Nimi</span>
          <input type="text" name="name" required>
        </label>
        <label class="admin-product-form__field">
          <span>Slug (URL)</span>
          <input type="text" name="slug" placeholder="Automaatselt nime põhjal">
        </label>
        <label class="admin-product-form__field">
          <span>Hind (€)</span>
          <input type="number" name="price" min="0" step="0.01" required>
        </label>
        <label class="admin-product-form__field">
          <span>Kategooria</span>
          <select name="category" required>
            ${CATEGORIES.map((item) => `<option value="${item.value}">${item.label}</option>`).join("")}
          </select>
        </label>
        <label class="admin-product-form__field">
          <span>Päritolu filter</span>
          <select name="origin_filter" required>
            ${ORIGINS.map((item) => `<option value="${item.value}">${item.label}</option>`).join("")}
          </select>
        </label>
        <label class="admin-product-form__field">
          <span>Päritolu tekst</span>
          <input type="text" name="origin" required>
        </label>
        <label class="admin-product-form__field">
          <span>Lühikirjeldus</span>
          <textarea name="short_desc" rows="2" required></textarea>
        </label>
        <label class="admin-product-form__field">
          <span>Pildi üleslaadimine</span>
          <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp" id="admin-product-image-file">
        </label>
        <label class="admin-product-form__field">
          <span>Pildi tee</span>
          <input type="text" name="image" placeholder="pildid/toode.png" id="admin-product-image-path">
        </label>
        <label class="admin-product-form__field">
          <span>Märk (nt UUS)</span>
          <input type="text" name="badge">
        </label>
        <div class="admin-product-form__actions">
          <button type="button" class="admin-product-form__cancel" id="admin-product-cancel">Tühista</button>
          <button type="submit" class="admin-product-form__submit">Salvesta</button>
        </div>
      </form>
    `;

    document.body.appendChild(dialog);

    dialog.querySelector("#admin-product-cancel")?.addEventListener("click", () => dialog.close());
    dialog.querySelector("#admin-product-form")?.addEventListener("submit", async (event) => {
      event.preventDefault();
      const form = event.currentTarget;
      const formData = new FormData(form);
      const imageFile = formData.get("image_file");

      if (imageFile && imageFile.size > 0) {
        const uploadData = new FormData();
        uploadData.append("image", imageFile);
        const uploadEndpoint =
          document.body.dataset.authBackend === "laravel"
            ? "/admin/api/products/upload-image"
            : null;

        if (uploadEndpoint) {
          const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";
          const uploadResponse = await fetch(uploadEndpoint, {
            method: "POST",
            credentials: "same-origin",
            headers: csrf ? { "X-CSRF-TOKEN": csrf } : {},
            body: uploadData,
          });
          const uploadResult = await uploadResponse.json().catch(() => ({}));
          if (!uploadResponse.ok) {
            throw new Error(uploadResult.message || "Pildi üleslaadimine ebaõnnestus.");
          }
          form.elements.image.value = uploadResult.path || "";
        }
      }

      await window.TarukodaAdminStore.saveProduct({
        slug: formData.get("slug"),
        name: formData.get("name"),
        price: formData.get("price"),
        category: formData.get("category"),
        origin_filter: formData.get("origin_filter"),
        origin: formData.get("origin"),
        short_desc: formData.get("short_desc"),
        image: formData.get("image"),
        badge: formData.get("badge"),
      });

      dialog.close();
      await renderGrid();
    });

    return dialog;
  }

  function openForm(product = null) {
    const dialog = ensureDialog();
    const form = dialog.querySelector("#admin-product-form");
    const title = dialog.querySelector("#admin-product-form-title");

    if (!form || !title) {
      return;
    }

    form.reset();
    title.textContent = product ? "Muuda toodet" : "Lisa toode";

    if (product) {
      form.elements.name.value = product.name;
      form.elements.slug.value = product.slug;
      form.elements.price.value = product.price;
      form.elements.category.value = product.category;
      form.elements.origin_filter.value = product.origin_filter;
      form.elements.origin.value = product.origin;
      form.elements.short_desc.value = product.short_desc;
      form.elements.image.value = product.image || "";
      form.elements.badge.value = product.badge || "";
    }

    dialog.showModal();
  }

  async function initAdminCrud() {
    if (!isAdmin() || !document.getElementById("products-grid")) {
      return;
    }

    const box = document.querySelector(".products-page__box");
    if (!box) {
      return;
    }

    document.body.classList.add("is-admin-products");
    ensureAdminBar(box);
    ensureDialog();
    await renderGrid();
  }

  document.addEventListener("DOMContentLoaded", () => {
    initAdminCrud();
  });

  window.addEventListener("tarukoda-auth-changed", () => {
    initAdminCrud();
  });

  window.addEventListener("tarukoda-products-changed", async () => {
    if (isAdmin()) {
      await renderGrid();
    }
  });
})();
