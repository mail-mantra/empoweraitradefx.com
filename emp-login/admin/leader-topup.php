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

<!--start-mm-top-header-->
<?php include('include/mm-top-header.php'); ?>
<!--end-mm-top-header-->

<!--start-body-content-->
<div class="body-content">
	<div class="container">
    	<div class="col-lg-12">
        	<div class="row">
                <div class="dashboard-title-2">
                	<div class="caption-2">
                        <h2>Leader Topup</h2>
                        <p>Leader Topup</p>
                    </div>
                </div>
            </div>
        </div>
        
        
    	<div class="form-panel">
        	<div class="row">
                <div class="col-lg-12"> 
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="leader-topupc" method="post">
                    	<div class="row">
                    	    <div class="form-group col-md-4 col-sm-6">
                                <label>Member Code*</label>
                                <input type="text" name="mem_code" id="mem_code" class="form-control" required  onkeyup="getMemberName()" placeholder="Enter member code" >
                                <span class='badge badge-success text-lg'></span>
	                            <span class='badge badge-danger text-lg'></span>
                            </div>
                            
                    	    <div class="form-group col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>Product*</label>
									<select name="pdt_id" id="pdt_id" class="form-control" data-rule-required="true" />
										<option value="">--- Select ---</option>
										<?php
										$sql_pdt = "SELECT * FROM `products` where id>1 and status=1";
                                        $con=$db->connect();
                                    	$q_pdt = mysqli_query($con,$sql_pdt);
                                    	$db->dbDisconnet($con);
                                    	while($r_pdt = mysqli_fetch_assoc($q_pdt)){
                                    	?>
										<option value="<?php echo $r_pdt['id'] ?>"><?php echo $r_pdt['product_name'] ?></option>
										<?php } ?>
									</select>
                                </div> 
                            </div> 
                            
                            <div class="form-group col-md-4 col-sm-6" id="show_package">
                                
                            </div>    
                           
                            <div class="col-md-12">
                            	<button type="submit" name="submit" id="submit" value="Submit" class="btn btn-sm btn-danger col-md-1 col-sm-3">Submit</button>
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
function getMemberName(){
	var mem_code = $('#mem_code').val();
    $.ajax({
        type: "POST",
        url: "../ajax/verify_member.php",
        data: {
            mem_code: mem_code
        },
        cache: !1,
        success: function(a) {
        	var s = JSON.parse(a);
        	if (s.status == 1) {
        		$('.badge-success').text(s.name);
        		$('.badge-danger').text("");
        	}else{
        		$('.badge-danger').text("Invalid Member Code");
        		$('.badge-success').text("");
        	}
			
            //location.reload();
        }
    });
}


$.fn.show_topup_packages = function() {
    var pdt_id = $("#pdt_id").val();

    $("#show_package").html("<div style='text-align:left;'><i class='fa fa-spinner fa-pulse fa-2x fa-fw'></i><span class='sr-only'>Loading...</span></div>");

    $.ajax({
        type: "POST",
        url: "../ajax/show-topup-packages",
        data: {
            pdt_id: pdt_id,
        },
        cache: false,
        //async:false,
        success: function(result) {
            $("#show_package").html(result);
        }
    });
    return false;
}

$(document).on("change keyup", "#pdt_id", function() {
    $.fn.show_topup_packages();

});
</script>

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
        $(document).ready(function() {
            $("#frmAdd").submit(function(event) {
                if( !confirm('Are you sure that you want to submit the form') )
                    event.preventDefault();
            });
        });

    });
</script>

<!-- particles -->
<script src="../web-assets/js/particles.min.js"></script>
<script src="../web-assets/js/app.js"></script>
</body>
</html>