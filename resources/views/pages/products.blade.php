@extends('layouts.app')

@section('title', 'Tooted | Tarukoda')

@section('content')
<main class="products-page" aria-labelledby="products-page-title">
    <img
      class="products-page__decor products-page__decor--left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width="240"
    >
    <img
      class="products-page__decor products-page__decor--right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg2_SUUR.png') }}"
      alt=""
      width="220"
    >

    <div class="container">
      <div class="products-page__box">
        <header class="products-page__header">
          <h1 id="products-page-title" class="new-products__title">Tooted</h1>
          <div class="best-offers__divider">
            <span class="best-offers__divider-line" aria-hidden="true"></span>
            <img
              class="best-offers__bee"
              src="{{ asset('Designi%20elemendid/Mesilane_V%C3%A4ike.png') }}"
              alt=""
              width="32"
              height="32"
            >
            <span class="best-offers__divider-line" aria-hidden="true"></span>
          </div>
          <p class="products-page__intro">Avastage meie hoolikalt valitud mahetooted — otse Eesti loodusest Sinu lauale.</p>
        </header>

        <div class="products-page__filters" aria-label="Toote filtrid">
          <div class="products-page__filter">
            <label class="products-page__filter-label" for="filter-category">Kategooria</label>
            <select class="products-page__filter-select" id="filter-category">
              <option value="all">Kõik tooted</option>
              <option value="mesi">Mesi</option>
              <option value="kunlad">Mesilasvaha küünlad</option>
              <option value="kinke">Kinkekomplektid</option>
              <option value="hooaeg">Hooajatooted</option>
            </select>
          </div>

          <div class="products-page__filter">
            <label class="products-page__filter-label" for="filter-origin">Päritolu</label>
            <select class="products-page__filter-select" id="filter-origin">
              <option value="all">Kõik piirkonnad</option>
              <option value="poltsamaa">Põltsamaa</option>
              <option value="jogevamaa">Jõgevamaa</option>
              <option value="laane">Lääne-Eesti</option>
            </select>
          </div>

          <div class="products-page__filter">
            <label class="products-page__filter-label" for="filter-sort">Sorteeri</label>
            <select class="products-page__filter-select" id="filter-sort">
              <option value="default">Vaikimisi</option>
              <option value="price-asc">Hind: odavam enne</option>
              <option value="price-desc">Hind: kallim enne</option>
            </select>
          </div>
        </div>

        <p class="products-page__count" id="products-count" aria-live="polite"></p>

        <div class="products-page__grid" id="products-grid">
          <article class="product-card" data-category="mesi" data-origin="poltsamaa" data-price="8.90">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_06_26%20PM.png') }}"
                alt="Niidumesi"
                width="295"
                height="359"
                loading="lazy"
              >
              <span class="product-card__badge">UUS</span>
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Niidumesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Õrn ja lilleline maitse, mis peegeldab Eesti suviseid niite.</p>
            <p class="product-card__meta">Päritolu: Põltsamaa, Jõgevamaa</p>
            <a href="{{ route('product.show', 'niidumesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="mesi" data-origin="jogevamaa" data-price="8.90">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_50_10%20PM.png') }}"
                alt="Metsamesi"
                width="295"
                height="359"
                loading="lazy"
              >
              <span class="product-card__badge">UUS</span>
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Metsamesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Tugevama iseloomuga mesi metstaimede nektarist.</p>
            <p class="product-card__meta">Päritolu: Jõgevamaa</p>
            <a href="{{ route('product.show', 'metsamesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="mesi" data-origin="laane" data-price="8.90">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_55_14%20PM.png') }}"
                alt="Pärnamesi"
                width="295"
                height="359"
                loading="lazy"
              >
              <span class="product-card__badge">UUS</span>
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Pärnamesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Sametine tekstuur ning meeldivalt aromaatne järelmaitse.</p>
            <p class="product-card__meta">Päritolu: Lääne-Eesti</p>
            <a href="{{ route('product.show', 'parnamesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kinke" data-origin="poltsamaa" data-price="25.70">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2005_04_47%20PM.png') }}"
                alt="Kinkekarp"
                width="295"
                height="359"
                loading="lazy"
              >
              <span class="product-card__badge">UUS</span>
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Kinkekarp</h2>
              <span class="product-card__price">25,70 €</span>
            </div>
            <p class="product-card__desc">Kolm hoolikalt valitud meeliiki kaunis kinkepakendis.</p>
            <p class="product-card__meta">Päritolu: Põltsamaa</p>
            <a href="{{ route('product.show', 'kinkekarp') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kunlad" data-origin="poltsamaa" data-price="12.50">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_50_10%20PM.png') }}"
                alt="Mesilasvaha küünal"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Mesilasvaha küünal</h2>
              <span class="product-card__price">12,50 €</span>
            </div>
            <p class="product-card__desc">Käsitööna valmistatud looduslik vahaküünal mee aroomiga.</p>
            <p class="product-card__meta">Päritolu: Põltsamaa</p>
            <a href="{{ route('product.show', 'mesilasvaha-kuunal') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="hooaeg" data-origin="jogevamaa" data-price="9.90">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_06_26%20PM.png') }}"
                alt="Hooajaline mesi"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Hooajaline mesi</h2>
              <span class="product-card__price">9,90 €</span>
            </div>
            <p class="product-card__desc">Piiratud koguses erimaitsega mesi vastavalt hooajale.</p>
            <p class="product-card__meta">Päritolu: Jõgevamaa</p>
            <a href="{{ route('product.show', 'hooajaline-mesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>
        </div>

        <p class="products-page__empty" id="products-empty" hidden>Valitud filtritele vastavaid tooteid ei leitud.</p>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const grid = document.getElementById("products-grid");
    const cards = [...grid.querySelectorAll(".product-card")];
    const categorySelect = document.getElementById("filter-category");
    const originSelect = document.getElementById("filter-origin");
    const sortSelect = document.getElementById("filter-sort");
    const countEl = document.getElementById("products-count");
    const emptyEl = document.getElementById("products-empty");

    function applyFilters() {
      const category = categorySelect.value;
      const origin = originSelect.value;
      const sort = sortSelect.value;

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
      countEl.textContent = count === 1 ? "Näitan 1 toodet" : `Näitan ${count} toodet`;
      emptyEl.hidden = count > 0;
      grid.hidden = count === 0;
    }

    [categorySelect, originSelect, sortSelect].forEach((select) => {
      select.addEventListener("change", applyFilters);
    });

    applyFilters();
  </script>
@endpush