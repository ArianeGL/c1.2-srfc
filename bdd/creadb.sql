drop schema if exists sae cascade;
create schema sae;
set schema 'sae';


--    TABLES

CREATE TABLE IF NOT EXISTS sae._activite
(
   idoffre        varchar(7)   NOT NULL,
   agerequis      integer      default(0),
   dureeactivite  varchar(999)   NOT NULL
);

ALTER TABLE sae._activite
   ADD CONSTRAINT _activite_pkey
   PRIMARY KEY (idoffre);



CREATE TABLE IF NOT EXISTS sae._compte
(
   idcompte          varchar(7)    NOT NULL,
   typecompte        varchar(30)   NOT NULL,
   email             varchar(50)   NOT NULL,
   motdepasse        varchar(20)   NOT NULL,
   numadressecompte  varchar(4)    NOT NULL,
   ruecompte         varchar(50)   NOT NULL,
   villecompte       varchar(30)   NOT NULL,
   codepostalcompte  varchar(5)    NOT NULL,
   telephone         varchar(15)   NOT NULL,
   urlimage          varchar(300)  default('/docker/sae/data/html/IMAGES/photoProfileDefault.png'),
   urlotp            varchar(500),
   otp               boolean       default false
);

ALTER TABLE sae._compte
   ADD CONSTRAINT _compte_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._comptemembre
(
   idcompte      varchar(7)    NOT NULL,
   nommembre     varchar(20)   NOT NULL,
   prenommembre  varchar(20)   NOT NULL,
   pseudo        varchar(80)   NOT NULL
);

ALTER TABLE sae._comptemembre
   ADD CONSTRAINT _comptemembre_pkey
   PRIMARY KEY (idcompte);

CREATE TABLE IF NOT EXISTS sae._compteprofessionnel
(
   idcompte      varchar(7)    NOT NULL,
   denomination  varchar(80)   NOT NULL
);

ALTER TABLE sae._compteprofessionnel
   ADD CONSTRAINT _compteprofessionnel_pkey
   PRIMARY KEY (idcompte);



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
   datedebut        DATE,
   datefin          DATE,
   nbavis           integer       default(0) not null,
   note             real          default(0) not null,
   prixmin          real          default(0) not NULL,
   enligne          boolean       default(true) not null,
   datepublication  date          default(CURRENT_DATE),
   dernieremaj      date          default(CURRENT_DATE),
   abonnement       varchar(15)   default('Gratuit'),
   blacklistdispo   integer       default(0) not null,
   idcompte         varchar(7)    NOT NULL,
   resume           varchar(9999) NOT NULL,
   description      varchar(9999) default(0),
   accesibilite     varchar(999)  default('non adapte pour personne a mobilite reduite'),
   latitude         real          default(0) not null,
   longitude        real          default(0) not null
);

ALTER TABLE sae._offre
   ADD CONSTRAINT _offre_pkey
   PRIMARY KEY (idoffre);

------------------------------------------------------------------------------------
------------------------------------------------------------------------------------
--Options, abonnement et hisorique de mise en ligne

CREATE TABLE IF NOT EXISTS sae._historique
(
  idoffre   varchar(7) NOT NULL REFERENCES sae._offre(idoffre),
  datedebut DATE       NOT NULL,
  datefin   DATE,
  CONSTRAINT _historique_pkey primary key (idoffre,datedebut)
);


CREATE TABLE IF NOT EXISTS sae._abonnement
(
  type    varchar(15) PRIMARY KEY,
  prixHT  real        NOT NULL
);

-- DECLARATION DES ABONNEMENTS
INSERT INTO sae._abonnement(type,prixHT) VALUES('Gratuit',0.00);
INSERT INTO sae._abonnement(type,prixHT) VALUES('Standard',1.67);
INSERT INTO sae._abonnement(type,prixHT) VALUES('Premium',3.34);
--

ALTER TABLE sae._offre
  ADD CONSTRAINT _offre_abonnement_fk
  FOREIGN KEY (abonnement) REFERENCES sae._abonnement(type);

CREATE TABLE IF NOT EXISTS sae._option
(
  type    varchar(15) NOT NULL PRIMARY KEY,
  prixHT  real        NOT NULL 
);


-- DECLARATION DES OPTIONS
INSERT INTO sae._option(type,prixHT) VALUES('A la une',16.68);
INSERT INTO sae._option(type,prixHT) VALUES('En relief',8.34);
--

CREATE TABLE IF NOT EXISTS sae._souscriptionoption
(
  idoffre           varchar(7)    NOT NULL    REFERENCES sae._offre(idoffre),
  option            varchar(15)   NOT NULL    REFERENCES sae._option(type), 
  nbsemaine         INTEGER       NOT NULL,
  semainelancement  DATE          NOT NULL,
  active            boolean       NOT NULL,
  CONSTRAINT _souscriptionoption_pkey PRIMARY KEY (idoffre,option)
);

CREATE TABLE IF NOT EXISTS sae._historiqueoption
(
  idoffre   varchar(7) NOT NULL REFERENCES sae._offre(idoffre),
  option    varchar(15) NOT NULL REFERENCES sae._option(type),
  debutsem  DATE       NOT NULL,
  finsem    DATE,
  prixoption  real,
  CONSTRAINT _historiqueoption_pkey primary key (idoffre,debutsem)
);
------------------------------------------------------------------------------------
------------------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS sae._imageoffre
(
   urlimage  varchar(300)   NOT NULL,
   idoffre   varchar(7)     NOT NULL
);

ALTER TABLE sae._imageoffre
   ADD CONSTRAINT _imageoffre_pkey
   PRIMARY KEY (urlimage);

CREATE TABLE IF NOT EXISTS sae._parcattraction
(
   idoffre        varchar(7)     NOT NULL,
   urlversplan    varchar(300)   NOT NULL,
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
   descriptionpresta  varchar(9999)  ,
   prixpresta         real           default(0)
);

ALTER TABLE sae._prestation
   ADD CONSTRAINT _prestation_pkey
   PRIMARY KEY (idpresta);

CREATE TABLE IF NOT EXISTS sae._prestationincluse
(
   idpresta           varchar(7)     NOT NULL REFERENCES sae._prestation(idpresta),
   idoffre            varchar(7)     NOT NULL REFERENCES sae._activite(idoffre)
);

ALTER TABLE sae._prestationincluse
   ADD CONSTRAINT _prestationincluse_pkey
   PRIMARY KEY (idpresta,idoffre);

CREATE TABLE IF NOT EXISTS sae._prestationnonincluse
(
   idpresta           varchar(7)     NOT NULL REFERENCES sae._prestation(idpresta),
   idoffre            varchar(7)     NOT NULL REFERENCES sae._activite(idoffre)
);

ALTER TABLE sae._prestationnonincluse
   ADD CONSTRAINT _prestationnonincluse_pkey
   PRIMARY KEY (idpresta,idoffre);

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
   gammeprix      varchar(3)     NOT NULL,
   petitdejeuner  boolean        default(false),
   dejeuner       boolean        default(false),
   diner          boolean        default(false),
   boisson        boolean        default(false),
   brunch         boolean        default(false),
   moycuisine     real			 ,
   moyservice     real			 ,
   moyambiance    real			 ,
   moyqp          real			 
);

ALTER TABLE sae._restauration
   ADD CONSTRAINT _restauration_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._spectacle
