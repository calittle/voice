/* 	
	AUTHOR: 		Charles Little (little_charles1@columbusstate.edu)
	CREATION DATE:	22-June-2017
	FILENAME:		VOICE-dml.sql
	PURPOSE:		Stored Procedures, Triggers, Functions (DML)
*/
use VOICE;
START TRANSACTION;

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`get_login` $$
CREATE PROCEDURE `VOICE`.`get_login` (
	IN username varchar(256)
)
COMMENT 'Get a password hash.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
	SELECT PWD_HASH,USER_ID FROM USERS WHERE USER_NAME = username LIMIT 1;
END $$
DELIMITER ; $$

/** Receipt and Details **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`receipt_generate` $$
CREATE PROCEDURE `VOICE`.`receipt_generate` (
	IN regid bigint(20),
    IN electionid bigint(20)
)
COMMENT 'Get all measures/options selected by registrant on an election ballot.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    SELECT
		M.MEASURE_DETAIL as 'Measure',
        O.OPTION_DETAIL  as 'Chosen Option',
        B.CREATED as 'Cast Time',
        B.SIGNATURE as 'Electronic Signature'
	FROM BALLOTS B
	INNER JOIN `MEASURES` M on M.MEASURE_ID = B.MEASURE_ID
	INNER JOIN `OPTIONS` O on O.OPTION_ID = B.OPTION_ID
    WHERE B.REGISTRANT_ID = regid and B.ELECTION_ID = electionid;
END $$
DELIMITER ; $$

/** ELECTIONS, ELECTION DISTRICTS, MEASURES, OPTIONS **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_cast_ballot` $$
CREATE PROCEDURE `VOICE`.`elections_cast_ballot` (
	IN regid bigint(20),
    IN electionid bigint(20),
    IN measureid bigint(20),
    IN optionid bigint(20),
    IN sig longblob,
    OUT ballotid bigint(20)
)
COMMENT 'Record registrant''s chosen option on an election''s measure'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    INSERT INTO BALLOTS (`REGISTRANT_ID`,`ELECTION_ID`,`MEASURE_ID`,`OPTION_ID`,`SIGNATURE`) VALUES (regid,electionid,measureid,optionid,sig);
    SELECT LAST_INSERT_ID() INTO ballotid;
    COMMIT;
END $$
DELIMITER ; $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_elections` $$
CREATE PROCEDURE `VOICE`.`registrant_get_elections` (IN regid bigint(20))
COMMENT 'List registrant''s eligible elections.'
BEGIN
    SELECT 
		`ELECTION_ID` as 'Election ID',
        `ELECTIONS`.`ELECTION_NAME` as 'Election Name',
        `ELECTIONS`.`ELECTION_DETAIL` as 'Election Detail',        
        `ELECTIONS`.`DATE_START` AS 'Start Date',
        `ELECTIONS`.`DATE_END` as 'End Date'
	FROM `ELECTIONS`
    WHERE `ELECTION_ID` IN (SELECT ELECTION_ID FROM ELECTION_DISTRICTS WHERE DISTRICT_ID IN (SELECT DISTRICT_ID FROM REGISTRANT_DISTRICTS WHERE REGISTRANT_ID = regid));
    
END $$
DELIMITER ; $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_get_measures` $$
CREATE PROCEDURE `VOICE`.`elections_get_measures` (IN electionid bigint(20))
COMMENT 'List measures in an election.'
BEGIN
    SELECT 
		`MEASURE_ID` as 'Measure ID',
        `MEASURE_DETAIL` as 'Election Detail'
	FROM MEASURES
    WHERE `ELECTION_ID` = electionid; 
    
END $$
DELIMITER ; $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_get_measure_options` $$
CREATE PROCEDURE `VOICE`.`elections_get_measure_options` (IN electionid bigint(20),IN measureid bigint(20))
COMMENT 'List options in a measure.'
BEGIN
    SELECT 
		`OPTION_ID` as 'Option ID',
        `OPTION_DETAIL` as 'Option Detail'
	FROM OPTIONS
    WHERE `ELECTION_ID` = electionid and `MEASURE_ID` = measureid;
    
END $$
DELIMITER ; $$
-- get an election
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_get` $$
CREATE PROCEDURE `VOICE`.`elections_get` (
	IN electionid bigint(20)
)
COMMENT 'Get an Election.'
BEGIN
	-- signal SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    SELECT 
    `ELECTION_NAME` as 'Election Name',
    `ELECTION_DETAIL`as 'Election Detail',
    `DATE_START` as 'Election Start Date',
    `DATE_END` as 'Election End Date'
     FROM `ELECTIONS` where ELECTION_ID = electionid;
END $$
DELIMITER ; $$

-- add election
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_add` $$
CREATE PROCEDURE `VOICE`.`elections_add` (
	IN electionname varchar(256),
    IN electiondetail longblob,
    IN startdate datetime,
    IN enddate datetime,
    OUT electionid bigint(20)
)
COMMENT 'Add an Election.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    INSERT INTO `ELECTIONS` (`ELECTION_NAME`, `ELECTION_DETAIL`,`DATE_START`, `DATE_END`) VALUES (electionname,electiondetail,startdate,enddate);
    SELECT LAST_INSERT_ID() INTO electionid;
    COMMIT;
END $$
DELIMITER ; $$

-- add district to election
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_add_district` $$
CREATE PROCEDURE `VOICE`.`elections_add_district` (
	IN electionid bigint(20),
    IN districtid bigint(20)
)
COMMENT 'Add a district to an election.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    -- SELECT LAST_INSERT_ID() INTO regid;
    INSERT INTO `ELECTION_DISTRICTS` (`ELECTION_ID`,`DISTRICT_ID`) VALUES (electionid, districtid);
    COMMIT;
END $$
DELIMITER ; $$

-- add measures to ballot
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_add_measure` $$
CREATE PROCEDURE `VOICE`.`elections_add_measure` (
	IN electionid bigint(20),
    IN measure longblob,
    OUT measureid bigint(20)
)
COMMENT 'Add a measure (question, candidate, etc.) to an election.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    INSERT INTO `MEASURES` (`ELECTION_ID`,`MEASURE_DETAIL`) VALUES (electionid,measure);
    SELECT LAST_INSERT_ID() INTO measureid;
    COMMIT;
END $$
DELIMITER ; $$

-- add options to measure
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`elections_add_measure_option` $$
CREATE PROCEDURE `VOICE`.`elections_add_measure_option` (
	IN electionid bigint(20),
    IN measureid bigint(20),
	IN moption longblob
)
COMMENT 'Add a measure option (choice, candidate X, etc.) to measure.'
BEGIN
	-- SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'X';
    INSERT INTO `OPTIONS` (`ELECTION_ID`,`MEASURE_ID`,`OPTION_DETAIL`) VALUES (electionid,measureid,moption);
    -- SELECT LAST_INSERT_ID() INTO measureid;
    COMMIT;
END $$
DELIMITER ; $$

-- add sample ballot to election



/** REGISTRANT - DISTRICTS - LOCATIONS**/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`registrant_add` $$
CREATE PROCEDURE `VOICE`.`registrant_add` (
	IN firstname varchar(256),
    IN middlename varchar(256),
    IN lastname varchar(256),
    IN suffix varchar(256),
    IN birthdate date,
    IN telephone varchar(64),
    IN stateidnum varchar(256),
    IN fedidnum varchar(32),
    IN gencd varchar(16),
    IN ethcd char(2),
    IN affirm tinyint(1),
    IN statecd varchar(3),
    IN partycd varchar(16),
    IN userid bigint(20),
    OUT regid bigint(20)
)
COMMENT 'Add a registrant. Requires user.'
BEGIN
	IF affirm = '1' THEN
		INSERT INTO `REGISTRANTS` ( `F_NAME`, `M_NAME`, `L_NAME`, `Suffix`, `DOB`, `Phone`,`STATEID`, `FEDERAL_ID`, `GENDERCD`, `ETHNICITYCD`,`AFFIRM_STATE`,`AFFIRMED`,`STATECD`,`PARTYCD`,`USER_ID`) 
		VALUES (firstname,middlename,lastname,suffix,birthdate,telephone,stateidnum,fedidnum,gencd,ethcd,affirm,NOW(),statecd,partycd,userid);
	ELSE
		INSERT INTO `REGISTRANTS` ( `F_NAME`, `M_NAME`, `L_NAME`, `Suffix`, `DOB`, `Phone`,`STATEID`, `FEDERAL_ID`, `GENDERCD`, `ETHNICITYCD`,`AFFIRM_STATE`,`STATECD`,`PARTYCD`,`USER_ID`) 
		VALUES (firstname,middlename,lastname,suffix,birthdate,telephone,stateidnum,fedidnum,gencd,ethcd,affirm,statecd,partycd,userid);
    END IF;
    SELECT LAST_INSERT_ID() INTO regid;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get` $$
CREATE PROCEDURE `VOICE`.`registrant_get` (IN regid bigint(20))
COMMENT 'Get a specific registrant'
BEGIN
	SELECT 
		CONCAT(R.F_NAME,' ',R.M_NAME,' ',R.L_NAME,' ',R.Suffix) as 'Name',
        R.DOB as 'Date of Birth',
        R.Phone as 'Phone',
        R.STATEID as 'State ID',
        R.FEDERAL_ID as 'Federal ID',
        G.GENDER as 'Gender',
        E.ETHNICITY as 'Ethnicity',
        R.APPROVAL_STATE as 'Approval State',
        R.AFFIRM_STATE as 'Affirmation State',
        R.USER_ID as 'User ID',
        R.REGISTRANT_ID as 'Registrant ID',
        P.PARTY as 'Party Affiliation',
        S.STATE as 'State',
        CONCAT(L.STREET_NAME1,' ',L.STREET_NAME2,' ',L.CITY,',',L.STATECD,' ',L.POSTALCODE) as 'Location'
	FROM REGISTRANTS R
    INNER JOIN GENDERS G on G.GENDERCD = R.GENDERCD
    INNER JOIN ETHNICITIES E on E.ETHNICITYCD = R.ETHNICITYCD
    INNER JOIN PARTIES P on P.PARTYCD = R.PARTYCD
    INNER JOIN STATES S on S.STATECD = R.STATECD
    INNER JOIN REG_LOC RL ON RL.REGISTRANT_ID = R.REGISTRANT_ID
    INNER JOIN LOCATIONS L ON L.LOCATION_ID = RL.LOCATION_ID
    WHERE R.REGISTRANT_ID = regid and RL.IS_RESIDENCE = 1;
END $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`get_ids` $$
CREATE PROCEDURE `VOICE`.`get_ids` (IN regid bigint(20), IN uid bigint(20))
COMMENT 'Get a registrant, location, and user id'
BEGIN
	IF regid >= 0 THEN
		SELECT 
			R.REGISTRANT_ID,
			R.USER_ID
			FROM REGISTRANTS R
			INNER JOIN USERS U ON U.USER_ID = R.USER_ID
			WHERE R.REGISTRANT_ID = regid
		UNION ALL
		SELECT 
			RL.LOCATION_ID,
			RL.REGISTRANT_ID
			FROM REG_LOC RL        
			WHERE RL.IS_RESIDENCE = 1 AND RL.REGISTRANT_ID = regid;
	ELSEIF uid >= 0 THEN
		SELECT 
			R.REGISTRANT_ID,
			R.USER_ID
			FROM REGISTRANTS R
			INNER JOIN USERS U ON U.USER_ID = R.USER_ID
			WHERE R.USER_ID = uid
		UNION ALL
		SELECT 
			RL.LOCATION_ID,
			RL.REGISTRANT_ID as 'IGNORE'
			FROM REG_LOC RL        
			WHERE RL.IS_RESIDENCE = 1 AND RL.REGISTRANT_ID = regid;    
    END IF;
END $$
DELIMITER ; $$


DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_basic` $$
CREATE PROCEDURE `VOICE`.`registrant_get_basic` (IN regid bigint(20))
COMMENT 'Get a specific registrant'
BEGIN
	SELECT 
			CONCAT(R.F_NAME,' ',R.M_NAME,' ',R.L_NAME,' ',R.Suffix) as 'Name',
			R.DOB as 'Date of Birth',
			R.Phone as 'Phone',
			R.STATEID as 'State ID',
			R.FEDERAL_ID as 'Federal ID',
			G.GENDER as 'Gender',
			E.ETHNICITY as 'Ethnicity',
			R.APPROVAL_STATE as 'Approval State',
			R.AFFIRM_STATE as 'Affirmation State',
			P.PARTY as 'Party Affiliation',
			S.STATE as 'State',
			R.USER_ID as 'User ID',
			R.REGISTRANT_ID as 'Registrant ID',
            CALL registrant_get_residenceaddr(regid) as 'Residence'
		FROM REGISTRANTS R
		INNER JOIN GENDERS G on G.GENDERCD = R.GENDERCD
		INNER JOIN ETHNICITIES E on E.ETHNICITYCD = R.ETHNICITYCD
		INNER JOIN PARTIES P on P.PARTYCD = R.PARTYCD
		INNER JOIN STATES S on S.STATECD = R.STATECD
		WHERE R.REGISTRANT_ID = regid;			
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`locations_delete` $$
CREATE PROCEDURE `VOICE`.`locations_delete` (
    IN regid bigint(20),
    IN locid bigint(20)
)
COMMENT 'Remove a location from a registrant.'
BEGIN	
	DELETE FROM `REG_LOC` WHERE REGISTRANT_ID = regid and LOCATION_ID = locid;
    DELETE FROM `LOCATIONS` WHERE LOCATION_ID = locid;
    COMMIT;
    /* Business Rules:
		If the residence location is removed from the registrant, reset approval state to unapproved.*/
	SET @reccount = (SELECT COUNT(*) FROM `LOCATIONS` L INNER JOIN `REG_LOC` RL on RL.LOCATION_ID = L.LOCATION_ID WHERE RL.IS_RESIDENCE = 1 and RL.REGISTRANT_ID = regid);
    IF @reccount = 0 THEN
		CALL registrant_unset_approved(regid);
	END IF;
