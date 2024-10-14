drop schema if exists saeTest cascade;
create schema saeTest;
set schema 'saeTest';

create table saeTest._offre(
  idOffre varchar(20) primary key,
  nomOffre varchar(50),
  numAdresse varchar(5),
  rueOffre varchar(50),
  villeOffre varchar(50),
  codePostalOffre integer,
  prixOffre float,
  dateDebut date,
  dateFin date,
  enLigne boolean,
  datePublication date,
  derniereMAJ date,
  estPremium boolean,
  blacklistDispo int
);

create table saeTest._tag(
  nomTag varchar(20) primary key
);

create table saeTest._tarif(
  idTarif varchar(7) primary key,
  nomTarif varchar(20),
  prixTarif float,
  idOffre varchar(20) references saeTest._offre
);

create table saeTest._imageOffre(
  urlVersImage varchar(100) primary key,
  idOffre varchar(20) references saeTest._offre
);

create table saeTest._spectacle(
  idOffre varchar(20) primary key,--doit être le même que Offre
  dureeSpectacle varchar(8),
  placesSpectacle integer
);

create table saeTest._parcAttraction(
  idOffre varchar(20) primary key,--doit être le même que Offre
  urlVersPlan varchar(100),
  nbAttractions integer,
  ageMinParc integer
);

create table saeTest._attraction(
  idAttraction varchar(7) primary key,
  nomAttraction varchar(20)
);

create table saeTest._visite(
  idOffre varchar(20) primary key,--doit être le même que Offre
  dureeVisite varchar(8),
  estGuidee boolean
);

create table saeTest._langue(
  nomLangue varchar(20) primary key
);

create table saeTest._activite(
  idOffre varchar(20) primary key,--doit être le même que Offre
  ageRequis integer,
  dureeActivite varchar(8)
);

create table saeTest._prestation(
  idPresta varchar(7) primary key,
  nomPrestation varchar(50),
  descriptionPresta varchar(500),
  prixPresta integer
);

create table saeTest._restauration(
  idOffre varchar(20) primary key,--doit être le même que Offre
  urlVersCarte varchar(100),
  gammePrix varchar(3),
  moyCuisine float,
  moyService float,
  moyAmbiance float,
  moyRapportQP float,
  petitDejeuner boolean,
  dejeuner boolean,
  diner boolean,
  boisson boolean
);

create table saeTest._noteRestaurant(
  idOffre       varchar(20)   not null,
  idAvis        varchar(8)    not null,
  noteService   integer       not null,
  noteCuisine   integer       not null,
  noteAmbiance  integer       not null,
  noteRapportQP integer       not null,
  constraint noteRestaurant_pk
  primary key (idOffre, idAvis)
);



create table saeTest._compte(
  idCompte    varchar(8)    primary key,
  email       varchar(50)   not null,
  motDePasse  varchar(20)   not null,
  numAdresseCompte    varchar(4)    not null,
  rueCompte   varchar(50)   not null,
  villeCompte varchar(30)   not null,
  codePostalCompte    varchar(3)    not null,
  telephone   varchar(15)   not null
);


create table saeTest._compteMembre(
  idCompte    varchar(8)    primary key,
  nomMembre   varchar(20)   not null,
  prenomMembre  varchar(20) not null,
  pseudo      varchar(20)   not null
);


create table saeTest._compteProfessionnel(
  idCompte    varchar(8)    primary key,
  denomination  varchar(40)   not null
);


create table saeTest._avis(
  idAvis      varchar(8)    primary key,
  membre      varchar(8)    not null references saeTest._compteMembre(idCompte),
  messageA    varchar(500)  not null,
  note        float         not null,
  nbLike      integer       not null,
  nbDislike   integer       not null,
  estConsulte boolean       not null,
  blacklist   boolean       not null,
  estSignale  boolean       not null
);

create table saeTest._reponse(
  professionnel varchar(8)    references saeTest._compteProfessionnel(idCompte),
  membre        varchar(8)    references saeTest._compteMembre(idCompte),
  messageRep    varchar(500)  not null,
  constraint reponse_pk
  primary key(professionnel,membre)
);

create table saeTest._professionnelPrive(
  SIREN   varchar(14)   primary key,
  IBAN    varchar(34)   not null,
  idCompte   varchar(8)    not null
);

create table saeTest._facture(
  numFacture    varchar(7)  primary key,
  prixFacture   float       not null,
  dateFacturation   date    not null,
  idOffre       varchar(20) not null references saeTest._offre(idOffre)
);

create or replace view saeTest.compteMembre AS
  select * from saeTest._compte natural join saeTest._compteMembre;
  
create or replace view saeTest.compteProfessionnelPublique AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel;
  
create or replace view saeTest.compteProfessionnelPrive AS
  select * from saeTest._compte natural join saeTest._compteProfessionnel natural join saeTest._professionnelPrive;