(
   idoffre          varchar(7)   NOT NULL,
   dureespectacle   varchar(16)   NOT NULL,
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

   CREATE TABLE IF NOT EXISTS sae._tagpouroffre
(
   nomtag  varchar(20)   NOT NULL REFERENCES sae._tag(nomtag),
   idoffre    varchar(7)   NOT NULL REFERENCES sae._offre(idoffre)
);

ALTER TABLE sae._tagpouroffre
   ADD CONSTRAINT _tagpouroffre_pkey
   PRIMARY KEY (nomtag,idoffre);

CREATE TABLE IF NOT EXISTS sae._tagrestauration
(
   nomtag  varchar(20)   NOT NULL
);

ALTER TABLE sae._tagrestauration
   ADD CONSTRAINT _tagrestauration_pkey
   PRIMARY KEY (nomtag);

CREATE TABLE IF NOT EXISTS sae._tagpourrestauration
(
   nomtag  varchar(20)   NOT NULL REFERENCES sae._tagrestauration(nomtag),
   idoffre    varchar(7)   NOT NULL REFERENCES sae._restauration(idoffre)
);

ALTER TABLE sae._tagpourrestauration
   ADD CONSTRAINT _tagpourrestauration_pkey
   PRIMARY KEY (nomtag,idoffre);

CREATE TABLE IF NOT EXISTS sae._tarif
(
   nomtarif   varchar(20)   NOT NULL,
   prixtarif  real        NOT NULL,
   idoffre    varchar(7)   NOT NULL REFERENCES sae._offre(idoffre)
);

ALTER TABLE sae._tarif
   ADD CONSTRAINT _tarif_pkey
   PRIMARY KEY (nomtarif,idoffre);

CREATE TABLE IF NOT EXISTS sae._horraire
(
   jour  varchar(20) NOT NULL,
   heuredebut1   TIME        NOT NULL,
   heurefin1     TIME        NOT NULL,
   heuredebut2   TIME        NOT NULL,
   heurefin2     TIME        NOT NULL,
   idoffre      varchar(7) NOT NULL,
   CONSTRAINT _horraire_pkey PRIMARY KEY (jour,idoffre)
);

CREATE TABLE IF NOT EXISTS sae._visite
(
   idoffre      varchar(7)   NOT NULL,
   dureevisite  varchar(16)   NOT NULL,
   estguidee    boolean      default(false)
);

ALTER TABLE sae._visite
   ADD CONSTRAINT _visite_pkey
   PRIMARY KEY (idoffre);

CREATE TABLE IF NOT EXISTS sae._langueproposes
(
  nomlangue  varchar(20)   NOT NULL REFERENCES sae._langue(nomlangue),
  idoffre      varchar(7)   NOT NULL REFERENCES sae._visite(idoffre)
);

ALTER TABLE sae._langueproposes
   ADD CONSTRAINT _langueproposes_pkey
   PRIMARY KEY (nomlangue,idoffre);

CREATE TABLE IF NOT EXISTS sae._attraction
(
   idattraction   varchar(7)    NOT NULL,
   nomattraction  varchar(20)   NOT NULL,
   idoffre        varchar(7)    NOT NULL REFERENCES sae._parcattraction(idoffre)
);

-----------------------------------------------------------------------
-- SPRINT 2 AVIS + FACTURE

CREATE TABLE IF NOT EXISTS sae._facture
(
  idfacture         varchar(7)    PRIMARY KEY,
  datefacture       date          not null DEFAULT(CURRENT_DATE),
  idoffre           varchar(7)    REFERENCES sae._offre(idoffre),
  moisprestation    integer   not null,
  echeanceReglement date          not null,
  nbjoursenligne    integer       not null,
  abonnementHT      real          not null,
  abonnementTTC     real          not null,
  optionHT          real          not null,
  optionTTC         real          not null,
  totalHT           real          not null,
  totalTTC          real          not null
);


CREATE TABLE IF NOT EXISTS sae._avis
(
  idavis varchar(7) PRIMARY KEY,
  titre   varchar(50) NOT NULL,
  datevisite DATE DEFAULT(CURRENT_DATE),
  contexte varchar(20) NOT NULL,
  idoffre varchar(7) REFERENCES sae._offre(idoffre),
  idcompte varchar(7) REFERENCES sae._comptemembre(idcompte),
  commentaire varchar(9999) not null,
  noteavis real not null,
  nblike integer not null,
  nbdislike integer not null,
  blacklist boolean not null,
  timeunblacklist timestamp,
  signale boolean not null,
  reponse varchar(9999),
  datereponse DATE,
  supprime boolean default false
);

CREATE TABLE IF NOT EXISTS sae._imageavis
(
   urlimage  varchar(300)   NOT NULL PRIMARY KEY,
   idavis   varchar(7)     NOT NULL REFERENCES sae._avis(idavis)
);

CREATE TABLE IF NOT EXISTS sae._notere
(
  idavis varchar(7) PRIMARY KEY REFERENCES sae._avis(idavis),
  idoffre varchar(7) NOT NULL REFERENCES sae._restauration(idoffre),
  noteCuisine real NOT NULL,
  noteService real NOT NULL,
  noteAmbiance real NOT NULL,
  noteRapportQP real NOT NULL
);

CREATE TABLE IF NOT EXISTS sae._aime
(
  idcompte  VARCHAR(7) NOT NULL REFERENCES sae._comptemembre(idcompte),
  idavis  VARCHAR(7)  NOT NULL REFERENCES sae._avis(idavis) ON DELETE CASCADE,
  aime  BOOLEAN NOT NULL,
  CONSTRAINT _aime_pk PRIMARY KEY(idcompte,idavis)
);


-----------------------------------------------------------------------


ALTER TABLE sae._attraction
   ADD CONSTRAINT _attraction_pkey
   PRIMARY KEY (idattraction);

ALTER TABLE sae._comptemembre
  ADD CONSTRAINT _comptemembre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compte(idcompte);

ALTER TABLE sae._compteprofessionnel
  ADD CONSTRAINT _compteprofessionnel_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compte(idcompte);

ALTER TABLE sae._imageoffre
  ADD CONSTRAINT _imageoffre_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._offre
  ADD CONSTRAINT _offre_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compteprofessionnel(idcompte);

ALTER TABLE sae._parcattraction
  ADD CONSTRAINT _parcattraction_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._professionnelprive
  ADD CONSTRAINT _professionnelprive_idcompte_fkey
  FOREIGN KEY (idcompte) REFERENCES sae._compteprofessionnel(idcompte);

ALTER TABLE sae._restauration
  ADD CONSTRAINT _restauration_idoffre_fkey
  FOREIGN KEY (idoffre) REFERENCES sae._offre(idoffre);

ALTER TABLE sae._spectacle
  ADD CONSTRAINT _spectacle_idoffre_fkey
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

  IF (TG_OP = 'INSERT') THEN
    if (NEW.urlimage is null) THEN
      NEW.urlimage = '/docker/sae/data/html/IMAGES/photoProfileDefault.png';
    end if;
    insert into sae._compte(idCompte,typecompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
    values(NEW.idCompte,'Membre',NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
    
    IF(NEW.pseudo is null) THEN
      NEW.pseudo = CONCAT(NEW.nommembre,NEW.prenommembre);
    end if;

    insert into sae._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
    values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,NEW.pseudo);

  ELSEIF (TG_OP = 'UPDATE') THEN

    UPDATE sae._compte SET email = NEW.email,
                           numadressecompte = NEW.numadressecompte,
                           ruecompte = NEW.ruecompte,
                           villecompte = NEW.villecompte,
                           codepostalcompte = NEW.codepostalcompte,
                           telephone = NEW.telephone
    WHERE idcompte = NEW.idcompte;

    UPDATE sae._compteMembre SET nommembre = NEW.nommembre,
                                 prenommembre = NEW.nommembre,
                                 pseudo = NEW.pseudo
    WHERE idcompte = idcompte;
  END IF;

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createMembre
  INSTEAD OF INSERT OR UPDATE ON sae.compteMembre
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
  IF (TG_OP = 'INSERT') THEN

    if (NEW.urlimage is null) THEN
      NEW.urlimage = '/docker/sae/data/html/IMAGES/photoProfileDefault.png';
    end if;
    insert into sae._compte(idCompte,typecompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
    values(NEW.idCompte,'Professionnel prive',NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
    
    insert into sae._compteProfessionnel(idCompte,denomination)
    values(NEW.idCompte,NEW.denomination);
    
    insert into sae._professionnelPrive(siren,iban,idCompte)
    values(NEW.siren,NEW.iban,NEW.idCompte);

  ELSEIF (TG_OP = 'UPDATE') THEN

    UPDATE sae._compte SET email = NEW.email,
                           numadressecompte = NEW.numadressecompte,
                           ruecompte = NEW.ruecompte,
                           villecompte = NEW.villecompte,
                           codepostalcompte = NEW.codepostalcompte,
                           telephone = NEW.telephone
    WHERE idcompte = NEW.idcompte;

    UPDATE sae._compteprofessionnel SET denomination = NEW.denomination WHERE idcompte = NEW.idcompte;

  END IF;
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPrive
  INSTEAD OF INSERT OR UPDATE ON sae.compteProfessionnelPrive
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
  IF (TG_OP = 'INSERT') THEN
    if (NEW.urlimage is null) THEN
      NEW.urlimage = '/docker/sae/data/html/IMAGES/photoProfileDefault.png';
    end if;
    insert into sae._compte(idCompte,typecompte,email,motDePasse,numAdresseCompte,rueCompte,villeCompte,codePostalCompte,telephone,urlimage)
    values(NEW.idCompte,'Professionnel publique',NEW.email,NEW.motDePasse,NEW.numAdresseCompte,NEW.rueCompte,NEW.villeCompte,NEW.codePostalCompte,NEW.telephone,NEW.urlimage);
    
    insert into sae._compteProfessionnel(idCompte,denomination)
    values(NEW.idCompte,NEW.denomination);

  ELSEIF (TG_OP = 'UPDATE') THEN

    UPDATE sae._compte SET email = NEW.email,
                           numadressecompte = NEW.numadressecompte,
                           ruecompte = NEW.ruecompte,
                           villecompte = NEW.villecompte,
                           codepostalcompte = NEW.codepostalcompte,
                           telephone = NEW.telephone
    WHERE idcompte = NEW.idcompte;

    UPDATE sae._compteprofessionnel SET denomination = NEW.denomination WHERE idcompte = NEW.idcompte;

  END IF;
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createProfessionnelPublique
  INSTEAD OF INSERT OR UPDATE ON sae.compteProfessionnelPublique
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
    if (NEW.nbavis is null) then
      NEW.nbavis = 0;
    end if;
    if (NEW.note is null) then
      NEW.note = 0;
    end if;
    if (NEW.prixmin is null) THEN
      NEW.prixmin = 0;
    end if;
    if (NEW.enligne is null) THEN 
      NEW.enligne = true;
    end if;
    if (NEW.blacklistdispo is null) THEN
      NEW.blacklistdispo = 0;
    end if;
    if (NEW.accesibilite is null) THEN
      NEW.accesibilite = 'non adapte pour personne a mobilite reduite';
    end if;
    IF (NEW.idcompte = (select idcompte from sae.compteprofessionnelpublique where idcompte = NEW.idcompte)) THEN
      NEW.abonnement = 'Gratuit';
    elseif (NEW.abonnement is null) THEN
      NEW.abonnement = 'Standard';
    end if;

    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,nbavis,note,
                              prixmin,enligne,blacklistdispo,idcompte,abonnement,
                              resume,description,accesibilite,datepublication
                              ,dernieremaj,datedebut,datefin,latitude,longitude)
    values(NEW.idoffre,'Spectacle',NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,NEW.nbavis,NEW.note,
            NEW.prixmin,NEW.enligne,NEW.blacklistdispo,NEW.idcompte,NEW.abonnement,
            NEW.resume,NEW.description,NEW.accesibilite,CURRENT_DATE,CURRENT_DATE,
            NEW.datedebut,NEW.datefin,NEW.latitude,NEW.longitude);
  
    insert into sae._spectacle(idoffre,dureespectacle,placesspectacle)
    values(NEW.idoffre,NEW.dureespectacle,NEW.placesspectacle);
    
    UPDATE sae._offre set blacklistdispo = 3 where abonnement = 'Premium';

    IF (NEW.enligne = true) THEN
      INSERT INTO sae._historique(idoffre,datedebut)
      VALUES(NEW.idoffre,CURRENT_DATE);
    END IF;

    RETURN NEW;
    
  ELSEIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              nbavis = NEW.nbavis,
                              note = NEW.note,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume,
                              description = NEW.description,
                              accesibilite = NEW.accesibilite,
                              latitude = NEW.latitude,
                              longitude = NEW.longitude
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
create or replace view sae.parcattraction AS
  select * from sae._offre natural join sae._parcattraction;

create or replace function sae.createUpdateParcAttraction()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    if (NEW.nbavis is null) then
      NEW.nbavis = 0;
    end if;
    if (NEW.note is null) then
      NEW.note = 0;
    end if;
    if (NEW.prixmin is null) THEN
      NEW.prixmin = 0;
    end if;
    if (NEW.enligne is null) THEN 
      NEW.enligne = true;
    end if;
    if (NEW.blacklistdispo is null) THEN
      NEW.blacklistdispo = 0;
    end if;
    if (NEW.accesibilite is null) THEN
      NEW.accesibilite = 'non adapte pour personne a mobilite reduite';
    end if;
    IF (NEW.idcompte = (select idcompte from sae.compteprofessionnelpublique where idcompte = NEW.idcompte)) THEN
      NEW.abonnement = 'Gratuit';
    elseif (NEW.abonnement is null) THEN
      NEW.abonnement = 'Standard';
    end if;

    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,nbavis,note,
                              prixmin,enligne,blacklistdispo,idcompte,abonnement,
                              resume,description,accesibilite,datepublication,
                              dernieremaj,datedebut,datefin,latitude,longitude)
    values(NEW.idoffre,'Parc attraction',NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,NEW.nbavis,NEW.note,
            NEW.prixmin,NEW.enligne,NEW.blacklistdispo,NEW.idcompte,NEW.abonnement,
            NEW.resume,NEW.description,NEW.accesibilite,CURRENT_DATE,CURRENT_DATE,
            NEW.datedebut,NEW.datefin,NEW.latitude,NEW.longitude);
  
  if (NEW.ageminparc is null) THEN
    NEW.ageminparc = 0;
  end if;
  insert into sae._parcattraction(idoffre,urlversplan,nbattractions,ageminparc)
  values(NEW.idoffre,NEW.urlversplan,0,NEW.ageminparc);

  UPDATE sae._offre set blacklistdispo = 3 where abonnement = 'Premium';

  IF (NEW.enligne = true) THEN
    INSERT INTO sae._historique(idoffre,datedebut)
    VALUES(NEW.idoffre,CURRENT_DATE);
  END IF;  

  RETURN NEW;
      
  ELSEIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              nbavis = NEW.nbavis,
                              note = NEW.note,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume,
                              description = NEW.description,
                              accesibilite = NEW.accesibilite,
                              latitude = NEW.latitude,
                              longitude = NEW.longitude
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
  INSTEAD OF INSERT OR UPDATE ON sae.parcattraction
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateParcAttraction();

create or replace view sae.attraction AS
  select * from sae._attraction;

create or replace function sae.creaattraction()
  RETURNS trigger
  AS
$$
BEGIN
  INSERT INTO sae._attraction(nomattraction,idoffre)
  VALUES(NEW.nomattraction,NEW.idoffre);

  UPDATE sae._attraction SET nbattraction = nbattraction + 1 where idoffre = NEW.idoffre;
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_creaattraction
  INSTEAD OF INSERT ON sae.attraction
  FOR EACH ROW
  EXECUTE PROCEDURE sae.creaattraction();


-- Visite
create or replace view sae.visite AS
  select * from sae._offre natural join sae._visite;

create or replace function sae.createUpdateVisite()
  RETURNS trigger
  AS
$$
BEGIN
  IF (TG_OP = 'INSERT') THEN
    if (NEW.nbavis is null) then
      NEW.nbavis = 0;
    end if;
    if (NEW.note is null) then
      NEW.note = 0;
    end if;
    if (NEW.prixmin is null) THEN
      NEW.prixmin = 0;
    end if;
    if (NEW.enligne is null) THEN 
      NEW.enligne = true;
    end if;
    if (NEW.blacklistdispo is null) THEN
      NEW.blacklistdispo = 0;
    end if;
    if (NEW.accesibilite is null) THEN
      NEW.accesibilite = 'non adapte pour personne a mobilite reduite';
    end if;
    IF (NEW.idcompte = (select idcompte from sae.compteprofessionnelpublique where idcompte = NEW.idcompte)) THEN
      NEW.abonnement = 'Gratuit';
    elseif (NEW.abonnement is null) THEN
      NEW.abonnement = 'Standard';
    end if;

    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,nbavis,note,
                              prixmin,enligne,blacklistdispo,idcompte,abonnement,
                              resume,description,accesibilite,datepublication,
                              dernieremaj,datedebut,datefin,latitude,longitude)
    values(NEW.idoffre,'Visite',NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,NEW.nbavis,NEW.note,
            NEW.prixmin,NEW.enligne,NEW.blacklistdispo,NEW.idcompte,NEW.abonnement,
            NEW.resume,NEW.description,NEW.accesibilite,CURRENT_DATE,CURRENT_DATE,
            NEW.datedebut,NEW.datefin,NEW.latitude,NEW.longitude);
  
  if (NEW.estguidee is null) THEN
    NEW.estguidee = false;
  end if;
  insert into sae._visite(idoffre,dureevisite,estguidee)
  values(NEW.idoffre,NEW.dureevisite,NEW.estguidee);

  UPDATE sae._offre set blacklistdispo = 3 where abonnement = 'Premium';

    IF (NEW.enligne = true) THEN
      INSERT INTO sae._historique(idoffre,datedebut)
      VALUES(NEW.idoffre,CURRENT_DATE);
    END IF;

  RETURN NEW;
      
  ELSEIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              nbavis = NEW.nbavis,
                              note = NEW.note,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume,
                              description = NEW.description,
                              accesibilite = NEW.accesibilite,
                              latitude = NEW.latitude,
                              longitude = NEW.longitude                    
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._visite SET dureevisite = NEW.dureevisite,
                               estguidee = NEW.estguidee
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateVisite
  INSTEAD OF INSERT OR UPDATE ON sae.visite
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
    if (NEW.nbavis is null) then
      NEW.nbavis = 0;
    end if;
    if (NEW.note is null) then
      NEW.note = 0;
    end if;
    if (NEW.prixmin is null) THEN
      NEW.prixmin = 0;
    end if;
    if (NEW.enligne is null) THEN 
      NEW.enligne = true;
    end if;
    if (NEW.blacklistdispo is null) THEN
      NEW.blacklistdispo = 0;
    end if;
    if (NEW.accesibilite is null) THEN
      NEW.accesibilite = 'non adapte pour personne a mobilite reduite';
    end if;
    IF (NEW.idcompte = (select idcompte from sae.compteprofessionnelpublique where idcompte = NEW.idcompte)) THEN
      NEW.abonnement = 'Gratuit';
    elseif (NEW.abonnement is null) THEN
      NEW.abonnement = 'Standard';
    end if;

    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,nbavis,note,
                              prixmin,enligne,blacklistdispo,idcompte,abonnement,
                              resume,description,accesibilite,datepublication,
                              dernieremaj,datedebut,datefin,latitude,longitude)
    values(NEW.idoffre,'Activite',NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,NEW.nbavis,NEW.note,
            NEW.prixmin,NEW.enligne,NEW.blacklistdispo,NEW.idcompte,NEW.abonnement,
            NEW.resume,NEW.description,NEW.accesibilite,CURRENT_DATE,CURRENT_DATE,
            NEW.datedebut,NEW.datefin,NEW.latitude,NEW.longitude);
  
  if (NEW.agerequis is null) THEN
    NEW.agerequis = 0;
  end if;
  insert into sae._activite(idoffre,agerequis,dureeactivite)
  values(NEW.idoffre,NEW.agerequis,NEW.dureeactivite);

  UPDATE sae._offre set blacklistdispo = 3 where abonnement = 'Premium';

    IF (NEW.enligne = true) THEN
      INSERT INTO sae._historique(idoffre,datedebut)
      VALUES(NEW.idoffre,CURRENT_DATE);
    END IF;

  RETURN NEW;
      
  ELSEIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              nbavis = NEW.nbavis,
                              note = NEW.note,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume,
                              description = NEW.description,
                              accesibilite = NEW.accesibilite,
                              latitude = NEW.latitude,
                              longitude = NEW.longitude
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._activite SET agerequis = NEW.agerequis,
                                 dureeactivite = NEW.dureeactivite
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateActivite
  INSTEAD OF INSERT OR UPDATE ON sae.activite
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
    if (NEW.nbavis is null) then
      NEW.nbavis = 0;
    end if;
    if (NEW.note is null) then
      NEW.note = 0;
    end if;
    if (NEW.prixmin is null) THEN
      NEW.prixmin = 0;
    end if;
    if (NEW.enligne is null) THEN 
      NEW.enligne = true;
    end if;
    if (NEW.blacklistdispo is null) THEN
      NEW.blacklistdispo = 0;
    end if;
    if (NEW.accesibilite is null) THEN
      NEW.accesibilite = 'non adapte pour personne a mobilite reduite';
    end if;
    IF (NEW.idcompte = (select idcompte from sae.compteprofessionnelpublique where idcompte = NEW.idcompte)) THEN
      NEW.abonnement = 'Gratuit';
    elseif (NEW.abonnement is null) THEN
      NEW.abonnement = 'Standard';
    end if;

    insert into sae._offre(idoffre,categorie,nomoffre,numadresse,
                              rueoffre,villeoffre,codepostaloffre,nbavis,note,
                              prixmin,enligne,blacklistdispo,idcompte,abonnement,
                              resume,description,accesibilite,datepublication,
                              dernieremaj,datedebut,datefin,latitude,longitude)
    values(NEW.idoffre,'Restauration',NEW.nomoffre,NEW.numadresse,
            NEW.rueoffre,NEW.villeoffre,NEW.codepostaloffre,NEW.nbavis,NEW.note,
            NEW.prixmin,NEW.enligne,NEW.blacklistdispo,NEW.idcompte,NEW.abonnement,
            NEW.resume,NEW.description,NEW.accesibilite,CURRENT_DATE,CURRENT_DATE,
            NEW.datedebut,NEW.datefin,NEW.latitude,NEW.longitude);
  

  if (NEW.petitdejeuner is null) THEN
    NEW.petitdejeuner = false;
  end if;
  if (NEW.dejeuner is null) THEN
    NEW.dejeuner = false;
  end if;
  if (NEW.diner is null) THEN
    NEW.diner = false;
  end if;
  if (NEW.boisson is null) THEN
    NEW.boisson = false;
  end if;
  if (NEW.brunch is null) THEN
    NEW.brunch = false;
  end if;
   
  insert into sae._restauration(idoffre,urlverscarte,gammeprix,petitdejeuner,dejeuner,diner,
                                    boisson,brunch,moycuisine,moyservice,moyambiance,moyqp)
  values(NEW.idoffre,NEW.urlverscarte,NEW.gammeprix,NEW.petitdejeuner,NEW.dejeuner,NEW.diner,
         NEW.boisson,NEW.brunch,NEW.moycuisine,NEW.moyservice,NEW.moyambiance,NEW.moyqp);

  UPDATE sae._offre set blacklistdispo = 3 where abonnement = 'Premium';

    IF (NEW.enligne = true) THEN
      INSERT INTO sae._historique(idoffre,datedebut)
      VALUES(NEW.idoffre,CURRENT_DATE);
    END IF;

  RETURN NEW;
      
  ELSEIF (TG_OP = 'UPDATE') THEN
    UPDATE sae._offre SET nomoffre = NEW.nomoffre,
                              numadresse = NEW.numadresse,
                              rueoffre = NEW.rueoffre,
                              villeoffre = NEW.villeoffre,
                              codepostaloffre = NEW.codepostaloffre,
                              prixmin = NEW.prixmin,
                              nbavis = NEW.nbavis,
                              note = NEW.note,
                              datedebut = NEW.datedebut,
                              datefin = NEW.datefin,
                              enligne = NEW.enligne,
                              dernieremaj = CURRENT_DATE,
                              resume = NEW.resume,
                              description = NEW.description,
                              accesibilite = NEW.accesibilite,
                              latitude = NEW.latitude,
                              longitude = NEW.longitude                    
    WHERE NEW.idoffre = idoffre;
    
    UPDATE sae._restauration SET urlverscarte = NEW.urlverscarte,
                                     gammeprix = NEW.gammeprix,
                                     petitdejeuner = NEW.petitdejeuner,
                                     dejeuner = NEW.dejeuner,
                                     diner = NEW.diner,
                                     boisson = NEW.boisson,
                                     brunch = NEW.brunch,
                                     moycuisine = NEW.moycuisine,
                                     moyservice = NEW.moyservice,
                                     moyambiance = NEW.moyambiance,
                                     moyqp = NEW.moyqp
    WHERE NEW.idoffre = idoffre;

    RETURN NEW;
  END IF;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_createUpdateRestauration
  INSTEAD OF INSERT OR UPDATE ON sae.restauration
  FOR EACH ROW
  EXECUTE PROCEDURE sae.createUpdateRestauration();


