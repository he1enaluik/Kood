@extends('layouts.app')

@section('title', 'Logi sisse | Tarukoda')
@section('description', 'Logi sisse Tarukoja kontole, et tellimusi hallata ja ostukogemust isikupärastada.')

@section('content')
<main class="login-page" aria-labelledby="login-heading">
    <div class="container">
      <div class="login-page__box">
        <h1 id="login-heading" class="login-page__heading">Konto</h1>
        <p class="login-page__subtitle">Logi sisse või loo uus konto</p>

        @if ($errors->any())
          <p class="login-page__message login-page__message--error">
            {{ $errors->first() }}
          </p>
        @endif

        <div class="login-page__tabs" role="tablist" aria-label="Konto tüüp">
          <button type="button" class="login-page__tab login-page__tab--active" id="auth-tab-login" role="tab" aria-selected="true">Logi sisse</button>
          <button type="button" class="login-page__tab" id="auth-tab-register" role="tab" aria-selected="false">Registreeru</button>
        </div>

        <form class="login-page__panel login-page__panel--active contact-page__form" id="login-form" method="post" action="{{ route('login.submit') }}">
          @csrf
          <label class="contact-page__field">
            <span class="contact-page__label">E-post <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
          </label>
          <label class="contact-page__field">
            <span class="contact-page__label">Parool <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="password" name="password" required autocomplete="current-password" minlength="6">
          </label>
          <button class="contact-page__submit" type="submit">Logi sisse</button>
        </form>

        <form class="login-page__panel contact-page__form" id="register-form" method="post" action="{{ route('register.submit') }}">
          @csrf
          <label class="contact-page__field">
            <span class="contact-page__label">Nimi <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
          </label>
          <label class="contact-page__field">
            <span class="contact-page__label">E-post <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
          </label>
          <label class="contact-page__field">
            <span class="contact-page__label">Parool <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="password" name="password" required autocomplete="new-password" minlength="6">
          </label>
          <label class="contact-page__field">
            <span class="contact-page__label">Korda parooli <span class="contact-page__required" aria-hidden="true">*</span></span>
            <input class="contact-page__input" type="password" name="password_confirmation" required autocomplete="new-password" minlength="6">
          </label>
          <button class="contact-page__submit" type="submit">Loo konto</button>
        </form>

        <p class="login-page__hint">
          Demo konto: <strong>demo@tarukoda.ee</strong> / <strong>tarukoda123</strong><br>
          Admin: <strong>test@test.ee</strong> / <strong>test</strong>
        </p>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const loginTab = document.getElementById("auth-tab-login");
    const registerTab = document.getElementById("auth-tab-register");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");

    function switchTab(tab) {
      const isLogin = tab === "login";
      loginForm?.classList.toggle("login-page__panel--active", isLogin);
      registerForm?.classList.toggle("login-page__panel--active", !isLogin);
      loginTab?.classList.toggle("login-page__tab--active", isLogin);
      registerTab?.classList.toggle("login-page__tab--active", !isLogin);
    }

    loginTab?.addEventListener("click", () => switchTab("login"));
    registerTab?.addEventListener("click", () => switchTab("register"));

    @if ($errors->has('name') || $errors->has('password_confirmation'))
      switchTab("register");
    @endif
  </script>
@endpush
