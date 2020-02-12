<?php
/*
	Plugin Name: Contest Cat Free
	Plugin URI: https://fatcatapps.com/contest-cat
	Description: Provides an easy way to create and administer contests
	Text Domain: contest-cat
	Domain Path: /languages
	Author: Fatcat Apps
	Author URI: https://fatcatapps.com/
	License: GPLv2
	Version: 1.0.2
*/

// BASIC SECURITY
defined( 'ABSPATH' ) or die( 'Unauthorized Access!' );



if ( !defined('FCA_CC_PLUGIN_DIR') ) {

	//DEFINE SOME USEFUL CONSTANTS
	define( 'FCA_CC_PLUGIN_VER', '1.0.2' );
	define( 'FCA_CC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'FCA_CC_PLUGINS_URL', plugins_url( '', __FILE__ ) );
	define( 'FCA_CC_PLUGIN_FILE', __FILE__ );
	define( 'FCA_CC_PLUGIN_PACKAGE', 'Free' ); //DONT CHANGE THIS, IT WONT ADD FEATURES, ONLY BREAKS UPDATER AND LICENSE

	//LOAD CORE
	include_once( FCA_CC_PLUGIN_DIR . '/includes/api.php' );
	include_once( FCA_CC_PLUGIN_DIR . '/includes/db.php' );
	if ( file_exists ( FCA_CC_PLUGIN_DIR . '/includes/upgrade.php' ) ) {
		//include_once( FCA_CC_PLUGIN_DIR . '/includes/upgrade.php' );
	}

	//LOAD MODULES
	include_once( FCA_CC_PLUGIN_DIR . '/includes/contest/contest.php' );
	include_once( FCA_CC_PLUGIN_DIR . '/includes/editor/editor.php' );
	if ( file_exists ( FCA_CC_PLUGIN_DIR . '/includes/editor/sidebar.php' ) ) {
		//include_once( FCA_CC_PLUGIN_DIR . '/includes/editor/sidebar.php' );
	}

	include_once( FCA_CC_PLUGIN_DIR . '/includes/entries/entries.php' );


	if ( file_exists ( FCA_CC_PLUGIN_DIR . '/includes/splash/splash.php' ) ) {
		//include_once( FCA_CC_PLUGIN_DIR . '/includes/splash/splash.php' );
	}

	//ACTIVATION HOOK
	function fca_cc_activation() {
		fca_cc_create_table();
		fca_cc_api_action( 'Activated Contest Cat Free' );
	}
	register_activation_hook( FCA_CC_PLUGIN_FILE, 'fca_cc_activation' );

	//DEACTIVATION HOOK
	function fca_cc_deactivation() {
		fca_cc_api_action( 'Deactivated Contest Cat Free' );
	}
	register_deactivation_hook( FCA_CC_PLUGIN_FILE, 'fca_cc_deactivation' );

	////////////////////////////
	// SET UP POST TYPE
	////////////////////////////

	//REGISTER CPT
	function fca_cc_register_post_type() {

		$labels = array(
			'name' => _x('Contests','contest-cat'),
			'singular_name' => _x('Contest','contest-cat'),
			'add_new' => _x('Add New','contest-cat'),
			'all_items' => _x('All Contests','contest-cat'),
			'add_new_item' => _x('Add New Contest','contest-cat'),
			'edit_item' => _x('Edit Contest','contest-cat'),
			'new_item' => _x('New Contest','contest-cat'),
			'view_item' => _x('View Contest','contest-cat'),
			'search_items' => _x('Search Contests','contest-cat'),
			'not_found' => _x('Contest not found','contest-cat'),
			'not_found_in_trash' => _x('No Contests found in trash','contest-cat'),
			'parent_item_colon' => _x('Parent Contest:','contest-cat'),
			'menu_name' => _x('Contest','contest-cat')
		);

		$args = array(
			'labels' => $labels,
			'description' => "",
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 102,
			// 'menu_icon' => FCA_CC_PLUGINS_URL . '/assets/icon.png',
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array('title'),
			'has_archive' => false,
			'rewrite' => false,
			'query_var' => true,
			'can_export' => true
		);

		register_post_type( 'contest', $args );
	}
	add_action ( 'init', 'fca_cc_register_post_type' );

	//CHANGE CUSTOM 'UPDATED' MESSAGES FOR OUR CPT
	function fca_cc_post_updated_messages( $messages ){

		$post = get_post();

		$messages['contest'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Contest updated.','contest-cat'),
			2  => __( 'Contest updated.','contest-cat'),
			3  => __( 'Contest deleted.','contest-cat'),
			4  => __( 'Contest updated.','contest-cat'),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Contest restored to revision from %s','contest-cat'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Contest published.' ,'contest-cat'),
			7  => __( 'Contest saved.' ,'contest-cat'),
			8  => __( 'Contest submitted.' ,'contest-cat'),
			9  => sprintf(
				__( 'Contest scheduled for: <strong>%1$s</strong>.','contest-cat'),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Contest draft updated.' ,'contest-cat'),
		);

		return $messages;
	}
	add_filter('post_updated_messages', 'fca_cc_post_updated_messages' );

	//Customize CPT table columns
	function fca_cc_add_new_post_table_columns($columns) {
		$new_columns = array();
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Contest Name', 'contest-cat');
		$new_columns['shortcode'] = __('Shortcode', 'contest-cat');
		$new_columns['entries'] = __('Participants', 'contest-cat');
		$new_columns['status'] = __('Status', 'contest-cat');


		return $new_columns;
	}
	add_filter('manage_edit-contest_columns', 'fca_cc_add_new_post_table_columns', 10, 1 );

	function fca_cc_manage_post_table_columns( $column_name, $id ) {

		$meta = get_post_meta( $id, 'fca_cc', true);
		$entries = fca_cc_count_entries( $id );
		$end =  empty( $meta['end'] ) ? -1 : strtotime( $meta['end'] );

		if ( $end == -1 ) {
			$status = _x( 'Not Scheduled', 'contest-cat' );
		} else if ( ( $end - current_time( 'timestamp' ) ) < 0 ) {
			$status = _x( 'Ended', 'e.g. this contest ended', 'contest-cat' );
		} else {
			$status = _x( 'Running', 'e.g. this contest is running', 'contest-cat' ) . ' (' . fca_cc_seconds_to_time( $end - current_time( 'timestamp' ) ) . _x( ' remaining)', 'as in time remaining', 'contest-cat' );
		}

		$remaining_time = fca_cc_seconds_to_time( $end - current_time( 'timestamp' ) );

		switch ($column_name) {
			case 'shortcode':
				echo '<input type="text" readonly="readonly" onclick="this.select()" value="[contest-cat id=&quot;'. $id . '&quot;]"/>';
					break;
			case 'entries':
				echo "<a href='" . admin_url( "admin.php?page=contest-cat-entries") . "&id=$id" . "'>" . $entries . "</a>";
					break;
			case 'status':
				echo $status;
					break;

			default:
			break;
		} // end switch
	}
	// Add to admin_init function
	add_action('manage_contest_posts_custom_column', 'fca_cc_manage_post_table_columns', 10, 2);

	function fca_cc_post_row_actions ( $actions, $post ) {

		if ( $post->post_type == 'contest' ) {
			$actions['fca_cc_view_entries'] = "<a href='" . admin_url( "admin.php?page=contest-cat-entries") . "&id=$post->ID" . "'>" . __( 'View Entries', 'contest-cat' ) . "</a>";
		}

		return $actions;
	}
	add_filter( 'post_row_actions', 'fca_cc_post_row_actions', 10, 2 );

	////////////////////////////
	// LOCALIZATION
	////////////////////////////

	//SET UP TEXTS - CHECK FOR LOCALIZED STRINGS, THEN ANY PHP FILTERS, THEN SHORTCODES
	function fca_cc_set_contest_text_strings( $atts ) {
		//FILTERABLE FRONT-END STRINGS
		$global_contest_text_strings = array (
			'enter' => __('Enter Contest', 'contest-cat'),
			'you_have_1_entry' => __('Tu est enregistré comme participant', 'contest-cat'),
			'your_name' => __('', 'contest-cat'),
			'your_email' => __('', 'contest-cat'),
			'your_tel' => __('ex : (4187244851)', 'contest-cat'),
			'your_code' => __('', 'contest-cat'),
			'terms' => __('By entering, I agree to the Terms & Conditions', 'contest-cat'),
			'days' => _x('d', 'abbreviation for days', 'contest-cat'),
			'hours' => _x('h', 'abbreviation for hours', 'contest-cat'),
			'minutes' => _x('m', 'abbreviation for minutes', 'contest-cat'),
			'seconds' => _x('s', 'abbreviation for seconds', 'contest-cat'),
			'ended' => __('This contest is no longer accepting entries.', 'contest-cat'),
		);

		$contest_text_strings = apply_filters( 'fca_cc_contest_text', $global_contest_text_strings );

		$shortcode_text_strings = array (

			'enter' => empty( $atts['enter'] ) ? false : $atts['enter'],
			'you_have_1_entry' => empty( $atts['you_have_1_entry'] ) ? false : $atts['you_have_1_entry'],
			'your_name' => empty( $atts['your_name'] ) ? false : $atts['your_name'],
			'your_email' => empty( $atts['your_email'] ) ? false : $atts['your_email'],
			'your_tel' => empty( $atts['your_tel'] ) ? false : $atts['your_tel'],
			'your_code' => empty( $atts['your_code'] ) ? false : $atts['your_code'],
			'terms' => empty( $atts['terms'] ) ? false : $atts['terms'],
			'days' => empty( $atts['days'] ) ? false : $atts['days'],
			'minutes' => empty( $atts['minutes'] ) ? false : $atts['minutes'],
			'hours' => empty( $atts['hours'] ) ? false : $atts['hours'],
			'seconds' => empty( $atts['seconds'] ) ? false : $atts['seconds'],
			'ended' => empty( $atts['ended'] ) ? false : $atts['ended'],
		);

		//CHECK SHORTCODES FOR TRANSLATIONS & OVERWRITE
		forEach ( $contest_text_strings as $key => $value ) {
			if ( !empty ( $shortcode_text_strings[$key] ) && $shortcode_text_strings[$key] !== false ) {
				$contest_text_strings[$key] = $shortcode_text_strings[$key];
			}
		}

		return $contest_text_strings;

	}

	function fca_cc_load_localization() {
		load_plugin_textdomain( 'contest-cat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	add_action( 'init', 'fca_cc_load_localization' );


	function fca_cc_remove_screen_options_tab ( $show_screen, $screen ) {
		if ( $screen->id == 'contest' ) {
			return false;
		}
		return $show_screen;
	}
	add_filter('screen_options_show_screen', 'fca_cc_remove_screen_options_tab', 10, 2);

	function fca_cc_tooltip( $text = 'Tooltip', $icon = 'dashicons dashicons-editor-help' ) {
		return "<span class='$icon fca_cc_tooltip' title='" . htmlentities( $text ) . "'></span>";
	}


	////////////////////////////
	// FUNCTIONS
	////////////////////////////

	function fca_cc_seconds_to_time( $inputSeconds, $text_strings = array() ) {

		$text_strings = empty ( $text_strings ) ? fca_cc_set_contest_text_strings(array()) : $text_strings;

		$secondsInAMinute = 60;
		$secondsInAnHour  = 60 * $secondsInAMinute;
		$secondsInADay    = 24 * $secondsInAnHour;

		// extract days
		$days = floor($inputSeconds / $secondsInADay);

		// extract hours
		$hourSeconds = $inputSeconds % $secondsInADay;
		$hours = floor($hourSeconds / $secondsInAnHour);

		// extract minutes
		$minuteSeconds = $hourSeconds % $secondsInAnHour;
		$minutes = floor($minuteSeconds / $secondsInAMinute);

		// extract the remaining seconds
		$remainingSeconds = $minuteSeconds % $secondsInAMinute;
		$seconds = ceil($remainingSeconds);

		// return the final string
		$string = '';
		if ( $days > 0 ) {
			$string .= "<span class='fca_cc_time_days'>$days</span><span class='fca_cc_time_units'>" . $text_strings['days'] . "</span> ";
		}
		if ( $hours > 0 OR $days > 0 ) {
			$string .= "<span class='fca_cc_time_hours'>$hours</span><span class='fca_cc_time_units'>" . $text_strings['hours'] . "</span> ";
		}

		$string .= "<span class='fca_cc_time_minutes'>$minutes</span><span class='fca_cc_time_units'>" . $text_strings['minutes'] . "</span> ";
		$string .= "<span class='fca_cc_time_seconds'>$seconds</span><span class='fca_cc_time_units'>" . $text_strings['seconds'] . "</span> ";

		return $string;


	}

	//RETURN GENERIC INPUT HTML
	function fca_cc_input ( $name, $placeholder = '', $value = '', $type = 'input' ) {

		$html = "<div class='fca-cc-field fca-cc-field-$type'>";

			switch ( $type ) {

				case 'checkbox':
					$checked = !empty( $value ) ? "checked='checked'" : '';

					$html .= "<div class='onoffswitch'>";
						$html .= "<input style='display:none;' type='checkbox' id='fca_cc[$name]' class='onoffswitch-checkbox fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]' $checked>";
						$html .= "<label class='onoffswitch-label' for='fca_cc[$name]'><span class='onoffswitch-inner' data-content-on='ON' data-content-off='OFF'><span class='onoffswitch-switch'></span></span></label>";
					$html .= "</div>";
					break;

				case 'textarea':
					$html .= "<textarea placeholder='$placeholder' class='fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]'>$value</textarea>";
					break;

				case 'image':
					$html .= "<input type='hidden' class='fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]' value='$value'>";
					$html .= "<button type='button' class='button-secondary fca_cc_image_upload_btn'>" . __('Add Image', 'contest-cat') . "</button>";
					$html .= "<img class='fca_cc_image' style='max-width: 252px' src='$value'>";

					$html .= "<div class='fca_cc_image_hover_controls'>";
						$html .= "<button type='button' class='button-secondary fca_cc_image_change_btn'>" . __('Change', 'contest-cat') . "</button>";
						$html .= "<button type='button' class='button-secondary fca_cc_image_revert_btn'>" . __('Remove', 'contest-cat') . "</button>";
					$html .=  '</div>';
					break;
				case 'color':
					$html .= "<input type='hidden' placeholder='$placeholder' class='fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]' value='$value'>";
					break;
				case 'editor':
					ob_start();
					wp_editor( $value, $name, array() );
					$html .= ob_get_clean();
					break;
				case 'datepicker':
					$html .= "<input type='text' placeholder='$placeholder' class='fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]' value='$value'>";
					break;

				default:
					$html .= "<input type='$type' placeholder='$placeholder' class='fca-cc-input-$type fca-cc-$name' name='fca_cc[$name]' value='$value'>";
			}

		$html .= '</div>';

		return $html;

	}

	function fca_cc_convert_entities ( $array ) {
		$array = is_array($array) ? array_map('fca_cc_convert_entities', $array) : html_entity_decode( $array, ENT_QUOTES );
		return $array;
	}

	//DEACTIVATION SURVEY
	function fca_cc_admin_deactivation_survey( $hook ) {
		if ( $hook === 'plugins.php' ) {

			ob_start(); ?>

			<div id="fca-deactivate" style="position: fixed; left: 232px; top: 191px; border: 1px solid #979797; background-color: white; z-index: 9999; padding: 12px; max-width: 669px;">
				<h3 style="font-size: 14px; border-bottom: 1px solid #979797; padding-bottom: 8px; margin-top: 0;"><?php _e( 'Sorry to see you go', 'contest-cat' ) ?></h3>
				<p><?php _e( 'Hi, this is David, the creator of Contest Cat. Thanks so much for giving my plugin a try. I’m sorry that you didn’t love it.', 'contest-cat' ) ?>
				</p>
				<p><?php _e( 'I have a quick question that I hope you’ll answer to help us make Contest Cat better: what made you deactivate?', 'contest-cat' ) ?>
				</p>
				<p><?php _e( 'You can leave me a message below. I’d really appreciate it.', 'contest-cat' ) ?>
				</p>

				<p><textarea style='width: 100%;' id='fca-cc-deactivate-textarea' placeholder='<?php _e( 'What made you deactivate?', 'contest-cat' ) ?>'></textarea></p>

				<div style='float: right;' id='fca-deactivate-nav'>
					<button style='margin-right: 5px;' type='button' class='button button-secondary' id='fca-cc-deactivate-skip'><?php _e( 'Skip', 'contest-cat' ) ?></button>
					<button type='button' class='button button-primary' id='fca-cc-deactivate-send'><?php _e( 'Send Feedback', 'contest-cat' ) ?></button>
				</div>

			</div>

			<?php

			$html = ob_get_clean();

			$data = array(
				'html' => $html,
				'nonce' => wp_create_nonce( 'fca_cc_uninstall_nonce' ),
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			);

			wp_enqueue_script('fca_cc_deactivation_js', FCA_CC_PLUGINS_URL . '/includes/deactivation.min.js', false, FCA_CC_PLUGIN_VER, true );
			wp_localize_script( 'fca_cc_deactivation_js', "fca_cc", $data );
		}


	}
	add_action( 'admin_enqueue_scripts', 'fca_cc_admin_deactivation_survey' );
}
