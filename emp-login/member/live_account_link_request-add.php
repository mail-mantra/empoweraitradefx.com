<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'live_account_link_request';
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
                            <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                        </div>
                        <div class="caption">
                            <h2>Live Account Link Request</h2>
                            <p>Add New Live Account Link Request</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                        <?php include('include/alert.php'); ?>
                        <form id="frmAdd" action="live_account_link_request-addc.php" method="post"
                            enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="octa_mail_id">Mail Id</label>
                                        <input type="email" name="octa_mail_id" id="octa_mail_id" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="octa_password">Password</label>
                                        <input type="text" name="octa_password" id="octa_password" class="form-control" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="investment">Investment</label>
                                        <input type="number" min="0" step="0.000001" name="investment" id="investment" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="mobile_no">Mobile Number</label>
                                        <input type="tel" minlength="10" maxlength="10" name="mobile_no" id="mobile_no" class="form-control" required />
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="broker_name">Broker Name</label>
                                        <input type="text" name="broker_name" id="broker_name" class="form-control" required />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group text-center">
                                        <button type="submit" name="submit" class="btn btn-primary" value="Submit">
                                            Submit Request
                                        </button>
                                    </div>
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
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>


    <!-- ---- added by milan --- start ---- -->

    <script type="text/javascript" src="//afarkas.github.io/lazysizes/lazysizes.min.js"></script>
    <script type="text/javascript" src="//afarkas.github.io/lazysizes/plugins/progressive/ls.progressive.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script type="text/javascript" src="//milan-sahana.github.io/bootstrap-notify/bootstrap-notify.js" charset="UTF-8"></script>
    <script type="text/javascript" src="//milan-sahana.github.io/bootstrap-notify/custom-notify-5.0.js" charset="UTF-8"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ---- added by milan --- end ---- -->

    <script type="text/javascript">
        $(document).ready(function() {
            $.fn.add_form = function() {
                if ($('#recipiant_name').val() == '') {
                    $.fn.cbn("Recipiant Name Required", 'danger');
                } else {


                    // Show full page LoadingOverlay
                    $.LoadingOverlay("show");

                    var $thisForm = $('#frmAdd');
                    var formData = new FormData($thisForm[0]);
                    // formData.append( 'file', $( '#image' )[0].files[0] );
                    formData.append('response', 'json');

                    $.ajax({
                        type: "POST",
                        url: $thisForm.attr('action'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        //async:false,
                        success: function(result, textStatus, xhr) {

                            // Show full page LoadingOverlay
                            $.LoadingOverlay("hide");
                            // try {
                            try {
                                var obj = JSON.parse(result);
                            } catch (e) {
                                var obj = result;
                            }


                            // $thisForm.find("input[type=text], input[type=date], input[type=password], input[type=email], input[type=number], input[type=url], select, textarea").val("");
                            // $thisForm.find("select option[value='']").prop('selected', 'selected').change();

                            if (obj.status == '0') {
                                $thisForm[0].reset();

                                // alert(obj.message);
                                // $.fn.cbn(obj.message, 'success');
                                Swal.fire({
                                    // position: 'top-end',
                                    icon: 'success',
                                    title: obj.message,
                                    text: obj.ticket_no,
                                    // showConfirmButton: false,
                                    // timer: 5000,
                                }).then((result) => {
                                    // alert('hi');
                                    // if (window.location.href != obj.back) {
                                    //     location.href = obj.back;
                                    // console.log(655);
                                    // } else {
                                    //     location.reload();
                                    // console.log(658);
                                    // }

                                    // console.log(window.location.href);
                                    // console.log(obj.back);
                                });


                                return;

                            } else if (obj.w != '') {
                                // alert(obj.message);
                                $.fn.cbn(obj.message, 'warning');

                            } else {
                                // alert(obj.message);
                                $.fn.cbn(obj.message, 'danger');
                            }

                            /*
                    }
                    catch (e) {
                        $.fn.cbn('Please Refresh the page, and try again');
                    }

                         */
                            $thisForm.find("button[type=submit]").prop("disabled", false);
                        }
                    });

                }
                return false;
            }

            $(document).on("submit", "#frmAdd", function(event) {
                event.preventDefault();
                $.fn.add_form();
            });
        });
    </script>

</body>

</html>