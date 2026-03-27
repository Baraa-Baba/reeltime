<div class="login" aria-hidden="true">
  <div class="login-background d-flex align-items-center justify-content-center p-3 p-lg-4">
    <div class="login-content-panel card border-0 shadow-lg overflow-hidden bg-body-tertiary w-100" style="max-width: 1100px;">
      <div class="row g-0">
        <div class="col-lg-5 p-4 p-lg-5 bg-dark border-end border-secondary-subtle">
          <span class="badge text-bg-warning text-dark rounded-pill mb-3">Member access</span>
          <h2 class="h3 fw-bold mb-3">One account for watchlists, bookings, and trivia.</h2>
          <p class="text-secondary mb-4">Sign in once and keep your movie night moving across every part of ReelTime.</p>

          <div class="d-grid gap-3 small text-secondary">
            <div class="d-flex gap-2">
              <i class="fa-solid fa-check text-warning mt-1"></i>
              <span>Save and revisit watchlists</span>
            </div>
            <div class="d-flex gap-2">
              <i class="fa-solid fa-check text-warning mt-1"></i>
              <span>Book seats without re-entering your details</span>
            </div>
            <div class="d-flex gap-2">
              <i class="fa-solid fa-check text-warning mt-1"></i>
              <span>Track ratings and game scores in one place</span>
            </div>
          </div>
        </div>

        <div class="col-lg-7 p-4 p-lg-5">
          <div class="d-flex gap-2 mb-4">
            <button class="btn btn-warning text-dark flex-fill active" data-tab="login" type="button">Log In</button>
            <button class="btn btn-outline-warning flex-fill" data-tab="signup" type="button">Sign Up</button>
          </div>

          <div class="auth-panel" id="loginPanel">
            <div class="mb-4">
              <h3 class="h4 fw-bold mb-1">Welcome back</h3>
              <p class="text-secondary mb-0">Enter your details to pick up where you left off.</p>
            </div>

            <form class="login-form" id="loginForm">
              <div class="mb-3">
                <label class="form-label" for="loginUsername">Username</label>
                <input class="form-control form-control-lg" id="loginUsername" placeholder="Enter your username" required>
              </div>

              <div class="mb-3">
                <label class="form-label" for="loginPassword">Password</label>
                <input class="form-control form-control-lg" type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
              </div>

              <button type="submit" class="btn btn-warning btn-lg w-100 fw-semibold" id="loginSubmitBtn">
                <span class="btn-text">Log In</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Logging in...</span>
              </button>
              <p id="loginError" class="login-error text-danger small mt-2 mb-0" aria-live="polite"></p>
              <p id="loginSuccess" class="login-success text-success small mt-2 mb-0" aria-live="polite"></p>
            </form>
          </div>

          <div class="auth-panel" id="signupPanel" style="display: none;">
            <div class="mb-4">
              <h3 class="h4 fw-bold mb-1">Join ReelTime</h3>
              <p class="text-secondary mb-0">Create your account and start building your list.</p>
            </div>

            <form class="login-form" id="signupForm">
              <div class="mb-3">
                <label class="form-label" for="signupUsername">Username</label>
                <input class="form-control form-control-lg" id="signupUsername" placeholder="Choose a username" required minlength="3" maxlength="30">
              </div>

              <div class="mb-3">
                <label class="form-label" for="signupEmail">Email</label>
                <input class="form-control form-control-lg" type="email" id="signupEmail" placeholder="Enter your email" required>
              </div>

              <div class="mb-3">
                <label class="form-label" for="signupPassword">Password</label>
                <input class="form-control form-control-lg" type="password" id="signupPassword" placeholder="Create a password (min 6 chars)" required minlength="6">
              </div>

              <div class="mb-3">
                <label class="form-label" for="signupPasswordConfirm">Confirm Password</label>
                <input class="form-control form-control-lg" type="password" id="signupPasswordConfirm" placeholder="Confirm your password" required minlength="6">
              </div>

              <button type="submit" class="btn btn-warning btn-lg w-100 fw-semibold" id="signupSubmitBtn">
                <span class="btn-text">Create Account</span>
                <span class="btn-loader" style="display:none;"><i class="fas fa-spinner fa-spin me-1"></i>Creating...</span>
              </button>
              <p id="signupError" class="login-error text-danger small mt-2 mb-0" aria-live="polite"></p>
              <p id="signupSuccess" class="login-success text-success small mt-2 mb-0" aria-live="polite"></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
