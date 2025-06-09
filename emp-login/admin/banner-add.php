<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();
$search = true;
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
<!--
<div class="page-loader">
<div class="loader"></div>
</div>
-->
<!--end-page-loader-->

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
                        <h2> Banner For Member</h2>
                        <p>Add Banner For All Member</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <div class="row">
                <div class="col-lg-12">
                    <?php include('include/alert.php'); ?>

                    <form action="banner-addc.php" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Banner Image</label>
                                <input name="banner_pic" type="file" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <input name="submit" value="Save" type="submit" class="btn btn-primary">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="table-panel">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-2 mb-4">Current Banner</div>
                </div>
                <div class="col-lg-12">
                    <?php
                    $query = "SELECT * FROM `banners` WHERE `id` = '1'";
                    $con = $db->connect();
                    $ss = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($ss);
                    if($nsql > 0) {
                        $d = mysqli_fetch_array($ss);
                        ?>
                        <img src="<?php echo "../assets/images/banners/" . $d['image']; ?>" class="img-responsive">
                        <br/>
                        <a href="banner-addc?id=<?php echo hash_encode($d['id']) ?>&submit=delete"
                           class="btn btn-danger btn-xs" style="margin: 2px"><i class="fa fa-trash"></i> DELETE</a>
                        <?php
                    }
                    else {
                        echo "<p class='text-danger'>Not Found</p>";
                    }
                    ?>
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
</body>

</html>