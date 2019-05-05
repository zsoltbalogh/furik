<?php
/**
 * WordPress shortcode: [furik_progress]: shows a status bar for the campaign
 */
function furik_shortcode_progress($atts) {
	$a = shortcode_atts( array(
		'amount' => 0
    ), $atts );

	global $wpdb;

    $post = get_post();
    $campaigns = get_posts(['post_parent' => $post->ID, 'post_type' => 'campaign']);
    $ids = array();
    $ids[] = $post->ID;

    foreach ($campaigns as $campaign) {
		$ids[] = $campaign->ID;
    }
    $id_list = implode($ids, ",");

    $sql = "SELECT
			sum(amount)
		FROM
			{$wpdb->prefix}furik_transactions AS transaction
			LEFT OUTER JOIN {$wpdb->prefix}posts campaigns ON (transaction.campaign=campaigns.ID)
		WHERE campaigns.ID in ($id_list)
			AND transaction.transaction_status in (".FURIK_STATUS_DISPLAYABLE.")
		ORDER BY time DESC";

	$result = $wpdb->get_var($sql);

	$r .= "<p class=\"furik-collected\">".number_format($result, 0, ',', ' ') . " Ft</p>";


	if ($a['amount'] > 0) {
		$percentage = 1.0 * $result/$a['amount']*100;
		$r .= "<style>
				.furik-progress-bar {
					background-color: #aaaaaaa;
					height: 30px;
					padding: 5px;
					width: 500px;
					margin: 5px 0;
					border-radius: 5px;
					box-shadow: 0 1px 5px #444 inset, 0 1px 0 #888;
					}
				.furik-progress-bar span {
					display: inline-block;
					float: left;
					height: 100%;
					border-radius: 3px;
					box-shadow: 0 1px 0 rgba(255, 255, 255, .5) inset;
					transition: width .4s ease-in-out;
					overflow: hidden;
					background-color: #fecf23;
					}
				</style>";
		$r .= "<p class=\"furik-goal\">".__('Goal', 'furik') . ": " . number_format($a['amount'], 0, ',', ' ') . " Ft</p>";
		$r .= "<p class=\"furik-percentage\">" . $percentage . "% ".__('completed', 'furik')."</p>";
		$r .= "<div class=\"furik-progress-bar\"><span style=\"width: " . ($percentage > 100 ? 100 : $percentage) . "%\"></span></div>";
	}
	else {
		$r .= "<p class=\"furik-collected\">$result Ft</p>";
	}

    return $r;
}

add_shortcode('furik_progress', 'furik_shortcode_progress');