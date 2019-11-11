<?php
/**
 * WordPress shortcode: [furik_progress]: shows a status bar for the campaign
 */
function furik_shortcode_progress($atts) {
	$a = shortcode_atts( array(
		'amount' => 0
    ), $atts );

    $result = furik_progress(NULL, $a['amount']);

	$r .= "<p class=\"furik-collected\">" . number_format($result['collected'], 0, ',', ' ') . " Ft</p>";

	if ($result['goal'] > 0) {
		$r .= $result['progress_bar'];
		$r .= "<p class=\"furik-percentage\">" . $result['percentage'] . "% ".__('completed', 'furik')."</p>";
		$r .= "<p class=\"furik-goal\">".__('Goal', 'furik') . ": " . number_format($result['goal'], 0, ',', ' ') . " Ft</p>";
	}

    return $r;
}

add_shortcode('furik_progress', 'furik_shortcode_progress');