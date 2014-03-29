alter table bruger
add column Fornavn varchar(50) after Kode,
add column Efternavn varchar(50) after Fornavn,
add column Adresse1 varchar(50) after Efternavn,
add column Adresse2 varchar(50) after Adresse1,
add column Telefon varchar(50) after Adresse2,
add column Mobil varchar(50) after Telefon,
add column Leder tinyint after Mobil;


update bruger set personid = 83 where brugerid = 70;
update bruger set personid = 81 where brugerid = 62;
update bruger set personid = 65 where brugerid = 60;
update bruger set personid = 80 where brugerid = 61;
update bruger set personid = 72 where brugerid = 51;
update bruger set personid = 82 where brugerid = 66;
update bruger set personid = 78 where brugerid = 59;
update bruger set personid = 77 where brugerid = 64;

insert into bruger (brugernavn, personID) values ('lena',44),('nicoline',36),('peterlind',66),('sarasejer',67),('trine',68),('helenesejer',79);

SET SQL_SAFE_UPDATES = 0;
update bruger, person 
   set bruger.fornavn = person.fornavn,
	   bruger.Efternavn = person.Efternavn,
	   bruger.Adresse1 = person.Adresse1,
       bruger.Adresse2 = person.Adresse2,
	   bruger.Telefon = person.Telefon,
	   bruger.Mobil = person.Mobil,
	   bruger.Leder = person.Leder
 where bruger.personid = person.personid;

delete from personrolle where personid not in (select personid from person);

RENAME TABLE personrolle TO BrugerRolle;
alter table BrugerRolle
add column BrugerID int after PersonID,
add constraint fk_brugerrolle_bruger Foreign Key (BrugerID) references Bruger (BrugerID) on Delete cascade;

SET SQL_SAFE_UPDATES = 0;
update BrugerRolle, bruger set BrugerRolle.brugerid = bruger.brugerid where bruger.personid = BrugerRolle.personid;

Alter table BrugerRolle 
drop primary key,
drop column personid,
add primary key(BrugerID, RolleID);


Rename Table ProgramPerson To ProgramBruger;
alter Table ProgramBruger 
	add column BrugerID int,
    add constraint fk_programbruger_bruger Foreign Key (BrugerID) references Bruger (ID);


Update ProgramBruger, Bruger Set ProgramBruger.BrugerID = Bruger.BrugerID Where Bruger.PersonID = ProgramBruger.PersonID;

Alter Table ProgramBruger
  drop primary key,
  add primary key (ProgramID, BrugerID),
  drop column personID;


Rename Table TeamPerson To TeamBruger;
Alter Table TeamBruger
  Add column BrugerID int,
  Add Constraint fk_TeamBruger_bruger Foreign Key (BrugerID) references Bruger (ID);


Update TeamBruger, Bruger Set TeamBruger.BrugerID = Bruger.BrugerID Where Bruger.PersonID = TeamBruger.PersonID;

Alter Table TeamBruger
  drop primary key,
  add primary key (TeamID, BrugerID),
  drop column personID;

SET SQL_SAFE_UPDATES = 1;

Alter table bruger
  drop column PersonID;

Drop table person;

