@extends('layouts.app')

@section('title', 'Bookings | ReelTime')

@section('body-class')
bookings-page
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bookings.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/watchlist.js') }}" defer></script>
@endpush

@section('content')
<div id="searchOverlay" class="search-overlay">
  <div class="search-results"></div>
</div>

<main class="container py-4 py-lg-5 booking-shell">
  <section class="card border-secondary-subtle shadow-sm bg-dark mb-4">
    <div class="card-body p-4 p-lg-5">
      <div class="row g-4 align-items-center">
        <div class="col-lg-7">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">Book in minutes</span>
          <h1 class="display-6 fw-bold mb-3">Pick a cinema, choose a seat, and check out without losing context.</h1>
          <p class="text-secondary mb-0">
            ReelTime keeps the booking flow simple: choose your movie, set the time, reserve seats,
            and confirm in one continuous path.
          </p>
        </div>

        <div class="col-lg-5">
          <div class="row row-cols-2 row-cols-md-5 g-2">
            <div class="col"><div class="card h-100 text-center border-secondary-subtle bg-body-tertiary"><div class="card-body py-3 fw-semibold">Cinema</div></div></div>
            <div class="col"><div class="card h-100 text-center border-secondary-subtle bg-body-tertiary"><div class="card-body py-3 fw-semibold">Movie</div></div></div>
            <div class="col"><div class="card h-100 text-center border-secondary-subtle bg-body-tertiary"><div class="card-body py-3 fw-semibold">Date</div></div></div>
            <div class="col"><div class="card h-100 text-center border-secondary-subtle bg-body-tertiary"><div class="card-body py-3 fw-semibold">Seats</div></div></div>
            <div class="col"><div class="card h-100 text-center border-secondary-subtle bg-body-tertiary"><div class="card-body py-3 fw-semibold">Checkout</div></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="booking-layout">
    <article class="booking-panel booking-flow surface-card">
      <div class="booking-steps">
        <button class="stepsbtn default enabled" id="btn1" onclick="showStep(1)">1</button>
        <button class="stepsbtn" id="btn2" onclick="showStep(2)">2</button>
        <button class="stepsbtn" id="btn3" onclick="showStep(3)">3</button>
        <button class="stepsbtn" id="btn4" onclick="showStep(4)">4</button>
        <button class="stepsbtn" id="btn5" onclick="showStep(5)">5</button>
      </div>

      <div class="booking-stage">
        <div class="step-content default" id="step1">
          <select id="cinemasSelect" class="form-select form-select-lg bg-body-tertiary border-secondary-subtle text-light">
            <option value="SpotChoueifat">The Spot Choueifat</option>
            <option value="SpotSaida">The Spot Saida</option>
          </select>
          <button class="btn btn-warning fw-semibold" onclick="completeStep()">Submit</button>
        </div>

        <div class="step-content" id="step2">
          <select id="movieselect" class="form-select form-select-lg bg-body-tertiary border-secondary-subtle text-light">
            <option value="TheRunningMan">The Running Man</option>
            <option value="Predator:Badlands">Predator: Badlands</option>
            <option value="HardaBasht">HardaBasht</option>
            <option value="Jujutsu Kaisen:Execution">Jujutsu Kaisen: Execution</option>
            <option value="Playdate">Playdate</option>
            <option value="ElSelemWElThoban">El Selem W El Thoban</option>
          </select>
          <button class="btn btn-warning fw-semibold" onclick="completeStep()">Submit</button>
        </div>

        <div class="step-content" id="step3">
          <input type="date" id="dateselect" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light">
          <select id="timeselect" class="form-select form-select-lg bg-body-tertiary border-secondary-subtle text-light">
            <option value="1:00">1:00 pm</option>
            <option value="3:00">3:00 pm</option>
            <option value="6:00">6:00 pm</option>
            <option value="9:00">9:00 pm</option>
          </select>
          <button class="btn btn-warning fw-semibold" id="Datebtn" disabled>Submit</button>
          <div id="datecompletestep"></div>
        </div>

        <div class="step-content" id="step4">
          <div class="container">
            <div class="front-screen">
              <div class="screen"></div>
              <div class="overlay"></div>
            </div>
            <div class="seats">
              <div class="front-row first-front-row"></div>
              <div class="front-row second-front-row"></div>
              <div class="middle-row"></div>
              <div class="front-row second-last-row"></div>
              <div class="front-row first-last-row"></div>
            </div>
            <div class="legend">
              <div>
                <div class="seat available"></div>
                <span>Available</span>
              </div>
              <div>
                <div class="seat selected"></div>
                <span>Selected</span>
              </div>
              <div>
                <div class="seat reserved"></div>
                <span>Reserved</span>
              </div>
            </div>
          </div>
          <button class="btn btn-warning fw-semibold" id="reserveBtn" disabled onclick="completeStep()">Reserve</button>
        </div>

        <div class="step-content" id="step5">
          <input type="text" id="Name" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light" placeholder="Enter your full name">
          <input type="text" id="Email" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light" placeholder="Enter your email">
          <input type="text" id="PhoneNumber" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light" placeholder="Enter your phone number">
          <input type="text" id="CardNumber" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light" placeholder="Enter your card number">
          <input type="text" id="CVV" class="form-control form-control-lg bg-body-tertiary border-secondary-subtle text-light" placeholder="Enter your CVV">
          <button class="btn btn-warning fw-semibold" id="confirmbtn">Confirm</button>
          <div id="TotalPrice"></div>
          <div id="confirmation"></div>
        </div>
      </div>
    </article>

    <aside class="booking-panel booking-gallery surface-card">
      <div class="panel-header">
        <span class="eyebrow">Now showing</span>
        <p>Tap a poster for showtimes, trailer context, and quick actions.</p>
      </div>

      <section class="gallery2" id="gallery2">
        <div class="movie" id="movie">
          <figure class="movie-card" data-title="The Running Man"
            data-description="In a near-future society, The Running Man is the top-rated show on televisionâ€”a deadly competition where contestants, known as Runners, must survive 30 days while being hunted by professional assassins, with every move broadcast to a bloodthirsty public and each day bringing a greater cash reward."
            data-trailer-id="YOUR_TRAILER_ID_4"
            data-cast="Arnold Schwarzenegger, Maria Conchita Alonso, Richard Dawson"
            data-genres="Action, Sci-Fi, Thriller"
            data-this-movie-is="Intense, Violent, Futuristic"
            data-rating="4.3"
            data-time="115 min">
            <img src="{{ asset('imgs/therunningman-min.png') }}" alt="The Running Man">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>

          <figure class="movie-card" data-title="Predator:Badlands"
            data-description="A young Predator outcast from his clan finds an unlikely ally on his journey in search of the ultimate adversary."
            data-trailer-id="YOUR_TRAILER_ID_4"
            data-cast="Elle Fanning, ravi Narayan, Micheal Homik"
            data-genres="Action, Sci-Fi, Horror"
            data-this-movie-is="Gory, Suspenseful, Alien Hunt"
            data-rating="3.3"
            data-time="125 min">
            <img src="{{ asset('imgs/predator badlands-min.png') }}" alt="Predator: Badlands">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>

          <figure class="movie-card" data-title="HardaBasht"
            data-description="A mother and her 3 boys live in the poor suburbs of Beirut. The youngest boy is religiously committed unlike his two older brothers who are drug dealers. They force their younger brother to work for them because of his clean reputation. A series of events bring chaos to the family and the whole neighborhood."
            data-trailer-id="YOUR_TRAILER_ID_4"
            data-cast="Randa Kaady, Alexandra Kahwagi, Hussein Kaouk"
            data-genres="Drama, Family, Crime"
            data-this-movie-is="Emotional, Lebanese, Gritty"
            data-rating="4.1"
            data-time="155 min">
            <img src="{{ asset('imgs/hardabsht-min.png') }}" alt="HardaBasht poster">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>

          <figure class="movie-card" data-title="Jujutsu Kaisen:Execution"
            data-description="A veil abruptly descends over the busy Shibuya area amid the bustling Halloween crowds, trapping countless civilians inside. Satoru Gojo, steps into the chaos. But in wait curse users scheming to seal him away. Yuji Itadori and jujutsu sorcerers, enters the Shibuya Incident. Be the first to experience Yuji and Yutaâ€™s fateful battle with the hotly anticipated kickoff to Season 3 in theatres nationwide."
            data-trailer-id="YOUR_TRAILER_ID_5"
            data-cast="Adam McArthur, Jun'ya Enoki, Yuchi Nakamura"
            data-genres="Action, Fantasy, Anime"
            data-this-movie-is="Exciting, Supernatural, Intense"
            data-rating="4.7"
            data-time="175 min">
            <img src="{{ asset('imgs/jujutsu kaisen-min.png') }}" alt="Jujutsu Kaisen:Execution poster">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>

          <figure class="movie-card" data-title="Playdate"
            data-description="After being fired from his job, BRIAN, instantly becomes a reluctant stay-at-home dad to his step son. On his very first day, Brian accepts a random invitation for a playdate from another stay-at-home dad, JEFF. Jeff definitely seems a little bitâ€¦off. And as the playdate begins, Jeff turns out to be someone Brian never expected and they immediately have to go on the run from a very dangerous situation. Itâ€™s a playdate from hell as the two fathers and their sons spend the day trying not to get killed and solve a conspiracy."
            data-trailer-id="YOUR_TRAILER_ID_6"
            data-cast="Alan Ritchson, Kevin James, Banks Peirce"
            data-genres="Comedy, Action, Thriller"
            data-this-movie-is="Funny, Suspenseful, Action-Packed"
            data-rating="4.2"
            data-time="185 min">
            <img src="{{ asset('imgs/playdate-min.png') }}" alt="Playdate poster">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>

          <figure class="movie-card" data-title="El Selem W El Thoban"
            data-description="In Snake and Ladder, love and ambition intertwine as Ahmed (Amr Youssef), a creative architect, and Malak (Asmaa Galal), a driven entrepreneur, struggle to reconcile who theyâ€™ve become with who they were together. When distance and new relationshipsâ€”like Malakâ€™s rekindled connection with Amir (Dhafer Lâ€™Abidine)â€”test their bond, both are forced to confront lost versions of themselves and the choices that shaped their paths."
            data-trailer-id="GRm2_FzP1m0"
            data-cast="Amr Youssef, Asmaa Galal, Dhafer L'Abidine"
            data-genres="Romance, Drama"
            data-this-movie-is="Emotional, Romantic, Thought-Provoking"
            data-rating="2.3"
            data-time="168 min">
            <img src="{{ asset('imgs/El Selem W El Thoban-min.png') }}" alt="El Selem W El Thoban">
            <button class="showShowTimes-btn" type="button">Show showtimes</button>
          </figure>
        </div>
      </section>
    </aside>
  </section>
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
        <p class="movieshowtime"><strong>Showtimes:</strong></p>
        <div class="showtimes">
          <span class="showtime-pill">1:00pm</span>
          <span class="showtime-pill">3:00pm</span>
          <span class="showtime-pill">6:00pm</span>
          <span class="showtime-pill">9:00pm</span>
        </div>
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

@push('scripts')
<script>
 document.querySelectorAll(".gallery2 .movie-card").forEach(card => {
    let title = card.dataset.title
      || card.querySelector("figcaption")?.textContent.trim()
      || "";
    let desc   = card.dataset.description || "";
    let rating = card.dataset.rating || "N/A";
    let time = card.dataset.time || "N/A";
    let overlay = document.createElement("div");
    overlay.className = "movie-overlay";

    overlay.innerHTML = `
      <p class="movie-overlay-title">${title}</p>
      <p class="movie-overlay-desc">${desc}</p>
      <div class="movie-overlay-bottom">
        <span class="film-overlay">${time}</span>
        <span class="movie-overlay-rating">${rating} / 5 ★</span>
      </div>
    `;

    card.appendChild(overlay);
  });
</script>
@endpush
@endsection
