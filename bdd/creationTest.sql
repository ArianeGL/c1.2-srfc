drop schema if exists sae cascade;
create schema sae;
set schema 'sae';


--    TABLES

CREATE TABLE IF NOT EXISTS sae._activite
(
   idoffre        varchar(7)   NOT NULL,
   agerequis      integer      default(0),
   dureeactivite  varchar(16)   NOT NULL
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
   urlimage          varchar(200)  default('/docker/sae/data/html/images/photoProfileDefault.png')
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
   PRIMARY KEY (idfacture);

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
   dureevisite  varchar(16)   NOT NULL,
   estguidee    boolean      default(false)
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
  
  --IF (NEW.pseudo is NULL) THEN
  --insert into sae._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  --values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,select concat(nommembre,prenommembre) from sae._compte);
  --ELSE
  insert into sae._compteMembre(idCompte,nomMembre,prenomMembre,pseudo)
  values(NEW.idCompte,NEW.nomMembre,NEW.prenomMembre,NEW.pseudo);
  --END IF;

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
    values(NEW.idoffre,'Spectacle',NEW.nomoffre,NEW.numadresse,
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
    values(NEW.idoffre,'Parc d''attraction',NEW.nomoffre,NEW.numadresse,
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
    values(NEW.idoffre,'Visite',NEW.nomoffre,NEW.numadresse,
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
                               estguidee = NEW.estguidee
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
    values(NEW.idoffre,'Activite',NEW.nomoffre,NEW.numadresse,
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
    values(NEW.idoffre,'Restauration',NEW.nomoffre,NEW.numadresse,
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



-- INSERT TEST

-- ACTIVITES
INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0001','agnes.pinson@gmail.com','motdepasse','3B','Hent Penn ar Pave',
'Le Vieux-Marche',22420,0684947677,'Planete Kayak',519495882,'FR7630001007941234567890181');

-- offre planete kayak a mettre

INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0002','tregor,bicyclette@gmail.com','motdepasse','3','Allee des soupirs',
'Lannion',22300,0712345678,'Tregor Bicyclette');

INSERT INTO activite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,agerequis,dureeactivite)
VALUES('Of-0002','Qui m''aime me suive','3','Allee des soupirs',
'Lannion',22300,0,'Co-0002',
'Montrer que l''on peut réaliser localement de belles balades à vélo, en empruntant de petites 
routes tranquilles et sans trop de montées. Les sorties sont volontairement limitées entre 15 km et 20 km pour permettre à un large 
public familial de se joindre à nous. A partir de 6 ou 7 ans, un enfant à l''aise sur son vélo, peut en 
général parcourir une telle distance sans problème : le rythme est suffisamment lent (adapté aux plus 
faibles), avec des pauses, et le fait d''être en groupe est en général un bon stimulant pour les enfants 
... et les plus grands ! Les plus jeunes peuvent aussi participer en charrette, sur un siège vélo ou bien 
avec une barre de traction.',
12,'6h');
-- A METTRE EN LIEN AVEC CETTE OFFRE donc tables a change obligatoires
-- mais jpp je pars d'un uml caca et un dictionnaire de donnees pas coherant
-- grille tarif a mettre
-- prestations a mettre
-- prestations non incluses a mettre
-- accesibilite
-- tags a mettre

---------------------------------------------------------------------------------------------

-- VISITES
INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0003','mairiedelannion@gmail.com','motdepasse','0','Pl. du General Leclerc',
'Lannion',22300,0296466422,'Mairie de Lannion');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite)
VALUES('Of-0003','Decouverte du centre-ville historique de Lannion','0','Pl. du General Leclerc',
'Lannion',22300,0,'Co-0003',
'Decouverte du centre-ville historique de Lannion',
'A votre rythme');

INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0004','cotedarmor@gmail.com','motdepasse','0','0',
'0',22000,0296626222,'Departement des cotes d''Armor');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite)
VALUES('Of-0004','Parc et Château de la Roche Jagu','0','0',
'Ploezal',22260,0,'Co-0004',
'Le parc est en accès libre et gratuit 7j/7 toute l''année ! Tarifs. Entrée château/expo (automne-hiver 2024) Plein tarif : 4,50 €',
'A votre rythme');

INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0005','leradome.cdt@gmail.com','motdepasse','0','Parc du Radome',
'Pleumeur-Bodou',22560,0296466380,' Fondation d''Entreprise Cité des Télécoms ',493290506,'FR7630001007941234567890182');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite)
VALUES('Of-0005','Cité des Télécoms','0','Parc du Radome',
'Pleumeur-Bodou',22560,0,'Co-0005',
'La Cité des télécoms est un parc français consacré aux télécommunications, des débuts à nos jours. 
Elle est située sur la commune de Pleumeur-Bodou en Bretagne.',
'A votre rythme');

INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0006','armor.navigation@gmail.com','motdepasse','0','Bd Joseph le Bihan',
'Perros-Guirec',22700,0296911000,' Fondation d’Entreprise Cité des Télécoms ',398414698,'FR7630001007941234567890186');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureevisite,estguidee)
VALUES('Of-0006','Excursion vers les 7 Iles','0','Bd Joseph le Bihan',
'Perros-Guirec',22700,0,'Co-0006',
'Découvrez l’archipel des Sept-Îles, la plus grande réserve ornithologique de France, à bord 
d’une vedette ou d’un bateau de la Ligue de Protection des Oiseaux.
Les Vedettes des 7 Iles proposent des excursions et des visites commentées vers l''archipel 
des Sept-Iles, au départ de Perros-Guirec. Le site est protégé et l''accès aux îles réglementé, mais vous 
pourrez néanmoins fouler le sol de l''Île-aux-Moines et admirer les autres îles depuis le bateau. Les 7 
îles sont un véritable sanctuaire pour les oiseaux marins, notamment les goélands, les fous de Bassan 
et les macareux',
'3h',true);

-- A ajouter accesibilite et tags

--------------------------------------------------------------------------

-- Spectacles

INSERT INTO spectacle(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,dureespectacle,placesspectacle)
VALUES('Of-0007','La Magie des arbres,','0','plage de Tourony',
'Perros-Guirec',22700,5.00,'Co-0004',
'Sur le site exceptionnel de la plage de Tourony, au cœur de la côte de Granit rose, ce festival 
concert son et lumière se déroule dans les arbres les 26 et 27 août.
Venez découvrir la Magie des arbres dans ce site exceptionnel de la côte de Granit rose : 
première partie musicale autour du Bagad de Perros-Guirec, puis, à la nuit tombée, assistez au son et 
lumière avec la projection sur des voiles de grands mâts tendues dans les arbres. Ce spectacle mêlant 
effets pyrotechniques, lumières et musique, vous entraînera dans un univers exceptionnel.',
'1h30',300);

-- A ajouter tags

------------------------------------------------------------------------

-- Parc d'atraction

INSERT INTO compteProfessionnelPublique(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination)
VALUES('Co-0007','village.gaulois@gmail.com','motdepasse','0','Parc du Radome',
'Pleumeur-Bodou',22560,0296918395,'Un Village Gaulois pour l’Afrique');

INSERT INTO parcAttraction(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,urlversplan)
VALUES('Of-0008','Le Village Gaulois,','0','Parc du Radome',
'Pleumeur-Bodou',22560,5.00,'Co-0007',
'Petit parc de loisirs pour enfants sur le thème du village avec jeux, activités et lieu de restauration.',
'https://parcduradome.com/wp-content/uploads/2023/02/illustration_parc-768x453.jpg');


-----------------------------------------------------------------------------

-- Restauration

INSERT INTO compteProfessionnelPrive(idcompte,email,motdepasse,
numadressecompte,ruecompte,villecompte,codepostalcompte,telephone,
denomination,siren,iban)
VALUES('Co-0008','contact@la-ville-blanche.com','motdepasse','29','Route de Tréguier',
'Rospez-Lannion',22300,0296370428,' SARL La Ville Blanche ',384552014,'FR7630001007941234567890188');

INSERT INTO visite(idoffre,nomoffre,numadresse,rueoffre,villeoffre,codepostaloffre,
prixmin,idcompte,resume,estguidee,dureevisite)
VALUES('Of-0009','La Ville Blanche','29','route de Tréguier',
'Rospez-Lannion',22300,0,'Co-0008',
'La Ville Blanche, en plein cœur du Trégor, non loin de la côte de Granit Rose vous accueille 
pour le plaisir des papilles.
Ce petit corps de ferme repris par la famille Jaguin est devenu au fil du temps une Maison 
de renom. D’aventures en aventures, la passion de cette cuisine s’est maintenant transmise, des 
souvenirs et des moments se sont déroulés dans cette Maison symbolique de Bretagne. Gorgé 
d’histoire, venez continuer de l’écrire avec Maud et Yvan Guglielmetti.', false, 'A votre rythme');

-- Remettre nbavis et note dans offre
-- + toutes les moyennes dans restauration
-- Mettre les tags restauration

-- Ajouter un attribut estgratuit qui met prixmin a 0 a la premiere insert ou estgratuit est en true
-- Ajouter un complement d'adresse
-- Changer des types d'attribut lies a l'adresse + taille des attributs
