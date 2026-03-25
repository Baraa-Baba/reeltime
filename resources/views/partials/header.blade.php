<header>
  <div class="brand">
    <div class="logo"><img src="{{ asset('imgs/movie.png') }}" alt=""></div>
    <span class="brand-name">ReelTime</span>
  </div>

  <nav class="main-nav" id="mainNav">
    <ul>
      <li><a href="{{ route('home') }}" @if(Route::currentRouteName() == 'home') style="background-color: blueviolet;" @endif>Home</a></li>
      <li><a href="{{ route('bookings') }}" @if(Route::currentRouteName() == 'bookings') style="background-color: blueviolet;" @endif>Bookings</a></li>
      <li><a href="{{ route('trivia') }}" @if(Route::currentRouteName() == 'trivia') style="background-color: blueviolet;" @endif>Games</a></li>
      <li><a href="{{ route('about') }}" @if(Route::currentRouteName() == 'about') style="background-color: blueviolet;" @endif>About</a></li>
      <li>
        @auth
          <button id="loginToggleBtn" class="login-toggle-btn logged-in">
            <i class="fas fa-user"></i> {{ Auth::user()->username }}
          </button>
        @else
          <button id="loginToggleBtn" class="login-toggle-btn">
            <i class="fas fa-user"></i> Login
          </button>
        @endauth
      </li>
    </ul>
  </nav>

  <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <div class="search">
    <a href="{{ route('search') }}" class="search-icon" id="searchToggle">
      <i class="fa-solid fa-magnifying-glass" @if(Route::currentRouteName() == 'search') style="color: blueviolet;" @endif aria-hidden="true"></i>
    </a>
  </div>
</header>
