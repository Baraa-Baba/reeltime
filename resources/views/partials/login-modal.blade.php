<div class="login">
  <div class="login-background">
    <div class="login-content-panel">
      <!-- Tab Switcher -->
      <div class="auth-tabs">
        <button class="auth-tab active" data-tab="login">Log In</button>
        <button class="auth-tab" data-tab="signup">Sign Up</button>
      </div>

      <!-- LOGIN FORM -->
      <div class="auth-panel" id="loginPanel">
        <div class="login-header">
          <div class="logo-text">Welcome Back</div>
          <p class="welcome-message">Enter your details to log in</p>
        </div>

        <form class="login-form" id="loginForm">
          <label>Username</label>
          <input id="loginUsername" placeholder="Enter your username" required>

          <label for="loginPassword">Password</label>
          <div class="password-group">
            <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
          </div>

          <button type="submit" class="login-button" id="loginSubmitBtn">
            <span class="btn-text">Log In</span>
            <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Logging in...</span>
          </button>
          <p id="loginError" class="login-error" aria-live="polite"></p>
          <p id="loginSuccess" class="login-success" aria-live="polite"></p>
        </form>
      </div>

      <!-- SIGNUP FORM -->
      <div class="auth-panel" id="signupPanel" style="display: none;">
        <div class="login-header">
          <div class="logo-text">Join ReelTime</div>
          <p class="welcome-message">Create your account and start exploring</p>
        </div>

        <form class="login-form" id="signupForm">
          <label>Username</label>
          <input id="signupUsername" placeholder="Choose a username" required minlength="3" maxlength="30">

          <label>Email</label>
          <input type="email" id="signupEmail" placeholder="Enter your email" required>

          <label>Password</label>
          <div class="password-group">
            <input type="password" id="signupPassword" placeholder="Create a password (min 6 chars)" required minlength="6">
          </div>

          <label>Confirm Password</label>
          <div class="password-group">
            <input type="password" id="signupPasswordConfirm" placeholder="Confirm your password" required minlength="6">
          </div>

          <button type="submit" class="login-button" id="signupSubmitBtn">
            <span class="btn-text">Create Account</span>
            <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Creating...</span>
          </button>
          <p id="signupError" class="login-error" aria-live="polite"></p>
          <p id="signupSuccess" class="login-success" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </div>
</div>
