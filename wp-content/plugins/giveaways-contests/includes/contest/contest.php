<?php

function fca_cc_shortcode_enqueue ( $post_id ) {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'dashicons' );


	wp_enqueue_style( 'fca_cc_tooltipster_stylesheet',FCA_CC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.css', array(), FCA_CC_PLUGIN_VER );
	wp_enqueue_style( 'fca_cc_tooltipster_borderless_stylesheet',FCA_CC_PLUGINS_URL . '/includes/tooltipster/tooltipster-borderless.min.css', array(), FCA_CC_PLUGIN_VER );
	wp_enqueue_style( 'fca_cc_tooltipster_fca_themestylesheet',FCA_CC_PLUGINS_URL . '/includes/tooltipster/tooltipster-fca-theme.min.css', array(), FCA_CC_PLUGIN_VER );
	wp_enqueue_script( 'fca_cc_tooltipster_js', FCA_CC_PLUGINS_URL . '/includes/tooltipster/tooltipster.bundle.min.js', array(), FCA_CC_PLUGIN_VER, true );

	wp_enqueue_style( 'fca_cc_contest_stylesheet',FCA_CC_PLUGINS_URL . '/includes/contest/contest.css', array(), FCA_CC_PLUGIN_VER );
	wp_enqueue_script( 'fca_cc_contest_js', FCA_CC_PLUGINS_URL . '/includes/contest/contest.js', array(), FCA_CC_PLUGIN_VER, true );

	$contestData = array(
		'post_id' => $post_id,
		'nonce' => wp_create_nonce( 'fca_cc_contest_nonce' ),
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	);

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();
		if ( $user->ID !== 0 ) {
			$contestData['user'] = array (
				'name' => $user->user_firstname,
				'email' => $user->user_email,
			);
		}
	}

	wp_localize_script( 'fca_cc_contest_js', "contestData_$post_id", $contestData );

}

function fca_cc_contest_shortcode ( $atts ) {

	if ( !empty ( $atts[ 'id' ] ) ) {

		$post_id = intVal ( $atts[ 'id' ] );

		$post_meta = get_post_meta( $post_id, 'fca_cc', true );
		$title = get_the_title ( $post_id );

		if ( !empty( $post_meta ) ) {

			fca_cc_shortcode_enqueue( $post_id );
			$text_strings = fca_cc_set_contest_text_strings( $atts );

			//ADD IMPRESSION

			$seconds_remaining = empty( $post_meta['end'] ) ? 0 : strtotime( $post_meta['end'] ) - current_time( 'timestamp' );
			$seconds_remaining = $seconds_remaining < 0 ? 0 : $seconds_remaining;
			$remaining_formatted = fca_cc_seconds_to_time( $seconds_remaining );

			$maybe_hide_ended_p = $seconds_remaining <= 0 ? '' : 'style="display:none"';

			//ADD LINE BREAKS TO TERMS
			$post_meta['terms'] = '<p>' . str_replace( "\n", '<br>', $post_meta['terms'] ) . '</p>';

			ob_start(); ?>
			<div class='fca_cc_contest' data-postid='<?php echo $post_id ?>' id='<?php echo "fca_cc_contest_$post_id" ?>'>
				<h5 class='fca_cc_title mt-4 mb-3'>Participer</h5>
				<div class='fca_cc_countdown' data-seconds='<?php echo $seconds_remaining ?>'><span class="dashicons dashicons-clock"></span><span class='timer'><?php echo $remaining_formatted ?></span></div>
				<span class='fca_cc_mobile_check'></span>
				<?php //only add button if time remains
					if ( $seconds_remaining > 0 ) { ?>
					<button type='button' id='fca_cc_enter_button' class='fca_cc_button'>Participer</button>
				<?php }
				echo "<p $maybe_hide_ended_p class='fca_cc_contest_ended'>" . $text_strings[ 'ended' ] . '</p>' ?>
				<div class='fca_cc_contest_entry' style='display: none;'>
					<?php if ( !empty ( $post_meta['show_name'] ) ) { ?>

						<div class="contact">
							<label>Nom</label>
							<span>
							<input type='text' id='fca_cc_name_input' title='<?php _e('Please enter your name.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_name'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
							</span>
						</div>

						<div class="contact">
						<label>Email</label>
						<span>
						<input type='text' id='fca_cc_email_input' title='<?php _e('Please enter your email.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_email'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>

						<div class="contact">
						<label>Téléphone</label>
						<span>
						<input type='text' id='fca_cc_tel_input' title='<?php _e('Please enter your number.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_tel'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>
						<div class="contact">
						<label>Code Postal</label>
						<span>
						<input type='text' id='fca_cc_code_input' title='<?php _e('Please enter your number.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_code'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>
						<div class="d-flex">
							<input id="mycheck" class="mt-1" type="checkbox" name="checkbox" value="check"/><p class="ml-3">J'accepte de recevoir des offres à caractère promotionnel</p>
						</div>
						<p class="rouge"></p>

					<?php } else { ?>
						<div class="contact">
						<label>Email</label>
						<span>
						<input type='text' id='fca_cc_email_input' title='<?php _e('Please enter your email.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_email'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>

						<div class="contact">
						<label>Téléphone</label>
						<span>
						<input type='text' id='fca_cc_tel_input' title='<?php _e('Please enter your number.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_tel'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>
						<div class="contact">
						<label>Code Postal</label>
						<span>
						<input type='text' id='fca_cc_code_input' title='<?php _e('Please enter your number.', 'quiz-cat') ?>' placeholder='<?php echo $text_strings['your_code'] ?>' class='fca_cc_optin_input' name='fca_cc_name_input'></input>
						</span>
						</div>

						<div class="d-flex">
							<input id="mycheck" class="mt-1" type="checkbox" name="checkbox" value="check"/><p class="ml-3">J'accepte de recevoir des offres à caractère promotionnel</p>
						</div>
						<p class="rouge"></p>
					<?php }
					//only add button if time remains
					if ( $seconds_remaining > 0 ) { ?>
						<button type='button' id='fca_cc_optin_button' class='fca_cc_button'><?php echo $text_strings[ 'enter' ] ?></button>
					<?php } ?>
					<a href='#' class='fca_cc_terms' title='<?php echo $post_meta['terms'] ?>'><?php echo $text_strings[ 'terms' ] ?></a>

				</div>
				<div class='fca_cc_contest_results' style='display: none;'>
					<p class='fca_cc_entry_text'><?php echo $text_strings['you_have_1_entry'] ?></p>
				</div>
			</div>

			<?php

			return ob_get_clean();

		}
	}

	return '<p>Contest Cat: ' . __('No contest found', 'contest-cat') . '</p>';

}
add_shortcode( 'contest-cat', 'fca_cc_contest_shortcode' );