END $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`locations_add` $$
CREATE PROCEDURE `VOICE`.`locations_add` (
    IN streetname1 varchar(256),
    IN streetname2 varchar(256),
    IN city varchar(256),
    IN postalcode varchar(256),
    IN countycd varchar(64),
    IN statecd varchar(3),
    IN countrycd varchar(3),    
    IN regid bigint(20),
    IN isresidence tinyint(1),
    IN ismailing tinyint(1),
    OUT locid bigint(20)
)
COMMENT 'Add a location to a registrant; IsResidence+IsMailing must be > 0'

BEGIN	
	DECLARE reccount INT unsigned DEFAULT 0;

    IF isresidence + ismailing = 0 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'One of ISRESIDENCE or ISMAILING flags must be set to 1. Both are set to 0.';
	END IF;
     
    IF isresidence = 1 THEN
		SET reccount = (SELECT COUNT(*) FROM `LOCATIONS` L INNER JOIN `REG_LOC` RL on RL.LOCATION_ID = L.LOCATION_ID WHERE RL.IS_RESIDENCE = 1 and RL.REGISTRANT_ID = regid);
    ELSEIF ismailing = 1 THEN
		SET reccount = (SELECT COUNT(*) FROM `LOCATIONS` L INNER JOIN `REG_LOC` RL on RL.LOCATION_ID = L.LOCATION_ID WHERE RL.IS_MAILING = 1 and RL.REGISTRANT_ID = regid);
	END IF;
    
    IF reccount > 0 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'This registrant already has two locations. You must delete one (locations_delete) before you can add another.';
	END IF;
    
    INSERT INTO `LOCATIONS` (`STREET_NAME1`,`STREET_NAME2`,`CITY`,`POSTALCODE`,`COUNTYCD`,`STATECD`,`COUNTRYCD`)
	VALUES (streetname1,streetname2,city,postalcode,countycd,statecd,countrycd);
	SELECT LAST_INSERT_ID() INTO locid;
    INSERT INTO `REG_LOC` (`LOCATION_ID`,`REGISTRANT_ID`,`IS_MAILING`,`IS_RESIDENCE`)
		VALUES (locid,regid,ismailing,isresidence);
    COMMIT;
