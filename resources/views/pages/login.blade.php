@extends('layouts.app')

@section('title', 'Logi sisse | Tarukoda')
@section('description', 'Logi sisse Tarukoja kontole, et tellimusi hallata ja ostukogemust isikupärastada.')

@section('content')
<main class="login-page" aria-labelledby="login-heading">
    <img
      class="login-page__decor login-page__decor--honeycomb-left"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1.png') }}"
      alt=""
      width="200"
      aria-hidden="true"
    >
    <img
      class="login-page__decor login-page__decor--honeycomb-right"
      src="{{ asset('Designi%20elemendid/Mesilask%C3%A4rg1_SUUR.png') }}"
      alt=""
      width="130"
      aria-hidden="true"
    >

    <div class="container">
      <div class="login-page__box">
        <h1 id="login-heading" class="login-page__heading">Logi sisse</h1>

        @if ($errors->any())
          <p class="login-page__message login-page__message--error">
            {{ $errors->first() }}
          </p>
        @endif

        <form class="login-page__panel login-page__panel--active" id="login-form" method="post" action="{{ route('login.submit') }}">
          @csrf
          <div class="login-page__field">
            <label class="visually-hidden" for="login-email">E-post</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="E-post" required autocomplete="email">
              <span class="login-page__input-icon login-page__input-icon--mail" aria-hidden="true"></span>
            </div>
          </div>
          <div class="login-page__field">
            <label class="visually-hidden" for="login-password">Parool</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="login-password" type="password" name="password" placeholder="Parool" required autocomplete="current-password" minlength="6">
              <span class="login-page__input-icon login-page__input-icon--lock" aria-hidden="true"></span>
            </div>
          </div>
          <div class="login-page__options">
            <label class="login-page__remember">
              <input type="checkbox" name="remember">
              <span>Jäta mind meelde</span>
            </label>
            <button type="button" class="login-page__forgot">Unustasid parooli?</button>
          </div>
          <button class="login-page__submit" type="submit">Logi sisse</button>
          <p class="login-page__footer">
            Pole kontot?
            <button type="button" class="login-page__switch" id="auth-switch-register">Registreeru</button>
          </p>
        </form>

        <form class="login-page__panel" id="register-form" method="post" action="{{ route('register.submit') }}">
          @csrf
          <div class="login-page__field">
            <label class="visually-hidden" for="register-name">Nimi</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="register-name" type="text" name="name" value="{{ old('name') }}" placeholder="Nimi" required autocomplete="name">
              <span class="login-page__input-icon login-page__input-icon--user" aria-hidden="true"></span>
            </div>
          </div>
          <div class="login-page__field">
            <label class="visually-hidden" for="register-email">E-post</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="register-email" type="email" name="email" value="{{ old('email') }}" placeholder="E-post" required autocomplete="email">
              <span class="login-page__input-icon login-page__input-icon--mail" aria-hidden="true"></span>
            </div>
          </div>
          <div class="login-page__field">
            <label class="visually-hidden" for="register-password">Parool</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="register-password" type="password" name="password" placeholder="Parool" required autocomplete="new-password" minlength="6">
              <span class="login-page__input-icon login-page__input-icon--lock" aria-hidden="true"></span>
            </div>
          </div>
          <div class="login-page__field">
            <label class="visually-hidden" for="register-password-confirm">Korda parooli</label>
            <div class="login-page__input-wrap">
              <input class="login-page__input" id="register-password-confirm" type="password" name="password_confirmation" placeholder="Korda parooli" required autocomplete="new-password" minlength="6">
              <span class="login-page__input-icon login-page__input-icon--lock" aria-hidden="true"></span>
            </div>
          </div>
          <button class="login-page__submit" type="submit">Loo konto</button>
          <p class="login-page__footer">
            On juba konto?
            <button type="button" class="login-page__switch" id="auth-switch-login">Logi sisse</button>
          </p>
        </form>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const loginHeading = document.getElementById("login-heading");
    const switchRegister = document.getElementById("auth-switch-register");
    const switchLogin = document.getElementById("auth-switch-login");
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");

    function switchTab(tab) {
      const isLogin = tab === "login";
      loginForm?.classList.toggle("login-page__panel--active", isLogin);
      registerForm?.classList.toggle("login-page__panel--active", !isLogin);
      if (loginHeading) {
        loginHeading.textContent = isLogin ? "Logi sisse" : "Registreeru";
      }
    }

    switchRegister?.addEventListener("click", () => switchTab("register"));
    switchLogin?.addEventListener("click", () => switchTab("login"));

    @if ($errors->has('name') || $errors->has('password_confirmation'))
      switchTab("register");
    @endif
  </script>
@endpush
