DELIMITER $$
CREATE DEFINER=`empoweraitradefx`@`localhost` FUNCTION `##HAS_SELF_INVESTMENT`(`var_member_id` INT(8), `var_self_investment` DECIMAL(20,6), `var_cal_date` DATE) RETURNS int
    NO SQL
    DETERMINISTIC
BEGIN
	DECLARE var_amount DECIMAL(20,6);
	DECLARE var_self_amount DECIMAL(20,6);
    DECLARE var_pass INT(1) DEFAULT 0;


	IF var_self_investment = 0 THEN
		SET var_pass = 1;
	ELSE
		SELECT IFNULL(SUM(amount),0) INTO var_self_amount
		FROM member_package_update_log WHERE member_id = var_member_id AND DATE(created_on) <= var_cal_date;

		IF var_self_amount >= var_self_investment THEN
			SET var_pass = 1;
		ELSE
			SET var_pass = 0;
		END IF;
    END IF;

	RETURN var_pass;

END$$
DELIMITER ;