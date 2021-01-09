<?php
if (!class_exists('WP_List_Table') ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Recurring_List extends WP_List_Table {

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
			return __('General donation', 'furik');
		}
		if (!$item['parent_campaign_name']) {
			return $item['campaign_name'];
		}
		return $item['campaign_name'] . " (" . $item['parent_campaign_name'] .")";
	}

	public function column_future_count($item) {
		if ($item['future_count']) {
			return $item['future_count'] . ' ' . __('future donations', 'furik');
		}
		elseif ($item['transaction_type'] == FURIK_TRANSACTION_TYPE_RECURRING_TRANSFER_REG) {
			$days = (time() - strtotime($item['last_transaction']))/60/60/24;

			return sprintf(__('Last payment was recorded %d day(s) ago.', 'furik'), $days);
		}
		else {
			return __('Expired or cancelled.', 'furik');
		}
	}

	public function column_transaction_id($item) {
		return "<a href=\"admin.php?page=wp_list_table_class&filter_by_parent=" . $item['id'] . "\">". $item['transaction_id'] . "</a>";
	}

	public function column_transaction_status($item) {
		switch ($item['transaction_status']) {
			case "":
				return __('Pending', 'furik');
			case FURIK_STATUS_SUCCESSFUL:
				return __('Successful, waiting for confirmation', 'furik');
			case FURIK_STATUS_UNSUCCESSFUL:
				return __('Unsuccessful card payment', 'furik');
			case FURIK_STATUS_TRANSFER_ADDED:
			case FURIK_STATUS_CASH_ADDED:
				$actions = array(
					'approve' => sprintf('<br /><a href="?page=%s&action=%s&campaign=%s&orderby=%s&order=%s&paged=%s">' . __('Approve', 'furik') . '</a>',
						$_REQUEST['page'],
						'approve',
						$item['id'],
						@$_GET['orderby'],
						@$_GET['order'],
						@$_GET['paged']),
				);
				return sprintf('%1$s %2$s', __('Waiting for confirmation', 'furik'), $actions['approve'] );
			case FURIK_STATUS_IPN_SUCCESSFUL:
				return __('Successful and confirmed', 'furik');
			default:
				return __('Unknown', 'furik');
		}
	}

	public function column_transaction_type($item) {
		if ($item['transaction_type'] == FURIK_TRANSACTION_TYPE_RECURRING_REG) {
			return __('SimplePay Card', 'furik');
		}
		elseif ($item['transaction_type'] == FURIK_TRANSACTION_TYPE_RECURRING_TRANSFER_REG) {
			return __('Bank transfer', 'furik');
		}
		else {
			return __('Unknown', 'furik');
		}
	}

	public static function get_donations($per_page = 5, $page_number = 1) {
		global $wpdb;

		$sql = "SELECT
				tr.*,
				campaigns.post_title AS campaign_name,
				parentcampaigns.post_title AS parent_campaign_name,
				(SELECT sum(amount) FROM {$wpdb->prefix}furik_transactions WHERE (parent=tr.id OR id=tr.id) AND transaction_status=".FURIK_STATUS_IPN_SUCCESSFUL.") as full_amount,
				(SELECT count(*) FROM {$wpdb->prefix}furik_transactions as ctr WHERE ctr.parent=tr.id AND ctr.transaction_status=".FURIK_STATUS_FUTURE.") as future_count,
				(SELECT transaction_time FROM {$wpdb->prefix}furik_transactions as ttr WHERE (ttr.parent=tr.id OR ttr.id=tr.id) ORDER BY id DESC LIMIT 1) as last_transaction
			FROM
				{$wpdb->prefix}furik_transactions as tr
				LEFT OUTER JOIN {$wpdb->prefix}posts campaigns ON (tr.campaign=campaigns.ID)
				LEFT OUTER JOIN {$wpdb->prefix}posts parentcampaigns ON (campaigns.post_parent=parentcampaigns.ID)
			WHERE transaction_type in (". FURIK_TRANSACTION_TYPE_RECURRING_REG . ", ". FURIK_TRANSACTION_TYPE_RECURRING_TRANSFER_REG .")";
		if (!empty($_REQUEST['orderby'])) {
			$sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
			$sql .= ! empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
		} else {
			$sql .= ' ORDER BY TIME DESC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

		$result = $wpdb->get_results($sql, 'ARRAY_A');
		return $result;
	}

	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}furik_transactions WHERE transaction_type = ". FURIK_TRANSACTION_TYPE_RECURRING_REG;

		return $wpdb->get_var($sql);
	}

	public function no_items() {
		_e('No recurring donations are avaliable.', 'furik');
	}

	function get_columns() {
		$columns = [
			'transaction_id' => __('ID', 'furik'),
			'time' => __('Registration time', 'furik'),
			'name' => __('Name', 'furik'),
			'email' => __('E-mail', 'furik'),
			'transaction_type' => __('Type', 'furik')
		];
		if (furik_extra_field_enabled('phone_number')) {
			$columns += ['phone_number' => __('Phone Number', 'furik')];
		}
		$columns += [
			'amount' => __('Amount', 'furik'),
			'full_amount' => __('Full amount', 'furik'),
			'campaign_name' => __('Campaign', 'furik'),
			'message' => __('Message', 'furik'),
			'anon' => __('Anonymity', 'furik'),
			'newsletter_status' => __('Newsletter Status', 'furik'),
			'transaction_status' => __('Registration status', 'furik'),
			'future_count' => __('Status', 'furik')
		];

		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
			'transaction_id' => array('ID', 'furik'),
			'time' => array('time', true),
			'name' => array('name', false),
			'email' => array('email', false),
			'amount' => array('amount', false),
			'full_amount' => array('full_amount', false),
			'campaign_name' => array('campaign_name', false),
			'anon' => array('anon', true),
			'newsletter_status' => array('newsletter_status', true),
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

class Recurring_List_Plugin {

	static $instance;
	public $donations_obj;

	public function __construct() {
		add_filter('set-screen-option', [ __CLASS__, 'set_screen' ], 11, 3);
		add_action('admin_menu', [$this, 'plugin_menu']);
	}

	public static function set_screen($status, $option, $value) {
		return $value;
	}

	public function plugin_menu() {
		$hook = add_menu_page(
			__('Recurring donations', 'furik'),
			__('Recurring donations', 'furik'),
			'manage_options',
			'recurring_donations',
			[$this, 'donations_list_page'],
			'dashicons-chart-line'
		);
		add_action("load-$hook", [$this, 'screen_option']);
	}

	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label' => 'Recurring donations',
			'default' => 20,
			'option' => 'donations_per_page'
		];

		add_screen_option($option, $args);

		$this->donations_obj = new Recurring_List();
	}

	public function donations_list_page() {
		global $wpdb;
		?>
		<style>
			td.message.column-message {
				white-space: nowrap;
				overflow:hidden;
				text-overflow:ellipsis;
			}
			td.message.column-message:hover {
				white-space: initial;
				overflow: initial;
			}
		</style>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e('Recurring donations', 'furik') ?></h1>
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
	Recurring_List_Plugin::get_instance();
} );
