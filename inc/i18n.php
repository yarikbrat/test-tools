<?php

/*
 * Create a JSON object with translated words to different languages (init it before other JS files are uploaded)
 */
add_action( 'wp_footer', function () {

    ?>
    <script>

        const i18n = {
            message: {
                hello: '<?php _e( 'Hello WordPress', 'sitedomain' ) ?>',
            }
        };

    </script>
    <?php
} );