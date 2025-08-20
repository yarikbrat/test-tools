<?php

//register users
// function wcl_register_user_callback()
// {
//   $ajax_response = ['success' => 0, 'message' => '', 'html' => ''];

//   if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
//     $ajax_response['message'] = 'Nonce is missing or invalid';
//     wp_send_json($ajax_response);
//   }

//   $username        = sanitize_user($_POST['username']);
//   $email           = sanitize_email($_POST['email']);
//   $password        = $_POST['password'];
//   $password_repeat = $_POST['password_repeat'];

//   if (empty($username) || empty($email) || empty($password)) {
//     $ajax_response['message'] = 'Please fill in all required fields.';
//     wp_send_json($ajax_response);
//   }

//   if ($password !== $password_repeat) {
//     $ajax_response['message'] = 'The passwords do not match.';
//     wp_send_json($ajax_response);
//   }

//   if (strlen($password) < 8 || !preg_match('/[a-zA-Z]/', $password) || !preg_match('/\d/', $password)) {
//     $ajax_response['message'] = 'Password must be at least 8 characters long and contain at least one letter and one number.';
//     wp_send_json($ajax_response);
//   }

//   if (username_exists($username) || email_exists($email)) {
//     $ajax_response['message'] = 'A user with that username or email already exists.';
//     wp_send_json($ajax_response);
//   }

//   //Processing avatar in temporary folder
//   $avatar_tmp = '';
//   if (!empty($_FILES['avatar']['name'])) {
//     require_once ABSPATH . 'wp-admin/includes/file.php';

//     add_filter('upload_dir', '__tmp_avatar_upload_dir');
//     $uploaded = wp_handle_upload($_FILES['avatar'], ['test_form' => false]);
//     remove_filter('upload_dir', '__tmp_avatar_upload_dir');

//     if (isset($uploaded['file'])) {
//       $avatar_tmp = $uploaded['file'];
//     }
//   }


//   $token = bin2hex(random_bytes(16));
//   $pending_data = [
//     'username'   => $username,
//     'email'      => $email,
//     'password'   => $password,
//     'avatar_tmp' => $avatar_tmp,
//   ];

//   $time = 24 * HOUR_IN_SECONDS;


//   set_transient('wcl_pending_reg_' . $token, $pending_data, 0);

//   // clean in 24 hours
//   wp_schedule_single_event(time() + $time, 'wcl_cleanup_unconfirmed', [$token]);


//   $confirm_link = add_query_arg('wcl_reg_token', $token, site_url('/confirm-registration/'));
//   $subject = 'Confirm your registration';
//   $message = "Hello $username,\n\nPlease confirm your registration by clicking the link below:\n\n$confirm_link\n\nIf you didn't request this, just ignore this email.";
//   $headers = ['Content-Type: text/plain; charset=UTF-8'];
//   wp_mail($email, $subject, $message, $headers);

//   $ajax_response['success'] = 1;
//   $ajax_response['message'] = 'Registration initiated. Please check your email to confirm.';
//   wp_send_json($ajax_response);
// }

// // Temporary folder for avatars
// function __tmp_avatar_upload_dir($dirs)
// {
//   $dirs['subdir'] = '/tmp-avatars' . $dirs['subdir'];
//   $dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
//   $dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
//   return $dirs;
// }

// add_action('wp_ajax_nopriv_wcl_register_user', 'wcl_register_user_callback');


//account
function wcl_update_profile_callback()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'data'    => [],
  ];

  if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wcl_nonce')) {
    $ajax_response['message'] = 'Nonce is missing or invalid';
    wp_send_json($ajax_response);
  }

  if (!is_user_logged_in()) {
    $ajax_response['message'] = 'User not logged in';
    wp_send_json($ajax_response);
  }

  $user_id = get_current_user_id();

  $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';

  // update_user_meta($user_id, 'description', $description);
  update_field('description', $description, 'user_' . $user_id);

  if (!empty($_FILES['avatar']['name'])) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $attachment_id = media_handle_upload('avatar', 0);

    if (is_wp_error($attachment_id)) {
      $ajax_response['message'] = 'Error uploading avatar: ' . $attachment_id->get_error_message();
      wp_send_json($ajax_response);
    } else {

      update_field('avatar', $attachment_id, 'user_' . $user_id);

      $avatar_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
      $ajax_response['data']['avatar_url'] = $avatar_url;
    }
  }

  $ajax_response['data']['description'] = $description;

  $ajax_response['message'] = 'Profile updated successfully';
  $ajax_response['success'] = 1;

  wp_send_json($ajax_response);
}


add_action('wp_ajax_wcl_update_profile', 'wcl_update_profile_callback');

//log in

function wcl_log_in_handler()
{
  $ajax_response = [
    'success' => 0,
    'message' => '',
    'data'    => [],
  ];

  if (
    !isset($_POST['wcl_login_nonce']) ||
    !wp_verify_nonce($_POST['wcl_login_nonce'], 'wcl_login_action')
  ) {
    $ajax_response['message'] = __('Security check failed.', 'test-tools');
    wp_send_json($ajax_response);
  }

  $username = sanitize_user($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $ajax_response['message'] = __('Please fill in all fields.', 'test-tools');
    wp_send_json($ajax_response);
  }

  $creds = [
    'user_login'    => $username,
    'user_password' => $password,
    'remember'      => true,
  ];

  $user = wp_signon($creds, false);

  if (is_wp_error($user)) {
    $ajax_response['message'] = __('Incorrect login or password.', 'test-tools');
    wp_send_json($ajax_response);
  }

  $ajax_response['success'] = 1;
  $ajax_response['message'] = __('Login successful.', 'test-tools');
  wp_send_json($ajax_response);
}

