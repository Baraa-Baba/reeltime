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
        <li class="nav-item">
          <a href="{{ route('home') }}" @class(['header-menu-link', 'is-active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('bookings') }}" @class(['header-menu-link rounded-pill', 'is-active' => request()->routeIs('bookings*')]) @if(request()->routeIs('bookings*')) aria-current="page" @endif>Bookings</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('trivia') }}" @class(['header-menu-link rounded-pill', 'is-active' => request()->routeIs('trivia*')]) @if(request()->routeIs('trivia*')) aria-current="page" @endif>Games</a>
        </li>
        <li class="nav-item">
          <a href="{{ route('about') }}" @class(['header-menu-link rounded-pill', 'is-active' => request()->routeIs('about')]) @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
        </li>
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

    <button
      class="navbar-toggler header-toggler d-lg-none"
      type="button"
      data-bs-toggle="offcanvas"
      data-bs-target="#headerDrawer"
      aria-controls="headerDrawer"
      aria-label="Open navigation menu"
    >
      <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>
  </div>
</header>

<div class="offcanvas offcanvas-end header-drawer d-lg-none" tabindex="-1" id="headerDrawer" aria-labelledby="headerDrawerLabel" data-bs-scroll="false" data-bs-backdrop="true">
  <div class="offcanvas-header">
    <div>
      <span class="badge text-bg-warning text-dark rounded-pill mb-2">Menu</span>
      <h2 class="h5 fw-bold mb-0" id="headerDrawerLabel">ReelTime</h2>
    </div>
    <button type="button" class="button button-secondary header-drawer-close" data-bs-dismiss="offcanvas" aria-label="Close menu">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="offcanvas-body header-drawer-body">
    <nav class="header-drawer-nav" aria-label="Mobile navigation">
      <a href="{{ route('home') }}" @class(['button button-secondary header-drawer-link rounded-pill', 'is-active' => request()->routeIs('home')]) @if(request()->routeIs('home')) aria-current="page" @endif>Home</a>
      <a href="{{ route('bookings') }}" @class(['button button-secondary header-drawer-link rounded-pill', 'is-active' => request()->routeIs('bookings*')]) @if(request()->routeIs('bookings*')) aria-current="page" @endif>Bookings</a>
      <a href="{{ route('trivia') }}" @class(['button button-secondary header-drawer-link rounded-pill', 'is-active' => request()->routeIs('trivia*')]) @if(request()->routeIs('trivia*')) aria-current="page" @endif>Games</a>
      <a href="{{ route('about') }}" @class(['button button-secondary header-drawer-link rounded-pill', 'is-active' => request()->routeIs('about')]) @if(request()->routeIs('about')) aria-current="page" @endif>About</a>
    </nav>

    <div class="header-drawer-actions">
      <a href="{{ route('search') }}" @class(['button button-secondary header-drawer-link header-action-icon rounded-pill', 'is-active' => request()->routeIs('search*')]) aria-label="Search movies" @if(request()->routeIs('search*')) aria-current="page" @endif>
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
        <span>Search</span>
      </a>

      @auth
        <button class="button button-secondary header-drawer-link header-drawer-login login-toggle-btn logged-in" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>{{ Auth::user()->username }}</span>
        </button>
        <form action="{{ route('auth.logout') }}" method="POST" class="m-0">
          @csrf
          <button class="button button-secondary header-drawer-link header-drawer-login" type="submit" aria-label="Log out">
            <i class="fas fa-sign-out-alt me-1" aria-hidden="true"></i>
            <span>Logout</span>
          </button>
        </form>
      @else
        <button class="button button-secondary header-drawer-link header-drawer-login login-toggle-btn" type="button">
          <i class="fas fa-user me-1" aria-hidden="true"></i>
          <span>Login</span>
        </button>
      @endauth
    </div>
  </div>
</div>
