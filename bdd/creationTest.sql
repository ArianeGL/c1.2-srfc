drop schema if exists sae cascade;
create schema sae;
set schema 'sae';


--    TABLES

CREATE TABLE IF NOT EXISTS sae._activite
(
   idoffre        varchar(7)   NOT NULL,
   agerequis      integer      default(0),
   dureeactivite  varchar(8)   NOT NULL
);

ALTER TABLE sae._activite
   ADD CONSTRAINT _activite_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._attraction
(
   idattraction   varchar(7)    NOT NULL,
   nomattraction  varchar(20)   NOT NULL,
   idoffre        varchar(7)    NOT NULL
);

ALTER TABLE sae._attraction
   ADD CONSTRAINT _attraction_pkey
   PRIMARY KEY (idattraction);

CREATE TABLE IF NOT EXISTS sae._compte
(
   idcompte          varchar(7)    NOT NULL,
   email             varchar(50)   NOT NULL,
   motdepasse        varchar(20)   NOT NULL,
   numadressecompte  varchar(4)    NOT NULL,
   ruecompte         varchar(50)   NOT NULL,
   villecompte       varchar(30)   NOT NULL,
   codepostalcompte  varchar(5)    NOT NULL,
   telephone         varchar(15)   NOT NULL,
   urlimage          varchar(100)  -- default(srfc-ventsdouest.dev/imageCompte.png)
);

ALTER TABLE sae._compte
   ADD CONSTRAINT _compte_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._comptemembre
(
   idcompte      varchar(7)    NOT NULL,
   nommembre     varchar(20)   NOT NULL,
   prenommembre  varchar(20)   NOT NULL,
   pseudo        varchar(40)   default(select concat(nommembre," ",prenommembre) from sae._compte)
);

ALTER TABLE sae._comptemembre
   ADD CONSTRAINT _comptemembre_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._compteprofessionnel
(
   idcompte      varchar(7)    NOT NULL,
   denomination  varchar(40)   NOT NULL
);

ALTER TABLE sae._compteprofessionnel
   ADD CONSTRAINT _compteprofessionnel_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._facture
(
   idfacture        varchar(7)   NOT NULL,
   prixfacture      real         default(0),
   datefacturation  date         default(CURRENT_DATE),
   idoffre          varchar(7)   NOT NULL,
   idcompte         varchar(7)   NOT NULL
);

ALTER TABLE sae._facture
   ADD CONSTRAINT _facture_pkey
   PRIMARY KEY (numfacture);

CREATE TABLE IF NOT EXISTS sae._imageoffre
(
   urlversimage  varchar(100)   NOT NULL,
   idoffre       varchar(7)     NOT NULL
);

ALTER TABLE sae._imageoffre
   ADD CONSTRAINT _imageoffre_pkey
   PRIMARY KEY (urlversimage);

CREATE TABLE IF NOT EXISTS sae._langue
(
   nomlangue  varchar(20)   NOT NULL
);

ALTER TABLE sae._langue
   ADD CONSTRAINT _langue_pkey
   PRIMARY KEY (nomlangue);

CREATE TABLE IF NOT EXISTS sae._offre
(
   idoffre          varchar(7)    NOT NULL,
   categorie        varchar(30)   NOT NULL,
   nomoffre         varchar(50)   NOT NULL,
   numadresse       varchar(5)    NOT NULL,
   rueoffre         varchar(50)   NOT NULL,
   villeoffre       varchar(50)   NOT NULL,
   codepostaloffre  integer       NOT NULL,
   prixmin          real          default(0),
   datedebut        date          ,
   datefin          date          ,
   enligne          boolean       default(true),
   datepublication  date          default(CURRENT_DATE),
   dernieremaj      date          default(CURRENT_DATE),
   estpremium       boolean       default(false),
   blacklistdispo   integer       default(0),
   idcompte         varchar(7)    NOT NULL,
   resume           varchar(9999) NOT NULL
);

ALTER TABLE sae._offre
   ADD CONSTRAINT _offre_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._parcattraction
(
   idoffre        varchar(7)     NOT NULL,
   urlversplan    varchar(100)   NOT NULL,
   nbattractions  integer        default(1),
   ageminparc     integer        default(0)
);

ALTER TABLE sae._parcattraction
   ADD CONSTRAINT _parcattraction_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._prestation
(
   idpresta           varchar(7)     NOT NULL,
   nomprestation      varchar(50)    NOT NULL,
   descriptionpresta  varchar(9999)  NOT NULL,
   prixpresta         real           default(0),
   idoffre            varchar(7)     NOT NULL
);

ALTER TABLE sae._prestation
   ADD CONSTRAINT _prestation_pkey
   PRIMARY KEY (idpresta);