END $$
DELIMITER ; $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_mailingaddr` $$
CREATE PROCEDURE `VOICE`.`registrant_get_mailingaddr` (
    IN regid bigint(20)
)
COMMENT 'Get the mailing address of a registrant. '
BEGIN
	CALL locations_get(regid,null,1,null);
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_residenceaddr` $$
CREATE PROCEDURE `VOICE`.`registrant_get_residenceaddr` (
    IN regid bigint(20)
)
COMMENT 'Get the residence address of a registrant. '
BEGIN
	CALL locations_get(regid,null,null,1);
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_addresses` $$
CREATE PROCEDURE `VOICE`.`registrant_get_addresses` (
    IN regid bigint(20)
)
COMMENT 'Get all addresses of a registrant. '
BEGIN
	CALL locations_get(regid,null,null,null);
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_address` $$
CREATE PROCEDURE `VOICE`.`registrant_get_address` (
    IN regid bigint(20),
    IN locid bigint(20)
)
COMMENT 'Get all addresses of a registrant. '
BEGIN
	CALL locations_get(regid,locid,null,null);
END $$

DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`locations_get` $$
CREATE PROCEDURE `VOICE`.`locations_get` (
    IN regid bigint(20),
    IN locid bigint(20),
    IN bmail tinyint(1),
    IN bres  tinyint(1)
)
COMMENT 'Use wrapper functions instead of calling this directly.'
BEGIN
	IF regid = NULL THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Must provide Registrant ID value.';
	ELSEIF bmail != null THEN
		SELECT
			L.`LOCATION_ID` as 'Location ID',
			L.`STREET_NAME1` as 'Address Line 1',
            L.`STREET_NAME2` as 'Address Line 2',
            CONCAT(L.`CITY`,' ',L.`STATECD`,' ',L.`POSTALCODE`) as 'CSZ',
            L.`COUNTYCD` as 'County',
            L.`COUNTRYCD` as 'Country'
		FROM LOCATIONS L 
        INNER JOIN REG_LOC RL ON RL.LOCATION_ID = L.LOCATION_ID
        WHERE RL.REGISTRANT_ID = regid and RL.IS_MAILING=1;
    ELSEIF bres != null THEN
		SELECT 
			L.`LOCATION_ID` as 'Location ID',
			L.`STREET_NAME1` as 'Address Line 1',
            L.`STREET_NAME2` as 'Address Line 2',
            CONCAT(L.`CITY`,' ',L.`STATECD`,' ',L.`POSTALCODE`) as 'CSZ',
            L.`COUNTYCD` as 'County',
            L.`COUNTRYCD` as 'Country'
		FROM LOCATIONS L 
        INNER JOIN REG_LOC RL ON RL.LOCATION_ID = L.LOCATION_ID
        WHERE RL.REGISTRANT_ID = regid and RL.IS_RESIDENCE = 1;
    ELSEIF locid != null  THEN
		SELECT 		
			L.`STREET_NAME1` as 'Address Line 1',
            L.`STREET_NAME2` as 'Address Line 2',
            CONCAT(L.`CITY`,' ',L.`STATECD`,' ',L.`POSTALCODE`) as 'CSZ',
            L.`COUNTYCD` as 'County',
            L.`COUNTRYCD` as 'Country',
            IF(RL.`IS_MAILING` = '1',
				IF(RL.`IS_RESIDENCE`='1','Mailing and Residence','Mailing'),
				IF(RL.`IS_RESIDENCE`='1','Residence','')
			)as 'Residence Type'            
		FROM LOCATIONS L 
        INNER JOIN REG_LOC RL ON RL.LOCATION_ID = locid
        WHERE RL.REGISTRANT_ID = regid and RL.LOCATION_ID = locid;
    ELSE 
		SELECT 
			L.`LOCATION_ID` as 'Location ID',
			L.`STREET_NAME1` as 'Address Line 1',
            L.`STREET_NAME2` as 'Address Line 2',
            CONCAT(L.`CITY`,' ',L.`STATECD`,' ',L.`POSTALCODE`) as 'CSZ',
            L.`COUNTYCD` as 'County',
            L.`COUNTRYCD` as 'Country',
			IF(RL.`IS_MAILING` = '1',
				IF(RL.`IS_RESIDENCE`='1','Mailing and Residence','Mailing'),
				IF(RL.`IS_RESIDENCE`='1','Residence','')
			)as 'Residence Type'
		FROM LOCATIONS L 
        INNER JOIN REG_LOC RL ON RL.LOCATION_ID = L.LOCATION_ID
        WHERE RL.REGISTRANT_ID = regid;
	END IF;
