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

        <form class="products-page__toolbar" method="get" action="{{ route('products') }}" aria-label="Toote filtrid">
          <div class="products-page__toolbar-left">
            <label class="products-page__filter-label" for="products-search">Otsi:</label>
            <input
              class="products-page__search-input"
              id="products-search"
              type="search"
              name="q"
              value="{{ $filters['q'] ?? '' }}"
              placeholder="Toote nimi või kirjeldus"
            >
            <span class="products-page__filter-label">Kategooriad:</span>
            <div class="products-page__categories">
              @foreach ([
                'all' => 'Kõik',
                'mesi' => 'Mesi',
                'kunlad' => 'Mesivahaküünlad',
                'kinke' => 'Kinkekomplektid',
                'hooaeg' => 'Hooajatooted',
              ] as $value => $label)
                <button
                  type="submit"
                  name="category"
                  value="{{ $value }}"
                  class="products-page__cat-btn {{ ($filters['category'] ?? 'all') === $value ? 'is-active' : '' }}"
                >{{ $label }}</button>
              @endforeach
            </div>
          </div>
          <div class="products-page__sort-wrap">
            <label class="products-page__filter-label" for="filter-sort">Sorteeri:</label>
            <select class="products-page__sort-select" id="filter-sort" name="sort" onchange="this.form.submit()">
              <option value="default" @selected(($filters['sort'] ?? 'default') === 'default')>Enim müüdud</option>
              <option value="price-asc" @selected(($filters['sort'] ?? '') === 'price-asc')>Hind: odavam enne</option>
              <option value="price-desc" @selected(($filters['sort'] ?? '') === 'price-desc')>Hind: kallim enne</option>
            </select>
          </div>
        </form>

        <p class="products-page__count" id="products-count" aria-live="polite">
          Leitud {{ $products->total() }} toodet
        </p>

        <div class="products-page__grid" id="products-grid">
          @forelse ($products as $product)
            <x-product-card :product="$product" />
          @empty
            <p class="products-page__empty">Valitud filtritele vastavaid tooteid ei leitud.</p>
          @endforelse
        </div>

        @if ($products->hasPages())
          <nav class="products-page__pagination" aria-label="Leheküljed">
            @if ($products->onFirstPage())
              <span class="products-page__page-btn is-disabled">Eelmine</span>
            @else
              <a class="products-page__page-btn" href="{{ $products->previousPageUrl() }}">Eelmine</a>
            @endif

            <span class="products-page__page-status">
              Leht {{ $products->currentPage() }} / {{ $products->lastPage() }}
            </span>

            @if ($products->hasMorePages())
              <a class="products-page__page-btn" href="{{ $products->nextPageUrl() }}">Järgmine</a>
            @else
              <span class="products-page__page-btn is-disabled">Järgmine</span>
            @endif
          </nav>
        @endif

      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script src="{{ asset('js/admin-store.js') }}" defer></script>
  <script src="{{ asset('js/admin-crud.js') }}" defer></script>
@endpush
