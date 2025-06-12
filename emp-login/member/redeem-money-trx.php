<?php
include('include/privilege.php');
include('../class/DbClass.php');
include('../lib/my_function.php');
$db = new Database();

$con = $db->connect();
$working_wallet = get_wallet_balance_of_member($con, $user_id, 'working_wallet_balance');
$roi_wallet = get_wallet_balance_of_member($con, $user_id, 'roi_wallet_balance');
$member_data = member_id_details($con, $user_id);
$db->dbDisconnet($con);

$open = true;
$crypto_deposit = true;

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
		}

		label>input:checked+img {
			border: 2px solid #ff3f04;
		}
	</style>
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
							<h2>TRX Withdraw Request</h2>
							<p>Withdraw fund from your Account TRX</p>
						</div>
					</div>
				</div>
			</div>

			<div class="form-panel">
				<form id="frmAdd" action="redeem-money-trxc" method="post">
					<div class="well">
						<div class="form_outer">
							<div class="row">
								<div class="col-lg-12">
									<div class="title-2 mb-4">
										<?php if ($working_wallet <= 0) { ?>
											<span class="btn btn-danger">Bonus Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
										<?php } else { ?>
											<span class="btn btn-success">Bonus Balance : <?php echo CURRENCY_ICON . $working_wallet; ?></span>
										<?php } ?>
										<?php if ($roi_wallet <= 0) { ?>
											<span class="btn btn-danger">Trading Profit Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
										<?php } else { ?>
											<span class="btn btn-success">Trading Profit Balance : <?php echo CURRENCY_ICON . $roi_wallet; ?></span>
										<?php } ?>

									</div>
								</div>
							</div>
							<hr />
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="form-group col-md-6 col-sm-6">
											<label>Wallet *</label>
											<select class="form-control" name="wallet_name" id="wallet" required>
												<option value="">---Select---</option>
												<option value="working">Bonus Wallet</option>
												<option value="roi">Trading Profit Wallet</option>
											</select>
										</div>
										<div class="form-group col-md-6 col-sm-6">
											<label>Amount*</label>
											<input type="text" name="amount" id="amount" class="form-control" data-rule-required="true" data-rule-number="true" onchange="getInr()" />
											<div id="inrValue"></div>
										</div>
										<div class="form-group col-md-6 col-sm-6">
											<label>Address*</label>
											<input type="text" value="<?php echo $member_data['crypto_address']; ?>" name="crypto_address" id="crypto_address" class="form-control" placeholder="Enter Address" data-rule-required="true" <?php if ($member_data['crypto_address'] != '') {
																																																												echo 'readonly';
																																																											} ?> />
										</div>

										<div class="col-md-12">
											<button type="submit" name="submit" id="submit" value="Proceed" class="btn btn-primary ">Proceed</button>
										</div>
									</div>
								</div>
							</div><!--row-->
						</div>
					</div><!--well-->

				</form>
			</div>
		</div>
	</div>
	<!--end-body-content-->

	<!--start-mm-footer-->
	<?php include('include/mm-footer.php'); ?>
	<!--end-mm-footer-->
	<!-- bank details -->
	<script type="text/javascript">
		$(document).on("keyup", "#amount", function() {
			let amount = $('#amount').val();
			let CRYPTOCOMPARE_API_KEY = "<?php echo CRYPTOCOMPARE_API_KEY; ?>"
			let fetchRes = fetch(
				"https://min-api.cryptocompare.com/data/price?fsym=USDT&tsyms=TRX&api_key=" + CRYPTOCOMPARE_API_KEY);
			fetchRes.then(res =>
				res.json()).then(d => {
				let usdRate = d.TRX;
				let texValue = amount * usdRate;
				$('#inrValue').html('TRX ' + texValue);
			})
		});
	</script>
	<!-- /bank details -->
	<!-- particles -->
	<script src="../web-assets/js/particles.min.js"></script>
	<script src="../web-assets/js/app.js"></script>
</body>

</html>