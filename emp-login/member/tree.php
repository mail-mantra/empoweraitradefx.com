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

<link rel="stylesheet" href="../web-assets/css/custom.css">
<style>
.form-panel {background: none !important; }
</style>
</head>
<body>
<!-- particles -->
<div id="particles-js"></div>

<!--start-page-loader-->
<div class="page-loader">
	<div class="loader"></div>
</div>
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
                        <h2>Tree</h2>
                        <p>Tree</p>
                    </div>
                </div>
            </div>
        </div>
        
         <?php include('include/alert.php'); ?>
         
        <div class="form-panel">
            <div class="row">   
                <div class="col-lg-12 tree"> 
                    
                    <ul>    
                        <li>
                        	<?php
							if(isset($_REQUEST['mem_code']))
							{
								$con=$db->connect();
								foreach($_REQUEST as $key => $value){
								  $data[$key] = prevent_injection($con,$value);
								}
								$db->dbDisconnet($con);
								$mem_code = $data['mem_code'];
								
							}
							else
							{
								$mem_code  = $user_code;
							}
							
							$con=$db->connect();
							$q=mysqli_query($con,"select m1.intro_code,m1.name from member m1 inner join member_login m2 on m1.member_id=m2.member_id where m1.mem_code='".$mem_code."'");
							$db->dbDisconnet($con);
							
	
							
							$d=mysqli_fetch_array($q);
						
							//$intro_code=$d['intro_code'];
							
							if($mem_code=='admin'){
								$name='admin';
							}else{
								$name=$d['name'];
							}
							
							
							$con=$db->connect();
							$q1=mysqli_query($con,"select m1.member_id,m1.mem_code,m1.name from member m1 inner join member_login m2 on m1.member_id=m2.member_id where m1.intro_code='".$mem_code."'");
							$db->dbDisconnet($con);
						
							
							?>
                            <a href="#" class="treetop"><i class="fa fa-user"></i><br/><?php echo $mem_code; ?></a><br><a href="#"><?php echo $name; ?></a> 
                            <ul>
                            	<?php
								while($r1=mysqli_fetch_assoc($q1))
								{
									//$intro_code1=$r1['intro_code'];
									$member_id1=$r1['member_id'];
									$mem_code1=$r1['mem_code'];
									$name1=$r1['name'];
									
									$con=$db->connect();
									$is_active=member_is_active($con,$member_id1);
									$db->dbDisconnet($con);
								?>
                                <li><a href="#" data-id="<?php echo $mem_code1; ?>" class="<?php if($is_active==1){ ?>acitve<?php }else{ ?>inacitve<?php } ?> amtooltip"><i class="fa fa-user"></i><br/><?php echo $mem_code1; ?></a><br><a href="tree?mem_code=<?php echo $mem_code1; ?>"><?php echo $name1; ?></a></li>
                                <?php
								}
								?>
                               <!-- <li><a href="#" class="acitve" data-toggle="tooltip" data-placement="bottom" title="Girija Prasad"><i class="fa fa-user"></i><br/>Girija Prasad</a><br><a href="#">CL351862</a></li>
                                <li><a href="#" class="acitve" data-toggle="tooltip" data-placement="bottom" title="Nalin Behary"><i class="fa fa-user"></i><br/>Nalin Behary</a><br><a href="#">CL351862</a></li> 
                                <li><a href="#" class="inacitve" data-toggle="tooltip" data-placement="bottom" title="sourav Behary"><i class="fa fa-user "></i><br/>sourav Behary</a><br><a href="#">CL351862</a></li> -->                                                                                                                                                       
                            </ul>                                                                                    
                        </li>
                    </ul>
                        
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

