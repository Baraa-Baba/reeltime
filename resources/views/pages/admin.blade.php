@extends('layouts.app')

@section('title', 'Admin Dashboard | ReelTime')

@section('body-class')
admin-page
@endsection

@section('content')
<main class="container py-4 py-lg-5">
  <section class="card border-secondary-subtle shadow-sm bg-dark mb-4">
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg">
          <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
            <img src="{{ $user->profile_image }}" alt="Admin" class="rounded-circle border border-3 border-warning object-fit-cover" width="88" height="88">
            <div>
              <span class="badge text-bg-warning text-dark rounded-pill mb-2">Administrator</span>
              <h1 class="display-6 fw-bold mb-2">Welcome back, {{ $user->username }}</h1>
              <p class="text-secondary mb-0">Manage movies, games, users, and bookings from one clean dashboard.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-auto">
          <div class="d-flex flex-wrap gap-2">
            <button type="button" onclick="openModal('addMovieModal')" class="btn btn-warning fw-semibold">
              <i class="fas fa-plus me-1"></i> Add Movie
            </button>
            <button type="button" onclick="openModal('addGameModal')" class="btn btn-outline-warning fw-semibold">
              <i class="fas fa-plus me-1"></i> Add Game
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    <div class="col">
      <div class="card border-secondary-subtle bg-dark h-100 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-grid place-items-center" style="width:56px;height:56px;">
            <i class="fas fa-film fs-4"></i>
          </div>
          <div>
            <div class="text-secondary small">Total Movies</div>
            <div class="fs-3 fw-bold">{{ $movieCount }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-secondary-subtle bg-dark h-100 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-grid place-items-center" style="width:56px;height:56px;">
            <i class="fas fa-gamepad fs-4"></i>
          </div>
          <div>
            <div class="text-secondary small">Total Games</div>
            <div class="fs-3 fw-bold">{{ $gameCount }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-secondary-subtle bg-dark h-100 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-grid place-items-center" style="width:56px;height:56px;">
            <i class="fas fa-users fs-4"></i>
          </div>
          <div>
            <div class="text-secondary small">Total Users</div>
            <div class="fs-3 fw-bold">{{ $userCount }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card border-secondary-subtle bg-dark h-100 shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-grid place-items-center" style="width:56px;height:56px;">
            <i class="fas fa-ticket-alt fs-4"></i>
          </div>
          <div>
            <div class="text-secondary small">Total Bookings</div>
            <div class="fs-3 fw-bold">{{ $bookingCount }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <nav class="nav nav-tabs border-secondary-subtle mb-4" role="tablist" aria-label="Admin sections">
    <button class="nav-link active" id="moviesTabBtn" type="button" onclick="switchTab('movies')">
      <i class="fas fa-film me-2"></i> Movies
    </button>
    <button class="nav-link" id="gamesTabBtn" type="button" onclick="switchTab('games')">
      <i class="fas fa-gamepad me-2"></i> Games
    </button>
    <button class="nav-link" id="bookingsTabBtn" type="button" onclick="switchTab('bookings')">
      <i class="fas fa-ticket-alt me-2"></i> Bookings
    </button>
    <button class="nav-link" id="usersTabBtn" type="button" onclick="switchTab('users')">
      <i class="fas fa-users me-2"></i> Users
    </button>
  </nav>

  <section id="moviesTab" class="admin-tab-pane">
    <div class="card border-secondary-subtle bg-dark shadow-sm">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="text-warning small text-uppercase">ID</th>
              <th class="text-warning small text-uppercase">Poster</th>
              <th class="text-warning small text-uppercase">Title</th>
              <th class="text-warning small text-uppercase">Rating</th>
              <th class="text-warning small text-uppercase">Duration</th>
              <th class="text-warning small text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($movies as $movie)
              <tr>
                <td>{{ $movie->movie_id }}</td>
                <td>
                  @if($movie->poster)
                    <img src="{{ asset($movie->poster) }}" class="rounded-3 object-fit-cover" width="52" height="52" alt="Poster">
                  @else
                    <div class="rounded-3 bg-secondary-subtle" style="width:52px;height:52px;"></div>
                  @endif
                </td>
                <td>{{ $movie->title }}</td>
                <td>{{ $movie->rating }}</td>
                <td>{{ $movie->duration }} min</td>
                <td>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-secondary py-4">No movies yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section id="gamesTab" class="admin-tab-pane d-none">
    <div class="card border-secondary-subtle bg-dark shadow-sm">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="text-warning small text-uppercase">ID</th>
              <th class="text-warning small text-uppercase">Icon</th>
              <th class="text-warning small text-uppercase">Title</th>
              <th class="text-warning small text-uppercase">Type</th>
              <th class="text-warning small text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($games as $game)
              <tr>
                <td>{{ $game->game_id }}</td>
                <td>
                  <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-grid place-items-center" style="width:44px;height:44px;">
                    <i class="fas {{ $game->icon }}"></i>
                  </div>
                </td>
                <td>{{ $game->title }}</td>
                <td>{{ $game->game_type }}</td>
                <td>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-secondary py-4">No games yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section id="bookingsTab" class="admin-tab-pane d-none">
    <div class="card border-secondary-subtle bg-dark shadow-sm">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="text-warning small text-uppercase">ID</th>
              <th class="text-warning small text-uppercase">User</th>
              <th class="text-warning small text-uppercase">Movie</th>
              <th class="text-warning small text-uppercase">Poster</th>
              <th class="text-warning small text-uppercase">Date</th>
              <th class="text-warning small text-uppercase">Status</th>
              <th class="text-warning small text-uppercase">Actions</th>
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
                  <img src="{{ asset($booking->showtime->movie->poster) }}" class="rounded-3 object-fit-cover" width="52" height="52" alt="Poster">
                </td>
                <td>{{ $booking->booking_date }}</td>
                <td>
                  <span class="badge
                    @if($bookingStatus === 'confirmed') text-bg-success
                    @elseif($bookingStatus === 'cancelled') text-bg-danger
                    @else text-bg-warning text-dark @endif">
                    {{ $booking->status }}
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-secondary py-4">No bookings yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section id="usersTab" class="admin-tab-pane d-none">
    <div class="card border-secondary-subtle bg-dark shadow-sm">
      <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
          <thead>
            <tr>
              <th class="text-warning small text-uppercase">ID</th>
              <th class="text-warning small text-uppercase">Avatar</th>
              <th class="text-warning small text-uppercase">Username</th>
              <th class="text-warning small text-uppercase">Email</th>
              <th class="text-warning small text-uppercase">Member Since</th>
              <th class="text-warning small text-uppercase">Role</th>
              <th class="text-warning small text-uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $member)
              <tr>
                <td>{{ $member->user_id }}</td>
                <td>
                  @if($member->profile_image)
                    <img src="{{ asset($member->profile_image) }}" class="rounded-circle object-fit-cover" width="40" height="40" alt="{{ $member->username }}">
                  @else
                    <div class="rounded-circle bg-secondary-subtle" style="width:40px;height:40px;"></div>
                  @endif
                </td>
                <td>{{ $member->username }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->member_since }}</td>
                <td>
                  <span class="badge {{ $member->role === 'admin' ? 'text-bg-warning text-dark' : 'text-bg-info text-dark' }}">
                    {{ $member->role }}
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-secondary py-4">No users yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>

<div id="addMovieModal" class="position-fixed top-0 start-0 w-100 h-100 bg-black bg-opacity-75 d-none align-items-center justify-content-center p-3" onclick="closeModal('addMovieModal')">
  <div class="container" onclick="event.stopPropagation()">
    <div class="card border-secondary-subtle shadow-lg bg-dark w-100" style="max-width: 560px;">
      <div class="card-body p-4 p-lg-5">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
          <div>
            <span class="badge text-bg-warning text-dark rounded-pill mb-2">Movie tools</span>
            <h2 class="h4 fw-bold mb-2">Add movie</h2>
            <p class="text-secondary mb-0">The create form can be wired in here when you’re ready.</p>
          </div>
          <button type="button" class="btn btn-outline-light btn-sm rounded-circle" onclick="closeModal('addMovieModal')" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="addGameModal" class="position-fixed top-0 start-0 w-100 h-100 bg-black bg-opacity-75 d-none align-items-center justify-content-center p-3" onclick="closeModal('addGameModal')">
  <div class="container" onclick="event.stopPropagation()">
    <div class="card border-secondary-subtle shadow-lg bg-dark w-100" style="max-width: 560px;">
      <div class="card-body p-4 p-lg-5">
        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
          <div>
            <span class="badge text-bg-warning text-dark rounded-pill mb-2">Game tools</span>
            <h2 class="h4 fw-bold mb-2">Add game</h2>
            <p class="text-secondary mb-0">The create form can be wired in here when you’re ready.</p>
          </div>
          <button type="button" class="btn btn-outline-light btn-sm rounded-circle" onclick="closeModal('addGameModal')" aria-label="Close">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function switchTab(tab) {
    const tabs = ['movies', 'games', 'bookings', 'users'];
    tabs.forEach((name) => {
      const content = document.getElementById(`${name}Tab`);
      const btn = document.getElementById(`${name}TabBtn`);
      const isActive = name === tab;

      if (content) {
        content.classList.toggle('d-none', !isActive);
      }

      if (btn) {
        btn.classList.toggle('active', isActive);
      }
    });
  }

  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.remove('d-none');
    modal.classList.add('d-flex');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    modal.classList.add('d-none');
    modal.classList.remove('d-flex');
    document.body.style.overflow = '';
  }

  document.addEventListener('DOMContentLoaded', () => switchTab('movies'));
</script>
@endsection
