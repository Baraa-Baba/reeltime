@extends('layouts.app')

@section('title', 'Admin Dashboard | ReelTime')

@section('body-class')
admin-page
@endsection

@push('scripts')
<script src="{{ asset('js/admin.js') }}" defer></script>
@endpush

@section('content')
@php($adminAvatar = Illuminate\Support\Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image))
<main class="page-shell admin-shell">
  <section class="surface-card admin-hero">
    <div class="admin-hero-main">
      <div class="admin-avatar-wrap">
        <img src="{{ $adminAvatar }}" alt="Admin" class="admin-avatar">
      </div>

      <div class="admin-hero-copy">
        <span class="eyebrow">Administrator</span>
        <h1>Welcome back, {{ $user->username }}</h1>
        <p>Manage movies, games, users, and bookings from one dashboard using the same ReelTime visual system as the public site.</p>
      </div>
    </div>

    <div class="admin-hero-actions">
      <button type="button" onclick="openModal('addMovieModal')" class="button button-primary">
        <i class="fas fa-plus" aria-hidden="true"></i>
        <span>Add Movie</span>
      </button>
      <button type="button" onclick="openGameModal()" class="button button-secondary">
        <i class="fas fa-plus" aria-hidden="true"></i>
        <span>Add Game</span>
      </button>
    </div>
  </section>

  <section class="admin-stats">
    <article class="surface-card admin-stat-card">
      <div class="admin-stat-icon">
        <i class="fas fa-film" aria-hidden="true"></i>
      </div>
      <div>
        <span class="admin-stat-label">Total Movies</span>
        <strong class="admin-stat-value">{{ $movieCount }}</strong>
      </div>
    </article>

    <article class="surface-card admin-stat-card">
      <div class="admin-stat-icon">
        <i class="fas fa-gamepad" aria-hidden="true"></i>
      </div>
      <div>
        <span class="admin-stat-label">Total Games</span>
        <strong class="admin-stat-value">{{ $gameCount }}</strong>
      </div>
    </article>

    <article class="surface-card admin-stat-card">
      <div class="admin-stat-icon">
        <i class="fas fa-users" aria-hidden="true"></i>
      </div>
      <div>
        <span class="admin-stat-label">Total Users</span>
        <strong class="admin-stat-value">{{ $userCount }}</strong>
      </div>
    </article>

    <article class="surface-card admin-stat-card">
      <div class="admin-stat-icon">
        <i class="fas fa-ticket-alt" aria-hidden="true"></i>
      </div>
      <div>
        <span class="admin-stat-label">Total Bookings</span>
        <strong class="admin-stat-value">{{ $bookingCount }}</strong>
      </div>
    </article>
  </section>

  <section class="surface-card admin-tabs-panel">
    <div class="admin-tabs" role="tablist" aria-label="Admin sections">
      <button class="button button-secondary admin-tab-btn is-active" id="moviesTabBtn" type="button" onclick="switchTab('movies')">
        <i class="fas fa-film" aria-hidden="true"></i>
        <span>Movies</span>
      </button>
      <button class="button button-secondary admin-tab-btn" id="gamesTabBtn" type="button" onclick="switchTab('games')">
        <i class="fas fa-gamepad" aria-hidden="true"></i>
        <span>Games</span>
      </button>
      <button class="button button-secondary admin-tab-btn" id="bookingsTabBtn" type="button" onclick="switchTab('bookings')">
        <i class="fas fa-ticket-alt" aria-hidden="true"></i>
        <span>Bookings</span>
      </button>
      <button class="button button-secondary admin-tab-btn" id="usersTabBtn" type="button" onclick="switchTab('users')">
        <i class="fas fa-users" aria-hidden="true"></i>
        <span>Users</span>
      </button>
      <button class="button button-secondary admin-tab-btn" id="heroBannersTabBtn" type="button" onclick="switchTab('heroBanners')">
        <i class="fas fa-image" aria-hidden="true"></i>
        <span>Hero Banners</span>
      </button>
    </div>

     <section id="moviesTab" class="admin-tab-pane">
      <div class="section-header">
        
      </div>

      <div class="admin-data-card">
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Poster</th>
                <th>Title</th>
                <th>Rating</th>
                <th>Duration</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($movies as $movie)
                <tr class="movie-row" style="cursor: pointer;"
                      data-title="{{ addslashes($movie->title) }}"
                      data-description="{{ addslashes($movie->description) }}"
                      data-cast="{{ addslashes($movie->cast) }}"
                      data-genres="{{ addslashes($movie->genres) }}"
                      data-this-movie-is="{{ addslashes($movie->this_movie_is) }}"
                      data-trailer-link="{{ $movie->trailer_link }}"
                      data-rating="{{ $movie->rating }}"
                      data-duration="{{ $movie->duration }}"
                      data-poster="{{ asset($movie->poster) }}">
                  <td>{{ $movie->movie_id }}</td>
                  <td>
                    @if($movie->poster)
                      <img src="{{ asset($movie->poster) }}" class="admin-thumb" alt="Poster">
                    @else
                      <div class="admin-thumb admin-thumb-placeholder"></div>
                    @endif
                  </td>
                  <td>{{ $movie->title }}</td>
                  <td>{{ $movie->rating ? number_format($movie->rating, 1) : '-' }}</td>
                  <td>{{ $movie->duration }} min</td>
                  <td>
                    <div class="admin-actions">
                      <button type="button" class="button button-secondary admin-icon-btn" 
                          onclick="openEditMovieModal(
                              '{{ $movie->movie_id }}',
                              '{{ addslashes($movie->title) }}',
                              '{{ addslashes($movie->description) }}',
                              '{{ addslashes($movie->genres) }}',
                              '{{ addslashes($movie->this_movie_is) }}',
                              '{{ addslashes($movie->cast) }}',
                              '{{ $movie->duration }}',
                              '{{ $movie->trailer_link }}',
                              '{{ asset($movie->poster) }}'
                          )" aria-label="Edit movie">
                          <i class="fas fa-edit" aria-hidden="true"></i>
                      </button>
                      <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-btn" data-url="{{route('admin.movies.destroy',$movie->movie_id)}}" aria-label="Delete movie">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="admin-empty">No movies yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section id="gamesTab" class="admin-tab-pane d-none">
      <div class="section-header">
        
      </div>

      <div class="admin-data-card">
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Icon</th>
                <th>Title</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($games as $game)
                <tr data-game-id="{{ $game->game_id }}">
                  <td>{{ $game->game_id }}</td>
                  <td><div class="admin-table-icon"><i class="fas {{ $game->icon }}"></i></div></td>
                  <td>{{ $game->title }}</td>
                  <td>{{ ucfirst($game->game_type) }}</td>
                  <td>
                    <div class="admin-actions">
                      <button type="button" class="button button-secondary admin-icon-btn edit-game-btn"
                                    data-id="{{ $game->game_id }}"
                                    data-title="{{ addslashes($game->title) }}"
                                    data-description="{{ addslashes($game->description) }}"
                                    data-game_type="{{ $game->game_type }}"
                                    data-icon="{{ $game->icon }}">
                        <i class="fas fa-edit"></i>
                      </button>
                            <button type="button" class="button button-secondary admin-icon-btn manage-questions-btn"
                                    data-id="{{ $game->game_id }}" data-title="{{ addslashes($game->title) }}">
                                <i class="fas fa-question-circle"></i>
                            </button>
                            <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-btn"
                                    data-url="/api/admin-api/games/{{ $game->game_id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="admin-empty">No games yet.</td></tr>
                @endforelse
            </tbody> 
          </table>
        </div>
      </div>
    </section>

    <section id="bookingsTab" class="admin-tab-pane d-none">
      <div class="section-header">
        
      </div>

      <div class="admin-data-card">
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>User</th>
                <th>Movie</th>
                <th>Poster</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                @php($bookingStatus = strtolower($booking->status ?? 'pending'))
                <tr>
                  <td>{{ $booking->booking_id }}</td>
                  <td>{{ $booking->user->username }}</td>
                  <td>{{ $booking->showtime->movie->title }}</td>
                  <td>
                    <img src="{{ asset($booking->showtime->movie->poster) }}" class="admin-thumb" alt="Poster">
                  </td>
                  <td>{{ $booking->booking_date }}</td>
                  <td>
                    <span class="admin-badge {{ 'status-' . $bookingStatus }}">{{ $booking->status }}</span>
                  </td>
                  <td>
                    <div class="admin-actions">
                      <button type="button" class="button button-secondary admin-icon-btn" aria-label="View booking">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                      </button>
                      <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-btn" data-url="{{ route('admin.bookings.destroy', $booking->booking_id) }}" aria-label="Delete booking">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="admin-empty">No bookings yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <section id="usersTab" class="admin-tab-pane d-none">
      <div class="section-header">
        
      </div>

      <div class="admin-data-card">
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Avatar</th>
                <th>Username</th>
                <th>Email</th>
                <th>Member Since</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $member)
                <tr>
                  <td>{{ $member->user_id }}</td>
                  <td>
                    @if($member->profile_image)
                      <img src="{{ Illuminate\Support\Str::startsWith($member->profile_image, ['http://', 'https://']) ? $member->profile_image : asset($member->profile_image) }}" class="admin-avatar-sm" alt="{{ $member->username }}">
                    @else
                      <div class="admin-avatar-sm admin-thumb-placeholder"></div>
                    @endif
                  </td>
                  <td>{{ $member->username }}</td>
                  <td>{{ $member->email }}</td>
                  <td>{{ $member->member_since }}</td>
                  <td>
                    <span class="admin-badge {{ $member->role === 'admin' ? 'role-admin' : 'role-user' }}">{{ $member->role }}</span>
                  </td>
                  <td>
                    <div class="admin-actions">
                      <button type="button" class="button button-secondary admin-icon-btn" aria-label="Edit user">
                        <i class="fas fa-edit" aria-hidden="true"></i>
                      </button>
                      <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-btn" data-url="{{ route('admin.users.destroy', $member->user_id) }}" aria-label="Delete user">
                        <i class="fas fa-trash" aria-hidden="true"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="admin-empty">No users yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
  </section>
 <!-- Hero Banners Tab -->
