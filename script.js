// Login Modal Functionality
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
        document.getElementById(tabName + 'Form').classList.add('active');
    });
});

// Tab link switching
tabLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const tabName = link.getAttribute('data-tab');
        const btn = document.querySelector(`[data-tab="${tabName}"]`);
        btn.click();
    });
});

// Form submission (prevent default for now)
const signinForm = document.getElementById('signinForm');
const signupForm = document.getElementById('signupForm');

if (signinForm) {
    signinForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Sign In functionality would be implemented here');
    });
}

if (signupForm) {
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Sign Up functionality would be implemented here');
    });
}
