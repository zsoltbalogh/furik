<?php
if (!class_exists('WP_List_Table') ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Campaigns_List extends WP_List_Table {

	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Campaign', 'sp' ),
			'plural'   => __( 'Campaigns', 'sp' ),
			'ajax'     => false
		] );
	}

	public function column_default( $item, $column_name ) {
		return $item[$column_name];
	}

	public static function get_campaigns( $per_page = 5, $page_number = 1 ) {
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}furik_campaigns";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}

	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}furik_campaigns";

		return $wpdb->get_var( $sql );
	}

	public function no_items() {
		_e( 'No campaigns are avaliable.', 'sp' );
	}

	function get_columns() {
		$columns = [
			'name' => __( 'Name', 'sp' ),
		];

		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array( 'name', false ),
		);

		return $sortable_columns;
	}

	public function prepare_items() {
		$per_page     = $this->get_items_per_page('campaigns_per_page', 20);
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
		    'total_items' => $total_items,
		    'per_page'    => $per_page
		] );

		$this->items = self::get_campaigns($per_page, $current_page);
	}
}

class Campaigns_List_Plugin {

	static $instance;
	public $campaigns_obj;

	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}

	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {
		$hook = add_menu_page(
			'Furik Donations Page',
			'Campaigns',
			'manage_options',
			'campaigns',
			[ $this, 'campaigns_list_page' ],
			'dashicons-portfolio'
		);
		add_action( "load-$hook", [ $this, 'screen_option' ] );
	}

	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label'   => 'Campaigns',
			'default' => 20,
			'option'  => 'campaigns_per_page'
		];

		add_screen_option( $option, $args );

		$this->campaigns_obj = new Campaigns_List();
	}

	public function campaigns_list_page() {
		?>
		<div class="wrap">
			<h2>Campaigns</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->campaigns_obj->prepare_items();
								$this->campaigns_obj->display(); ?>
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
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

add_action( 'plugins_loaded', function () {
	Campaigns_List_Plugin::get_instance();
} );
