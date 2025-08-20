<?php

if (is_user_logged_in()) {
  wp_redirect(home_url());
  exit;
} else {

  get_header();
?>
  <div class="wcl-container container" style="max-width: 400px;">
    <form id="wcl-login-form" method="post" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username or Email</label>
        <input type="text" name="username" id="username" class="form-control" required>
        <div class="invalid-feedback">
          Please enter your username or email.
        </div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
        <div class="invalid-feedback">
          Please enter your password.
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Log In</button>

      <div class="wcl-login-message mt-3"></div>

      <?php wp_nonce_field('wcl_login_action', 'wcl_login_nonce'); ?>
    </form>
  </div>

  <div class="wcl-container container py-5" style="max-width: 400px;">
    <form id="wcl-lost-password">
      <h2 class="text-center">Lost Password?</h2>
      <div class="mb-3">
        <label for="lost-pass-email" class="form-label">Enter Email</label>
        <input type="email" name="lost_pass_email" id="lost-pass-email" class="form-control" required>
        <div class="invalid-feedback">
          Please enter your email.
        </div>
      </div>
      <?php wp_nonce_field('wcl_lost_password_action', 'wcl_lost_password_nonce'); ?>
      <button type="submit" class="btn btn-primary w-100">Send</button>
    </form>
  </div>



<?php
}

get_footer();