---------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------
-- INSERT TEST

INSERT INTO sae._tag(nomtag)
VALUES('Plein air');
INSERT INTO sae._tag(nomtag)
VALUES('Sport');
INSERT INTO sae._tag(nomtag)
VALUES('Famille');
INSERT INTO sae._tag(nomtag)
VALUES('Nature');
INSERT INTO sae._tag(nomtag)
VALUES('Nautique');
INSERT INTO sae._tag(nomtag)
VALUES('Son et lumiere');
INSERT INTO sae._tag(nomtag)
VALUES('Culture');
INSERT INTO sae._tag(nomtag)
VALUES('Musique');

INSERT INTO sae._prestation(idpresta,nomprestation)
VALUES('Pr-0001','Encadrant');
INSERT INTO sae._prestation(idpresta,nomprestation)
VALUES('Pr-0002','Kit de crevaison');
INSERT INTO sae._prestation(idpresta,nomprestation)
VALUES('Pr-0003','Dejeuner sandwich');
INSERT INTO sae._prestation(idpresta,nomprestation)
VALUES('Pr-0004','Bicyclette');
INSERT INTO sae._prestation(idpresta,nomprestation)
VALUES('Pr-0005','Creme solaire');

-- ACTIVITES
INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0001','agnes.pinson@gmail.com','motdepasse','3B','Hent Penn ar Pave',
'Le Vieux-Marche',22420,0684947677,'Planete Kayak',519495882,'FR7630001007941234567890181');

