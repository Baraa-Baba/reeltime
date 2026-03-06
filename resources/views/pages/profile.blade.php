@extends('layouts.app')

@section('title', 'Your Profile | ReelTime')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/profile.js') }}" defer></script>
<script src="{{ asset('js/watchlist.js') }}" defer></script>
@endpush

@section('content')
<div class="Profile-container">
    
</div>

<main>
    <div id="watchlist-container" class="gallery"></div>
    
    <div class="rated-section">
        <div class="section-header">
            <div class="section-title">My Rated Movies</div>
            <div class="watchlist-count" id="rated-counter">0 movies rated</div>
        </div>
        <div class="rated" id="rated"></div>
    </div>
    
    <div class="booked-section">
      <div class="section-header">
        <div class="section-title">My Booked Movies</div>
        <div class="watchlist-count" id="booked-counter">0 bookings</div>
      </div>
      <div class="booked-grid-modern" id="booked-grid"></div>
    </div>

</main>
@endsection
