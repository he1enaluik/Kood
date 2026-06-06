@extends('layouts.app')

@section('title', 'Kontakt | Tarukoda')
@section('description', 'Võta Tarukodaga ühendust. Küsimused meie mahemee, tellimiste või koostöö kohta – vastame esimesel võimalusel.')

@section('content')
<main class="contact-page" aria-labelledby="contact-heading">
    <img
      class="contact-page__decor contact-page__decor--honeycomb-left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width="280"
    >
    <img
      class="contact-page__decor contact-page__decor--honeycomb-right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg2_SUUR.png') }}"
      alt=""
      width="260"
    >
    <img
      class="contact-page__decor contact-page__decor--bee-left"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="120"
    >
    <img
      class="contact-page__decor contact-page__decor--bee-right"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="100"
    >

    <div class="container">
      <div class="contact-page__box">
        <header class="contact-page__header">
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
        </header>

        <div class="contact-page__inner">
          <div class="contact-page__info">
            <h1 id="contact-heading" class="contact-page__heading">Võta ühendust</h1>
            <p class="contact-page__subtitle">Meeleldi vastame sulle</p>
            <p class="contact-page__text">Soovid rohkem teada meie mahemee, toodete või tellimisvõimaluste kohta? Saada meile sõnum ja vastame esimesel võimalusel.</p>

            <a href="mailto:info@tarukoda.ee" class="contact-page__email">info@tarukoda.ee</a>

            <div class="contact-page__social">
              <a href="#" class="contact-page__social-link" aria-label="TikTok">
                <img src="{{ asset('Ikoonid/tiktok.svg') }}" alt="" width="32" height="32">
              </a>
              <a href="#" class="contact-page__social-link" aria-label="Instagram">
                <img src="{{ asset('Ikoonid/instagram.svg') }}" alt="" width="32" height="32">
              </a>
              <a href="#" class="contact-page__social-link" aria-label="Facebook">
                <img src="{{ asset('Ikoonid/facebook.svg') }}" alt="" width="32" height="32">
              </a>
            </div>
          </div>

          <form class="contact-page__form" method="post" action="{{ route('contact.submit') }}">
            @csrf

            @if (session('success'))
              <p class="form-message form-message--success">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
              <p class="form-message form-message--error">{{ $errors->first() }}</p>
            @endif

            <div class="contact-page__form-row">
              <label class="contact-page__field">
                <span class="contact-page__label">Eesnimi</span>
                <input class="contact-page__input" type="text" name="firstname" value="{{ old('firstname') }}" autocomplete="given-name">
              </label>
              <label class="contact-page__field">
                <span class="contact-page__label">Perekonnanimi</span>
                <input class="contact-page__input" type="text" name="lastname" value="{{ old('lastname') }}" autocomplete="family-name">
              </label>
            </div>

            <label class="contact-page__field">
              <span class="contact-page__label">Email <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Sõnum <span class="contact-page__required" aria-hidden="true">*</span></span>
              <textarea class="contact-page__input contact-page__textarea" name="message" required>{{ old('message') }}</textarea>
            </label>

            <button class="contact-page__submit" type="submit">Saada</button>
          </form>
        </div>

        <img
          class="contact-page__box-decor"
          src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
          alt=""
          width="90"
          loading="lazy"
        >
      </div>
    </div>
  </main>
@endsection
