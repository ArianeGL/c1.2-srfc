drop schema if exists saeTest cascade;
create schema saeTest;
set schema 'saeTest';


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
   telephone         varchar(15)   NOT NULL
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
   datepublication  date          NOT NULL,
   dernieremaj      date          NOT NULL,
   estpremium       boolean       NOT NULL,
   blacklistdispo   integer       NOT NULL,
   idcompte         varchar(7)    NOT NULL
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
   descriptionpresta  varchar(500)   NOT NULL,
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


  
