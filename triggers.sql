-- trigger for updating
CREATE TRIGGER `allPersonsArePatients` AFTER INSERT ON `personlivesat`
FOR EACH ROW INSERT INTO Patient(Patientid, PatientRisk)
SELECT P.Healthnum, 'M' FROM PersonLivesAt P WHERE P.Healthnum = New.Healthnum AND P.Alive = 'Yes'

-- Address

-- 
CREATE TRIGGER 'PersoncantEcontactself' AFTER INSERT ON 'EContact'
FOR EACH ROW DELETE FROM EContact WHERE Personid = contactid


-- trigger checking. replace the allPersonArePatients trigger
CREATE TRIGGER 'HighRiskPatients' AFTER INSERT ON 'Patient'
FOR EACH ROW 
	IF ((DATEDIFF(CURDATE(),PersonLivesAt.DOB) / 365) < 5) OR (DATEDIFF(CURDATE(),PersonLivesAt.DOB) / 365 > 65) OR 
		PersonLivesAt.BloodType = 'AB'
	THEN 
		-- UPDATE Patient set PatientRisk = "High" WHERE (DATEDIFF(CURDATE(),PersonLivesAt.DOB) / 365 < 5) OR (DATEDIFF(CURDATE(),PersonLivesAt.DOB) / 365) > 65) OR PersonLivesAt.BloodType = 'AB'
		INSERT INTO Patient(Patientid, PatientRisk) 
		SELECT P.Healthnum, 'High' FROM PersonLivesAt P WHERE P.Healthnum = New.Healthnum AND P.BloodType = 'AB' OR 
		(DATEDIFF(CURDATE(), PersonLivesAt.DOB)/365) < 5 OR (DATEDIFF(CURDATE(),PersonLivesAt.DOB)/365) > 65;
	ELSE
		INSERT INTO Patient(Patientid,PatientRisk)
		SELECT P.Healthnum, 'Medium' FROM PersonLivesAt P WHERE P.Healthnum = New.Healthnum;
	END IF

-- trigger deleting patients after 2 weekks
CREATE TRIGGER 'NoLongerPatient' AFTER UPDATE ON 'Vaccinate'
FOR EACH ROW
	IF (DATEDIFF(CURDATE(),Vaccinate.VacDate) > 14) THEN 
		DELETE FROM Patient P, Vaccinate WHERE P.Patientid = Vaccinate.Patientid ;
	END IF