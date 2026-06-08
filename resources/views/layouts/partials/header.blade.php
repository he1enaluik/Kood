<header class="header">
  <div class="header__inner container">
    <a href="{{ route('home') }}" class="header__logo" aria-label="Tarukoda avaleht">
      <span class="header__logo-icon" aria-hidden="true"></span>
      <span class="header__logo-text">TARUKODA</span>
    </a>

    <button
      type="button"
      class="header__menu-toggle"
      id="header-menu-toggle"
      aria-label="Ava menüü"
      aria-expanded="false"
      aria-controls="header-mobile-panel"
    >
      <span class="header__menu-toggle-bar" aria-hidden="true"></span>
      <span class="header__menu-toggle-bar" aria-hidden="true"></span>
      <span class="header__menu-toggle-bar" aria-hidden="true"></span>
    </button>

    <nav class="header__nav" aria-label="Peamenüü">
      <ul class="header__menu">
        <li><a href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Avaleht</a></li>
        <li><a href="{{ route('products') }}" @if(request()->routeIs('products', 'product.show')) aria-current="page" @endif>Tooted</a></li>
        <li><a href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Kontakt</a></li>
        <li><a href="{{ route('home') }}#our-mission">Meist</a></li>
      </ul>

      <div class="header__actions">
        <div class="header__search" data-product-base="{{ url('/toode') }}/">
          <button
            type="button"
            class="header__search-toggle"
            id="header-search-toggle"
            aria-label="Ava otsing"
            aria-expanded="false"
            aria-controls="header-search-panel"
          >
            <span class="header__icon header__icon--search" aria-hidden="true"></span>
          </button>
          <div class="header__search-panel" id="header-search-panel">
            <label class="visually-hidden" for="header-search">Otsi tooteid</label>
            <input
              type="search"
              class="header__search-input"
              id="header-search"
              placeholder="Otsi tooteid..."
              autocomplete="off"
              aria-controls="header-search-results"
              aria-expanded="false"
              aria-autocomplete="list"
            >
          </div>
          <div class="header__search-dropdown" id="header-search-results" role="listbox" hidden></div>
        </div>

        <a href="{{ route('order') }}" class="header__cart-wrap" aria-label="Ostukorv">
          <span class="header__icon header__icon--cart"></span>
          <span class="header__cart-badge" data-cart-badge hidden></span>
        </a>

        @guest
          <div class="header__profile" data-login-url="{{ route('login') }}" data-home-url="{{ route('home') }}">
            <a href="{{ route('login') }}" class="header__profile-link" id="header-profile-link" aria-label="Logi sisse">
              <span class="header__icon header__icon--profile"></span>
            </a>
          </div>
        @else
          <div class="header__profile" data-home-url="{{ route('home') }}">
            <a href="#" class="header__profile-link" id="header-profile-link" aria-label="Profiil: {{ Auth::user()->name }}" data-logged-in="true">
              <span class="header__icon header__icon--profile"></span>
            </a>
            <div class="header__profile-menu" id="header-profile-menu" hidden>
              <p class="header__profile-greeting">Tere, <span id="header-profile-name">{{ Auth::user()->name }}</span>!</p>
              <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="header__profile-logout">Logi välja</button>
              </form>
            </div>
          </div>
        @endguest
      </div>
    </nav>
  </div>

  <div class="header__mobile-panel" id="header-mobile-panel" hidden>
    <nav class="header__mobile-nav" aria-label="Mobiilimenüü">
      <ul class="header__mobile-menu">
        <li><a href="{{ route('home') }}">Avaleht</a></li>
        <li><a href="{{ route('products') }}">Tooted</a></li>
        <li><a href="{{ route('contact') }}">Kontakt</a></li>
        <li><a href="{{ route('home') }}#our-mission">Meist</a></li>
      </ul>
    </nav>
  </div>
</header>
