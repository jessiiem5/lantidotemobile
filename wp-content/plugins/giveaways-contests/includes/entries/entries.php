<?php

////////////////////////////
// ENTRIES PAGE
////////////////////////////

//REGISTER ENTRIES PAGE
function fca_cc_entries_page() {
	add_submenu_page(
		null,
		__('Entries', 'contest-cat'),
		__('Entries', 'contest-cat'),
		'manage_options',
		'contest-cat-entries',
		'fca_cc_render_entries_page'
	);
}
add_action( 'admin_menu', 'fca_cc_entries_page' );

function fca_cc_entries_page_enqueue( $id ) {

	wp_enqueue_script('fca_cc_entries_js', FCA_CC_PLUGINS_URL . '/includes/entries/entries.js', array(), FCA_CC_PLUGIN_VER, true );
	wp_enqueue_style( 'fca_cc_entries_stylesheet', FCA_CC_PLUGINS_URL . '/includes/entries/entries.min.css', array(), FCA_CC_PLUGIN_VER );

	$admin_data = array (

		'post_id' => $id,
		'ajaxurl' => admin_url ( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'fca_cc_entries_nonce' ),
	);

	wp_localize_script( 'fca_cc_entries_js', 'adminData', $admin_data );

}

function fca_cc_render_entries_page() {

	$id = intval( $_GET['id'] );
	$show = empty( $_GET['show'] ) ? '' : sanitize_text_field( $_GET['show'] );
	$title = get_the_title( $id );
	$title = empty( $title ) ? __('(no title)', 'contest-cat') : $title;
	$offset = empty ( $_GET['offset'] ) ? 0 : intval( $_GET['offset'] );

	fca_cc_entries_page_enqueue( $id );

	$winner_url = admin_url( "admin.php?page=contest-cat-entries") . "&id=$id&show=winners";
	$entries_url = admin_url( "admin.php?page=contest-cat-entries") . "&id=$id";

	$html = '<div id="fca-cc-entries-page" >';

	if ( $show === 'winners' ) {

		$winners = fca_cc_get_winners( $id );
		$winners = empty ( $winners ) ? array() : $winners;

		$html .= "<h1><a style='text-decoration: none;' href='" . admin_url("post.php?post=$id&action=edit") . "'>$title</a>";
		$html .= "<a href='" . admin_url('edit.php?post_type=contest') . "' id='fca-cc-back-btn' class='button button-secondary'>" . _x( 'Back', 'go back in navigation', 'contest-cat' ) . "</a></h1>";

		$html .= '<div id="fca-cc-nav">';

			$html .= '<h1 class="nav-tab-wrapper">';
				$html .= '<a href="' . $winner_url . '" id="winners-nav" class="nav-tab nav-tab-active">' . __('Winners', 'quiz-cat') . '</a>';
				$html .= '<a href="' . $entries_url . '" id="entries-nav" class="nav-tab">' . __('Entries', 'quiz-cat') . '</a>';
			$html .= '</h1>';

		$html .= '</div>';

		$html .= '<div id="winners-tab" class="entries-page-tab">';
			$html .=  fca_cc_winners_table( $winners );
			$html .= '<button type="button" class="button button-primary" id="fca-cc-pick-winner-button" data-alttext="' . __( 'Pick Another Winner', 'contest-cat' ) . '">' . __( 'Pick a Random Winner', 'contest-cat' ) . '</button>';

		$html .= '</div>';


	} else {

		$entries = fca_cc_get_entries( $id, $offset );
		$entries = empty ( $entries ) ? array() : $entries;

		$html .= "<h1><a style='text-decoration: none;' href='" . admin_url("post.php?post=$id&action=edit") . "'>$title</a>";
		$html .= "<a href='" . admin_url('edit.php?post_type=contest') . "' id='fca-cc-back-btn' class='button button-secondary'>" . _x( 'Back', 'go back in navigation', 'contest-cat' ) . "</a></h1>";

		$html .= '<div id="fca-cc-nav">';

			$html .= '<h1 class="nav-tab-wrapper">';
				$html .= '<a href="' . $winner_url . '" id="winners-nav" class="nav-tab">' . __('Winners', 'quiz-cat') . '</a>';
				$html .= '<a href="' . $entries_url . '" id="entries-nav" class="nav-tab nav-tab-active">' . __('Entries', 'quiz-cat') . '</a>';
			$html .= '</h1>';

		$html .= '</div>';

		$html .= '<div id="entries-tab" class="entries-page-tab">';
			$html .=  fca_cc_entries_table( $entries );
		$html .= '</div>';

		$html .= '<div id="fca-cc-nav-bottom">';
			if ( $offset !== 0 ) {
				$back_offset = $offset - 30 < 0 ? 0 : $offset - 30;
				$html .= "<a href='$entries_url" . "&offset=$back_offset" .  "' class='button button-secondary'>" . _x( 'Previous', 'go home in navigation', 'contest-cat' ) . "</a>";
			}
			if ( count( $entries ) === 30 ) {
				$html .= "<a style='float: right; margin-right: 20px;' href='$entries_url" . '&offset=' . ($offset + 30) . "' class='button button-secondary'>" . _x( 'Next', 'go to next page in navigation', 'contest-cat' ) . "</a>";
			}

		$html .= '</div>';

	}

	$html .= '</div>';

	echo $html;

}

