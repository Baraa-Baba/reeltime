@extends('layouts.app')

@section('title', 'About Us | ReelTime')

@section('body-class')
about-page
@endsection

@section('content')
<main class="container py-4 py-lg-5">
  <section class="card border-secondary-subtle shadow-sm bg-dark mb-4">
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">About ReelTime</span>
          <h1 class="display-6 fw-bold mb-3">Built for people who want movie night handled in one place.</h1>
          <p class="text-secondary mb-4">
            ReelTime combines trailers, seat booking, watchlists, ratings, and trivia into a cleaner,
            faster flow for people who care more about the film than the clutter around it.
          </p>
          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('search') }}" class="btn btn-warning fw-semibold">Explore movies</a>
            <a href="{{ route('bookings') }}" class="btn btn-outline-warning fw-semibold">Plan a booking</a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row g-3">
            <div class="col-4">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">500+</div>
                  <div class="text-secondary small">Movies to rate</div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">24/7</div>
                  <div class="text-secondary small">Booking access</div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="card border-secondary-subtle bg-body-tertiary h-100 text-center">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">Fast</div>
                  <div class="text-secondary small">Trailer first</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="row g-4 mb-4">
    <div class="col-lg-7">
      <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
        <div class="card-body p-4">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">The story</span>
          <h2 class="h3 fw-bold mb-3">Why ReelTime exists</h2>
          <p class="text-secondary">
            We wanted a movie experience that feels direct. Open a trailer, check the vibe, add it to
            your watchlist, and move toward a booking without bouncing between unrelated pages.
          </p>
          <p class="text-secondary mb-4">
            ReelTime is designed for viewers who like discovery to feel curated and booking to feel obvious.
            It is simple on purpose.
          </p>

          <div class="row g-3">
            <div class="col-md-4">
              <div class="card border-secondary-subtle bg-body-tertiary text-center h-100">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">100+</div>
                  <div class="text-secondary small">Trailers uploaded</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card border-secondary-subtle bg-body-tertiary text-center h-100">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">Global</div>
                  <div class="text-secondary small">Booking coverage</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card border-secondary-subtle bg-body-tertiary text-center h-100">
                <div class="card-body">
                  <div class="fs-3 fw-bold text-warning">95%</div>
                  <div class="text-secondary small">User satisfaction</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card border-secondary-subtle shadow-sm bg-dark h-100 overflow-hidden">
        <img src="{{ asset('imgs/for welcome page.png') }}" alt="ReelTime cinema experience" class="img-fluid h-100 object-fit-cover">
      </div>
    </div>
  </section>

  <section class="mb-4">
    <div class="mb-3">
      <span class="badge text-bg-warning text-dark rounded-pill mb-2">Why choose ReelTime</span>
      <h2 class="h3 fw-bold mb-1">Everything stays focused on the movie.</h2>
      <p class="text-secondary mb-0">We keep the experience tight so users can move from discovery to booking without friction.</p>
    </div>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
          <div class="card-body p-4">
            <div class="mb-3 text-warning fs-2"><i class="fas fa-ticket-alt"></i></div>
            <h3 class="h5 fw-bold">Smart booking</h3>
            <p class="text-secondary mb-0">Reserve seats with clear timing, clean steps, and enough context to stay confident.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
          <div class="card-body p-4">
            <div class="mb-3 text-warning fs-2"><i class="fas fa-play-circle"></i></div>
            <h3 class="h5 fw-bold">Trailer first</h3>
            <p class="text-secondary mb-0">See the trailer before you commit, then decide whether to watch, rate, or save it.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
          <div class="card-body p-4">
            <div class="mb-3 text-warning fs-2"><i class="fas fa-heart"></i></div>
            <h3 class="h5 fw-bold">Rate & remember</h3>
            <p class="text-secondary mb-0">Leave ratings and comments that shape your own watchlist and movie history.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
        <div class="card-body p-4">
          <img src="{{ asset('imgs/badge.png') }}" alt="Achievements" class="img-fluid mb-3" style="max-height:72px;width:auto;">
          <h3 class="h5 fw-bold">Achievements</h3>
          <p class="text-secondary mb-0">We’ve enabled thousands of bookings, trailer views, and ratings across the catalog.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
        <div class="card-body p-4">
          <img src="{{ asset('imgs/shared-vision.png') }}" alt="Vision" class="img-fluid mb-3" style="max-height:72px;width:auto;">
          <h3 class="h5 fw-bold">Vision</h3>
          <p class="text-secondary mb-0">To be the most direct way to discover, save, and book movies from one place.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-secondary-subtle shadow-sm bg-dark h-100">
        <div class="card-body p-4">
          <img src="{{ asset('imgs/mission.png') }}" alt="Mission" class="img-fluid mb-3" style="max-height:72px;width:auto;">
          <h3 class="h5 fw-bold">Mission</h3>
          <p class="text-secondary mb-0">Give movie lovers a fast path from curiosity to the big screen with no clutter.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="card border-secondary-subtle shadow-sm bg-dark">
    <div class="card-body p-4 p-lg-5 text-center">
      <span class="badge text-bg-warning text-dark rounded-pill mb-3">Ready to move?</span>
      <h2 class="h3 fw-bold mb-3">Browse the catalog or lock in seats now.</h2>
      <p class="text-secondary mb-4">Watch trailers, rate movies, and build a watchlist that actually feels useful.</p>
      <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-warning fw-semibold">Start watching</a>
        <a href="{{ route('bookings') }}" class="btn btn-outline-warning fw-semibold">Book tickets</a>
      </div>
    </div>
  </section>
</main>
@endsection
