/* 	
	AUTHOR: 		Charles Little (little_charles1@columbusstate.edu)
	CREATION DATE:	22-June-2017
	FILENAME:		VOICE-test.sql
	PURPOSE:		Testing scripts for DML
*/
SET autocommit=0;
USE vorem;
SET @newUserid = 0;
SET @regid = 0;
SET @districtid = 0;
SET @locid = 0;
SET @electionid=0;
SET @measureid=0;
SET @ballotid=0;

-- Create a sample user and registrant for the user.
CALL users_add('testuser2','test@test.com',SHA2('<SaltPlusPassword>',256),'SHA2-256','<Salt>',@newUserid);
CALL registrant_add('TestJane2','TestChandra','TestDoe','','1970-01-01','770-555-1234','GAID','TESTfedid','FEMALE','AS','0','GA','UNS',@newUserid,@regid);

-- Approve the registrant. (note: this should fail due to missing district)
CALL registrant_set_approved(@regid);

-- Add a district, add the registrant to the district.
CALL districts_add('TestDistrict',@districtid);
CALL registrant_set_district(@regid,@districtid);

-- Add a new district, and add the registrant to the district.
CALL districts_add('TestDistrict2',@districtid);
CALL registrant_set_district(@regid,@districtid);

-- Remove the registrant from the last district.
CALL registrant_unset_district(@regid,@districtid);

-- Approve the registrant. (note: this should fail due to missing affirmations)
CALL registrant_set_approved(@regid);

-- Show the affirmations for the registrant's state, and mark them affirmed.
CALL get_affirmations('GA');
CALL registrant_set_affirm(@regid);

-- Approve the registrant. (note: this should fail due to missing location)
CALL registrant_set_approved(@regid);

-- Show basic registrant information.
CALL registrant_get_basic(@regid);

-- add location to registrant
CALL locations_add('123 Main Streeet','Apt # 2B','Marietta','30062','Cobb','GA','USA',@regid,1,1,@locid);

-- Approve the registrant. 
CALL registrant_set_approved(@regid);

-- Remove a location from a registrant (should unset approval)
CALL locations_delete(2,2);

-- add sample election
CALL elections_add('TestElection','A very long description of the election.','2017-06-22 07:00:00', '2017-06-22 19:00:00',@electionid);

-- add district to election
CALL elections_add_district(@electionid,@districtid);

-- add measure to election
CALL elections_add_measure(@electionid,'Who do you choose to be President of the World?',@measureid);
CALL elections_add_measure_option(@electionid,@measureid,'Test Corinthian Leather');
CALL elections_add_measure_option(@electionid,@measureid,'Test President El Corazon');
CALL elections_add_measure_option(@electionid,@measureid,'<b>Why Not Choose Zoidberg?</b>');
-- get eligible elections for registrant. (this should return no results).
CALL registrant_get_elections(@regid);

-- reenable district for registrant.
CALL registrant_set_district(@regid,@districtid);

-- get eligible elections for registrant. .
CALL registrant_get_elections(@regid);

-- manually set the election ID based on user choice; get measures for election.
-- SET @electionid = 1; 
CALL elections_get_measures (@electionid);

-- manually set the measure ID based on user choice; get options for measure.
SET @measureid = 1; 
CALL elections_get_measure_options (@electionid,@measureid);

-- cast ballot
-- manually set option ID based on user choice.
SET @optionid = 2;
CALL elections_cast_ballot (@regid,@electionid,@measureid,@optionid,'Some Sort of IP address capture/signature and other stuff...',@ballotid);

-- print receipt of all registrant's election/measure/ballot choices.
CALL registrant_get_basic(@regid);
CALL elections_get(@electionid);
CALL receipt_generate(@regid,@electionid);
-- NOTE: use Google CAPTCHA in ballot.
-- triggers to audit table.

