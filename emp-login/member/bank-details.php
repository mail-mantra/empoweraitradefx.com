<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();
$today = today();


/*if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}*/


$showData = false;

$con = $db->connect();
$q1 = mysqli_query($con, "select * from member_details where member_id='" . $user_id . "'");
$db->dbDisconnet($con);
if(mysqli_num_rows($q1) == 1) {
    $r1 = mysqli_fetch_assoc($q1);
    $bank_update = $r1['bank_update'];
    $showData = true;
}
else {
    $systemDenied = true;
}

if($bank_update == '' || $bank_update == NULL || $bank_update == 0) {
    $editData = true;
}
else {
    $editData = false;
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
                             <h2>Bank Information</h2>
                             <p>Add Your Bank Details</p>
                        </div>
                    </div>
                </div>
            </div>

           
                <div class="form-panel">
                    <?php include('include/alert.php'); ?>
                    
                    <?php
                    if($showData == true) {
                        //if($bank_update=='' || $bank_update==NULL || $bank_update==0){
                    ?>
                        <?php
                        if($editData) {
                            ?>
                            <form id="frmEdit" action="bank-detailsc" method="post">
                            <input type="hidden" readonly name="id"
                                   value="<?php echo hash_encode($r1['member_id']); ?>"/>
                            <?php
                        }
                        ?>
                        <div id="agentDetails">
                            <div class="well">
                              

                                <div class="form_outer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Bank Name<font color="#FF0000">*</font></label>
                                                <input type="text" name="bank" value="<?php echo $r1['bnk_nm'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                            <div class="form-group">
                                                <label>A/C Holder Name<font color="#FF0000">*</font></label>
                                                <input type="text" name="acc_nm" value="<?php echo $r1['acc_nm'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                            <div class="form-group">
                                                <label>A/C Type<font color="#FF0000">*</font></label>
                                                <select name="acc_type"
                                                        class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> >
                                                    <option value="">---Select---</option>
                                                    <option value="current" <?php if($r1['acc_type'] == 'current') {
                                                        echo 'selected';
                                                    } ?>>Current
                                                    </option>
                                                    <option value="savings" <?php if($r1['acc_type'] == 'savings') {
                                                        echo 'selected';
                                                    } ?>>Savings
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>PAN Number<font color="#FF0000">*</font></label>
                                                <input type="text" name="pan_no" value="<?php echo $r1['pan_no'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Branch<font color="#FF0000">*</font></label>
                                                <input type="text" name="branch" value="<?php echo $r1['brnch_nm'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                            <div class="form-group">
                                                <label>A/C Number<font color="#FF0000">*</font></label>
                                                <input type="text" name="acc_no" value="<?php echo $r1['acc_no'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                            <div class="form-group">
                                                <label>IFSC Code<font color="#FF0000">*</font></label>
                                                <input type="text" name="ifsc" value="<?php echo $r1['ifsc'] ?>"
                                                       class="form-control" <?= ($editData) ? 'data-rule-required="true"' : 'disabled'; ?> />
                                            </div>

                                        </div>
                                    </div>
                                </div><!--row-->

                                <?php if($editData) { ?>
                                    <input type="submit" name="submit" id="submit" class="btn btn-primary"
                                           value="Update">
                                <?php }
                                else {
                                    ?>
                                    <!--<div class="row my-5">
                                        <div class="col-md-12 text-center">
                                            <a href="bank-detailsc.php?bank_change_request=1" class="btn btn-danger my-5 btn_bank_change" data-title="Bank change request to Admin" >Submit Bank Change Request</a>
                                        </div>
                                    </div>-->
                                    <?php
                                }
                                ?>
                            </div>


                        </div>
                        <?php
                        if($editData) {
                            ?>
                            </form>
                            <?php
                        }
                        ?>
                        <?php
                        //}

                    }
                    ?> 
                </div><!--form panel-->
           
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