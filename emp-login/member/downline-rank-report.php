<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$page_name = 'downline-rank-report';

if(isset($_POST['mem_code'])) {
	$mem_code = trim($_POST['mem_code']);
	$sql_member = " AND b.mem_code='" . $mem_code . "'";
}else{
	$mem_code='';
	$sql_member = '';
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
                            <h2>Downline Rank Report</h2>
                            <p>Downline Rank Report</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-panel">
                <div class="row">
                    <div class="col-lg-12">
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Member Code</label>
                                <input type="text" name="mem_code" value="<?php if (isset($_REQUEST['mem_code'])) {
                                                                                echo $mem_code;
                                                                            } ?>" class="form-control" placeholder="Enter Member Code to search" />
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="submit" id="submit" class="btn btn-danger col-md-2 col-sm-3">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    </div>
                </div>
                <br>
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
                    
                    $query = "select b.mem_code, b.name, a.* from rank_achievement a inner join member b on a.member_id = b.member_id where b.intro_mtree LIKE '%$user_code%' ". $sql_member ." order by a.id desc";

                    $con = $db->connect();
                    $ss = mysqli_query($con, $query);
                    $db->dbDisconnet($con);
                    $t = mysqli_num_rows($ss);
                    $tot_page = ceil($t / $uplim);
                    $con = $db->connect();
                    $sql = mysqli_query($con, "$query limit $lowlim,$uplim");
                    $db->dbDisconnet($con);
                    $nsql = mysqli_num_rows($sql);
                    if ($nsql > 0) {
                        
                    ?>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-custom">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Date</th>
                                            <th>Member Code</th>
                                            <th>Name</th>
                                            <th>Rank</th>
                                            <th>Reward</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($r1 = mysqli_fetch_assoc($sql)) {
                                            $no++;
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?>.</td>
                                                <td><?php echo dmy($r1['created_on']); ?></td>
                                                <td><?php echo $r1['mem_code']; ?></td>
                                                <td><?php echo $r1['name']; ?></td>
                                                <td><?php echo $r1['rank']; ?></td>
                                                <td><?php echo $r1['reward']; ?></td>
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $first; ?>&mem_code=<?php echo ($mem_code); ?>" id="pagination" title="First Page"><?php echo "First"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $prev; ?>&mem_code=<?php echo ($mem_code); ?>" id="pagination" title="Previous Page"><?php echo "Previous"; ?></a></li>
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
                                            <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $i; ?>&mem_code=<?php echo ($mem_code); ?>" id="pagination" title="Page No. <?php echo "$i"; ?>"><?php
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
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $next; ?>&mem_code=<?php echo ($mem_code); ?>" id="pagination" title="Next Page"><?php echo "Next"; ?></a></li>
                                        <li class="page-item"><a class="page-link" href="<?php echo $page_name; ?>?cp=<?php echo $tot_page; ?>&mem_code=<?php echo ($mem_code); ?>" id="pagination" title="Last Page"><?php echo "Last"; ?></a></li>
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
    <script src="../web-assets/js/particles.min.js"></script>
    <script src="../web-assets/js/app.js"></script>

</body>

</html>