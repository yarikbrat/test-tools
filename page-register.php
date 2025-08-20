<?php

get_header();
?>

<div class="container py-5" style="max-width: 480px;">
  <h2 class="mb-4">Registration</h2>

  <form id="wcl-register-form" enctype="multipart/form-data" class="needs-validation" novalidate>
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wcl_nonce'); ?>">

    <div class="mb-3">
      <label for="wcl_username" class="form-label">User name*</label>
      <input type="text" name="username" id="wcl_username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_email" class="form-label">Email*</label>
      <input type="email" name="email" id="wcl_email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_password" class="form-label">Password*</label>
      <div class="input-group">
        <input type="password" class="form-control" id="wcl_password" name="password" required>
        <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
      </div>
    </div>


    <div class="mb-3">
      <label for="wcl_password_repeat" class="form-label">Confirm password*</label>
      <input type="password" name="password_repeat" id="wcl_password_repeat" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_avatar" class="form-label">Avatar (optional)</label>
      <input type="file" name="avatar" id="wcl_avatar" class="form-control" accept="image/*">
    </div>


    <button type="submit" class="btn btn-primary w-100">Submit</button>
  </form>

  <div id="wcl-register-message" class="mt-3"></div>
</div>

<?php get_footer(); ?>