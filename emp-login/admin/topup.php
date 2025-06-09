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
    label>input {
        visibility: hidden;
        position: absolute;
    }

    label>input+img {
        cursor: pointer;
        border: 2px solid transparent;
        max-width: 200px !important;
        float: left;
    }

    label>input:checked+img {
        border: 2px solid #ff3f04;
    }
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
                        <h2>Investment</h2>
                        <p>Invest For ID</p>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="form-panel">
        	<div class="row">
        	    <div class="col-lg-12">
                    <div class="title-2 mb-4">
                        Investment
                    </div>
                </div>
        	    
                <div class="col-lg-12"> 
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="topupc" method="post">
                    	<div class="row">
                    	    <div class="form-group col-md-3 col-sm-6">
                                <label>Member Code*</label>
                                <input type="text" name="mem_code" id="mem_code" class="form-control" required  onkeyup="getMemberName()" placeholder="Enter member code*">
                                <div id="validMember"></div>
                            </div>
                            
                            <div class="form-group col-md-3 col-sm-6">
                                <label>Amount*</label>
                                <input type="text" name="amount" id="amount" readonly class="form-control" data-rule-digits="true" data-rule-required="true" placeholder="Enter the amount" />
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
        	    $("#validMember").html("<span class='badge badge-success'>" + s.name + "</span>");
        	}else{
        		$("#validMember").html("<span class='badge badge-danger'>Invalid ID</span>");
        	}
			
            //location.reload();
        }
    });
}
</script>


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