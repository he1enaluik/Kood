@extends('layouts.app')

@section('title', 'Tarukoda')

@section('content')
<section class="hero" aria-label="Avalehe sissejuhatus">
    <div class="hero__media">
      <img
        class="hero__image"
        src="{{ asset('pildid/hero-taust.png') }}"
        alt=""
        width="1920"
        height="993"
        loading="lazy"
      >
      <img
        class="hero__swirl"
        src="{{ asset('Designi%20elemendid/Swirlywirly.png') }}"
        alt=""
        width="1920"
        height="163"
        loading="lazy"
      >
      <img
        class="hero__honeycomb hero__honeycomb--left"
        src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg4.png') }}"
        alt=""
        width="250"
        height="169"
        loading="lazy"
      >
      <img
        class="hero__honeycomb hero__honeycomb--right"
        src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg4.png') }}"
        alt=""
        width="269.07"
        height="187.55"
        loading="lazy"
      >
    </div>
    <div class="hero__inner">
      <div class="container">
        <div class="hero__content">
        <h1 class="hero__title">Mahemesi Eesti<br>puhtast loodusest.</h1>
        <p class="hero__text">Tarukoda on väike Eesti mesindustalu, kus austame looduse rütmi ning hoolitseme iga mesilaspere eest armastuse ja pühendumusega. Meie mesi jõuab Sinuni täpselt sellisena, nagu loodus selle loonud on.</p>
        <div class="hero__actions">
          <a href="{{ route('products') }}" class="hero__btn hero__btn--primary">Vaata tooteid</a>
          <a href="{{ route('home') }}#our-mission" class="hero__btn hero__btn--secondary">Meie missioon</a>
        </div>
        </div>
      </div>
    </div>
  </section>

  <section class="new-products" aria-labelledby="new-products-title">
    <img
      class="new-products__decor"
      src="Designi elemendid/Mesilaskärg1_SUUR.png') }}"
      alt=""
      width="220"
      height="220"
    >
    <div class="container">
      <div class="new-products__box">
        <header class="new-products__header">
          <h2 id="new-products-title" class="new-products__title">Uued tooted</h2>
          <div class="new-products__divider">
            <span class="new-products__divider-line" aria-hidden="true"></span>
            <img
              class="new-products__bee"
              src="{{ asset('Designi%20elemendid/Mesilane_V%C3%A4ike.png') }}"
              alt=""
              width="32"
              height="32"
              loading="lazy"
            >
            <span class="new-products__divider-line" aria-hidden="true"></span>
          </div>
        </header>

        <div class="new-products__grid">
        <article class="product-card">
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
            <h3 class="product-card__name">Niidumesi</h3>
            <span class="product-card__price">8,90 €</span>
          </div>
          <p class="product-card__desc">Õrn ja lilleline maitse, mis peegeldab Eesti suviseid niite.</p>
          <a href="{{ route('product.show', 'niidumesi') }}" class="product-card__btn">Vaata lähemalt</a>
        </article>

        <article class="product-card">
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
            <h3 class="product-card__name">Metsamesi</h3>
            <span class="product-card__price">8,90 €</span>
          </div>
          <p class="product-card__desc">Tugevama iseloomuga mesi metstaimede nektarist.</p>
          <a href="{{ route('product.show', 'metsamesi') }}" class="product-card__btn">Vaata lähemalt</a>
        </article>

        <article class="product-card">
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
            <h3 class="product-card__name">Pärnamesi</h3>
            <span class="product-card__price">8,90 €</span>
          </div>
          <p class="product-card__desc">Sametine tekstuur ning meeldivalt aromaatne järelmaitse.</p>
          <a href="{{ route('product.show', 'parnamesi') }}" class="product-card__btn">Vaata lähemalt</a>
        </article>

        <article class="product-card">
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
            <h3 class="product-card__name">Kinkekarp</h3>
            <span class="product-card__price">8,90 €</span>
          </div>
          <p class="product-card__desc">Kolm hoolikalt valitud meeliiki kaunis kindkepakendis.</p>
          <a href="{{ route('product.show', 'kinkekarp') }}" class="product-card__btn">Vaata lähemalt</a>
        </article>
      </div>

        <a href="{{ route('products') }}" class="new-products__link">Vaata kõiki</a>
      </div>
    </div>
  </section>

  <section class="our-mission" id="our-mission" aria-labelledby="our-mission-title">
    <img
      class="our-mission__decor our-mission__decor--left"
      src="Designi elemendid/Mesilaskärg3_SUUR.png') }}"
      alt=""
      width="220"
      loading="lazy"
    >
    <img
      class="our-mission__decor our-mission__decor--bee"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="130"
      loading="lazy"
    >
    <div class="container">
      <div class="our-mission__inner">
        <div class="our-mission__content">
          <h2 id="our-mission-title" class="our-mission__title">Meie missioon</h2>
          <p class="our-mission__text">Tarukoda eesmärk on pakkuda ehtsat mahemett, mis kannab endas Eesti looduse puhtust ja rikkalikke maitseid. Usume, et kvaliteetne mesi sünnib hoolivast mesindusest, tervetest mesilasperedest ning austusest looduse loomuliku tasakaalu vastu.</p>
          <p class="our-mission__text">Lisaks kvaliteetsete toodete loomisele soovime hoida elus Eesti mesindustraditsioone ning toetada mesilaste heaolu ka tulevaste põlvkondade jaoks. Iga Tarukoja meepurk on looduse, käsitöö ja jätkusuutliku mõtteviisi tulemus.</p>
          <a href="{{ route('contact') }}" class="our-mission__btn">Võta ühendust</a>
        </div>
        <div class="our-mission__visual">
          <div class="our-mission__hex">
            <div class="our-mission__hex-inner">
              <img
                class="our-mission__hex-image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2007_29_19%20PM.png') }}"
                alt="Mesinik kontrollib mesitaru raami"
                width="380"
                height="420"
                loading="lazy"
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="best-offers" aria-labelledby="best-offers-title">
    <div class="container">
      <header class="best-offers__header">
        <h2 id="best-offers-title" class="new-products__title">Parimad pakkumised</h2>
        <div class="best-offers__divider">
          <span class="best-offers__divider-line" aria-hidden="true"></span>
          <img
            class="best-offers__bee"
            src="{{ asset('Designi%20elemendid/Mesilane_V%C3%A4ike.png') }}"
            alt=""
            width="32"
            height="32"
            loading="lazy"
          >
          <span class="best-offers__divider-line" aria-hidden="true"></span>
        </div>
      </header>

      <div class="best-offers__slider">
        <button class="best-offers__arrow best-offers__arrow--prev" type="button" aria-label="Eelmine toode">
          <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
        </button>

        <div class="best-offers__track">
          <article class="product-card">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_50_10%20PM.png') }}"
                alt="Metsamesi"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h3 class="product-card__name">Metsamesi</h3>
              <span class="product-card__price">9,90 €</span>
            </div>
            <p class="product-card__desc">Tugevama iseloomuga mesi metsataimede nektarist.</p>
            <a href="{{ route('product.show', 'metsamesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_06_26%20PM.png') }}"
                alt="Niidumesi"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h3 class="product-card__name">Niidumesi</h3>
              <span class="product-card__price">8,90 €</span>
            </div>
            <p class="product-card__desc">Õrn ja lilleline maitse, mis peegeldab Eesti suviseid niite.</p>
            <a href="{{ route('product.show', 'niidumesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2004_55_14%20PM.png') }}"
                alt="Pärnamesi"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h3 class="product-card__name">Pärnamesi</h3>
              <span class="product-card__price">7,90 €</span>
            </div>
            <p class="product-card__desc">Sametine tekstuur ning meeldivalt aromaatne järelmaitse.</p>
            <a href="{{ route('product.show', 'parnamesi') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>

          <article class="product-card">
            <div class="product-card__media">
              <img
                class="product-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%203%2C%202026%2C%2005_04_47%20PM.png') }}"
                alt="Kinkekarp"
                width="295"
                height="359"
                loading="lazy"
              >
            </div>
            <div class="product-card__top">
              <h3 class="product-card__name">Kinkekarp</h3>
              <span class="product-card__price">25,70 €</span>
            </div>
            <p class="product-card__desc">Kolm hoolikalt valitud meeliiki kaunis kinkepakendis.</p>
            <a href="{{ route('product.show', 'kinkekarp') }}" class="product-card__btn">Vaata lähemalt</a>
          </article>
        </div>

        <button class="best-offers__arrow best-offers__arrow--next" type="button" aria-label="Järgmine toode">
          <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
        </button>
      </div>

      <a href="{{ route('products') }}" class="best-offers__link">Vaata kõiki</a>
    </div>
  </section>

  <section class="events" aria-labelledby="events-title">
    <img
      class="events__decor events__decor--bee"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="130"
      loading="lazy"
    >
    <img
      class="events__decor events__decor--honeycomb"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg2_SUUR.png') }}"
      alt=""
      width="220"
      loading="lazy"
    >
    <div class="container">
      <header class="events__header">
        <h2 id="events-title" class="new-products__title">Toimuvad sündmused</h2>
        <div class="best-offers__divider">
          <span class="best-offers__divider-line" aria-hidden="true"></span>
          <img
            class="best-offers__bee"
            src="{{ asset('Designi%20elemendid/Mesilane_V%C3%A4ike.png') }}"
            alt=""
            width="32"
            height="32"
            loading="lazy"
          >
          <span class="best-offers__divider-line" aria-hidden="true"></span>
        </div>
        <p class="events__intro our-mission__text">Hoia end kursis Tarukoja tegemiste, laatade, degusteerimiste ja hooajaliste sündmustega.</p>
      </header>

      <div class="events__slider">
        <button class="events__arrow events__arrow--prev" type="button" aria-label="Eelmine sündmus">
          <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
        </button>

        <div class="events__track">
          <article class="event-card">
            <div class="event-card__media">
              <img
                class="event-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%204%2C%202026%2C%2003_18_14%20PM.png') }}"
                alt="Mesilane lillel"
                loading="lazy"
              >
              <div class="event-card__date">
                <img class="event-card__date-icon" src="{{ asset('Ikoonid/Kalender.svg') }}" alt="" width="18" height="18">
                <span class="event-card__day">17</span>
                <span class="event-card__month">Mai</span>
                <span class="event-card__year">2026</span>
              </div>
            </div>
            <div class="event-card__body">
              <p class="event-card__time">
                <img class="event-card__time-icon" src="{{ asset('Ikoonid/Kell.svg') }}" alt="" width="16" height="16">
                10:00
              </p>
              <h3 class="product-card__name">Kevadised mesilasperede ülevaatused</h3>
              <p class="product-card__desc">Valmistame mesilaspered ette suviseks korjehooajaks.</p>
              <a href="#" class="event-card__link">Loe lähemalt <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <article class="event-card">
            <div class="event-card__media">
              <img
                class="event-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%204%2C%202026%2C%2007_20_29%20PM.png') }}"
                alt="Mesindustalu mesitarudega"
                loading="lazy"
              >
              <div class="event-card__date">
                <img class="event-card__date-icon" src="{{ asset('Ikoonid/Kalender.svg') }}" alt="" width="18" height="18">
                <span class="event-card__day">8</span>
                <span class="event-card__month">Juuni</span>
                <span class="event-card__year">2026</span>
              </div>
            </div>
            <div class="event-card__body">
              <p class="event-card__time">
                <img class="event-card__time-icon" src="{{ asset('Ikoonid/Kell.svg') }}" alt="" width="16" height="16">
                9:00
              </p>
              <h3 class="product-card__name">Avatud talude päev Tarukojas</h3>
              <p class="product-card__desc">Tule tutvu meie mesila, toodete ja mesindusega lähemalt.</p>
              <a href="#" class="event-card__link">Loe lähemalt <span aria-hidden="true">→</span></a>
            </div>
          </article>

          <article class="event-card">
            <div class="event-card__media">
              <img
                class="event-card__image"
                src="{{ asset('pildid/ChatGPT%20Image%20Jun%204%2C%202026%2C%2007_32_18%20PM.png') }}"
                alt="Meedegusteerimine meepurkidega"
                loading="lazy"
              >
              <div class="event-card__date">
                <img class="event-card__date-icon" src="{{ asset('Ikoonid/Kalender.svg') }}" alt="" width="18" height="18">
                <span class="event-card__day">22</span>
                <span class="event-card__month">Juuli6</span>
                <span class="event-card__year">2026</span>
              </div>
            </div>
            <div class="event-card__body">
              <p class="event-card__time">
                <img class="event-card__time-icon" src="{{ asset('Ikoonid/Kell.svg') }}" alt="" width="16" height="16">
                11:00
              </p>
              <h3 class="product-card__name">Meedegusteerimine ja toote tutvustus</h3>
              <p class="product-card__desc">Maitse erinevaid meeliike ja saa teada Tarukoja toodetest.</p>
              <a href="#" class="event-card__link">Loe lähemalt <span aria-hidden="true">→</span></a>
            </div>
          </article>
        </div>

        <button class="events__arrow events__arrow--next" type="button" aria-label="Järgmine sündmus">
          <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
        </button>
      </div>

      <a href="{{ route('products') }}" class="best-offers__link">Vaata kõiki</a>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    function initCarousel(trackSelector, prevSelector, nextSelector, cardSelector) {
      const track = document.querySelector(trackSelector);
      const prev = document.querySelector(prevSelector);
      const next = document.querySelector(nextSelector);

      if (!track || !prev || !next) return;

      const scrollStep = () => {
        const card = track.querySelector(cardSelector);
        if (!card) return 271;
        const gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 48;
        return card.offsetWidth + gap;
      };

      prev.addEventListener("click", () => {
        track.scrollBy({ left: -scrollStep(), behavior: "smooth" });
      });

      next.addEventListener("click", () => {
        track.scrollBy({ left: scrollStep(), behavior: "smooth" });
      });
    }

    initCarousel(".best-offers__track", ".best-offers__arrow--prev", ".best-offers__arrow--next", ".product-card");
    initCarousel(".events__track", ".events__arrow--prev", ".events__arrow--next", ".event-card");
  </script>
@endpush
