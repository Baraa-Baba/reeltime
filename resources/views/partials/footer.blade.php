<footer class="cinema-footer">
  <div class="film-strip">
    <div class="film-perfs">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  
  <div class="footer-main">
    <div class="footer-section">
      <div class="brand">
        <div class="logo"><img src="{{ asset('imgs/movie.png') }}" alt=""></div>
        <span class="brand-name">ReelTime</span>
      </div>
      <p class="tagline">Where stories come to life</p>
    </div>
    
    <div class="footer-nav">
      <div class="nav-column">
        <strong>Movies</strong>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('bookings') }}">Bookings</a>
        <a href="{{ Auth::check() && Auth::user()->role === 'admin' ? route('admin') : route('profile') }}">My Profile</a>
        <a href="{{ route('about') }}">About Us</a>
      </div>
      
      <div class="nav-column">
        <strong>Social media</strong>
        <a href="#">Instagram</a>
        <a href="#">Facebook</a>
        <a href="#">TikTok</a>
      </div>
    </div>
  </div>
  
  <div class="footer-credits">
    <p>Made with <i class="fas fa-heart"></i> for movie lovers</p>
    <p>&copy; 2024 ReelTime Cinemas</p>
  </div>
</footer>
