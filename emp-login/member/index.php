<?php
session_start();
include('../class/DbClass.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo PROJECT_NAME ?></title>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../login/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../login/assets/css/font-awesome.min.css">

    <script src="../login/assets/js/jquery.min.js"></script>
    <script src="../login/assets/js/popper.min.js"></script>
    <script src="../login/assets/js/bootstrap.min.js"></script>

    <style type="text/css">
        body {
            background: url(../login/2.jpg) no-repeat top center fixed;
            background-size: cover;
        }

    .login-box{ position:relative; width:100%; height:100%; margin-top:30%; padding:50px 30px; background:rgba(255, 255, 255, 0.7);
      border-radius:0 5px;
      -webkit-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
      -moz-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
      box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);    
    }
    .login-box:before{position:absolute; left:0; top:-3px; width:50%; height:5px; content:""; /*background:#fdaf4b;*/}
    .login-box:after{position:absolute; right:0; top:-3px; width:50%; height:5px; content:""; /*background:#9934ff;*/}
    
    .login-box h2{font-family: 'Open Sans', sans-serif; font-size:24px; line-height:30px; color:#FFF; margin-bottom:30px; text-align:center;}
    .login-box h4{font-family: 'Open Sans', sans-serif; font-size:14px; font-weight:bold; line-height:20px; color:#2f2f2f; margin:10px 0; text-align:center;}
    .login-box h4 a{color:#2f2f2f;}
    .login-box h5{font-family: 'Open Sans', sans-serif; font-size:14px; line-height:20px; color:#adaaaa; margin-bottom:10px; text-align:center;}
    .login-box h5 a{color:#ababab;}
    
    .formsec{ padding-top:10px;}
    
    .form-control{background: #0d0133; border:none!important; line-height:36px; height:50px;  }
    
    
    .btn{width:100%; border-radius:0!important; background:#3b1555; border-radius:5px!important; border:none; color:#fff;}
    .btn:hover{background:#8460f3;}
    
    .btn2{width:100%; border-radius:0!important; background:#1e7e34; border-radius:5px!important; border:none; color:#fff !important;}
    .btn2:hover{background:#1e7e34;}
    
    .logo{}
    .logo img{ margin:10px auto; display:block;}

       /* #Div1 {
            background: #ffffff91;
            width: 100%;
            padding: 20px;
            z-index: 1;
            position: relative;
        }

        #Div2 {
            background: #ffffff91;
            width: 100%;
            padding: 20px;
            z-index: 5;
            position: relative;
            display: none;
        }

        #Div3 {
            background: #110426;
            width: 100%;
            padding: 20px;
            z-index: 15;
            position: relative;
            display: none;
        }*/

        #Div1{ width:100%; padding:0px; z-index:1; position:relative;}
        #Div2{ width:100%; padding:0px; z-index:5; position:relative; display: none;}
        #Div3{ width:100%; padding:20px; z-index:15; position:relative; display: none;}
        
        
        #bg1{
    	position: fixed;
    	width: 100%;
    	height: 100%;
    	top: 0;
    	left: 0;
    	-webkit-transition: opacity 1s;
    	transition: opacity 1s;
    }
    
    #bg1 {
    	/*background: -webkit-linear-gradient(top, #1d3a72, #7db84a);
    	background: linear-gradient(top, #1d3a72, #7db84a);*/
    	
    	background: -webkit-linear-gradient(top, #1d3a72, #03112f);
    	background: linear-gradient(top, #1d3a72, #03112f);
    }
    
    .particle {
    	opacity: 0;
    	position: absolute;
    	background-color: rgba(255, 255, 255, 0.5);
    	-webkit-animation: particleAnim 3s ease-in-out infinite;
    	animation: particleAnim 3s ease-in-out infinite;
    	border-radius: 100%;
    }
    
    @-webkit-keyframes particleAnim {
    	0% {
    		opacity: 0;
    		transform: translateY(-0%);
    	}
    	15% {
    		opacity: 1;
    	}
    	100% {
    		opacity: 0;
    		transform: translateY(-1500%);
    	}
    }
    
    @keyframes particleAnim {
    	0% {
    		opacity: 0;
    		transform: translateY(-0%);
    	}
    	25% {
    		opacity: 1;
    	}
    	100% {
    		opacity: 0;
    		transform: translateY(-1500%);
    	}
    }
    @media (max-width: 768px) {
    
    }
    @media (max-width: 500px) {
    
    }   
    </style>

</head>

<body>
    <!--<div id="bg1"></div>
    <div id="particleGenerator"></div>-->
    <div class="container">
        <div class="col-md-4 offset-md-0 col-sm-8">
            <div class="login-box">
                <div id="Div1">
            
                    <!--<h4>Welcome to GS Admin</h4>-->
                    <?php include('include/alert.php'); ?>
                    
                    <div class="logo">
                        <img src="<?php echo PROJECT_LOGO ?>?v=12" class="img-fluid" >
                    </div>
                    
                    <div class="formsec">
                        <form action="loginc" id="frm_login" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">  
                            <div class="form-group">
                                <input type="text" class="form-control" name="un" placeholder="Member Code" data-rule-required="true" required id="uname">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="pw" placeholder="Enter Password" data-rule-required="true" required id="passcode">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" id="submit" value="Login" class="btn btn-primary">Login</button>
                            </div>
                            <!--<div class="form-group">                
                                <input type="submit" name="submit" value="Login"  class="btn btn-primary btn-block" />                            
                            </div>--> 
                            <h4><a href="#" id="Button1" type="button" value="Click" onclick="switchVisible();">Forgot Password</a></h4>
                            <!--<h4><a href="#" id="Button1" type="button" value="Click" onclick="switchVisible();">Goto into Trade Dashboard</a></h4>                       
                            <h4><a href="member-signup" class="btn btn-success btn2">Register as new Member</a></h4>-->
                        </form>
                    </div>
                </div>
                
                <div id="Div2">
                    <?php include('include/alert.php'); ?>

                    <div class="logo"><img src="<?php echo PROJECT_LOGO ?>" class="img-fluid" style="width:280px;"></div>

                    <div class="formsec">
                            <h5 style="color:#c5bbbb">Forgot Your Password</h5>
    
                            <form action="forgot-passwordc" id="frm_login" novalidate="novalidate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
    
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Username" name="un" required>
                                </div>
    
                                <div class="form-group">
                                    <button type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">Submit</button>
                                </div>
                                <h4><a href="#" id="Button4" type="button" value="Click" onclick="switchVisible4();">Back to Login</a></h4>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function switchVisible() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display == 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div2').style.display = 'none';
                } else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div2').style.display = 'block';

                }
            }

        }

        function switchVisible2() {
            if (document.getElementById('Div1')) {

                if (document.getElementById('Div1').style.display == 'none') {
                    document.getElementById('Div1').style.display = 'block';
                    document.getElementById('Div3').style.display = 'none';
                } else {
                    document.getElementById('Div1').style.display = 'none';
                    document.getElementById('Div3').style.display = 'block';
                }
            }

        }

        function switchVisible3() {
            if (document.getElementById('Div3')) {

                if (document.getElementById('Div3').style.display == 'none') {
                    document.getElementById('Div3').style.display = 'block';
                    document.getElementById('Div1').style.display = 'none';
                } else {
                    document.getElementById('Div3').style.display = 'none';
                    document.getElementById('Div1').style.display = 'block';
                }
            }

        }

        function switchVisible4() {
            if (document.getElementById('Div2')) {

                if (document.getElementById('Div2').style.display == 'none') {
                    document.getElementById('Div2').style.display = 'block';
                    document.getElementById('Div1').style.display = 'none';
                } else {
                    document.getElementById('Div2').style.display = 'none';
                    document.getElementById('Div1').style.display = 'block';
                }
            }

        }
    </script>

    <script>
function beginTransition() {
	var title = document.getElementById('theTitle');
	var input = document.getElementsByClassName('inputBox');
	var names = document.getElementsByClassName('name');
	var button = document.getElementsByClassName('submitButton');
	var bg2 = document.getElementById('bg2');
	setTimeout(function() {
		title.style.opacity = 0;
		title.style.right = "80%";
	}, 100);
	setTimeout(function() {
		names[0].style.opacity = 0;
		names[0].style.right = "80%";
		bg2.style.opacity = 1;
	}, 200);
	setTimeout(function() {
		input[0].style.opacity = 0;
		input[0].style.right = "80%";
	}, 300);
	setTimeout(function() {
		names[1].style.opacity = 0;
		names[1].style.right = "80%";
	}, 400);
	setTimeout(function() {
		input[1].style.opacity = 0;
		input[1].style.right = "80%";
	}, 500);
	setTimeout(function() {
		button[0].style.opacity = 0;
		button[0].style.right = "80%";
	}, 600);
	setTimeout(function() {
		var mes = document.getElementById('mesOut');
		mes.style.visibility = "visible";
		mes.style.opacity = 1;
		mes.style.top = "40%";
	}, 1000);
}

function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

function particlesInit() {
	var generator = document.getElementById("particleGenerator");
	var particleCount = 200;
	for (var i = 0; i < particleCount; i++) {
		var size = getRandomInt(2, 6);
		var n = '<div class="particle" style="top:' + getRandomInt(15, 95) + '%; left:' + getRandomInt(5,95) + '%; width:'
		+ size + 'px; height:' + size + 'px; animation-delay:' + (getRandomInt(0,30)/10) + 's; background-color:rgba('
		+ getRandomInt(80, 160) + ',' + getRandomInt(185, 255) + ',' + getRandomInt(160, 255) + ',' + (getRandomInt(2, 8)/10) + ');"></div>';
		console.log("Particle " + i + ": " + n);
		var node = document.createElement("div");
		node.innerHTML = n;
		generator.appendChild(node);
	}
}

particlesInit();
</script>
</body>

</html>