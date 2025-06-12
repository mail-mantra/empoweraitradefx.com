<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();
$today = today();


if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}


@$back = $_SERVER['HTTP_REFERER'];
$redirect = 'upi-deposite';
if(isset($_REQUEST['auth']) && $_REQUEST['auth']==true){
  
        $con=$db->connect();
    	$q1=mysqli_query($con,"select * from admin_bank_account order by id desc limit 0,1");
    	$db->dbDisconnet($con);	
    	$r1 = mysqli_fetch_assoc($q1);
    	$admin_account_id = $r1['id'];
    	$admin_account_name = urlencode($r1['account_name']);
    	$admin_account_no = $r1['account_no'];
    	$admin_ifsc_code = $r1['ifsc_code'];
        
        $token = BH_PAY_TOKEN;
        $api_url = "https://api.bharatpays.in/api/vpa/create?token=$token&name=$admin_account_name&description=$admin_account_name&account_number=$admin_account_no&account_ifsc=$admin_ifsc_code";
        $api_response_obj = curlGet($api_url);
    
   
        $api_response = json_encode($api_response_obj);
        $api_success = $api_response_obj->success;
        $api_message = $api_response_obj->message;
       
      
        if($api_success==1){
                $virtual_account_id = $api_response_obj->data->virtual_account_id;
                $virtual_account_number = $api_response_obj->data->virtual_account_number;
                $virtual_ifsc = $api_response_obj->data->virtual_ifsc;
                $virtual_upi_handle = $api_response_obj->data->virtual_upi_handle;
                $service_charge = $api_response_obj->data->service_charge;
                $gst_amount = $api_response_obj->data->gst_amount;
                $service_charge_with_gst = $api_response_obj->data->service_charge_with_gst;
                $qrcode_image = $api_response_obj->data->qrcode_image;
                $qrcode_pdf = $api_response_obj->data->qrcode_pdf;
                
                $con=$db->connect();
            	mysqli_autocommit($con,FALSE);						
            	$tq1=mysqli_query($con,"INSERT INTO `member_vpa_account`(`member_id`,`mem_code`, `admin_account_id`, `virtual_account_id`, `virtual_account_number`, `virtual_ifsc`, `virtual_upi_handle`, `service_charge`, `gst_amount`, `service_charge_with_gst`, `qrcode_image`, `qrcode_pdf`, `api_response`, `created_by`, `created_on`)
            						values ('".$user_id."','".$user_code."','".$admin_account_id."', '".$virtual_account_id."','".$virtual_account_number."','".$virtual_ifsc."','".$virtual_upi_handle."','".$service_charge."','".$gst_amount."','".$service_charge_with_gst."','".$qrcode_image."','".$qrcode_pdf."','".$api_response."','".$action_by."','".$now."')");
            	$error = mysqli_error($con);
                $aff_rows = mysqli_affected_rows($con);
            	if($q1 && $aff_rows==1){
            		mysqli_commit($con);
            		$success = 1;
            	}else{
            		$success = 0;
            		mysqli_rollback($con);
            	}
            	$db->dbDisconnet($con);
            	if($success == 1){
            	    $id = hash_encode($virtual_account_id);
            		header("Location:$redirect?pay=true&id=$id");
                    die;
            	}else{
            		$_SESSION['e'] = "Temporary problem...! Please try again. ";
            	
            	}
        }else{
            $_SESSION['e'] = $api_message;
        }
        header("Location:$redirect");
        die;
}

if(isset($_REQUEST['status']) && $_REQUEST['status']==1){
    $_SESSION['s'] = "<strong>Successful Transaction</strong>. Amount has been credited to your wallet";
    header("Location:$redirect");
    die;
}else if(isset($_REQUEST['status']) && $_REQUEST['status']==2){
    $_SESSION['e'] = "Invalid Transaction";
    header("Location:$redirect");
    die;
}else if(isset($_REQUEST['status']) && $_REQUEST['status']==3){
    $_SESSION['w'] = "<strong>Successful Transaction</strong>. But the amount is not credited to your wallet. It will be updated soon.";
    header("Location:$redirect");
    die;
}
else if(isset($_REQUEST['status']) && $_REQUEST['status']==4){
    $_SESSION['e'] = "<strong>Sorry !</strong>. Transaction Failed";
    header("Location:$redirect");
    die;
}else if(isset($_REQUEST['status']) && $_REQUEST['status']==0){
    $_SESSION['w'] = "<strong>Sorry !</strong>. Transaction not found";
    header("Location:$redirect");
    die;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php include('include/title.php'); ?></title>
    <link rel="shortcut icon" href="images/fab_icon.gif" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('include/header-common-file.php'); ?>
</head>


<body>
    <!--start-mm-menu-direction-->
    <?php include('include/menu-direction.php'); ?>
    <!--end-mm-menu-direction-->

    <!--start-body-content-->
    <div class="body-content">

        <!--start-mm-top-header-->
        <?php include('include/mm-top-header.php'); ?>
        <!--end-mm-top-header-->

        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="dashboard-title-2">
                        <div class="caption-2">
                             <h2>Recharge </h2>
                             <p>Make payment through UPI</p>
                        </div>
                    </div>
                </div>
            </div>

           
                <div class="form-panel">
                    <div class="row">
                        <div class="col-lg-12">

                            <?php include('include/alert.php'); ?>

                            <div class="well text-center">
                            <?php
                            if(isset($_REQUEST['pay']) && $_REQUEST['pay']==true){
                                $virtual_account_id = hash_decode($_REQUEST['id']);
                               
                                $con=$db->connect();
                                $q1=mysqli_query($con,"select * from member_vpa_account where member_id='".$user_id."' and virtual_account_id='".$virtual_account_id."'");
                                $db->dbDisconnet($con);
                               
                                if(mysqli_num_rows($q1)==1){
                                   $r1 = mysqli_fetch_assoc($q1);
                                   $qrcode_image = $r1['qrcode_image']; 
                                   $virtual_upi_handle = $r1['virtual_upi_handle'];
                                }else{
                                    $systemDenied=true;
        	                        include('include/forced-logout.php');
                                }
                                
                            ?>
                       
                            <!-- The Modal Start -->
                            <div class="modal" id="myModal" style="z-index:999999">
                              <div class="modal-dialog">
                                <div class="modal-content" >
                                  <!-- Modal body -->
                                  <div class="modal-body">
                                       <h3 style="color:#fff">Scan & Pay</h3><br>
                                       <img alt="Loading...." title="Scan and Pay" style="width:300px" src="<?php echo $qrcode_image ?>">
                                       <p style="color:#5ed703"><?php echo $virtual_upi_handle ?></p>
                                       
                                       <div id="checkMsg"><h3 style="font-size:16px;" ><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></h3></div>
                                       <br>
                                       <p style="font-size:16px; color:#8e9523">Please do not refresh or close the page</p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- The Modal END-->
                            <?php }else{ ?>
                            <a href="?auth=true" id="payBtn" class="btn btn-lg btn-primary">Pay Now</a>
                            <div id="payMsg"></div>
                            <?php } ?>
                            
                            <!--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>-->
                           
                            </div><!--well-->
                            
                        </div>

                    </div>
                </div>
           
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
    $('body').on('click', '#payBtn', function(){ 
      $('#payBtn').hide();
      $('#payMsg').html('<h4><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> Loading...</h4>');
    });
    <?php
    if(isset($_REQUEST['pay']) && $_REQUEST['pay']==true){
        $id = $_REQUEST['id'];
        echo "var id='$id';";
    } ?>

    </script>
    <?php if(isset($_REQUEST['pay']) && $_REQUEST['pay']==true){ ?>
    <script src="../web-assets/js/customjs.js"></script>
    <?php } ?>
</body>

</html>