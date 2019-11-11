<?php
/**
 * WordPress shortcode: [furik_campaigns]: lists all child campaigns
 */
function furik_shortcode_campaigns($atts) {
	$a = shortcode_atts( array(
	   'show' => 'image,title,excerpt,progress_bar,completed,goal'
	), $atts );

	$post = get_post();
	$campaigns = get_posts(['post_parent' => $post->ID, 'post_type' => 'campaign', 'numberposts' => 100]);
	$show = explode(",", $a['show']);

	foreach ($campaigns as $campaign) {
		$r .= "<div class=\"furik-sub-campaign-listing\">";
		$progress = furik_progress($campaign->ID);

		foreach ($show as $field) {
			switch ($field) {
				case "image":
					$meta = get_post_custom($campaign->ID);
					$r .= "<a href=\"".$campaign->guid."\"><img src=\"" . $meta['IMAGE'][0] . "\" class=\"furik-sub-campaign-image\" alt=\"" . esc_html($campaign->post_title) . "\"/></a>";
					break;
				case "title":
					$r .= "<div class=\"furik-sub-campaign-title\"><a href=\"".$campaign->guid."\">".esc_html($campaign->post_title)."</a></div>";
					break;
				case "excerpt":
					$r .= "<div class=\"furik-sub-campaign-title\">".esc_html($campaign->post_excerpt)."</div>";
					break;
				case "progress_bar":
					$r .= "<div class=\"furik-sub-campaign-progress-bar\">" . $progress['progress_bar'] . "</div>";
					break;
				case "completed":
					$r .= "<div class=\"furik-sub-campaign-percentage\">" . $progress['percentage'] . "% " . __('completed', 'furik') . "</div>";
					break;
				case "goal":
					$r .= "<p class=\"furik-sub-campaign-goal\">".__('Goal', 'furik') . ": " . number_format($progress['goal'], 0, ',', ' ') . " Ft</p>";
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