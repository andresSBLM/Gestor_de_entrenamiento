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
    apellidos       VARCHAR(255)    NOT NULL,
    edad            NUMERIC(3)      NOT NULL,
    peso            NUMERIC(5,2)    NOT NULL,
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
    titulo          VARCHAR(255)    NOT NULL DEFAULT 'Actividad',
    fecha           DATE            NOT NULL DEFAULT CURRENT_DATE,
    calorias        INTEGER         NOT NULL,
    distancia       INTEGER         ,
    tiempo          INTEGER         ,
    velocidad       INTEGER         ,      
    usuario_id      BIGINT          NOT NULL REFERENCES usuarios(id) ON DELETE CASCADE,
    ejercicio_id    BIGINT          NOT NULL REFERENCES ejercicios(id)
);






/*Datos de prueba */
INSERT INTO categorias (categoria)
VALUES ('admin'),
       ('usuario'),
       ('usuario_premium');

INSERT INTO usuarios (nombre, apellidos, edad, peso, email, categoria_id, password)
VALUES ('Andrés', 'Jimenez',20, 70, 'andres@donana.com', 1, crypt('1236', gen_salt('bf',12))),
       ('Andrés', 'Jimenez',20, 70, 'andres@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Lucía','Jimenez', 25, 58.45, 'lucia@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Carlos','Jimenez', 31, 80.40, 'carlos@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Marta','Jimenez', 28, 61.75, 'marta@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Javier','Jimenez', 22, 75.00, 'javier@correo.com', 2, crypt('1236', gen_salt('bf',12))),
       ('Elena', 'Jimenez',35, 64.30, 'elena@correo.com', 3, crypt('1236', gen_salt('bf',12))),
       ('Pablo', 'Jimenez',19, 68.90, 'pablo@correo.com', 2, crypt('1236', gen_salt('bf',12)));

INSERT INTO ejercicios (nombre)
VALUES ('ciclismo'),
       ('atletismo'),
       ('natacion'),
       ('pesas');

INSERT INTO practicas (fecha, calorias, usuario_id, ejercicio_id)
VALUES ('2026-10-20', 380, 1, 1),
       ('2026-10-21', 520, 3, 1),
       ('2026-10-22', 300, 2, 2),
       ('2026-10-22', 410, 4, 3),
       ('2026-10-23', 600, 5, 1),
       ('2026-10-24', 250, 6, 4),
       ('2026-10-25', 350, 3, 4),
       ('2026-10-26', 480, 1, 2),
       ('2026-10-27', 700, 7, 1),
       ('2026-10-28', 330, 8, 3),
       ('2026-10-29', 540, 4, 1),
       ('2026-10-30', 290, 2, 4),
       ('2026-10-30', 460, 5, 2),
       ('2026-10-31', 620, 6, 1),
       ('2026-10-31', 390, 7, 4),
       ('2026-11-01', 510, 2, 2),
       ('2026-11-02', 430, 2, 2),
       ('2026-10-01', 566, 2, 1)       
       ;