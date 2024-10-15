--    VIEWS CRUD

-- Compte membre
create or replace view saeTest.compteMembre AS
  select * from saeTest._compte natural join saeTest._compteMembre;
  
create or replace function saeTest.createMembre()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone);
  
  insert into saeTest._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,NEW.pseudo);


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createMembre
  INSTEAD OF INSERT ON saeTest.compteMembre
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createMembre();

INSERT INTO saeTest.compteMembre(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,nomMembre,prenomMembre,pseudo)
VALUES('Co-7849','anonymous@gmail.com','anomousny','12','route de Lannion','Trégastel','22730','0651821494','Mabit','Baptiste','The Beast');


-- Compte professionnel publique
create or replace view saeTest.compteProfessionnelPublique AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel;
  
create or replace function saeTest.createProfessionnelPublique()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone);
  
  insert into saeTest._compteProfessionnel(idCompte,denomination)
  values(NEW.idCompte,NEW.denomination);


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPublique
  INSTEAD OF INSERT ON saeTest.compteProfessionnelPublique
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createProfessionnelPublique();
  

INSERT INTO saeTest.compteProfessionnelPublique(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,denomination)
VALUES('Co-7894','batttt@gmail.com','ouieuhhhcestmoi','12','rue du Caca','Saint Hilaire de Loulay','85600','0651821494','Mabit Industries');


-- Compte professionnel prive
create or replace view saeTest.compteProfessionnelPrive AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel natural join saeTest._professionnelPrive;

create or replace function saeTest.createProfessionnelPrive()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone);
  
  insert into saeTest._compteProfessionnel(idCompte,denomination)
  values(NEW.idCompte,NEW.denomination);
  
  insert into saeTest._professionnelPrive(siren,iban,idCompte)
  values(NEW.siren,NEW.iban,NEW.idCompte);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPrive
  INSTEAD OF INSERT ON saeTest.compteProfessionnelPrive
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createProfessionnelPrive();
  

INSERT INTO saeTest.compteProfessionnelPrive(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,denomination,siren,iban)
VALUES('Co-7850','batttt@gmail.com','ouieuhhhcestmoi','26','rue du Prout','Saint Hilaire de Loulay','85600','0651821495','Mabit Coorporation','362 521 879 00034','FR14 2004 1010 0505 0001 3M02 606');


-- Spectacle
create or replace view saeTest.spectacle AS
  select * from saeTest._offre natural join saeTest._spectacle;
-- Parc d'attraction
create or replace view saeTest.parcAttraction AS
  select * from saeTest._offre natural join saeTest._parcAttraction;
-- Visite
create or replace view saeTest.visite AS
  select * from saeTest._offre natural join saeTest._visite;
-- Activite
create or replace view saeTest.activite AS
  select * from saeTest._offre natural join saeTest._activite;
-- Restauration
create or replace view saeTest.restauration AS
  select * from saeTest._offre natural join saeTest._restauration;
  
