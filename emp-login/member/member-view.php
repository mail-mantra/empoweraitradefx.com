<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con=$db->connect();
$direct_downlineCount = direct_downlineCount($con,$user_code);
$total_downlineCount = total_downlineCount($con,$user_code);
$db->dbDisconnet($con);

if(isset($_POST['mem_code'])) {
	$mem_code = trim($_POST['mem_code']);
	
}else{
	
	$mem_code='';
}

if(isset($_POST['mem_name']))
{
	$mem_name=$_POST['mem_name'];
	
}else{
	
	$mem_name='';
}
							
if(isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])  && ($_REQUEST['start_date']!='')  && ($_REQUEST['end_date']!='')) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $start_date = ymd($_REQUEST['start_date']);
        $end_date = ymd($_REQUEST['end_date']);
    } else if($_SERVER['REQUEST_METHOD'] == 'GET') {
        $start_date = ymd(base64_decode($_REQUEST['start_date']));
        $end_date = ymd(base64_decode($_REQUEST['end_date']));
    }
	
	$period = "From " . dmy($start_date) . " To " . dmy($end_date);
	
} else{
    $start_date = '';
    $end_date = '';
	$period = 'All';
}

$search_joining_date='';
$search_by = '';
$mem_name_id = '';
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
                        <h2>List of Members</h2>
                        <p>Member List</p>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="table-panel">
        	<div class="row">
            	<div class="col-lg-12">
                    <div class="title-2 mb-4">
                        <button type="button" class="btn btn-info">
                            My Direct : <span class="badge badge-light"><?php echo $direct_downlineCount; ?></span>
                        </button>
                        <button type="button" class="btn btn-warning">
                            My Team : <span class="badge badge-light"><?php echo $total_downlineCount; ?></span>
                        </button>
                    </div>
                </div>
       
                <div class="col-lg-12">
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="post"> 
                    	<div class="row">  
                    	    <div class="form-group col-md-4 col-sm-6">	
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                        <input type="radio" required  <?php if(!((isset($_REQUEST['search_by'])) && (($_REQUEST['search_by']=='mem_name') || ($_REQUEST['search_by']=='mem_mobile')))) { ?>checked="checked"<?php } ?>  name="search_by" value="mem_code" class="form-check-input"  />
                                        Member Code
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <label class="form-check-label">
                                    <input type="radio" required  <?php if(!((isset($_REQUEST['search_by'])) && (($_REQUEST['search_by']=='mem_name') || ($_REQUEST['search_by']=='mem_mobile')))) { ?>checked="checked"<?php } ?>  name="search_by" value="mem_code" class="form-check-input"  />
                                        Member Name
                                    </label>
                                </div>
                            </div>  
                            <div class="form-group col-md-4 col-sm-6">
                            <label>Member Code/Name</label>
                            <input type="text" name="mem_name_id" class="form-control"  value="<?php if((isset($_REQUEST['mem_name_id'])) && ($_REQUEST['mem_name_id']!='')) { echo $_REQUEST['mem_name_id']; } ?>" />
                            </div>
                        </div>    
                        <div class="row">  
                            <div class="form-group col-md-4 col-sm-6">
                            <label>Joining Date</label>
                            <input type="date" name="start_date" value="<?php if(isset($_REQUEST['start_date'])){ echo $start_date; } ?>" class="dp-1 datepicker form-control" placeholder="From Date" />
                            </div>
                            <div class="form-group col-md-4 col-sm-6">
                            <label>&nbsp;</label>
                            <input type="date" name="end_date" value="<?php if(isset($_REQUEST['end_date'])){ echo $end_date; } ?>" class="dp-1 datepicker form-control" placeholder="To Date" />
                            </div>                     
                             
                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3">Search</button>
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
				$uplim = 50;
				$lowlim = $uplim * ($cn - 1);
				$no = $lowlim;
				
				if(isset($_REQUEST['mem_name_id']) && ($_REQUEST['mem_name_id']!='') && isset($_REQUEST['search_by']) && ($_REQUEST['search_by']!='')){
					$mem_name_id = trim($_REQUEST['mem_name_id']);
					$search_by = trim($_REQUEST['search_by']);
				
					if(($_REQUEST['search_by']=='mem_code')){
						
						$con=$db->connect();
						$mc = member_code($con,$mem_name_id);
						$db->dbDisconnet($con);	
					
						$query_search_by = " AND (`m1`.`mem_code` like '".$mem_name_id."') ";
					}
					elseif(($_REQUEST['search_by']=='mem_name')){
						$query_search_by = " AND (`m1`.`name` like '%".$mem_name_id."%') ";
					}
					else{
						$query_search_by = '';
					}
				}
				else{
					$query_search_by = '';
				} 
			
				if(isset($_REQUEST['search_joining_date']) && ($_REQUEST['search_joining_date']!='') && ($_REQUEST['search_joining_date']=='yes')){
					$search_joining_date = 'yes';
					$search_with_date_range = " AND (`m1`.`doj` BETWEEN '".$start_date."' AND '".$end_date."') ";
				}
				else{
					$search_joining_date='';
					$search_with_date_range = "";
				}
				
				$con=$db->connect();
                $gm = member_code($con,$user_code);
                $db->dbDisconnet($con);
                
                $intro_level = $gm['intro_level'];
                
                $query = "select m1.*,m2.*,m3.state,m3.city,m3.pin from member m1 
                        inner join member_login m2 on m1.member_id=m2.member_id
						 inner join member_details m3 on m1.member_id=m3.member_id 
						 where 1 ".$query_search_by.$search_with_date_range;

                $query = "select m1.*,m2.*,m3.state,m3.city,m3.pin, pu1.created_on AS member_package_update_on
						from member m1 
						inner join member_login m2 on m1.member_id=m2.member_id 
						inner join member_details m3 on m1.member_id=m3.member_id 
						LEFT JOIN `member_package_update` AS pu1 on m1.member_id= pu1.member_id 
						where m1.intro_mtree like '%$user_code%' ".$query_search_by.$search_with_date_range;
						
                $con=$db->connect();
                $ss = mysqli_query($con,"$query order by m1.member_id desc");
                $db->dbDisconnet($con);
                $t = mysqli_num_rows($ss);
                $tot_page = ceil($t / $uplim);
                $con=$db->connect();
                $sql = mysqli_query($con,"$query order by m1.member_id desc limit $lowlim,$uplim");
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
                                <th>Member Code</th>
                                <th>Name</th>
                                <th>Intro Code</th>
                                <th>Joining Date</th>
                                <th>Activation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while($r1=mysqli_fetch_assoc($sql))
                            {
                                $no++;
								$member_id = $r1['member_id'];
								$mem_code = $r1['mem_code'];
                            ?>
                                <tr>
                                    <td><?php echo $no; ?>.</td>	
                                    <td><?php echo $mem_code; ?></td>
                                    <td><?php echo $r1['name'];?></td>
                                    <td><?php echo $r1['intro_code']; ?></td>
                                    <td><?php echo dmy($r1['doj']); ?></td>
                                    <td><?php echo ($r1['member_package_update_on'])?dmy($r1['member_package_update_on']):''; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        </table>
                        
                        
                        <ul class="pagination">
                            <?php
                            if ($cn != 1) {
                                $prev = $cn - 1;
                                $first = 1;
                                ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                    <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&search_by=<?php echo $search_by; ?>&mem_name_id=<?php echo $mem_name_id; ?>&search_joining_date=<?php echo $search_joining_date; ?>&start_date=<?php echo base64_encode($start_date); ?>&end_date=<?php echo base64_encode($end_date); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    </div>

                <?php
                } else {
                    echo '<div class="col-lg-12"><div class="alert alert-info"  role="alert">No records found...!</div></div>';
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
<script src="web-assets/js/particles.min.js"></script>
<script src="web-assets/js/app.js"></script>

</body>
</html>