<section id="heroBannersTab" class="admin-tab-pane d-none">
    <div class="section-header">
        <div class="d-flex justify-content-end align-items-center w-100">
            <button type="button" class="button button-primary" onclick="openHeroBannerModal()">
                <i class="fas fa-plus"></i> Add Banner
            </button>
        </div>
    </div>

    <div class="admin-data-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>CTA Label</th>
                        <th>CTA Route</th>
                        <th>Position</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="heroBannersList">
                    <tr><td colspan="9" class="admin-empty">Loading banners...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
  </section>
  <!-- Image Preview Modal for Hero Banners -->
<div id="imagePreviewModal" class="admin-modal">
    <div class="surface-card admin-modal-shell" style="max-width: 800px; max-height: 90vh; overflow: auto;">
        <button type="button" class="modal-close-btn" onclick="closeImagePreviewModal()" aria-label="Close">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <div class="section-header" style="text-align: center;">
            <span class="eyebrow">Banner Preview</span>
            <h2 id="previewModalTitle">Hero Banner</h2>
        </div>
        <div style="text-align: center; padding: 1rem;">
            <img id="previewImage" src="" alt="Banner preview" style="max-width: 100%; max-height: 60vh; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
        </div>
        <div style="text-align: center; margin-top: 1rem;">
            <p id="previewImageTitle" style="color: var(--text-muted);"></p>
        </div>
    </div>
