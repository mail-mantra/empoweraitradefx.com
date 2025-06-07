DELIMITER $$
CREATE
    DEFINER = `empoweraitradefx`@`localhost` FUNCTION `TOTAL_SELF_TOPUP`(`var_member_id` INT(8)) RETURNS int
    NO SQL
    DETERMINISTIC
BEGIN
    DECLARE var_self_amount DECIMAL(20, 6);

    SELECT IFNULL(SUM(amount), 0)
    INTO var_self_amount
    FROM member_package_update_log
    WHERE member_id = var_member_id
      AND DATE(created_on) <= var_cal_date;


    RETURN var_self_amount;

END$$
DELIMITER ;