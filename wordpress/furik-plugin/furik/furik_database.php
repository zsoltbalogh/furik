<?php
define("FURIK_STATUS_UNKNOWN", 0);
define("FURIK_STATUS_SUCCESSFUL", 1);
define("FURIK_STATUS_UNSUCCESSFUL", 2);
define("FURIK_STATUS_CANCELLED", 3);
define("FURIK_STATUS_TRANSFER_ADDED", 4);
define("FURIK_STATUS_CASH_ADDED", 5);
define("FURIK_STATUS_FUTURE", 6);
define("FURIK_STATUS_IPN_SUCCESSFUL", 10);
define("FURIK_STATUS_RECURRING_FAILED", 11);
define("FURIK_STATUS_DISPLAYABLE", "1, 10");

define("FURIK_TRANSACTION_TYPE_SIMPLEPAY", 0);
define("FURIK_TRANSACTION_TYPE_TRANSFER", 1);
define("FURIK_TRANSACTION_TYPE_CASH", 2);
define("FURIK_TRANSACTION_TYPE_RECURRING_REG", 3);
define("FURIK_TRANSACTION_TYPE_RECURRING_AUTO", 4);
define("FURIK_TRANSACTION_TYPE_RECURRING_TRANSFER_REG", 5);
define("FURIK_TRANSACTION_TYPE_RECURRING_TRANSFER_AUTO", 6);

function furik_get_transaction($order_ref) {
	global $wpdb;

	return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}furik_transactions WHERE transaction_id=%s", $order_ref));
}

function furik_get_post_id_from_order_ref($order_ref) {
	global $wpdb;

	return $wpdb->get_var($wpdb->prepare("SELECT campaign FROM {$wpdb->prefix}furik_transactions WHERE transaction_id=%s", $order_ref));
}

