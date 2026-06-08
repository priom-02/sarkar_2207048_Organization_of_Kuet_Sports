// ========== Dark Mode Toggle ==========
function initDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const htmlElement = document.documentElement;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', currentTheme);
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            const theme = htmlElement.getAttribute('data-theme');
            const newTheme = theme === 'light' ? 'dark' : 'light';
            
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }
}

// ========== Mobile Menu Toggle ==========
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-menu a');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
    
    // Close menu when a link is clicked
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            menuToggle.classList.remove('active');
        });
    });
}

// ========== Smooth Scrolling ==========
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// ========== Scroll to Top Button ==========
function initScrollToTop() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    
    if (scrollToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });
        
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// ========== Toast Notification System ==========
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    // Add styles if not in CSS
    Object.assign(toast.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        padding: '15px 20px',
        borderRadius: '4px',
        zIndex: '9999',
        animation: 'slideIn 0.3s ease-in-out'
    });
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in-out';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ========== Form Validation ==========
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();
                showToast('Please fill all required fields correctly', 'error');
            }
        });
    });
}

function validateForm(form) {
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            // Validate email
            if (input.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    input.classList.add('error');
                    isValid = false;
                } else {
                    input.classList.remove('error');
                }
            } else {
                input.classList.remove('error');
            }
        }
    });
    
    return isValid;
}

// Add error styling when user leaves field
document.addEventListener('blur', (e) => {
    if (e.target.hasAttribute('required') && !e.target.value.trim()) {
        e.target.classList.add('error');
    } else {
        e.target.classList.remove('error');
    }
}, true);

// ========== Image Lazy Loading ==========
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        images.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
}

// ========== Search Functionality ==========
function initSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            
            if (query.length === 0) {
                if (searchResults) searchResults.innerHTML = '';
                return;
            }
            
            // Search in members
            const members = document.querySelectorAll('.member-card, .member-item');
            const results = [];
            
            members.forEach(member => {
                const text = member.textContent.toLowerCase();
                if (text.includes(query)) {
                    results.push(member);
                }
            });
            
            if (searchResults && results.length > 0) {
                searchResults.innerHTML = results.map(r => 
                    `<div class="search-result">${r.textContent}</div>`
                ).join('');
            }
        });
    }
}

// ========== Page Transition Effects ==========
function initPageTransition() {
    window.addEventListener('load', () => {
        document.body.style.opacity = '1';
    });
    
    document.querySelectorAll('a:not([target="_blank"]):not([href^="#"])').forEach(link => {
        link.addEventListener('click', (e) => {
            if (!link.href.includes(window.location.hostname)) {
                return;
            }
            
            e.preventDefault();
            document.body.style.opacity = '0';
            
            setTimeout(() => {
                window.location.href = link.href;
            }, 300);
        });
    });
}

