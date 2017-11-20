DROP TABLE IF EXISTS generos CASCADE;

CREATE TABLE generos
(
    id      BIGSERIAL    PRIMARY KEY
  , genero  VARCHAR(255) NOT NULL UNIQUE

);

DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas
(
    id          BIGSERIAL       PRIMARY KEY
  , titulo      VARCHAR(255)    NOT NULL
  , anyo        NUMERIC(4)
  , sinopsis    TEXT
  , duracion    SMALLINT        DEFAULT 0
                                CONSTRAINT ck_peliculas_duracion_positiva
                                CHECK (coalesce(duracion, 0) >= 0)
  , genero_id   BIGINT          NOT NULL
                                REFERENCES generos (id)
                                ON DELETE NO ACTION
                                ON UPDATE CASCADE
);

-- INSERT

INSERT INTO generos (genero)
VALUES ('Comedia')
     , ('Terror')
     , ('Ciencia-Ficcion')
     , ('Drama')
     , ('Aventura');

INSERT INTO peliculas (titulo,anyo,sinopsis,duracion,genero_id)
VALUES ('Los ultimos jedi',2017,'Va uno y se cae...',204,3)
     , ('Los Goonies',1985,'unos niños se encuentran un tesoro',120,5)
     , ('Aqui llega Condemor',1996,'Mejor no cuento nada...',90,1);
