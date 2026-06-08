@extends('layouts.app')

@section('title', $product['name'] . ' | Tarukoda')
@section('description', \Illuminate\Support\Str::limit($product['short_desc'], 155))

@section('content')
<main class="product-detail" aria-labelledby="product-detail-title">
    <img
      class="product-detail__decor product-detail__decor--honeycomb-left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
      alt=""
      width="200"
    >
    <img
      class="product-detail__decor product-detail__decor--honeycomb-right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width=""
    >
    <img
      class="product-detail__decor product-detail__decor--bee-left"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="120"
    >
    <img
      class="product-detail__decor product-detail__decor--bee-right"
      src="{{ asset('Designi%20elemendid/Mesilane.png') }}"
      alt=""
      width="100"
    >

    <div class="container">
      <div class="product-detail__box">
        <div class="product-detail__inner">
          <div class="product-detail__gallery">
            @php
              $primaryImage = !empty($product['gallery'])
                ? $product['gallery'][0]
                : (!empty($product['image']) ? ['src' => $product['image'], 'alt' => $product['name']] : null);
            @endphp
            <div class="product-detail__main{{ $primaryImage ? '' : ' product-detail__main--placeholder' }}">
              @if ($primaryImage)
              <img
                class="product-detail__image"
                id="product-detail-main-image"
                src="{{ asset(\App\Data\Products::assetPath($primaryImage['src'])) }}"
                alt="{{ $primaryImage['alt'] }}"
                width="406"
                height="406"
              >
              @endif
            </div>

            @if ($primaryImage)
            <div class="product-detail__thumbs" id="product-detail-thumbs">
              @for ($i = 0; $i < 4; $i++)
              <button
                type="button"
                class="product-detail__thumb{{ $i === 0 ? ' product-detail__thumb--active' : '' }}"
                data-index="{{ $i }}"
                aria-label="Vaata pilti {{ $i + 1 }}"
              >
                <img
                  src="{{ asset(\App\Data\Products::assetPath($primaryImage['src'])) }}"
                  alt="{{ $primaryImage['alt'] }}"
                  width="94"
                  height="94"
                  loading="lazy"
                >
              </button>
              @endfor
            </div>
            @endif
          </div>

          <div class="product-detail__info">
            <div class="best-offers__divider product-detail__divider">
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

            <h1 id="product-detail-title" class="product-detail__title">{{ $product['name'] }}</h1>
            <p class="product-detail__price" id="product-detail-price">{{ \App\Data\Products::formatPrice($product['price']) }}</p>
            <p class="product-detail__desc" id="product-detail-desc">{{ $product['short_desc'] }} {{ \Illuminate\Support\Str::before($product['description'], '.') }}.</p>

            <div class="product-detail__specs">
              <p class="product-detail__spec"><span class="product-detail__spec-label">Päritolu:</span> <span id="product-detail-origin">{{ $product['origin'] }}</span></p>
              <p class="product-detail__spec"><span class="product-detail__spec-label">Kaal:</span> <span id="product-detail-weight">{{ $product['weight'] }}</span></p>
            </div>

            <div class="product-detail__actions">
              <div class="product-detail__stepper">
                <button type="button" class="product-detail__stepper-btn" id="qty-minus" aria-label="Vähenda kogust">−</button>
                <span class="product-detail__stepper-value" id="product-qty" aria-live="polite">1</span>
                <button type="button" class="product-detail__stepper-btn" id="qty-plus" aria-label="Suurenda kogust">+</button>
              </div>
              <button type="button" class="product-detail__btn" id="product-add-to-cart" data-slug="{{ $product['slug'] }}">
                Lisa korvi
                <img class="product-detail__btn-icon" src="{{ asset('Ikoonid/Ostukorv.svg') }}" alt="" width="20" height="20">
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const qtyValue = document.getElementById("product-qty");
    const minusBtn = document.getElementById("qty-minus");
    const plusBtn = document.getElementById("qty-plus");
    let quantity = 1;

    minusBtn?.addEventListener("click", () => {
      if (quantity > 1) {
        quantity -= 1;
        qtyValue.textContent = quantity;
      }
    });

    plusBtn?.addEventListener("click", () => {
      if (quantity < 20) {
        quantity += 1;
        qtyValue.textContent = quantity;
      }
    });

    document.getElementById("product-add-to-cart")?.addEventListener("click", () => {
      const slug = document.getElementById("product-add-to-cart").dataset.slug;
      TarukodaCart.addItem(slug, quantity);
      window.location.href = "{{ route('order') }}";
    });

    document.querySelectorAll(".product-detail__thumb").forEach((button) => {
      button.addEventListener("click", () => {
        document.querySelectorAll(".product-detail__thumb").forEach((el) => {
          el.classList.remove("product-detail__thumb--active");
        });
        button.classList.add("product-detail__thumb--active");
      });
    });
  </script>
@endpush
