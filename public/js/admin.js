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

    window.switchTab = function(tab) {
        const tabs = ['movies', 'games', 'bookings', 'users','heroBanners'];
        tabs.forEach((name) => {
            const content = document.getElementById(`${name}Tab`);
            const btn = document.getElementById(`${name}TabBtn`);
            const isActive = name === tab;
            if (content) content.classList.toggle('d-none', !isActive);
            if (btn) btn.classList.toggle('is-active', isActive);
        });
    };

    let pendingDeleteUrl = null;
    let pendingDeleteButton = null;

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
                    if (row) row.remove();
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
                    const tempCard = document.createElement('div');
                    tempCard.classList.add('movie-card');
                    tempCard.setAttribute('data-title', movie.title);
                    tempCard.setAttribute('data-description', movie.description);
                    tempCard.setAttribute('data-cast', movie.cast);
                    tempCard.setAttribute('data-genres', movie.genres);
                    tempCard.setAttribute('data-this-movie-is', movie.this_movie_is || 'N/A');
                    tempCard.setAttribute('data-rating', movie.rating);
                    tempCard.setAttribute('data-trailer-id', movie.trailer_link || '');
                    const h3 = document.createElement('h3');
                    h3.textContent = movie.title;
                    tempCard.appendChild(h3);
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
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
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
                <td><img src="${banner.background_image}" class="admin-thumb" style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px;"></td>
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

});