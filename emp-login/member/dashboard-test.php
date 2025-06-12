<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();


$con = $db->connect();
$direct_refaralCount = direct_refaralCount($con, $user_code);
$total_downlineCount = total_downlineCount($con, $user_code);

$fund_wallet = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
$working_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
//$ba_wallet = get_wallet_balance_of_member($con, $user_id, 'ba_wallet_balance');
//$roi_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');

$is_active = member_is_active($con, $user_id);

//$is_registered = member_is_registered($con, $user_id);

//$total_business = downline_member_business($con, $user_code, 'member_package_update_log');

$self_business = get_self_investment_of_member($con, $user_id, 'member_package_update_log');


$_rank = get_rank($con, $user_id);

/*$sql_dwn = "INSERT INTO member_downline_business(member_id, business)
        SELECT member_id, INTROWISE_BUSINESS_60_40(member_id, '$current_date') FROM member
        ON DUPLICATE KEY UPDATE business = VALUES(`business`)";
$q_dwn = mysqli_query($con,$sql_dwn);

if($q_dwn){
    $_business_ratio = get_business_ratio($con, $user_id);
    $business_1 = $_business_ratio['max_value'];
    $business_2 = $_business_ratio['min_value'];
}else{
    $business_1 = 0;
    $business_2 = 0; 
}


$_reward = get_reward($con, $user_id);*/

$db->dbDisconnet($con);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php include('include/title.php'); ?></title>
  <link rel="shortcut icon" href="images/fab_icon.gif" />
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php include('include/header-common-file.php'); ?>
  <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
  <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

  <link rel="stylesheet" href="../web-assets/css/calendar.css">
  <style>
    .morris-hover {
      position: absolute;
      z-index: 1000;
    }

    .morris-hover.morris-default-style {
      border-radius: 10px;
      padding: 6px;
      color: #666;
      background: rgba(255, 255, 255, 0.8);
      border: solid 2px rgba(230, 230, 230, 0.8);
      font-family: sans-serif;
      font-size: 12px;
      text-align: center;
    }

    .morris-hover.morris-default-style .morris-hover-row-label {
      font-weight: bold;
      margin: 0.25em 0;
    }

    .morris-hover.morris-default-style .morris-hover-point {
      white-space: nowrap;
      margin: 0.1em 0;
    }

    svg {
      width: 100%;
    }

    /* The snackbar - position it at the bottom and in the middle of the screen */
    #snackbar {
      visibility: hidden;
      /* Hidden by default. Visible on click */
      min-width: 250px;
      /* Set a default minimum width */
      margin-left: -125px;
      /* Divide value of min-width by 2 */
      background-color: #333;
      /* Black background color */
      color: #fff;
      /* White text color */
      text-align: center;
      /* Centered text */
      border-radius: 2px;
      /* Rounded borders */
      padding: 16px;
      /* Padding */
      position: fixed;
      /* Sit on top of the screen */
      z-index: 1;
      /* Add a z-index if needed */
      left: 50%;
      /* Center the snackbar */
      bottom: 30px;
      /* 30px from the bottom */
    }

    /* Show the snackbar when clicking on a button (class added with JavaScript) */
    #snackbar.show {
      visibility: visible;
      /* Show the snackbar */
      /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
      However, delay the fade out process for 2.5 seconds */
      -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
      animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
  </style>
  <style>
    .clickBtn {
      font-size: 15px;
      color: #2a7fff;
    }

    .blink {
      color: yellow;
      animation: blink 1s linear infinite;
    }

    @keyframes blink {
      0% {
        opacity: 0;
      }

      50% {
        opacity: .5;
      }

      100% {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <!--start-mm-menu-direction-->
  <?php include('include/menu-direction.php'); ?>
  <!--end-mm-menu-direction-->

  <!--start-modal-->
  <div id="myModal" class="modal fade" role="dialog" style="z-index:10000">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-body">
          <div class="popupclosebtn">
            <button type="button" class="close close1" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <img src="../web-assets/images/popup.jpg" width="100%" class="img-fluid" />
          <!--<a href="download/business-plan.pdf" class="btn btn-success btn-lg" style="width:230px; margin:10px auto; display:block;" download>Download Businessplan</a>-->
        </div>
      </div>

    </div>
  </div>
  <!--end-modal-->

  <!--start-body-content-->
  <div class="body-content">

    <!--start-mm-top-header-->
    <?php include('include/mm-top-header.php'); ?>
    <!--end-mm-top-header-->

    <div class="container">
      <div class="col-lg-12">

        <?php include('include/alert.php'); ?>

        <div class="row">
          <div class="dashboard-title-2">
            <div class="caption-2">
              <h2><?php echo PROJECT_NAME ?> Business Overview</h2>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="row d-none d-lg-block">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <div class="nk-block-head-content">
                    <h5 class="text-primary h5">Fundamental & Technical Outlook</h5>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="home-tab" data-toggle="tab"
                        data-target="#home" type="button" role="tab"
                        aria-controls="home" aria-selected="true"
                        style="color:#222; padding:5px;">Track all
                        markets</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="profile-tab" data-toggle="tab"
                        data-target="#profile" type="button" role="tab"
                        aria-controls="profile" aria-selected="false"
                        style="color:#222; padding:5px;">Personal
                        trading chart</button>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                      aria-labelledby="home-tab">
                      <!-- TradingView Widget BEGIN -->
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <div class="tradingview-widget-copyright"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js"
                          async>
                          {
                            "width": "100%",
                            "height": "550",
                            "currencies": [
                              "EUR",
                              "USD",
                              "JPY",
                              "GBP",
                              "CHF",
                              "AUD",
                              "CAD",
                              "NZD",
                              "CNY",
                              "TRY",
                              "SEK",
                              "NOK"
                            ],
                            "isTransparent": true,
                            "colorTheme": "light",
                            "locale": "en"
                          }
                        </script>
                      </div>
                      <!-- TradingView Widget END -->
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel"
                      aria-labelledby="profile-tab">
                      <div class="tradingview-widget-container">
                        <div id="tradingview_f933e"></div>
                        <div class="tradingview-widget-copyright">

                          <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                          <script type="text/javascript">
                            new TradingView.widget({
                              "width": "100%",
                              "height": "550",
                              "symbol": "COINBASE:BTCUSD",
                              "interval": "1",
                              "timezone": "Etc/UTC",
                              "theme": 'light',
                              "style": "9",
                              "locale": "en",
                              "toolbar_bg": "#f1f3f6",
                              "enable_publishing": false,
                              "hide_side_toolbar": false,
                              "allow_symbol_change": true,
                              "calendar": false,
                              "studies": [
                                "BB@tv-basicstudies"
                              ],
                              "container_id": "tradingview_f933e"
                            });
                          </script>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>

    <!--start-calendar-->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.min.js'></script>
    <script src='https://momentjs.com/downloads/moment.min.js'></script>