</div>
</main>


<!-- Add/Edit Banner Modal -->
<div id="heroBannerModal" class="admin-modal" onclick="closeModal('heroBannerModal')">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 600px;">
        <button type="button" class="modal-close-btn" onclick="closeModal('heroBannerModal')">
            <i class="fas fa-times"></i>
        </button>
        <div class="section-header">
            <span class="eyebrow">Hero Banner</span>
            <h2 id="heroBannerModalTitle">Add Banner</h2>
        </div>
        <form id="heroBannerForm" enctype="multipart/form-data">
            <input type="hidden" name="banner_id" id="banner_id">
            <div style="display: grid; gap: 1rem;">
                <div>
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="banner_title" required class="form-control" style="width:100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                <div>
                    <label>Subtitle</label>
                    <input type="text" name="subtitle" id="banner_subtitle" class="form-control" style="width:100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                <div>
                    <label>CTA Label </label>
                    <input type="text" name="cta_label" id="banner_cta_label" class="form-control" style="width:100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                <div>
                    <label>CTA Route Name</label>
                    <input type="text" name="cta_route_name" id="banner_cta_route_name" class="form-control" style="width:100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                <div>
                    <label>Position (order)</label>
                    <input type="number" name="position" id="banner_position" class="form-control" min="0" style="width:100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                </div>
                <div>
                    <label>Background Image <span class="text-danger">*</span></label>
                    <input type="file" name="background_image" id="banner_image" accept="image/*" class="form-control" style="padding: 0.75rem 1rem;">
                    <div id="currentImagePreview" style="margin-top: 0.5rem; display: none;">
                        <img id="currentImageImg" style="max-width: 100px; border-radius: 8px;">
                    </div>
                </div>
                <div id="heroBannerFormMessage" style="display: none;" class="alert"></div>
            </div>
        </form>
        <div class="admin-actions" style="justify-content: flex-end; margin-top: 1rem;">
            <button class="button button-secondary" onclick="closeModal('heroBannerModal')">Cancel</button>
            <button class="button button-primary" id="submitHeroBannerBtn">Save Banner</button>
        </div>
    </div>
