<?php
if (!class_exists('WP_List_Table') ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Donations_List extends WP_List_Table {

	public function __construct() {
		parent::__construct( [
			'singular' => __('Donation', 'furik'),
			'plural'   => __('Donations', 'furik'),
			'ajax'     => false
		] );
	}

	public function column_default($item, $column_name) {
		return esc_html($item[$column_name]);
	}

	public function column_campaign_name($item) {
		if (!$item['campaign_name']) {
			return __('General donation');
		}
		if (!$item['parent_campaign_name']) {
			return $item['campaign_name'];
		}
		return $item['campaign_name'] . " (" . $item['parent_campaign_name'] .")";
	}

	public function column_transaction_status($item) {
		switch ($item['transaction_status']) {
			case "":
				return __('Pending card payment', 'furik');
			case 1:
				return __('Successful, waiting for confirmation', 'furik');
			case 2:
				return __('Unsuccessful card payment', 'furik');
			case 10:
				return __('Successful and confirmed', 'furik');
			default:
				return __('Unknown', 'furik');
		}

	}

	public static function get_donations($per_page = 5, $page_number = 1) {
		global $wpdb;

		$sql = "SELECT
				{$wpdb->prefix}furik_transactions.*,
				campaigns.post_title AS campaign_name,
				parentcampaigns.post_title AS parent_campaign_name
			FROM
				{$wpdb->prefix}furik_transactions
				LEFT OUTER JOIN {$wpdb->prefix}posts campaigns ON ({$wpdb->prefix}furik_transactions.campaign=campaigns.ID)
				LEFT OUTER JOIN {$wpdb->prefix}posts parentcampaigns ON (campaigns.post_parent=parentcampaigns.ID)";
		if (!empty($_REQUEST['orderby'])) {
			$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
			$sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
		$result = $wpdb->get_results($sql, 'ARRAY_A');
		return $result;
	}

	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}furik_transactions";

		return $wpdb->get_var($sql);
	}

	public function no_items() {
		_e('No donations avaliable.', 'furik');
	}

	function get_columns() {
		$columns = [
			'name' => __('Name', 'furik'),
			'email' => __('E-mail', 'furik'),
		    'amount' => __('Amount', 'furik'),
		    'campaign_name' => __('Campaign', 'furik'),
		    'time' => __('Time', 'sp'),
		    'transaction_status' => __('Status', 'furik')
		];

		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array('name', false),
			'email' => array('email', false),
			'amount' => array('amount', false),
			'campaign_name' => array('campaign_name', false),
			'time' => array('time', true),
			'transaction_status' => array('transaction_status', false)
		);

		return $sortable_columns;
	}

	public function prepare_items() {
		$per_page = $this->get_items_per_page('donations_per_page', 20);
		$current_page = $this->get_pagenum();
		$total_items = self::record_count();

		$this->set_pagination_args( [
		    'total_items' => $total_items,
		    'per_page' => $per_page
		] );

		$this->items = self::get_donations($per_page, $current_page);
	}
}

class Donations_List_Plugin {

	static $instance;
	public $donations_obj;

	public function __construct() {
		add_filter('set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3);
		add_action('admin_menu', [$this, 'plugin_menu']);
	}

	public static function set_screen($status, $option, $value) {
		return $value;
	}

	public function plugin_menu() {
		$hook = add_menu_page(
			__('Furik Donations', 'furik'),
			__('Donations', 'furik'),
			'manage_options',
			'wp_list_table_class',
			[$this, 'donations_list_page'],
			'dashicons-chart-line'
		);
		add_action("load-$hook", [$this, 'screen_option']);
	}

	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label' => 'Donations',
			'default' => 20,
			'option' => 'donations_per_page'
		];

		add_screen_option($option, $args);

		$this->donations_obj = new Donations_List();
	}

	public function donations_list_page() {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Donations', 'furik') ?></h1>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->donations_obj->prepare_items();
								$this->donations_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	public static function get_instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

add_action( 'plugins_loaded', function () {
	Donations_List_Plugin::get_instance();
} );