--------------------------------------------------------------------------------------------------------

INSERT INTO activite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,description,dureeactivite,latitude,longitude)
VALUES('Of-0001','Archipel de Brehat en kayak','3B','Hent Penn ar Pave',
'Le Vieux-Marche',22420,0,'Co-0001',
'Des îles et paysages qui évoluent sous vos yeux au fil de la marée',
'Venez jouer à cache-cache avec les couleurs et les lumières dans les labyrinthes d’îlots et de blocs de granit colorés et découvrir les trésors que Bréhat réserve aux kayakistes curieux !',
'Prennez votre temps',48.603646649102224,-3.4480530127061138);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.nomade-aventure.com/Content/Images/ImgProduits/FRA/7902/458261_QuatreTiers.ori.jpg','Of-0001');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.randokayak.com/wp-content/uploads/2017/06/manipulation-des-kayaks-sur-le-recif-photo-Jean-Marc-Terrade.jpg?v=1615314431','Of-0001');

--------------------------------------------------------------------------------------------------------

INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,urlimage)
VALUES('Co-0002','tregor,bicyclette@gmail.com','motdepasse','3','Allee des soupirs',
'Lannion',22300,0712345678,'Tregor Bicyclette','https://www.tregorbicyclette.fr/images/Logo2024/trebi_couleur_fond_transparent_pour_fond_blanc.png');

