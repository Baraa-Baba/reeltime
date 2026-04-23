@php
  $primaryNavItems = [
    ['label' => 'Home', 'route' => route('home'), 'match' => 'home', 'icon' => 'fa-solid fa-house'],
    ['label' => 'Bookings', 'route' => route('bookings'), 'match' => 'bookings*', 'icon' => 'fa-solid fa-ticket-simple'],
    ['label' => 'Games', 'route' => route('trivia'), 'match' => 'trivia*', 'icon' => 'fa-solid fa-gamepad'],
    ['label' => 'About', 'route' => route('about'), 'match' => 'about', 'icon' => 'fa-solid fa-circle-info'],
  ];

  $mobileNavItems = [
    ['label' => 'Home', 'route' => route('home'), 'match' => 'home', 'icon' => 'fa-solid fa-house'],
    ['label' => 'Search', 'route' => route('search'), 'match' => 'search*', 'icon' => 'fa-solid fa-magnifying-glass'],
    ['label' => 'Bookings', 'route' => route('bookings'), 'match' => 'bookings*', 'icon' => 'fa-solid fa-ticket-simple'],
    ['label' => 'Games', 'route' => route('trivia'), 'match' => 'trivia*', 'icon' => 'fa-solid fa-gamepad'],
    ['label' => 'About', 'route' => route('about'), 'match' => 'about', 'icon' => 'fa-solid fa-circle-info'],
  ];
@endphp

<header class="navbar navbar-dark site-header surface-card">
  <div class="container-fluid nav-shell px-3 px-lg-4">
    <a href="{{ route('home') }}" class="navbar-brand brand" aria-label="ReelTime home">
      <span class="logo"><img src="{{ asset('imgs/movie.png') }}" alt="ReelTime logo"></span>
      <span class="brand-copy">
        <span class="brand-name">ReelTime</span>
        <small>cinema, curated</small>
      </span>
    </a>

    <nav class="main-nav d-none d-lg-flex" aria-label="Primary navigation">
      <ul class="navbar-nav flex-row align-items-center justify-content-center">
        @foreach($primaryNavItems as $item)
          <li class="nav-item">
            <a href="{{ $item['route'] }}" @class(['header-menu-link', 'is-active' => request()->routeIs($item['match'])]) @if(request()->routeIs($item['match'])) aria-current="page" @endif>{{ $item['label'] }}</a>
          </li>
        @endforeach
      </ul>
    </nav>

    <div class="header-actions d-none d-lg-flex">
      <a href="{{ route('search') }}" @class(['button button-secondary header-action-btn header-action-icon', 'is-active' => request()->routeIs('search*')]) aria-label="Search movies" @if(request()->routeIs('search*')) aria-current="page" @endif>
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <span class="visually-hidden">Search</span>
      </a>

      @auth
        <button class="button button-secondary header-action-btn login-toggle-btn logged-in" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>{{ Auth::user()->username }}</span>
        </button>
        <form action="{{ route('auth.logout') }}" method="POST" class="m-0 header-logout-form">
          @csrf
          <button class="button button-secondary header-action-btn header-logout-btn" type="submit" aria-label="Log out">
            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
            <span class="header-logout-label">Logout</span>
          </button>
        </form>
      @else
        <button class="button button-secondary header-action-btn login-toggle-btn" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>Login</span>
        </button>
      @endauth
    </div>

    <div class="header-mobile-actions d-lg-none">
      @auth
        <button class="button button-secondary header-action-btn header-mobile-login login-toggle-btn logged-in" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>{{ Auth::user()->username }}</span>
        </button>
      @else
        <button class="button button-secondary header-action-btn header-mobile-login login-toggle-btn" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>Login</span>
        </button>
      @endauth
    </div>
  </div>
</header>

<nav class="mobile-bottom-nav d-lg-none" aria-label="Bottom navigation">
  @foreach($mobileNavItems as $item)
    <a href="{{ $item['route'] }}" @class(['mobile-bottom-nav-link', 'is-active' => request()->routeIs($item['match'])]) @if(request()->routeIs($item['match'])) aria-current="page" @endif>
      <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
      <span>{{ $item['label'] }}</span>
    </a>
  @endforeach
</nav>
