<?php
add_action('wcl_cleanup_unconfirmed', function ($token) {
  $pending_data = get_transient('wcl_pending_reg_' . $token);
  if (!$pending_data) return;

  if (!empty($pending_data['avatar_tmp']) && file_exists($pending_data['avatar_tmp'])) {
    unlink($pending_data['avatar_tmp']);
  }

  delete_transient('wcl_pending_reg_' . $token);
});
