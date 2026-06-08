@props(['product'])

<article
  class="product-card"
  data-category="{{ $product['category'] }}"
  data-origin="{{ $product['origin_filter'] }}"
  data-price="{{ $product['price'] }}"
  data-slug="{{ $product['slug'] }}"
>
  <div class="product-card__media {{ empty($product['image']) ? 'product-card__media--no-image' : '' }}">
    @if (!empty($product['image']))
      <img
        class="product-card__image"
        src="{{ asset(\App\Data\Products::assetPath($product['image'])) }}"
        alt="{{ $product['name'] }}"
        width="295"
        height="359"
        loading="lazy"
      >
    @endif
    @if (!empty($product['badge']))
      <span class="product-card__badge">{{ $product['badge'] }}</span>
    @endif
  </div>
  <div class="product-card__top">
    <h2 class="product-card__name">{{ $product['name'] }}</h2>
    <span class="product-card__price">{{ \App\Data\Products::formatPrice((float) $product['price']) }}</span>
  </div>
  <p class="product-card__desc">{{ $product['short_desc'] }}</p>
  <a href="{{ route('product.show', $product['slug']) }}" class="product-card__btn">Vaata lähemalt</a>
</article>
