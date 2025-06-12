<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$q1 = mysqli_query($con, "select * from member_details where member_id='" . $user_id . "'");
$db->dbDisconnet($con);
if (mysqli_num_rows($q1) == 1) {
    $r1 = mysqli_fetch_assoc($q1);

    $bank_update = $r1['bank_update'];
    if ($bank_update == 1) {
        $disabled = true;
    } else {
        $disabled = false;
    }

    $editData = true;
} else {
    $systemDenied = true;
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
                            <h2>My Bank</h2>
                            <p>Update your bank details from here</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if ($editData == true) {
            ?>
                <div class="form-panel">
                    <div class="row">
                        <div class="col-lg-12">
                            <?php include('include/alert.php'); ?>
                            <form id="frmEdit" action="my-bankc" method="post" enctype="multipart/form-data">
                                <input type="hidden" readonly name="id" value="<?php echo hash_encode($r1['member_id']); ?>" />
                                <div class="row">
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>IFSC*</label>
                                        <input type="text" name="ifsc" id="ifsc" value="<?php echo $r1['ifsc'] ?>" class="form-control" data-rule-required="true" <?php if ($disabled) echo 'disabled'; ?> />
                                        <div id="ifsc_status_text"></div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Bank Name*</label>
                                        <input type="text" name="bnk_nm" id="bnk_nm" value="<?php echo $r1['bnk_nm'] ?>" class="form-control" <?php if ($disabled) echo 'disabled'; ?> />
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Branch*</label>
                                        <input type="text" name="branch" id="brnch_nm" value="<?php echo $r1['brnch_nm'] ?>" class="form-control" data-rule-required="true" <?php if ($disabled) echo 'disabled'; ?> />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>A/C Number*</label>
                                        <input type="text" name="acc_no" class="form-control" data-rule-required="true" value="<?php echo $r1['acc_no']; ?>" <?php if ($disabled) echo 'disabled'; ?> />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>A/C Holder Name*</label>
                                        <input type="text" name="acc_nm" class="form-control" value="<?php echo $r1['acc_nm']; ?>" data-rule-required="true" <?php if ($disabled) echo 'disabled'; ?> />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>A/C Type*</label>
                                        <select name="acc_type" class="form-control" required <?php if ($disabled) echo 'disabled'; ?>>
                                            <option value="">Select</option>
                                            <option value="current" <?php if ($r1['acc_type'] == 'current') {
                                                                        echo 'selected';
                                                                    } ?>>Current</option>
                                            <option value="savings" <?php if ($r1['acc_type'] == 'savings') {
                                                                        echo 'selected';
                                                                    } ?>>Savings</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>PAN Number*</label>
                                        <input type="text" name="pan_no" value="<?php echo $r1['pan_no'] ?>" class="form-control" data-rule-required="true" <?php if ($r1['pan_no'] != '') {
                                                                                                                                                                echo 'readonly';
                                                                                                                                                            } ?> /> <!-- data-rule-pan="true" data-rule-maxlength="10" data-rule-minlength="10" -->
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" value="Save" class="btn btn-primary" <?php if ($disabled) echo 'disabled'; ?>>Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <!-- bank details -->
    <script type="text/javascript">
        $(document).ready(function() {
            $.fn.show_bank_branch_details = function() {
                $("#ifsc_status_text").html("<label for='ifsc' class='text-success'>Please Wait...!</label>");
                var bank_ifsc = $("#ifsc").val();

                if (bank_ifsc == '') {
                    $("#ifsc").val("");
                } else if (bank_ifsc.length > 11) {
                    $("#div_bank_ifsc").html("");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "../ajax/ifsc.php",
                        data: {
                            IFSC: bank_ifsc
                        },
                        cache: false,
                        success: function(result) {
                            try {
                                if (result == 'Not Found') {
                                    $("#bnk_nm").val('');
                                    $("#brnch_nm").val('');
                                    $("#ifsc_status_text").html("<label for='ifsc' class='text-danger'>Please Input A Valid IFSC Code..!</label>");
                                    /*$("#bnk_nm").attr("readonly", false); 
                                    $("#brnch_nm").attr("readonly", false); */
                                } else {
                                    let bankName = result.BANK;
                                    $("#bnk_nm").val(bankName.toUpperCase());
                                    $("#brnch_nm").val(result.BRANCH);
                                    $("#ifsc_status_text").html("<label for='ifsc' class='text-success'>Done..!</label>");
                                }
                            } catch (e) {
                                // $("#bank_branch").val("");
                            }
                        }
                    });
                }
                return false;
            };

            $(document).on("keyup", "#ifsc", function() {
                let ifsc = $('#ifsc').val();
                //alert(ifsc);
                $('#ifsc').val(ifsc.toUpperCase());
                if (ifsc.length == 11) {
                    $.fn.show_bank_branch_details();
                } else if (ifsc.length != 0) {
                    $("#bnk_nm").val('');
                    $("#brnch_nm").val('');
                    $("#ifsc_status_text").html("<label for='ifsc' class='text-danger'>Please Input A Valid IFSC Code..!</label>");
                } else {
                    $("#bnk_nm").val('');
                    $("#brnch_nm").val('');
                    $("#ifsc_status_text").html("<label for='ifsc' class='text-danger'>Please Input A Valid IFSC Code..!</label>");
                }
            });



        });
    </script>
    <!-- /bank details -->

    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
</body>

</html>