// ========== Login Modal Functionality ==========
function initLoginModal() {
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const closeBtn = document.getElementById('closeBtn');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const loginForms = document.querySelectorAll('.login-form');
    const tabLinks = document.querySelectorAll('.tab-link');

    // Open modal
    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            loginModal.classList.add('active');
        });
    }

    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            loginModal.classList.remove('active');
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            loginModal.classList.remove('active');
        }
    });

    // Tab switching
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.getAttribute('data-tab');
            
            // Remove active class from all tabs and forms
            tabBtns.forEach(b => b.classList.remove('active'));
            loginForms.forEach(form => form.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding form
            btn.classList.add('active');
            const formElement = document.getElementById(tabName + 'Form');
            if (formElement) {
                formElement.classList.add('active');
            }
        });
    });

    // Tab link switching
    tabLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const tabName = link.getAttribute('data-tab');
            const btn = document.querySelector(`[data-tab="${tabName}"]`);
            if (btn) btn.click();
        });
    });

    // Logout functionality
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            const formData = new FormData();
            formData.append('action', 'logout');
            
            fetch('auth-backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Logged out successfully', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'home.php';
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        });
    }

    // Form submission - Sign In
    const signinForm = document.getElementById('signinForm');
    if (signinForm) {
        signinForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = signinForm.querySelector('input[name="email"]').value.trim();
            const password = signinForm.querySelector('input[name="password"]').value;
            
            console.log('Signin Form Data:', { email, password: '***' });
            
            if (!email || !password) {
                showToast('Please fill all fields', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'signin');
            formData.append('email', email);
            formData.append('password', password);
            
            console.log('Sending signin request to auth-backend.php');
            
            fetch('auth-backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'home.php';
                    }, 1500);
                } else {
                    showToast(data.message || 'Signin failed', 'error');
                    console.error('Signin error details:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        });
    }

    // Form submission - Sign Up
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get form values using name attributes
            const full_name = signupForm.querySelector('input[name="full_name"]').value.trim();
            const email = signupForm.querySelector('input[name="email"]').value.trim();
            const password = signupForm.querySelector('input[name="password"]').value;
            const confirm_password = signupForm.querySelector('input[name="confirm_password"]').value;
            const team = signupForm.querySelector('select[name="team"]').value;
            const terms = signupForm.querySelector('input[name="terms"]').checked;
            
            console.log('Signup Form Data:', { full_name, email, team, password: '***', confirm_password: '***', terms });
            
            if (!full_name || !email || !password || !confirm_password) {
                showToast('Please fill all fields', 'error');
                return;
            }
            
            if (!terms) {
                showToast('Please agree to terms and conditions', 'error');
                return;
            }
            
            if (password !== confirm_password) {
                showToast('Passwords do not match', 'error');
                return;
            }
            
            if (password.length < 6) {
                showToast('Password must be at least 6 characters', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'signup');
            formData.append('full_name', full_name);
            formData.append('email', email);
            formData.append('team', team);
            formData.append('password', password);
            formData.append('confirm_password', confirm_password);
            
            console.log('Sending signup request to auth-backend.php');
            
            fetch('auth-backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    showToast(data.message, 'success');
                    // Clear form
                    signupForm.reset();
                    setTimeout(() => {
                        window.location.href = data.redirect || 'home.php';
                    }, 1500);
                } else {
                    showToast(data.message || 'Signup failed', 'error');
                    console.error('Signup error details:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showToast('An error occurred. Please try again.', 'error');
            });
        });
    }
}



// ========== Lightbox Gallery Modal ==========
function initLightbox() {
    // Create lightbox HTML
    const lightboxHTML = `
        <div id="lightboxModal" class="lightbox-modal">
            <div class="lightbox-content">
                <span class="lightbox-close">&times;</span>
                <button class="lightbox-prev">❮</button>
                <img id="lightboxImage" src="" alt="Gallery Image" class="lightbox-image">
                <button class="lightbox-next">❯</button>
                <div class="lightbox-info">
                    <h3 id="lightboxTitle"></h3>
                    <p id="lightboxDescription"></p>
                </div>
            </div>
        </div>
    `;
    
    if (!document.getElementById('lightboxModal')) {
        document.body.insertAdjacentHTML('beforeend', lightboxHTML);
    }
    
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxDescription = document.getElementById('lightboxDescription');
    const closeBtn = document.querySelector('.lightbox-close');
    const prevBtn = document.querySelector('.lightbox-prev');
    const nextBtn = document.querySelector('.lightbox-next');
    const galleryPhotos = document.querySelectorAll('.gallery-photo');
    
    let currentImageIndex = 0;
    let visibleImages = [];
    
    // Get visible gallery images
    function updateVisibleImages() {
        visibleImages = Array.from(galleryPhotos).filter(img => {
            const item = img.closest('.gallery-item');
            return item.style.display !== 'none';
        });
    }
    
    // Open lightbox
    galleryPhotos.forEach((photo, index) => {
        photo.addEventListener('click', () => {
            updateVisibleImages();
            currentImageIndex = visibleImages.indexOf(photo);
            displayImage(currentImageIndex);
            lightboxModal.classList.add('active');
        });
        
        photo.style.cursor = 'pointer';
    });
    
    // Close lightbox
    closeBtn.addEventListener('click', () => {
        lightboxModal.classList.remove('active');
    });
    
    // Close on background click
    lightboxModal.addEventListener('click', (e) => {
        if (e.target === lightboxModal) {
            lightboxModal.classList.remove('active');
        }
    });
    
    // Navigation
    prevBtn.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex - 1 + visibleImages.length) % visibleImages.length;
        displayImage(currentImageIndex);
    });
    
    nextBtn.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex + 1) % visibleImages.length;
        displayImage(currentImageIndex);
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (!lightboxModal.classList.contains('active')) return;
        
        if (e.key === 'ArrowLeft') prevBtn.click();
        if (e.key === 'ArrowRight') nextBtn.click();
        if (e.key === 'Escape') closeBtn.click();
    });
    
    function displayImage(index) {
        const photo = visibleImages[index];
        const item = photo.closest('.gallery-item');
        const overlay = item.querySelector('.gallery-overlay');
        
        lightboxImage.src = photo.src;
        lightboxImage.alt = photo.alt;
        
        if (overlay) {
            const title = overlay.querySelector('h3');
            const description = overlay.querySelector('p');
            lightboxTitle.textContent = title ? title.textContent : 'Gallery Image';
            lightboxDescription.textContent = description ? description.textContent : '';
        }
    }
}

