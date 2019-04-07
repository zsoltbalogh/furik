<?php
/**
 * WordPress shortcode: [furik_campaigns]: lists all child campaigns
 */
function furik_shortcode_campaigns($atts) {
    $post = get_post();
    $campaigns = get_posts(['post_parent' => $post->ID, 'post_type' => 'campaign']);

    foreach ($campaigns as $campaign) {
		$r .= "<a href=\"".$campaign->guid."\">".$campaign->post_title."</a><br />";
    }

    return $r;
}

add_shortcode('furik_campaigns', 'furik_shortcode_campaigns');