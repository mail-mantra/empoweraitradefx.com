<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');

date_default_timezone_set('Asia/kolkata');
$db = new Database();
if (!$_SESSION['user_data']["logged_in"]) {
    //$result["message"] = "Member Address Required";
} else {
    $member_id = $_SESSION['user_data']['user_id'];


    /*$sql_view = "SELECT a.*, b.name, c.amount AS trx_amount FROM payment_transaction a 
                INNER JOIN member b ON a.member_id=b.member_id 
                INNER JOIN trongrid_payment_data_software AS c ON a.for_member_id = c.tx_id
                WHERE a.`member_id`='$member_id' ORDER BY id DESC LIMIT 5;";*/

    $sql_view = "SELECT a.*, b.name  FROM api_transaction a 
                INNER JOIN member b ON a.member_id=b.member_id
                WHERE a.`member_id`='$member_id'
                ORDER BY id DESC LIMIT 5";


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
                <div class="col-auto"><a href="all-add-fund-transaction" class="text-default">View all</a></div>
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
                                                    <?php echo $arr_view['actual_amount']; ?>
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
}
