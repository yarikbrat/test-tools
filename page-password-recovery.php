<?php

$token = isset($_GET['reset_token']) ? sanitize_text_field($_GET['reset_token']) : '';
get_header();
?>

<div class="wcl-container container py-5" style="max-width: 400px;">
  <?php if (!$token): ?>
    <p style="color: red;">Invalid or missing token.</p>
  <?php else: ?>
    <form id="wcl-password-reset-form">
      <h2 class="text-center">Reset Password</h2>
      <div class="mb-3">
        <label for="new-password" class="form-label">New Password</label>
        <input type="password" id="new-password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="confirm-password" class="form-label">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm_password" class="form-control" required>
      </div>
      <input type="hidden" name="reset_token" value="<?php echo esc_attr($token); ?>">
      <?php wp_nonce_field('wcl_recovery_password_action', 'wcl_recovery_password_nonce'); ?>
      <button type="submit" class="btn btn-primary w-100">Change Password</button>
    </form>
  <?php endif; ?>
</div>

<?php get_footer(); ?>