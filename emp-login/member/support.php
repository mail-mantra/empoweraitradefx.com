<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'support';
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
    <!-- <div class="page-loader">
	<div class="loader"></div>
</div> -->
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
                            <i class="fa fa-rocket" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Support System</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <form id="frmAdd" action="supportc" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Categories</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Please Select One Support Category</option>
                                        <?php
                                        $con = $db->connect();
                                        $q = mysqli_query($con, "SELECT * FROM `support_category` WHERE 1 ORDER BY sup_cat_body");
                                        $db->dbDisconnet($con);
                                        if (mysqli_num_rows($q) >= 1) {
                                            while ($row = mysqli_fetch_assoc($q)) {
                                        ?>
                                                <option value="<?php echo $row['sup_cat_id']; ?>"><?php echo $row['sup_cat_body']; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Description</label>
                                    <textarea name="description" rows="3" class="form-control" placeholder="Please Write Your Query In detail" required></textarea>
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Upload The supportive Image/Screenshot</label>
                                    <input type="file" name="screenshots" class="form-control" />
                                </div>

                                <div class="col-md-12">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- show -->
                        <!-- compain list -->
                        <?php
                        $query = "SELECT `support_query`.*, `support_category`.`sup_cat_body` FROM `support_query` INNER JOIN `support_category` ON `support_query`.`sup_cat_id`=`support_category`.`sup_cat_id` WHERE `support_query`.`member_id`= '$user_id' ORDER BY `support_query`.`sup_query_id` DESC";

                        $con = $db->connect();
                        $sql = mysqli_query($con, $query);
                        $db->dbDisconnet($con);
                        $nsql = mysqli_num_rows($sql);
                        if ($nsql > 0) {
                        ?>
                            <div id="accordion">
                                <?php
                                $no = 1;
                                while ($d = mysqli_fetch_array($sql)) {
                                ?>
                                    <!-- acc -->
                                    <div class="card" id="commentDiv_<?php echo $no; ?>" style="margin-bottom:20px;">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn text-dark" data-toggle="collapse" data-target="#collapse<?php echo $no; ?>" aria-expanded="true" aria-controls="collapse<?php echo $no; ?>">
                                                    <?php echo $d['sup_cat_body']; ?> &nbsp;
                                                    <span class="badge <?php if (!$d['status']) echo 'badge-success';
                                                                        else echo 'badge-danger' ?>  pull-right">
                                                        <?php if ($d['status']) {
                                                            echo "Unsolved";
                                                        } else {
                                                            echo "Solved";
                                                        } ?>
                                                    </span>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapse<?php echo $no; ?>" class="collapse " aria-labelledby="heading<?php echo $no; ?>" data-parent="#accordion">
                                            <div class="card-body text-dark">
                                                <!-- comment List -->
                                                <h5>
                                                    <b>Token: #<?php echo base64_encode(sprintf('%09d', $d['sup_query_id'])); ?></b>

                                                </h5>
                                                <!--<h5 class="media-heading"><b>Query :</b> </h5>-->
                                                <p><?php echo $d['sup_query_desc']; ?></p>
                                                <?php
                                                if (!empty($d['screenshots_url'])) {
                                                ?>
                                                    <p>
                                                        <i class="fa fa-image fa-2x text-info" title="View Supported Image/Screenshot" data-fancybox data-src="../assets/images/screenshots/<?= $d['screenshots_url'] ?>" style="cursor: pointer;"></i>
                                                    </p>
                                                <?php
                                                }
                                                ?>
                                                <hr style="border-top: 1px solid #ccc;">
                                                <!-- dynamic comment -->
                                                <?php
                                                $complain_id = $d['sup_query_id'];
                                                $query_comment = "SELECT `sup_query_ans_id`, `sup_query_ans_desc`, `sup_query_ans_image`,`created_by`, `created_on` FROM `support_query_answers` WHERE `sup_query_id` = '$complain_id'";
                                                $con = $db->connect();
                                                $sql_comment = mysqli_query($con, $query_comment);
                                                $db->dbDisconnet($con);
                                                $nsql_comment = mysqli_num_rows($sql_comment);
                                                if ($nsql_comment <= 0) {
                                                    if ($d['status']) {
                                                        $text = "Please wait..! Our support executive will resolve this query soon.";
                                                        $icon = 'clock-o';
                                                        $text_color = 'text-danger';
                                                    } else {
                                                        $text = "Your query is solved. Thank you!";
                                                        $icon = 'check';
                                                        $text_color = 'text-success';
                                                    }
                                                ?>
                                                    <!-- <p class="text-danger">No comments</p> -->
                                                    <div class="text-center <?php echo $text_color ?>">
                                                        <i class="fa fa-<?php echo $icon ?> fa-2x"></i>
                                                        <h5><?php echo $text; ?></h5>
                                                    </div>
                                                    <?php
                                                } else {
                                                    while ($data_comment = mysqli_fetch_array($sql_comment)) {
                                                    ?>
                                                        <?php
                                                        if ($data_comment['created_by'] == 'admin') {
                                                            $image_name = 'ADMIN';
                                                            $image_color = 'text-success';
                                                        } else {
                                                            $image_name = 'YOU';
                                                            $image_color = 'text-primary';
                                                        }
                                                        ?>
                                                        <div class="card mb-2">
                                                            <div class="card-body">
                                                                <p class="<?php echo $image_color ?>"><?php echo $image_name ?></p>
                                                                <p><?php echo $data_comment['sup_query_ans_desc']; ?></p>
                                                                <?php
                                                                if (!empty($data_comment['sup_query_ans_image'])) {
                                                                ?>
                                                                    <p>
                                                                        <i class="fa fa-image fa-2x text-info" title="View Supported Image/Screenshot" data-fancybox data-src="../assets/images/screenshots/<?= $data_comment['sup_query_ans_image'] ?>" style="cursor: pointer;"></i>
                                                                    </p>
                                                                <?php
                                                                }
                                                                ?>
                                                                <small class="pull-right text-info"><?php echo $data_comment['created_on'] ?></small>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    if ($d['status']) {
                                                    ?>
                                                        <!-- <div class="row"> -->
                                                        <form name="comment-form_<?php echo $no ?>" id="comment_form_<?php echo $no ?>" action="#" method="post" style="margin-top: 20px;padding-bottom: 40px;">
                                                            <!-- <div class="col-md-12"> -->
                                                            <input type="hidden" name="complain_id_<?php echo $no ?>" id="complain_id_<?php echo $no ?>" value="<?php echo $d['sup_query_id'] ?>">
                                                            <div class="form-group">
                                                                <label for="comment">Comment:</label>
                                                                <textarea class="form-control" rows="3" name="comment_<?php echo $no ?>" id="comment_<?php echo $no ?>" placeholder="Your Commnet" onkeyup='checkEmpty(<?php echo $no ?>)' required></textarea>
                                                            </div>
                                                            <button type="button" id="submit_comment_<?php echo $no ?>" name="submit_comment" class="btn btn-info btn-xs pull-right" onclick="submitForm(<?php echo $no ?>)" disabled><i class="fa fa-reply"></i> Reply</button>
                                                            <!-- </div> -->
                                                        </form>
                                                        <!-- </div> -->
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="text-center text-success">
                                                            <i class="fa fa-check fa-2x"></i>
                                                            <h5>This Query is Resolved. Thank You!</h5>
                                                        </div>
                                                <?php
                                                    }
                                                }
                                                ?>
                                                <!-- /comment list -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- .acc -->
                                    <?php
                                    ?>

                                <?php
                                    $no++;
                                }
                                ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- /show -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end-body-content-->

    <!--start-mm-footer-->
    <?php include('include/mm-footer.php'); ?>
    <!--end-mm-footer-->

    <script>
        $('body').on('blur', '#mem_code', function() {
            member_check($(this).val());
        });

        function submitForm(id) {
            var comment = $('#comment_' + id).val();
            var complain_id = $('#complain_id_' + id).val();
            $("#submit_comment_" + id).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "supportc.php",
                data: {
                    complain_id: complain_id,
                    comment: comment,
                    submit: 'comment'
                },
                cache: !1,
                success: function(a) {
                    var s = JSON.parse(a);
                    if (s.status) {
                        $("#collapse" + id).load(location.href + " #collapse" + id + ">*", "");
                    } else {
                        $("#collapse" + id).load(location.href + " #collapse" + id + ">*", "");
                    }
                    $("#submit_comment_" + id).prop('disabled', false);
                }
            })
        }

        function checkEmpty(id) {
            var data = $('#comment_' + id).val();
            if (data != '') {
                $('#submit_comment_' + id).prop('disabled', false);
            } else {
                $('#submit_comment_' + id).prop('disabled', true);
            }
        }
    </script>
    <!-- particles -->
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>

</html>