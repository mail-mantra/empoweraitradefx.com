<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$sql_education_photo = "SELECT * FROM `foundations` WHERE  (`deleted`=0) ORDER BY `display_order` asc, id desc ";
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
                            <h2>All Foundation Photos</h2>
                            <p>View all Foundation Photos</p>
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
                                        <div class="card m-0" style="width: 100%; display: flex; flex-direction: column; align-items: center;">

                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                <?php
                                                if (!empty($arr_education_photo['img'])) {
                                                ?>
                                                    <img data-src="<?= WRITABLE_URL; ?>uploads/foundation_photos/<?= $arr_education_photo['img']; ?>"
                                                        class="lazyload blur-up img-thumbnail" style="max-width: 100%; height: auto;" data-fancybox
                                                        alt="Loading..." />
                                                    <?php } else if (!empty($arr_education_photo['video_url'])) {
                                                    $video_id = '';
                                                    if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $arr_education_photo['video_url'], $matches)) {
                                                        $video_id = $matches[1];
                                                    }
                                                    if ($video_id) {
                                                    ?>
                                                        <iframe width="100%" height="auto" style="aspect-ratio: 16/9;" src="https://www.youtube.com/embed/<?= $video_id; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                                                <?php }
                                                } ?>
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