// ========== Gallery Filter Functionality ==========
function initGalleryFilter() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filterValue = btn.getAttribute('data-filter');

            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            btn.classList.add('active');

            // Show/hide gallery items
            galleryItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                
                if (filterValue === 'all' || itemCategory === filterValue) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Add transition effect to gallery items
    galleryItems.forEach(item => {
        item.style.transition = 'opacity 0.3s ease-in-out';
        item.style.opacity = '1';
    });
}

// ========== Initialize All Functions on Page Load ==========
document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    initMobileMenu();
    initSmoothScroll();
    initScrollToTop();
    initFormValidation();
    initLazyLoading();
    initSearch();
    initPageTransition();
    initLoginModal();
    initGalleryFilter();
    initLightbox();
    
    // Add lightbox styles
    addLightboxStyles();
    
    // Page load animation
    document.body.style.transition = 'opacity 0.3s ease-in-out';
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 10);
});

// ========== Add Lightbox Styles ==========
function addLightboxStyles() {
    const styleId = 'lightbox-styles';
    if (document.getElementById(styleId)) return;
    
    const styles = `
        #lightboxModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        #lightboxModal.active {
            display: flex;
            opacity: 1;
        }
        
        .lightbox-content {
            position: relative;
            max-width: 90vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: zoomIn 0.3s ease-in-out;
        }
        
        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .lightbox-image {
            max-width: 85vw;
            max-height: 75vh;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
            z-index: 10001;
        }
        
        .lightbox-close:hover {
            color: #ff6b6b;
        }
        
        .lightbox-prev,
        .lightbox-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            font-size: 30px;
            padding: 15px 20px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            z-index: 10001;
        }
        
        .lightbox-prev {
            left: 20px;
        }
        
        .lightbox-next {
            right: 20px;
        }
        
        .lightbox-prev:hover,
        .lightbox-next:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
        
        .lightbox-info {
            text-align: center;
            color: white;
            margin-top: 20px;
        }
        
        #lightboxTitle {
            font-size: 28px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        
        #lightboxDescription {
            font-size: 18px;
            margin: 0;
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            .lightbox-content {
                max-width: 95vw;
                max-height: 95vh;
            }
            
            .lightbox-image {
                max-width: 90vw;
                max-height: 60vh;
            }
            
            .lightbox-prev,
            .lightbox-next {
                padding: 10px 15px;
                font-size: 24px;
            }
            
            .lightbox-close {
                font-size: 30px;
                top: 15px;
                right: 20px;
            }
            
            #lightboxTitle {
                font-size: 20px;
            }
            
            #lightboxDescription {
                font-size: 14px;
            }
        }
    `;
    
    const styleTag = document.createElement('style');
    styleTag.id = styleId;
    styleTag.textContent = styles;
    document.head.appendChild(styleTag);
}

