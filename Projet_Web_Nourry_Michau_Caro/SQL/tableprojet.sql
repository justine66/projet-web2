--------------------------------------------------------------------------------
---------------------------- Schema de la base ---------------------------------
--------------------------------------------------------------------------------

-- Création schéma

CREATE SCHEMA projetweb;
SET SCHEMA 'projetweb';

-- Création types

CREATE TYPE type_role AS ENUM ('admin','validateur','annotateur','lecteur');      	-- création d'un type role pour les utilisateur
CREATE TYPE type_etat AS ENUM ('Valide','Refuse','En attente','En cours');	        -- création d'un type pour l'état de validation

      --Utilisateur

-- Création table Utilisateur
CREATE TABLE Utilisateur(
    mail VARCHAR(50), 		            --1 utilisateur/email
    mdp VARCHAR(50) NOT NULL,
    num VARCHAR(15) NOT NULL,	
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    role type_role NOT NULL,			--type role définit en amont
    etat type_etat NOT NULL,
    lastco TIMESTAMP (0),
    CONSTRAINT num_unique UNIQUE (num),	--1 utilisateur/num
    CONSTRAINT num_len CHECK(LENGTH(num)>9),
    PRIMARY KEY (mail)
);

      --Espece

-- Création table Espece
CREATE TABLE Espece(
    nomstandard VARCHAR(50),
    PRIMARY KEY(nomstandard)	--reference des noms d'especes
);

      --Sequence

-- Création table Genome
CREATE TABLE Genome(
    id SERIAL,
    nomgenome VARCHAR(100),	--nom du fichier fourni
    espece VARCHAR(50),	      --nom standard de l'espece
    souche VARCHAR(50),       --nom de la souche
    chrm VARCHAR(50),   --nom du chromosome
    coor VARCHAR(50),        --debut:fin
    seqnuc TEXT NOT NULL,
    FOREIGN KEY (espece) REFERENCES Espece(nomstandard),
    PRIMARY KEY (id)
);

-- Création table CDS
CREATE TABLE Cds(
    id VARCHAR(50),           --identifiant de la sequence
    idgenome INT,
    chrm VARCHAR(50),         --chromosome
    coor VARCHAR(50),
    espece VARCHAR(50),
    souche VARCHAR(50),
    seqnuc TEXT NOT NULL,     --sequence nucléotides des cds
    seqprot TEXT NOT NULL,    --sequence en acides aminés de la cds
    etat type_etat,		-- etat de l'attribution
    FOREIGN KEY (espece) REFERENCES Espece(nomstandard),
    FOREIGN KEY (idgenome) REFERENCES Genome(id),
    PRIMARY KEY(id)
);

      --Annotation

-- Création table Attribution
CREATE TABLE Attribution(
    annot VARCHAR(50),  --mail de l'annotateur, si etat = en cour/valide
    idcds VARCHAR(50),
    attri TIMESTAMP (0),
    FOREIGN KEY (idcds) REFERENCES Cds(id),
    FOREIGN KEY (annot) REFERENCES Utilisateur(mail),
    PRIMARY KEY (idcds)
);

-- Création table Annotation
CREATE TABLE Annotation(
    idcds VARCHAR(50),
    mail VARCHAR (50),		--savoir qui a annoté
    sens VARCHAR(2),          -- 1 ou -1
    genename VARCHAR(50),
    gene_biotype VARCHAR(50),
    transcrit_biotype VARCHAR(50),
    gene_symbol VARCHAR(50),
    description VARCHAR(500),
    dateversion TIMESTAMP (0),
    validation type_etat,
    FOREIGN KEY (mail) REFERENCES Utilisateur(mail),
    FOREIGN KEY (idcds) REFERENCES Cds(id),
    PRIMARY KEY (idcds,dateversion)		--1 annotation par cds
);

-- Création table Validation
CREATE TABLE Validation(
    mail VARCHAR(50),
    idcds VARCHAR(50),
    dateversion TIMESTAMP (0),
    commentaire TEXT,
    FOREIGN KEY (mail) REFERENCES Utilisateur(mail),
    FOREIGN KEY (idcds,dateversion) REFERENCES Annotation(idcds,dateversion),
    PRIMARY KEY (idcds,dateversion)
);
