<?php

function fca_cc_upgrade_menu() {
	$page_hook = add_submenu_page(
		'edit.php?post_type=contest',
		__('Upgrade to Premium', 'contest-cat'),
		__('Upgrade to Premium', 'contest-cat'),
		'manage_options',
		'contest-cat-upgrade',
		'fca_cc_upgrade_ob_start'
	);
	add_action('load-' . $page_hook , 'fca_cc_upgrade_page');
}
add_action( 'admin_menu', 'fca_cc_upgrade_menu' );

function fca_cc_upgrade_ob_start() {
    ob_start();
}

function fca_cc_upgrade_page() {
    wp_redirect('https://fatcatapps.com/contestcat/upgrade?utm_medium=plugin&utm_source=contest%20Cat%20Free&utm_campaign=free-plugin', 301);
    exit();
}

function fca_cc_upgrade_to_premium_menu_js() {
    ?>
    <script type="text/javascript">
    	jQuery(document).ready(function ($) {
            $('a[href="edit.php?post_type=contest&page=contest-cat-upgrade"]').on('click', function () {
        		$(this).attr('target', '_blank')
            })
        })
    </script>
    <style>
        a[href="edit.php?post_type=contest&page=contest-cat-upgrade"] {
            color: #6bbc5b !important;
        }
        a[href="edit.php?post_type=contest&page=contest-cat-upgrade"]:hover {
            color: #7ad368 !important;
        }
    </style>
    <?php 
}
add_action( 'admin_footer', 'fca_cc_upgrade_to_premium_menu_js');
