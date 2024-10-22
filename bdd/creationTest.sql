drop schema if exists saetest cascade;
create schema saetest;
set schema 'saetest';


--    TABLES

CREATE TABLE IF NOT EXISTS saetest._activite
(
   idoffre        varchar(7)   NOT NULL,
   agerequis      integer      NOT NULL,
   dureeactivite  varchar(8)   NOT NULL
);

ALTER TABLE saetest._activite
   ADD CONSTRAINT _activite_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS saetest._attraction
(
   idattraction   varchar(7)    NOT NULL,
   nomattraction  varchar(20)   NOT NULL,
   idoffre        varchar(7)    NOT NULL
);

ALTER TABLE saetest._attraction
   ADD CONSTRAINT _attraction_pkey
   PRIMARY KEY (idattraction);

CREATE TABLE IF NOT EXISTS saetest._compte
(
   idcompte          varchar(7)    NOT NULL,
   email             varchar(50)   NOT NULL,
   motdepasse        varchar(20)   NOT NULL,
   numadressecompte  varchar(4)    NOT NULL,
   ruecompte         varchar(50)   NOT NULL,
   villecompte       varchar(30)   NOT NULL,
   codepostalcompte  varchar(5)    NOT NULL,
   telephone         varchar(15)   NOT NULL,
   urlimage          varchar(100)  NOT NULL
);

ALTER TABLE saetest._compte
   ADD CONSTRAINT _compte_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS saetest._comptemembre
(
   idcompte      varchar(7)    NOT NULL,
   nommembre     varchar(20)   NOT NULL,
   prenommembre  varchar(20)   NOT NULL,
   pseudo        varchar(20)   NOT NULL
);

ALTER TABLE saetest._comptemembre
   ADD CONSTRAINT _comptemembre_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS saetest._compteprofessionnel
(
   idcompte      varchar(7)    NOT NULL,
   denomination  varchar(40)   NOT NULL
);

ALTER TABLE saetest._compteprofessionnel
   ADD CONSTRAINT _compteprofessionnel_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS saetest._facture
(
   numfacture       varchar(7)   NOT NULL,
   prixfacture      float8       NOT NULL,
   datefacturation  date         NOT NULL,
   idoffre          varchar(7)   NOT NULL,
   idcompte         varchar(7)   NOT NULL
);

ALTER TABLE saetest._facture
   ADD CONSTRAINT _facture_pkey
   PRIMARY KEY (numfacture);

CREATE TABLE IF NOT EXISTS saetest._imageoffre
(
   urlversimage  varchar(100)   NOT NULL,
   idoffre       varchar(7)     NOT NULL
);

ALTER TABLE saetest._imageoffre
   ADD CONSTRAINT _imageoffre_pkey
   PRIMARY KEY (urlversimage);

CREATE TABLE IF NOT EXISTS saetest._langue
(
   nomlangue  varchar(20)   NOT NULL
);

ALTER TABLE saetest._langue
   ADD CONSTRAINT _langue_pkey
   PRIMARY KEY (nomlangue);

CREATE TABLE IF NOT EXISTS saetest._offre
(
   idoffre          varchar(7)    NOT NULL,
   nomoffre         varchar(50)   NOT NULL,
   numadresse       varchar(5)    NOT NULL,
   rueoffre         varchar(50)   NOT NULL,
   villeoffre       varchar(50)   NOT NULL,
   codepostaloffre  integer       NOT NULL,
   prixmin          float8        NOT NULL,
   datedebut        date          NOT NULL,
   datefin          date          NOT NULL,
   enligne          boolean       NOT NULL,
   datepublication  date          default(CURRENT_DATE) NOT NULL,
   dernieremaj      date          NOT NULL,
   estpremium       boolean       NOT NULL,
   blacklistdispo   integer       NOT NULL,
   idcompte         varchar(7)    NOT NULL,
   resume           varchar(10000)NOT NULL
);

ALTER TABLE saetest._offre
   ADD CONSTRAINT _offre_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS saetest._parcattraction
(
   idoffre        varchar(7)     NOT NULL,
   urlversplan    varchar(100)   NOT NULL,
   nbattractions  integer        NOT NULL,
   ageminparc     integer        NOT NULL
);

ALTER TABLE saetest._parcattraction
   ADD CONSTRAINT _parcattraction_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS saetest._prestation
