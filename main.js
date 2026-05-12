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

    // Form submission
    const signinForm = document.getElementById('signinForm');
    const signupForm = document.getElementById('signupForm');

    if (signinForm) {
        signinForm.addEventListener('submit', (e) => {
            e.preventDefault();
            showToast('Sign In successful!', 'success');
            loginModal.classList.remove('active');
        });
    }

    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            showToast('Account created successfully!', 'success');
            loginModal.classList.remove('active');
        });
    }
}

// ========== Event Registration Modal ==========
function initEventRegistration() {
    // Create event registration modal HTML
    const registrationHTML = `
        <div id="eventRegistrationModal" class="event-modal">
            <div class="event-modal-content">
                <span class="event-modal-close">&times;</span>
                <div class="event-details-section">
                    <h2 id="eventModalTitle"></h2>
                    <div class="event-modal-meta">
                        <div class="modal-meta-item">
                            <span class="modal-meta-label">Date & Time:</span>
                            <span id="eventModalDateTime"></span>
                        </div>
                        <div class="modal-meta-item">
                            <span class="modal-meta-label">Location:</span>
                            <span id="eventModalLocation"></span>
                        </div>
                    </div>
                    <div class="event-modal-description">
                        <h3>Event Details</h3>
                        <p id="eventModalDescription"></p>
                    </div>
                </div>
                
                <div class="registration-form-section">
                    <h3>Event Registration Form</h3>
                    <form id="eventRegistrationForm">
                        <div class="form-group">
                            <label for="regFullName">Full Name *</label>
                            <input type="text" id="regFullName" name="fullName" required>
                        </div>
                        <div class="form-group">
                            <label for="regEmail">Email Address *</label>
                            <input type="email" id="regEmail" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="regPhone">Phone Number *</label>
                            <input type="tel" id="regPhone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="regDepartment">Department *</label>
                            <select id="regDepartment" name="department" required>
                                <option value="">Select Department</option>
                                <option value="CSE">Computer Science & Engineering</option>
                                <option value="EEE">Electrical & Electronics Engineering</option>
                                <option value="ME">Mechanical Engineering</option>
                                <option value="CE">Civil Engineering</option>
                                <option value="ChE">Chemical Engineering</option>
                                <option value="URP">Urban & Regional Planning</option>
                                <option value="Architecture">Architecture</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="regTeamName">Team Name (if applicable)</label>
                            <input type="text" id="regTeamName" name="teamName">
                        </div>
                        <div class="form-group">
                            <label for="regExperience">Experience Level *</label>
                            <select id="regExperience" name="experience" required>
                                <option value="">Select Experience Level</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Professional">Professional</option>
                            </select>
                        </div>
                        <div class="form-group checkbox">
                            <input type="checkbox" id="regTerms" name="terms" required>
                            <label for="regTerms">I agree to the event rules and regulations *</label>
                        </div>
                        <button type="submit" class="submit-btn">Complete Registration</button>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    if (!document.getElementById('eventRegistrationModal')) {
        document.body.insertAdjacentHTML('beforeend', registrationHTML);
    }
    
    const modal = document.getElementById('eventRegistrationModal');
    const closeBtn = document.querySelector('.event-modal-close');
    const form = document.getElementById('eventRegistrationForm');
    
    // Close modal
    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });
    
    // Close on background click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });
    
    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const eventTitle = document.getElementById('eventModalTitle').textContent;
        const fullName = document.getElementById('regFullName').value;
        showToast(`Successfully registered for ${eventTitle}! Confirmation email sent to ${document.getElementById('regEmail').value}`, 'success');
        form.reset();
        modal.classList.remove('active');
    });
    
    // Add event card click handlers
    const eventCards = document.querySelectorAll('.event-card');
    eventCards.forEach(card => {
        const registerLink = card.querySelector('.event-link');
        registerLink.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Extract event details
            const title = card.querySelector('.event-title').textContent;
            const description = card.querySelector('.event-description').textContent;
            const day = card.querySelector('.event-day').textContent;
            const month = card.querySelector('.event-month').textContent;
            const time = card.querySelector('.event-time').textContent;
            const location = card.querySelector('.event-location').textContent;
            
            // Populate modal
            document.getElementById('eventModalTitle').textContent = title;
            document.getElementById('eventModalDescription').textContent = description;
            document.getElementById('eventModalDateTime').textContent = `${day} ${month} at ${time}`;
            document.getElementById('eventModalLocation').textContent = location;
            
            // Show modal
            modal.classList.add('active');
            form.reset();
        });
    });
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
    initEventRegistration();
    
    // Add lightbox styles
    addLightboxStyles();
    
    // Add event modal styles
    addEventModalStyles();
    
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

// ========== Add Event Modal Styles ==========
function addEventModalStyles() {
    const styleId = 'event-modal-styles';
    if (document.getElementById(styleId)) return;
    
    const styles = `
        #eventRegistrationModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            overflow-y: auto;
        }
        
        #eventRegistrationModal.active {
            display: flex;
            opacity: 1;
        }
        
        .event-modal-content {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            padding: 40px;
            position: relative;
            animation: slideUp 0.4s ease-in-out;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .event-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
            transition: color 0.3s;
            line-height: 1;
        }
        
        .event-modal-close:hover {
            color: #ff6b6b;
        }
        
        .event-details-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid #e5e5e5;
        }
        
        #eventModalTitle {
            font-size: 28px;
            color: var(--primary-blue, #2563eb);
            margin: 0 0 20px 0;
            font-weight: 700;
        }
        
        .event-modal-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .modal-meta-item {
            background-color: #f5f5f5;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid var(--primary-blue, #2563eb);
        }
        
        .modal-meta-label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .modal-meta-item span:not(.modal-meta-label) {
            color: #666;
            font-size: 14px;
        }
        
        .event-modal-description {
            margin-top: 15px;
        }
        
        .event-modal-description h3 {
            font-size: 16px;
            color: #333;
            margin: 0 0 10px 0;
            font-weight: 600;
        }
        
        #eventModalDescription {
            color: #666;
            line-height: 1.6;
            margin: 0;
            font-size: 14px;
        }
        
        .registration-form-section {
            margin-top: 0;
        }
        
        .registration-form-section h3 {
            font-size: 20px;
            color: #333;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        
        #eventRegistrationForm .form-group {
            margin-bottom: 18px;
        }
        
        #eventRegistrationForm label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        #eventRegistrationForm input[type="text"],
        #eventRegistrationForm input[type="email"],
        #eventRegistrationForm input[type="tel"],
        #eventRegistrationForm select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }
        
        #eventRegistrationForm input:focus,
        #eventRegistrationForm select:focus {
            outline: none;
            border-color: var(--primary-blue, #2563eb);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        #eventRegistrationForm input.error,
        #eventRegistrationForm select.error {
            border-color: #ff6b6b;
        }
        
        #eventRegistrationForm .form-group.checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        
        #eventRegistrationForm .form-group.checkbox input[type="checkbox"] {
            width: auto;
            margin-top: 5px;
            cursor: pointer;
        }
        
        #eventRegistrationForm .form-group.checkbox label {
            margin: 0;
            cursor: pointer;
            line-height: 1.4;
        }
        
        #eventRegistrationForm .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-blue, #2563eb) 0%, var(--secondary-blue, #3b82f6) 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        
        #eventRegistrationForm .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }
        
        @media (max-width: 768px) {
            .event-modal-content {
                padding: 25px;
                margin: 20px;
            }
            
            .event-modal-close {
                top: 15px;
                right: 15px;
            }
            
            #eventModalTitle {
                font-size: 22px;
            }
            
            .event-modal-meta {
                grid-template-columns: 1fr;
            }
            
            .registration-form-section h3 {
                font-size: 18px;
            }
        }
    `;
    
    const styleTag = document.createElement('style');
    styleTag.id = styleId;
    styleTag.textContent = styles;
    document.head.appendChild(styleTag);
}
