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
                    <span class="user-name"><strong>Admin</strong></span>
                    <span class="user-role">Administrator</span>
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
                <?php
                if ($_SESSION['user_data']['user_type'] === 'admin') {
                ?>
                    <ul>
                        <li class="mm-header-menu"><span>General</span></li>
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-home"></i><span>Dashboard</span><span class="badge badge-pill badge-warning">New</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="dashboard">Dashboard</a></li>
                                    <li><a href="news">News Board</a></li>
                                    <li><a href="add-popup">Popup</a></li>
                                    <!--<li><a href="setting">Settings</a></li>
                                    <li><a href="bank-settings">Bank Update</a></li>-->
                                </ul>
                            </div>
                        </li>

                        <!--<li><a href="signal"><i class="fa fa-line-chart"></i><span>Signal</span></a></li>-->

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-diamond"></i><span>Member</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="member">Add Member</a></li>
                                    <li><a href="member-view">Member List</a></li>
                                    <!--<li><a href="member-kyc-view">Kyc Report</a></li>-->
                                    <li><a href="direct-member-view">Direct Member List</a></li>
                                    <li><a href="tree">Tree View</a></li>
                                    <li><a href="down-tree">Genealogy View</a></li>
                                    <li><a href="level-wise-member-count">Level View</a></li>
                                </ul>
                            </div>
                        </li>

                        <!--<li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-book"></i><span>Education</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="education_photo-add.php">Add Image</a></li>
                                    <li><a href="education_photo-view">Image List</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-book"></i><span>Foundations</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="foundation-add.php">Add Image/Video</a></li>
                                    <li><a href="foundation-view">View List</a></li>
                                </ul>
                            </div>
                        </li>

                        <li><a href="member-kyc-view"><i class="fa fa-database"></i><span>Kyc Report</span></a></li>

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-id-card"></i><span>Kyc Verification</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="pan-wise-count">PAN Verification</a></li>
                                    <li><a href="aadhaar-wise-count">Aadhaar Verification</a></li>
                                </ul>
                            </div>
                        </li>
                    
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-folder"></i><span>Kyc Verification (Manual) </span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="pan-verify">Verify PAN</a></li>
                                    <li><a href="pan-verified-list">Verified PAN List</a></li>

                                    <li><a href="aadhar-front-verify">Verify Aadhar Front</a></li>
                                    <li><a href="aadhar-front-verified-list">Verified Aadhar Front List</a></li>
                                    
                                    <li><a href="aadhar-back-verify">Verify Aadhar Back</a></li>
                                    <li><a href="aadhar-back-verified-list">Verified Aadhar Back List</a></li>

                                    <li><a href="bank-verify">Verify Bank</a></li>
                                    <li><a href="bank-verified-list">Verified Bank List</a></li>

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
                            <a href="#"><i class="fa fa-money"></i><span>Force Credit/Debit</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="force-credit-debit-fund-wallet">Fund Wallet Cr/Dr</a></li>
                                    <li><a href="report-force-credit-debit-fund-wallet">View Fund Wallet Cr/Dr</a></li>

                                    <li><a href="force-credit-debit-working-wallet">Working Wallet Cr/Dr</a></li>
                                    <li><a href="report-force-credit-debit-working-wallet">View Working Wallet Cr/Dr</a>

                                    <li><a href="force-credit-debit-roi-wallet">Working Trading Profit Cr/Dr</a></li>
                                    <li><a href="report-force-credit-debit-roi-wallet">View Trading Profit Wallet Cr/Dr</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-folder"></i><span>Wallet Statement</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="report-fund-transaction">Fund Wallet</a></li>
                                    <li><a href="report-working-transaction">Working Wallet</a></li>
                                    <!--<li><a href="report-ba-transaction">BA Wallet</a></li>-->
                                    <li><a href="report-roi-transaction">Trading Profit Wallet</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-folder"></i><span>Wallet Balance</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="fund-balance">Fund Balance</a></li>
                                    <li><a href="working-balance">Working Balance</a></li>
                                    <!--<li><a href="ba-balance">BA Balance</a></li>-->
                                    <li><a href="roi-balance">Trading Profit Balance</a></li>
                                </ul>
                            </div>
                        </li>

                        <li><a href="topup-live-downline"><i class="fa fa-database"></i><span>Live Trade Invest for ID</span></a></li>

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-ban"></i><span>Pay Income</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="pay-income">Working</a></li>
                                    <li><a href="pay-income-voucher">Working Voucher</a></li>

                                    <li><a href="pay-income-roi">Trading Profit</a></li>
                                    <li><a href="pay-income-voucher-roi">Trading Profit Voucher</a></li>
                                </ul>
                            </div>
                        </li>

                        <!--<li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-ban"></i><span>Block ID</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="income-block">Working Block</a></li>
                                    <li><a href="income-block-list">Working Block List</a></li>
                                    <li><a href="roi-block">Trading Profit Block</a></li>
                                    <li><a href="roi-block-list">Trading Profit Block List</a></li>
                                </ul>
                            </div>
                        </li>

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-database"></i><span>Principle Withdraw</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="principle-withdraw">Principle Withdraw</a></li>
                                    <li><a href="principle-withdraw-report">Principle Withdraw Report</a></li>
                                    <li><a href="topup-deleted-report">Principle Withdraw Investment List</a></li>
                                </ul>
                            </div>
                        </li>-->

                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-file-text-o"></i><span>Rank Report</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="member-current-rank-report">View Today Rank Report</a></li>
                                    <li><a href="member-monthly-rank-report">View Monthly Rank Report</a></li>
                                    <li><a href="member-rank-log-report">View Rank Log Report</a></li>
                                    <!--<li><a href="show_growth">Business Growth Report</a></li>-->
                                </ul>
                            </div>
                        </li>

                        <!--<li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-file-text-o"></i><span>Refund Entry</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="view-refund-entry">View Refund Entry</a></li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-line-chart"></i><span>Live Account Link Request</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="live_account_link_request-view">View All</a></li>
                                </ul>
                            </div>
                        </li> 
                        
                        <li><a href="member-balance" class=""><i class="fa fa-usd"></i> <span>Member Balance</span></a></li>

                        <li><a href="withdraw-request"><i class="fa fa-database"></i><span>View Withdraw Request</span></a></li>
                        
                        <li><a href="view-voucher"><i class="fa fa-database"></i><span>View Voucher</span></a></li>-->

                        <!--<li><a href="withdraw-request-trx"><i class="fa fa-database"></i><span>View Withdraw Request (TRX)</span></a></li>


                        <li><a href="withdraw-request-bep20"><i class="fa fa-database"></i><span>View Withdraw Request (BEP20)</span></a></li>
                        
                    
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-database"></i><span>Leadership Topup</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="leader-topup">Leader Topup</a></li>
                                    <li><a href="leader-topup-report">Leader Topup Report</a></li>
                                </ul>
                            </div>
                        </li>
                        
                        <li><a href="upi-deposite-report"><i class="fa fa-database"></i><span>Recharge Report</span></a></li>
                        
                        <li><a href="view-voucher"><i class="fa fa-database"></i><span>View Voucher</span></a></li>

                        <li><a href="topup"><i class="fa fa-dropbox"></i><span>Investment</span></a></li>

                        
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-question-circle"></i><span>Support System</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="support">Unsolved Queries</a></li>
                                    <li><a href="solved-support-query">Solved Queries</a></li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-users"></i><span>Sub Admin</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <li><a href="add-sub-admin">Add Sub Admin</a></li>
                                    <li><a href="view-sub-admin">View Sub Admin</a></li>
                                    <li><a href="view-page-privilege">View Page Privilege</a></li>
                                </ul>
                            </div>
                        </li>-->

                        <li class="mm-header-menu"><span>Report</span></li>
                        <li><a href="topup-report"><i class="fa fa-database"></i><span>Community Trade List</span></a></li>

                        <li><a href="topup-live-report"><i class="fa fa-database"></i><span>Live Trade List</span></a></li>

                        <!-- <li><a href="view-fund-request"><i class="fa fa-database"></i><span>View Balance Request</span></a></li> -->
                        <li><a href="bep20-deposit-report"><i class="fa fa-database"></i><span>Bep20 Deposit Report</span></a></li>
                        <!--<li><a href="view-live-account-request"><i class="fa fa-database"></i><span>Live Account Request</span></a></li>
                        <li><a href="view-copy-trade-request"><i class="fa fa-database"></i><span>Copy Trade Request</span></a></li>
                        
                        <li><a href="view-transfer-myfund-wallet"><i class="fa fa-database"></i><span>Report Transfer Fund Balance to Other</span></a></li>
                        <li><a href="view-transfer-working-myfund-wallet"><i class="fa fa-database"></i><span>Report Transfer Working to Fund Wallet (Self)</span></a></li>

                        <li><a href="registration-report"><i class="fa fa-database"></i><span>Registration List</span></a></li>
                    
                        <li><a href="reg-report"><i class="fa fa-database"></i><span>Subscription List</span></a></li>
                        <li><a href="payment-report"><i class="fa fa-database"></i><span>Payment Report</span></a></li>-->

                    </ul>
                <?php
                } else if ($_SESSION['user_data']['user_type'] === 'sub_admin') {
                ?>
                    <ul>
                        <li class="mm-header-menu"><span>General</span></li>
                        <li class="mm-sidebar-dropdown">
                            <a href="#"><i class="fa fa-home"></i><span>Dashboard</span><span class="badge badge-pill badge-warning">New</span></a>
                            <div class="mm-sidebar-submenu">
                                <ul>
                                    <?php if (in_array("dashboard", $accessible_pages)) { ?><li><a href="dashboard">Dashboard</a></li><?php } ?>
                                    <?php if (in_array("news", $accessible_pages)) { ?><li><a href="news">News Board</a></li><?php } ?>
                                    <?php if (in_array("add-popup", $accessible_pages)) { ?><li><a href="add-popup">Popup</a></li><?php } ?>
                                </ul>
                            </div>
                        </li>

                        <?php if (in_array("signal", $accessible_pages)) { ?><li><a href="signal"><i class="fa fa-line-chart"></i><span>Signal</span></a></li><?php } ?>

                        <?php
                        $menus = [
                            "Member" => ["member", "member-view", "direct-member-view", "tree", "down-tree", "level-wise-member-count"],
                            "Education" => ["education_photo-add.php", "education_photo-view"],
                            "Income Bonus" => ["report-direct-income", "report-level-income", "report-roi-income", "report-salary-income", "report-club-income", "report-reward-income"],
                            "Force Credit/Debit" => [
                                "force-credit-debit-fund-wallet",
                                "report-force-credit-debit-fund-wallet",
                                "force-credit-debit-working-wallet",
                                "report-force-credit-debit-working-wallet"
                            ],
                            "Wallet Statement" => ["report-fund-transaction", "report-working-transaction", "report-roi-transaction"],
                            "Wallet Balance" => ["fund-balance", "working-balance", "roi-balance"],
                            "Principle Withdraw" => ["principle-withdraw", "principle-withdraw-report"],
                            "Rank Report" => ["member-current-rank-report", "member-monthly-rank-report", "member-rank-log-report"],
                            "Support System" => ["support", "solved-support-query"],
                            "Sub Admin" => ["add-sub-admin", "view-sub-admin", "view-page-privilege"],
                            "Report" => ["topup-report", "topup-signal-report", "view-fund-request", "view-live-account-request"]
                        ];

                        foreach ($menus as $title => $menu) {
                            if (sizeof(array_intersect($menu, $accessible_pages))) {
                                echo "<li class='mm-sidebar-dropdown'>";
                                echo "<a href='#'><i class='fa fa-folder'></i><span>$title</span></a>";
                                echo "<div class='mm-sidebar-submenu'><ul>";
                                foreach ($menu as $page) {
                                    if (in_array($page, $accessible_pages)) {
                                        echo "<li><a href='$page'>" . ucfirst(str_replace('-', ' ', $page)) . "</a></li>";
                                    }
                                }
                                echo "</ul></div></li>";
                            }
                        }
                        ?>

                        <?php if (in_array("topup-downline", $accessible_pages)) { ?><li><a href="topup-downline"><i class="fa fa-database"></i><span>Invest for ID</span></a></li><?php } ?>
                        <?php if (in_array("member-balance", $accessible_pages)) { ?><li><a href="member-balance" class=""><i class="fa fa-usd"></i> <span>Member Balance</span></a></li><?php } ?>
                        <?php if (in_array("withdraw-request", $accessible_pages)) { ?><li><a href="withdraw-request"><i class="fa fa-database"></i><span>View Withdraw Request</span></a></li><?php } ?>
                        <?php if (in_array("view-voucher", $accessible_pages)) { ?><li><a href="view-voucher"><i class="fa fa-database"></i><span>View Voucher</span></a></li><?php } ?>
                        <?php if (in_array("withdraw-request-trx", $accessible_pages)) { ?><li><a href="withdraw-request-trx"><i class="fa fa-database"></i><span>View Withdraw Request (TRX)</span></a></li><?php } ?>
                    </ul>
                <?php
                }
                ?>
            </div>
            <!--end-mm-sidebar-menu-->
        </div>
        <!--end-sidebar-content-->

        <!--start-mm-sidebar-footer-->
        <div class="mm-sidebar-footer">
            <a href="member"><i class="fa fa-user"></i><span class="badge badge-pill badge-info notification">Add</span></a>
            <a href="news"><i class="fa fa-bell"></i><span
                    class="badge badge-pill badge-warning notification">Add</span></a>
            <a href="change-password"><i class="fa fa-cog"></i><span class="badge-sonar">Pwd</span></a>
            <a href="logout"><i class="fa fa-power-off"></i></a>
        </div>
        <!--end-mm-sidebar-footer-->

    </nav>
    <!--end-mm-sidebar-wrapper-->
</div>