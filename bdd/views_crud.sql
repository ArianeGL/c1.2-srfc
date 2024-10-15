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

create or replace function saeTest.createSpectacle()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte);
  
  insert into saeTest._spectacle(idoffre,dureespectacle,placesspectacle)
  values(NEW.idoffre,NEW.dureespectacle,NEW.placesspectacle);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createSpectacle
  INSTEAD OF INSERT ON saeTest.spectacle
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createSpectacle();

INSERT INTO saeTest.spectacle(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,dureespectacle,placesspectacle)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','123min',64);
-- Parc d'attraction
create or replace view saeTest.parcAttraction AS
  select * from saeTest._offre natural join saeTest._parcAttraction;

create or replace function saeTest.createParcAttraction()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte);
  
  insert into saeTest._parcattraction(idoffre,urlversplan,nbattractions,ageminparc)
  values(NEW.idoffre,NEW.urlversplan,NEW.nbattractions,NEW.ageminparc);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createParcAttraction
  INSTEAD OF INSERT ON saeTest.parcAttraction
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createParcAttraction();

INSERT INTO saeTest.parcAttraction(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,urlversplan,nbattractions,ageminparc)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850',
        'https://lecacalepipilepopo/attractions',4,7);


-- Visite
create or replace view saeTest.visite AS
  select * from saeTest._offre natural join saeTest._visite;

create or replace function saeTest.createVisite()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte);
  
  insert into saeTest._visite(idoffre,dureevisite,estguidee)
  values(NEW.idoffre,NEW.dureevisite,NEW.estguidee);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createVisite
  INSTEAD OF INSERT ON saeTest.visite
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createVisite();

INSERT INTO saeTest.visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,dureevisite,estguidee)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','5433min',true);


-- Activite
create or replace view saeTest.activite AS
  select * from saeTest._offre natural join saeTest._activite;

create or replace function saeTest.createActivite()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte);
  
  insert into saeTest._activite(idoffre,urlversplan,agerequis,dureeactivite)
  values(NEW.idoffre,NEW.urlversplan,NEW.agerequis,NEW.dureeactivite);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createActivite
  INSTEAD OF INSERT ON saeTest.activite
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createActivite();

INSERT INTO saeTest.activite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,agerequis,dureeactivite)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850',
        9,'1234min');


-- Restauration
create or replace view saeTest.restauration AS
  select * from saeTest._offre natural join saeTest._restauration;
  
create or replace function saeTest.createRestauration()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte);
  
  insert into saeTest._restauration(idoffre,urlverscarte,gammeprix,moycuisine,moyservice,
                                    moyambiance,moyrapportqp,petitdejeuner,dejeuner,diner,
                                    boisson,brunch)
  values(NEW.idoffre,NEW.urlverscarte,NEW.gammeprix,NEW.moycuisine,NEW.moyservice,
         NEW.moyambiance,NEW.moyrapportqp,NEW.petitdejeuner,NEW.dejeuner,NEW.diner,
         NEW.boisson,NEW.brunch);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createRestauration
  INSTEAD OF INSERT ON saeTest.restauration
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createRestauration();

INSERT INTO saeTest.restauration(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,urlverscarte,gammeprix,moycuisine,moyservice,
                              moyambiance,moyrapportqp,petitdejeuner,dejeuner,diner,boisson,brunch)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850',
        'https://lecacalepipilepopo/attractions','€€',4.4,4.4,4.4,4.4,true,true,true,true,true);