</div>

<div id="addMovieModal" class="admin-modal" onclick="closeModal('addMovieModal')">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 700px; max-height: 90vh; display: flex; flex-direction: column;">    
        <div style="flex-shrink: 0; padding: 0 0 1rem 0;">
           <button type="button" class="modal-close-btn" onclick="closeModal('addMovieModal')" aria-label="Close">
    <i class="fas fa-times" aria-hidden="true"></i>
</button>
            <div class="section-header" style="padding-right: 2.5rem;">
                <span class="eyebrow">Movie Tools</span>
                <h2>Add movie</h2>
                <p>Fill in the details to add a new movie to the catalog.</p>
            </div>
        </div>
        
       
        <div style="flex: 1; overflow-y: auto; padding-right: 0.5rem; margin-right: -0.5rem;">
            <form id="addMovieForm" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; gap: 1.25rem;">
                    
                  
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-film" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Title <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" name="title" required 
                               placeholder="Enter movie title"
                               class="form-control" 
                               style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; transition: all 0.2s;">
                    </div>
                    
                   
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-align-left" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Description <span style="color: #ff6b6b;">*</span>
                        </label>
                        <textarea name="description" rows="4" required 
                                  placeholder="Enter movie description..."
                                  style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; resize: vertical; font-family: inherit;"></textarea>
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Provide a compelling description of the movie</small>
                    </div>
                    
                    
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-tags" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Genres <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" name="genres" required 
                               placeholder="e.g., Action, Sci-Fi, Thriller"
                               style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Separate genres with commas</small>
                    </div>
                     <div>
    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
        <i class="fas fa-face-smile" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
        This Movie Is (Mood/Tags)
    </label>
    <input type="text" name="this_movie_is" 
           placeholder="e.g., Exciting, Suspenseful, Emotional, Heartwarming"
           style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
    <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Describe the movie mood (comma separated)</small>
