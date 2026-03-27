@extends('layouts.app')

@section('title', 'Search Movies | ReelTime')

@section('body-class')
search-page
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/search.js') }}" defer></script>
<script src="{{ asset('js/watchlist.js') }}" defer></script>
@endpush

@section('content')
<main class="container py-4 py-lg-5">
  <section class="card border-secondary-subtle shadow-sm bg-dark mb-4">
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-6">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">Discover · Rate · Book</span>
          <h1 class="display-6 fw-bold mb-3">Find your next movie without the noise.</h1>
          <p class="text-secondary mb-0">
            Search the ReelTime catalog by title, genre, or mood and jump straight into a trailer or
            watchlist save.
          </p>
        </div>

        <div class="col-lg-6">
          <div class="input-group input-group-lg">
            <span class="input-group-text bg-body-tertiary border-secondary-subtle text-warning">
              <i class="fa-solid fa-film"></i>
            </span>
            <input id="mainSearchInput" type="text" class="form-control bg-body-tertiary border-secondary-subtle" placeholder="Type a movie title, genre, or keyword...">
            <button id="mainSearchBtn" type="button" class="btn btn-warning fw-semibold">
              <i class="fa fa-search" aria-hidden="true"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
    <div>
      <span class="badge text-bg-warning text-dark rounded-pill mb-2">Results</span>
      <p id="resultsCount" class="text-secondary mb-0">Loading movies...</p>
    </div>
    <select id="searchSort" class="form-select form-select-sm bg-body-tertiary border-secondary-subtle text-light" aria-label="Sort search results" style="max-width: 220px;">
      <option value="relevance">Relevance</option>
      <option value="title-asc">Title A-Z</option>
      <option value="title-desc">Title Z-A</option>
      <option value="rating-desc">Rating High → Low</option>
      <option value="rating-asc">Rating Low → High</option>
    </select>
  </section>

  <section id="searchResults" class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4"></section>

  <div id="searchEmpty" class="alert alert-secondary border-secondary-subtle text-center mt-4 mb-0" style="display:none;">
    <h3 class="h5 fw-bold mb-2">No results found</h3>
    <p class="mb-0 text-secondary">Try another title, genre, or keyword.</p>
  </div>
</main>

<div class="modal fade" id="card-modal" tabindex="-1" aria-hidden="true" aria-labelledby="modal-title">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark border-secondary-subtle shadow-lg">
      <div class="modal-header border-secondary-subtle">
        <h2 class="modal-title h5 fw-bold mb-0" id="modal-title">movie</h2>
        <button type="button" class="btn-close btn-close-white" id="modal-close" aria-label="Close dialog" data-close-modal></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-lg-6">
            <div id="trailer-container" class="ratio ratio-16x9 mb-3">
              <iframe id="modal-trailer" src="" title="Trailer" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div id="movieinfo" class="text-secondary small d-grid gap-2">
              <p class="mb-0"><strong class="text-light">Cast:</strong> <span id="modal-cast"></span></p>
              <p class="mb-0"><strong class="text-light">Genres:</strong> <span id="modal-genres"></span></p>
              <p class="mb-0"><strong class="text-light">This movie is:</strong> <span id="modal-this-movie-is"></span></p>
            </div>
          </div>

          <div class="col-lg-6">
            <p id="modal-text" class="text-secondary"></p>
            <button class="btn btn-warning fw-semibold add-watchlist-btn">+ Add to Watchlist</button>
            <div class="comments-section mt-4">
              <h4 class="h6 text-uppercase text-warning fw-bold">Reviews</h4>
              <div id="comments-list" class="d-grid gap-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
