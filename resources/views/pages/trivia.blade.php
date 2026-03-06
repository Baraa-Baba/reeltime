@extends('layouts.app')

@section('title', 'Movie Games | ReelTime')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/trivia.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/trivia.js') }}" defer></script>
@endpush

@section('content')
<main class="trivia-main">
    <div class="games-hero">
      <h1>Movie Games & Trivia</h1>
      <p>Test your movie knowledge and have fun!</p>
    </div>

    <!-- Login Required Message  -->
    <div id="loginRequired" class="login-required" style="display: none;">
      <div class="notlogin">
        <div class="empty-icon"><i class="fas fa-gamepad style #8a2be2" ></i></div>
        <h3>Please Log In</h3>
        <p>You need to be logged in to play movie games and track your scores.</p>
        <a href="{{ route('home') }}" style="color: #8a2be2; text-decoration: none; font-weight: bold;">Go to Login <i class="fas fa-arrow-right #8a2be2"></i></a>
      </div>
    </div>

    <!-- Games Grid  -->
    <div id="gamesContainer" class="games-container" style="display: none;">
      <div class="games-grid">
        <!-- Game 1: Emojis -->
        <div class="game-card" data-game="guess">
          <div class="game-icon"><i class="fas fa-theater-masks" style="color: #ffd700;"></i></div>
          <h3>Emoji Challenge</h3>
          <p>Guess movies from emoji combinations</p>
          <button class="play-btn" onclick="startGuessGame()">Play Now</button>
        </div>

        <!-- Game 2: Characters -->
        <div class="game-card" data-game="character">
          <div class="game-icon"><i class="fas fa-users" style="color:#4285f4 ;"></i></div>
          <h3>Character Match</h3>
          <p>Match characters to their movies</p>
          <button class="play-btn" onclick="startCharacterGame()">Play Now</button>
        </div>

        <!-- Game 3: Quotes -->
        <div class="game-card" data-game="quotes">
          <div class="game-icon"><i class="fas fa-quote-right" style="color: #34a853 ;"></i></div>
          <h3>Movie Quotes</h3>
          <p>Identify movies from famous quotes</p>
          <button class="play-btn" onclick="startQuotesGame()">Play Now</button>
        </div>

        <!-- Game 4: Scenes -->
        <div class="game-card" data-game="scenes">
          <div class="game-icon"><i class="fas fa-film #ea4335"></i></div>
          <h3>Movie Scenes</h3>
          <p>Guess movies from scene descriptions</p>
          <button class="play-btn" onclick="startScenesGame()">Play Now</button>
        </div>

        <!-- Game 5: Coming Soon -->
<div class="game-card" data-game="coming-soon">
  <div class="game-icon"><i class="fas fa-rocket " style="color: #ff6b35;" ></i></div>
  <h3>More Games</h3>
  <p>Exciting new games are in development. Stay tuned!</p>
  <button class="play-btn" disabled>Coming Soon</button>
</div>
      </div>

      <!-- Leaderboard Section -->
      <div class="leaderboard-section">
        <h2>Top Players</h2>
        <div class="leaderboard" id="leaderboard">
          <!-- Dynamic content from JS -->
        </div>
      </div>
    </div>
  </main>

  <!-- Game Modals -->
  <div id="triviaModal" class="game-modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeGame()">&times;</span>
      <div id="game-content">
        <!-- Dynamic game content -->
      </div>
    </div>
  </div>
</main>
@endsection
</html>