@extends('layouts.app')

@section('title', 'Movie Games | ReelTime')

@section('body-class')
trivia-page
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/trivia.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/trivia.js') }}" defer></script>
@endpush

@section('content')
<main class="container py-4 py-lg-5 trivia-main">
  <section class="card border-secondary-subtle shadow-sm bg-dark mb-4">
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">Movie games</span>
          <h1 class="display-6 fw-bold mb-3">Test your movie memory and keep the night going.</h1>
          <p class="text-secondary mb-0">Play through four quick game modes, earn points, and climb the leaderboard.</p>
        </div>

        <div class="col-lg-5">
          <div class="row g-2 row-cols-1 row-cols-sm-3">
            <div class="col">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="small text-warning fw-bold text-uppercase mb-1">01</div>
                  <h2 class="h6 fw-bold mb-1">Emoji challenge</h2>
                  <p class="text-secondary small mb-0">Guess the movie from a tiny clue.</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="small text-warning fw-bold text-uppercase mb-1">02</div>
                  <h2 class="h6 fw-bold mb-1">Quote mode</h2>
                  <p class="text-secondary small mb-0">Match famous lines to the right film.</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="small text-warning fw-bold text-uppercase mb-1">03</div>
                  <h2 class="h6 fw-bold mb-1">Leaderboard</h2>
                  <p class="text-secondary small mb-0">See who is keeping score across the app.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="loginRequired" class="card border-secondary-subtle shadow-sm bg-dark" style="display: none;">
    <div class="card-body p-4 text-center">
      <div class="display-6 text-warning mb-3"><i class="fas fa-gamepad"></i></div>
      <h3 class="h4 fw-bold">Please log in</h3>
      <p class="text-secondary mb-3">You need to be signed in to play movie games and track your scores.</p>
      <a href="{{ route('home') }}" class="btn btn-outline-warning fw-semibold">Go to login <i class="fas fa-arrow-right ms-1"></i></a>
    </div>
  </div>

  <div id="gamesContainer" class="d-grid gap-4" style="display: none;">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
      <div class="col">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100" data-game="guess">
          <div class="card-body d-grid gap-3">
            <div class="display-6 text-warning"><i class="fas fa-theater-masks"></i></div>
            <div>
              <h3 class="h5 fw-bold">Emoji challenge</h3>
              <p class="text-secondary mb-0">Guess movies from emoji combinations.</p>
            </div>
            <button class="btn btn-warning fw-semibold" type="button" onclick="startGuessGame()">Play now</button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100" data-game="character">
          <div class="card-body d-grid gap-3">
            <div class="display-6 text-warning"><i class="fas fa-users"></i></div>
            <div>
              <h3 class="h5 fw-bold">Character match</h3>
              <p class="text-secondary mb-0">Match characters to their movies.</p>
            </div>
            <button class="btn btn-warning fw-semibold" type="button" onclick="startCharacterGame()">Play now</button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100" data-game="quotes">
          <div class="card-body d-grid gap-3">
            <div class="display-6 text-warning"><i class="fas fa-quote-right"></i></div>
            <div>
              <h3 class="h5 fw-bold">Movie quotes</h3>
              <p class="text-secondary mb-0">Identify movies from famous quotes.</p>
            </div>
            <button class="btn btn-warning fw-semibold" type="button" onclick="startQuotesGame()">Play now</button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100" data-game="scenes">
          <div class="card-body d-grid gap-3">
            <div class="display-6 text-warning"><i class="fas fa-film"></i></div>
            <div>
              <h3 class="h5 fw-bold">Movie scenes</h3>
              <p class="text-secondary mb-0">Guess movies from scene descriptions.</p>
            </div>
            <button class="btn btn-warning fw-semibold" type="button" onclick="startScenesGame()">Play now</button>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100" data-game="coming-soon">
          <div class="card-body d-grid gap-3">
            <div class="display-6 text-warning"><i class="fas fa-rocket"></i></div>
            <div>
              <h3 class="h5 fw-bold">More games</h3>
              <p class="text-secondary mb-0">New game modes are on the way.</p>
            </div>
            <button class="btn btn-outline-warning fw-semibold" type="button" disabled>Coming soon</button>
          </div>
        </div>
      </div>
    </div>

    <section class="card border-secondary-subtle shadow-sm bg-dark">
      <div class="card-body p-4">
        <div class="mb-3">
          <span class="badge text-bg-warning text-dark rounded-pill mb-2">Leaderboard</span>
          <h2 class="h4 fw-bold mb-0">Top players</h2>
        </div>
        <div class="leaderboard" id="leaderboard"></div>
      </div>
    </section>
  </div>
</main>

<div id="triviaModal" class="game-modal">
  <div class="modal-content">
    <span class="close-btn" onclick="closeGame()">&times;</span>
    <div id="game-content"></div>
  </div>
</div>
@endsection
