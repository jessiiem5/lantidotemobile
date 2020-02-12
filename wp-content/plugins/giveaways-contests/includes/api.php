<?php

////////////////////////////
// API ENDPOINTS
////////////////////////////

//ENTRIES END POINT
function fca_cc_add_entry_ajax() {

	$post_id = intval( $_REQUEST['post_id'] );
	$nonce = sanitize_text_field( $_REQUEST['nonce'] );
	$email = sanitize_email( $_REQUEST['email'] );
	$name = sanitize_text_field( $_REQUEST['name'] );
	$tel = sanitize_text_field( $_REQUEST['tel'] );
	$code = sanitize_text_field( $_REQUEST['code'] );

	$nonceVerified = wp_verify_nonce( $nonce, 'fca_cc_contest_nonce') == 1;
	$idVerified = is_int( $post_id ) && $post_id > 0;

	$post_meta = get_post_meta( $post_id, 'fca_cc', true );

	$remaining =  empty( $post_meta['end'] ) ? 0 : strtotime( $post_meta['end'] ) - current_time( 'timestamp' );

	if ( $remaining <= 0 ) {
		wp_send_json_error('ended');
	}

	if ( $nonceVerified && $idVerified && !empty( $email ) ) {

		if ( fca_cc_entry_exists( $post_id, $email ) ) {
			wp_send_json_error('exists');
		}

		$entry = array(
			'name' => $name,
			'email' => $email,
			'time' => current_time( 'mysql' ),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'status' => 'eligible',
			'tel' => $tel,
			'code' => $code,
		);

		if ( fca_cc_add_entry( $post_id, $entry ) ) {
			wp_send_json_success();
		}
	}
	wp_send_json_error();

}
add_action( 'wp_ajax_fca_cc_entry', 'fca_cc_add_entry_ajax' );
add_action( 'wp_ajax_nopriv_fca_cc_entry', 'fca_cc_add_entry_ajax' );

//TOGGLE ELIGIBILITY END POINT
function fca_cc_set_eligible_status() {

	$nonce = sanitize_text_field( $_REQUEST['nonce'] );
	$id = intval( $_REQUEST['id'] );
	$status = sanitize_text_field( $_REQUEST['status'] );

	$nonceVerified = wp_verify_nonce( $nonce, 'fca_cc_entries_nonce') == 1;
	$idVerified = is_int( $id ) && $id > 0;

	if ( $nonceVerified && $idVerified && in_array( $status, array( 'eligible', 'rejected' ) ) ) {

		if ( fca_cc_update_eligibility( $id, $status ) ) {

			wp_send_json_success( $id );
		}

	}
	wp_send_json_error();

}
add_action( 'wp_ajax_fca_cc_set_eligible_status', 'fca_cc_set_eligible_status' );

//REJECT A WINNER
function fca_cc_reject_winner() {

	$nonce = sanitize_text_field( $_REQUEST['nonce'] );
	$id = intval( $_REQUEST['id'] );

	$nonceVerified = wp_verify_nonce( $nonce, 'fca_cc_entries_nonce') == 1;
	$idVerified = is_int( $id ) && $id > 0;

	if ( $nonceVerified && $idVerified ) {

		if ( fca_cc_update_eligibility( $id, 'rejected' ) ) {
			wp_send_json_success( $id );
		}
	}
	wp_send_json_error();

}
add_action( 'wp_ajax_fca_cc_reject_winner', 'fca_cc_reject_winner' );


//DRAW WINNER END POINT
function fca_cc_pick_winner_ajax() {

	$post_id = intval ( $_REQUEST['post_id'] );
	$nonce = sanitize_text_field( $_REQUEST['nonce'] );

	$nonceVerified = wp_verify_nonce( $nonce, 'fca_cc_entries_nonce') == 1;
	$idVerified = is_int( $post_id ) && $post_id > 0;

	if ( $nonceVerified && $idVerified ) {
		$winner = fca_cc_get_new_winner( $post_id );
		if ( $winner !== false ) {
			wp_send_json_success( $winner );
		}
	}

	wp_send_json_error();

}
add_action( 'wp_ajax_fca_cc_get_winner', 'fca_cc_pick_winner_ajax' );

function fca_cc_filter_eligible( $arr ) {
	return $arr['status'] === 'eligible';
}

//UNINSTALL ENDPOINT
function fca_cc_uninstall_ajax() {

	$msg = sanitize_text_field( $_REQUEST['msg'] );
	$nonce = sanitize_text_field( $_REQUEST['nonce'] );
	$nonceVerified = wp_verify_nonce( $nonce, 'fca_cc_uninstall_nonce') == 1;

	if ( $nonceVerified && !empty( $msg ) ) {

		$url =  "https://api.fatcatapps.com/api/feedback.php";

		$body = array(
			'product' => 'contestcat',
			'msg' => $msg,
		);

		$args = array(
			'body' => json_encode( $body ),
		);

		$return = wp_remote_post( $url, $args );

		wp_send_json_success( $msg );

	}
	wp_send_json_error( $msg );

}
add_action( 'wp_ajax_fca_cc_uninstall', 'fca_cc_uninstall_ajax' );

//ADD AN EVENT FOR DRIP SUBSCRIBER FOR ACTIVATION/DEACTIVATION OF PLUGIN
function fca_cc_api_action( $action = '' ) {
	$tracking = get_option( 'fca_cc_activation_status' );
	if ( $tracking !== false ) {
		$user = wp_get_current_user();
		$url =  "https://api.fatcatapps.com/api/activity.php";

		$body = array(
			'user' => $user->user_email,
			'action' => $action,
		);

		$args = array(
			'body' => json_encode( $body ),
		);

		$return = wp_remote_post( $url, $args );

		return true;
	}

	return false;

}
