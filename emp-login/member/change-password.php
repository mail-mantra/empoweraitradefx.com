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
                        <h2>Password Reset</h2>
                        <p>Change Your Password </p>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="form-panel">
        	<div class="row">
        	    
                <div class="col-lg-12">     
                
                    <?php include('include/alert.php'); ?>
                    
                    <form id="frmAdd" action="change-passwordc" method="post">
                    	<div class="row">
                            <div class="form-group col-md-4 col-sm-6">
                                <label>Current Password*</label>
                                <input type="password"  name="cur_pwd" id="cur_pwd" class="form-control" data-rule-required="true" />
                                <div id="current_password"></div>
                            </div>
                            
                            <div class="form-group col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>New Password*</label>
                                    <input type="password"  name="new_pwd" id="new_pwd" class="form-control" data-rule-required="true" />
                                </div> 
                            </div>  
                            
                            <div class="form-group col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>Confirm Password*</label>
                                    <input type="password"  name="conf_pwd" id="conf_pwd" class="form-control" data-rule-required="true" />
                                    <div id="confirm_password"></div>
                                </div> 
                            </div>  
                            <div class="col-md-12">
                            	<button type="submit" name="submit" id="submit" value="Save" class="btn btn-primary col-md-2 col-sm-3">Save</button>
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
$(document).ready(function(){
	$.fn.show_confirm_password = function(){
		var new_pwd = $("#new_pwd").val();
		var conf_pwd = $("#conf_pwd").val();
		$.ajax({
		type: "POST",
		url: "../ajax/confirm_password",
		data:  {
					new_pwd: new_pwd,
					conf_pwd: conf_pwd
				},
		cache: false,
		success: function(result)
		{
			try
			{
				var obj = JSON.parse(result);
				if(obj.status==1)
				{
					$("#confirm_password").html("<span class='badge badge-success'>Correct</span>");		
	
				}
				else
				{
					$("#confirm_password").html("<span class='badge badge-danger'>Password Mismatch</span>");
				}
			}
			catch(e)
			{
				
			}
		}
		});
		return false;
	}
	
	$.fn.current_password_match = function(){
		var cur_pwd = $("#cur_pwd").val();
		$.ajax({
		type: "POST",
		url: "../ajax/match_current_password",
		data:  {
					cur_pwd: cur_pwd
				},
		cache: false,
		success: function(result)
		{
			try
			{
				var obj = JSON.parse(result);
				if(obj.status==1)
				{
					$("#current_password").html("<span class='badge badge-success'>Correct</span>");		
	
				}
				else
				{
					$("#current_password").html("<span class='badge badge-danger'>Invalid Password</span>");
				}
			}
			catch(e)
			{
				
			}
		}
		});
		return false;
	}
});

$(document).on("change keyup", "#conf_pwd",  function(){
	$.fn.show_confirm_password();
});	

$(document).on("change keyup", "#cur_pwd",  function(){
	$.fn.current_password_match();
});	

</script>

<!-- particles -->
<script src="../web-assets/js/particles.min.js"></script>
<script src="../web-assets/js/app.js"></script>
</body>
</html>