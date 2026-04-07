
$(function() {
    // Only initialize if user is logged in
    if (sessionStorage.getItem('loggedInUser')) {
        initializeWatchlist();
    }
    setupWatchlistEventHandlers();  
});

function getCSRFToken() {
    // Try to get CSRF token from meta tag
    let token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) {
        // Try to get from cookie
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'XSRF-TOKEN') {
                token = decodeURIComponent(value);
                break;
            }
        }
    }
    return token;
}

function initializeWatchlist() {
    const userData = sessionStorage.getItem('loggedInUser');
    
    if (!userData) {
        return;
    }
    
    try {
        const user = JSON.parse(userData);
        if (!user) {
           return;
        }
        
        // Load watchlist from API
        loadWatchlistFromAPI();
    } catch (error) {
        console.error('Error initializing watchlist:', error);
    }
}

function loadWatchlistFromAPI() {
    const userData = JSON.parse(sessionStorage.getItem('loggedInUser'));
    if (!userData || !userData.id) return;

    const csrfToken = getCSRFToken();

    fetch('/api/watchlist', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken || ''
        },
        credentials: 'include'
    })
    .then(response => {
        if (!response.ok) throw new Error('Failed to fetch watchlist');
        return response.json();
    })
    .then(data => {
        // Handle paginated response from Laravel
        const watchlistData = data.data || data;
        
        if (Array.isArray(watchlistData)) {
            userData.watchlist = watchlistData.map(item => ({
                watchlist_id: item.watchlist_id,
                movie_id: item.movie_id,
                title: item.movie?.title || '',
                image: item.movie?.poster || '',
                rating: item.movie?.rating || '0'
            }));
        } else {
            userData.watchlist = [];
        }
        sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
        
        // Update UI to show correct button states
        updateWatchlistUIOnAllPages();
    })
    .catch(error => {
        console.error('Error loading watchlist:', error);
        // Initialize empty watchlist on error
        if (!userData.watchlist) userData.watchlist = [];
        sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
    });
}

function updateWatchlistUIOnAllPages() {
    const userData = JSON.parse(sessionStorage.getItem('loggedInUser'));
    if (!userData || !userData.watchlist) return;

    // Update all watchlist buttons across the page
    $('.add-watchlist-btn').each(function() {
        const $btn = $(this);
        const movieId = $btn.data('movie-id');
        const movieTitle = $('#modal-title').text().trim() || $btn.data('title');
        
        const inWatchlist = userData.watchlist.some(m => m.movie_id == movieId);
        
        if (inWatchlist) {
            $btn.text('Remove from Watchlist').addClass('added');
        } else {
            $btn.text('+ Add to Watchlist').removeClass('added').css('background', '');
        }
    });

    // Update all watch-flag icons
    $('.watch-flag').each(function() {
        const $flag = $(this);
        const $card = $flag.closest('.movie-card');
        const movieId = $flag.data('movie-id') || $card.data('movie-id');
        
        const inWatchlist = userData.watchlist.some(m => m.movie_id == movieId);
        
        if (inWatchlist) {
            $flag.addClass('in-watchlist');
            $flag.find('.fa-heart')
                 .removeClass('fa-regular')
                 .addClass('fa-solid');
        } else {
            $flag.removeClass('in-watchlist');
            $flag.find('.fa-heart')
                 .removeClass('fa-solid')
                 .addClass('fa-regular');
        }
    });
}

function setupWatchlistEventHandlers() {
    $(document).off('click', '.add-watchlist-btn').on('click', '.add-watchlist-btn', function(event) {
        event.preventDefault();
        event.stopPropagation();
        handleWatchlistAdd($(this));
    });
    $(document).off('click', '.watch-flag').on('click', '.watch-flag', function (event) {
        event.preventDefault();
        event.stopPropagation();
        handleWatchlistToggleFromCard($(this));
    });
    
    // Listen for watchlist updates from other tabs/windows
    window.addEventListener('storage', function(e) {
        if (e.key === 'loggedInUser') {
            loadWatchlistFromAPI();
        }
    });
    
    // Listen for watchlist-updated custom event
    window.addEventListener('watchlist-updated', function() {
        // Delay slightly to ensure state is updated
        setTimeout(() => {
            updateWatchlistUIOnAllPages();
        }, 100);
    });
}

function handleWatchlistAdd($button) {
    // Check if user is logged in
    const userData = JSON.parse(sessionStorage.getItem('loggedInUser'));
    if (!userData || !userData.id) {
        alert('Please log in to use watchlist');
        return;
    }
   
    const movieTitle = $('#modal-title').text().trim();
    
    // Get movie ID from data attribute or search for it
    let movieId = $button.data('movie-id') || null;
    
    if (!movieId) {
        // Try to find movie ID from the movie card
        const $movieCard = $(`.movie-card[data-title="${movieTitle}"]`).first();
        movieId = $movieCard.data('movie-id') || null;
    }

    if (!movieId) {
        // Query API to find movie by title
        fetch(`/api/movies?search=${encodeURIComponent(movieTitle)}`, {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                const movie = data.data[0];
                toggleWatchlistAPI(movie.movie_id, $button);
            } else {
                alert('Movie not found');
            }
        })
        .catch(error => {
            console.error('Error searching for movie:', error);
            alert('Error adding to watchlist');
        });
    } else {
        toggleWatchlistAPI(movieId, $button);
    }
}

