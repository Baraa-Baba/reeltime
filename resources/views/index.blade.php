@extends('layouts.app')

@section('title', 'ReelTime - Discover, Rate & Watch')

@push('scripts')
<script src="{{ asset('js/watchlist.js') }}" defer></script>
@endpush

@section('content')
<main>
  <div class="welcome-box" id="heroBox">
    <button class="hero-btn prev" onclick="prevImage()">&#10094;</button>
    <button class="hero-btn next" onclick="nextImage()">&#10095;</button>

    @foreach($heroBanners ?? [] as $banner)
      <div class="Welcome2 hero-slide {{ $loop->first ? 'active' : '' }}" data-position="{{ $banner->position }}">
        @if($banner->subtitle)
          <p style="font-style: italic;">{{ $banner->subtitle }}</p>
        @endif
        <h1>{{ $banner->title }}</h1>
        @if($banner->cta_label && $banner->cta_route_name)
          <a href="{{ route($banner->cta_route_name) }}">{{ $banner->cta_label }}</a>
        @endif
      </div>
    @endforeach
  </div>

  <section class="gallery" id="gallery"></section>
</main>

<div class="modal" id="card-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div class="modal__backdrop" data-close-modal></div>
  <div class="modal__dialog" role="document">
    <button class="modal__close" id="modal-close" aria-label="Close dialog" data-close-modal>&times;</button>

    <div class="modal__media">
      <div id="trailer-container">
        <iframe id="modal-trailer" width="100%" height="315" src="" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen>
        </iframe>
      </div>
      <div id="movieinfo">
        <p><strong>Cast:</strong> <span id="modal-cast"></span></p>
        <p><strong>Genres:</strong> <span id="modal-genres"></span></p>
        <p><strong>This movie is:</strong> <span id="modal-this-movie-is"></span></p>
      </div>
    </div>

    <div class="modal__body">
      <h3 id="modal-title">movie</h3>
      <p id="modal-text"></p>
      <button class="add-watchlist-btn">+ Add to Watchlist</button>
      <div class="comments-section">
        <h4>Reviews</h4>
        <div id="comments-list"></div>
      </div>
    </div>
  </div>
</div>
@endsection