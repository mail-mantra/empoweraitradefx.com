<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();
$today = today();
$eligible=false;

$con=$db->connect();
$q1 = mysqli_query($con,"select DATE(created_on) AS dop1  from member_package_update where member_id='".$user_id."'");
$db->dbDisconnet($con);
if(mysqli_num_rows($q1)==1){
	$r1 = mysqli_fetch_assoc($q1);
	$dop1 = $r1['dop1'];
	if(date_diffence($today,$dop1)<=15){
	    $eligible=true;
	}
}

$con=$db->connect();
$q2 = mysqli_query($con,"select DATE(created_on) AS dop2  from member_signal_package_update where member_id='".$user_id."'");
$db->dbDisconnet($con);
if(mysqli_num_rows($q2)==1){
	$r2 = mysqli_fetch_assoc($q2);
	$dop2 = $r2['dop2'];
	if(date_diffence($today,$dop2)<=30){
	    $eligible=true;
	}
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
                        <h2>Signal</h2>
                        <p>View Our Signals</p>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="form-panel">
        	<div class="row">
        	    
                <div class="col-lg-12">     
                    <?php include('include/alert.php'); ?>
                    
                        <?php
                        if($eligible){
                            if (isset($_GET['cp'])) {
                                $cn = $_GET['cp'];
                            } else {
                                $cn = 1;
                            }
                            $uplim = 50;
                            $lowlim = $uplim * ($cn - 1);
                            $no = $lowlim;
        
            
               		    	$query="select * from signal_data order by id desc";
            			
        					$con=$db->connect();
        					$ss = mysqli_query($con,$query);
        					$db->dbDisconnet($con);
        					$t = mysqli_num_rows($ss);
        					$tot_page = ceil($t / $uplim);
        					$con=$db->connect();
        					$sql = mysqli_query($con,$query." limit $lowlim,$uplim");
        					$db->dbDisconnet($con);
        		
        					$nsql = mysqli_num_rows($sql);
        				
                            if ($nsql == 0){
                                ?>
                                <br><br><div class="alert alert-info">No Signal Found</div>
                                <?php
                            } else {
                            
                            ?>
                               <br><br>    
                               <div class="panel panel-default">
                               <table class="table table-bordered table-hover table-dark">
                                 <thead>
                                    <tr>
                                        <th width="2%">Sl.No.</th>
                                        <th>Trade Direction</th> 
                                        <th>Currency Pair</th>
                                        <th>Price</th>
                                        <th>Take Profit 1</th>
                                        <th>Take Profit 2</th>
                                        <th>Stop Loss</th>
                                        <th>Reference ID</th>
                                        <th>Result</th>
                                        <th>Date</th>

                                    </tr>
                                  </thead>
                                  <tbody>
        
                                <?php
                                while ($a = mysqli_fetch_array($sql)) {
                                    
                                    $no++;				 
                                    ?>
                                    <tr>     
                                        <td><strong><?php echo $no; ?>.</strong></td>
                                        <td><?php echo $a['trade_direction']; ?></td>
                                        <td><?php echo $a['currency_pair']; ?></td> 
                                        <td><?php echo $a['price']; ?></td> 
                                        <td><?php echo $a['take_profit_1']; ?></td> 
                                        <td><?php echo $a['take_profit_2']; ?></td> 
                                        <td><?php echo $a['stop_loss']; ?></td> 
                                        <td><?php echo $a['reference_id']; ?></td>
                                        <td><?php echo $a['result']; ?></td>
                                        <td><?php echo dmy($a['created_on']); ?></td>
                                      
                                    </tr>
                                    <?php
                                }
                                ?>
        
        
                                  </tbody>
        
                                </table>
                               </div>
                               
                               <div class="col-md-12 text-center">
                                    <ul class="pagination">
                                        <?php
                                        if ($cn != 1) {
                                            $prev = $cn - 1;
                                            $first = 1;
                                            ?>
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>" title="First Page"><?php echo "<<"; ?></a></li>
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>" title="Previous Page"><?php echo "<"; ?></a></li>
                                            <?php
                                        }
                                        $limit = ($tot_page - $cn);
                                        if ($cn > 5) {
                                            $st_page = $cn - 4;
                                            if ($limit < 4) {
                                                $en_page = $cn + $limit;
                                            } else {
                                                $en_page = $cn + 4;
                                            }
                                        } else {
                                            $st_page = 1;
                                            if ($limit > 4) {
                                                $en_page = $cn + 4;
                                            } else {
                                                $en_page = $cn + $limit;
                                            }
                                        }
                                        for ($i = $st_page; $i <= $en_page; $i++) {
                                            if ($cn != $i) {
                                                ?>		
                                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>" title="Page No. <?php echo $i; ?>"><?php echo $i; ?></a></li>
                                                <?php
                                            } else {
                                                ?>		
                                                <li class="page-item active"><a class="page-link" href="#"><?php echo $i; ?></a></li>
                                                <?php
                                            }
                                        }
                                        $next = $cn + 1;
                                        if ($tot_page >= $next) {
                                            ?>	
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>" title="Next Page"><?php echo ">"; ?></a></li>
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>" title="Last Page"><?php echo ">>"; ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php
                            }
                        }else{
                            echo "<div class='alert alert-warning' role='alert'>Sorry ! You are not eligible for this feature</div>";
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