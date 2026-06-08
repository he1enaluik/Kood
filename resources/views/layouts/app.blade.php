<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description', 'Tarukoda pakub ehtsat mahemett Eesti puhtast loodusest. Vaata mesi, mesilasvaha küünlad ja kinkekomplekte.')">
  <title>@yield('title', 'Tarukoda')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
  <noscript><link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@600;800&family=Inter:wght@300;400;600&display=swap" rel="stylesheet"></noscript>
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  @stack('head')
</head>
<body
  data-auth-backend="laravel"
  data-contact-endpoint="{{ route('contact.submit') }}"
  data-order-endpoint="{{ route('order.submit') }}"
  data-stripe-checkout-endpoint="{{ route('stripe.checkout') }}"
>
  @include('layouts.partials.header')

  @yield('content')

  @include('layouts.partials.footer')

  <script src="{{ asset('js/products-data.js') }}" defer></script>
  <script src="{{ asset('js/cart.js') }}" defer></script>
  <script src="{{ asset('js/search.js') }}" defer></script>
  <script src="{{ asset('js/auth.js') }}" defer></script>
  <script src="{{ asset('js/forms.js') }}" defer></script>
  <script src="{{ asset('js/site-config.js') }}" defer></script>
  <script src="{{ asset('js/stripe-checkout.js') }}" defer></script>
  <script src="{{ asset('js/mobile-nav.js') }}" defer></script>
  @stack('scripts')
</body>
</html>
