<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db= new Database();

if(!isset($_SERVER['HTTP_REFERER'])){
	$systemDenied=true;
	include('include/forced-logout.php');
}
@$back = $_SERVER['HTTP_REFERER'];

if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['side']) && isset($_GET['intro_code']))
{
    
    $con=$db->connect();
	foreach($_GET as $key => $value){
	  $data[$key] = prevent_injection($con,$value);
	}
	$db->dbDisconnet($con);
	
	$side = hash_decode($data['side']);
	//$intro_code = $user_code;
	//$intro_code1 = hash_decode($data['intro_code']);
	
	$intro_code = hash_decode($data['intro_code']);
	$upliner_code = $intro_code;
	
	
	$sql1="select count(1) AS member_exists, name from member where mem_code='".$intro_code."'";
	$con=$db->connect();
	$q1=mysqli_query($con,$sql1);
	$db->dbDisconnet($con);
	$r1=mysqli_fetch_assoc($q1);
	$member_exists=$r1['member_exists'];
	$member_name=$r1['name'];
	
	$sql2="select count(1) AS member_side_exists from member where upliner_code='".$upliner_code."' and side='".$side."'";
	
	$con=$db->connect();
	$q2=mysqli_query($con,$sql2);
	$db->dbDisconnet($con);
	$r2=mysqli_fetch_assoc($q2);
	$member_side_exists = $r2['member_side_exists'];
	
	if($member_exists==0){
	    $_SESSION['e']="Invalid Introducer.";
		header("Location: $back");
		die;
		
	}else if($member_side_exists>=1){
	    $_SESSION['e']="Invalid Upliner.";
		header("Location: $back");
		die;
		
	}else if($side!='L' && $side!='R'){
	    $_SESSION['e']="Invalid Side.";
		header("Location: $back");
		die;
		
	}else{
	
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
                        <h2>Add Member</h2>
                        <p>Welcome to Member panel.</p>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="form-panel">
        	<div class="row">
            	<div class="col-lg-12">
                	<div class="title-2 mb-4">Joining Details</div>
                </div>
                <div class="col-lg-12">   
                
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="direct-member-joiningc" method="post">
                    	<div class="row">                   	
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Introducer Code*</label>
                                <input type="text" class="form-control" data-rule-required="true" value="<?php echo $intro_code; ?>" disabled />
                                <input type="hidden" name="intro" id="intro" class="form-control" data-rule-required="true" value="<?php echo $intro_code; ?>"/>
                                <input type="hidden" name="upliner" id="upliner" class="form-control" data-rule-required="true" value="<?php echo $upliner_code; ?>"/>
                            </div>
                            
                            <div class="form-group col-md-4 col-sm-6" id="introSection"></div>
                            
                            <div class="form-group col-md-4 col-sm-6">	
                                <label>Upliner <span>*</span></label>
                                <input type="text" name="" value="<?php echo $upliner_code.' - '.$member_name ?>" class="form-control" disabled>
                           </div>
                           
                            <div class="form-group col-md-4 col-sm-6">	
                                <label>Side*</label><br>
                                <?php if($side=='L'){  ?>   
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="side" id="side" value="L" <?php if($side=='L'){ echo 'checked'; } ?>>LEFT
                                        </label>
                                    </div>
                                <?php } if($side=='R'){ ?>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="side" id="side" value="R" <?php if($side=='R'){ echo 'checked'; } ?>>RIGHT
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>  
                            
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Name*</label>
                                <input type="text" name="name" class="form-control" data-rule-required="true" />
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>Mobile No.*</label>
                                <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10" data-rule-required="true" data-rule-digits="true" data-rule-minlength="10" data-rule-maxlength="10" />
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>Email ID*</label>
                                <input type="email" name="email" id="email" class="form-control" data-rule-required="true" />
                            </div>

                            <div class="form-group col-md-4 col-sm-6">
                                <label>Country*</label>
                                <select name="country" class="form-control" required>
                                    <option value="">- - - Select - - -</option>
                                    <?php
                                    $sql1 = "SELECT * FROM `countries` ORDER BY `name` ASC ";
                                    $con = $db->connect();
                                    $res1 = mysqli_query($con, $sql1);
                                    $con->close();
                                    if (mysqli_num_rows($res1)) {
                                        while ($arr1 = mysqli_fetch_assoc($res1)) {
                                    ?>
                                            <option value="<?php echo $arr1['name']; ?>" <?= ($arr1['name'] == 'India') ? "selected" : ""; ?>><?php echo $arr1['name']; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-4 col-sm-6">
                                <label>PAN</label>
                                <input type="text" name="pan_no" id="pan_no" class="form-control" />
                            </div>
                            
                            <div class="col-md-12">
                            	<button type="submit" name="submit" id="submit" value="Join" class="btn btn-danger col-md-2 col-sm-3">Join</button>
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

<script type="text/javascript">
    $(document).ready(function() {
        $.fn.verify_intro = function() {
            var intro = $("#intro").val();
            $.ajax({
                type: "POST",
                url: "../ajax/intro",
                data: {
                    intro: intro
                },
                cache: false,
                success: function(result) {
                    $("#introSection").html(result);
                }
            });
            return false;
        }
        $.fn.verify_intro();

        /*$("#no_pan").change(function() {
            if(this.checked) {
                $('#pan_no').val('A/F');
                $('#pan_no').prop("readonly", true);
            }else{
                $('#pan_no').val('');
                $('#pan_no').prop("readonly", false);
            }
        });*/
    });

    $(document).on("change keyup", "#intro", function() {
        $.fn.verify_intro();
    });
</script>

<!-- particles -->
<script src="web-assets/js/particles.min.js"></script>
<script src="web-assets/js/app.js"></script>
</body>
</html>
<?php
	}
}
?>