(
   idpresta           varchar(7)     NOT NULL,
   nomprestation      varchar(50)    NOT NULL,
   descriptionpresta  varchar(5000)  NOT NULL,
   prixpresta         integer        NOT NULL,
   idoffre            varchar(7)     NOT NULL
);

ALTER TABLE saetest._prestation
   ADD CONSTRAINT _prestation_pkey
   PRIMARY KEY (idpresta);

CREATE TABLE IF NOT EXISTS saetest._professionnelprive
(
   siren     varchar(20)   NOT NULL,
   iban      varchar(34)   NOT NULL,
   idcompte  varchar(7)    NOT NULL
);

ALTER TABLE saetest._professionnelprive
   ADD CONSTRAINT _professionnelprive_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS saetest._restauration
(
   idoffre        varchar(7)     NOT NULL,
   urlverscarte   varchar(100)   NOT NULL,
   gammeprix      varchar(3)     NOT NULL,
   moycuisine     float8         NOT NULL,
   moyservice     float8         NOT NULL,
   moyambiance    float8         NOT NULL,
   moyrapportqp   float8         NOT NULL,
   petitdejeuner  boolean        NOT NULL,
   dejeuner       boolean        NOT NULL,
   diner          boolean        NOT NULL,
   boisson        boolean        NOT NULL,
   brunch         boolean        NOT NULL
);

ALTER TABLE saetest._restauration
   ADD CONSTRAINT _restauration_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS saetest._spectacle
(
   idoffre          varchar(7)   NOT NULL,
   dureespectacle   varchar(8)   NOT NULL,
   placesspectacle  integer      NOT NULL
);

ALTER TABLE saetest._spectacle
   ADD CONSTRAINT _spectacle_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS saetest._tag
(
   nomtag  varchar(20)   NOT NULL
);

ALTER TABLE saetest._tag
   ADD CONSTRAINT _tag_pkey
   PRIMARY KEY (nomtag);

CREATE TABLE IF NOT EXISTS saetest._tagrestauration
(
   nomtag  varchar(20)   NOT NULL
);

ALTER TABLE saetest._tagrestauration
   ADD CONSTRAINT _tagrestauration_pkey
   PRIMARY KEY (nomtag);

CREATE TABLE IF NOT EXISTS saetest._tarif
(
   idtarif    varchar(7)    NOT NULL,
   nomtarif   varchar(20)   NOT NULL,
   prixtarif  float8        NOT NULL,
   idoffre    varchar(7)    NOT NULL
);

ALTER TABLE saetest._tarif
   ADD CONSTRAINT _tarif_pkey
   PRIMARY KEY (idtarif);

CREATE TABLE IF NOT EXISTS saetest._visite
(
   idoffre      varchar(7)   NOT NULL,
   dureevisite  varchar(8)   NOT NULL,
   estguidee    boolean      NOT NULL
);

ALTER TABLE saetest._visite
   ADD CONSTRAINT _visite_pkey
   PRIMARY KEY (idoffre);


ALTER TABLE saetest._comptemembre
  ADD CONSTRAINT _comptemembre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES saetest._compte(idcompte);

ALTER TABLE saetest._compteprofessionnel
  ADD CONSTRAINT _compteprofessionnel_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES saetest._compte(idcompte);

ALTER TABLE saetest._facture
  ADD CONSTRAINT _facture_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES saetest._professionnelprive(idcompte);

ALTER TABLE saetest._imageoffre
  ADD CONSTRAINT _imageoffre_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);

ALTER TABLE saetest._offre
  ADD CONSTRAINT _offre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES saetest._compteprofessionnel(idcompte);

ALTER TABLE saetest._parcattraction
  ADD CONSTRAINT _parcattraction_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);

ALTER TABLE saetest._prestation
  ADD CONSTRAINT _prestation_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._activite(idoffre);

ALTER TABLE saetest._professionnelprive
  ADD CONSTRAINT _professionnelprive_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES saetest._compteprofessionnel(idcompte);

ALTER TABLE saetest._restauration
  ADD CONSTRAINT _restauration_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);

ALTER TABLE saetest._spectacle
  ADD CONSTRAINT _spectacle_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);

ALTER TABLE saetest._tarif
  ADD CONSTRAINT _tarif_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);

ALTER TABLE saetest._visite
  ADD CONSTRAINT _visite_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES saetest._offre(idoffre);


--    VIEWS CRUD

-- Compte membre
create or replace view saeTest.compteMembre AS
  select * from saeTest._compte natural join saeTest._compteMembre;
  