</div>
                    
                   
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-users" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Cast <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" name="cast" required 
                               placeholder="e.g., Actor 1, Actor 2, Actor 3"
                               style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Separate cast members with commas</small>
                    </div>
                    
                    
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-clock" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Duration (minutes) <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="number" name="duration" required min="1" max="600"
                               placeholder="e.g., 120"
                               style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Enter duration in minutes (1-600)</small>
                    </div>
                    
                    
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fab fa-youtube" style="margin-right: 0.5rem; color: #ff0000;"></i>
                            Trailer Link
                        </label>
                        <input type="url" name="trailer_link" 
                               placeholder="https://www.youtube.com/embed/VIDEO_ID"
                               style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                        <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">YouTube embed URL (optional)</small>
                    </div>
                    
                  
                    
                    
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-color);">
                            <i class="fas fa-image" style="margin-right: 0.5rem; color: var(--accent-3);"></i>
                            Poster Image <span style="color: #ff6b6b;">*</span>
                        </label>
                        <div style="border: 2px dashed rgba(255,255,255,0.2); border-radius: 12px; padding: 1rem; text-align: center; background: rgba(255,255,255,0.02);">
                            <input type="file" name="poster" accept="image/*" 
                                   id="posterInput"
                                   style="display: none;">
                            <button type="button" onclick="document.getElementById('posterInput').click()" 
                                    style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 0.5rem 1rem; color: white; cursor: pointer; margin-bottom: 0.5rem;">
                                <i class="fas fa-upload"></i> Choose Image
                            </button>
                            <div id="posterPreview" style="margin-top: 0.5rem; display: none;">
                                <img id="posterPreviewImg" style="max-width: 150px; max-height: 150px; border-radius: 8px; margin-top: 0.5rem;">
                                <p id="posterFileName" style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem;"></p>
                            </div>
                            <small style="color: var(--text-muted); display: block;">JPG, PNG, GIF, SVG up to 2MB</small>
                        </div>
                    </div>
                    
                   
                    <div id="movieFormMessage" style="display: none; padding: 0.75rem 1rem; border-radius: 12px; margin-top: 0.5rem;"></div>
                </div>
            </form>
        </div>
        
        
        <div style="flex-shrink: 0; padding-top: 1.5rem; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="admin-actions" style="justify-content: flex-end; gap: 1rem;">
              <button type="button" class="button button-secondary" data-close-modal="addMovieModal">
    <i class="fas fa-times"></i> Cancel
</button>
                <button type="submit" form="addMovieForm" class="button button-primary" id="submitMovieBtn" style="min-width: 120px;">
                    <i class="fas fa-plus"></i> Add Movie
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Movie Modal -->
<div id="editMovieModal" class="admin-modal" onclick="closeModal('editMovieModal')">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 700px; max-height: 90vh; display: flex; flex-direction: column;">
        <div style="flex-shrink: 0; padding: 0 0 1rem 0;">
            <button type="button" class="modal-close-btn" onclick="closeModal('editMovieModal')" aria-label="Close">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
            <div class="section-header" style="padding-right: 2.5rem;">
                <span class="eyebrow">Movie Tools</span>
                <h2>Edit Movie</h2>
                <p>Update the movie details.</p>
            </div>
        </div>

        <div style="flex: 1; overflow-y: auto; padding-right: 0.5rem; margin-right: -0.5rem;">
            <form id="editMovieForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="movie_id" id="edit_movie_id">
                <div style="display: grid; gap: 1.25rem;">
                    <div>
                        <label for="edit_title" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-film"></i> Title <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" id="edit_title" name="title" required class="form-control" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div>
                        <label for="edit_description" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-align-left"></i> Description <span style="color: #ff6b6b;">*</span>
                        </label>
                        <textarea id="edit_description" name="description" rows="4" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;"></textarea>
                    </div>
                    <div>
    <label for="edit_this_movie_is" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
        <i class="fas fa-face-smile"></i> This Movie Is (Mood/Tags)
    </label>
    <input type="text" id="edit_this_movie_is" name="this_movie_is" 
           placeholder="e.g., Exciting, Suspenseful, Emotional, Heartwarming"
           style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
    <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">Describe the movie mood (comma separated)</small>
