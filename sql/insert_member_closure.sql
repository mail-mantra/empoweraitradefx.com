DELIMITER $$
CREATE DEFINER=`empoweraitradefx`@`localhost` PROCEDURE `insert_member_closure`(IN `p_mem_code` VARCHAR(255))
BEGIN
    DECLARE v_depth INT DEFAULT 0;
    DECLARE v_ancestor_code VARCHAR(255);
    DECLARE v_intro_code VARCHAR(255);

    -- Error handler first
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
        BEGIN
            RESIGNAL;
            ROLLBACK;
        END;

    -- Begin transaction
    START TRANSACTION;

    DELETE FROM member_closure WHERE descendant_code = p_mem_code;

    INSERT INTO member_closure (ancestor_code, descendant_code, level)
    VALUES (p_mem_code, p_mem_code, 0);

    SET v_ancestor_code = p_mem_code;

    -- Use labeled loop for faster control
    ancestor_loop: LOOP
        SELECT intro_code INTO v_intro_code
        FROM member
        WHERE mem_code = v_ancestor_code;

        IF v_intro_code IS NULL OR v_intro_code = '' THEN
            LEAVE ancestor_loop;
        END IF;

        SET v_depth = v_depth + 1;

        INSERT INTO member_closure (ancestor_code, descendant_code, level)
        VALUES (v_intro_code, p_mem_code, v_depth);

        SET v_ancestor_code = v_intro_code;

        IF v_intro_code = 'admin' THEN
            LEAVE ancestor_loop;
        END IF;
    END LOOP;

    UPDATE member SET recorded = 1 WHERE mem_code = p_mem_code;

    COMMIT;
END$$
DELIMITER ;