INSERT INTO activite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,description,accesibilite,agerequis,dureeactivite,enligne,latitude,longitude)
VALUES('Of-0002','Qui m''aime me suive','3','Allee des soupirs',
'Lannion',22300,0,'Co-0002',
'Montrer que l''on peut réaliser localement de belles balades à vélo, en empruntant de petites routes tranquilles et sans trop de montées.',
'Les sorties sont volontairement limitées entre 15 km et 20 km pour permettre à un large public familial de se joindre à nous. A partir de 6 ou 7 ans, un enfant à l''aise sur son vélo, peut en général parcourir une telle distance sans problème : le rythme est suffisamment lent (adapté aux plus faibles), avec des pauses, et le fait d''être en groupe est en général un bon stimulant pour les enfants ... et les plus grands ! Les plus jeunes peuvent aussi participer en charrette, sur un siège vélo ou bien avec une barre de traction.',
'Le public en situation de handicap est le bienvenu, ne pas hésiter à nous appeler pour préparer la balade',
12,'6h',false,48.7284413,-3.4589695);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQe9oPRAczCLSqErot_8570iR1ZDq-SEejBYg&s','Of-0002');

INSERT INTO sae._prestationincluse(idpresta,idoffre)
VALUES('Pr-0001','Of-0002');
INSERT INTO sae._prestationincluse(idpresta,idoffre)
VALUES('Pr-0002','Of-0002');
INSERT INTO sae._prestationincluse(idpresta,idoffre)
VALUES('Pr-0003','Of-0002');
INSERT INTO sae._prestationnonincluse(idpresta,idoffre)
VALUES('Pr-0004','Of-0002');
INSERT INTO sae._prestationnonincluse(idpresta,idoffre)
VALUES('Pr-0005','Of-0002');

INSERT INTO sae._tarif(nomtarif,prixtarif,idoffre)
VALUES('Adherant enfant',0,'Of-0002');
INSERT INTO sae._tarif(nomtarif,prixtarif,idoffre)
VALUES('Adherant Adulte',2,'Of-0002');
INSERT INTO sae._tarif(nomtarif,prixtarif,idoffre)
VALUES('Non adherant enfant',10,'Of-0002');
INSERT INTO sae._tarif(nomtarif,prixtarif,idoffre)
VALUES('Non adherant Adulte',15,'Of-0002');

INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Plein air','Of-0002');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Sport','Of-0002');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Famille','Of-0002');

---------------------------------------------------------------------------------------------

INSERT INTO sae._langue(nomlangue)
VALUES('francais');
INSERT INTO sae._langue(nomlangue)
VALUES('anglais');

-- VISITES
INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0003','mairiedelannion@gmail.com','motdepasse','0','Pl. du General Leclerc',
'Lannion',22300,0296466422,'Mairie de Lannion');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite,latitude,longitude)
VALUES('Of-0003','Decouverte du centre-ville historique de Lannion','0','Pl. du General Leclerc',
'Lannion',22300,0,'Co-0003',
'Decouverte du centre-ville historique de Lannion',
'A votre rythme',48.732060269978206,-3.458521061903191);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.bretagne-cotedegranitrose.com/app/uploads/lannion-tourisme/2020/03/thumbs/lannion-1-640x640.jpg','Of-0003');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://cdt22.media.tourinsoft.eu/upload/-RY8NEE5.jpg','Of-0003');


INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0004','cotedarmor@gmail.com','motdepasse','0','0',
'0',22000,0296626222,'Departement des cotes d''Armor');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite,enligne,latitude,longitude)
VALUES('Of-0004','Parc et Château de la Roche Jagu','0','0',
'Ploezal',22260,0,'Co-0004',
'Le parc est en accès libre et gratuit 7j/7 toute l''année ! Tarifs. Entrée château/expo (automne-hiver 2024) Plein tarif : 4,50 €',
'A votre rythme',false,48.73320076961851, -3.151034759791902);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://dynamic-media-cdn.tripadvisor.com/media/photo-o/12/bf/77/2d/domaine-departemental.jpg?w=1200&h=-1&s=1','Of-0004');


INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0005','leradome.cdt@gmail.com','motdepasse','0','Parc du Radome',
'Pleumeur-Bodou',22560,0296466380,' Fondation d''Entreprise Cité des Télécoms ',493290506,'FR7630001007941234567890182');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite,enligne,latitude,longitude)
VALUES('Of-0005','Cité des Télécoms','0','Parc du Radome',
'Pleumeur-Bodou',22560,0,'Co-0005',
'La Cité des télécoms est un parc français consacré aux télécommunications, des débuts à nos jours. Elle est située sur la commune de Pleumeur-Bodou en Bretagne.',
'A votre rythme',false,48.782029,-3.524793);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.cite-telecoms.com/voy_content/uploads/2023/06/chimair_cite-des-telecoms-82-scaled.jpg','Of-0005');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.cite-telecoms.com/voy_content/uploads/2019/02/terre_connectee-chimair-14md-scaled.jpg','Of-0005');


INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0006','armor.navigation@gmail.com','motdepasse','0','Bd Joseph le Bihan',
'Perros-Guirec',22700,0296911000,' Fondation d’Entreprise Cité des Télécoms ',398414698,'FR7630001007941234567890186');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,description,accesibilite,dureevisite,estguidee,latitude,longitude)
VALUES('Of-0006','Excursion vers les 7 Iles','0','Bd Joseph le Bihan',
'Perros-Guirec',22700,0,'Co-0006',
'Découvrez l’archipel des Sept-Îles, la plus grande réserve ornithologique de France, à bord d’une vedette ou d’un bateau de la Ligue de Protection des Oiseaux.',
'Les Vedettes des 7 Iles proposent des excursions et des visites commentées vers l''archipel des Sept-Iles, au départ de Perros-Guirec. Le site est protégé et l''accès aux îles réglementé, mais vous pourrez néanmoins fouler le sol de l''Île-aux-Moines et admirer les autres îles depuis le bateau. Les 7 îles sont un véritable sanctuaire pour les oiseaux marins, notamment les goélands, les fous de Bassan et les macareux',
'accueil du public en situation de handicap avec fauteuil roulant manuel',
'3h',true,48.8163256039127,-3.4594912437186736);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.stereden.com/usermedia/photo-635625325660958262-2.jpg?dummy=0&h=800','Of-0006');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.stereden.com/usermedia/photo-638136995194307876-2.jpg?dummy=0&h=800','Of-0006');


