<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Tarukoda')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Abhaya+Libre:wght@600;800&family=Inter:wght@300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('style.css') }}">
  @stack('head')
</head>
<body data-auth-backend="laravel">
  @include('layouts.partials.header')

  @yield('content')

  @include('layouts.partials.footer')

  <script src="{{ asset('js/products-data.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/search.js') }}"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
  @stack('scripts')
</body>
</html>
