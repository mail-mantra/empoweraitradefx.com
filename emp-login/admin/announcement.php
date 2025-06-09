<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$search = true;

$sql = "SELECT * FROM announcements WHERE id = 1";
$con = $db->connect();
$result_announcements = mysqli_query($con, $sql);
$con->close();
if($result_announcements->num_rows == 0) {
    $description = '';
}
else {
    $arr_announcements = $result_announcements->fetch_assoc();
    $description = $arr_announcements['description'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php include('include/title.php'); ?></title>
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
                        <h2>Announcement</h2>
                        <p>Set Announcement For All Member</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-2 mb-4">News Board</div>
                </div>
                <div class="col-lg-12">
                    <?php include('include/alert.php'); ?>

                    <form id="frmEdit" action="announcementc.php" method="post">
                        <div class="row">
                            <div class="form-group col-md-12 col-sm-6">
                                <label>Add Text*</label>
                                <input type="text" class="form-control" name="description" placeholder="News Text"
                                       value="<?= $description ?>" />
                            </div>
                            <!--<div class="form-group col-md-12 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="scroll" id="scroll">
                                    <label class="form-check-label" for="scroll">
                                        Check for SCROLL
                                    </label>
                                </div>
                            </div>-->
                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" value="Save"
                                        class="btn btn-primary col-md-2 col-sm-3">Save
                                </button>
                            </div>
                        </div>
                    </form>

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
<script src="web-assets/js/particles.min.js"></script>
<script src="web-assets/js/app.js"></script>
</body>
</html>

