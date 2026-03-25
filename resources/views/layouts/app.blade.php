<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Permissions-Policy" content="autoplay=(self),encrypted-media=(self)">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'ReelTime')</title>
  <link rel="shortcut icon" href="{{ asset('imgs/movie.png') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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

    // Pass auth state from server to JS
   window.authUser = {{ Illuminate\Support\Js::from(Auth::check() ? [
    'id' => Auth::user()->user_id,
    'username' => Auth::user()->username,
    'email' => Auth::user()->email,
    'img' => Auth::user()->profile_image ?? 'https://robohash.org/' . urlencode(Auth::user()->username),
    'since' => optional(Auth::user()->member_since)->year ?? Auth::user()->created_at->year,
] : null) }};

    // Auto-open login modal if redirected with login_required flash
    @if(session('login_required'))
        $(document).ready(function() {
            $('.login').fadeIn(300);
        });
    @endif
  </script>
  <script src="{{ asset('js/config.js') }}"></script>
  @stack('head-scripts')
</head>

<body @yield('body-class')>
  @include('partials.header')

  @if(session('success') || session('error'))
    <div id="flash-message" class="flash-message {{ session('success') ? 'success' : 'error' }}" style="
        position: fixed;
        top: 20px;
        right: 20px;
        background: {{ session('success') ? '#4ade80' : '#f87171' }};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 100000;
        font-weight: 600;
        transition: opacity 0.5s ease;
    ">
        {{ session('success') ?? session('error') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('flash-message').style.opacity = '0';
            setTimeout(() => document.getElementById('flash-message').remove(), 500);
        }, 3000);
    </script>
  @endif

  @yield('content')

  @include('partials.footer')

  @include('partials.login-modal')

  <script src="{{ asset('js/script.js') }}" defer></script>
  <script src="{{ asset('js/scriptJQ.js') }}" defer></script>
  @stack('scripts')
</body>
</html>