function fca_cc_entries_table( $entries ) {
	$html = "<table class='fca-cc-entries-table fca-cc-all-entries widefat striped'";
		$html .= '<tr><th id="fca-cc-entries-number">#</th>' .
			'<th id="fca-cc-entries-name">' . __( 'Name' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-email">' . __( 'Email' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-tel">' . __( 'Tel' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-code">' . __( 'Code Postal' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-status">' . __( 'Status' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-time">' . __( 'Inscrit le' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-ip">' . __( 'IP' , 'conntest-cat') . '</th>' .
		'<th id="fca-cc-entries-button">&nbsp;</th></tr>';

		forEach ( $entries as $key => $entry ) {
			$maybe_winner = $entry['status'] === 'won' ? 'fca-cc-winner' : '';

			$button = $entry['status'] !== 'rejected' ?
					'<button type="button" class="button button-secondary fca-cc-toggle-eligible" data-entry-id="' . $entry['id'] . '" data-alttext="' . __('Allow', 'contest-cat') . '" >' . _x('Reject', 'remove from eligibility', 'contest-cat') . '</button>' :
					'<button type="button" class="button button-secondary fca-cc-toggle-eligible" data-entry-id="' . $entry['id'] . '" data-alttext="' . __('Reject', 'contest-cat') . '" >' . _x('Allow', 'remove from eligibility', 'contest-cat') . '</button>';

			$html .= "<tr class='$maybe_winner'>
			<td>" .
				$entry['id']	. '</td>

				<td>' .
				$entry['name']	. '</td>

				<td><input class="fca-cc-email-input" type="text" readonly="readonly" onclick="this.select()" value="' .
				$entry['email'] . '"/></td>

				<td>' .
				$entry['tel']	. '</td>

				<td>' .
				$entry['code']	. '</td>

				<td class="fca-cc-status">' .
				$entry['status'] . '</td>

				<td>' .
				$entry['time'] . '</td>

				<td>' .
				$entry['ip'] . '</td>

				<td class="fca-cc-status-toggle-td">' .
				$button . '</td>
				</tr>';
		}

	$html .= '</table>';

	return $html;

}

function fca_cc_winners_table( $winners ) {

	$html = "<table class='fca-cc-entries-table fca-cc-winning-entries widefat striped'";
		$html .= '<tr><th id="fca-cc-entries-number">#</th>' .
			'<th id="fca-cc-entries-name">' . __( 'Name' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-email">' . __( 'Email' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-tel">' . __( 'Tel' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-code">' . __( 'Code Postal' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-time">' . __( 'Time' , 'conntest-cat') . '</th>' .
			'<th id="fca-cc-entries-ip">' . __( 'IP' , 'conntest-cat') . '</th>' .
		'<th id="fca-cc-entries-button">&nbsp;</th></tr>';

		$i = 0;

		forEach ( $winners as $key => $winner ) {
			$i++;
			$html .= "<tr>

				<td class='fca-cc-winner-count'>$i</td>

				<td>" .
				$winner['name']	. '</td>

				<td><input class="fca-cc-email-input" type="text" readonly="readonly" onclick="this.select()" value="' .
				$winner['email'] . '"/></td>

				<td>' .
				$winner['tel'] . '</td>

				<td>' .
				$winner['time'] . '</td>

				<td>' .
				$winner['ip'] . '</td>

				<td class="fca-cc-status-toggle-td">' .
				'<button type="button" class="button button-secondary fca-cc-reject-winner" data-winner-id="' . $winner['id'] . '" >' . _x('Reject', 'remove from eligibility', 'contest-cat') . '</button>
				</td>

				</tr>';
		}

	$html .= '</table>';

	return $html;

}