CREATE TABLE IF NOT EXISTS sae._professionnelprive
(
   siren     varchar(20)   NOT NULL,
   iban      varchar(34)   NOT NULL,
   idcompte  varchar(7)    NOT NULL
);

ALTER TABLE sae._professionnelprive
   ADD CONSTRAINT _professionnelprive_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._restauration
(
   idoffre        varchar(7)     NOT NULL,
   urlverscarte   varchar(100)   NOT NULL,
   gammeprix      varchar(3)     default(0),
   petitdejeuner  boolean        default(false),
   dejeuner       boolean        default(false),
   diner          boolean        default(false),
   boisson        boolean        default(false),
   brunch         boolean        default(false)
);

ALTER TABLE sae._restauration
   ADD CONSTRAINT _restauration_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._spectacle
(
   idoffre          varchar(7)   NOT NULL,
   dureespectacle   varchar(8)   NOT NULL,
   placesspectacle  integer      NOT NULL
);

ALTER TABLE sae._spectacle
   ADD CONSTRAINT _spectacle_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._tag
(
   nomtag  varchar(20)   NOT NULL
);

ALTER TABLE sae._tag
   ADD CONSTRAINT _tag_pkey
   PRIMARY KEY (nomtag);

CREATE TABLE IF NOT EXISTS sae._tagrestauration
(
   nomtag  varchar(20)   NOT NULL

);

ALTER TABLE sae._tagrestauration
   ADD CONSTRAINT _tagrestauration_pkey
   PRIMARY KEY (nomtag);

CREATE TABLE IF NOT EXISTS sae._tarif
(
   idtarif    varchar(7)    NOT NULL,
   nomtarif   varchar(20)   NOT NULL,
   prixtarif  real        NOT NULL,
   idoffre    varchar(7)    NOT NULL
);

ALTER TABLE sae._tarif
   ADD CONSTRAINT _tarif_pkey
   PRIMARY KEY (idtarif);

CREATE TABLE IF NOT EXISTS sae._visite
(
   idoffre      varchar(7)   NOT NULL,
   dureevisite  varchar(8)   NOT NULL,
   estguidee    boolean      NOT NULL
);

ALTER TABLE sae._visite
   ADD CONSTRAINT _visite_pkey
   PRIMARY KEY (idoffre);


ALTER TABLE sae._comptemembre
  ADD CONSTRAINT _comptemembre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compte(idcompte);

ALTER TABLE sae._compteprofessionnel
  ADD CONSTRAINT _compteprofessionnel_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compte(idcompte);

ALTER TABLE sae._facture
  ADD CONSTRAINT _facture_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._professionnelprive(idcompte);

ALTER TABLE sae._imageoffre
  ADD CONSTRAINT _imageoffre_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._offre
  ADD CONSTRAINT _offre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compteprofessionnel(idcompte);

ALTER TABLE sae._parcattraction
  ADD CONSTRAINT _parcattraction_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._prestation
  ADD CONSTRAINT _prestation_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._activite(idoffre);

ALTER TABLE sae._professionnelprive
  ADD CONSTRAINT _professionnelprive_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compteprofessionnel(idcompte);

ALTER TABLE sae._restauration
  ADD CONSTRAINT _restauration_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._spectacle
  ADD CONSTRAINT _spectacle_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._tarif
  ADD CONSTRAINT _tarif_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._visite
  ADD CONSTRAINT _visite_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);


--    VIEWS CRUD

-- Compte membre
create or replace view sae.compteMembre AS
  select * from sae._compte natural join sae._compteMembre;
  
create or replace function sae.createMembre()
  RETURNS trigger
  AS
$$
BEGIN
  insert into sae._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
  -- IF (NEW.pseudo is NULL) THEN
  -- insert into sae._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  -- values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,concat(select concat(nommembre,prenommembre) from sae._compte));
  -- ELSE
  insert into sae._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,NEW.pseudo);
  -- END IF


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createMembre
  INSTEAD OF INSERT ON sae.compteMembre
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createMembre();


-- Compte professionnel prive
create or replace view sae.compteProfessionnelPrive AS
  select * from sae._compte natural join sae._compteProfessionnel natural join sae._professionnelPrive;

create or replace function sae.createProfessionnelPrive()
  RETURNS trigger
  AS
$$
BEGIN
  insert into sae._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
  insert into sae._compteProfessionnel(idCompte,denomination)
  values(NEW.idCompte,NEW.denomination);
  
  insert into sae._professionnelPrive(siren,iban,idCompte)
  values(NEW.siren,NEW.iban,NEW.idCompte);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPrive
  INSTEAD OF INSERT ON sae.compteProfessionnelPrive
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createProfessionnelPrive();
  


-- Compte professionnel publique
create or replace view sae.compteProfessionnelPublique AS
  select * from sae._compte natural join sae._compteProfessionnel
  where idCompte not in (select idCompte from sae.compteProfessionnelPrive);
  
