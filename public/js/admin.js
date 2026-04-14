document.addEventListener('DOMContentLoaded', function() {
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = '';
            modal.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('is-open');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
        if (modalId === 'addMovieModal') {
            const form = document.getElementById('addMovieForm');
            if (form) form.reset();
            const posterPreview = document.getElementById('posterPreview');
            if (posterPreview) posterPreview.style.display = 'none';
            const messageDiv = document.getElementById('movieFormMessage');
            if (messageDiv) messageDiv.style.display = 'none';
        }
        if (modalId === 'editMovieModal') {
            const form = document.getElementById('editMovieForm');
            if (form) form.reset();
            const posterPreview = document.getElementById('edit_poster_preview');
            if (posterPreview) posterPreview.style.display = 'none';
            const messageDiv = document.getElementById('editMovieFormMessage');
            if (messageDiv) messageDiv.style.display = 'none';
        }
        
    };
    let movieDetailsCache = {};
    let bookingDetailsCache = {};
    let userDetailsCache = {};
    let questionsCache = {};
 // Image show Modal for Hero Banners
function openImagePreviewModal(imageUrl, title) {
    const modal = document.getElementById('imagePreviewModal');
    const previewImage = document.getElementById('previewImage');
    const previewTitle = document.getElementById('previewImageTitle');
    const modalTitle = document.getElementById('previewModalTitle');
    const closeBtn = modal ? modal.querySelector('.modal-close-btn') : null;
    
    if (modal && previewImage) {
        previewImage.src = imageUrl;
        previewTitle.textContent = title || 'Hero Banner';
        modalTitle.textContent = title ? `${title}` : 'Hero Banner';
        modal.classList.add('is-open');
        document.body.classList.add('modal-open');
        
        
        if (closeBtn) {
            closeBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeImagePreviewModal();
            };
        }
    }
    
const xButton = document.getElementById('previewModalCloseBtn');
if (xButton) {
    xButton.onclick = function(e) {
        e.preventDefault();
        e.stopPropagation();
        closeImagePreviewModal();
    };
}
}

function closeImagePreviewModal() {
    const modal = document.getElementById('imagePreviewModal');
    if (modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
        const previewImage = document.getElementById('previewImage');
        if (previewImage) {
            previewImage.src = '';
        }
    }
}

