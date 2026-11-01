
DROP TABLE IF EXISTS ciclismo;
DROP TABLE IF EXISTS practicas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS ejercicios;

CREATE TABLE categorias (
    id          BIGSERIAL       PRIMARY KEY,
    categoria   VARCHAR(255)    NOT NULL UNIQUE
);


CREATE TABLE usuarios(
    id              BIGSERIAL       PRIMARY KEY,
    nombre          VARCHAR(255)    NOT NULL,
    edad            NUMERIC(3)      NOT NULL,
    peso            NUMERIC(8)      NOT NULL,
    email           VARCHAR(255)    UNIQUE NOT NULL,
    categoria_id    BIGINT          NOT NULL DEFAULT 2 REFERENCES categorias(id),
    password        VARCHAR(255)    NOT NULL
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
INSERT INTO categorias (categoria)
VALUES ('admin'),
       ('usuario'),
       ('usuario_premium');

INSERT INTO usuarios (nombre, edad, peso, email, categoria_id, password)
VALUES ('Andrés', 20, 70.50, 'andres@donana.com', 1, crypt('1236', gen_salt('bf',12))),
       ('Andrés', 20, 70.50, 'andres@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Lucía', 25, 58.20, 'lucia@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Carlos', 31, 82.40, 'carlos@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Marta', 28, 61.75, 'marta@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Javier', 22, 75.00, 'javier@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Elena', 35, 64.30, 'elena@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Pablo', 19, 68.90, 'pablo@correo.com', 2, crypt('1236', gen_salt('bf',12)));

INSERT INTO ejercicios (nombre)
VALUES ('ciclismo'),
       ('atletismo'),
       ('natacion'),
       ('pesas'),
       ('yoga');

INSERT INTO practicas (fecha, calorias, usuario_id, ejercicio_id)
VALUES (DEFAULT,      450, 2, 1),
       (DEFAULT,      450, 2, 1),
       ('2026-10-20', 380, 1, 1),
       ('2026-10-21', 520, 3, 1),
       ('2026-10-22', 300, 2, 2),
       ('2026-10-22', 410, 4, 3),
       ('2026-10-23', 600, 5, 1),
       ('2026-10-24', 250, 6, 5),
       ('2026-10-25', 350, 3, 4),
       ('2026-10-26', 480, 1, 2),
       ('2026-10-27', 700, 7, 1),
       ('2026-10-28', 330, 8, 3),
       ('2026-10-29', 540, 4, 1),
       ('2026-10-30', 290, 2, 4),
       ('2026-10-30', 460, 5, 2),
       ('2026-10-31', 620, 6, 1),
       ('2026-10-31', 390, 7, 4);

INSERT INTO ciclismo (distancia, tiempo, velocidad, practica_id)
VALUES (25, 60,   25, 1),
       (18, 3600, 18, 2),
       (30, 4500, 24, 3),
       (45, 5400, 30, 6),
       (60, 7200, 30, 10),
       (36, 5400, 24, 12),
       (50, 7200, 25, 15);