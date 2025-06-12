<?php
function CheckStaus($con, $oid)
{
    global $now;
    $curr_status = 'pending';
    //Check if is in success table
    $sql = "select id from member_money_transfer_success where order_id = '$oid'";
    $result = mysqli_query($con,$sql);
    $num_row = mysqli_num_rows($result);
    if($num_row > 0)
    {
        //update status to success in MMT
        $update_sql = "UPDATE member_money_transfer SET status = 'success', update_on = '$now' WHERE order_id = '$oid'";
        $update_result = mysqli_query($con,$update_sql);
        $curr_status = 'success';
    }
    else
    {
        //Check if is in failed table
        $sql = "select id from member_money_transfer_failed where order_id = '$oid'";
        $result = mysqli_query($con,$sql);
        $num_row = mysqli_num_rows($result);
        if($num_row > 0)
        {
            //update status to failed in MMT
            $update_sql = "UPDATE member_money_transfer SET status = 'failed', update_on = '$now' WHERE order_id = '$oid'";
            $update_result = mysqli_query($con,$update_sql);
            $curr_status = 'failed';
        }
		else
		{
			//Check if is in refund table
			$sql = "select id from member_money_transfer_refund where order_id = '$oid'";
			$result = mysqli_query($con,$sql);
			$num_row = mysqli_num_rows($result);
			if($num_row > 0)
			{
				//update status to refund in MMT
				$update_sql = "UPDATE member_money_transfer SET status = 'refund', update_on = '$now' WHERE order_id = '$oid'";
				$update_result = mysqli_query($con,$update_sql);
				$curr_status = 'refund';
			}
		}

    }
    return $curr_status;
}