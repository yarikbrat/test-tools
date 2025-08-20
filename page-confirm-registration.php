<?php

$token = isset($_GET['wcl_reg_token']) ? sanitize_text_field($_GET['wcl_reg_token']) : '';
if (!$token) wp_die('Invalid confirmation token.');

$pending_data = get_transient('wcl_pending_reg_' . $token);
if (!$pending_data) wp_die('Confirmation token expired or invalid.');

if (username_exists($pending_data['username']) || email_exists($pending_data['email'])) {
  wp_die('User already registered. You can log in.');
}

$user_id = wp_create_user($pending_data['username'], $pending_data['password'], $pending_data['email']);
if (is_wp_error($user_id)) wp_die('Error creating user: ' . esc_html($user_id->get_error_message()));

// If there is a temporary avatar transfer it and create an attachment
if (!empty($pending_data['avatar_tmp']) && file_exists($pending_data['avatar_tmp'])) {
  $upload_dir    = wp_upload_dir();
  $new_file_path = $upload_dir['path'] . '/' . basename($pending_data['avatar_tmp']);
  rename($pending_data['avatar_tmp'], $new_file_path);

  $filetype   = wp_check_filetype(basename($new_file_path), null);
  $attachment = [
    'guid'           => $upload_dir['url'] . '/' . basename($new_file_path),
    'post_mime_type' => $filetype['type'],
    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($new_file_path)),
    'post_content'   => '',
    'post_status'    => 'inherit',
  ];

  $attachment_id = wp_insert_attachment($attachment, $new_file_path);
  require_once ABSPATH . 'wp-admin/includes/image.php';
  wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $new_file_path));

  update_field('avatar', $attachment_id, 'user_' . $user_id);
}

delete_transient('wcl_pending_reg_' . $token);

wp_set_current_user($user_id);
wp_set_auth_cookie($user_id);
wp_redirect(site_url('/account'));
exit;