function toggleWatchlistAPI(movieId, $button) {
    const userData = JSON.parse(sessionStorage.getItem('loggedInUser'));
    const csrfToken = getCSRFToken();
    
    if (!userData || !userData.id) {
        alert('Please log in to use watchlist');
        return;
    }
    
    // Ensure watchlist array exists
    if (!userData.watchlist) userData.watchlist = [];
    
    // Check if already in watchlist
    const watchlistItem = userData.watchlist.find(m => m.movie_id == movieId);
    const inWatchlist = !!watchlistItem;
    
    if (inWatchlist) {
        // Remove from watchlist
        if (!watchlistItem.watchlist_id) {
            alert('Error: Cannot remove from watchlist');
            return;
        }
        
        fetch(`/api/watchlist/${watchlistItem.watchlist_id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'include'
        })
        .then(response => {
            if (response.ok) {
                // Remove from local state
                userData.watchlist = userData.watchlist.filter(m => m.movie_id != movieId);
                sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
                
                // Update UI
                $button.text('+ Add to Watchlist').removeClass('added').css('background', '');
                window.dispatchEvent(new CustomEvent('watchlist-updated'));
                updateWatchlistUIOnAllPages();
            } else {
                alert('Error removing from watchlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing from watchlist');
        });
    } else {
        // Add to watchlist
        fetch('/api/watchlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'include',
            body: JSON.stringify({ movie_id: movieId })
        })
        .then(response => {
            if (response.status === 409) {
                // Already in watchlist - reload from API
                loadWatchlistFromAPI();
                return null;
            }
            if (response.ok) {
                return response.json();
            }
            throw new Error('Failed to add to watchlist');
        })
        .then(data => {
            if (data && data.watchlist) {
                // Add to local state
                userData.watchlist.push({
                    watchlist_id: data.watchlist.watchlist_id,
                    movie_id: data.watchlist.movie_id
                });
                sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
                
                // Update UI
                $button.text('Remove from Watchlist').addClass('added');
                window.dispatchEvent(new CustomEvent('watchlist-updated'));
                updateWatchlistUIOnAllPages();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding to watchlist');
        });
    }
} 
function handleWatchlistToggleFromCard($flag) {
    const userDataRaw = sessionStorage.getItem('loggedInUser');
    if (!userDataRaw) {
        alert('Please log in to use your watchlist.');
        return;
    }
    const userData = JSON.parse(userDataRaw);

    // Get movie ID from data attribute
    const $card = $flag.closest('.movie-card');
    let movieId = $flag.data('movie-id') || $card.data('movie-id') || null;
    const movieTitle = $flag.data('title') || $card.data('title') || '';

    if (!movieId) {
        // Query API to find movie by title
        fetch(`/api/movies?search=${encodeURIComponent(movieTitle)}`, {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                const movie = data.data[0];
                toggleWatchlistFromCardAPI(movie.movie_id, $flag, userData);
            }
        })
        .catch(error => console.error('Error searching for movie:', error));
    } else {
        toggleWatchlistFromCardAPI(movieId, $flag, userData);
    }
}

function toggleWatchlistFromCardAPI(movieId, $flag, userData) {
    const csrfToken = getCSRFToken();
    
    // Ensure watchlist array exists
    if (!userData.watchlist) userData.watchlist = [];
    
    const watchlistItem = userData.watchlist.find(m => m.movie_id == movieId);
    const inWatchlist = !!watchlistItem;

    if (inWatchlist) {
        // REMOVE from watchlist
        if (!watchlistItem.watchlist_id) return;

        fetch(`/api/watchlist/${watchlistItem.watchlist_id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'include'
        })
        .then(response => {
            if (response.ok) {
                userData.watchlist = userData.watchlist.filter(m => m.movie_id != movieId);
                sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
                
                $flag.removeClass('in-watchlist');
                $flag.find('.fa-heart')
                     .removeClass('fa-solid')
                     .addClass('fa-regular');
                
                window.dispatchEvent(new CustomEvent('watchlist-updated'));
                updateWatchlistUIOnAllPages();
            }
        })
        .catch(error => console.error('Error removing from watchlist:', error));

    } else {
        // ADD to watchlist
        fetch('/api/watchlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            credentials: 'include',
            body: JSON.stringify({ movie_id: movieId })
        })
        .then(response => {
            if (response.status === 409) {
                // Already in watchlist - reload
                loadWatchlistFromAPI();
                return null;
            }
            if (response.ok) return response.json();
            throw new Error('Failed to add to watchlist');
        })
        .then(data => {
            if (data && data.watchlist) {
                if (!userData.watchlist) userData.watchlist = [];
                userData.watchlist.push({
                    watchlist_id: data.watchlist.watchlist_id,
                    movie_id: data.watchlist.movie_id
                });
                sessionStorage.setItem('loggedInUser', JSON.stringify(userData));
                
                $flag.addClass('in-watchlist');
                $flag.find('.fa-heart')
                     .removeClass('fa-regular')
                     .addClass('fa-solid');
                
                window.dispatchEvent(new CustomEvent('watchlist-updated'));
                updateWatchlistUIOnAllPages();
            }
        })
        .catch(error => console.error('Error adding to watchlist:', error));
    }
}