</div>
                    <div>
                        <label for="edit_genres" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-tags"></i> Genres <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" id="edit_genres" name="genres" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div>
                        <label for="edit_cast" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-users"></i> Cast <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="text" id="edit_cast" name="cast" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div>
                        <label for="edit_duration" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-clock"></i> Duration (minutes) <span style="color: #ff6b6b;">*</span>
                        </label>
                        <input type="number" id="edit_duration" name="duration" required min="1" max="600" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    <div>
                        <label for="edit_trailer_link" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fab fa-youtube"></i> Trailer Link
                        </label>
                        <input type="url" id="edit_trailer_link" name="trailer_link" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white;">
                    </div>
                    
                    <div>
                        <label for="edit_poster" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">
                            <i class="fas fa-image"></i> Poster Image (leave empty to keep current)
                        </label>
                        <div style="border: 2px dashed rgba(255,255,255,0.2); border-radius: 12px; padding: 1rem; text-align: center;">
                            <input type="file" name="poster" accept="image/*" id="edit_poster_input" style="display: none;">
                            <button type="button" onclick="document.getElementById('edit_poster_input').click()" class="button button-secondary">
                                <i class="fas fa-upload"></i> Choose New Image
                            </button>
                            <div id="edit_poster_preview" style="margin-top: 0.5rem; display: none;">
                                <img id="edit_poster_preview_img" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                <p id="edit_poster_file_name" style="color: var(--text-muted); font-size: 0.85rem;"></p>
                            </div>
                            <small style="color: var(--text-muted); display: block;">JPG, PNG, GIF, SVG up to 2MB</small>
                        </div>
                    </div>
                    <div id="editMovieFormMessage" style="display: none; padding: 0.75rem 1rem; border-radius: 12px;"></div>
                </div>
            </form>
        </div>
        <div style="flex-shrink: 0; padding-top: 1.5rem; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="admin-actions" style="justify-content: flex-end; gap: 1rem;">
                <button type="button" class="button button-secondary" onclick="closeModal('editMovieModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" form="editMovieForm" class="button button-primary" id="submitEditMovieBtn">
                    <i class="fas fa-save"></i> Update Movie
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Game Modal-->
<div id="gameModal" class="admin-modal" onclick="closeGameModal()">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 600px;">
        <button type="button" class="modal-close-btn" onclick="closeGameModal()"><i class="fas fa-times"></i></button>
        <div class="section-header">
            <span class="eyebrow">Game Manager</span>
            <h2 id="gameModalTitle">Add Game</h2>
        </div>
        <form id="gameForm">
            <input type="hidden" id="game_id" name="game_id">
            <div style="display: grid; gap: 1rem;">
                <div>
                    <label>Title *</label>
                    <input type="text" name="title" id="game_title" required class="form-control" placeholder="e.g., Emoji Challenge">
                </div>
                <div>
                    <label>Description</label>
                    <textarea name="description" id="game_description" rows="3" class="form-control" placeholder="Short description of the game"></textarea>
                </div>
                <div>
                    <label>Game Type * </label>
                    <input type="text" name="game_type" id="game_type" required list="gameTypeSuggestions" class="form-control" placeholder="e.g., Emoji Challenge, Character Match, Movie Quotes, etc.">
                    <datalist id="gameTypeSuggestions">
                        <option value="Emoji Challenge">
                        <option value="Character Match">
                        <option value="Movie Quotes">
                        <option value="Movie Scenes">
                        <option value="Sound Clips">
                        <option value="Year Guess">
                    </datalist>
                  </div>
                <div>
                    <label>Icon (click to select)</label>
                    <div id="iconPicker" style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px; background: rgba(255,255,255,0.03); border-radius: 16px; padding: 12px; border: 1px solid rgba(255,255,255,0.1);">
                        <!-- icons will be populated by JS -->
                    </div>
                    <input type="hidden" name="icon" id="game_icon" value="fa-gamepad">
                    <small>Selected icon will appear in the games list</small>
                </div>
            </div>
            <div id="gameFormMessage" class="alert" style="display:none; margin-top:1rem;"></div>
        </form>
        <div class="admin-actions" style="justify-content: flex-end; margin-top:1.5rem;">
            <button type="button" class="button button-secondary" onclick="closeGameModal()">Cancel</button>
            <button type="button" class="button button-primary" id="submitGameBtn">Save Game</button>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="admin-modal">
    <div class="surface-card admin-modal-shell" style="max-width: 450px;">
        <button type="button" class="modal-close-btn" id="closeDeleteModal" aria-label="Close">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <div class="section-header" style="text-align: center;">
            <span class="eyebrow" style="background: rgba(251, 113, 133, 0.2); color: var(--danger);">
                <i class="fas fa-exclamation-triangle"></i> Warning
            </span>
            <h2 style="color: var(--danger);">Delete Item?</h2>
            <p>This action cannot be undone. Are you sure you want to permanently delete this item?</p>
        </div>
        <div class="admin-actions" style="justify-content: center; gap: 1rem; margin-top: 1rem;">
            <button class="button button-secondary cancel-delete-btn" id="cancelDeleteBtn">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="button button-primary confirm-delete-btn" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