END $$
DELIMITER ; $$


DROP PROCEDURE IF EXISTS `VOICE`.`districts_add` $$
CREATE PROCEDURE `VOICE`.`districts_add` (IN district varchar(128),OUT districtid bigint(20))
COMMENT 'Add a district'
BEGIN
	INSERT INTO `DISTRICTS` (`DISTRICT`) VALUES (district);
    COMMIT;
    SELECT `DISTRICT_ID` INTO districtid FROM `DISTRICTS` WHERE `DISTRICTS`.`DISTRICT`= district;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`districts_update` $$
CREATE PROCEDURE `VOICE`.`districts_update` (IN districtid bigint(20), IN district varchar(128))
COMMENT 'Update a district name'
BEGIN
	UPDATE `DISTRICTS` SET `DISTRICT`=district WHERE `DISTRICTS`.`DISTRICT_ID` = districtid;
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`districts_delete` $$
CREATE PROCEDURE `VOICE`.`districts_delete` (IN districtid bigint(20))
COMMENT 'Delete a district'
BEGIN
	DELETE FROM `DISTRICTS` WHERE `DISTRICTS`.`DISTRICT_ID` = districtid;
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`registrant_get_districts` $$
CREATE PROCEDURE `VOICE`.`registrant_get_districts` (IN regid bigint(20))
COMMENT 'Get a specific registrant''s districts'
BEGIN
	SELECT 
		D.DISTRICT 
	FROM DISTRICTS D 
    INNER JOIN REGISTRANT_DISTRICTS RD ON D.DISTRICT_ID = RD.DISTRICT_ID
    WHERE RD.REGISTRANT_ID = regid;		
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`get_affirmations` $$
CREATE PROCEDURE `VOICE`.`get_affirmations` (IN statecd varchar(3))
COMMENT 'Get affirmations for a given state'
BEGIN
	SELECT `AFFIRMATION`,`AFFIRM_ID`,`STATECD` 
	FROM `AFFIRMATIONS` 
    WHERE `AFFIRMATIONS`.`STATECD` = (statecd);		
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_set_affirm` $$
CREATE PROCEDURE `VOICE`.`registrant_set_affirm` (IN regid bigint(20))
COMMENT 'Mark registration as affirming state''s affirmations'
BEGIN
	UPDATE REGISTRANTS
    SET 
		`AFFIRM_STATE` = 1,
		`AFFIRMED` = NOW()
    WHERE `REGISTRANTS`.`REGISTRANT_ID` = regid;
	COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_set_district` $$