INSERT INTO sae._langueproposes(nomlangue,idoffre)
VALUES('francais','Of-0006');
INSERT INTO sae._langueproposes(nomlangue,idoffre)
VALUES('anglais','Of-0006');

INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Plein air','Of-0006');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Nature','Of-0006');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Nautique','Of-0006');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Famille','Of-0006');

--------------------------------------------------------------------------

-- Spectacles

INSERT INTO spectacle(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,description,dureespectacle,placesspectacle,latitude,longitude)
VALUES('Of-0007','La Magie des arbres','0','plage de Tourony',
'Perros-Guirec',22700,5.00,'Co-0004',
'Sur le site exceptionnel de la plage de Tourony, au cœur de la côte de Granit rose, ce festival concert son et lumière se déroule dans les arbres les 26 et 27 août.',
'Venez découvrir la Magie des arbres dans ce site exceptionnel de la côte de Granit rose : première partie musicale autour du Bagad de Perros-Guirec, puis, à la nuit tombée, assistez au son et  lumière avec la projection sur des voiles de grands mâts tendues dans les arbres. Ce spectacle mêlant  effets pyrotechniques, lumières et musique, vous entraînera dans un univers exceptionnel.',
'1h30',300,48.82880315,-3.495851192941339);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://static.actu.fr/uploads/2022/12/da632068fba36fe632068fba311732v-960x640.jpg','Of-0007');


INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Plein air','Of-0007');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Son et lumiere','Of-0007');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Musique','Of-0007');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Famille','Of-0007');
INSERT INTO sae._tagpouroffre(nomtag,idoffre)
VALUES('Culture','Of-0007');

------------------------------------------------------------------------

-- Parc d'atraction

INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0007','village.gaulois@gmail.com','motdepasse','0','Parc du Radome',
'Pleumeur-Bodou',22560,0296918395,'Un Village Gaulois pour l’Afrique');

INSERT INTO parcAttraction(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,urlversplan,latitude,longitude)
VALUES('Of-0008','Le Village Gaulois','0','Parc du Radome',
'Pleumeur-Bodou',22560,5.00,'Co-0007',
'Petit parc de loisirs pour enfants sur le thème du village avec jeux, activités et lieu de restauration.',
'https://parcduradome.com/wp-content/uploads/2023/02/illustration_parc-768x453.jpg',48.782029,-3.524793);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://www.stereden.com/usermedia/photo-634978455280391258-2.jpg?dummy=0&h=800','Of-0008');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRj6LcJM7sGXnG_iuzTgtKLT49YGe_At-cUSQ&s','Of-0008');

-----------------------------------------------------------------------------

-- Restauration

INSERT INTO sae._tagrestauration(nomtag)
VALUES('Francaise');
INSERT INTO sae._tagrestauration(nomtag)
VALUES('Gastronomique');
INSERT INTO sae._tagrestauration(nomtag)
VALUES('Italienne');
INSERT INTO sae._tagrestauration(nomtag)
VALUES('Europeenne');
INSERT INTO sae._tagrestauration(nomtag)
VALUES('Mexicaine');

INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0008','contact@la-ville-blanche.com','motdepasse','29','Route de Tréguier',
'Rospez',22300,0296370428,' SARL La Ville Blanche ',384552014,'FR7630001007941234567890188');

INSERT INTO restauration(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,nbavis,note,idcompte,resume,description,urlverscarte,gammeprix,dejeuner,diner,boisson,
moycuisine,moyservice,moyambiance,moyqp,latitude,longitude)
VALUES('Of-0009','La Ville Blanche','29','route de Tréguier',
'Rospez',22300,26,753,4.5,'Co-0008',
'La Ville Blanche, en plein cœur du Trégor, non loin de la côte de Granit Rose vous accueille pour le plaisir des papilles.',
'Ce petit corps de ferme repris par la famille Jaguin est devenu au fil du temps une Maison de renom. D’aventures en aventures, la passion de cette cuisine s’est maintenant transmise, des souvenirs et des moments se sont déroulés dans cette Maison symbolique de Bretagne. Gorgé d’histoire, venez continuer de l’écrire avec Maud et Yvan Guglielmetti.',
'https://cdn.eat-list.fr/establishment/menu/gallery_menu/22300-rospez/la-ville-blanche_77330_b8c.jpg','€€€',true,true,true,
4.8,4.5,4.3,4.5,48.7442748,-3.4001234);

INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTeHGJOow1oZyvT_l03EpnFCaU1WiIUIQjIVw&s','Of-0009');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSnK0mNLtiaE7zl_kFk2Zvpk0WLNlygY_-L0Q&s','Of-0009');
INSERT INTO sae._imageoffre(urlimage,idoffre) VALUES('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQyAxbUKxwW7QK4iXmH6BSit9nrN50ujH4FMw&s','Of-0009');

INSERT INTO sae._tagpourrestauration(nomtag,idoffre)
VALUES('Francaise','Of-0009');
INSERT INTO sae._tagpourrestauration(nomtag,idoffre)
VALUES('Gastronomique','Of-0009');


------------------------------------------------------------------------------------------------

-- Tag d'une offre
create or replace view sae.tagof AS
  select * from sae._tag natural join sae._tagpouroffre;

create or replace function sae.placerTagof()
  RETURNS trigger
  AS
$$
BEGIN
  if ( (select count(nomtag) from sae._tag where nomtag = NEW.nomtag) = 1 ) THEN
    insert into sae._tagpouroffre(nomtag,idoffre)
    values(NEW.nomtag,NEW.idoffre);
  else
    insert into sae._tag(nomtag)
    values(NEW.nomtag);
    
    insert into sae._tagpouroffre(nomtag,idoffre)
    values(NEW.nomtag,NEW.idoffre);
  END IF;
  
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_placerTagof
  INSTEAD OF INSERT ON sae.tagof
  FOR EACH ROW
  EXECUTE PROCEDURE sae.placerTagof();


-------------------------------------------------------------------------------------------

-- Tag d'un restaurant
create or replace view sae.tagre AS
  select * from sae._tagrestauration natural join sae._tagpourrestauration;

create or replace function sae.placerTagre()
  RETURNS trigger
  AS
$$
BEGIN
  if ( (select count(nomtag) from sae._tagrestauration where nomtag = NEW.nomtag) = 1 ) THEN
    insert into sae._tagpourrestauration(nomtag,idoffre)
    values(NEW.nomtag,NEW.idoffre);
  ELSE
    RAISE EXCEPTION 'tag inconnu';
  END IF;
  
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_placerTagre
  INSTEAD OF INSERT ON sae.tagre
  FOR EACH ROW
  EXECUTE PROCEDURE sae.placerTagre();

-------------------------------------------------------------------------------------------

