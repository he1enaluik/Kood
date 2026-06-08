@extends('layouts.app')

@section('title', 'Tellimus kinnitatud | Tarukoda')
@section('description', 'Sinu Tarukoja tellimus on edukalt makstud.')

@section('content')
<main class="order-page order-page--success" aria-labelledby="order-success-title">
  <div class="container">
    <div class="order-page__box order-page__box--success">
      <h1 id="order-success-title" class="order-page__heading">Aitäh tellimuse eest!</h1>
      <p class="order-page__subtitle">Makse õnnestus. Võtame sinuga peagi ühendust tarne kinnitamiseks.</p>
      <p class="order-page__success-note" id="order-success-session" hidden></p>
      <a href="{{ route('products') }}" class="contact-page__submit order-page__success-link">Tagasi toodete juurde</a>
    </div>
  </div>
</main>
@endsection

@push('scripts')
  <script src="{{ asset('js/cart.js') }}" defer></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      window.TarukodaCart?.clearCart();

      const params = new URLSearchParams(window.location.search);
      const sessionId = params.get("session_id");
      const note = document.getElementById("order-success-session");

      if (sessionId && note) {
        note.textContent = "Makse ID: " + sessionId;
        note.hidden = false;
      }
    });
  </script>
@endpush
