<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

$now=now();

/*$con=$db->connect();
$total_direct = direct_refaralCount($con, $user_code);
$active_direct = active_direct_count($con, $user_id);
$db->dbDisconnet($con);	
$inactive_direct = $total_direct - $active_direct;*/

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
                        <h2>Level View</h2>
                        <p>Level View List</p>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="table-panel">
            <?php /* ?><div class="row">
                <div class="col-lg-12">
                    <div class="title-2 mb-4">
                        <a href="#" class="btn btn-sm btn-warning">Total Direct <span class='badge badge-primary'><?php echo $total_direct; ?></span></a>
                        <a href="#" class="btn btn-sm btn-success">Active Direct <span class='badge badge-danger'><?php echo $active_direct; ?></span></a>
                        <a href="#" class="btn btn-sm btn-danger">Inactive Direct <span class='badge badge-warning'><?php echo $inactive_direct; ?></span></a>
                    </div>
                </div>
            </div><?php */ ?>
            
            <div class="row">    
                <?php
               if($user_code=='admin'){
                   $level=0;
               }else{
                $con=$db->connect();
                $q1=mysqli_query($con,"select intro_level from member where mem_code='".$user_code."'");
                $db->dbDisconnet($con);	
                $r1=mysqli_fetch_array($q1);
                $level=$r1['intro_level'];
               }
                /*$con=$db->connect();
                $q2=mysqli_query($con,"select max(intro_level) AS max_level from member where intro_mtree like '%".$user_code."%'");
                $db->dbDisconnet($con);
                $r2=mysqli_fetch_array($q2);
                $max_level=$r2['max_level'];*/
                ?>
                    <div class="col-lg-12">                	
                    <div class="table-responsive">
                    	<table class="table table-bordered table-hover table-dark">
                        <thead>
                            <tr class="text-center">
                                <th>Level</th>
                                <th>Total ID</th>
                                <th>Active ID</th>
                                <th>Inactive ID</th>
                                <!--<th>Level %</th>-->
                                <th>Business</th>
                                <!--<th>Estimated Income</th>
                                <th>Level Open</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $con=$db->connect();
                            for($i=1;$i<=30;$i++)
                            {
                                //$n=$i+1;
                                $cal_level = $level + $i;
                                
                                $sql1 = mysqli_query($con,"SELECT `child_count_levelwise`('$user_code', '$cal_level','2023-01-01','$current_date') AS `child_count_levelwise`");
                                $d1 = mysqli_fetch_array($sql1);
                                $level_join_count = $d1['child_count_levelwise'];
                                
                                $sql2 = mysqli_query($con,"SELECT `active_child_count_levelwise`('$user_code', '$cal_level','2023-01-01','$current_date') AS `active_child_count_levelwise`");
                                $d2 = mysqli_fetch_array($sql2);
                                $level_active_count = $d2['active_child_count_levelwise'];
                                
                                $sql3 = "SELECT `child_business_levelwise`('$user_code', '$cal_level','$now') AS `child_business_levelwise`";
                                $q3 = mysqli_query($con,$sql3);
                                $d3 = mysqli_fetch_array($q3);
                                $level_business = $d3['child_business_levelwise'];
                                
                                /*$sql4 = mysqli_query($con,"SELECT level_percentage, required_direct from level_income_list where id='$i'");
                                $d4 = mysqli_fetch_array($sql4);
                                $level_percentage = $d4['level_percentage'];
                                $required_direct = $d4['required_direct'];
                                
                                $estimated_income = ($level_business * $level_percentage)/100;*/
                                
                                /*if($active_direct >= $required_direct){
                                    $level_open = "<span class='badge badge-success'>Yes</span>";
                                }else{
                                    $level_open = "<span class='badge badge-danger'>No</span><br><span class='small'>Required ".$required_direct."</span>";
                                }
                                
                                $sql4 = mysqli_query($con,"SELECT IS_WORKING_BLOCK('$user_id') AS var_is_block;");
                                $d4 = mysqli_fetch_array($sql4);
                                $var_is_block = $d4['var_is_block'];
                                
                                
                                if($i<=3){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=4 && $i<=7 && get_rank_count($con, $user_id, 1)==1){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=8 && $i<=11 && get_rank_count($con, $user_id, 2)==1){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=12 && $i<=15 && get_rank_count($con, $user_id, 3)==1){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=16 && $i<=19 && get_rank_count($con, $user_id, 4)==1){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=20 && $i<=23 && get_rank_count($con, $user_id, 5)==1){
                                    $rank_complete = 1;
                                    
                                }elseif($i>=24 && get_rank_count($con, $user_id, 6)==1){
                                    $rank_complete = 1;
                                    
                                }else{
                                    $rank_complete = 0;
                                }
                                
                                if($rank_complete==1 && $var_is_block==0){
                                    $level_open = "<span class='badge badge-success'>Yes</span>";
                                }else{
                                    $level_open = "<span class='badge badge-danger'>No</span>";
                                }*/
                                
                             ?>
                                <tr class="text-center">
                                    <!--<td><?php //echo 'Level - '.$i; ?></td>	-->
                                    <td>
                                        <?php if($level_join_count>0){ ?>
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#list<?php echo $cal_level; ?>"><?php echo 'Level - '.$i; ?></button>
                                            <div class="modal fade" id="list<?php echo $cal_level; ?>" role="dialog">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content bg-dark">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Level - <?php echo $i; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        	<div class="table-responsive">
                                                            	<table class="table table-bordered table-hover table-dark">
                                                                    <thead class="thead-dark">
                                                                        <tr class="text-center">
                                                                            <th>Sl. No.</th>
                                                                            <th>Member Code</th>
                                                                            <th>Name</th>
                                                                            <th>Joining Date</th>
                                                                            <th>Status</th>
                                                                            <th>Business</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $n=1;
                                                                        $sql4="select member_id,mem_code,name,doj from member where intro_level='".$cal_level."' AND (doj BETWEEN '2023-01-01' AND '$current_date') AND intro_mtree LIKE CONCAT('%$user_code%') order by member_id desc";
                                                                        $q4=mysqli_query($con,$sql4);
                                                                        while($r3=mysqli_fetch_assoc($q4)){
                                                                           $is_active = member_is_active($con,$r3['member_id']);
                                                                           $self_business = get_self_investment_of_member($con,$r3['member_id'],'member_package_update_log');
                                                                         ?>
                                                                        <tr class="text-center">
                                                                          <td><?php echo $n++; ?>.</td>
                                                                          <td><?php echo $r3['mem_code']; ?></td>
                                                                          <td><?php echo $r3['name']; ?></td>
                                                                          <td><?php echo dmy($r3['doj']); ?></td>
                                                                          <td>
                                                                            <?php
                                                                            if($is_active=='1')
                                                                                echo '<span class="badge badge-success">Active</span>';
                                                                            else
                                                                                echo '<span class="badge badge-danger">In-Active</span>';
                                                                            ?>
                                                                          </td>
                                                                          <td><?php echo number_format($self_business,2); ?></td>
                                                                        </tr>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }else{ echo 'Level - '.$i; } ?>
                                    </td>
                                    
                                    <td><span class='badge badge-primary'><?php echo $level_join_count; ?></span></td>
                                    <td><span class='badge badge-success'><?php echo $level_active_count; ?></span></td>
                                    <td><span class='badge badge-danger'><?php echo $level_join_count - $level_active_count; ?></span></td>
                                    <?php /* ><td><?php echo $level_percentage; ?></td><?php */ ?>
                                    <td><?php echo number_format($level_business,2); ?></td>
                                    <?php /* ><td><?php echo number_format($estimated_income,6); ?></td>
                                    <td><?php echo $level_open; ?></td><?php */ ?>
                                </tr>
                            <?php
                            }
                            $db->dbDisconnet($con);
                            ?>
                        </tbody>
                        </table>
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