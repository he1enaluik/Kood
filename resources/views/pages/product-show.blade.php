@extends('layouts.app')

@section('title', $product['name'] . ' | Tarukoda')
@section('description', \Illuminate\Support\Str::limit($product['description'], 155))

@section('content')
<main class="product-detail" aria-labelledby="product-detail-title">
    <div class="container">
      <a href="{{ route('products') }}" class="product-detail__back">
        <img src="{{ asset('Ikoonid/arrow_back_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="20" height="20">
        Tagasi toodete juurde
      </a>

      <div class="product-detail__box">
        <div class="product-detail__inner{{ empty($product['gallery']) ? ' product-detail__inner--no-gallery' : '' }}">
          @if (!empty($product['gallery']))
          <div class="product-detail__gallery">
            <div class="product-detail__main">
              <img
                class="product-detail__image"
                id="product-detail-main-image"
                src="{{ asset(\App\Data\Products::assetPath($product['gallery'][0]['src'])) }}"
                alt="{{ $product['gallery'][0]['alt'] }}"
                width="406"
                height="400"
              >
              <button class="product-detail__arrow product-detail__arrow--prev" type="button" aria-label="Eelmine pilt">
                <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
              </button>
              <button class="product-detail__arrow product-detail__arrow--next" type="button" aria-label="Järgmine pilt">
                <img src="{{ asset('Ikoonid/arrow_back_ios_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" width="24" height="24">
              </button>
            </div>

            <div class="product-detail__thumbs" id="product-detail-thumbs" role="tablist" aria-label="Toote pildid">
              @foreach ($product['gallery'] as $index => $image)
              <button
                type="button"
                class="product-detail__thumb{{ $index === 0 ? ' is-active' : '' }}"
                role="tab"
                aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                aria-controls="product-detail-main-image"
                data-image="{{ asset(\App\Data\Products::assetPath($image['src'])) }}"
                data-alt="{{ $image['alt'] }}"
              >
                <img
                  src="{{ asset(\App\Data\Products::assetPath($image['src'])) }}"
                  alt=""
                  width="88"
                  height="88"
                >
              </button>
              @endforeach
            </div>
          </div>
          @endif

          <div class="product-detail__info">
            <div class="section-divider product-detail__divider">
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

            <h1 id="product-detail-title" class="product-detail__title">{{ $product['name'] }}</h1>
            <p class="product-detail__price" id="product-detail-price">{{ \App\Data\Products::formatPrice($product['price']) }}</p>
            <p class="product-detail__desc" id="product-detail-desc">{{ $product['description'] }}</p>

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
    const mainImage = document.getElementById("product-detail-main-image");
    const thumbs = document.querySelectorAll(".product-detail__thumb");
    const prevBtn = document.querySelector(".product-detail__arrow--prev");
    const nextBtn = document.querySelector(".product-detail__arrow--next");

    @if (!empty($product['gallery']))
    function selectImage(index) {
      const thumb = thumbs[index];
      if (!thumb || !mainImage) return;

      mainImage.src = thumb.dataset.image;
      mainImage.alt = thumb.dataset.alt;

      thumbs.forEach((item, i) => {
        item.classList.toggle("is-active", i === index);
        item.setAttribute("aria-selected", i === index ? "true" : "false");
      });
    }

    function getActiveIndex() {
      return [...thumbs].findIndex((thumb) => thumb.classList.contains("is-active"));
    }

    thumbs.forEach((thumb, index) => {
      thumb.addEventListener("click", () => selectImage(index));
    });

    prevBtn?.addEventListener("click", () => {
      const activeIndex = getActiveIndex();
      selectImage((activeIndex - 1 + thumbs.length) % thumbs.length);
    });

    nextBtn?.addEventListener("click", () => {
      const activeIndex = getActiveIndex();
      selectImage((activeIndex + 1) % thumbs.length);
    });
    @endif

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
  </script>
@endpush
