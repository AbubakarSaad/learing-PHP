-- Address
CREATE TABLE Address(
	Housenum int,
	Street char(50),
	Pcode char(50),
	PRIMARY KEY(Housenum, Street)
);

-- Lives At
CREATE TABLE PersonLivesAt(
	Housenum int,
	Healthnum int NOT NULL,
	pName varchar(50),
	DOB date,
	BloodType char(10),
	Alive char(10),
	PRIMARY KEY(Healthnum),
	FOREIGN KEY(Housenum) REFERENCES Address(Housenum)
);

CREATE TABLE Patient(
	Patientid int,
	PatientRisk varchar(10),
	PRIMARY KEY(Patientid),
	FOREIGN KEY(Patientid) REFERENCES PersonLivesAt(Healthnum)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

-- PHE table
CREATE TABLE PHE(
	PHEid int,
	PRIMARY KEY(PHEid),
	FOREIGN KEY (PHEid) REFERENCES PersonLivesAt(Healthnum)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

-- Emergency Contact
CREATE TABLE EContact(
	Personid int,
	Econtactid int,
	PRIMARY KEY(Personid, Econtactid),
	FOREIGN KEY(Personid) REFERENCES PersonLivesAt(Healthnum),
	FOREIGN KEY(Econtactid) REFERENCES PersonLivesAt(Healthnum)
);

-- Vac Site
CREATE TABLE VacSite(
	Vacsitenum int,
	VsName char(50),
	PRIMARY KEY(Vacsitenum) 
);

-- Index for VacSite
CREATE INDEX For_Vacsite 
ON VacSite(VsName);


-- Locate AT
CREATE TABLE LocateAt(
	Housenum int,
	Vacsitenum int NOT NULL,
	PRIMARY KEY(Housenum, Vacsitenum),
	FOREIGN KEY(Housenum) REFERENCES Address(Housenum),
	FOREIGN KEY(Vacsitenum) REFERENCES VacSite(Vacsitenum)
);

-- Region table
CREATE TABLE Region(
	Regionnum int,
	rName char(50) UNIQUE,
	RuralUrban char(50),
	PRIMARY KEY(Regionnum)
);


-- IN 
CREATE TABLE Resides(
	Vacsitenum int UNIQUE,
	Regionnum int NOT NULL,
	PRIMARY KEY(Vacsitenum, Regionnum),
	FOREIGN KEY(Vacsitenum) REFERENCES VacSite(Vacsitenum),
	FOREIGN KEY(Regionnum) REFERENCES Region(Regionnum)
);

-- Vaccine
CREATE TABLE Vaccine(
	Vid int,
	Cost int,
	PRIMARY KEY(Vid)
);

-- Vaccinate with
CREATE TABLE VaccinateWith(
	Vid int,
	Patientid int,
	VacDate	DATE,
	PRIMARY KEY(Vid,Patientid),
	FOREIGN KEY(Vid) REFERENCES Vaccine(Vid),
	FOREIGN KEY(Patientid) REFERENCES Patient(Patientid)
);

-- Virus
CREATE TABLE Virus(
	Virusnum int,
	Vname char(50),
	Risk char(50),
	PRIMARY KEY(Virusnum)
);

-- infected with
CREATE TABLE InfectedWith(
	Patientid int,
	Virusnum int,
	Treated char(50),
	PRIMARY KEY(Patientid, Virusnum),
	FOREIGN KEY(Patientid) REFERENCES Patient(Patientid),
	FOREIGN KEY(Virusnum) REFERENCES Virus(Virusnum)
);

-- Protects against
CREATE TABLE ProtectsAgainst(
	Vid int NOT NULL,
	Virusnum int,
	PRIMARY KEY(Vid, Virusnum),
	FOREIGN KEY(Vid) REFERENCES Vaccine(Vid),
	FOREIGN KEY(Virusnum) REFERENCES Virus(Virusnum)
);

-- Supervises
CREATE TABLE Supervises(
	Vacsitenum int UNIQUE,
	PHEid int,
	PRIMARY KEY(PHEid),
	FOREIGN KEY(Vacsitenum) REFERENCES VacSite(Vacsitenum),
    FOREIGN KEY(PHEid) REFERENCES PHE(PHEid)
);

-- Assigned to
CREATE TABLE AssignedTo(
	PHEid int NOT NULL,
	Vacsitenum int,
	PRIMARY KEY(PHEid,Vacsitenum),
	FOREIGN KEY(Vacsitenum) REFERENCES VacSite(Vacsitenum),
	FOREIGN KEY(PHEid) REFERENCES PHE(PHEid)
);