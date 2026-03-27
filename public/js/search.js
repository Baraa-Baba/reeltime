$(function () {
    const $input = $("#mainSearchInput");
    const $btn = $("#mainSearchBtn");
    const $grid = $("#searchResults");
    const $empty = $("#searchEmpty");
    const $count = $("#resultsCount");
    const $sort = $("#searchSort");

    let allMovies = [];
    let watchlistTitles = new Set();

    function refreshWatchlistTitles() {
        try {
            const userData = JSON.parse(sessionStorage.getItem("loggedInUser"));
            if (!userData) {
                watchlistTitles = new Set();
                return;
            }

            const saved = JSON.parse(localStorage.getItem("watchlist")) || [];
            watchlistTitles = new Set(
                saved.filter(item => item.username === userData.username).map(item => item.title)
            );
        } catch (err) {
            console.error("Error building watchlist titles:", err);
            watchlistTitles = new Set();
        }
    }

    window.addEventListener("watchlist-updated", () => {
        refreshWatchlistTitles();
        triggerSearch();
    });

    $.getJSON("../data/movies.json")
        .done(function (data) {
            allMovies = [];
            window.movieComments = window.movieComments || [];

            (data.categories || []).forEach((cat) => {
                (cat.movies || []).forEach((movie) => {
                    allMovies.push(movie);
                    if (Array.isArray(movie.comments) && movie.comments.length) {
                        window.movieComments[movie.title] = movie.comments;
                    }
                });
            });

            refreshWatchlistTitles();
            triggerSearch();
        })
        .fail(function () {
            $count.text("Couldn't load movies.");
        });

    function resolveImagePath(image) {
        if (!image) return "../imgs/default-movie.jpg";
        if (image.startsWith("../")) return image;
        if (image.startsWith("/")) return ".." + image;
        return "../" + image.replace(/^\/+/, "");
    }

    function applySort(list, mode) {
        const copy = [...list];

        if (mode === "title-asc") {
            copy.sort((a, b) => a.title.localeCompare(b.title));
        } else if (mode === "title-desc") {
            copy.sort((a, b) => b.title.localeCompare(a.title));
        } else if (mode === "rating-desc") {
            copy.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        } else if (mode === "rating-asc") {
            copy.sort((a, b) => (a.rating || 0) - (b.rating || 0));
        }

        return copy;
    }

    function renderResults(list) {
        $grid.empty();

        if (!list.length) {
            $count.text("0 results");
            if ($empty.length) $empty.show();
            return;
        }

        if ($empty.length) $empty.hide();
        $count.text(`${list.length} result${list.length !== 1 ? "s" : ""} found`);

        list.forEach((movie) => {
            const imgSrc = resolveImagePath(movie.image);
            const rating = movie.rating ?? "N/A";
            const inWatchlist = watchlistTitles.has(movie.title);
            const description = movie.description || movie.overview || "";
            const cast = Array.isArray(movie.cast) ? movie.cast.join(", ") : (movie.cast || "");
            const genres = Array.isArray(movie.genres) ? movie.genres.join(", ") : (movie.genre || movie.genres || "");
            const mood = Array.isArray(movie.tags) ? movie.tags.join(", ") : (movie.thisMovieIs || movie.tags || "");

            const $card = $(`
              <div class="col">
                <div class="card h-100 bg-dark border-secondary-subtle shadow-sm movie-card position-relative"
                     data-title="${movie.title}"
                     data-description="${movie.description || ''}"
                     data-rating="${rating}"
                     data-cast="${cast}"
                     data-genres="${genres}"
                     data-this-movie-is="${mood}">
                  <div class="position-relative">
                    <img src="${imgSrc}" class="card-img-top" alt="${movie.title}" onerror="this.src='../imgs/default-movie.jpg'">
                    <span class="watch-flag badge rounded-pill text-bg-warning position-absolute top-0 end-0 m-2 ${inWatchlist ? 'in-watchlist' : ''}"
                          data-title="${movie.title}"
                          title="${inWatchlist ? 'In your watchlist' : 'Not in watchlist'}">
                      <i class="${inWatchlist ? 'fa-solid' : 'fa-regular'} fa-heart"></i>
                    </span>
                  </div>
                  <div class="card-body d-flex flex-column">
                    <h3 class="h5 fw-bold mb-2">${movie.title || ""}</h3>
                    <p class="text-secondary small mb-3 flex-grow-1">${description}</p>
                    <div class="d-flex justify-content-between gap-3 small">
                      <span class="text-secondary"><i class="far fa-clock me-1"></i>${movie.time || ""}</span>
                      <span class="text-warning fw-semibold">${rating} / 5 ★</span>
                    </div>
                  </div>
                </div>
              </div>
            `);

            $grid.append($card);
        });
    }

    function triggerSearch() {
        const q = $input.val().toLowerCase().trim();
        const sortMode = $sort.val() || "relevance";

        let filtered = allMovies.filter((movie) => {
            if (!q) return true;
            const haystack = [
                movie.title,
                movie.genre,
                movie.description,
                (movie.year || "").toString()
            ].join(" ").toLowerCase();

            return haystack.includes(q);
        });

        filtered = applySort(filtered, sortMode);
        renderResults(filtered);
    }

    $btn.on("click", function (e) {
        e.preventDefault();
        triggerSearch();
    });

    $input.on("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            triggerSearch();
        }
    });

    $input.on("input", triggerSearch);
    $sort.on("change", triggerSearch);
});
