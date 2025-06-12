<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'all-add-fund-transaction';

$member_id = $user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>

    <?php include('include/header-common-file.php'); ?>
</head>


<body>
    <!-- particles -->
    <div id="particles-js"></div>

    <!--start-page-loader-->
    <div class="page-loader">
        <div class="loader"></div>
    </div>
    <!--end-page-loader-->

    <!--start-mm-menu-direction-->
    <?php include('include/menu-direction.php'); ?>
    <!--end-mm-menu-direction-->

    <!--start-mm-top-header-->
    <?php include('include/mm-top-header.php'); ?>
    <!--end-mm-top-header-->

    <!--start-body-content-->
    <div class="body-content">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title">
                        <div class="icon">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Balance Deposit</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel text-center">
                <?php
                $sql_view = "SELECT a.*, b.name  FROM api_transaction a 
                INNER JOIN member b ON a.member_id=b.member_id
                WHERE a.`member_id`='$member_id'
                ORDER BY id DESC";


                $con = $db->connect();
                $res_view = $con->query($sql_view);
                $db->dbDisconnet($con);
                $result["sql"] = $sql_view;
                if ($res_view->num_rows) {
                ?>
                    <div class="container mb-3">
                        <div class="row">
                            <div class="col">
                                <h6 class="subtitle mb-3">Transactions </h6>
                            </div>
                            <div class="col-auto"><a href="deposit-bep20" class="text-default">Back</a></div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="card mb-4">
                                    <div class="card-body px-0">
                                        <ul class="list-group list-group-flush">
                                            <?php
                                            $i = 1;
                                            while ($arr_view = $res_view->fetch_assoc()) {
                                            ?>
                                                <li class="list-group-item">
                                                    <div class="row align-items-center">
                                                        <div class="col align-self-center pr-0">
                                                            <h6 class="font-weight-normal mb-1" style="color: #000;">
                                                                <?php echo $arr_view['name'] ?>
                                                            </h6>
                                                            <p class="small text-info" style="padding-bottom: 0px; margin-bottom: 0px;">
                                                                <a href="https://bscscan.com/tx/<?php echo $arr_view['hash'] ?>" target="_BLANK">
                                                                    <?php echo substr($arr_view['hash'], 0, 8) . "..." . substr($arr_view['hash'], -8) ?>
                                                                </a>
                                                            </p>
                                                            <p class="small text-secondary">
                                                                <?php echo date('d-m-Y h:i A', strtotime($arr_view['created_on'])); ?>
                                                            </p>
                                                        </div>
                                                        <div class="col-auto">
                                                            <h6 class="text-success">
                                                                <?php echo $arr_view["currency_type"] ?> <?php echo $arr_view["actual_amount"] ?>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php
                                                $i++;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        <?php
                } else {
                    //$result["message"] = "No data Found";
                }
        ?>
        </div><!--tabel-panel-->

    </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

    <script>
        function copyToClipboard() {
            //console.time('time1');
            //$('#copyBtn_'+id).text('Copied');

            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#memberAddress').text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }
    </script>

    <script type="text/javascript">
        function copyToClipboard2(id) {

            var temp = $("<input>");
            $("body").append(temp);
            temp.val($('#address_' + id).text()).select();
            document.execCommand("copy");
            temp.remove();
            alert('Copied');
        }
    </script>

</body>

</html>