INSERT INTO
sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, urlimage, nommembre, prenommembre, pseudo)
VALUES ('Co-0009', 'john.doe@gmail.com', 'motdepasse', '54', 'Imp. Covenant Pasquiou', 'Lannion', '22300', '0606060606', 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png', 'Doe', 'John', 'DoeJohn22');


-------------------------------------------------------------------------------------------


--- AVIS 
create or replace view sae.avis AS
  select * from sae._avis 
  where idoffre <> (select idoffre from sae._offre where categorie = 'Restauration');

create or replace function sae.posteravis()
  RETURNS trigger
  AS
$$
BEGIN

  IF (NEW.datevisite is null) THEN 
    NEW.datevisite = CURRENT_DATE;
  END IF;
  INSERT INTO sae._avis(idavis,titre,datevisite,contexte,idoffre,idcompte,
                        commentaire,noteavis,nblike,nbdislike,blacklist,signale)
  VALUES(NEW.idavis,NEW.titre,NEW.datevisite,NEW.contexte,NEW.idoffre,NEW.idcompte,
        NEW.commentaire,NEW.noteavis,0,0,false,false);

  UPDATE sae._offre SET nbavis = nbavis + 1 WHERE idoffre = NEW.idoffre;
  UPDATE sae._offre SET note = (select SUM(noteavis) from sae._avis where idoffre = NEW.idoffre) / nbavis
    WHERE idoffre = NEW.idoffre;

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_posteravis
  INSTEAD OF INSERT ON sae.avis
  FOR EACH ROW
  EXECUTE PROCEDURE sae.posteravis();


-------------------------------------------------------------------------------------------

create or replace view sae.avisre AS
  select * from sae._avis natural join sae._notere;

create or replace function sae.posteravisre()
  RETURNS trigger
  AS
$$
BEGIN

  IF (NEW.datevisite is null) THEN 
    NEW.datevisite = CURRENT_DATE;
  END IF;

  INSERT INTO sae._avis(idavis,titre,datevisite,contexte,idoffre,idcompte,
                        commentaire,noteavis,nblike,nbdislike,blacklist,signale)
  VALUES(NEW.idavis,NEW.titre,NEW.datevisite,NEW.contexte,NEW.idoffre,NEW.idcompte,
        NEW.commentaire,0,0,0,false,false);

  INSERT INTO sae._noterestauration(idavis,idoffre,notecuisine,noteservice,noteambiance,noterapportqp)
  VALUES(NEW.idavis,NEW.idoffre,NEW.notecuisine,NEW.noteservice,NEW.noteambiance,NEW.noterapportqp);

  UPDATE sae._restauration SET 
    moycuisine = (select SUM(notecuisine) from sae._notere where idoffre = NEW.idoffre) / (select count(notecuisine) from sae._notere where idoffre = NEW.idoffre),
    moyservice = (select SUM(noteservice) from sae._notere where idoffre = NEW.idoffre) / (select count(noteservice) from sae._notere where idoffre = NEW.idoffre),
    moyambiance = (select SUM(noteambiance) from sae._notere where idoffre = NEW.idoffre) / (select count(noteambiance) from sae._notere where idoffre = NEW.idoffre),
    moyqp = (select SUM(noterapportqp) from sae._notere where idoffre = NEW.idoffre) / (select count(noterapportqp) from sae._notere where idoffre = NEW.idoffre)
    WHERE idoffre = NEW.idoffre;
    
  UPDATE sae._avis SET noteavis = (select moycuisine + moyservice + moyambiance + moyqp from sae._restauration where idoffre = NEW.idoffre) / 4 where idoffre = NEW.idoffre;

  UPDATE sae._offre SET nbavis = nbavis + 1 WHERE idoffre = NEW.idoffre;
  UPDATE sae._offre SET note = (select SUM(noteavis) from sae._avis where idoffre = NEW.idoffre) / nbavis
    WHERE idoffre = NEW.idoffre;

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_posteravisre
  INSTEAD OF INSERT ON sae.avisre
  FOR EACH ROW
  EXECUTE PROCEDURE sae.posteravisre();


-------------------------------------------------------------------------------------

create or replace view sae.facture as
  select * from sae._facture;

create or replace function sae.genfacture()
  RETURNS trigger
  AS
$$
BEGIN

  IF (EXTRACT(MONTH FROM CURRENT_DATE) = 1) THEN
    NEW.moisprestation = 12;
  ELSE
    NEW.moisprestation = EXTRACT(MONTH FROM CURRENT_DATE) - 1;
  END IF;
  
  NEW.nbjoursenligne = (
      SELECT SUM(datefin - datedebut + 1)
      FROM sae._historique
      WHERE  NEW.moisprestation = EXTRACT(MONTH FROM datedebut) and NEW.moisprestation = EXTRACT(MONTH FROM datefin)
        AND idoffre = NEW.idoffre
  );
  IF (NEW.nbjoursenligne is null) THEN
  	NEW.nbjoursenligne = 0;
  END IF;

  NEW.abonnementHT = NEW.nbjoursenligne * (
      SELECT prixht
      FROM sae._abonnement
      WHERE type = (
          SELECT abonnement
          FROM sae._offre
          WHERE idoffre = NEW.idoffre
      )
  );
  NEW.abonnementTTC =  NEW.abonnementHT * 1.2;

  NEW.optionHT = (
      (SELECT distinct prixHT 
      FROM sae._option 
      NATURAL JOIN sae._souscriptionoption
      WHERE type = 'A la une')
      *
      (SELECT COUNT(*)
      FROM sae._historiqueoption 
      NATURAL JOIN sae._souscriptionoption
      WHERE EXTRACT(MONTH FROM debutsem) = NEW.moisprestation
        AND idoffre = NEW.idoffre
        AND option = 'A la une')
  ) + (
      (SELECT distinct prixHT
      FROM sae._option 
      NATURAL JOIN sae._souscriptionoption
      WHERE type = 'En relief')
      *
      (SELECT COUNT(*)
      FROM sae._historiqueoption 
      NATURAL JOIN sae._souscriptionoption
      WHERE EXTRACT(MONTH FROM debutsem) = NEW.moisprestation
        AND idoffre = NEW.idoffre
        AND option = 'En relief')
  );

  if (NEW.optionHT is null) THEN
    NEW.optionHT = 0;
  END IF;
  NEW.optionTTC = NEW.optionHT * 1.2;


  NEW.totalHT = NEW.abonnementHT + NEW.optionHT;
  NEW.totalTTC = NEW.abonnementTTC + NEW.optionTTC;
  IF(NEW.totalTTC = 0) THEN
    RAISE EXCEPTION 'Aucune facture ne peut etre creer si le prix est null';
  END IF;
  
  INSERT INTO sae._facture(idfacture,datefacture,idoffre,moisprestation,echeancereglement,
                          nbjoursenligne,abonnementHT,abonnementTTC,optionHT,optionTTC,
                          totalHT,totalTTC)
  VALUES(NEW.idfacture,CURRENT_DATE,NEW.idoffre,NEW.moisprestation,CURRENT_DATE + 60,
        NEW.nbjoursenligne,NEW.abonnementHT,NEW.abonnementTTC,NEW.optionHT,NEW.optionTTC,
        NEW.totalHT,NEW.totalTTC);

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_genfacture
  INSTEAD OF INSERT ON sae.facture
  FOR EACH ROW
  EXECUTE PROCEDURE sae.genfacture();

-------------------------------------------------------------------------------------
-------------------------------------------------------------------------------------

create or replace view sae.option AS
  select * from sae._souscriptionoption;

create or replace function sae.prendreoption()
  RETURNS trigger
  AS
$$
BEGIN

  IF (NEW.active is null) THEN
    NEW.active = false;
  END IF;

  INSERT INTO sae._souscriptionoption(idoffre,option,nbsemaine,semainelancement,active)
  VALUES(NEW.idoffre,NEW.option,4,NEW.semainelancement,NEW.active);

  IF (NEW.active = true) THEN
    INSERT INTO sae._historiqueoption(idoffre,option,debutsem,prixoption)
    VALUES(NEW.idoffre,NEW.option,NEW.semainelancement,(select prixht from sae._option where type = NEW.option));
  END IF;

  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_prendreoption
  INSTEAD OF INSERT ON sae.option
  FOR EACH ROW
  EXECUTE PROCEDURE sae.prendreoption();
-------------------------------------------------------------------------------------
-------------------------------------------------------------------------------------

UPDATE sae._offre SET datepublication = '2024-12-01'where idoffre = 'Of-0001';
UPDATE sae._historique set datedebut = '2024-12-01', datefin = '2024-12-08' where idoffre = 'Of-0001';
INSERT INTO sae._historique(idoffre,datedebut,datefin) VALUES('Of-0001','2024-12-25','2024-12-28');
INSERT INTO sae.option(idoffre,option,semainelancement,active)
VALUES('Of-0001','A la une','2024-12-01',false);
INSERT INTO sae._historiqueoption(idoffre,option,debutsem,finsem,prixoption)
VALUES('Of-0001','A la une','2024-12-07','2024-12-14',(select prixht from sae._option where type = 'A la une'));
INSERT INTO sae._historiqueoption(idoffre,option,debutsem,finsem,prixoption)
VALUES('Of-0001','A la une','2024-12-15','2024-12-22', (select prixht from sae._option where type = 'A la une'));
INSERT INTO sae._historiqueoption(idoffre,option,debutsem,finsem,prixoption)
VALUES('Of-0001','A la une','2024-12-22','2024-12-29', (select prixht from sae._option where type = 'A la une'));

INSERT INTO sae.option(idoffre,option,semainelancement,active)
VALUES('Of-0009','En relief',CURRENT_DATE,true);

UPDATE sae._offre SET datepublication = '2024-12-07' where idoffre = 'Of-0006';
UPDATE sae._offre SET datepublication = '2024-12-14' where idoffre = 'Of-0009';


INSERT INTO sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, nommembre, prenommembre)
VALUES ('Co-0010', 'evan.dart@gmail.com', 'motdepasse', '6', 'rue Louis Bleriot', 'Plouneventer', '29400', '0754934884', 'Evan', 'Dart');
INSERT INTO sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, nommembre, prenommembre,urlimage)
VALUES ('Co-0011', 'gamerpfz22@gmail.com', 'sevr@uvo.345?', '3', 'rue Branly', 'Lannion', '22300', '0784050267', 'Pierre', 'Vander',
'https://static4.depositphotos.com/1000824/337/i/450/depositphotos_3379507-stock-photo-man-in-sunglasses.jpg');
INSERT INTO sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, nommembre, prenommembre)
VALUES ('Co-0012', 'mathieumahr@gmail.com', 'matmar.67', '2', 'rue Henry Treville', 'Rennes', '35000', '0654546803', 'Mathieu', 'Mahr');
INSERT INTO sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, nommembre, prenommembre,urlimage)
VALUES ('Co-0013', 'valentineberger69@gmail.com', 'pochaontas?69', '17', 'rue Gilles Fort', 'Lyon', '69000', '0704454693', 'Valentine', 'Berger',
'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBn_e2w-Ne9dtv4yKthY8Z7nKVXB8R9vEtVg&s');
INSERT INTO sae.comptemembre(idcompte, email, motdepasse, numadressecompte, ruecompte, villecompte, codepostalcompte, telephone, nommembre, prenommembre,pseudo)
VALUES ('Co-0014', 'noone@gmail.com', 'Q@S!EF#DXSD!46s1r7', '0', 'Nowhere', 'Nowhere', '0', '0000000000', 'Anonymous', 'Anonymous','Anonymous');

