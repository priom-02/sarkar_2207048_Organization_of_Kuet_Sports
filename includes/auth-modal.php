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
                    <label for="profile_pic" style="display: block; margin-bottom: 8px; color: #333; font-weight: 600;">Profile Picture (Optional)</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 100%;">
                    <small style="color: #999; display: block; margin-top: 4px;">Accepted formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                </div>
                <div class="form-group">
                    <select name="team" placeholder="Select Team (Optional)">
                        <option value="">Select Team (Optional)</option>
                        <option value="Cricket Team">Cricket Team</option>
                        <option value="Football Team">Football Team</option>
                        <option value="Badminton Team">Badminton Team</option>
                        <option value="Basketball Team">Basketball Team</option>
                        <option value="Tennis Team">Tennis Team</option>
                        <option value="Athletics Team">Athletics Team</option>
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
