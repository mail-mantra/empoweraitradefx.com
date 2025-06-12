<div class="c-logo d-none d-md-block">
    <a href="dashboard"><img src="<?php echo PROJECT_LOGO ?>" class="img-fluid" style="max-width:200px" /></a>
</div>
<div class="mm-theme scrollable">
    <!--start-mm-sidebar-wrapper-->
    <nav class="mm-sidebar-wrapper">
        <!--start-mm-sidebar-content-->
        <div class="mm-sidebar-content">
            <!--start-mm-sidebar-header-->
            <div class="mm-sidebar-header">
                <div class="user-pic">
                    <img class="img-fluid img-rounded" src="../web-assets/images/user.jpg" alt="User picture">
                </div>
                <div class="user-info">
                    <span class="user-name"><strong><?php echo $user_full_name ?></strong></span>
                    <span class="user-role"><?php echo $user_code ?></span>
                    <span class="user-status"><i class="fa fa-circle"></i><span>Online</span></span>
                </div>
            </div>
            <!--end-mm-sidebar-header-->

            <!--start-mm-sidebar-search-->
            <!--<div class="mm-sidebar-search">        
            <div class="input-group"><input type="text" class="form-control search-menu" placeholder="Search...">
                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search" aria-hidden="true"></i></span>
                </div>
            </div>        
        </div>-->
            <!--end-mm-sidebar-search-->

            <!--start-mm-sidebar-menu-->
            <div class="mm-sidebar-menu">
                <ul>
                    <?php
                    if (isset($_SESSION['user_data']['_login_from']) && $_SESSION['user_data']['_login_from_id'] == 'admin') {
                    ?>
                        <li class="bg-info">
                            <a href="login_back_admin" style="padding:15px 15px;" class="text-white"><i
                                    class="fa fa-sign-out bg-red"></i>Back to Admin</a>
                        </li>
                    <?php
                    }
                    ?>
                    <li class="mm-header-menu"><span>General</span></li>
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-home"></i><span>Dashboard</span><span class="badge badge-pill badge-warning">New</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="dashboard">Dashboard</a></li>
                            </ul>
                        </div>
                    </li>

                    <!--<li><a href="signal"><i class="fa fa-line-chart"></i><span>Signal</span></a></li>
                    <li><a href="education_photo-view.php"><i class="fa fa-book"></i><span>Education</span></a></li>
                    <li><a href="foundation-view.php"><i class="fa fa-book"></i><span>Foundation</span></a></li>-->

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-diamond"></i><span>Settings</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="my-profile">Profile</a></li>
                                <!--<li><a href="my-bank">Bank</a></li>-->
                                <li><a href="my-crypto-address">Crypto Address</a></li>

                                <!--<li><a href="kyc-upload">KYC Upload</a></li>-->
                                <li><a href="change-password">Password Reset</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-user"></i><span>Member</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="member">Add Member</a></li>
                                <li><a href="member-view">Member List</a></li>
                                <li><a href="direct-member-view">Direct Member List</a></li>
                                <li><a href="tree">Tree View</a></li>
                                <li><a href="down-tree">Genealogy View</a></li>
                                <li><a href="level-wise-member-count">Level View</a></li>
                            </ul>
                        </div>
                    </li>

                    <li><a href="deposit-bep20-on-admin"><i class="fa fa-money"></i><span>Deposit (BEP20)</span></a></li>
                    <li><a href="pay-income-report"><i class="fa fa-money"></i><span>Pay Voucher (Working)</span></a></li>
                    <li><a href="pay-income-report-roi"><i class="fa fa-money"></i><span>Pay Voucher (Trading Profit)</span></a></li>

                    <!--<li><a href="trx-deposite"><i class="fa fa-money"></i><span>Deposit (TRX)</span></a></li>

                    <li><a href="deposit-bep20-on-admin"><i class="fa fa-money"></i><span>Deposit (BEP20)</span></a></li>
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-qrcode"></i><span>Booking</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="upi-deposite"> Recharge Now</a></li>
                                <li><a href="upi-deposite-report">Recharge Report</a></li>
                            </ul>
                        </div>
                    </li>-->

                    <!-- <li><a href="view-myfund-request"><i class="fa fa-money"></i><span>Fund Deposit</span></a></li> -->

                    <!--<li><a href="view-live-account-request"><i class="fa fa-money"></i><span>Live Account Deposit</span></a></li>
                    <li><a href="view-copy-trade-request"><i class="fa fa-money"></i><span>Copy Trade Deposit</span></a></li>
                     <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-line-chart"></i><span>Live Account Link Request</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="live_account_link_request-add">Add Request</a></li>
                                <li><a href="live_account_link_request-view">View All</a></li>
                            </ul>
                        </div>
                    </li> -->
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-file-text-o"></i><span>Community Trade Investment</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="topup-self">Invest</a></li>
                                <li><a href="topup-self-report">Investment Report</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-file-text-o"></i><span>Community Trade Investment For Downline</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="topup-downline">Downline Investment</a></li>
                                <li><a href="topup-downline-report">Downline Investment Report</a></li>
                            </ul>
                        </div>
                    </li>

                    <!--<li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-file-text-o"></i><span>Premium Signal Plan</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="topup-signal">Invest for Signal Plan</a></li>
                                <li><a href="topup-signal-report">Signal Plan Report</a></li>
                            </ul>
                        </div>
                    </li>-->

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-database"></i><span>Bonus</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="report-roi-income">Community Trade Bonus</a></li>
                                <li><a href="report-level-income">Community Trade Level Bonus</a></li>
                                <li><a href="report-live-roi-income">Live Trade Bonus</a></li>
                                <li><a href="report-live-level-income">Live Trade Level Bonus</a></li>
                                <li><a href="report-salary-income">Performance Bonus</a></li>
                                <li><a href="report-reward-income">Reward Bonus</a></li>
                                <li><a href="report-royalty-income">Royalty Bonus</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-folder"></i><span>Wallet</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="report-myfund-transaction">Fund Wallet</a></li>
                                <li><a href="report-working-transaction">Working Wallet</a></li>
                                <!--<li><a href="report-ba-transaction">BA Wallet</a></li>-->
                                <li><a href="report-roi-transaction">Trading Profit Wallet</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-file-text-o"></i><span>Rank Report</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="member-current-rank-report">Today Rank Report</a></li>
                                <li><a href="member-monthly-rank-report">Monthly Rank Report</a></li>
                                <li><a href="member-rank-log-report">Rank Log Report</a></li>
                                <!--<li><a href="downline-rank-report">Downline ID Rank</a></li>
                                <li><a href="downline-rank-business-report">Downline Business Ratio Report</a></li>
                                <li><a href="show_growth">Business Growth Report</a></li>-->
                            </ul>
                        </div>
                    </li>

                    <!--<li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-reply"></i><span>Withdraw</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="redeem-money?x=inr">Withdraw Request</a></li>
                                <li><a href="redeem-report">Withdraw Report</a></li>
                                <li><a href="redeem-money-trx">Withdraw Request (TRX)</a></li>
                                <li><a href="redeem-report-trx">Withdraw Report(TRX)</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-database"></i><span>Principle Withdraw</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="principle-withdraw">Principle Withdraw</a></li>
                                <li><a href="principle-withdraw-report">Principle Withdraw Report</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-database"></i><span>Transfer</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="transfer-myfund-wallet">Transfer Fund Balance to Other</a></li>
                                <li><a href="transfer-working-fund-wallet">Transfer Working to Fund Wallet (Self)</a></li>
                                
                                <li><a href="view-transfer-myfund-wallet">Report Transfer Fund Balance to Other</a></li>
                                <li><a href="view-transfer-working-myfund-wallet">Report Transfer Working to Fund Wallet (Self)</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-file-text-o"></i><span>Refund Entry</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                <li><a href="refund-entry">New Refund Entry</a></li>
                                <li><a href="view-refund-entry">View Refund Entry</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="mm-sidebar-dropdown">
                        <a href="#"><i class="fa fa-reply"></i><span>Withdraw</span></a>
                        <div class="mm-sidebar-submenu">
                            <ul>
                                 <li><a href="redeem-money?x=inr">Withdraw Request</a></li>
                                <li><a href="redeem-report">Withdraw Report</a></li> 

                                <li><a href="redeem-money-bep20">Withdraw Request (BEP20)</a></li>
                                <li><a href="redeem-report-bep20">Withdraw Report(BEP20)</a></li>-->

                    <!--<li><a href="redeem-money-trx">Withdraw Request (TRX)</a></li>
                                <li><a href="redeem-report-trx">Withdraw Report (TRX)</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="support"><i class="fa fa-question-circle"></i><span>Support</span></a></li>-->
                </ul>
            </div>
            <!--end-mm-sidebar-menu-->
        </div>
        <!--end-sidebar-content-->

        <!--start-mm-sidebar-footer-->
        <div class="mm-sidebar-footer">
            <!--<a href="member"><i class="fa fa-user"></i><span class="badge badge-pill badge-info notification">Add</span></a>-->
            <a href="change-password"><i class="fa fa-cog"></i><span class="badge-sonar">Pwd</span></a>
            <a href="logout"><i class="fa fa-power-off"></i></a>
        </div>
        <!--end-mm-sidebar-footer-->

    </nav>
    <!--end-mm-sidebar-wrapper-->
</div>