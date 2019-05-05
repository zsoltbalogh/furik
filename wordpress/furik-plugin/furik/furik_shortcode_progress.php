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
	$r .= "Amount: $result";

    return $r;
}

add_shortcode('furik_progress', 'furik_shortcode_progress');