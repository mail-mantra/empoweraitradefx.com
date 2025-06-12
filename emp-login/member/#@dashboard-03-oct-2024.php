<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

if ($payment_status == 0) {
  header("Location:../../payment");
  die;
  
} else {

    $con = $db->connect();
    $direct_refaralCount = direct_refaralCount($con, $user_code);
    $total_downlineCount = total_downlineCount($con, $user_code);
    
    $fund_wallet = get_wallet_balance_of_member($con, $user_id, 'myfund_wallet_balance');
    $working_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
    $roi_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');
    
    $is_active = member_is_active($con, $user_id);
    
    $total_business = downline_member_business($con, $user_code, 'member_package_update_log');
    
    $self_business = get_self_investment_of_member($con, $user_id, 'member_package_update_log');
    
    $mfRate = getMfRate($con);
    //$ba_wallet = get_wallet_balance_of_member($con, $user_id, 'ba_wallet_balance');
    
    $sql_dwn = "INSERT INTO member_downline_business(member_id, business)
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
    
    $_rank = get_rank_reward($con, $user_id, 'rank');
    $_reward = get_rank_reward($con, $user_id, 'reward');
    
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
                <h2><?php echo PROJECT_NAME ?> Analytics</h2>
              </div>
            </div>
          </div>
        </div>

        <div class="sec-17">
          <div class="row">
            <div class="col-md-6">
              <div class="row">

                <div class="col-md-6">
                  <div class="box">
                    <div class="icon"><i class="fa fa-diamond"></i></div>
                    <h4>Status</h4>
                    <h3><?php echo ($is_active >= 1) ? 'Active' : 'Inactive' ?></h3>
                    <h5><span class="<?php echo ($is_active >= 1) ? 'cl-6' : 'cl-7' ?>"><?php echo ($is_active >= 1) ? 'Active' : 'Inactive' ?></span>Status</h5>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-database"></i></div>
                    <h4>Sponsor Count</h4>
                    <h3><?php echo $direct_refaralCount ?></h3>
                    <h5><span class="cl-6"><?php echo $direct_refaralCount ?></span>Sponsor Count</h5>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-database"></i></div>
                    <h4>Downline Count</h4>
                    <h3><?php echo $total_downlineCount ?></h3>
                    <h5><span class="cl-7"><?php echo $total_downlineCount ?></span>Downline Count</h5>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="box">
                    <div class="icon"><i class="fa fa-database"></i></div>
                    <h4>Investment (Self)</h4>
                    <h3>MF <?php echo $self_business; ?></h3>
                    <h5><span class="cl-6">Rs. <?php echo $self_business ? $self_business * $mfRate : '0'; ?></span>Investment (Self)</h5>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="box">
                    <div class="icon"><i class="fa fa-database"></i></div>
                    <h4>Business Ratio</h4>
                    <h3><?php echo $business_1 . ' : ' . $business_2; ?></h3>
                    <h5><span class="cl-6"><?php echo $business_1 . ' : ' . $business_2; ?></span>Business Ratio</h5>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-star"></i></div>
                    <h4>Rank</h4>
                    <h3><?php echo $_rank; ?></h3>
                    <h5><span class="cl-7"><?php echo $_rank; ?></span>Rank</h5>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-trophy"></i></div>
                    <h4>Reward</h4>
                    <h3><?php echo $_reward; ?></h3>
                    <h5><span class="cl-7"><?php echo $_reward; ?></span>Reward</h5>
                  </div>
                </div>
                
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="news">
                <h3>News Board</h3>
                <marquee scrollamount="2" direction="up" loop="true">
                  <?php
                  $sql = "SELECT `description` FROM `member_news` WHERE `status`='1' ORDER BY `id` DESC";
                  $con = $db->connect();
                  $result = $con->query($sql);
                  if ($result->num_rows > 0) {
                    $no = 0;
                    while ($row = $result->fetch_assoc()) {
                      if ($no == 0) {
                        echo '<p>' . nl2br($row["description"]) . '</p>';
                      } else {
                        echo '<p>' . nl2br($row["description"]) . '</p>';
                      }
                      $no++;
                    }
                  } else {
                    echo '<p>Will be updated soon.</p>';
                  }
                  ?>
                </marquee>
              </div>
              
              <div class="news" style="background:#fff; min-height:130px;">
                <h3 style="color:#2481cc"><i class="fa fa-telegram"></i> Premium Telegram Group</h3> 
                <a href="https://t.me/+YchsWYMTMoFlZDBl" target="_blank" class="btn btn-success">Click Here to Join</a>
              </div>
            </div>
            <!-- <div class="col-md-6">
            <div id="bar-chart"></div>
          </div> -->

            <div class="col-md-12">
              <div class="row">

                <div class="col-md-3">
                  <div class="box">
                    <div class="icon"><i class="fa fa-suitcase"></i></div>
                    <h4>Investment (Team)</h4>
                    <h3><?php echo $total_business ?></h3>
                    <h5><span class="cl-6"><?php echo $total_business ?></span>Investment (Team)</h5>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-suitcase"></i></div>
                    <h4>Fund</h4>
                    <h3><?php echo $fund_wallet ?></h3>
                    <h5><span class="cl-7"><?php echo $fund_wallet ?></span>Fund Balance</h5>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="box">
                    <div class="icon"><i class="fa fa-suitcase"></i></div>
                    <h4>Working</h4>
                    <h3><?php echo $working_wallet ?></h3>
                    <h5><span class="cl-5"><?php echo $working_wallet ?></span>Working Balance</h5>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="box-2">
                    <div class="icon"><i class="fa fa-suitcase"></i></div>
                    <h4>ROI</h4>
                    <h3><?php echo $roi_wallet ?></h3>
                    <h5><span class="cl-7"><?php echo $roi_wallet ?></span>ROI Balance</h5>
                  </div>
                </div>

              </div>
            </div>



            <div class="col-md-4">
              <div id="cal"></div>
            </div>

            <div class="col-md-8">
              <div class="refer">
                <h3>Registration Referral Link</h3>
                <div class="row">
                  <?php
                  $con = $db->connect();
                  $qk = mysqli_query($con, "select * from join_package order by id");
                  $db->dbDisconnet($con);
                  while ($rk = mysqli_fetch_assoc($qk)) {

                    $package_id = $rk['id'];
                    $package_name = $rk['package_name'];
                  ?>

                    <div class="col-md-6">
                      <div class="caption">
                        <p class='text-danger'><?php echo $package_name ?></p>
                        <?php
                        $ref = encrypt($user_code . "_" . $package_id);
                        $ref_link = PROJECT_URL . "referral-join?ref=" . $ref;
                        ?>
                        <span style="color:#000;">
                          <?php echo substr($ref_link, 0, 26) . "..." . substr($ref_link, 50, 5); ?>
                        </span>
                        <a onclick="copyToClipboard('<?php echo $ref_link ?>');" title="Copy to clipboard">
                          <i class="fa fa-copy" style="font-size: 1.2em; cursor: copy;"></i>
                        </a>
                        <div id="snackbar">Copied to clipboard</div>
                        <h5>
                          <a href="tg://msg?text=<?php echo $ref_link; ?>"><img src="../web-assets/images/telegram.png" alt="Telegram" style="width:25px;"></a>
                          <a href="whatsapp://send?text=<?php echo $ref_link ?>"> <img src="../web-assets/images/whatsapp_icon.png" alt="Whatsapp" style="width:25px;"></a>
                          <a href="sms://?body=/<?php echo $ref_link; ?>/"><img src="../web-assets/images/sms_icon.png" alt="SMS" style="width:25px;"></a>
                          <a href="mailto:?&subject=Mahadevan&body=<?php echo $ref_link ?>"><img src="../web-assets/images/gmail_icon.png" alt="Gmail" style="width:25px;"></a>
                        </h5>
                      </div>

                      <hr>
                    </div>

                  <?php
                  }
                  ?>
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

    <script script type="text/javascript">
      // $('#myModal').modal('show');
      function copyToClipboard(textToCopy) {
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(textToCopy).select();
        document.execCommand("copy");
        temp.remove();
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function() {
          x.className = x.className.replace("show", "");
        }, 3000);
      }
      $(document).ready(function() {
        //barChart();
        //lineChart();
        //areaChart();
        //donutChart();

        $(window).resize(function() {
          window.barChart.redraw();
          window.lineChart.redraw();
          window.areaChart.redraw();
          window.donutChart.redraw();
        });
      });

      function barChart() {
        window.barChart = Morris.Bar({
          element: 'bar-chart',
          data: [{
              y: '2006',
              a: 100,
              b: 90
            },
            {
              y: '2007',
              a: 75,
              b: 65
            },
            {
              y: '2008',
              a: 50,
              b: 40
            },
            {
              y: '2009',
              a: 75,
              b: 65
            }
          ],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Series A', 'Series B'],
          lineColors: ['#1e88e5', '#ff3321'],
          lineWidth: '3px',
          resize: true,
          redraw: true
        });
      }

      function lineChart() {
        window.lineChart = Morris.Line({
          element: 'line-chart',
          data: [{
              y: '2006',
              a: 100,
              b: 90
            },
            {
              y: '2007',
              a: 75,
              b: 65
            },
            {
              y: '2008',
              a: 50,
              b: 40
            },
            {
              y: '2009',
              a: 75,
              b: 65
            }
          ],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Series A', 'Series B'],
          lineColors: ['#1e88e5', '#ff3321'],
          lineWidth: '3px',
          resize: true,
          redraw: true
        });
      }

      function areaChart() {
        window.areaChart = Morris.Area({
          element: 'area-chart',
          data: [{
              y: '2006',
              a: 100,
              b: 90
            },
            {
              y: '2007',
              a: 75,
              b: 65
            },
            {
              y: '2008',
              a: 50,
              b: 40
            },
            {
              y: '2009',
              a: 75,
              b: 65
            }
          ],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Series A', 'Series B'],
          lineColors: ['#1e88e5', '#ff3321'],
          lineWidth: '3px',
          resize: true,
          redraw: true
        });
      }

      function donutChart() {
        window.donutChart = Morris.Donut({
          element: 'donut-chart',
          data: [{
              label: "Download Sales",
              value: 50
            },
            {
              label: "In-Store Sales",
              value: 25
            },

          ],
          resize: true,
          redraw: true
        });
      }

      function pieChart() {
        var paper = Raphael("pie-chart");
        paper.piechart(
          100, // pie center x coordinate
          100, // pie center y coordinate
          90, // pie radius
          [18.373, 18.686, 2.867, 23.991, 9.592, 0.213], // values
          {
            legend: ["Windows/Windows Live", "Server/Tools", "Online Services", "Business", "Entertainment/Devices", "Unallocated/Other"]
          }
        );
      }
    </script>

    <!--start-calendar-->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.min.js'></script>
    <script src='https://momentjs.com/downloads/moment.min.js'></script>
    <script>
      class Calendar extends React.Component {
        constructor(props) {
          super(props);

          this.state = {
            month: moment(),
            selected: moment().startOf('day')
          };


          this.previous = this.previous.bind(this);
          this.next = this.next.bind(this);
        }

        previous() {
          const {
            month
          } =
          this.state;

          this.setState({
            month: month.subtract(1, 'month')
          });

        }

        next() {
          const {
            month
          } =
          this.state;

          this.setState({
            month: month.add(1, 'month')
          });

        }

        select(day) {
          this.setState({
            selected: day.date,
            month: day.date.clone()
          });

        }

        renderWeeks() {
          let weeks = [];
          let done = false;
          let date = this.state.month.clone().startOf("month").add("w" - 1).day("Sunday");
          let count = 0;
          let monthIndex = date.month();

          const {
            selected,
            month
          } =
          this.state;

          while (!done) {
            weeks.push( /*#__PURE__*/
              React.createElement(Week, {
                key: date,
                date: date.clone(),
                month: month,
                select: day => this.select(day),
                selected: selected
              }));


            date.add(1, "w");

            done = count++ > 2 && monthIndex !== date.month();
            monthIndex = date.month();
          }

          return weeks;
        }

        renderMonthLabel() {
          const {
            month
          } =
          this.state;

          return /*#__PURE__*/ React.createElement("span", {
            className: "month-label"
          }, month.format("MMMM YYYY"));
        }

        render() {
          return /*#__PURE__*/ (
            React.createElement("section", {
                className: "calendar"
              }, /*#__PURE__*/
              React.createElement("header", {
                  className: "header"
                }, /*#__PURE__*/
                React.createElement("div", {
                    className: "month-display row"
                  },
                  this.renderMonthLabel(), /*#__PURE__*/
                  React.createElement("i", {
                    className: "arrow fa fa-angle-left",
                    onClick: this.previous
                  }), /*#__PURE__*/

                  React.createElement("i", {
                    className: "arrow fa fa-angle-right",
                    onClick: this.next
                  })), /*#__PURE__*/

                React.createElement(DayNames, null)),

              this.renderWeeks()));


        }
      }


      class DayNames extends React.Component {
        render() {
          return /*#__PURE__*/ (
            React.createElement("div", {
                className: "row day-names"
              }, /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "S"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "M"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "T"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "W"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "T"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "F"), /*#__PURE__*/
              React.createElement("span", {
                className: "day"
              }, "S")));


        }
      }


      class Week extends React.Component {
        render() {
          let days = [];
          let {
            date
          } =
          this.props;

          const {
            month,
            selected,
            select
          } =
          this.props;

          for (var i = 0; i < 7; i++) {
            let day = {
              name: date.format("dd").substring(0, 1),
              number: date.date(),
              isCurrentMonth: date.month() === month.month(),
              isToday: date.isSame(new Date(), "day"),
              date: date
            };

            days.push( /*#__PURE__*/
              React.createElement(Day, {
                day: day,
                selected: selected,
                select: select
              }));


            date = date.clone();
            date.add(1, "day");
          }

          return /*#__PURE__*/ (
            React.createElement("div", {
                className: "row week",
                key: days[0]
              },
              days));


        }
      }



      class Day extends React.Component {
        render() {
          const {
            day,
            day: {
              date,
              isCurrentMonth,
              isToday,
              number
            },

            select,
            selected
          } =
          this.props;

          return /*#__PURE__*/ (
            React.createElement("span", {
              key: date.toString(),
              className: "day" + (isToday ? " today" : "") + (isCurrentMonth ? "" : " different-month") + (date.isSame(selected) ? " selected" : ""),
              onClick: () => select(day)
            }, number));

        }
      }


      ReactDOM.render( /*#__PURE__*/ React.createElement(Calendar, null), document.getElementById('cal'));
    </script>
    <!--end-calendar-->
  <?php
}
  ?>