<?php
class Database
{
	private $db_host = "localhost";
	private $db_user = 'empoweraitradefx_user';
	private $db_pass = "^aDEW)XGVtZk";
	private $db_name = "empoweraitradefx_db";


	private $con = '';

	public function connect()
	{
		$con = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			die;
		} else {
			return $con;
		}
	}

	function dbDisconnet($con)
	{
		mysqli_close($con);
	}
}

define('PROJECT_NAME', 'Empower TradeFX');
define('PROJECT_URL', 'empoweraitradefx.com/');
define('PROJECT_LOGO', 'https://empoweraitradefx.com/emp-login/web-assets/images/logo.png');
define('PROJECT_LOGO2', 'https://empoweraitradefx.com/emp-login/web-assets/images/logo.png');
define('PREFIX_MEMBER_CODE', 'ETF');
define('CURRENCY_NAME', 'USDT ');
define('CURRENCY_ICON', '<i class="fa fa-usd"></i> ');

define('WRITABLE_PATH',	dirname(__DIR__) . "/web-assets/images"); // images/screenshots
define('WRITABLE_URL', "https://empoweraitradefx.com/emp-login/web-assets/images/"); // images/screenshots/

ini_set('memory_limit', '512M');
define('BASE_URL', 'empoweraitradefx.com/');
define('API_TOKEN', 'TW358CM5K4I9GZEAFD271NZ6PVE49Q9EDQ');
define('BEP_20_CONTRACT_ADDRESS', '0x55d398326f99059ff775485246999027b3197955');

define('MM_MAIL_AUTH_KEY', '4840-681c8ce905fc2-00003');
define('MM_SMS_AUTH_KEY', '#');
define('BH_PAY_TOKEN', '');

define("CRYPTOCOMPARE_API_KEY", "#");
define("TELEGRAM_API_TOKEN", "#");
define("TELEGRAM_CHAT_ID", "#");

define("USD_RATE", 80);

$page_name = basename($_SERVER['PHP_SELF']);