function furik_add_custom_page( $title, $content ) {
	$my_post = array(
		'post_title'   => wp_strip_all_tags( $title ),
		'post_content' => $content,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	wp_insert_post( $my_post );
}

function furik_install() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql_transactions = "CREATE TABLE {$wpdb->prefix}furik_transactions (
		id int NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_time datetime,
		transaction_id varchar(100) NOT NULL,
		transaction_type int DEFAULT 0,
		production_system int,
		name varchar(255),
		first_name varchar(255),
		last_name varchar(255),
		anon int,
		email varchar(255),
		phone_number varchar(255),
		amount int,
		campaign int,
		message longtext,
		transaction_status int,
		vendor_ref varchar(255),
		recurring int,
		parent int,
		token varchar(255),
		token_validity datetime,
		newsletter_status int,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql_transaction_log = "CREATE TABLE {$wpdb->prefix}furik_transaction_log (
		id int NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		transaction_id varchar(100) NOT NULL,
		message text,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql_transactions);
	dbDelta($sql_transaction_log);

	add_option('furik_db_version', 1);

	$site_title = get_bloginfo();
	$site_url = get_bloginfo( 'url' );

	furik_add_custom_page( 'Adományozás', '[furik_donate_form amount=5000 enable_monthly=true]' );
	furik_add_custom_page(
		'Adattovábbítási nyilatkozat',
		'Tudomásul veszem, hogy a ' + $site_title + ' (CHANGEME address) adatkezelő által a ' + $site_url + ' felhasználói adatbázisában tárolt alábbi személyes adataim átadásra kerülnek az OTP Mobil Kft., mint adatfeldolgozó részére. Az adatkezelő által továbbított adatok köre az alábbi: név, e-mail cím, telefonszám, számlázási adatok.<br>
		Az adatfeldolgozó által végzett adatfeldolgozási tevékenység jellege és célja a SimplePay Adatkezelési tájékoztatóban, az alábbi linken tekinthető meg: https://simplepay.hu/vasarlo-aff'
	);
	furik_add_custom_page(
		'Átutalásos támogatás',
		'<p>Köszönjük, hogy jelezted, hogy támogatod az Alapítványunkat! Az adományodat kérjük a ' + $site_title + ' nevére és a CHANGEME bankszámlaszámára utald. A közlemény mezőbe a következő kódot írd: <strong>[furik_order_ref]</strong>. Köszönjük!</p>
		<p>Fontos: a felületen akkor jelenik meg az adományod, ha elutaltad, és mi jóváhagytuk azt!</p>'
	);
	furik_add_custom_page(
		'Kártya regisztrációs nyilatkozat',
		'Az ismétlődő bankkártyás fizetés (továbbiakban: „Ismétlődő fizetés”) egy, a SimplePay által biztosított bankkártya elfogadáshoz tartozó funkció, mely azt jelenti, hogy a Vásárló által a regisztrációs tranzakció során megadott bankkártyaadatokkal a jövőben újabb fizetéseket lehet kezdeményezni a bankkártyaadatok újbóli megadása nélkül. Az ismétlődő fizetés ún. „eseti hozzájárulásos” típusa minden tranzakció esetében a Vevő eseti jóváhagyásával történik, tehát, Ön valamennyi jövőbeni fizetésnél jóvá kell, hogy hagyja a tranzakciót. A sikeres fizetés tényéről Ön minden esetben a hagyományos bankkártyás fizetéssel megegyező csatornákon keresztül értesítést kap.<br>
		Az Ismétlődő fizetés igénybevételéhez jelen nyilatkozat elfogadásával Ön hozzájárul, hogy a sikeres regisztrációs tranzakciót követően jelen webshopban (' + $site_url + ') Ön az itt található felhasználói fiókjából kezdeményezett későbbi fizetések a bankkártyaadatok újbóli megadása nélkül menjenek végbe.
		Figyelem(!): a bankkártyaadatok kezelése a kártyatársasági szabályoknak megfelelően történik. A bankkártyaadatokhoz sem a Kereskedő, sem a SimplePay nem fér hozzá. A Kereskedő által tévesen vagy jogtalanul kezdeményezett ismétlődő fizetéses tranzakciókért közvetlenül a Kereskedő felel, Kereskedő fizetési szolgáltatójával (SimplePay) szemben bármilyen igényérvényesítés kizárt.
		Jelen tájékoztatót átolvastam, annak tartalmát tudomásul veszem és elfogadom.'
	);
	furik_add_custom_page( 'Köszönjük támogatásod!', '<p>Sikeres tranzakció.</p> <p>[furik_payment_info]</p>' );
	furik_add_custom_page(
		'Megszakított tranzakció',
		'<p>A fizetés meg lett szakítva vagy lejárt a tranzakció maximális ideje!</p>
		<p>[furik_payment_info]</p>
		<a href="[furik_back_to_campaign_url]">Vissza az oldalra</a>'
	);
	furik_add_custom_page(
		'Rendszeres támogatás',
		'<p>Amikor a rendszeres támogatás lehetőséget választod, akkor az először megadott összeggel havi rendszerességgel támogatod az Alapítványt. A kártyádról az első alkalommal az adatok megadása után vonjuk le az összeget, a későbbiekben pedig ez automatikusan megy. Minden hónapnak azon a napján, amelyiken regisztráltad a lehetőséget.</p>
		<p>A regisztráció alkalmával küldünk egy jelszót, amivel bejelentkezve a havi támogatást le tudod mondani bármikor. A rendszer 2 évig tudja levonni maximum az összegeket.</p>'
	);
	furik_add_custom_page(
		'Sikertelen kártyás tranzakció',
		'<p>Kérjük, ellenőrizze a tranzakció során megadott adatok helyességét. Amennyiben minden adatot helyesen adott meg, a visszautasítás okának kivizsgálása kapcsán kérjük, szíveskedjen kapcsolatba lépni kártyakibocsátó bankjával.</p>
		<p>[furik_payment_info]</p>'
	);
}

function furik_progress($campaign_id, $amount = 0) {
	global $wpdb;

	$return = array();

    $post = get_post($campaign_id);

    if (!$amount) {
		$meta = get_post_custom($post->ID);
		if (is_numeric($meta['GOAL'][0])) {
			$amount = $meta['GOAL'][0];
		}
    }
    $campaigns = get_posts(['post_parent' => $post->ID, 'post_type' => 'campaign', 'numberposts' => 100]);
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

	$return['collected'] = $result;

	if ($amount > 0) {
		$return['goal'] = $amount;
		$percentage = $return['percentage'] = round(1.0 * $result/$amount*100);
		$return['progress_bar'] = "<div class=\"furik-progress-bar\"><span style=\"width: " . ($percentage > 100 ? 100 : $percentage) . "%\"></span></div>";
	}

	return $return;
}

function furik_transaction_log($transaction_id, $message) {
	global $wpdb;

	$wpdb->insert(
		"{$wpdb->prefix}furik_transaction_log",
		array(
			'time' => current_time( 'mysql' ),
			'transaction_id' => $transaction_id,
			'message' => $message
		)
	);
}

function furik_update_transaction_status($order_ref, $status, $vendor_ref = "") {
	global $wpdb;

	$transaction = furik_get_transaction($order_ref);

	if ($transaction->transaction_status >= $status) {
		return;
	}

	$table_name = $wpdb->prefix . 'furik_transactions';
	$update = array("transaction_status" => $status);
	if ($vendor_ref) {
		$update["vendor_ref"] = $vendor_ref;
	}
	$wpdb->update(
		$table_name,
		$update,
		array("transaction_id" => $order_ref)
	);
}