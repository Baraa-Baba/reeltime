<div class="login" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
  <div class="login-background">
    <div class="login-content-panel surface-card">
      <div class="login-shell">
        <section class="login-panel" aria-labelledby="authModalTitle">
          <div class="login-toolbar">
            <div class="login-tabs" role="tablist" aria-label="Authentication tabs">
              <button class="button button-secondary auth-tab is-active" data-tab="login" type="button" aria-selected="true">Log In</button>
              <button class="button button-secondary auth-tab" data-tab="signup" type="button" aria-selected="false">Sign Up</button>
            </div>

            <button type="button" class="button button-secondary login-close" data-login-close aria-label="Close dialog">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <h2 id="authModalTitle" class="visually-hidden">Account Access</h2>

          <div class="auth-panel" id="loginPanel">
            <form class="login-form" id="loginForm">
              <h2 class="auth-panel-title">Account Access</h2>
              <div class="auth-field">
                <label for="loginUsername">Username</label>
                <div class="auth-input">
                  <i class="fa-regular fa-user" aria-hidden="true"></i>
                  <input id="loginUsername" placeholder="Enter your username" required>
                </div>
              </div>

              <div class="auth-field">
                <label for="loginPassword">Password</label>
                <div class="auth-input auth-input-password">
                  <i class="fa-solid fa-lock" aria-hidden="true"></i>
                  <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                  <button type="button" class="auth-password-toggle" data-password-target="loginPassword" aria-label="View password" aria-pressed="false">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                  </button>
                </div>
                <button type="button" id="showForgotPanelBtn" class="auth-inline-link">Forgot password?</button>
              </div>

              <button type="submit" class="button button-primary login-submit" id="loginSubmitBtn">
                <span class="btn-text">Log In</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Logging in...</span>
              </button>
            </form>
          </div>

          <div class="auth-panel" id="signupPanel" style="display: none;">
            <form class="login-form" id="signupForm">
              <h2 class="auth-panel-title">Create Account</h2>
              <div class="auth-field">
                <label for="signupUsername">Username</label>
                <div class="auth-input">
                  <i class="fa-regular fa-user" aria-hidden="true"></i>
                  <input id="signupUsername" placeholder="Choose a username" required minlength="3" maxlength="30">
                </div>
              </div>

              <div class="auth-field">
                <label for="signupEmail">Email</label>
                <div class="auth-input">
                  <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                  <input type="email" id="signupEmail" placeholder="Enter your email" required>
                </div>
              </div>

              <div class="auth-field">
                <label for="signupPassword">Password</label>
                <div class="auth-input auth-input-password">
                  <i class="fa-solid fa-lock" aria-hidden="true"></i>
                  <input type="password" id="signupPassword" placeholder="Create a password (min 6 chars)" required minlength="6">
                  <button type="button" class="auth-password-toggle" data-password-target="signupPassword" aria-label="View password" aria-pressed="false">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                  </button>
                </div>
              </div>

              <div class="auth-field">
                <label for="signupPasswordConfirm">Confirm Password</label>
                <div class="auth-input auth-input-password">
                  <i class="fa-solid fa-key" aria-hidden="true"></i>
                  <input type="password" id="signupPasswordConfirm" placeholder="Confirm your password" required minlength="6">
                  <button type="button" class="auth-password-toggle" data-password-target="signupPasswordConfirm" aria-label="View password confirmation" aria-pressed="false">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="button button-primary login-submit" id="signupSubmitBtn">
                <span class="btn-text">Create Account</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Creating...</span>
              </button>
            </form>
          </div>

          <div class="auth-panel" id="forgotPanel" style="display: none;">
            <form class="login-form" id="forgotPasswordForm">
              <h2 class="auth-panel-title">Reset Password</h2>
              <div class="auth-field">
                <label for="forgotEmail">Email</label>
                <div class="auth-input">
                  <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                  <input type="email" id="forgotEmail" placeholder="Enter your account email" required>
                </div>
              </div>

              <button type="submit" class="button button-primary login-submit" id="forgotSubmitBtn">
                <span class="btn-text">Send Reset Link</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Sending...</span>
              </button>
              <button type="button" class="button button-secondary login-secondary-btn" id="backToLoginBtn">Back to Login</button>
            </form>
          </div>

          <div class="auth-panel" id="resetPanel" style="display: none;">
            <form class="login-form" id="resetPasswordForm">
              <h2 class="auth-panel-title">Create New Password</h2>
              <input type="hidden" id="resetToken" name="token">
              <div class="auth-field">
                <label for="resetEmail">Email</label>
                <div class="auth-input">
                  <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                  <input type="email" id="resetEmail" name="email" placeholder="Enter your account email" required>
                </div>
              </div>

              <div class="auth-field">
                <label for="resetPassword">New Password</label>
                <div class="auth-input auth-input-password">
                  <i class="fa-solid fa-lock" aria-hidden="true"></i>
                  <input type="password" id="resetPassword" name="password" placeholder="Enter new password" required minlength="6">
                  <button type="button" class="auth-password-toggle" data-password-target="resetPassword" aria-label="View password" aria-pressed="false">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                  </button>
                </div>
              </div>

              <div class="auth-field">
                <label for="resetPasswordConfirm">Confirm Password</label>
                <div class="auth-input auth-input-password">
                  <i class="fa-solid fa-key" aria-hidden="true"></i>
                  <input type="password" id="resetPasswordConfirm" name="password_confirmation" placeholder="Confirm new password" required minlength="6">
                  <button type="button" class="auth-password-toggle" data-password-target="resetPasswordConfirm" aria-label="View password confirmation" aria-pressed="false">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="button button-primary login-submit" id="resetSubmitBtn">
                <span class="btn-text">Update Password</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Updating...</span>
              </button>
              <button type="button" class="button button-secondary login-secondary-btn" id="resetBackToLoginBtn">Back to Login</button>
            </form>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