CREATE PROCEDURE `VOICE`.`registrant_set_district` (IN regid bigint(20), IN districtid bigint(20))
COMMENT 'Add a registrant to a district'
BEGIN
	INSERT INTO REGISTRANT_DISTRICTS (`REGISTRANT_ID`,`DISTRICT_ID`) VALUES (regid,districtid);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_unset_district` $$
CREATE PROCEDURE `VOICE`.`registrant_unset_district` (IN regid bigint(20), IN districtid bigint(20))
COMMENT 'Remove a registrant to a district'
BEGIN
	UPDATE `REGISTRANT_DISTRICTS` RD SET `ACTIVE` = 0 WHERE RD.REGISTRANT_ID = regid and RD.DISTRICT_ID = districtid;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_unset_approved` $$
CREATE PROCEDURE `VOICE`.`registrant_unset_approved` (IN regid bigint(20))
COMMENT 'Remove approval of registrant.'
BEGIN
	UPDATE `REGISTRANTS` SET `APPROVAL_STATE` = 0 WHERE REGISTRANT_ID = regid;
    COMMIT;
END $$


DROP PROCEDURE IF EXISTS `VOICE`.`registrant_set_approved` $$
CREATE PROCEDURE `VOICE`.`registrant_set_approved` (IN regid bigint(20))
COMMENT 'Approve registrant.'
BEGIN
/* Business rules:
	1) Must have at least 1 district
    2) Must have affirm
    3) Must have at least one location with a residence flag.
*/
	SET @ck_district = (SELECT COUNT(*) FROM `REGISTRANT_DISTRICTS` WHERE REGISTRANT_ID = regid);
    SET @ck_affirm = (SELECT `REGISTRANTS`.`AFFIRM_STATE` FROM `REGISTRANTS` WHERE `REGISTRANT_ID` = regid);
    SET @ck_location = (SELECT COUNT(*) FROM `REG_LOC` WHERE `REGISTRANT_ID` = regid and `IS_RESIDENCE` = 1);
	
    IF @ck_district = 0 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot approve registrant: no district(s) have been assigned.';
    ELSEIF @ck_affirm = 0 THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot approve registrant: registrant has not affirmed voter affirmations for residence state.';
    ELSEIF @ck_location = 0 THEN
    	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot approve registrant: no residence location has been created.';    
    END IF;

	UPDATE REGISTRANTS
    SET 
		APPROVAL_STATE = 1,
		APPROVALSTATECHANGED = NOW()
    WHERE `REGISTRANTS`.`REGISTRANT_ID` = regid;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`registrant_set_rejected` $$
CREATE PROCEDURE `VOICE`.`registrant_set_rejected` (IN regid bigint(20))
COMMENT 'Reject registrant.'
BEGIN
	UPDATE REGISTRANTS
    SET 
		`APPROVAL_STATE` = 2,
		`APPROVALSTATECHANGED` = NOW()
    WHERE `REGISTRANTS`.`REGISTRANT_ID` = regid;
    COMMIT;
END $$

DELIMITER ; $$