// Close with Escape key alsi
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('imagePreviewModal');
        if (modal && modal.classList.contains('is-open')) {
            closeImagePreviewModal();
        }
    }
});

    window.switchTab = function(tab) {
        const tabs = ['movies', 'games', 'bookings', 'users','heroBanners'];
        tabs.forEach((name) => {
            const content = document.getElementById(`${name}Tab`);
            const btn = document.getElementById(`${name}TabBtn`);
            const isActive = name === tab;
            if (content) content.classList.toggle('d-none', !isActive);
            if (btn) btn.classList.toggle('is-active', isActive);
        });
        if (tab === 'games') {
            loadGames();
        }
        if (tab === 'heroBanners') {
            loadHeroBanners();
        }
    };

    let pendingDeleteUrl = null;
    let pendingDeleteButton = null;
    let pendingQuestionId = null;

    const deleteModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const closeDeleteBtn = document.getElementById('closeDeleteModal');

    function openDeleteModal(url, button) {
        if (!deleteModal) return;
        pendingDeleteUrl = url;
        pendingDeleteButton = button;
        deleteModal.classList.add('is-open');
    }

    function closeDeleteModal() {
        if (!deleteModal) return;
        deleteModal.classList.remove('is-open');
        pendingDeleteUrl = null;
        pendingDeleteButton = null;
        pendingQuestionId = null;
    }

    if (closeDeleteBtn) closeDeleteBtn.onclick = closeDeleteModal;
    if (cancelDeleteBtn) cancelDeleteBtn.onclick = closeDeleteModal;
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) closeDeleteModal();
        });
    }
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
        if (pendingQuestionId) {
            const qid = pendingQuestionId;
            const btn = $('.delete-question-btn[data-id="' + qid + '"]')[0];
            if (btn) {
                const original = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Deleting...';
                $.ajax({
                    url: `/api/admin-api/questions/${qid}`,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { _method: 'DELETE' },
                    success: function(res) {
                        if (res.success) {
                            showToast('Question deleted', 'success');
                            loadQuestions(currentGameForQuestions);
                        } else {
                            showToast(res.message, 'error');
                        }
                    },
                    error: function() {
                        showToast('Error deleting question', 'error');
                    },
                    complete: function() {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = original;
                        }
                        closeDeleteModal();
                        pendingQuestionId = null;
                    }
                });
            }
            return; 
        }

            if (!pendingDeleteUrl || !pendingDeleteButton) return;

            const btn = pendingDeleteButton;

            const original = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Deleting...';
            fetch(pendingDeleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'include'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = btn.closest('tr');
                    if (row) {
        const firstCell = row.cells[0];
        const idValue = firstCell ? firstCell.textContent.trim() : null;
        const parentTable = row.closest('table');
        const isUsersTable = parentTable && parentTable.closest('#usersTab');
        const isMoviesTable = parentTable && parentTable.closest('#moviesTab');
        
        if (isMoviesTable && idValue) {
            delete movieDetailsCache[idValue];
        } else if (isUsersTable && idValue) {
            delete userDetailsCache[idValue];
        }
        
        row.remove();
    }
                    showToast('Deleted successfully', 'success');
                } else {
                    showToast(data.message || 'Delete failed', 'error');
                }
            })
            .catch(() => showToast('Server error', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = original;
                closeDeleteModal();
            });
        });
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;
        e.preventDefault();
        const url = btn.getAttribute('data-url');
        if (url) openDeleteModal(url, btn);
    });

    const addMovieForm = document.getElementById('addMovieForm');
    if (addMovieForm) {
        addMovieForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitMovieBtn');
            const messageDiv = document.getElementById('movieFormMessage');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            messageDiv.style.display = 'none';
            const formData = new FormData(this);
            try {
                const response = await fetch('/api/admin-api/movies', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'rgba(74, 222, 128, 0.2)';
                    messageDiv.style.border = '1px solid rgba(74, 222, 128, 0.3)';
                    messageDiv.style.color = '#4ade80';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    addMovieForm.reset();
                    setTimeout(() => {
                        closeModal('addMovieModal');
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'rgba(251, 113, 133, 0.2)';
                    messageDiv.style.border = '1px solid rgba(251, 113, 133, 0.3)';
                    messageDiv.style.color = '#fb7185';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + (data.message || 'Failed to add movie');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                messageDiv.style.display = 'block';
                messageDiv.style.background = 'rgba(251, 113, 133, 0.2)';
                messageDiv.style.border = '1px solid rgba(251, 113, 133, 0.3)';
                messageDiv.style.color = '#fb7185';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Network error. Please try again.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    
    const posterInput = document.getElementById('posterInput');
    const posterPreview = document.getElementById('posterPreview');
    const posterPreviewImg = document.getElementById('posterPreviewImg');
    const posterFileName = document.getElementById('posterFileName');
    if (posterInput) {
        posterInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    posterPreviewImg.src = e.target.result;
                    posterPreview.style.display = 'block';
                    posterFileName.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                posterPreview.style.display = 'none';
            }
        });
    }

    
    window.openEditMovieModal = function(movieId, title, description, genres, this_movie_is, cast, duration, trailerLink, posterUrl) {
        document.getElementById('edit_movie_id').value = movieId;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_genres').value = genres;
        document.getElementById('edit_this_movie_is').value = this_movie_is;
        document.getElementById('edit_cast').value = cast;
        document.getElementById('edit_duration').value = duration;
        document.getElementById('edit_trailer_link').value = trailerLink || '';
        const previewDiv = document.getElementById('edit_poster_preview');
        if (previewDiv) previewDiv.style.display = 'none';
        const input = document.getElementById('edit_poster_input');
        if (input) input.value = '';
        openModal('editMovieModal');
    };

    const editMovieForm = document.getElementById('editMovieForm');
    if (editMovieForm) {
        editMovieForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const movieId = document.getElementById('edit_movie_id').value;
            const submitBtn = document.getElementById('submitEditMovieBtn');
            const messageDiv = document.getElementById('editMovieFormMessage');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            messageDiv.style.display = 'none';
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            try {
                const response = await fetch(`/api/admin-api/movies/${movieId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'include',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    delete movieDetailsCache[movieId];
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'rgba(74, 222, 128, 0.2)';
                    messageDiv.style.border = '1px solid rgba(74, 222, 128, 0.3)';
                    messageDiv.style.color = '#4ade80';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    setTimeout(() => {
                        closeModal('editMovieModal');
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = 'rgba(251, 113, 133, 0.2)';
                    messageDiv.style.border = '1px solid rgba(251, 113, 133, 0.3)';
                    messageDiv.style.color = '#fb7185';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + (data.message || 'Failed to update movie');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            } catch (error) {
                messageDiv.style.display = 'block';
                messageDiv.style.background = 'rgba(251, 113, 133, 0.2)';
                messageDiv.style.border = '1px solid rgba(251, 113, 133, 0.3)';
                messageDiv.style.color = '#fb7185';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Network error. Please try again.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    const editPosterInput = document.getElementById('edit_poster_input');
    const editPosterPreview = document.getElementById('edit_poster_preview');
    const editPosterPreviewImg = document.getElementById('edit_poster_preview_img');
    const editPosterFileName = document.getElementById('edit_poster_file_name');
    if (editPosterInput) {
        editPosterInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editPosterPreviewImg.src = e.target.result;
                    editPosterPreview.style.display = 'block';
                    editPosterFileName.textContent = file.name;
                };
                reader.readAsDataURL(file);
            } else {
                editPosterPreview.style.display = 'none';
            }
        });
    }

    function initRowClicks() {
        const tableBody = document.querySelector('.admin-table tbody');
        if (!tableBody) return;

        tableBody.addEventListener('click', async function(e) {
            const row = e.target.closest('.movie-row');
            if (!row) return;
            if (e.target.closest('.admin-actions')) return; 

            const movieId = row.cells[0]?.textContent;
            if (!movieId) return;

           
            if (movieDetailsCache[movieId]) {
                const movie = movieDetailsCache[movieId];
                const tempCard = createTempCardFromMovie(movie);
                if (typeof window.openMovieModal === 'function') {
                    window.openMovieModal(tempCard);
                } else {
                    console.error('openMovieModal not available');
                    showToast('Modal function not available', 'error');
                }
                return;
            }

            try {
                const response = await fetch(`/api/admin-api/movies/${movieId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'include'
                });
                const result = await response.json();
                if (result.success) {
                    const movie = result.data;
                    // Create a temporary card element with required data attributes
                     movieDetailsCache[movieId] = movie;
                    const tempCard = createTempCardFromMovie(movie);
                    if (typeof window.openMovieModal === 'function') {
                        window.openMovieModal(tempCard);
                    } else {
                        console.error('openMovieModal not available');
                        showToast('Modal function not available', 'error');
                    }
                } else {
                    showToast('Failed to load movie details', 'error');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showToast('Could not load movie details', 'error');
            }
        });
    }
    function createTempCardFromMovie(movie) {
        const card = document.createElement('div');
        card.classList.add('movie-card');
        card.setAttribute('data-title', movie.title);
        card.setAttribute('data-description', movie.description);
        card.setAttribute('data-cast', movie.cast);
        card.setAttribute('data-genres', movie.genres);
        card.setAttribute('data-this-movie-is', movie.this_movie_is || 'N/A');
        card.setAttribute('data-rating', movie.rating);
        card.setAttribute('data-trailer-url', movie.trailer_link || '');
        // optional: add duration, poster, etc. if needed by openMovieModal
        return card;
    }

    
    if (typeof window.openMovieModal === 'function') {
        initRowClicks();
    } else {
        window.addEventListener('load', function() {
            if (typeof window.openMovieModal === 'function') {
                initRowClicks();
            } else {
                console.warn('openMovieModal still not available');
            }
        });
    }

    
    switchTab('movies');
  
// Load hero banners into table
function loadHeroBanners() {
    const tbody = document.getElementById('heroBannersList');
    if (!tbody) {
        return;
    }
    
    
    tbody.innerHTML = '<tr><td colspan="7" class="admin-empty">Loading banners...</td></tr>';
    
    fetch('/api/admin-api/hero-banners', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'include'
    })
    .then(res => {
        return res.json();
    })
    .then(result => {
        if (result.success && result.data) {
            renderHeroBannersTable(result.data);
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="admin-empty">Failed to load banners: ' + (result.message || 'Unknown error') + '</td></tr>';
        }
    })
    .catch(err => {
        tbody.innerHTML = '<tr><td colspan="7" class="admin-empty">Error loading banners: ' + err.message + '</td></tr>';
    });
}
// Helper: escape HTML to prevent XSS
function escapeHtml(str) {
   if (str === null || str === undefined) return '';
    str = String(str);
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}
// in the hero banner image click to show larger preview
function handleBannerImageClick(e) {
    e.stopPropagation();
    const img = e.currentTarget;
    const imageUrl = img.getAttribute('data-src') || img.src;
    const title = img.getAttribute('data-title') || 'Hero Banner';
    openImagePreviewModal(imageUrl, title);
}
// Render table rows
function renderHeroBannersTable(banners) {
    const tbody = document.getElementById('heroBannersList');
    if (!tbody) return;
    
    if (!banners.length) {
        tbody.innerHTML = '<tr><td colspan="9" class="admin-empty">No banners yet. Click "Add Banner" to create one.</td></tr>';
        return;
    }
    
    let html = '';
    banners.forEach(banner => {
        html += `
            <tr data-id="${banner.hero_banner_id}">
                <td>${banner.hero_banner_id}</td>
                <td><img src="${banner.background_image}" class="admin-thumb preview-img banner-preview-img" data-src="${banner.background_image}" data-title="${escapeHtml(banner.title)}" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; cursor: pointer;"></td>
                <td><strong>${escapeHtml(banner.title)}</strong></td>
                <td>${escapeHtml(banner.subtitle || '—')}</td>
                <td>${escapeHtml(banner.cta_label || '—')}</td>
                <td>${escapeHtml(banner.cta_route_name || '—')}</td>
                <td>${banner.position}</td>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="toggle-active" data-id="${banner.hero_banner_id}" ${banner.is_active ? 'checked' : ''}>
                        <span class="slider round"></span>
                    </label>
                </td>
                <td>
                    <div class="admin-actions">
                        <button type="button" class="button button-secondary admin-icon-btn edit-banner" 
                            data-id="${banner.hero_banner_id}"
                            data-title="${escapeHtml(banner.title)}"
                            data-subtitle="${escapeHtml(banner.subtitle || '')}"
                            data-cta_label="${escapeHtml(banner.cta_label || '')}"
                            data-cta_route_name="${escapeHtml(banner.cta_route_name || '')}"
                            data-position="${banner.position}"
                            data-image="${banner.background_image}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-banner" data-id="${banner.hero_banner_id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
    // Add click handler for banner image p
    document.querySelectorAll('.banner-preview-img').forEach(img => {
        img.removeEventListener('click', handleBannerImageClick);
        img.addEventListener('click', handleBannerImageClick);
    });
    
    document.querySelectorAll('.toggle-active').forEach(btn => {
        btn.removeEventListener('change', handleToggleActive);
        btn.addEventListener('change', handleToggleActive);
    });
    document.querySelectorAll('.edit-banner').forEach(btn => {
        btn.removeEventListener('click', handleEditBanner);
        btn.addEventListener('click', handleEditBanner);
    });
    document.querySelectorAll('.delete-banner').forEach(btn => {
        btn.removeEventListener('click', handleDeleteBanner);
        btn.addEventListener('click', handleDeleteBanner);
    });
}

// Toggle active status
async function handleToggleActive(e) {
    const cb = e.target;
    const id = cb.dataset.id;
    const originalChecked = cb.checked;
    
    try {
        const response = await fetch(`/api/admin-api/hero-banners/${id}/toggle-active`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            credentials: 'include'
        });
        const result = await response.json();
        if (result.success) {
            showToast(result.message, 'success');
            loadHeroBanners(); // refresh to update active count and order
        } else {
            cb.checked = !originalChecked;
            showToast(result.message || 'Failed to toggle status', 'error');
        }
    } catch (error) {
        cb.checked = !originalChecked;
        showToast('Network error', 'error');
    }
}

// Edit banner 
function handleEditBanner(e) {
    const btn = e.currentTarget;
    document.getElementById('banner_id').value = btn.dataset.id;
    document.getElementById('banner_title').value = btn.dataset.title;
    document.getElementById('banner_subtitle').value = btn.dataset.subtitle;
    document.getElementById('banner_cta_label').value = btn.dataset.cta_label;
    document.getElementById('banner_cta_route_name').value = btn.dataset.cta_route_name;
    document.getElementById('banner_position').value = btn.dataset.position;
    
    const previewDiv = document.getElementById('currentImagePreview');
    const previewImg = document.getElementById('currentImageImg');
    if (btn.dataset.image && btn.dataset.image !== 'undefined') {
        previewImg.src = btn.dataset.image;
        previewDiv.style.display = 'block';
    } else {
        previewDiv.style.display = 'none';
    }
    
    document.getElementById('heroBannerModalTitle').innerText = 'Edit Banner';
    document.getElementById('banner_image').required = false;
    openModal('heroBannerModal');
}

// Delete banner
function handleDeleteBanner(e) {
    const id = e.currentTarget.dataset.id;
    const url = `/api/admin-api/hero-banners/${id}`;
    const button = e.currentTarget;
    openDeleteModal(url, button);
}

window.openHeroBannerModal = function() {
    document.getElementById('heroBannerForm').reset();
    document.getElementById('banner_id').value = '';
    document.getElementById('currentImagePreview').style.display = 'none';
    document.getElementById('heroBannerModalTitle').innerText = 'Add Banner';
    document.getElementById('banner_image').required = true;
    openModal('heroBannerModal');
};

// Submit form (add or edit)
document.getElementById('submitHeroBannerBtn')?.addEventListener('click', async function() {
    const form = document.getElementById('heroBannerForm');
    const formData = new FormData(form);
    const bannerId = document.getElementById('banner_id').value;
    const isEdit = bannerId !== '';
    const url = isEdit ? `/api/admin-api/hero-banners/${bannerId}` : '/api/admin-api/hero-banners';
    
    if (!isEdit && !formData.get('background_image')) {
        showToast('Please select an image', 'error');
        return;
    }
    
    if (isEdit) {
        formData.append('_method', 'PUT');
    }
    
    const submitBtn = this;
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            credentials: 'include',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('heroBannerModal');
            loadHeroBanners();
        } else {
            showToast(data.message || 'Failed to save banner', 'error');
        }
    } catch (error) {
        showToast('Network error', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Load banners when switching to the Hero Banners tab
const originalSwitchTab = window.switchTab;
window.switchTab = function(tab) {
    originalSwitchTab(tab);
    if (tab === 'heroBanners') {
        loadHeroBanners();
    }
};


document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('heroBannersTab') && !document.getElementById('heroBannersTab').classList.contains('d-none')) {
        loadHeroBanners();
    }
});

    // Fix modal open/close to add body class
    const originalOpenModal = window.openModal;
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('is-open');
            document.body.classList.add('modal-open');
        }
        if (originalOpenModal) originalOpenModal(modalId);
    };
    
    const originalCloseModal = window.closeModal;
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('is-open');
            document.body.classList.remove('modal-open');
        }
        if (originalCloseModal) originalCloseModal(modalId);
    };

    // GAMES
    function loadGames() {
        fetch('/api/admin-api/games', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                renderGamesTable(result.data);
                populateGameTypeFilter(result.data);
            } else {
                showToast('Failed to load games', 'error');
            }
        });
    }

    
    function renderGamesTable(games) {
        const tbody = $('#gamesTab .admin-table tbody');
        if (!tbody.length) return;
        if (!games.length) {
            tbody.html('<tr><td colspan="5" class="admin-empty">No games yet.</td></tr>');
            return;
        }
        let html = '';
        games.forEach(game => {
            html += `
                <tr data-game-id="${game.game_id}">
                    <td>${game.game_id}</td>
                    <td><div class="admin-table-icon"><i class="fas ${game.icon || 'fa-gamepad'}"></i></div></td>
                    <td>${escapeHtml(game.title)}</td>
                    <td>${escapeHtml(game.game_type)}</td>
                    <td>
                        <div class="admin-actions">
                            <button type="button" class="button button-secondary admin-icon-btn edit-game-btn"
                                    data-id="${game.game_id}"
                                    data-title="${escapeHtml(game.title)}"
                                    data-description="${escapeHtml(game.description || '')}"
                                    data-game_type="${escapeHtml(game.game_type)}"
                                    data-icon="${game.icon || 'fa-gamepad'}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="button button-secondary admin-icon-btn manage-questions-btn"
                                    data-id="${game.game_id}" data-title="${escapeHtml(game.title)}" data-game-type="${escapeHtml(game.game_type)}">
                                <i class="fas fa-question-circle"></i>
                            </button>
                            <button type="button" class="button button-secondary admin-icon-btn admin-icon-btn-danger delete-btn"
                                    data-url="/api/admin-api/games/${game.game_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.html(html);
    }
    function openGameModal(gameId = null) {
        $('#game_id').val('');
        $('#gameForm')[0].reset();
        $('#gameFormMessage').hide();
        
        
        renderIconPicker('fa-gamepad');
        $('#game_icon').val('fa-gamepad');
        
        if (gameId) {
            $('#gameModalTitle').text('Edit Game');
            fetch(`/api/admin-api/games/${gameId}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    let g = result.data;
                    $('#game_id').val(g.game_id);
                    $('#game_title').val(g.title);
                    $('#game_description').val(g.description);
                    $('#game_type').val(g.game_type);
                   
                    const savedIcon = g.icon || 'fa-gamepad';
                    renderIconPicker(savedIcon);
                    $('#game_icon').val(savedIcon);
                } else {
                    showToast('Failed to load game', 'error');
                }
            });
        } else {
            $('#gameModalTitle').text('Add Game');
            renderIconPicker('fa-gamepad');
            $('#game_icon').val('fa-gamepad');
        }
        $('#gameModal').addClass('is-open');
        document.body.classList.add('modal-open');
    }
    window.openGameModal = openGameModal;

    function closeGameModal() {
        $('#gameModal').removeClass('is-open');
        document.body.classList.remove('modal-open');
    }
    window.closeGameModal = closeGameModal;
    $('#submitGameBtn').on('click', function() {
        const gameId = $('#game_id').val();
        const url = gameId ? `/api/admin-api/games/${gameId}` : '/api/admin-api/games';
        const method = gameId ? 'PUT' : 'POST';
        const formData = {
            title: $('#game_title').val(),
            description: $('#game_description').val(),
            game_type: $('#game_type').val(),
            icon: $('#game_icon').val()
        };
        $.ajax({
            url: url,
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { ...formData, _method: method },
            success: function(res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    closeGameModal();
                    loadGames(); 
                } else {
                    $('#gameFormMessage').text(res.message).addClass('alert-error').show();
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Error saving game';
                $('#gameFormMessage').text(msg).addClass('alert-error').show();
            }
        });
    });

    $(document).on('click', '.delete-btn', function(e) {
        let url = $(this).data('url');
        if (url && url.includes('/admin/games/')) {
            let gameId = url.split('/').pop();
            $(this).data('url', `/api/admin-api/games/${gameId}`);
        }
       
    });
    // questions
    let currentGameForQuestions = null;
    let currentGameType = null;

    function openQuestionsModal(gameId, gameTitle, gameType) {
        currentGameForQuestions = gameId;
        currentGameType = gameType;
        $('#currentGameId').val(gameId);
        $('#questionsModalTitle').text(`Questions for ${gameTitle}`);
        loadQuestions(gameId);
        $('#questionsModal').addClass('is-open');
        document.body.classList.add('modal-open');
    }
    window.openQuestionsModal = openQuestionsModal;

    function closeQuestionsModal() {
        $('#questionsModal').removeClass('is-open');
        document.body.classList.remove('modal-open');
    }
    window.closeQuestionsModal = closeQuestionsModal;

    function loadQuestions(gameId) {
        if (questionsCache[gameId]) {
            renderQuestionsList(questionsCache[gameId]);
            return;
        }
        $('#questionsList').html('<p>Loading questions...</p>');
        fetch(`/api/admin-api/games/${gameId}/questions`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        })
        .then(res => res.json())
        .then(result => {
            if (result.success && result.data) {
                questionsCache[gameId] = result.data;
                renderQuestionsList(result.data);
            } else {
                $('#questionsList').html('<p>No questions yet. Add one below.</p>');
            }
            attachQuestionButtons();
        });
    }
    function renderQuestionsList(questions) {
        if (!questions.length) {
            $('#questionsList').html('<p>No questions yet. Add one below.</p>');
            attachQuestionButtons();
            return;
        }
                let html = '<div style="display:flex; flex-direction:column; gap:1rem;">';
        questions.forEach(q => {
                    html += `
                        <div class="surface-card" style="padding:0.75rem; border-radius:12px;" data-qid="${q.question_id}">
                            <strong>${escapeHtml(q.content || 'No content')}</strong>
                            ${q.question_text ? `<div><small>${escapeHtml(q.question_text)}</small></div>` : ''}
                            <div><small>Correct: ${escapeHtml(q.correct_answer)}</small></div>
                            <div class="admin-actions" style="margin-top:0.5rem;">
                                <button class="button button-secondary edit-question-btn" data-q='${JSON.stringify(q)}'>Edit</button>
                                <button class="button button-secondary admin-icon-btn-danger delete-question-btn" data-id="${q.question_id}">Delete</button>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#questionsList').html(html);
            attachQuestionButtons();
    }

    function attachQuestionButtons() {
        $('.edit-question-btn').off('click').on('click', function() {
            let q = $(this).data('q');
            openAddQuestionModal(q);
        });
        $('.delete-question-btn').off('click').on('click', function() {
        pendingQuestionId = $(this).data('id');
        $('#deleteConfirmModal .section-header p').text('Are you sure you want to permanently delete this question?');
        $('#deleteConfirmModal').addClass('is-open');
        document.body.classList.add('modal-open');
    });
    }

    function openAddQuestionModal(questionData = null) {
        $('#questionForm')[0].reset();
        $('#question_id').val('');
        $('#questionFormMessage').hide();
        $('#q_correct').val('');

        if (questionData) {
            $('#questionFormModalTitle').text('Edit Question');
            $('#question_id').val(questionData.question_id);
            $('#q_text').val(questionData.question_text || '');
            $('#q_content').val(questionData.content || '');
            $('#q_hint').val(questionData.hint || '');
            $('#q_points').val(questionData.points || 10);

            
            let opts = questionData.options;
            if (typeof opts === 'string') {
                try {
                    opts = JSON.parse(opts);
                } catch(e) {
                    opts = ['', '', '', ''];
                }
            }
            if (!Array.isArray(opts)) opts = ['', '', '', ''];
            // Ensure we have 4 items
            while (opts.length < 4) opts.push('');
            
            $('#opt_a').val(opts[0] || '');
            $('#opt_b').val(opts[1] || '');
            $('#opt_c').val(opts[2] || '');
            $('#opt_d').val(opts[3] || '');

            
            const correctValue = questionData.correct_answer || '';
            let correctLetter = '';
            if (correctValue === opts[0]) correctLetter = 'A';
            else if (correctValue === opts[1]) correctLetter = 'B';
            else if (correctValue === opts[2]) correctLetter = 'C';
            else if (correctValue === opts[3]) correctLetter = 'D';
            $('#q_correct').val(correctLetter);
        } else {
            $('#questionFormModalTitle').text('Add Question');
            $('#q_text, #q_content, #q_hint').val('');
            $('#opt_a, #opt_b, #opt_c, #opt_d').val('');
            $('#q_correct').val('');
            $('#q_points').val(10);
        }

        $('#questionFormModal').addClass('is-open');
        document.body.classList.add('modal-open');
    }
    window.openAddQuestionModal = openAddQuestionModal;

    function closeQuestionFormModal() {
        $('#questionFormModal').removeClass('is-open');
        document.body.classList.remove('modal-open');
    }
    window.closeQuestionFormModal = closeQuestionFormModal;

    $('#submitQuestionBtn').off('click').on('click', function() {
        const gameId = $('#currentGameId').val();
        const questionId = $('#question_id').val();
        const url = questionId ? `/api/admin-api/questions/${questionId}` : `/api/admin-api/games/${gameId}/questions`;
        const method = questionId ? 'PUT' : 'POST';

        const optionsArray = [
            $('#opt_a').val().trim(),
            $('#opt_b').val().trim(),
            $('#opt_c').val().trim(),
            $('#opt_d').val().trim()
        ];
        if (optionsArray.some(opt => opt === '')) {
            $('#questionFormMessage').text('Please fill all four options.').show();
            return;
        }

        const correctLetter = $('#q_correct').val();
        if (!correctLetter) {
            $('#questionFormMessage').text('Please select the correct answer.').show();
            return;
        }
        let correctAnswer = '';
        if (correctLetter === 'A') correctAnswer = optionsArray[0];
        else if (correctLetter === 'B') correctAnswer = optionsArray[1];
        else if (correctLetter === 'C') correctAnswer = optionsArray[2];
        else if (correctLetter === 'D') correctAnswer = optionsArray[3];

        const content = ($('#q_content').val() || '').trim();
        if (!content) {
            $('#questionFormMessage').text('Content is required.').show();
            return;
        }

        const payload = {
            question_text: ($('#q_text').val() || '').trim() || null,
            content: content,
            correct_answer: correctAnswer,
            options: optionsArray,
            hint: ($('#q_hint').val() || '').trim() || null,
            points: parseInt($('#q_points').val()) || 10,
            _method: method
        };

        $.ajax({
            url: url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: payload,
            success: function(res) {
                if (res.success) {
                    showToast(res.message, 'success');
                    closeQuestionFormModal();
                    loadQuestions(gameId);
                } else {
                    $('#questionFormMessage').text(res.message).show();
                }
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Error saving question';
                $('#questionFormMessage').text(msg).show();
            }
        });
    });

    $('#openAddQuestionBtn').off('click').on('click', function() {
        openAddQuestionModal();
    });

    $(document).on('click', '.edit-game-btn', function() {
        openGameModal($(this).data('id'));
    });
    $(document).on('click', '.manage-questions-btn', function() {
        openQuestionsModal($(this).data('id'), $(this).data('title'), $(this).data('game-type'));
    });
    //ICON PICKER 
    const gameIcons = [
        'fa-gamepad', 'fa-film', 'fa-mask', 'fa-quote-left', 'fa-image',
        'fa-music', 'fa-brain', 'fa-crown', 'fa-dice-d6', 'fa-hat-wizard',
        'fa-robot', 'fa-star', 'fa-ticket-alt', 'fa-clapperboard', 'fa-theater-masks',
        'fa-fire', 'fa-dragon', 'fa-ghost', 'fa-cat', 'fa-dog', 'fa-tree',
        'fa-heart', 'fa-skull', 'fa-moon', 'fa-sun', 'fa-cloud-moon',
        'fa-car', 'fa-rocket', 'fa-space-shuttle', 'fa-subway', 'fa-bicycle',
        'fa-camera', 'fa-video', 'fa-headphones', 'fa-microphone', 'fa-volume-up'
    ];

    function renderIconPicker(selectedIcon = 'fa-gamepad') {
        const container = $('#iconPicker');
        container.empty();
        gameIcons.forEach(icon => {
            const isSelected = (icon === selectedIcon);
            const $iconDiv = $(`
                <div class="icon-option ${isSelected ? 'selected' : ''}" 
                    data-icon="${icon}" 
                    style="padding: 10px; border-radius: 12px; cursor: pointer; 
                            background: ${isSelected ? 'rgba(122, 92, 255, 0.3)' : 'rgba(255,255,255,0.05)'};
                            border: 1px solid ${isSelected ? 'var(--accent)' : 'rgba(255,255,255,0.15)'};
                            transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center;
                            width: 44px; height: 44px;">
                    <i class="fas ${icon}" style="font-size: 1.4rem;"></i>
                </div>
            `);
            $iconDiv.on('click', function() {
                $('#iconPicker .icon-option').removeClass('selected').css({
                    background: 'rgba(255,255,255,0.05)',
                    borderColor: 'rgba(255,255,255,0.15)'
                });
                $(this).addClass('selected').css({
                    background: 'rgba(122, 92, 255, 0.3)',
                    borderColor: 'var(--accent)'
                });
                $('#game_icon').val(icon);
            });
            container.append($iconDiv);
        });
    }
    // Show booking 
    window.showBookingDetails = async function(bookingId) {
        const modal = document.getElementById('bookingDetailsModal');
        const contentDiv = document.getElementById('bookingDetailsContent');
        const idSpan = document.getElementById('bookingIdSpan');

        idSpan.textContent = bookingId;
        if (bookingDetailsCache[bookingId]) {
            renderBookingDetails(bookingDetailsCache[bookingId]);
            modal.classList.add('is-open');
            document.body.classList.add('modal-open');
            return;
        }
        contentDiv.innerHTML = '<div class="admin-empty"><i class="fas fa-spinner fa-pulse"></i> Loading...</div>';
        modal.classList.add('is-open');
        document.body.classList.add('modal-open');

        try {
            const response = await fetch(`/api/bookings/${bookingId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include'
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();

            if (result.success && result.data) {
                bookingDetailsCache[bookingId] = result.data;
                renderBookingDetails(result.data);
            } else {
                contentDiv.innerHTML = `<div class="admin-empty text-danger"> ${result.message || 'Failed to load booking'}</div>`;
            }
        } catch (error) {
            contentDiv.innerHTML = `<div class="admin-empty text-danger"> ${error.message}</div>`;
        }
    };

    function renderBookingDetails(booking) {
        const movie = booking.showtime?.movie || {};
        const showtime = booking.showtime || {};
        const cinema = showtime.cinema || {};
        const user = booking.user || {};

        let showtimeValue = 'N/A';
        if (showtime.show_date && showtime.show_time) {
           const date = new Date(showtime.show_date);
            const timeStr = showtime.show_time.substring(0, 5); 
            showtimeValue = `${date.toLocaleDateString()} at ${timeStr}`;
        } else if (showtime.show_date) {
            showtimeValue = showtime.show_date;
        } else if (showtime.show_time) {
            showtimeValue = showtime.show_time;
        }

       let seatsDisplay = '—';
        if (booking.seats) {
            try {
                const seats = typeof booking.seats === 'string' ? JSON.parse(booking.seats) : booking.seats;
                seatsDisplay = seats.join(', ');
            } catch(e) { seatsDisplay = booking.seats; }
        }

        const html = `
            <div style="display: grid; gap: 0.8rem;">
                <div class="booking-detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">${booking.booking_id}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">User:</span>
                    <span class="detail-value">${escapeHtml(user.username || 'N/A')} (${escapeHtml(user.email || 'N/A')})</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Movie:</span>
                    <span class="detail-value">${escapeHtml(movie.title || 'N/A')}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Cinema:</span>
                    <span class="detail-value">${escapeHtml(cinema.name || 'N/A')}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Showtime:</span>
                    <span class="detail-value">${escapeHtml(showtimeValue)}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Seats:</span>
                    <span class="detail-value">${escapeHtml(seatsDisplay)}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Total Price:</span>
                    <span class="detail-value">$${parseFloat(booking.total_price || booking.price || 0).toFixed(2)}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Booking Date:</span>
                    <span class="detail-value">${escapeHtml(booking.booking_date)}</span>
                </div>
                <div class="booking-detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value status-${(booking.status || 'pending').toLowerCase()}">${escapeHtml(booking.status || 'pending')}</span>
                </div>
            </div>
        `;
        document.getElementById('bookingDetailsContent').innerHTML = html;
    }

    window.closeBookingDetailsModal = function() {
        const modal = document.getElementById('bookingDetailsModal');
        if (modal) {
            modal.classList.remove('is-open');
            document.body.classList.remove('modal-open');
        }
    };

    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('bookingDetailsModal');
            if (modal && modal.classList.contains('is-open')) {
                closeBookingDetailsModal();
            }
        }
    });

   document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-booking-btn');
        if (btn) {
            e.preventDefault();
            const bookingId = btn.getAttribute('data-booking-id');
            if (bookingId) showBookingDetails(bookingId);
        }
    });
    // Show user details modal by fetching from API
window.showUserDetails = function(userId) {
    const modal = document.getElementById('userDetailsModal');
    const contentDiv = document.getElementById('userDetailsContent');
    const titleSpan = document.getElementById('userDetailsTitle');

    if (!modal || !contentDiv) return;

    if (userDetailsCache[userId]) {
        renderUserDetails(userDetailsCache[userId]);
        modal.classList.add('is-open');
        document.body.classList.add('modal-open');
        return;
    }
    // Show modal and loading state
    modal.classList.add('is-open');
    document.body.classList.add('modal-open');
    contentDiv.innerHTML = '<div class="admin-empty"><i class="fas fa-spinner fa-pulse"></i> Loading user details...</div>';

    fetch(`/api/admin-api/users/${userId}`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'include'
    })
    .then(res => res.json())
    .then(result => {
        if (result.success && result.data) {
            userDetailsCache[userId] = result.data;
            renderUserDetails(result.data);
        } else {
            contentDiv.innerHTML = `<div class="admin-empty text-danger">${result.message || 'Failed to load user details'}</div>`;
        }
    })
    .catch(error => {
        contentDiv.innerHTML = `<div class="admin-empty text-danger">Network error: ${error.message}</div>`;
    });
};

function renderUserDetails(user) {
    const avatarHtml = user.profile_image
        ? `<img src="${user.profile_image}" class="admin-avatar-sm" style="width:80px; height:80px; border-radius:50%;">`
        : '<div class="admin-thumb-placeholder" style="width:80px; height:80px; border-radius:50%;"></div>';

    const html = `
        <div style="display: grid; gap: 1rem;">
            <div style="text-align: center;">${avatarHtml}</div>
            <div class="booking-detail-row">
                <span class="detail-label">User ID:</span>
                <span class="detail-value">${user.user_id}</span>
            </div>
            <div class="booking-detail-row">
                <span class="detail-label">Username:</span>
                <span class="detail-value">${escapeHtml(user.username)}</span>
            </div>
            <div class="booking-detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">${escapeHtml(user.email)}</span>
            </div>
            <div class="booking-detail-row">
                <span class="detail-label">Role:</span>
                <span class="detail-value">${escapeHtml(user.role)}</span>
            </div>
            <div class="booking-detail-row">
                <span class="detail-label">Member Since:</span>
                <span class="detail-value">${escapeHtml(user.member_since)}</span>
            </div>
        </div>
    `;
    document.getElementById('userDetailsContent').innerHTML = html;
    document.getElementById('userDetailsTitle').innerHTML = `User: ${escapeHtml(user.username)}`;
}

window.closeUserDetailsModal = function() {
    const modal = document.getElementById('userDetailsModal');
    if (modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
    }
};

// Attach event listener for the view-user-btn
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.view-user-btn');
    if (btn) {
        e.preventDefault();
        const userId = btn.getAttribute('data-user-id');
        if (userId) showUserDetails(userId);
    }
});
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.cancel-booking-btn');
    if (!btn) return;
    e.preventDefault();
    const bookingId = btn.getAttribute('data-booking-id');
    if (bookingId) {
        openCancelBookingModal(bookingId, btn);
    }
});
let pendingCancelBookingId = null;
let pendingCancelButton = null;

function openCancelBookingModal(bookingId, button) {
    const modal = document.getElementById('cancelBookingModal');
    if (!modal) return;
    pendingCancelBookingId = bookingId;
    pendingCancelButton = button;
    modal.classList.add('is-open');
    document.body.classList.add('modal-open');
}

function closeCancelBookingModal() {
    const modal = document.getElementById('cancelBookingModal');
    if (modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
    }
    pendingCancelBookingId = null;
    pendingCancelButton = null;
}


document.getElementById('closeCancelModal')?.addEventListener('click', closeCancelBookingModal);
document.getElementById('keepBookingBtn')?.addEventListener('click', closeCancelBookingModal);
document.getElementById('cancelBookingModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCancelBookingModal();
});

document.getElementById('confirmCancelBtn')?.addEventListener('click', function() {
    if (!pendingCancelBookingId || !pendingCancelButton) return;
    
    const btn = pendingCancelButton;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    fetch(`/api/admin-api/bookings/${pendingCancelBookingId}/cancel`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
             delete bookingDetailsCache[pendingCancelBookingId];
            showToast(data.message, 'success');
            const row = btn.closest('tr');
            if (row) {
                const statusCell = row.querySelector('td:nth-child(6) .admin-badge');
                if (statusCell) {
                    statusCell.textContent = 'cancelled';
                    statusCell.classList.remove('status-pending', 'status-confirmed');
                    statusCell.classList.add('status-cancelled');
                }
                btn.disabled = true;
                btn.style.opacity = '0.5';
                btn.style.cursor = 'not-allowed';
                btn.innerHTML = '<i class="fas fa-ban"></i>';
            }
        } else {
            showToast(data.message || 'Failed to cancel booking', 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
        closeCancelBookingModal();
    })
    .catch(error => {
        showToast('Network error: ' + error.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        closeCancelBookingModal();
    });
});
//filters
function populateMovieGenres() {
    const genreSet = new Set();
    document.querySelectorAll('#moviesTab .movie-row').forEach(row => {
        const genres = row.getAttribute('data-genres');
        if (genres) {
            genres.split(',').forEach(g => genreSet.add(g.trim()));
        }
    });
    const select = document.getElementById('movieGenreFilter');
    if (select) {
        select.innerHTML = '<option value="">All Genres</option>';
        Array.from(genreSet).sort().forEach(genre => {
            select.innerHTML += `<option value="${escapeHtml(genre)}">${escapeHtml(genre)}</option>`;
        });
    }
}

function filterMovies() {
    const searchTerm = (document.getElementById('movieSearch')?.value || '').toLowerCase();
    const selectedGenre = document.getElementById('movieGenreFilter')?.value || '';
    const rows = document.querySelectorAll('#moviesTab .movie-row');
    let visibleCount = 0;
    rows.forEach(row => {
        const title = (row.getAttribute('data-title') || '').toLowerCase();
        const cast = (row.getAttribute('data-cast') || '').toLowerCase();
        const genres = (row.getAttribute('data-genres') || '').toLowerCase();
        const matchesSearch = searchTerm === '' || title.includes(searchTerm) || cast.includes(searchTerm) || genres.includes(searchTerm);
        const matchesGenre = selectedGenre === '' || genres.includes(selectedGenre.toLowerCase());
        if (matchesSearch && matchesGenre) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    const tbody = document.querySelector('#moviesTab .admin-table tbody');
    const emptyMsg = tbody?.querySelector('.filter-empty-row');
    if (visibleCount === 0 && !emptyMsg) {
        tbody?.insertAdjacentHTML('beforeend', '<tr class="filter-empty-row"><td colspan="6" class="admin-empty">No movies match your filters.</td></tr>');
    } else if (visibleCount > 0 && emptyMsg) {
        emptyMsg.remove();
    }
}

function filterGames() {
    const searchTerm = (document.getElementById('gameSearch')?.value || '').toLowerCase();
    const typeFilter = (document.getElementById('gameTypeFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#gamesTab .admin-table tbody tr');
    let visibleCount = 0;
    rows.forEach(row => {
        const title = (row.querySelector('td:nth-child(3)')?.textContent || '').toLowerCase();
        const gameType = (row.querySelector('td:nth-child(4)')?.textContent || '').toLowerCase();
        const matchesSearch = searchTerm === '' || title.includes(searchTerm);
        const matchesType = typeFilter === '' || gameType === typeFilter;
        if (matchesSearch && matchesType) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    const tbody = document.querySelector('#gamesTab .admin-table tbody');
    const emptyMsg = tbody?.querySelector('.filter-empty-row');
    if (visibleCount === 0 && !emptyMsg) {
        tbody?.insertAdjacentHTML('beforeend', '<tr class="filter-empty-row"><td colspan="5" class="admin-empty">No games match your filters.</td></tr>');
    } else if (visibleCount > 0 && emptyMsg) {
        emptyMsg.remove();
    }
}

function filterBookings() {
    const searchTerm = (document.getElementById('bookingSearch')?.value || '').toLowerCase();
    const statusFilter = (document.getElementById('bookingStatusFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#bookingsTab .admin-table tbody tr');
    let visibleCount = 0;
    rows.forEach(row => {
        const userId = (row.querySelector('td:nth-child(2)')?.textContent || '').toLowerCase();
        const movieTitle = (row.querySelector('td:nth-child(3)')?.textContent || '').toLowerCase();
        const bookingId = (row.querySelector('td:nth-child(1)')?.textContent || '').toLowerCase();
        const statusElem = row.querySelector('td:nth-child(6) .admin-badge');
        const status = (statusElem?.textContent || '').toLowerCase();
        const matchesSearch = searchTerm === '' || userId.includes(searchTerm) || movieTitle.includes(searchTerm) || bookingId.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status === statusFilter;
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    const tbody = document.querySelector('#bookingsTab .admin-table tbody');
    const emptyMsg = tbody?.querySelector('.filter-empty-row');
    if (visibleCount === 0 && !emptyMsg) {
        tbody?.insertAdjacentHTML('beforeend', '<tr class="filter-empty-row"><td colspan="7" class="admin-empty">No bookings match your filters.</td></tr>');
    } else if (visibleCount > 0 && emptyMsg) {
        emptyMsg.remove();
    }
}

function filterUsers() {
    const searchTerm = (document.getElementById('userSearch')?.value || '').toLowerCase();
    const roleFilter = (document.getElementById('userRoleFilter')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#usersTab .admin-table tbody tr');
    let visibleCount = 0;
    rows.forEach(row => {
        const username = (row.querySelector('td:nth-child(3)')?.textContent || '').toLowerCase();
        const email = (row.querySelector('td:nth-child(4)')?.textContent || '').toLowerCase();
        const roleElem = row.querySelector('td:nth-child(6) .admin-badge');
        const role = (roleElem?.textContent || '').toLowerCase();
        const matchesSearch = searchTerm === '' || username.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = roleFilter === '' || role === roleFilter;
        if (matchesSearch && matchesRole) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    const tbody = document.querySelector('#usersTab .admin-table tbody');
    const emptyMsg = tbody?.querySelector('.filter-empty-row');
    if (visibleCount === 0 && !emptyMsg) {
        tbody?.insertAdjacentHTML('beforeend', '<tr class="filter-empty-row"><td colspan="7" class="admin-empty">No users match your filters.</td></tr>');
    } else if (visibleCount > 0 && emptyMsg) {
        emptyMsg.remove();
    }
}

function debounce(func, delay) {
    let timer;
    return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
    };
}

function initFilters() {
    const movieSearch = document.getElementById('movieSearch');
    const movieGenre = document.getElementById('movieGenreFilter');
    const clearMovie = document.getElementById('clearMovieFilters');
    if (movieSearch) movieSearch.addEventListener('input', debounce(filterMovies, 300));
    if (movieGenre) movieGenre.addEventListener('change', filterMovies);
    if (clearMovie) clearMovie.addEventListener('click', () => {
        if (movieSearch) movieSearch.value = '';
        if (movieGenre) movieGenre.value = '';
        filterMovies();
    });
    populateMovieGenres();

  
    const gameSearch = document.getElementById('gameSearch');
    const gameType = document.getElementById('gameTypeFilter');
    const clearGame = document.getElementById('clearGameFilters');
    if (gameSearch) gameSearch.addEventListener('input', debounce(filterGames, 300));
    if (gameType) gameType.addEventListener('change', filterGames);
    if (clearGame) clearGame.addEventListener('click', () => {
        if (gameSearch) gameSearch.value = '';
        if (gameType) gameType.value = '';
        filterGames();
    });

    const bookingSearch = document.getElementById('bookingSearch');
    const bookingStatus = document.getElementById('bookingStatusFilter');
    const clearBooking = document.getElementById('clearBookingFilters');
    if (bookingSearch) bookingSearch.addEventListener('input', debounce(filterBookings, 300));
    if (bookingStatus) bookingStatus.addEventListener('change', filterBookings);
    if (clearBooking) clearBooking.addEventListener('click', () => {
        if (bookingSearch) bookingSearch.value = '';
        if (bookingStatus) bookingStatus.value = '';
        filterBookings();
    });

    const userSearch = document.getElementById('userSearch');
    const userRole = document.getElementById('userRoleFilter');
    const clearUser = document.getElementById('clearUserFilters');
    if (userSearch) userSearch.addEventListener('input', debounce(filterUsers, 300));
    if (userRole) userRole.addEventListener('change', filterUsers);
    if (clearUser) clearUser.addEventListener('click', () => {
        if (userSearch) userSearch.value = '';
        if (userRole) userRole.value = '';
        filterUsers();
    });
}

const originalLoadGames = window.loadGames;
window.loadGames = function() {
    originalLoadGames();
    setTimeout(() => {
        filterGames();
        const gameSearch = document.getElementById('gameSearch');
        if (gameSearch && !gameSearch.hasListener) {
            gameSearch.addEventListener('input', debounce(filterGames, 300));
            gameSearch.hasListener = true;
        }
        const gameType = document.getElementById('gameTypeFilter');
        if (gameType && !gameType.hasListener) {
            gameType.addEventListener('change', filterGames);
            gameType.hasListener = true;
        }
    }, 100);
};

initFilters();
const observer = new MutationObserver(() => populateMovieGenres());
observer.observe(document.querySelector('#moviesTab .admin-table tbody'), { childList: true, subtree: true });
function populateGameTypeFilter(games) {
    const typeSet = new Set();
    games.forEach(game => {
        if (game.game_type) typeSet.add(game.game_type);
    });
    const sortedTypes = Array.from(typeSet).sort();
    const select = document.getElementById('gameTypeFilter');
    if (!select) return;
    let html = '<option value="">All Types</option>';
    sortedTypes.forEach(type => {
        html += `<option value="${escapeHtml(type)}">${escapeHtml(type)}</option>`;
    });
    select.innerHTML = html;
}
});