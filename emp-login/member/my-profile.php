<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$q1 = mysqli_query($con, "select * from member m1 inner join member_details m2 on m1.member_id=m2.member_id where m1.member_id='" . $user_id . "'");
$db->dbDisconnet($con);
if (mysqli_num_rows($q1) == 1) {
    $r1 = mysqli_fetch_assoc($q1);
    $disabled = false;

//    $profile_update = $r1['profile_update'];
//    if ($profile_update == 1) {
//        $disabled = true;
//    } else {
//        $disabled = false;
//    }

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
                            <h2>My Account</h2>
                            <p>Update your account details from here</p>
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

                            <form id="frmEdit" action="my-profilec" method="post" enctype="multipart/form-data">
                                <input type="hidden" readonly name="id" value="<?php echo hash_encode($r1['member_id']); ?>" />
                                <div class="row">
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Name*</label>
                                        <input type="text" name="name" class="form-control" data-rule-required="true" value="<?php echo $r1['name']; ?>" disabled />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Mobile*</label>
                                        <input type="text" name="mobile" value="<?php echo $r1['mobile'] ?>" class="form-control" maxlength="10" data-rule-required="true" data-rule-digits="true" data-rule-minlength="10" data-rule-maxlength="10" disabled />
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Email ID*</label>
                                        <input type="email" name="email" value="<?php echo $r1['email'] ?>" class="form-control" disabled />
                                    </div>


                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="father_name">Father Name*</label>
                                        <input type="text" name="father_name" id="father_name" class="form-control" value="<?=$r1['father_name']; ?>" data-rule-required="true" required />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="mother_name">Mother Name*</label>
                                        <input type="text" name="mother_name" id="mother_name" class="form-control" value="<?=$r1['mother_name']; ?>" data-rule-required="true" required />
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Date of Birth*</label>
                                        <input type="date" name="dob" value="<?php echo ($r1['mem_dob'] == '00-00-0000') ? '' : $r1['mem_dob'] ?>" class="form-control" placeholder="dd-mm-yyyy" required />
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label>Gender*</label>
                                        <select name="gender" id="gender" class="form-control" required >
                                            <option value="">Select</option>
                                            <option value="male" <?php if ($r1['gender'] == 'male') {
                                                                        echo 'selected';
                                                                    } ?>>Male</option>
                                            <option value="female" <?php if ($r1['gender'] == 'female') {
                                                                        echo 'selected';
                                                                    } ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="marriage_status">Married/Unmarried*</label>
                                        <select name="marital" id="marriage_status" class="form-control" required >
                                            <option value="">---Select---</option>
                                            <option value="Married" <?=($r1['marital'] == 'Married') ?'selected':''; ?>>Married</option>
                                            <option value="Unmarried" <?=($r1['marital'] == 'Unmarried') ?'selected':''; ?>>Unmarried</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="spouse_name">Spouse Name*</label>
                                        <input type="text" name="spouse_name" id="spouse_name" class="form-control" value="<?=$r1['spouse_name']; ?>" required />
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="free_insurance">Want Gift Insurance?*</label>
                                        <select name="free_insurance" id="free_insurance" class="form-control" required >
                                            <option value="">---Select---</option>
                                            <option value="yes" <?=($r1['free_insurance'] == 'yes') ?'selected':''; ?>>Yes</option>
                                            <option value="no" <?=($r1['free_insurance'] == 'no') ?'selected':''; ?>>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="nominee_name">Nominee Name*</label>
                                        <input type="text" name="nominee_name" id="nominee_name" class="form-control" value="<?=$r1['nominee_name']; ?>" required />
                                    </div>

                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="relation_with_nominee">Relation with Nominee*</label>
                                        <input type="text" name="relation_with_nominee" id="relation_with_nominee" class="form-control" value="<?=$r1['relation_with_nominee']; ?>" required />
                                    </div>


                                    <div class="form-group col-md-4 col-sm-6">
                                        <label for="nominee_dob">Nominee Date of Birth*</label>
                                        <input type="date" name="nominee_dob" id="nominee_dob" value="<?php echo ($r1['nominee_dob'] == '00-00-0000') ? '' : $r1['nominee_dob'] ?>" class="form-control" placeholder="dd-mm-yyyy" required />
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" value="Save" class="btn btn-primary col-md-2 col-sm-3" <?php if ($disabled) echo 'disabled'; ?>>Save</button>
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
<script>
    $(document).ready(function () {
        // Function to handle enabling/disabling the spouse name input
        function toggleSpouseNameInput() {
            const marriageStatus = $('#marriage_status').val();
            const spouseNameInput = $('#spouse_name');
            if (marriageStatus === 'Married') {
                spouseNameInput.prop('disabled', false);  // Enable input
                spouseNameInput.prop('required', true);   // Make it required
                spouseNameInput.parent().show();
            }
            else {
                spouseNameInput.prop('disabled', true);   // Disable input
                spouseNameInput.prop('required', false);  // Remove required
                spouseNameInput.val('');                  // Clear the input
                spouseNameInput.parent().hide();
            }
        }

        // Call the function on page load
        toggleSpouseNameInput();

        // Call the function when marriage status changes
        $('#marriage_status').on('change', function () {
            toggleSpouseNameInput();
        });
    });

</script>
<script>
    $(document).ready(function () {
        // Function to handle enabling/disabling the spouse name input
        function toggleNomineeDetailsInput() {
            const freeInsurance = $('#free_insurance').val();
            const nomineeNameInput = $('#nominee_name');
            const relationWithNomineeInput = $('#relation_with_nominee');
            const nomineeDobInput = $('#nominee_dob');
            if (freeInsurance === 'yes') {
                nomineeNameInput.prop('disabled', false);  // Enable input
                nomineeNameInput.prop('required', true);   // Make it required
                nomineeNameInput.parent().show();

                relationWithNomineeInput.prop('disabled', false);  // Enable input
                relationWithNomineeInput.prop('required', true);   // Make it required
                relationWithNomineeInput.parent().show();

                nomineeDobInput.prop('disabled', false);  // Enable input
                nomineeDobInput.prop('required', true);   // Make it required
                nomineeDobInput.parent().show();


            }
            else {
                nomineeNameInput.prop('disabled', true);   // Disable input
                nomineeNameInput.prop('required', false);  // Remove required
                nomineeNameInput.val('');                  // Clear the input
                nomineeNameInput.parent().hide();

                relationWithNomineeInput.prop('disabled', true);   // Disable input
                relationWithNomineeInput.prop('required', false);  // Remove required
                relationWithNomineeInput.val('');                  // Clear the input
                relationWithNomineeInput.parent().hide();

                nomineeDobInput.prop('disabled', true);   // Disable input
                nomineeDobInput.prop('required', false);  // Remove required
                nomineeDobInput.val('');                  // Clear the input
                nomineeDobInput.parent().hide();

            }
        }

        // Call the function on page load
        toggleNomineeDetailsInput();

        // Call the function when marriage status changes
        $('#free_insurance').on('change', function () {
            toggleNomineeDetailsInput();
        });
    });

</script>
  <?php /* ?>
    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>
  <?php */ ?>
</body>

</html>