@php
    $title       = e($movie['title'] ?? '');
    $movieId     = e($movie['movie_id'] ?? '');
    $description = e($movie['description'] ?? '');
    $ratingRaw   = $movie['rating'] ?? null;
    $ratingDisplay = is_numeric($ratingRaw) ? $ratingRaw : '-';
    $showRating  = !is_numeric($ratingRaw) || (float) $ratingRaw > 0;
    $runtime     = e($movie['time'] ?? '');
    $cast        = is_array($movie['cast'] ?? null) ? e(implode(', ', $movie['cast'])) : e($movie['cast'] ?? '');
    $genres      = is_array($movie['genres'] ?? null) ? e(implode(', ', $movie['genres'])) : e($movie['genres'] ?? '');
    $mood        = is_array($movie['this_movie_is'] ?? null) ? e(implode(', ', $movie['this_movie_is'])) : e($movie['this_movie_is'] ?? '');
    $posterUrl   = $movie['poster_url'] ?? asset('imgs/default-movie.jpg');
    $trailerUrl  = e($movie['trailer_url'] ?? '');
    $showtimes   = isset($movie['showtimes']) && is_array($movie['showtimes'])
        ? collect($movie['showtimes'])->pluck('display')->filter()->values()->all()
        : [];
@endphp

<figure class="movie-card"
    data-movie-id="{{ $movieId }}"
    data-title="{{ $title }}"
    data-description="{{ $description }}"
    data-trailer-url="{{ $trailerUrl }}"
    data-rating="{{ $ratingDisplay }}"
    data-cast="{{ $cast }}"
    data-genres="{{ $genres }}"
    data-this-movie-is="{{ $mood }}"
    data-time="{{ $runtime }}"
    data-showtimes='@json($showtimes)'>
    @if($showRating)
        <span class="rating-overlay">{{ $ratingDisplay }} / 5</span>
    @endif
    <img src="{{ $posterUrl }}" alt="{{ $title }} poster" onerror="this.src='{{ asset('imgs/default-movie.jpg') }}'">
    <div class="movie-overlay">
        <p class="movie-overlay-title">{{ $title }}</p>
        <p class="movie-overlay-desc">{{ $description }}</p>
        <div class="movie-overlay-bottom">
            <span class="film-overlay">{{ $runtime }}</span>
            @if($showRating)
                <span class="movie-overlay-rating">{{ $ratingDisplay }} / 5 ★</span>
            @endif
        </div>
    </div>
</figure>
