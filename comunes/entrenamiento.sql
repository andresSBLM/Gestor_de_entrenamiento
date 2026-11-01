DROP TABLE IF EXISTS ciclismo;
DROP TABLE IF EXISTS practicas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS ejercicios;


CREATE TABLE usuarios(
    id          BIGSERIAL       PRIMARY KEY,
    nombre      VARCHAR(255)    NOT NULL,
    edad        NUMERIC(3)      NOT NULL,
    peso        NUMERIC(8)      NOT NULL,
    email       VARCHAR(255)    UNIQUE NOT NULL,
    puesto      VARCHAR(255)    NOT NULL DEFAULT 'becario',
    password    VARCHAR(255)    NOT NULL
);


CREATE TABLE ejercicios(
    id          BIGSERIAL       PRIMARY KEY,
    nombre      VARCHAR(255)    NOT NULL UNIQUE
);

CREATE TABLE practicas(
    id              BIGSERIAL       PRIMARY KEY,
    fecha           DATE            NOT NULL DEFAULT CURRENT_DATE,
    calorias        INTEGER         NOT NULL,      
    usuario_id      BIGINT          NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    ejercicio_id    BIGINT          NOT NULL REFERENCES ejercicios(id)
);


CREATE TABLE ciclismo(
    id          BIGSERIAL       PRIMARY KEY,
    distancia   INTEGER         NOT NULL,
    tiempo      INTEGER         NOT NULL,
    velocidad   NUMERIC(6,2)    NOT NULL,
    practica_id BIGINT          NOT NULL UNIQUE REFERENCES practicas(id) ON DELETE CASCADE
);



/*Datos de prueba */

INSERT INTO usuarios (nombre, edad, peso, email,puesto, password)
VALUES ('Andrés', 20, 70.50, 'andres@donana.com', 'admin', crypt('1236', gen_salt('bf',12)));

INSERT INTO ejercicios (nombre)
VALUES ('ciclismo');

INSERT INTO practicas (calorias, usuario_id, ejercicio_id)
VALUES (450, 1, 1);

INSERT INTO ciclismo (distancia, tiempo, velocidad, practica_id)
VALUES (25, 60, 25, 1);