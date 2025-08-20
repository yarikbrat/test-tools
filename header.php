<!DOCTYPE html>
<html <?php echo get_language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo wp_get_document_title(); ?></title>

  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


  <!--
====================================================================
    DEVELOPED BY WebComplete (webcomplete.io)
====================================================================
 -->

  <div class="wcl-body-inner">
    <!-- HEADER -->
    <header id="wcl-header" class="wcl-header navbar-light bg-light">
      <div class="wcl-container">
        <nav id="header-navigation-desc" class="wcl-header__navigation navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <?php if (is_user_logged_in()) : ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/account')); ?>">Account</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/log-out')); ?>">Log Out</a>
                </li>
              <?php else : ?>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/log-in')); ?>">Log In</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo esc_url(home_url('/register')); ?>">Register</a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </nav>


      </div>
    </header>