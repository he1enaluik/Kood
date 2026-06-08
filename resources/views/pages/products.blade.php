@extends('layouts.app')

@section('title', 'Tooted | Tarukoda')
@section('description', 'Vaata Tarukoja mahemett, mesilasvaha küünlad, kinkekomplekte ja hooajatooteid. Tellimine ja kohaletoimetamine üle Eesti.')

@section('content')
<main class="products-page" aria-labelledby="products-page-title">
    <img
      class="products-page__decor products-page__decor--left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
      alt=""
      width="200"
      aria-hidden="true"
    >
    <img
      class="products-page__decor products-page__decor--right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width="130"
      aria-hidden="true"
    >

    <div class="container">
      <div class="products-page__box">
        <header class="products-page__header">
          <h1 id="products-page-title" class="products-page__title">Meie tooted</h1>
          <p class="products-page__intro">Pakume parimat ja värskeimat mahemett otse Eesti loodusest — iga purk on tõend meie mesilasperede hoolikast tööst ja puhtast mahepõllundusest.</p>
        </header>

        <div class="products-page__toolbar" aria-label="Toote filtrid">
          <div class="products-page__toolbar-left">
            <span class="products-page__filter-label">Kategooriad:</span>
            <div class="products-page__categories">
              <button type="button" class="products-page__cat-btn is-active" data-filter-category="all">Kõik</button>
              <button type="button" class="products-page__cat-btn" data-filter-category="mesi">Mesi</button>
              <button type="button" class="products-page__cat-btn" data-filter-category="kunlad">Mesivahaküünlad</button>
              <button type="button" class="products-page__cat-btn" data-filter-category="kinke">Kinkekomplektid</button>
              <button type="button" class="products-page__cat-btn" data-filter-category="hooaeg">Hooajatooted</button>
            </div>
          </div>
          <div class="products-page__sort-wrap">
            <label class="products-page__filter-label" for="filter-sort">Sorteeri:</label>
            <select class="products-page__sort-select" id="filter-sort">
              <option value="default">Enim müüdud</option>
              <option value="price-asc">Hind: odavam enne</option>
              <option value="price-desc">Hind: kallim enne</option>
            </select>
          </div>
          <input type="hidden" id="filter-category" value="all">
          <input type="hidden" id="filter-origin" value="all">
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
            <a href="{{ route('product.show', 'kinkekarp') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="hooaeg" data-origin="poltsamaa" data-price="8.90">
            <div class="product-card__media product-card__media--no-image" aria-hidden="true"></div>
            <div class="product-card__top">
              <h2 class="product-card__name">Kevademesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Kerge ja õrn kevadine mesi varakevadiste õitelillede nektarist.</p>
            <a href="{{ route('product.show', 'kevademesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="hooaeg" data-origin="poltsamaa" data-price="8.90">
            <div class="product-card__media product-card__media--no-image" aria-hidden="true"></div>
            <div class="product-card__top">
              <h2 class="product-card__name">Suvemesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Lilleline ja mitmekesine suvine mesi Eesti põldudelt ja niitudelt.</p>
            <a href="{{ route('product.show', 'suvemesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="hooaeg" data-origin="jogevamaa" data-price="8.90">
            <div class="product-card__media product-card__media--no-image" aria-hidden="true"></div>
            <div class="product-card__top">
              <h2 class="product-card__name">Sügisemesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Täismoka sügisene mesi hilisema hooaja õite ja metstaimede nektarist.</p>
            <a href="{{ route('product.show', 'sugisemesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="hooaeg" data-origin="poltsamaa" data-price="8.90">
            <div class="product-card__media product-card__media--no-image" aria-hidden="true"></div>
            <div class="product-card__top">
              <h2 class="product-card__name">Talvemesi</h2>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Tugevama iseloomuga mesi, mis sobib hästi talveperioodiks.</p>
            <a href="{{ route('product.show', 'talvemesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kunlad" data-origin="poltsamaa" data-price="12.50">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/tarukujulinek%C3%BC%C3%BCnal.JPG') }}"
                alt="Tarukujuline küünal"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Tarukujuline küünal</h2>
              <span class="product-card__price">12,50 €</span>
            </div>
            <p class="product-card__desc">Käsitöö tarukujuline mesilasvaha küünal sooja mee aroomiga.</p>
            <a href="{{ route('product.show', 'tarukujuline-kuunal') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kunlad" data-origin="poltsamaa" data-price="12.50">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ribik%C3%BC%C3%BCnal.png') }}"
                alt="Ribiküünal"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Ribiküünal</h2>
              <span class="product-card__price">12,50 €</span>
            </div>
            <p class="product-card__desc">Ribikujuline mesilasvaha küünal käsitööna valmistatud.</p>
            <a href="{{ route('product.show', 'ribikuunal') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kunlad" data-origin="poltsamaa" data-price="12.50">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/mesilastaruk%C3%BC%C3%BCnal.JPG') }}"
                alt="Mesilastaruküünal"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Mesilastaruküünal</h2>
              <span class="product-card__price">12,50 €</span>
            </div>
            <p class="product-card__desc">Mesilastaruga kaunistatud mesilasvaha küünal.</p>
            <a href="{{ route('product.show', 'mesilastarukuanal') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card" data-category="kunlad" data-origin="poltsamaa" data-price="12.50">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/lilleest.png') }}"
                alt="Lillküünal"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h2 class="product-card__name">Lillküünal</h2>
              <span class="product-card__price">12,50 €</span>
            </div>
            <p class="product-card__desc">Lillekujuline mesilasvaha küünal käsitööna valmistatud.</p>
            <a href="{{ route('product.show', 'lillkuunal') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>
        </div>

        <p class="products-page__empty" id="products-empty" hidden>Valitud filtritele vastavaid tooteid ei leitud.</p>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script src="{{ asset('js/products-filter.js') }}?v=5" defer></script>
@endpush
