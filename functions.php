<?php


/** @desc this loads the composer autoload file */
require get_theme_file_path('/vendor/autoload.php');

/** @desc this instantiates Dotenv and passes in our path to .env.example */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/** @desc Using an .env.example file for security */
// define( 'RECAPTCHA_SECRET_KEY', $_ENV[ 'RECAPTCHA_SECRET_KEY' ] );
// define( 'RECAPTCHA_SITE_KEY', $_ENV[ 'RECAPTCHA_SITE_KEY' ] );
// define( 'EMAIL_SENDER', $_ENV[ 'EMAIL_SENDER' ] );

/** @desc Other variables */
define('WCL_THEME_VERSION', '0.0.1');
define('SITE_NAME', get_bloginfo('name'));
define('WCL_CONTACT_MAIL', 'admin@mail.com');

/** Define variable from ACF options page */
function wcl_acf_init()
{
  $contact_email = get_field('contact_email', 'option');
  $contact_phone = get_field('contact_phone', 'option');

  define('CONTACT_EMAIL', $contact_email);
  define('CONTACT_PHONE', $contact_phone);
}

// add_action( 'acf/init', 'wcl_acf_init' );


/** Enqueueing Styles & Scripts */
function wcl_theme_enqueue_scripts()
{
  // Remove jQuery from front-end of the website
  // wp_deregister_script('jquery');

  // Styles
  wp_enqueue_style('bootstrap-styles',  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css', [], WCL_THEME_VERSION);

  wp_enqueue_style('funcybox-styles',  'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css', [], WCL_THEME_VERSION);

  wp_enqueue_style('wcl-style', get_template_directory_uri() . '/assets/css/wcl-style.css', ['bootstrap-styles', 'funcybox-styles'], WCL_THEME_VERSION);

  // Scripts

  // wp_enqueue_script( 'captcha-js', 'https://www.google.com/recaptcha/api.js?render=' . RECAPTCHA_SITE_KEY, [], WCL_THEME_VERSION, true );

  wp_enqueue_script('bootstrap-scripts', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js', [], WCL_THEME_VERSION, true);

  wp_enqueue_script('funcybox-scripts', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js', [], WCL_THEME_VERSION, true);

  wp_enqueue_script('validate', 'https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js', [], WCL_THEME_VERSION, true);

  wp_enqueue_script('sweet-alert', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', [], WCL_THEME_VERSION, true);

  wp_enqueue_script('wcl-scripts', get_template_directory_uri() . '/assets/js/wcl-scripts.js', ['bootstrap-scripts', 'funcybox-scripts', 'validate', 'sweet-alert'], WCL_THEME_VERSION, true);

  wp_localize_script('wcl-scripts', 'config', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('wcl_nonce'),
    // 'recaptcha_key' => RECAPTCHA_SITE_KEY,
  ]);
}

add_action('wp_enqueue_scripts', 'wcl_theme_enqueue_scripts');


function my_acf_init()
{
  acf_update_setting('google_api_key', 'AIzaSyC5bH31kwKqG6gEJjZm04E74Fr866Niwdc');
}
add_action('acf/init', 'my_acf_init');



/** Enqueueing Styles & Scripts To Admin Panel */
function wcl_admin_enqueue_scripts($hook)
{
  // Styles
  wp_enqueue_style('wcl-admin-style', get_template_directory_uri() . '/assets/css/wcl-admin-style.css', [], WCL_THEME_VERSION);

  // Script
  wp_enqueue_script('wcl-admin-scripts', get_template_directory_uri() . '/assets/js/wcl-admin-scripts.js', [], WCL_THEME_VERSION, true);

  //for admin
  wp_localize_script('wcl-admin-scripts', 'wclData', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('wcl_nonce'),
  ]);
}

add_action('admin_enqueue_scripts', 'wcl_admin_enqueue_scripts', 100);


/** Remove default image sizes options */
function wcl_disable_unused_image_sizes($sizes)
{
  unset($sizes['thumbnail']);    // disable thumbnail size
  unset($sizes['medium']);       // disable medium size
  unset($sizes['large']);        // disable large size
  unset($sizes['medium_large']); // disable medium-large size
  unset($sizes['1536x1536']);    // disable 2x medium-large size
  unset($sizes['2048x2048']);    // disable 2x large size
  return $sizes;
}

add_action('intermediate_image_sizes_advanced', 'wcl_disable_unused_image_sizes');


function wcl_disable_other_images()
{
  remove_image_size('post-thumbnail'); // disable set_post_thumbnail_size()
  remove_image_size('another-size');   // disable other add image sizes
}

add_action('init', 'wcl_disable_other_images');

add_filter('big_image_size_threshold', '__return_false');


/** Add custom image sizes */
/*
add_image_size( 'banner-image', 1140, 0, false );
add_image_size( 'banner-image@2x', 2280, 0, false );
*/


/* * Support HTML 5 tags for styles and scripts */
function wcl_add_theme_support()
{
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['script', 'style']);
}

add_action('after_setup_theme', 'wcl_add_theme_support');


/** Register Nav Manus */

function wcl_register_nav_menus()
{
  register_nav_menu('header-menu', 'Header Menu');
  register_nav_menu('footer-menu', 'Footer Menu');
}

add_action('after_setup_theme', 'wcl_register_nav_menus');



/** ACF Option Page */
if (function_exists('acf_add_options_page')) {
  // Theme Settings page
  acf_add_options_page(array(
    'page_title' => 'Theme Settings',
    'menu_title' => 'Theme Settings',
    'menu_slug' => 'theme-settings',
    'capability' => 'edit_posts',
    'redirect' => false,
    'icon_url' => 'dashicons-admin-home',
  ));

  /*
    // Theme Settings Subpage
    acf_add_options_sub_page( array(
        'page_title' => 'Subpage',
        'menu_title' => 'Subpage',
        'parent_slug' => 'theme-settings',
    ) );
    */
}


/** Change WordPress Login Page Logo */
function wcl_custom_login_logo()
{
  echo '<style type="text/css">
        body.login {
            background: #2B2647 !important;
        }

        #login h1 a, .login h1 a {
            background-image: url(' . get_stylesheet_directory_uri() . '/assets/img/wcl-logo.png);
            height: 80px !important; /* Change the height as needed */
            width: 100% !important; /* Use 100% width for responsiveness */
            background-size: 100% !important; /* Adjust this property as needed */
        }

        .login #backtoblog a, .login #nav a {
            color: #fff !important;
        }

        .login #backtoblog a:hover, .login #nav a:hover {
            color: #E97872 !important;
        }
    </style>';
}