-- INSERT AVIS

INSERT INTO sae.avis(idavis,titre,contexte,idoffre,idcompte,commentaire,noteavis)
VALUES('Av-0001','Magnifique archipel !','En amoureux','Of-0001','Co-0009','Tres sympa a faire je vous le recommande fortement, l archipel est magnifique je vous le conseil si vous voulez faire un petit tour en kayak',4);

INSERT INTO sae.avis(idavis,titre,contexte,idoffre,idcompte,commentaire,noteavis)
VALUES('Av-0002','Kayak abime','Seul','Of-0001','Co-0010','Belle ballade mais le kayak etait legerement abime ce qui etait un petit peu inconfortable a la longue, cela reste cependant un belle endroit breton a visite',3);



create or replace function daily_check_historique_offre(id varchar)
  returns void
  AS
$$
BEGIN
  IF((select enligne from _offre where idoffre = $1) = true) THEN
    IF(EXTRACT(MONTH from CURRENT_DATE) <> EXTRACT(MONTH from CURRENT_DATE + 1)) THEN
      UPDATE _historique SET datefin = CURRENT_DATE WHERE idoffre = $1 and datefin is NULL;
      INSERT INTO _historique(idoffre,datedebut) VALUES($1, CURRENT_DATE + 1);
    END IF;
  ELSE
    UPDATE _historique SET datefin = CURRENT_DATE where idoffre = $1 and datefin is NULL;
  END IF;
END;
$$ language plpgsql;

create or replace function daily_check_historique_option(id varchar, option varchar)
  returns void
  AS
$$
DECLARE
  maxdate DATE;
BEGIN
  select MAX(debutsem) into maxdate from sae._historiqueoption
  where idoffre = $1 and option = $2;
  IF(maxdate + 7 = CURRENT_DATE) THEN
    UPDATE sae._historiqueoption SET finsem = CURRENT_DATE - 1 where idoffre = $1 and option = $2 and debutsem = maxdate;
    UPDATE sae._souscriptionoption SET active = false where idoffre = $1 and option = $2;
  END IF;

  IF((select prixht from sae._historiqueoption where idoffre = $1 and option = $2 and debutsem = maxdate) is null) THEN
    UPDATE sae._historiqueoption SET prixoption = (select prixht from sae._option where type = $2) WHERE idoffre = $1 and option = $2 and debutsem = maxdate;
  END IF;

  IF((select semainelancement from sae._souscriptionoption where idoffre = $1 and option = $2) = CURRENT_DATE) THEN
    UPDATE sae._souscriptionoption SET active = true, 
                                       nbsemaine = nbsemaine - 1
    WHERE idoffre = $1 and option = $2;

    INSERT INTO sae._historiqueoption(idoffre,option,debutsem,prixoption)
    VALUES($1,$2,CURRENT_DATE,(select prixht from sae._option where type = $2));
  END IF;
END;
$$ language plpgsql;

-------------------------------------------------------------
-- Anonymisation des donnees

CREATE OR REPLACE FUNCTION set_idcompte_to_default()
RETURNS TRIGGER AS $$
BEGIN
  UPDATE sae._avis
  SET idcompte = 'Co-0014'
  WHERE idcompte = OLD.idcompte;
  
  UPDATE sae._aime
  SET idcompte = 'Co-0014'
  WHERE idcompte = OLD.idcompte;
  RETURN OLD;
END;
$$ LANGUAGE plpgsql;



CREATE TRIGGER before_delete_set_idcompte
BEFORE DELETE ON sae._comptemembre
FOR EACH ROW
EXECUTE FUNCTION set_idcompte_to_default();


------------------------------------------------------------
-- Gestion des likes / dislike
create or replace view sae.aime AS
  select * from sae._aime;

create or replace function sae.aimeavis()
  RETURNS trigger
  AS
$$
BEGIN
  IF(TG_OP = 'INSERT') THEN
    INSERT INTO sae._aime(idcompte,idavis,aime)
    VALUES(NEW.idcompte,NEW.idavis,NEW.aime);

    IF(NEW.aime = true) THEN
      UPDATE sae._avis SET nblike = nblike + 1 WHERE idavis = NEW.idavis;
    ELSE
      UPDATE sae._avis SET nbdislike = nbdislike + 1 WHERE idavis = NEW.idavis;
    END IF;
  ELSEIF(TG_OP = 'UPDATE')THEN
    IF(NEW.aime = false) THEN
      UPDATE sae._avis SET nblike = nblike - 1, nbdislike = nbdislike + 1 WHERE idavis = NEW.idavis;
      UPDATE sae._aime set aime = false WHERE idcompte = NEW.idcompte and idavis = NEW.idavis;
    ELSEIF(NEW.aime = true) THEN
      UPDATE sae._avis SET nblike = nblike + 1, nbdislike = nbdislike - 1 WHERE idavis = NEW.idavis;
      UPDATE sae._aime set aime = true WHERE idcompte = NEW.idcompte and idavis = NEW.idavis;
    END IF;
  END IF;
  RETURN NEW;
END;
$$ language plpgsql;

CREATE OR REPLACE TRIGGER tg_aimeavis
  INSTEAD OF INSERT OR UPDATE ON sae.aime
  FOR EACH ROW
  EXECUTE PROCEDURE sae.aimeavis();

----------------------------------------------------------
--INSERT INTO sae.facture(idoffre,idfacture) VALUES("Of-0001","Fa-0001");
--INSERT INTO sae.facture(idoffre,idfacture) VALUES("Of-0009","Fa-0002");


--------- SPRINT 4 --------------------------
create or replace function sae.suppravis() returns trigger as $$
declare
  abo varchar(15);
begin
  SELECT INTO abo abonnement FROM sae._offre WHERE idoffre = OLD.idoffre;
  if abo = 'Premium' then
    UPDATE sae._offre SET blacklistdispo = blacklistdispo+1 WHERE idoffre = OLD.idoffre;
  end if;

  UPDATE sae._offre SET supprime = true WHERE idavis = OLD.idavis;
end;
$$ language plpgsql;

create or replace trigger tg_on_delete_avis
instead of delete on sae.avis
for each row
execute function sae.suppravis();
