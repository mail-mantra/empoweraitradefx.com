<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$now = now();
$today = today();

$page_name='upi-deposite-report';

$search=false;

if(isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])  && ($_REQUEST['start_date']!='')  && ($_REQUEST['end_date']!='')) {
    $search=true;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd(($_REQUEST['start_date']));
        $end_date = ymd(($_REQUEST['end_date']));
    }
	
	$period = "From " . dmy($start_date) . " To " . dmy($end_date);
	
} else{
    $start_date = '';
    $end_date = '';
	$period = 'All';
}


$search_joining_date='';
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
                            <h2>Recharge Report</h2>
                            <p>View Your Recharge Report</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                            <form id="frmAdd" action="" method="post"> 
                                <div class="row">  
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label>From Date</label>
                                        <input type="date" name="start_date" value="<?php if(isset($_REQUEST['start_date'])){ echo $start_date; } ?>" class="form-control" />
                                    </div>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label>To Date</label>
                                        <input type="date" name="end_date" value="<?php if(isset($_REQUEST['end_date'])){ echo $end_date; } ?>" class="form-control" />
                                    </div>  
                                    
                                    
                                    <?php /* ?>
                                    <div class="form-group col-md-3 col-sm-6">
                                        <label>Profit Sharing</label>
                                        <select class="form-control" name="roi_allow">
                                            <option value="">All</option>
                                            <option value="yes" <?php echo ($roi_allow == "yes") ? "selected" : "" ?>>Yes</option>
                                            <option value="no" <?php echo ($roi_allow == "no") ? "selected" : "" ?>>No</option>
                                        </select>
                                    </div> 
                                    <?php */ ?>
                                    
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3">Search</button>
                                        <?php
                                        if($search){
                                        ?>
                                        <a href="upi-deposite-report" class="btn btn-danger col-md-2 col-sm-3">Show All</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">    
                <?php
			    if (isset($_GET['cp'])) {
					$cn = $_GET['cp'];
				} else {
					$cn = 1;
				}
				$uplim = 100;
				$lowlim = $uplim * ($cn - 1);
				$no = $lowlim;
  
				
                $sql_member = " AND b.mem_code='".$user_code."'";
                
                if(!empty($start_date) && !empty($end_date)){
                    $search_with_date_range = " AND (DATE(`a`.`date_time`) BETWEEN '".$start_date."' AND '".$end_date."') ";
                }
                else{
                    $search_with_date_range = "";
                }
				
                
			 
			   $query = "select a.*,b.mem_code, c.mem_name from callback_bh_payment a 
			             inner join member_vpa_account b on b.virtual_account_id=a.virtual_account_id
			             inner join member_details c on c.member_id=b.member_id
			             $sql_member  $search_with_date_range ";
			   
			   $con=$db->connect();
               $ss = mysqli_query($con,"$query order by a.date_time desc");
               $db->dbDisconnet($con);
               $t = mysqli_num_rows($ss);
               $tot_page = ceil($t / $uplim);
               $con=$db->connect();
               $sql = mysqli_query($con,"$query order by a.date_time desc limit $lowlim,$uplim");
               $db->dbDisconnet($con);
               $nsql = mysqli_num_rows($sql);
               if($nsql>0)
               {
			    ?>
                    <div class="col-lg-12">                	
                    <div class="table-responsive">
                    	<table class="table table-bordered table-hover table-dark">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Date</th>
                                <th>TXN ID</th>
                                <th>Amount</th>
                                <th>Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							//$i=0;
							$page_tot=0;
                            while($r1=mysqli_fetch_assoc($sql))
                            {
                                $no++;
                                $page_tot += $r1['amount'];
                            ?>
                            <tr>
                              <td><?php echo $no; ?>.</td>
                              <td>
                                 <?php echo dmy_time($r1['date_time']); ?>
                              </td>
                              <td>
                                  <?php echo $r1['txn_code']; ?>
                                 
                              </td>
                              
                              <td><?php echo $r1['amount']; ?></td>
                              <td><?php echo $r1['status']; ?></td>
                             
                              
                            </tr>
                            <?php
                            }
                            /*$all_tot=0;
                            while($r2=mysqli_fetch_assoc($ss))
                            {
                                $all_tot += $r2['amount'];
                            }
                            ?>
                            <tr style="font-weight: bold">
                                <td colspan="6" class="text-right">Page Total</td>
                                <td>
                                        USD <?php echo number_format($page_tot, 2); ?><br/>
                                        INR <?php echo number_format($page_tot*77,2); ?>
                                    </td>
                            </tr>
                            <tr style="font-weight: bold">
                                <td colspan="6" class="text-right">All Total</td>
                                <td> USD <?php echo number_format($all_tot, 2); ?><br/>
                                     INR <?php echo number_format($all_tot*77,2); ?>
                                </td>
                            </tr>
                            <?php */ ?>
                        </tbody>
                        </table>
                        
                        
                        <ul class="pagination">
                            <?php
                            if ($cn != 1) {
                                $prev = $cn - 1;
                                $first = 1;
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&roi_allow=<?php echo $roi_allow ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&roi_allow=<?php echo $roi_allow ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&roi_allow=<?php echo $roi_allow ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
                                        echo
                    
                                        "$i";
                                        ?></a></li>
                                    <?php
                                } else {
                                    ?>		
                                    <li class="page-item active"><a class="page-link" href="#"><?php echo "$i"; ?></a></li>
                                    <?php
                                }
                            }
                            $next = $cn + 1;
                            if ($tot_page >= $next) {
                                ?>	
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&roi_allow=<?php echo $roi_allow ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&mem_code=<?php echo ($mem_code); ?>&start_date=<?php echo ($start_date); ?>&end_date=<?php echo ($end_date); ?>&roi_allow=<?php echo $roi_allow ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                
                <?php
				}
				else
				{
					echo '<div class="col-lg-12"><br><br><div class="alert alert-info"  role="alert">No records found...!</div></div>';
				}
				?> 
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