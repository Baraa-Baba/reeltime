@extends('layouts.app')

@section('title', 'Your Profile | ReelTime')

@section('body-class')
profile-page
@endsection

@push('scripts')
<script>
    window.authUser = {
        'id': {{ $user->user_id }},
        'username': "{{ $user->username }}",
        'email': "{{ $user->email }}",
        'img': "{{ $user->profile_image ?? ('https://robohash.org/' . urlencode($user->username)) }}",
        'since': {{ optional($user->member_since ?? $user->created_at)->year }},
        'role': "{{ $user->role }}",
    };
    window.watchlistCount = {{ $watchlist->count() }};
    window.ratedCount = {{ $ratedMovies->count() }};
    window.bookingsCount = {{ $bookings->count() }};
    window.userRatings = {!! json_encode($ratedMovies->map(function($rating) {
    return [
        'title' => $rating->movie->title,
        'rating' => $rating->score,
        'comment' => $rating->comment,
        'image' => $rating->movie->poster ? asset($rating->movie->poster) : asset('imgs/default-movie.jpg'),
    ];
})->toArray()) !!};
</script>
<script src="{{ asset('js/profile.js') }}" defer></script>
<script src="{{ asset('js/watchlist.js') }}" defer></script>
@endpush

@section('content')
<main class="profile-page">
    <div class="profile-modern">
        <!-- Profile header -->
        <div class="profile-hero">
            <div class="user-avatar-container">
                <img src="{{ $user->profile_image ?? 'https://robohash.org/' . urlencode($user->username) }}" alt="{{ $user->username }}" class="user-avatar" onerror="this.src='../../imgs/default-avatar.jpg'">
                <div class="online-status"></div>
            </div>
            <div class="profile-info-modern">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <h1>{{ $user->username }}</h1>
                    <button class="icon-btn" id="editProfileBtn" aria-label="Edit profile">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
                <p class="profile-meta">{{ $user->email }}</p>
                <p class="profile-meta">{{ $user->user_id }}</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number" id="watchlist-count">{{ $watchlist->count() }}</span>
                        <span class="stat-label">In Watchlist</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="rated-count">{{ $ratedMovies->count() }}</span>
                        <span class="stat-label">Movies Rated</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="total-bookings">{{ $bookings->count() }}</span>
                        <span class="stat-label">Total Bookings</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="member-since">{{ optional($user->member_since ?? $user->created_at)->year }}</span>
                        <span class="stat-label">Member Since</span>
                    </div>
                </div>
                <button class="logout-btn" id="logoutBtn">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </div>
        </div>
        
        <div class="search">
            <input id="SearchInput" placeholder="Search movies in your watchlist..." />
        </div>
        
        <!-- Watchlist Section - Render from server -->
        <div class="watchlist-section">
            <div class="section-header">
                <div class="section-title">My Watchlist</div>
                <div class="watchlist-count" id="watchlist-counter">{{ $watchlist->count() }} movies</div>
            </div>
            <div class="watchlist-grid-modern" id="modern-watchlist">
                @forelse($watchlist as $movie)
                    <div class="watchlist-card-modern" data-movie-id="{{ $movie->movie_id }}">
                        <img src="{{ $movie->poster ? asset($movie->poster) : asset('imgs/default-movie.jpg') }}" 
                             alt="{{ $movie->title }}" class="card-image">
                        <button class="btn-remove-card" data-title="{{ $movie->title }}" data-movie-id="{{ $movie->movie_id }}" aria-label="Remove from watchlist">
                            <i class="fas fa-trash"></i>
                        </button>
                        @if(!$movie->ratings->where('user_id', auth()->id())->first())
                            <button class="btn-rate-icon" data-title="{{ $movie->title }}" data-movie-id="{{ $movie->movie_id }}" aria-label="Rate this movie">
                                <i class="far fa-star"></i>
                            </button>
                        @endif
                        <div class="card-content">
                            <h3 class="card-title">{{ $movie->title }}</h3>
                            @if($userRating = $movie->ratings->where('user_id', auth()->id())->first())
                                <div class="card-rating-wrapper">
                                    <div class="user-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($userRating->score))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - $userRating->score < 1 && $userRating->score % 1 != 0)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor 
                                    </div>
                                </div>
                            @endif
                            <div class="card-actions">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-watchlist" style="grid-column: 1 / -1;">
                        <h3>Your Watchlist is Empty</h3>
                        <p>Start adding movies to build your personalized collection!</p>
                        <a href="{{ route('search') }}" class="accent-link">Browse Movies</a>
                    </div>
                @endforelse
            </div>
        </div>
        
        <div class="booked-section">
            <div class="section-header">
                <div class="section-title">My Booked Movies</div>
                <div class="watchlist-count" id="booked-counter">{{ $bookings->count() }} bookings</div>
                <select id="booked-sort">
                    <option value="nearest">Nearest date first</option>
                    <option value="latest">Farthest date first</option>
                    <option value="upcoming">Upcoming bookings</option>
                    <option value="watched">Watched bookings</option>
                    <option value="cancelled">Cancelled bookings</option>
                </select>
            </div>
            <div class="booked-grid-modern" id="booked-grid">
        @forelse($bookings as $booking)
            @php
                $showtime = $booking->showtime;
                $movie = $showtime?->movie;
                $status = $booking->status ?? 'upcoming';
                $customerInfo = json_decode($booking->customer_info, true);
            @endphp
            <div class="booked-card-modern" data-booking-id="{{ $booking->booking_id }}">
                <div class="booked-header">
                    <h3 class="booked-movie-title">{{ $movie->title ?? 'Unknown Movie' }}</h3>
                    <div class="booked-status status-{{ $status }}">
                        @if($status == 'cancelled')
                            <i class="fas fa-times"></i> Cancelled
                        @elseif($status == 'watched')
                            <i class="fas fa-check"></i> Watched
                        @else
                            <i class="fas fa-clock"></i> Upcoming
                        @endif
                    </div>
                </div>
                <div class="booked-meta">
                    <span><strong>Date:</strong> {{ $showtime?->show_date ?? 'Not specified' }}</span>
                    <span><strong>Time:</strong> {{ $showtime?->show_time ?? 'Not specified' }}</span>
                    <span><strong>Cinema:</strong> {{ $showtime?->cinema?->name ?? 'Not specified' }}</span>
                </div>
                <div class="booked-extra">
                    <div><strong>Seats:</strong> {{ $booking->seats }}</div>
                    <div><strong>Total:</strong> ${{ number_format($booking->price, 2) }}</div>
                </div>
               @if(in_array($status, ['confirmed', 'pending', 'upcoming']))
                <div class="booking-actions-small">
                    <button class="btn-action-small btn-cancel-small" data-booking-id="{{ $booking->booking_id }}">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
                @endif
            </div>
        @empty
            <div class="empty-booked" style="grid-column: 1 / -1;">
                <div class="empty-icon"><i class="fas fa-ticket-alt"></i></div>
                <h3>No Bookings Yet</h3>
                <p>Book a movie from the bookings page and it will appear here.</p>
                <a href="{{ route('bookings') }}" class="accent-link">Book a Movie <i class="fas fa-arrow-right"></i></a>
        </div>
        @endforelse
    </div>
    </main>
    // Edit Profile Modal
        <div id="editProfileModal" class="admin-modal">
        <div class="surface-card admin-modal-shell" style="max-width: 500px;">
            <button type="button" class="modal-close-btn" onclick="closeEditProfileModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="section-header">
                <span class="eyebrow">Profile Settings</span>
                <h2>Edit Your Info</h2>
                <p>Update your username, email, or profile picture.</p>
            </div>
            <form id="editProfileForm">
            <div style="display: grid; gap: 1.25rem;">
                <div style="text-align: center;">
                    <div style="position: relative; display: inline-block;">
                        <img id="editAvatarPreview" src="{{ $user->profile_image ?? ('https://robohash.org/' . urlencode($user->username)) }}" 
                             alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent);">
                        <label for="editAvatarInput" style="position: absolute; bottom: 0; right: 0; background: var(--accent); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                            <i class="fas fa-camera" style="font-size: 14px;"></i>
                        </label>
                        <input type="file" id="editAvatarInput" accept="image/*" style="display: none;">
                    </div>
                    <p class="text-muted small mt-2">Click the camera to upload a new image</p>
                </div>

                <div>
                    <label>Username</label>
                    <input type="text" id="editUsername" name="username" class="form-control" 
                           value="{{ $user->username }}" required>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" id="editEmail" name="email" class="form-control" 
                           value="{{ $user->email }}" required>
                </div>

                <div id="editProfileMessage" class="alert" style="display: none;"></div>
            </div>
        </form>
        <div class="admin-actions" style="justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
            <button class="button button-secondary" onclick="closeEditProfileModal()">Cancel</button>
            <button class="button button-primary" id="saveProfileBtn">Save Changes</button>
        </div>
    </div>
</div>
@endsection
