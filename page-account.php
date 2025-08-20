<?php

if (!is_user_logged_in()) {
  wp_redirect(site_url('/log-in/'));
  exit;
}

get_header();

$current_user = wp_get_current_user();
?>

<div class="account-page py-5">
  <div class="account-info wcl-container container">
    <h1 class="mb-4 text-center">Account</h1>
    <div class="row justify-content-center">
      <div class="col-md-4 text-center mb-4">
        <div class="avatar mb-3">
          <?php
          $avatar_id = get_field('avatar', 'user_' . $current_user->ID);
          if ($avatar_id) {
            echo wp_get_attachment_image($avatar_id, 'thumbnail', false, ['id' => 'current-avatar', 'class' => 'rounded-circle img-thumbnail']);
          } else {
            echo get_avatar($current_user->ID, 96, '', '', ['id' => 'current-avatar', 'class' => 'rounded-circle img-thumbnail']);
          }
          ?>
        </div>
        <div class="user-details">
          <p><strong>Username:</strong> <?php echo esc_html($current_user->user_login); ?></p>
          <p><strong>Email:</strong> <?php echo esc_html($current_user->user_email); ?></p>
          <p><strong>Description:</strong> <span id="desc"><?php echo get_field('description', $current_user); ?></span></p>
        </div>
      </div>

      <div class="col-md-6">
        <form method="post" id="update-profile-form" enctype="multipart/form-data" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="avatar" class="form-label">Change Avatar:</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" class="form-control">
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <?php
            $description = get_user_meta($current_user->ID, 'description', true);
            ?>
            <textarea name="description" id="description" rows="6" class="form-control" placeholder="Write something about yourself..."><?php echo esc_textarea($description); ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary">Save</button>
          <div id="profile-message" class="mt-3"></div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>