$(function () {
    const $input = $("#mainSearchInput");
    const $btn = $("#mainSearchBtn");
    const $grid = $("#searchResults");
    const $empty = $("#searchEmpty");
    const $count = $("#resultsCount");
    const $sort = $("#searchSort");

    let allMovies = [];
    let watchlistTitles = new Set();
    window.movieComments = window.movieComments || {};

    function getCurrentUser() {
        if (window.authUser) {
            return window.authUser;
        }

        try {
            return JSON.parse(sessionStorage.getItem("loggedInUser"));
        } catch (err) {
            console.error("Error parsing loggedInUser:", err);
            return null;
        }
    }

    function escapeHtml(value) {
        return String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;");
    }

    function refreshWatchlistTitles() {
        try {
            const userData = JSON.parse(sessionStorage.getItem('loggedInUser'));
            if (!userData || !userData.watchlist) {
                watchlistTitles = new Set();
                return;
            }

            // Use API-based watchlist from sessionStorage
            watchlistTitles = new Set(
                userData.watchlist.map(item => item.movie_id)
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

    if (Array.isArray(window.searchMovies)) {
        allMovies = window.searchMovies;

        allMovies.forEach((movie) => {
            if (Array.isArray(movie.comments) && movie.comments.length) {
                window.movieComments[movie.title] = movie.comments;
            }
        });

        refreshWatchlistTitles();
        triggerSearch();
    } else {
        $count.text("Couldn't load movies.");
    }

    function resolveImagePath(image) {
        if (!image) return "../imgs/default-movie.jpg";
        if (/^(https?:)?\/\//.test(image)) return image;
        if (image.startsWith("../") || image.startsWith("./")) return image;
        if (image.startsWith("/")) return image;
        return "/" + image.replace(/^\/+/, "");
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
            const title = movie.title || "";
            const description = movie.description || movie.overview || "";
            const rating = movie.rating ?? "-";
            const ratingValue = Number(rating);
            const showRating = Number.isFinite(ratingValue) ? ratingValue > 0 : true;
            const runtime = movie.time || (movie.duration ? `${movie.duration} min` : "");
            const cast = Array.isArray(movie.cast) ? movie.cast.join(", ") : (movie.cast || "");
            const genres = Array.isArray(movie.genres) ? movie.genres.join(", ") : (movie.genre || movie.genres || "");
            const mood = Array.isArray(movie.tags) ? movie.tags.join(", ") : (movie.thisMovieIs || movie.tags || "");
            const imgSrc = resolveImagePath(movie.poster_url || movie.image);
            const movieId = movie.movie_id || null;
            const trailerUrl = movie.trailer_url || "";
            const modalShowtimes = Array.isArray(movie.showtimes)
                ? movie.showtimes.map((showtime) => showtime.display).filter(Boolean)
                : [];

            const $card = $(`
                <figure class="movie-card search-result-card"
                    role="button"
                    tabindex="0"
                    data-title="${escapeHtml(title)}"
                    data-movie-id="${escapeHtml(movieId || '')}"
                    data-description="${escapeHtml(description)}"
                    data-trailer-url="${escapeHtml(trailerUrl)}"
                    data-rating="${escapeHtml(rating)}"
                    data-cast="${escapeHtml(cast)}"
                    data-genres="${escapeHtml(genres)}"
                    data-this-movie-is="${escapeHtml(mood)}"
                    data-time="${escapeHtml(runtime)}"
                    data-showtimes='${escapeHtml(JSON.stringify(modalShowtimes))}'>
                    ${showRating ? `<span class="rating-overlay">${escapeHtml(rating)} / 5</span>` : ""}
                    <img src="${imgSrc}" alt="${escapeHtml(title)} poster" onerror="this.src='../imgs/default-movie.jpg'">
                    <button class="showShowTimes-btn" type="button">View showtimes</button>
                    <div class="movie-overlay">
                        <p class="movie-overlay-title">${escapeHtml(title)}</p>
                        <p class="movie-overlay-desc">${escapeHtml(description)}</p>
                        <div class="movie-overlay-bottom">
                            <span class="film-overlay">${escapeHtml(runtime)}</span>
                            ${showRating ? `<span class="movie-overlay-rating">${escapeHtml(rating)} / 5 stars</span>` : ""}
                        </div>
                    </div>
                </figure>
            `);

            $grid.append($card);
        });
    }

    function triggerSearch() {
        const q = $input.val().toLowerCase().trim();
        const sortMode = $sort.val() || "relevance";

        let filtered = allMovies.filter((movie) => {
            if (!q) return true;
            const genresText = Array.isArray(movie.genres) ? movie.genres.join(" ") : (movie.genres || "");
            const tagsText = Array.isArray(movie.tags) ? movie.tags.join(" ") : (movie.tags || "");
            const haystack = [
                movie.title,
                movie.genre,
                movie.description,
                genresText,
                tagsText,
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

    $(document).on("click", "#searchResults .showShowTimes-btn", function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (typeof openMovieModal === "function") {
            openMovieModal(this);
        }
    });

    // Handle movie card clicks to open modal
    $(document).on("click", "#searchResults .movie-card", function(e) {
        if ($(e.target).closest('.showShowTimes-btn').length) {
            return;
        }
        if (typeof openMovieModal === "function") {
            openMovieModal(this);
        }
    });

    $(document).on("keydown", "#searchResults .movie-card", function(e) {
        if (e.key !== "Enter" && e.key !== " ") {
            return;
        }

        e.preventDefault();

        if (typeof openMovieModal === "function") {
            openMovieModal(this);
        }
    });
});
