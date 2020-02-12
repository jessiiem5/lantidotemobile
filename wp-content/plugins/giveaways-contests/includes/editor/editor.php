<?php

////////////////////////////
// EDITOR PAGE
////////////////////////////

//ENQUEUE ANY SCRIPTS OR CSS FOR OUR ADMIN PAGE EDITOR
function fca_cc_admin_cpt_script( $hook ) {
	global $post;
	if ( ($hook == 'post-new.php' || $hook == 'post.php')  &&  $post->post_type === 'contest' ) {
		wp_enqueue_media();
		wp_enqueue_style('dashicons');
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-tooltip');

		wp_enqueue_style('jquery-datetimepicker-css', FCA_CC_PLUGINS_URL . '/includes/datetimepicker/jquery.datetimepicker.min.css', array(), FCA_CC_PLUGIN_VER );

		wp_enqueue_script('jquery-datetimepicker', FCA_CC_PLUGINS_URL . '/includes/datetimepicker/jquery.datetimepicker.full.min.js', array('jquery'), FCA_CC_PLUGIN_VER, true );

		wp_enqueue_script('fca_cc_admin_js', FCA_CC_PLUGINS_URL . '/includes/editor/admin.min.js', array('jquery','jquery-ui-core', 'jquery-ui-tooltip', 'wp-color-picker', 'jquery-datetimepicker' ), FCA_CC_PLUGIN_VER, true );
		wp_enqueue_style( 'fca_cc_admin_stylesheet', FCA_CC_PLUGINS_URL . '/includes/editor/admin.min.css', array(), FCA_CC_PLUGIN_VER );

		$admin_data = array (
			'ajaxurl' => admin_url ( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'fca_cc_admin_nonce' ),
		);

		wp_localize_script( 'fca_cc_admin_js', 'adminData', $admin_data );
	}

}
add_action( 'admin_enqueue_scripts', 'fca_cc_admin_cpt_script', 10, 1 );

//ADD META BOXES TO EDIT CPT PAGE
function fca_cc_add_custom_meta_boxes( $post ) {

	add_meta_box(
		'fca_cc_contest_meta_box',
		__( 'This Contest', 'contest-cat' ),
		'fca_cc_render_contest_meta_box',
		null,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_contest', 'fca_cc_add_custom_meta_boxes' );

function fca_cc_render_contest_meta_box( $post ) {

	$meta = get_post_meta ( $post->ID, 'fca_cc', true );
	$meta = empty ( $meta ) ? array() : $meta;
	//CHECK SETTINGS FOR EMPTY
	$settings = array (
		'description',
		'image',
		'end',
		'show_name',
		'terms',
	);
	forEach ( $settings as $setting ) {
		$meta[$setting] = empty ( $meta[$setting] ) ? '' : $meta[$setting];
	}

	$meta['description'] = empty ( $meta['description'] )? '' : $meta['description'];

	//SET END DATE +7 DAYS FOR NEW POSTS
	$screen = get_current_screen();

	if ( $screen->action === 'add' ) {
		$meta['end'] = date( 'Y/n/j G:i', current_time ( 'timestamp' ) + 604800 );
	}

	//ADD A HIDDEN PREVIEW URL INPUT
	$html = "<input type='hidden' name='fca_cc_preview_url' id='fca_cc_preview_url' value='" . get_permalink( $post ) . "'>";

	$html .= "<table class='fca_cc_setting_table'>";

		$html .= '<tr>';
			$html .= '<th>' . __('End Time', 'contest-cat') . '</th>';
			$html .= '<td>' . fca_cc_input( 'end', '', $meta['end'], 'datepicker' ) . '</td>';
		$html .=  '<tr>';

		$html .= '<tr>';
			$html .= '<th>' . __('Display name field', 'contest-cat') . '</th>';
			$html .= '<td>' . fca_cc_input( 'show_name', '', $meta['show_name'], 'checkbox' ) . '</td>';
		$html .=  '<tr>';


		$shortcode = '[contest-cat id="' . $post->ID . '"]';

		$html .= '<tr>';
			$html .= '<th>' . __('Shortcode', 'contest-cat') . '</th>';
			$html .= "<td><input type='text' id='fca-cc-shortcode' value='$shortcode' readonly></td>";
		$html .=  '<tr>';

	$html .= '</table>';

	echo $html;
}

//CUSTOM SAVE HOOK
function fca_cc_save_post ( $post_id ) {

	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}

	//ONLY DO OUR STUFF IF ITS A REAL SAVE, NOT A NEW IMPORTED ONE
	if ( array_key_exists( 'fca_cc_preview_url', $_POST ) ) {
		$meta = empty( $_POST['fca_cc'] ) ? '' : fca_cc_sanitize_post_save( $_POST );
		update_post_meta( $post_id, 'fca_cc', $meta );
		wp_publish_post( $post_id );
	}
}
add_action( 'save_post_contest', 'fca_cc_save_post' );

function fca_cc_sanitize_post_save ( $post ) {

	$data['description'] = empty( $post['description'] ) ? '' : wp_kses_post( $post['description'] );
	$data['image'] = empty( $post['fca_cc']['image'] ) ? '' : sanitize_text_field( $post['fca_cc']['image'] );
	$data['show_name'] = empty( $post['fca_cc']['show_name'] ) ? '' : 'on';
	$data['terms'] = empty( $post['fca_cc']['terms'] ) ? '' : wp_kses_post( $post['fca_cc']['terms'] );
	$data['end'] = empty( $post['fca_cc']['end'] ) ? '' : sanitize_text_field( $post['fca_cc']['end'] );

	return $data;

}

//PREVIEW
function fca_cc_live_preview( $content ){
	global $post;
	if ( is_user_logged_in() && $post->post_type === 'contest' && is_main_query() && !doing_action( 'wp_head' ) )  {
		return $content . do_shortcode("[contest-cat id='" . $post->ID . "']");
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'fca_cc_live_preview');

//Redirect when Save & Preview button is clicked
function fca_cc_save_preview_redirect ( $location ) {
	global $post;
	if ( !empty( $_POST['fca_cc_preview_url'] ) ) {
		// Flush rewrite rules
		global $wp_rewrite;
		$wp_rewrite->flush_rules( true );

		return esc_url( $_POST['fca_cc_preview_url'] );
	}

	return $location;
}
add_filter('redirect_post_location', 'fca_cc_save_preview_redirect');

//SUPPRESS POST TITLES ON OUR CUSTOM POST TYPE
function fca_cc_suppress_post_title() {
	global $post;
	if ( empty ( $post ) ) {
		return false;
	}
	if ( $post->post_type == 'contest' &&  is_main_query() ) {
		wp_enqueue_style( 'fca_cc_contest_post_stylesheet', FCA_CC_PLUGINS_URL .  '/includes/editor/hide-title.css', array(), FCA_CC_PLUGIN_VER );
	}
}
add_action( 'wp_enqueue_scripts', 'fca_cc_suppress_post_title' );