/** USER, USER ROLES **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`users_list` $$
CREATE PROCEDURE `VOICE`.`users_list` ()
BEGIN
	SELECT USERID,USERNAME,EMAIL from USERS;
END $$

/* NOTE: hash and salt must be generated outside of database. */
DROP PROCEDURE IF EXISTS `VOICE`.`users_add2` $$
CREATE PROCEDURE `VOICE`.`users_add2` (IN username varchar(256),IN email varchar(256), IN passwordhash varchar(256), IN hashalg varchar(50),IN salt varchar(50))
BEGIN
	INSERT INTO `USERS` (`USER_NAME`,`EMAIL`,`PWD_HASH`,`HASH_ALGORITHM`,`SALT`) VALUES (username,email,passwordhash,hashalg,salt);    
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`users_add` $$
CREATE PROCEDURE `VOICE`.`users_add` (IN username varchar(256),IN email varchar(256), IN passwordhash varchar(256), IN hashalg varchar(50),IN salt varchar(50),OUT newid bigint(20))
BEGIN
	INSERT INTO `USERS` (`USER_NAME`,`EMAIL`,`PWD_HASH`,`HASH_ALGORITHM`,`SALT`) VALUES (username,email,passwordhash,hashalg,salt);    
    SELECT LAST_INSERT_ID() INTO newid;
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`users_update_password` $$
CREATE PROCEDURE `VOICE`.`users_update_password` (IN userid bigint(20),IN passwordhash varchar(256), IN hashalg varchar(50),IN salt varchar(50))
COMMENT 'Update a user password'
BEGIN		
	UPDATE `USERS` 
	SET 
		`PWD_HASH` = passwordhash,
		`HASH_ALGORITHM` = hashalg,
        `SALT` = salt
	WHERE `USERS`.`USER_ID`=userid;
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`users_update_email` $$
CREATE PROCEDURE `VOICE`.`users_update_email` (IN userid bigint(20),IN email varchar(256))
COMMENT 'Update a user email address'
BEGIN		
	UPDATE `USERS` SET `EMAIL`=email WHERE `USERS`.`USER_ID`=userid;
    COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`users_add_role` $$
CREATE PROCEDURE `VOICE`.`users_add_role` (IN userid bigint(20), IN roleid bigint (20), IN username varchar(256), IN role varchar(64))
COMMENT 'Can use combination of ID or name in either user or role, IDs being preferred'
BEGIN
	IF (userid != NULL and roleid != NULL) THEN
        INSERT INTO `USERS_ROLES` (`USER_ID`,`ROLE_ID`) VALUES (userid,roleid);
	ELSEIF (username != NULL and role != NULL) THEN
    	INSERT INTO `USERS_ROLES` (`USER_ID`,`ROLE_ID`) VALUES ((SELECT `USER_ID` FROM USERS WHERE `USERS`.`USER_NAME` = username),(SELECT `ROLE_ID` FROM `ROLES` WHERE `ROLES`.`ROLE_DESCRIPTION` = role));
	ELSEIF (userid != NULL and role != NULL) THEN
    	INSERT INTO `USERS_ROLES` (`USER_ID`,`ROLE_ID`) VALUES (userid,(SELECT `ROLE_ID` FROM `ROLES` WHERE `ROLES`.`ROLE_DESCRIPTION` = role));
	ELSEIF (username != NULL and roleid !=NULL) THEN
    	INSERT INTO `USERS_ROLES` (`USER_ID`,`ROLE_ID`) VALUES ((SELECT `USER_ID` FROM USERS WHERE `USERS`.`USER_NAME` = username),roleid);
    END IF;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`users_add_role` $$
CREATE PROCEDURE `VOICE`.`users_add_role` (IN userid bigint(20), IN roleid bigint (20))
BEGIN
	INSERT INTO `USERS_ROLES` (`USER_ID`,`ROLE_ID`) VALUES (userid,roleid);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`users_delete` $$
CREATE PROCEDURE `VOICE`.`users_delete` (IN username varchar(256),IN userid bigint(20))
BEGIN
	IF username != NULL THEN
		DELETE FROM `USERS` WHERE `USERS`.`USER_NAME` = username;
	ELSEIF userid != NULL THEN
		DELETE FROM `USERS` WHERE `USERS`.`USER_ID` = userid;
	END IF;
    COMMIT;
END $$
DELIMITER ; $$

/** ETHNICITIES **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`ethnicities_list` $$
CREATE PROCEDURE `VOICE`.`ethnicities_list` ()
BEGIN
	SELECT * FROM ETHNICITIES;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`ethnicities_add` $$
CREATE PROCEDURE `VOICE`.`ethnicities_add` (IN acode char(2), IN avalue varchar(15))
BEGIN
	INSERT INTO `ETHNICITIES` (`ETHNICITYCD`,`ETHNICITY`) VALUES (acode,avalue);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`ethnicities_delete` $$
CREATE PROCEDURE `VOICE`.`ethnicities_delete` (IN acode char(2))
BEGIN
	DELETE FROM `ETHNICITIES` WHERE `ETHNICITIES`.`ETHNICITYCD` = acode;	
    COMMIT;
END $$
DELIMITER ; $$
/** GENDERS **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`genders_list` $$
CREATE PROCEDURE `VOICE`.`genders_list` ()
BEGIN
	SELECT * FROM GENDERS;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`genders_add` $$
CREATE PROCEDURE `VOICE`.`genders_add` (IN avalue varchar(16), IN bvalue varchar(16))
BEGIN
	INSERT INTO `GENDERS` (`GENDERCD`,`GENDER`) VALUES (avalue,bvalue);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`genders_delete` $$
CREATE PROCEDURE `VOICE`.`genders_delete` (IN gendercd varchar(16))
BEGIN
	DELETE FROM `GENDERS` WHERE `GENDERS`.`GENDERCD` = gendercd;
END $$
DELIMITER ; $$

/** DISTRICTS **/
DELIMITER $$
DROP PROCEDURE IF EXISTS `VOICE`.`districts_list` $$
CREATE PROCEDURE `VOICE`.`districts_list` ()
BEGIN
	SELECT * FROM DISTRICTS;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`districts_add` $$
CREATE PROCEDURE `VOICE`.`districts_add` (IN avalue varchar(128), OUT newid bigint(20))
BEGIN
	INSERT INTO `DISTRICTS` (`DISTRICT`) VALUES (avalue);
    SELECT LAST_INSERT_ID() into newid;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`districts_delete` $$
