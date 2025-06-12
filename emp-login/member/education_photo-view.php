<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$sql_education_photo = "SELECT * FROM `education_photos` WHERE  (`deleted`=0) ORDER BY `display_order` asc, id desc ";
$con = $db->connect();
$res_education_photo = $con->query($sql_education_photo);
$con->close();

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
                            <h2>All Education Photos</h2>
                            <p>View all Education Photos</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-panel">
                <div class="row">

                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>

                        <div class="row d-flex flex-wrap">
                            <?php
                            if ($res_education_photo->num_rows) {
                                $n = 0;
                                while ($arr_education_photo = $res_education_photo->fetch_assoc()) {
                                    $n++;
                            ?>
                                    <div class="col-md-4 d-flex mb-4">
                                        <div class="card m-0">
                                            <?php
                                            if (strpos($arr_education_photo['img'], 'https://youtu.be/') === 0) {
                                                $youtube_id = explode('?v=', str_replace('https://youtu.be/', '', $arr_education_photo['img']))[0];
                                            ?>
                                                <iframe width="auto" height="150" src="https://www.youtube.com/embed/<?= $youtube_id; ?>" frameborder="0" allowfullscreen></iframe>
                                            <?php
                                            } elseif (strpos($arr_education_photo['img'], 'https://') === 0) {
                                            ?>
                                                <iframe width="200" height="150" src="<?= $arr_education_photo['img']; ?>" frameborder="0" allowfullscreen></iframe>
                                            <?php
                                            } else {
                                                if (!defined('WRITABLE_URL')) {
                                                    define('WRITABLE_URL', 'your_base_url_here'); // Replace with your actual base URL
                                                }
                                            ?>
                                                <img data-src="<?= WRITABLE_URL; ?>uploads/education_photos/<?= $arr_education_photo['img']; ?>"
                                                    class="card-img-top lazyload blur-up img-thumbnail" width="200"
                                                    data-fancybox
                                                    alt="Loading..." />
                                            <?php
                                            }
                                            ?>
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <?= nl2br($arr_education_photo['title_text']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                $_SESSION['w'] = 'No record Found';
                                include 'include/alert.php';
                            }
                            ?>


                        </div>


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