</div>
<!-- Movie Detail Modal (same as homepage) -->
<div class="modal" id="card-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="modal-title">
  <div class="modal__backdrop" data-close-modal></div>
  <div class="modal__dialog surface-card" role="document">
    <button class="modal__close" id="modal-close" aria-label="Close dialog" data-close-modal>&times;</button>
    <div class="modal__media">
      <div id="trailer-container">
        <iframe id="modal-trailer" width="100%" height="315" src="" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen></iframe>
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
      <div class="comments-section">
        <h4>Reviews</h4>
        <div id="comments-list"></div>
      </div>
    </div>
  </div>
</div>


<!-- Questions Modal -->
<div id="questionsModal" class="admin-modal" onclick="closeQuestionsModal()">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 900px; max-height: 85vh; display: flex; flex-direction: column;">
        <button type="button" class="modal-close-btn" onclick="closeQuestionsModal()"><i class="fas fa-times"></i></button>
        <div class="section-header" style="flex-shrink: 0;">
            <span class="eyebrow">Game Questions</span>
            <h2 id="questionsModalTitle">Manage Questions</h2>
            <input type="hidden" id="currentGameId">
        </div>

        <!-- Scrollable question list -->
        <div id="questionsList" style="flex: 1; overflow-y: auto; margin: 1rem 0; padding-right: 0.5rem;">
            <!-- dynamically loaded questions -->
        </div>

        <!-- Sticky Add Button -->
        <div style="flex-shrink: 0; text-align: right; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
            <button type="button" class="button button-primary" id="openAddQuestionBtn">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>
    </div>