CREATE PROCEDURE `VOICE`.`districts_delete` (IN anid bigint(20))
BEGIN
	DELETE FROM `DISTRICTS` WHERE `DISTRICTS`.`DISTRICT_ID` = anid;
END $$
DELIMITER ; $$

/** ABILITY SETS **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`abilitysets_list` $$
CREATE PROCEDURE `VOICE`.`abilitysets_list` ()
BEGIN
	SELECT * FROM `ABILITY_SETS`;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilitysets_get` $$
CREATE PROCEDURE `VOICE`.`abilitysets_get` (IN absetid bigint(20))
BEGIN
	SELECT * FROM `ABILITY_SETS` WHERE `ABILITY_SETS`.`ABILITY_SET_ID` = absetid;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilitysets_add` $$
CREATE PROCEDURE `VOICE`.`abilitysets_add` (IN avalue varchar(64))
BEGIN
	INSERT INTO `ABILITY_SETS` (`ABILITY_SETS`) VALUES (avalue);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilitysets_delete` $$
CREATE PROCEDURE `VOICE`.`abilitysets_delete` (IN anid bigint(20))
BEGIN
	DELETE FROM `ABILITY_SETS` WHERE `ABILITY_SETS`.`ABILITY_SET_ID` = anid;
    COMMIT;
END $$
delimiter ; $$

/** ABILITIES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`abilities_list` $$
CREATE PROCEDURE `VOICE`.`abilities_list` ()
BEGIN
	SELECT * FROM ABILITIES;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilities_add` $$
CREATE PROCEDURE `VOICE`.`abilities_add` (IN aname varchar(256),IN adesc longblob)
BEGIN
	INSERT INTO `ABILITIES` (`ABILITY_NAME`,`ABILITY_DESCRIPTION`) VALUES (aname,adesc);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilities_delete` $$
CREATE PROCEDURE `VOICE`.`abilities_delete` (IN anid bigint(20))
BEGIN
	DELETE FROM `ABILITIES` WHERE `ABILITIES`.`ABILITY_ID` = anid;
    COMMIT;
END $$
delimiter ; $$

/** ABILITY SETS - ABILITIES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`abilityset_abilities_list` $$
CREATE PROCEDURE `VOICE`.`abilityset_abilities_list` (IN aid bigint(20))
BEGIN
	SELECT 
		_AS.ABILITY_SET_NAME as 'Ability Set',
		A.ABILITY_NAME as 'Ability',
        A.ABILITY_DESCRIPTION as 'Description',
        ASA.ENABLED as 'Enabled',
        ASA.AVAILABLE as 'Available',
        ASA.VISIBLE as 'Visible'        
        FROM ABILITY_SET_ABILITIES ASA 
			INNER JOIN ABILITIES A ON ASA.ABILITY_ID = A.ABILITY_ID
			INNER JOIN ABILITY_SETS _AS on _AS.ABILITY_SET_ID = ASA.ABILITY_SET_ID
        WHERE ASA.ABILITY_SET_ID = aid;        
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilityset_abilities_add` $$
CREATE PROCEDURE `VOICE`.`abilityset_abilities_add` (IN asid bigint(20),IN aid bigint(20),IN enabled tinyint(1),IN available tinyint(1), IN visible tinyint(1) )
BEGIN
	INSERT INTO `ABILITY_SET_ABILITIES` (ABILITY_SET_ABILITIES,`ABILITY_ID`,`ENABLED`,`AVAILABLE`,`VISIBLE`) VALUES (asid,aid,enabled,available,visible);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`abilityset_abilities_delete` $$
CREATE PROCEDURE `VOICE`.`abilityset_abilities_delete` (IN asid bigint(20),IN aid bigint(20))
BEGIN
	DELETE FROM `ABILITY_SET_ABILITIES` WHERE `ABILITY_SET_ABILITIES`.`ABILITY_SET_ID`=asid and `ABILITY_ID` = aid;
    COMMIT;
END $$
DELIMITER ;

/** ROLES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`roles_list` $$
CREATE PROCEDURE `VOICE`.`roles_list` ()
BEGIN
	SELECT * FROM ROLES;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`roles_add` $$
CREATE PROCEDURE `VOICE`.`roles_add` (IN role varchar(64))
BEGIN
	INSERT INTO `ROLES` (`ROLE_DESCRIPTION`) VALUES (role);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`roles_delete` $$
CREATE PROCEDURE `VOICE`.`roles_delete` (IN roleid bigint(20))
BEGIN
	DELETE FROM `ROLES` WHERE `ROLES`.`ROLE_ID` = roleid;
    COMMIT;
END $$
delimiter ; $$

/** ABILITY SETS - ROLES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`ability_set_roles_list` $$
CREATE PROCEDURE `VOICE`.`ability_set_roles_list` (IN asid bigint(20))
BEGIN
	SELECT
		Abset.ABILITY_SET_NAME as 'Ability Set Name',
		R.ROLE_DESCRIPTION as Role
	FROM ABILITY_SET_ROLES ASR 
    INNER JOIN ROLES R ON R.ROLE_ID = ASR.ROLE_ID
    INNER JOIN ABILITY_SETS Abset on Abset.ABILITY_SET_ID = asid
    WHERE ASR.ABILITY_SET_ID = asid;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`ability_set_roles_add` $$
CREATE PROCEDURE `VOICE`.`ability_set_roles_add` (IN roleid bigint(20), IN absetid bigint(20))
BEGIN
	INSERT INTO `ABILITY_SET_ROLES` (`ROLE_ID`,`ABILITY_SET_ID`) VALUES (roleid,absetid);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`ability_set_roles_delete` $$
CREATE PROCEDURE `VOICE`.`ability_set_roles_delete` (IN roleid bigint(20), IN absetid bigint(20))
BEGIN
	DELETE FROM `ABILITY_SET_ROLES` WHERE `ABILITY_SET_ROLES`.`ROLE_ID` = roleid and `ABILITY_SET_ROLES`.`ABILITY_SET_ID` = absetid;
    COMMIT;
END $$
delimiter ; $$

/** PARTIES, STATE_PARTIES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`state_parties_list` $$
CREATE PROCEDURE `VOICE`.`state_parties_list` (IN statecode varchar(3))
BEGIN
	SELECT 
		SP.PARTYCD,
        P.PARTY
        FROM `STATE_PARTIES` SP
        INNER JOIN `PARTIES` P ON P.PARTYCD = SP.PARTYCD
        WHERE
		SP.STATECD = statecode;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`parties_list` $$
CREATE PROCEDURE `VOICE`.`parties_list` ()
BEGIN
	SELECT
		PARTYCD as 'PARTY CODE',
        PARTY as 'PARTY'
        FROM 
        PARTIES;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`parties_add` $$
CREATE PROCEDURE `VOICE`.`parties_add` (IN partycd varchar(16), IN party varchar(64))
BEGIN
	INSERT INTO `PARTIES` (`PARTYCD`,`PARTY`) VALUES (partycd,party);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`state_parties_add` $$
CREATE PROCEDURE `VOICE`.`state_parties_add` (IN partycd varchar(16), IN statecd varchar(3))
BEGIN
	INSERT INTO `STATE_PARTIES` (`PARTYCD`,`STATECD`) VALUES (partycd,statecd);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`parties_delete` $$
CREATE PROCEDURE `VOICE`.`parties_delete` (IN partycd varchar(16))
BEGIN
	DELETE FROM `PARTIES` WHERE `PARTIES`.`PARTYCD` = partycd;
	COMMIT;
END $$
DROP PROCEDURE IF EXISTS `VOICE`.`state_parties_delete` $$
CREATE PROCEDURE `VOICE`.`state_parties_delete` (IN partycd varchar(16),IN statecd varchar(3))
BEGIN
	DELETE FROM `PARTIES` WHERE `PARTIES`.`PARTYCD` = partycd AND `PARTIES`.`STATECD` = statecd;
	COMMIT;
END $$
delimiter ; $$

/** STATES, COUNTRIES, COUNTIES **/
delimiter $$
DROP PROCEDURE IF EXISTS `VOICE`.`counties_list` $$
CREATE PROCEDURE `VOICE`.`counties_list` (IN statecode varchar(3))
BEGIN
	SELECT 
		C.COUNTYCD as `COUNTYCD`,
        C.COUNTY as `COUNTY`
        FROM `COUNTIES` C
        WHERE
		C.STATECD = statecode;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`state_list` $$
