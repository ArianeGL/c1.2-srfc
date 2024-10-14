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

create table saeTest._avis(
  idAvis varchar(8) primary key,
  messageA varchar(500),
  note float,
  nbLike integer,
  nbDislike integer,
  estConsulte boolean,
  blacklist boolean,
  estSignale boolean
);