add_action('login_enqueue_scripts', 'wcl_custom_login_logo');


// Remove WP top bar for users
function wcl_remove_admin_bar()
{
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}

add_action('after_setup_theme', 'wcl_remove_admin_bar');


// Block the user from entering the admin panel
function wcl_restrict_admin_access()
{
  if (!current_user_can('manage_options') && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php') {
    wp_redirect(home_url());
    exit;
  }
}

add_action('admin_init', 'wcl_restrict_admin_access');

//for permalink alternative

add_action('init', function () {
  add_rewrite_rule(
    '^alternatives/([^/]+)/page/([0-9]+)/?$',
    'index.php?pagename=alternatives&tool=$matches[1]&paged=$matches[2]',
    'top'
  );

  add_rewrite_rule(
    '^alternatives/([^/]+)/?$',
    'index.php?pagename=alternatives&tool=$matches[1]',
    'top'
  );

  add_rewrite_rule(
    '^compare/([^/]+)-vs-([^/]+)/?$',
    'index.php?pagename=compare&tool1=$matches[1]&tool2=$matches[2]',
    'top'
  );
});

// query vars
add_filter('query_vars', function ($vars) {
  $vars[] = 'tool';
  $vars[] = 'tool1';
  $vars[] = 'tool2';
  return $vars;
});




//
add_filter('acf/fields/post_object/query/name=tool_2', function ($args, $field, $post_id) {

  $tool_1 = get_field('tool_1', $post_id);

  if (!$tool_1) {
    return $args;
  }

  if (is_object($tool_1)) {
    $tool_1_id = $tool_1->ID;
  } else {
    $tool_1_id = $tool_1;
  }

  $terms = wp_get_post_terms($tool_1_id, 'ai_tool_category', ['fields' => 'ids']);

  if (!empty($terms)) {
    $args['tax_query'] = [
      [
        'taxonomy' => 'ai_tool_category',
        'field' => 'term_id',
        'terms' => $terms,
      ],
    ];
  }

  return $args;
}, 10, 3);

//5 posts for compare cpt archive
add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query() && is_post_type_archive('compare')) {
    $query->set('posts_per_page', 5);
  }
});









/** Others Files */
require_once get_theme_file_path('/inc/helpers.php');

// ACF Blocks
require_once get_theme_file_path('/inc/acf-blocks.php');

// Translations
// require_once get_theme_file_path( '/inc/i18n.php' );

// Walker
// require_once get_theme_file_path( '/inc/walker/header-nav-menu.php' );

// CPT
require_once get_theme_file_path('/inc/custom-post-types/tools.php');
require_once get_theme_file_path('/inc/custom-post-types/compare-cpt.php');
require_once get_theme_file_path('/inc/custom-post-types/compare-tools.php');

// Actions
require_once get_theme_file_path('/inc/actions/ai-tools-categories.php');
require_once get_theme_file_path('/inc/actions/autocomplete.php');
require_once get_theme_file_path('/inc/actions/filter.php');
require_once get_theme_file_path('/inc/actions/compare.php');
require_once get_theme_file_path('/inc/actions/users.php');

// API
// require_once get_theme_file_path( '/inc/api/api.php' );

// Cron
require_once get_theme_file_path('/inc/cron/cleanup-unconfirmed.php');

//rest api
add_action('rest_api_init', function () {
  register_rest_route('appjs/v1', '/testlogin', [
    'methods' => 'POST',
    'callback' => 'user_generator_get_ui',
    'permission_callback' => '__return_true',
    'show_in_index' => true,
    'args' => [
      'login' => [
        'required' => true,
        'type' => 'string',
        // 'validate_callback' => function ($param, $request, $key) {
        //   return !empty($param);
        // },
      ],
      'password' => [
        'required' => true,
        'type' => 'string',
        // 'validate_callback' => function ($param, $request, $key) {
        //   return !empty($param);
        // },
      ],
    ],
  ]);
});

function user_generator_get_ui(WP_REST_Request $request)
{
  $login = wp_signon([
    'user_login' => $request['login'],
    'user_password' => $request['password'],
    'remember' => true,
  ]);

  if (is_wp_error($login)) {
    return new WP_Error('login_failed', 'Login failed', ['status' => 401]);
  }


  return [
    'login' => $login,

  ];
}


//block test



function wcl_register_acf_test_block()
{
  if (function_exists('acf_register_block_type')) {
    acf_register_block_type(array(
      'name'            => 'test-acf-block',
      'title'           => __('Test ACF Block'),
      'description'     => __('A simple block with ACF fields'),
      'render_callback' => function ($block) {
        include get_template_directory() . '/template-parts/blocks/test-acf-block/render.php';
      },
      'category'        => 'layout',
      'icon'            => 'admin-customizer',
      'keywords'        => array('acf', 'test'),
      'mode'            => 'edit',
    ));
  }
}
add_action('acf/init', 'wcl_register_acf_test_block');
