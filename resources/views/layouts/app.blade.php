<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Permissions-Policy" content="autoplay=(self),encrypted-media=(self)">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'ReelTime')</title>
  <link rel="shortcut icon" href="{{ asset('imgs/movie.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  @stack('styles')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    window.routes = {
        profile: "{{ route('profile') }}",
        home: "{{ route('home') }}",
        about: "{{ route('about') }}",
        bookings: "{{ route('bookings') }}",
        search: "{{ route('search') }}",
        trivia: "{{ route('trivia') }}"
    };
  </script>
  <script src="{{ asset('js/config.js') }}"></script>
  @stack('head-scripts')
</head>

<body @yield('body-class')>
  @include('partials.header')

  @yield('content')

  @include('partials.footer')

  @include('partials.login-modal')

  <script src="{{ asset('js/script.js') }}" defer></script>
  <script src="{{ asset('js/scriptJQ.js') }}" defer></script>
  @stack('scripts')
</body>
</html>