create or replace function saeTest.createMembre()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
  insert into saeTest._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,NEW.pseudo);


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createMembre
  INSTEAD OF INSERT ON saeTest.compteMembre
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createMembre();

INSERT INTO saeTest.compteMembre(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage,nomMembre,prenomMembre,pseudo)
VALUES('Co-7849','anonymous@gmail.com','anomousny','12','route de Lannion','Trégastel','22730','0651821494','https://avatarmaker.com/grosse\tete\a\baptiste','Mabit','Baptiste','The Beast');


-- Compte professionnel prive
create or replace view saeTest.compteProfessionnelPrive AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel natural join saeTest._professionnelPrive;

create or replace function saeTest.createProfessionnelPrive()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
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
  

INSERT INTO saeTest.compteProfessionnelPrive(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage,denomination,siren,iban)
VALUES('Co-7850','batttt@gmail.com','ouieuhhhcestmoi','26','rue du Prout','Saint Hilaire de Loulay','85600','0651821495','https://oui.com/img1','Mabit Coorporation','362 521 879 00034','FR14 2004 1010 0505 0001 3M02 606');


-- Compte professionnel publique
create or replace view saeTest.compteProfessionnelPublique AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel
  where idCompte not in (select idCompte from saeTest.compteProfessionnelPrive);
  
create or replace function saeTest.createProfessionnelPublique()
  RETURNS trigger
  AS
$$
BEGIN
  insert into saeTest._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
  insert into saeTest._compteProfessionnel(idCompte,denomination)
  values(NEW.idCompte,NEW.denomination);


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPublique
  INSTEAD OF INSERT ON saeTest.compteProfessionnelPublique
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createProfessionnelPublique();
  

INSERT INTO saeTest.compteProfessionnelPublique(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage,denomination)
VALUES('Co-7894','batttt@gmail.com','ouieuhhhcestmoi','12','rue du Caca','Saint Hilaire de Loulay','85600','0651821494','https://caca.fr/accueil/image\accueil','Mabit Industries');


-- Spectacle
create or replace view saeTest.spectacle AS
  select * from saeTest._offre natural join saeTest._spectacle;

create or replace function saeTest.createUpdateSpectacle()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into saeTest._offre(idoffre,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,
                              dernieremaj,estpremium,blacklistdispo,idcompte,resume)
    values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
            NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte,NEW.resume);
  
    insert into saeTest._spectacle(idoffre,dureespectacle,placesspectacle)
    values(NEW.idoffre,NEW.dureespectacle,NEW.placesspectacle);
    
    RETURN NEW;
    
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE saeTest._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              estpremium = NEW.estpremium,
                              blacklistdispo = NEW.blacklistdispo,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE saeTest._spectacle SET dureespectacle = NEW.dureespectacle,
                                  placesspectacle = NEW.placesspectacle
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateSpectacle
  INSTEAD OF INSERT or UPDATE ON saeTest.spectacle
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createUpdateSpectacle();

INSERT INTO saeTest.spectacle(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,resume,dureespectacle,placesspectacle)
VALUES('Of-0001','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','truc bien','123min',64);
        

-- Parc d'attraction
create or replace view saeTest.parcAttraction AS
  select * from saeTest._offre natural join saeTest._parcAttraction;

create or replace function saeTest.createUpdateParcAttraction()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte,resume)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte,NEW.resume);
  
  insert into saeTest._parcattraction(idoffre,urlversplan,nbattractions,ageminparc)
  values(NEW.idoffre,NEW.urlversplan,NEW.nbattractions,NEW.ageminparc);

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE saeTest._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              estpremium = NEW.estpremium,
                              blacklistdispo = NEW.blacklistdispo,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE saeTest._parcattraction SET urlversplan = NEW.urlversplan,
                                       nbattractions = NEW.nbattractions,
                                       ageminparc = NEW.ageminparc
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateParcAttraction
  INSTEAD OF INSERT ON saeTest.parcAttraction
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createUpdateParcAttraction();

INSERT INTO saeTest.parcAttraction(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,resume,urlversplan,nbattractions,ageminparc)
VALUES('Of-0002','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','truc encore mieux',
        'https://lecacalepipilepopo/attractions',4,7);


-- Visite
create or replace view saeTest.visite AS
  select * from saeTest._offre natural join saeTest._visite;