add_action('wp_ajax_nopriv_log_in_handler', 'wcl_log_in_handler');

//lost password

function wcl_lost_password_handler()
{
  if (
    empty($_POST['wcl_lost_password_nonce']) ||
    ! wp_verify_nonce($_POST['wcl_lost_password_nonce'], 'wcl_lost_password_action')
  ) {
    wp_send_json_error(['message' => 'Invalid request, please try again']);
  }

  $email = sanitize_email($_POST['lost_pass_email'] ?? '');
  if (empty($email) || !is_email($email)) {
    wp_send_json_error(['message' => 'Please enter a valid email']);
  }

  $user = get_user_by('email', $email);
  if (!$user) {
    wp_send_json_error(['message' => 'No user found with this email']);
  }

  $token = wp_generate_password(32, false);

  set_transient('wcl_pwreset_' . $token, $user->ID, HOUR_IN_SECONDS);

  $reset_link = add_query_arg('reset_token', $token, site_url('/password-recovery/'));

  $subject = 'Password Reset Request';
  $message = "Hello {$user->user_login},\n\nClick the link below to reset your password:\n{$reset_link}\n\nIf you didn't request this, ignore this email.";
  $headers = ['Content-Type: text/plain; charset=UTF-8'];

  wp_mail($email, $subject, $message, $headers);

  wp_send_json_success(['message' => 'Password reset link sent to your email']);
}

add_action('wp_ajax_nopriv_wcl_lost_password_handler', 'wcl_lost_password_handler');

//reset password

function wcl_password_reset_handler()
{
  if (
    empty($_POST['wcl_recovery_password_nonce']) ||
    ! wp_verify_nonce($_POST['wcl_recovery_password_nonce'], 'wcl_recovery_password_action')
  ) {
    wp_send_json_error(['message' => 'Invalid request, please try again']);
  }

  $token = sanitize_text_field($_POST['reset_token'] ?? '');
  $new_pass = sanitize_text_field($_POST['new_password'] ?? '');
  $confirm_pass = sanitize_text_field($_POST['confirm_password'] ?? '');

  if (!$token || !$new_pass || !$confirm_pass) {
    wp_send_json_error(['message' => 'All fields are required']);
  }

  if ($new_pass !== $confirm_pass) {
    wp_send_json_error(['message' => 'Passwords do not match']);
  }

  $user_id = get_transient('wcl_pwreset_' . $token);
  if (!$user_id) {
    wp_send_json_error(['message' => 'Invalid or expired token']);
  }

  wp_set_password($new_pass, $user_id);
  delete_transient('wcl_pwreset_' . $token);

  wp_send_json_success(['message' => 'Password changed successfully']);
}

add_action('wp_ajax_nopriv_wcl_password_reset_handler', 'wcl_password_reset_handler');


//register user via REST API
add_action('rest_api_init', function () {
  register_rest_route('wcl/v1', '/register', [
    'methods' => 'POST',
    'callback' => 'wcl_rest_register_user',
    'permission_callback' => '__return_true',
    'args' => [
      'username' => ['required' => true, 'type' => 'string'],
      'email' => ['required' => true, 'type' => 'string'],
      'password' => ['required' => true, 'type' => 'string'],
      'password_repeat' => ['required' => true, 'type' => 'string'],
      'wcl_nonce' => ['required' => true, 'type' => 'string'],
    ],
  ]);
});

function wcl_rest_register_user(WP_REST_Request $request)
{
  $nonce = $request->get_param('wcl_nonce');
  if (! $nonce || ! wp_verify_nonce($nonce, 'wcl_rest_nonce')) {
    return new WP_Error('invalid_nonce', 'Invalid or missing nonce', ['status' => 403]);
  }

  $username = sanitize_user($request['username']);
  $email = sanitize_email($request['email']);
  $password = $request['password'];
  $password_repeat = $request['password_repeat'];

  if ($password !== $password_repeat) {
    return new WP_Error('password_mismatch', 'Passwords do not match', ['status' => 400]);
  }

  if (username_exists($username) || email_exists($email)) {
    return new WP_Error('user_exists', 'Username or email already exists', ['status' => 400]);
  }

  $avatar_tmp = '';
  if (!empty($_FILES['avatar']['name'])) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    add_filter('upload_dir', '__tmp_avatar_upload_dir');
    $uploaded = wp_handle_upload($_FILES['avatar'], ['test_form' => false]);
    remove_filter('upload_dir', '__tmp_avatar_upload_dir');

    if (isset($uploaded['file'])) {
      $avatar_tmp = $uploaded['file'];
    }
  }

  $token = bin2hex(random_bytes(16));
  $pending_data = compact('username', 'email', 'password', 'avatar_tmp');

  set_transient('wcl_pending_reg_' . $token, $pending_data, 0);

  wp_schedule_single_event(time() + 24 * HOUR_IN_SECONDS, 'wcl_cleanup_unconfirmed', [$token]);

  $confirm_link = add_query_arg('wcl_reg_token', $token, site_url('/confirm-registration/'));
  wp_mail($email, 'Confirm your registration', "Hello $username,\n\nConfirm: $confirm_link");

  return ['success' => true, 'message' => 'Registration initiated. Check your email.'];
}

// Temporary folder for avatars
function __tmp_avatar_upload_dir($dirs)
{
  $dirs['subdir'] = '/tmp-avatars' . $dirs['subdir'];
  $dirs['path']   = $dirs['basedir'] . $dirs['subdir'];
  $dirs['url']    = $dirs['baseurl'] . $dirs['subdir'];
  return $dirs;
}
