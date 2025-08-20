<?php

if (is_user_logged_in()) {
  wp_logout();
}

wp_redirect(home_url());
exit;

get_header();

get_footer();
