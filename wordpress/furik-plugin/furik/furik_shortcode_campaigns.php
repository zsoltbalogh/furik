<?php
/**
 * WordPress shortcode: [furik_campaigns]: lists all child campaigns
 */
function furik_shortcode_campaigns($atts) {
	$a = shortcode_atts( array(
	   'show' => 'image,title,excerpt,progress_bar'
	), $atts );

	$post = get_post();
	$campaigns = get_posts(['post_parent' => $post->ID, 'post_type' => 'campaign', 'numberposts' => 100]);
	$show = explode(",", $a['show']);

	foreach ($campaigns as $campaign) {
		$r .= "<div class=\"sub-campaign-listing\">";
		$progress = furik_progress($campaign->ID);

		foreach ($show as $field) {
			switch ($field) {
				case "image":
					$meta = get_post_custom($campaign->ID);
					$r .= "<a href=\"".$campaign->guid."\"><img src=\"" . $meta['IMAGE'][0] . "\" class=\"sub-campaign-image\" alt=\"" . esc_html($campaign->post_title) . "\"/></a>";
					break;
				case "title":
					$r .= "<div class=\"sub-campaign-title\"><a href=\"".$campaign->guid."\">".esc_html($campaign->post_title)."</a></div>";
					break;
				case "excerpt":
					$r .= "<div class=\"sub-campaign-title\">".esc_html($campaign->post_excerpt)."</div>";
					break;
				case "progress_bar":
					$r .= print_r($progress, true);
					$r .= "<div class=\"sub-campaign-progress-bar\">" . $progress['progress_bar'] . "</div>";
					break;
				default:
					$r .= __('Unknown field: ', 'furik') . $field;
			}
		}
		$r .= "</div>";
	}

	return $r;
}

add_shortcode('furik_campaigns', 'furik_shortcode_campaigns');