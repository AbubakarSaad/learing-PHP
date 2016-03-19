-- * overlap constriants
	-- person could be both 
--PHEs should be able to:

	--Select a region and by that region,
	
		--Find the health numbers, names and addresses of all patients who have received a vaccine against ETCC.
		
			SELECT P.Healthnum, P.pName,P.Housenum, A.Street, A.Pcode
			FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
			WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = V3.Virusnum AND V3.Vname = "ETCC Disease"
			
		--Find the health numbers, names and addresses of all patients who have received a vaccine against ETCC in the past two weeks.
		
			SELECT P.Healthnum, P.pName,P.Housenum, A.Street, A.Pcode 
			FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
			WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = V3.Virusnum AND V3.Vname = 'ETCC Disease' AND (DATEDIFF(CURDATE(), V.VacDate))> 1 AND (DATEDIFF(CURDATE(), V.VacDate)) < 14;
		
		--Find the health numbers, names and addresses of all patients who have net yet received a vaccine against ETCC.
		
			SELECT pla.Healthnum, pla.pName, ad.Housenum, ad.Street, ad.Pcode 
			FROM PersonLivesAt pla NATURAL JOIN Address ad
			WHERE pla.Healthnum NOT IN (SELECT P.Healthnum
    									FROM Region R NATURAL JOIN Resides R1, LocateAt L, PersonLivesAt P, Patient P1, VaccinateWith V,Vaccine V2, ProtectsAgainst P2, Virus V3, Address A 
										WHERE R1.Vacsitenum = L.Vacsitenum AND L.Housenum = A.Housenum AND A .Housenum = P.Housenum AND P.Healthnum = P1.Patientid AND P1.Patientid = V.Patientid AND V.Vid = V2.Vid AND V2.Vid = P2.Vid AND P2.Virusnum = V3.Virusnum AND V3.Vname = 'ETCC');
										
		--View all patients who are in a high-risk category for contracting ETCC. This includes patients who have previously contracted high-risk diseases, or patients in the high-risk age categories. *imran belongs to two regions he is phe in houeue and patient in kanto region
			SELECT DISTINCT pa.Patientid, pa.PatientRisk FROM Region Reg NATURAL JOIN Resides Res NATURAL JOIN LocateAt la NATURAL JOIN Address ad NATURAL JOIN PersonLivesAt pla NATURAL JOIN Patient pa NATURAL JOIN InfectedWith iw NATURAL JOIN Virus Vi WHERE (Vi.Risk = 'High' OR pa.PatientRisk = 'High') AND Reg.Regionnum = '141425'
			
		--View all the vaccination sites in that region, and show the names of their supervisors.
			SELECT DISTINCT pla.pName  
			FROM Region R, Resides Res, VacSite Vac, Supervises sup, PHE ph, PersonLivesAt pla 
			WHERE Res.Vacsitenum = Vac.Vacsitenum AND Vac.Vacsitenum = sup.Vacsitenum AND sup.PHEid = ph.PHEid AND ph.PHEid = pla.Healthnum AND Res.Regionnum = '141425'



	--PHEs should be able to determine, by region, how many patients still require vaccination against ETCC.
		SELECT COUNT(*) AS 'Number of Patients'
		FROM Patient pa 
		WHERE pa.Patientid NOT IN (SELECT pat.Patientid 
								   FROM Resides res, Vacsite vas, LocateAt la, Address ads, PersonLivesAt pla, Patient pat, VaccinateWith vw, Vaccine vac, ProtectsAgainst pag, Virus vi 
								   WHERE res.Vacsitenum = vas.Vacsitenum AND vas.Vacsitenum = la.Vacsitenum AND la.Housenum = ads.Housenum AND ads.Housenum = pla.Housenum AND pla.Healthnum = pat.Patientid AND pat.Patientid = vw.Patientid AND vw.Vid = vac.Vid AND vac.vid = pag.vid AND pag.Virusnum = vi.Virusnum AND vi.Vname = 'ETCC Disease' AND res.Regionnum = '141425')
	--This information should be able to compute the total cost for vaccine doses still needed.
	
	--This information should also be used to compute the proportion of people in a region who are protected against the virus.
		number of needed to vaccinate for that region / total people in that region 


--PHEs should also be able to:

	--View the vaccination history of all people (health number, person name, vaccine id, virus protected.)
	
		SELECT DISTINCT Pat.Patientid, Per.pName, Vac.Vid, Vir.Vname
		FROM PersonLivesAt Per, Patient Pat, VaccinateWith Vac, ProtectsAgainst Pro, Virus Vir
		WHERE Pat.Patientid=Vac.Patientid AND Per.Healthnum=Pat.Patientid AND Vac.Vid=Pro.Vid AND Vir.Virusnum=Pro.Virusnum;

	--Remove a patient from the patient relation if and only if they have been been vaccinated against ETCC and two weeks have passed.
	
		DELETE
		FROM Patient Pat
		WHERE Pat.Patientid IN (
		SELECT DISTINCT Pat.Patientid
		FROM PersonLivesAt Per, Patient Pat, VaccinateWith Vac, ProtectsAgainst Pro, Virus Vir
		WHERE Pat.Patientid=Vac.Patientid AND Per.Healthnum=Pat.Patientid AND Vac.Vid=Pro.Vid AND Vir.Virusnum=Pro.Virusnum AND Vir.Vname='ETCC' AND (DATEDIFF(CURDATE(),Vac.VacDate) >= 14));
	
	--Update emergency contact information for all persons. The contact information should refer to another (living) person.
		
	--View all attributes about all persons in a given age range. Consider using query parameters. (replace 20 and 60 with min and max age)
	
		SELECT DISTINCT *
		FROM PersonLivesAt Per
		WHERE (DATEDIFF(CURDATE(),Per.DOB) > (365 * 20)) AND (DATEDIFF(CURDATE(),Per.DOB) < (365 * 60));
	
	--Select a known viral disease and update its risk category with respect to ETCC. (Replace ebola with target)
	
		UPDATE Virus
		SET Risk='HIGH'
		WHERE Vname='Ebola';
	
	--Select a known, high-risk disease, and view the health numbers, names and addresses of all (susceptible) patients who have previously had that disease.

		SELECT Per.Healthnum, Per.pName, Adr.Housenum, Adr.Street, Adr.Pcode
		FROM PersonLivesAt Per, Patient Pat, InfectedWith Inf, Virus Vir, Address Adr
		WHERE ((Vir.Vname='Ebola Disease' AND Vir.Virusnum=Inf.Virusnum AND Inf.Treated='TREATED' AND Inf.Patientid=Pat.Patientid And Pat.Patientid=Per.Healthnum) AND
		Per.Alive='Alive' AND Per.Housenum=Adr.Housenum);



--All users should be able to:

	--Select a region and view the names and addresses of all vaccination sites in that region.
	
	--Select a region and view the proportion of (living) people who are still not vaccinated against ETCC. They must not be able to see any personal information about other people.