CREATE PROCEDURE `VOICE`.`state_list` (IN countrycd varchar(3))
BEGIN
	SELECT 
		S.StateCD as `State Code`,
        S.State as `State`
        FROM `STATES` S
        WHERE
		S.COUNTRYCD = countrycd;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`countries_list` $$
CREATE PROCEDURE `VOICE`.`countries_list` ()
BEGIN
	SELECT 
		C.CountryCD as `Country Code`,
        C.Country as `Country`
        FROM `COUNTRIES` C;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`countries_add` $$
CREATE PROCEDURE `VOICE`.`countries_add` (IN countrycd varchar(3), IN country varchar(64))
BEGIN
	INSERT INTO `COUNTRIES` (`COUNTRYCD`,`COUNTRY`) VALUES (countrycd,country);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`states_add` $$
CREATE PROCEDURE `VOICE`.`states_add` (IN countrycd varchar(3), IN statecd varchar(3), IN state varchar(64))
BEGIN
	INSERT INTO `STATES` (`STATECD`,`COUNTRYCD`,`STATE`) VALUES (statecd,countrycd,state);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`counties_add` $$
CREATE PROCEDURE `VOICE`.`counties_add` (IN statecd varchar(3), IN countycd varchar(3), IN county varchar(64))
BEGIN
	INSERT INTO `COUNTIES` (`COUNTYCD`,`STATECD`,`COUNTRY`) VALUES (countycd,statecd,country);
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`counties_delete` $$
CREATE PROCEDURE `VOICE`.`counties_delete` (IN countycd varchar(3),IN statecd varchar(3))
BEGIN
	DELETE FROM `COUNTIES` WHERE `COUNTYCD` = countycd and `STATECD`=statecd;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`states_delete` $$
CREATE PROCEDURE `VOICE`.`states_delete` (IN countrycd varchar(3),IN statecd varchar(3))
BEGIN
	DELETE FROM `STATES` WHERE `STATES`.`COUNTRYCD` = countrycd and `STATES`.`STATECD`=statecd;
    COMMIT;
END $$

DROP PROCEDURE IF EXISTS `VOICE`.`countries_delete` $$
CREATE PROCEDURE `VOICE`.`countries_delete` (IN countrycd varchar(3))
BEGIN
	DELETE FROM `COUNTRIES` WHERE `COUNTRIES`.`COUNTRYCD` = countrycd;
    COMMIT;
END $$

delimiter ; $$
COMMIT;