<?php
/**
 * Reusable Authentication Modal Component
 * Include this file in any page to add login/signup functionality
 * 
 * Usage:
 * <?php include 'includes/auth-modal.php'; ?>
 * 
 * Make sure main.js is loaded AFTER this modal in your page
 */
?>

<!-- Login Modal (Reusable Component) -->
<div class="login-modal" id="loginModal">
    <div class="login-container">
        <span class="close-btn" id="closeBtn">&times;</span>
        
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Logged In View -->
            <div class="user-menu">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                <p style="color: #999; margin: 10px 0; font-size: 14px;"><?php echo htmlspecialchars($_SESSION['user']); ?></p>
                
                <div style="margin-top: 30px;">
                    <button class="submit-btn" style="width: 100%; margin-bottom: 10px;" onclick="window.location.href='members.php'">View Profile</button>
                    <button class="submit-btn" style="width: 100%; background-color: #dc3545;" id="logoutBtn">Logout</button>
                </div>
            </div>
        <?php else: ?>
            <!-- Logged Out View -->
            <!-- Tab Buttons -->
            <div class="login-tabs">
                <button class="tab-btn active" data-tab="signin">Sign In</button>
                <button class="tab-btn" data-tab="signup">Sign Up</button>
            </div>

            <!-- Sign In Form -->
            <form class="login-form active" id="signinForm" method="POST" action="auth-backend.php">
                <h2>Sign In</h2>
                <input type="hidden" name="action" value="signin">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <button type="submit" class="submit-btn">Sign In</button>
                <p class="form-footer">Don't have an account? <a href="#" class="tab-link" data-tab="signup">Sign Up</a></p>
            </form>

            <!-- Sign Up Form -->
            <form class="login-form" id="signupForm" method="POST" action="auth-backend.php">
                <h2>Sign Up</h2>
                <input type="hidden" name="action" value="signup">
                <div class="form-group">
                    <input type="text" name="full_name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <div class="form-group">
                    <select name="team" placeholder="Select Team (Optional)">
                        <option value="">Select Team (Optional)</option>
                        <option value="Cricket">Cricket</option>
                        <option value="Football">Football</option>
                        <option value="Badminton">Badminton</option>
                        <option value="Tennis">Tennis</option>
                        <option value="Athletics">Athletics</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group checkbox">
                    <input type="checkbox" name="terms" id="terms">
                    <label for="terms">I agree to the terms and conditions</label>
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
                <p class="form-footer">Already have an account? <a href="#" class="tab-link" data-tab="signin">Sign In</a></p>
            </form>
        <?php endif; ?>
    </div>
</div>