create or replace function saeTest.createUpdateVisite()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte,resume)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte,NEW.resume);
  
  insert into saeTest._visite(idoffre,dureevisite,estguidee)
  values(NEW.idoffre,NEW.dureevisite,NEW.estguidee);

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE saeTest._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              estpremium = NEW.estpremium,
                              blacklistdispo = NEW.blacklistdispo,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE saeTest._visite SET dureevisite = NEW.dureevisite,
                               estguide = NEW.estguide
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateVisite
  INSTEAD OF INSERT ON saeTest.visite
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createUpdateVisite();

INSERT INTO saeTest.visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,resume,dureevisite,estguidee)
VALUES('Of-0003','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','TRUC PAS OUF','5433min',true);


-- Activite
create or replace view saeTest.activite AS
  select * from saeTest._offre natural join saeTest._activite;

create or replace function saeTest.createUpdateActivite()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte,resume)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte,NEW.resume);
  
  insert into saeTest._activite(idoffre,agerequis,dureeactivite)
  values(NEW.idoffre,NEW.agerequis,NEW.dureeactivite);

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE saeTest._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              estpremium = NEW.estpremium,
                              blacklistdispo = NEW.blacklistdispo,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE saeTest._activite SET agerequis = NEW.agerequis,
                                 dureeactivite = NEW.dureeactivite
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateActivite
  INSTEAD OF INSERT ON saeTest.activite
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createUpdateActivite();

INSERT INTO saeTest.activite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,resume,agerequis,dureeactivite)
VALUES('Of-0004','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','PROUUTTTTT',
        9,'1234min');
-- caca

-- Restauration
create or replace view saeTest.restauration AS
  select * from saeTest._offre natural join saeTest._restauration;
  
create or replace function saeTest.createUpdateRestauration()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
  insert into saeTest._offre(idoffre,nomoffre,numadresse,
                            rueoffre,villeoffre,codepostaloffre,
                            prixmin,datedebut,datefin,enligne,datepublication,
                            dernieremaj,estpremium,blacklistdispo,idcompte,resume)
  values(NEW.idoffre,NEW.nomoffre,NEW.numadresse,
          NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
          NEW.prixmin,NEW.datedebut,NEW.datefin,NEW.enligne,NEW.datepublication,
          NEW.dernieremaj,NEW.estpremium,NEW.blacklistdispo,NEW.idcompte,NEW.resume);
  
  insert into saeTest._restauration(idoffre,urlverscarte,gammeprix,moycuisine,moyservice,
                                    moyambiance,moyrapportqp,petitdejeuner,dejeuner,diner,
                                    boisson,brunch)
  values(NEW.idoffre,NEW.urlverscarte,NEW.gammeprix,NEW.moycuisine,NEW.moyservice,
         NEW.moyambiance,NEW.moyrapportqp,NEW.petitdejeuner,NEW.dejeuner,NEW.diner,
         NEW.boisson,NEW.brunch);

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE saeTest._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              estpremium = NEW.estpremium,
                              blacklistdispo = NEW.blacklistdispo,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE saeTest._restauration SET urlverscarte = NEW.urlverscarte,
                                     gammeprix = NEW.gammeprix,
                                     moycuisine = NEW.moycuisine,
                                     moyservice = NEW.moyservice,
                                     moyambiance = NEW.moyambiance,
                                     moyrapportqp = NEW.moyrapportqp,
                                     petitdejeuner = NEW.petitdejeuner,
                                     dejeuner = NEW.dejeuner,
                                     diner = NEW.diner,
                                     boisson = NEW.boisson,
                                     brunch = NEW.brunch
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateRestauration
  INSTEAD OF INSERT ON saeTest.restauration
  FOR EACH ROW
  EXECUTE PROCEDURE saeTest.createUpdateRestauration();

INSERT INTO saeTest.restauration(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
                              prixmin,datedebut,datefin,enligne,datepublication,dernieremaj,estpremium,
                              blacklistdispo,idcompte,resume,urlverscarte,gammeprix,moycuisine,moyservice,
                              moyambiance,moyrapportqp,petitdejeuner,dejeuner,diner,boisson,brunch)
VALUES('Of-0005','spectacle2oof','23','rue du Pipi','Pipiville',22300,60.0,
        CURRENT_DATE,CURRENT_DATE,true,CURRENT_DATE,CURRENT_DATE,true,2,'Co-7850','Le resto de trop la balle',
        'https://lecacalepipilepopo/attractions','€€',4.4,4.4,4.4,4.4,true,true,true,true,true);
       
