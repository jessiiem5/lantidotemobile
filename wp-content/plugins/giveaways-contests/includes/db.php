<?php


//RETURN TABLE NAME W/ PREFIX
function fca_cc_table() {
	global $wpdb;
	return $wpdb->prefix . "fca_cc_activity_tbl";
}

//CREATED ON PLUGIN ACTIVATION
function fca_cc_create_table() {
	global $wpdb;
	$table_name = fca_cc_table();

	if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name ) {

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			`id` BIGINT(20) NOT NULL AUTO_INCREMENT,
			`contest` BIGINT(20),
			`name` VARCHAR(100),
			`email` VARCHAR(100),
			`time` DATETIME,
			`ip` VARCHAR(45),
			`status` VARCHAR(20),
			`tel` VARCHAR(200),
			`code` VARCHAR(255),
			PRIMARY KEY  (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		return true;

	}

	return false;
}

/* Expected entry:
	'name' => $name,
	'email' => $email,
	'time' => current_time( 'timestamp' ),
	'ip' => $_SERVER['REMOTE_ADDR'],
	'status' => 'eligible',
*/
function fca_cc_add_entry( $contest_id, $entry ) {

	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) && $contest_id > 0  ) {

		global $wpdb;
		$table_name = fca_cc_table();

		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== NULL ) {

			$rows = $wpdb->insert( $table_name,
				array(
					'contest' => $contest_id,
					'name' => $entry['name'],
					'email' => $entry['email'],
					'time' => $entry['time'],
					'ip' => $entry['ip'],
					'status' => $entry['status'],
					'tel' => $entry['tel'],
					'code' => $entry['code'],
				)
			);

			if ( $rows == 1 ) {
				return true;
			}

		}
	}
	return false;
}

function fca_cc_entry_exists( $contest_id, $email ) {

	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) && !empty ( $email ) ) {
		global $wpdb;
		$table_name = fca_cc_table();

		return $wpdb->get_var("SELECT `status` FROM `$table_name` WHERE `email` = '$email' AND `contest` = '$contest_id'") !== null;

	}
	return false;
}

function fca_cc_get_entries( $contest_id, $offset = 0 ) {
	$limit = 30;
	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) && is_int ( $limit ) ) {
		global $wpdb;

		$table_name = fca_cc_table();

		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== null ) {

			$sql = ("SELECT * FROM `$table_name` WHERE `contest` = $contest_id ORDER BY `time` ASC LIMIT $limit OFFSET $offset");
			return $wpdb->get_results( $sql, ARRAY_A );

		}
	}
	return false;
}

function fca_cc_get_winners( $contest_id ) {

	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) ) {
		global $wpdb;

		$table_name = fca_cc_table();

		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== null ) {

			$sql = ("SELECT * FROM `$table_name` WHERE `contest` = $contest_id AND `status` = 'won'");
			return $wpdb->get_results( $sql, ARRAY_A );

		}
	}
	return false;
}

function fca_cc_update_eligibility( $entry_uid, $status = false ) {

	$entry_uid = intval ( $entry_uid );
	$status = esc_sql ( $status );

	if ( is_int ( $entry_uid ) && in_array( $status, array('rejected', 'eligible') ) ) {

		global $wpdb;
		$table_name = fca_cc_table();

		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== null ) {
			return $wpdb->update( $table_name, array( 'status' => $status ), array( 'id' => $entry_uid ) ) == 1;
		}
	}
	return false;
}



function fca_cc_get_new_winner( $contest_id ) {

	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) ) {
		global $wpdb;

		$table_name = fca_cc_table();

		if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== null ) {

			$sql = ("SELECT * FROM `$table_name` WHERE `contest` = '$contest_id' AND `status` = 'eligible'");
			$results = $wpdb->get_results( $sql, ARRAY_A );

			$winner = $results[array_rand ( $results )];

			if ( empty( $winner['id'] ) ) {
				return false;
			}

			if ( $winner !== null && isSet ( $winner['id'] ) ) {
				$winner['status'] = 'won';
				if ( $wpdb->update( $table_name, array( 'status' => 'won' ), array( 'id' => $winner['id'] ) ) == 1 ) {
					return $winner;
				}
			}

		}
	}
	return false;
}

function fca_cc_count_entries( $contest_id ) {

	$contest_id = intval ( $contest_id );

	if ( is_int ( $contest_id ) ) {

		global $wpdb;

		$table_name = fca_cc_table();

		return $wpdb->get_var("SELECT COUNT(*) FROM `$table_name` WHERE `contest` = '$contest_id'");
	}
	return false;
}

function fca_cc_delete_post( $post_id ){
	if ( get_post_type( $post_id ) === 'contest' ) {
		fca_cc_delete_quiz_from_db( (int) $post_id );
	}
}
add_action( 'before_delete_post', 'fca_cc_delete_post' );

function fca_cc_delete_quiz_from_db( $contest_id ) {
	if ( is_int ( $contest_id ) ) {
		global $wpdb;
		$table_name = fca_cc_table();
		$wpdb->delete( $table_name, array( 'contest' => $contest_id ) );

	}
}