create or replace function sae.createProfessionnelPublique()
  RETURNS trigger
  AS
$$
BEGIN
  insert into sae._compte(idCompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
  values(NEW.idCompte,NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
  
  insert into sae._compteProfessionnel(idCompte,denomination)
  values(NEW.idCompte,NEW.denomination);


  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPublique
  INSTEAD OF INSERT ON sae.compteProfessionnelPublique
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createProfessionnelPublique();
  

-- Spectacle
create or replace view sae.spectacle AS
  select * from sae._offre natural join sae._spectacle;

create or replace function sae.createUpdateSpectacle()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,estpremium,idcompte,resume)
    values(NEW.idoffre,"Spectacle",NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.estpremium,NEW.idcompte,NEW.resume);
  
    insert into sae._spectacle(idoffre,dureespectacle,placesspectacle)
    values(NEW.idoffre,NEW.dureespectacle,NEW.placesspectacle);
    
    UPDATE sae._offre set blacklistdispo = 3 where estpremium = true;

    RETURN NEW;
    
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._spectacle SET dureespectacle = NEW.dureespectacle,
                                  placesspectacle = NEW.placesspectacle
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateSpectacle
  INSTEAD OF INSERT or UPDATE ON sae.spectacle
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateSpectacle();
        

-- Parc d'attraction
create or replace view sae.parcAttraction AS
  select * from sae._offre natural join sae._parcAttraction;

create or replace function sae.createUpdateParcAttraction()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,estpremium,idcompte,resume)
    values(NEW.idoffre,"Parc d'attraction",NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.estpremium,NEW.idcompte,NEW.resume);
  
  insert into sae._parcattraction(idoffre,urlversplan,nbattractions,ageminparc)
  values(NEW.idoffre,NEW.urlversplan,NEW.nbattractions,NEW.ageminparc);

  UPDATE sae._offre set blacklistdispo = 3 where estpremium = true;

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._parcattraction SET urlversplan = NEW.urlversplan,
                                       nbattractions = NEW.nbattractions,
                                       ageminparc = NEW.ageminparc
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateParcAttraction
  INSTEAD OF INSERT ON sae.parcAttraction
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateParcAttraction();


-- Visite
create or replace view sae.visite AS
  select * from sae._offre natural join sae._visite;

create or replace function sae.createUpdateVisite()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,estpremium,idcompte,resume)
    values(NEW.idoffre,"Visite",NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.estpremium,NEW.idcompte,NEW.resume);
  
  insert into sae._visite(idoffre,dureevisite,estguidee)
  values(NEW.idoffre,NEW.dureevisite,NEW.estguidee);

  UPDATE sae._offre set blacklistdispo = 3 where estpremium = true;

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._visite SET dureevisite = NEW.dureevisite,
                               estguide = NEW.estguide
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateVisite
  INSTEAD OF INSERT ON sae.visite
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateVisite();


-- Activite
create or replace view sae.activite AS
  select * from sae._offre natural join sae._activite;

create or replace function sae.createUpdateActivite()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,estpremium,idcompte,resume)
    values(NEW.idoffre,"Activite",NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.estpremium,NEW.idcompte,NEW.resume);
  
  insert into sae._activite(idoffre,agerequis,dureeactivite)
  values(NEW.idoffre,NEW.agerequis,NEW.dureeactivite);

  UPDATE sae._offre set blacklistdispo = 3 where estpremium = true;

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._activite SET agerequis = NEW.agerequis,
                                 dureeactivite = NEW.dureeactivite
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateActivite
  INSTEAD OF INSERT ON sae.activite
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateActivite();


-- Restauration
create or replace view sae.restauration AS
  select * from sae._offre natural join sae._restauration;
  
create or replace function sae.createUpdateRestauration()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,
                              prixmin,estpremium,idcompte,resume)
    values(NEW.idoffre,"Restauration",NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,
            NEW.prixmin,NEW.estpremium,NEW.idcompte,NEW.resume);
  
  insert into sae._restauration(idoffre,urlverscarte,gammeprix,petitdejeuner,dejeuner,diner,
                                    boisson,brunch)
  values(NEW.idoffre,NEW.urlverscarte,NEW.gammeprix,NEW.petitdejeuner,NEW.dejeuner,NEW.diner,
         NEW.boisson,NEW.brunch);

  UPDATE sae._offre set blacklistdispo = 3 where estpremium = true;

  RETURN NEW;
      
  ELSIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume                       
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._restauration SET urlverscarte = NEW.urlverscarte,
                                     gammeprix = NEW.gammeprix,
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
  INSTEAD OF INSERT ON sae.restauration
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateRestauration();