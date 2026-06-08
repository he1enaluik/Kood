@extends('layouts.app')

@section('title', 'Tellimus | Tarukoda')
@section('description', 'Esita Tarukoja toodete tellimus. Täida andmed ja saadame mahemee ja mesindustooted Sinu aadressile.')

@section('content')
<main class="order-page" aria-labelledby="order-title" data-products-url="{{ route('products') }}">
    <img
      class="order-page__decor order-page__decor--honeycomb-left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
      alt=""
      width="200"
      aria-hidden="true"
    >
    <img
      class="order-page__decor order-page__decor--honeycomb-right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width="130"
      aria-hidden="true"
    >
    <img
      class="order-page__decor order-page__decor--bee-left"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="120"
    >
    <img
      class="order-page__decor order-page__decor--bee-right"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="100"
    >

    <div class="container">
      <div class="order-page__box">
        <header class="order-page__header">
          <div class="section-divider">
            <span class="section-divider__line" aria-hidden="true"></span>
            <img
              class="section-divider__bee"
              src="{{ asset('Designi%20elemendid/Mesilane_V%C3%A4ike.png') }}"
              alt=""
              width="32"
              height="32"
            >
            <span class="section-divider__line" aria-hidden="true"></span>
          </div>
        </header>

        <div class="order-page__inner">
          <div class="order-page__summary">
            <h1 id="order-title" class="order-page__heading">Tellimus</h1>
            <p class="order-page__subtitle">Kontrolli tellimust ja täida andmed</p>

            <div id="order-cart-items"></div>

            <p id="order-cart-empty" class="order-page__empty" hidden>
              Ostukorv on tühi. <a href="{{ route('products') }}">Vaata tooteid</a>
            </p>

            <button type="button" class="order-page__promo" id="order-promo-toggle" hidden>Sisesta sooduskood</button>

            <div class="order-page__totals" id="order-cart-totals"></div>
          </div>

          <form class="order-page__form" method="post" action="{{ route('order.submit') }}">
            @csrf

            @if (session('success'))
              <p class="form-message form-message--success">{{ session('success') }}</p>
            @endif

            @if ($errors->any())
              <p class="form-message form-message--error">{{ $errors->first() }}</p>
            @endif

            <div class="order-page__form-row">
              <label class="contact-page__field">
                <span class="contact-page__label">Eesnimi <span class="contact-page__required" aria-hidden="true">*</span></span>
                <input class="contact-page__input" type="text" name="firstname" required autocomplete="given-name">
              </label>
              <label class="contact-page__field">
                <span class="contact-page__label">Perekonnanimi <span class="contact-page__required" aria-hidden="true">*</span></span>
                <input class="contact-page__input" type="text" name="lastname" required autocomplete="family-name">
              </label>
            </div>

            <label class="contact-page__field">
              <span class="contact-page__label">Email <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="email" name="email" required autocomplete="email">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Telefon <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="tel" name="phone" required autocomplete="tel" inputmode="numeric" pattern="[0-9]+" title="Sisesta ainult numbrid">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Tarneaadress <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="text" name="address" required autocomplete="street-address">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Linn <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="text" name="city" required autocomplete="address-level2">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Postiindeks <span class="contact-page__required" aria-hidden="true">*</span></span>
              <input class="contact-page__input" type="text" name="postcode" required autocomplete="postal-code">
            </label>

            <label class="contact-page__field">
              <span class="contact-page__label">Märkused</span>
              <textarea class="contact-page__input contact-page__textarea" name="notes"></textarea>
            </label>

            <button class="contact-page__submit" type="submit">Esita tellimus</button>
          </form>
        </div>

        <img
          class="order-page__box-decor"
          src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
          alt=""
          width="90"
          loading="lazy"
        >
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script src="{{ asset('js/order-page.js') }}" defer></script>
  <script>
    const phoneInput = document.querySelector('input[name="phone"]');

    if (phoneInput) {
      phoneInput.addEventListener("input", () => {
        phoneInput.value = phoneInput.value.replace(/\D/g, "");
      });

      phoneInput.addEventListener("paste", (event) => {
        event.preventDefault();
        const pasted = (event.clipboardData || window.clipboardData).getData("text");
        phoneInput.value = (phoneInput.value + pasted).replace(/\D/g, "");
      });
    }
  </script>
@endpush
