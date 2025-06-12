<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php include('include/title.php'); ?></title>

<?php include('include/header-common-file.php'); ?>

<style>
.form-panel {background: none !important; background-image:  none !important; }
</style>
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
                            <h2>Genealogy Tree</h2>
                            <p>Genealogy Tree View</p>
                        </div>
                    </div>
                </div>
            </div>

        
         <?php include('include/alert.php'); ?>
         
        <div class="form-panel">
            <div class="row">  
                <div class="col-lg-12">
                    <iframe src="new_tree/index_genealogy?id=<?php echo hash_encode($user_code); ?>&role=<?php echo hash_encode($user_type);?>&log_mem_code=<?php echo hash_encode($user_code);?>" width="1000px" height="1000px" frameborder="0" scrolling="auto">
    				</iframe>
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