</div>
<!-- Question Form Modal (Add/Edit) -->
<div id="questionFormModal" class="admin-modal" onclick="closeQuestionFormModal()">
    <div class="surface-card admin-modal-shell" onclick="event.stopPropagation()" style="max-width: 700px;">
        <button type="button" class="modal-close-btn" onclick="closeQuestionFormModal()"><i class="fas fa-times"></i></button>
        <div class="section-header">
            <span class="eyebrow">Question Details</span>
            <h2 id="questionFormModalTitle">Add Question</h2>
        </div>
        <form id="questionForm">
            <input type="hidden" id="question_id">
            <div style="display: grid; gap: 1rem;">
                <div><label>Question Text</label><input type="text" id="q_text" class="form-control"></div>
                <div><label>Content *</label><textarea id="q_content" rows="2" class="form-control" required></textarea></div>
                <div><label>Options *</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                        <input type="text" id="opt_a" placeholder="Option A" class="form-control">
                        <input type="text" id="opt_b" placeholder="Option B" class="form-control">
                        <input type="text" id="opt_c" placeholder="Option C" class="form-control">
                        <input type="text" id="opt_d" placeholder="Option D" class="form-control">
                    </div>
                </div>
                <div><label>Correct Answer *</label>
                    <select id="q_correct" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div><label>Hint</label><input type="text" id="q_hint" class="form-control"></div>
                    <div><label>Points</label><input type="number" id="q_points" value="10" class="form-control"></div>
                </div>
            </div>
            <div id="questionFormMessage" class="alert" style="display:none; margin-top:1rem;"></div>
        </form>
        <div class="admin-actions" style="justify-content: flex-end; margin-top:1.5rem;">
            <button class="button button-secondary" onclick="closeQuestionFormModal()">Cancel</button>
            <button class="button button-primary" id="submitQuestionBtn">Save Question</button>
    </div>
  </div>
</div>
<style>
    .admin-table tbody tr.movie-row {
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .admin-table tbody tr.movie-row:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .3s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: var(--accent);
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    /* Reduce gap between tabs and table */
    .admin-tab-pane {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .admin-tab-pane .admin-data-card {
        margin-top: 0 !important;
    }
    
    .admin-tab-pane .d-flex.justify-content-end {
        margin-bottom: 0.75rem !important;
    }
    
    /* Fix modal z-index to appear above header */
    .admin-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999 !important;
        background: rgba(4, 6, 10, 0.85);
        backdrop-filter: blur(18px);
        display: none;
        place-items: center;
        padding: 1rem;
    }
    
    .admin-modal.is-open {
        display: grid;
    }
    
    .admin-modal-shell {
        position: relative;
        max-width: 700px;
        width: 100%;
        max-height: 85vh;
        overflow-y: auto;
        margin: auto;
        z-index: 10000 !important;
    }
    
    .modal-close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10002;
    }
    
    .modal-close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    header.site-header {
        z-index: 1000 !important;
    }
    
    body.modal-open {
        overflow: hidden !important;
    }
    /* Delete Confirmation Modal Styles */
#deleteConfirmModal .admin-modal-shell {
    max-width: 450px;
    text-align: center;
}

#deleteConfirmModal .section-header {
    margin-bottom: 0.5rem;
}

#deleteConfirmModal .section-header h2 {
    color: var(--danger);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

#deleteConfirmModal .section-header p {
    color: var(--text-muted);
    font-size: 0.9rem;
}

#deleteConfirmModal .admin-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

#deleteConfirmModal .cancel-delete-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: var(--text-color);
    padding: 0.6rem 1.5rem;
}

#deleteConfirmModal .cancel-delete-btn:hover {
    background: rgba(255, 255, 255, 0.15);
}

#deleteConfirmModal .confirm-delete-btn {
    background: linear-gradient(135deg, var(--danger), #dc2626);
    border: none;
    color: white;
    padding: 0.6rem 1.5rem;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

#deleteConfirmModal .confirm-delete-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
}

/* Warning icon */
#deleteConfirmModal .modal-close-btn {
    background: rgba(255, 255, 255, 0.1);
}

#imagePreviewModal .admin-modal-shell {
    max-width: 800px;
    background: linear-gradient(180deg, rgba(16, 20, 31, 0.98), rgba(12, 15, 24, 0.98));
    border: 1px solid rgba(255, 255, 255, 0.1);
}

#previewImage {
    transition: transform 0.2s ease;
    cursor: zoom-out;
}

#previewImage:hover {
    transform: scale(1.02);
}

#imagePreviewModal .modal-close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

#imagePreviewModal .modal-close-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.05);
}
.icon-option:hover {
    background: rgba(122, 92, 255, 0.2) !important;
    transform: scale(1.05);
    border-color: var(--accent) !important;
}
#deleteConfirmModal {
    z-index: 10001 !important;
}
</style>
@endsection
