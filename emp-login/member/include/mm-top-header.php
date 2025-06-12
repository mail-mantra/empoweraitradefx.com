<div class="mm-top-header">
    <div class="logo d-block d-md-none">
        <a href="dashboard"><img src="<?php echo PROJECT_LOGO ?>" class="img-fluid" /></a>
    </div>
    <div class="mth-dropdown">
        <ul>
            <li class="dropdown">
                <a href="#" data-toggle="dropdown" class="">
                    <h6><img src="../web-assets/images/user.jpg" alt="user" width="40" height="40" class="rounded mr-1"> <?php echo $user_code; ?></h6>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="my-profile" class="dropdown-item"><i class="fa fa-user"></i> Edit Profile</a>
                    <a href="change-password" class="dropdown-item"><i class="fa fa-key"></i> Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a href="logout" class="dropdown-item"><i class="fa fa-sign-out"></i> Logout</a>
                </div>
            </li>
        </ul>

    </div>
</div>
<div class="blank-div"></div>