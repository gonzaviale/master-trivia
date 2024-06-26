CREATE SCHEMA Preguntados;

USE Preguntados;

-- Crear la tabla
CREATE TABLE Rol (
    id INT PRIMARY KEY,
    rol VARCHAR(30)
);

-- Crear la tabla Dificultad
CREATE TABLE Dificultad (
    id INT PRIMARY KEY,
    dificultad VARCHAR(15)
);

-- Crear la tabla Usuario
CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50),
    nombre_completo VARCHAR(100),
    ano_nacimiento INT,
    sexo VARCHAR(10),
    pais VARCHAR(50),
    ciudad VARCHAR(50),
    nombre_usuario VARCHAR(20),
    foto_perfil VARCHAR(100),
    token_validacion VARCHAR(100),
    cuenta_validada BOOLEAN
);

-- Crear la tabla Jugador
CREATE TABLE Jugador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    respuestas_correctas INT NOT NULL ,
    respuestas_incorrectas INT NOT NULL,
    total_respuestas INT NOT NULL,
    nivel_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id),
    FOREIGN KEY (nivel_id) REFERENCES Dificultad(id)
);

-- Crear la tabla Login
CREATE TABLE Login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20),
    password VARCHAR(32),
    id_rol INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_creacion DATE NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES Rol(id),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id)
);

-- Crear la tabla Categoria
CREATE TABLE Categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(255) NOT NULL
);

-- Crear la tabla Pregunta
CREATE TABLE Pregunta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuestas_correctas INT NOT NULL ,
    respuestas_incorrectas INT NOT NULL,
    total_respuestas INT NOT NULL,
    categoria_id INT NOT NULL,
    dificultad_id INT NOT NULL,
    fecha_creacion DATE,
    FOREIGN KEY (dificultad_id) REFERENCES Dificultad(id),
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id)
);

-- Crear la tabla Respuesta
CREATE TABLE Respuesta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    respuesta TEXT NOT NULL,
    pregunta_id INT,
    correcta BOOLEAN,
    FOREIGN KEY (pregunta_id) REFERENCES Pregunta(id)
);

-- Crear la tabla Reportada
CREATE TABLE Reportada(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pregunta INT NOT NULL,
    id_jugador INT NOT NULL,
    FOREIGN KEY (id_pregunta) REFERENCES pregunta(id),
    FOREIGN KEY (id_jugador) REFERENCES jugador(id)
);

-- Crear la tabla Pregunta Respondida
CREATE TABLE Pregunta_Respondida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pregunta_id INT NOT NULL,
    jugador_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES pregunta(id),
    FOREIGN KEY (jugador_id) REFERENCES jugador(id)
);

-- Crear la tabla partida
CREATE TABLE Partida(
    id INT AUTO_INCREMENT PRIMARY KEY,
    jugador_id INT NOT NULL,
    puntaje INT NOT NULL,
    id_ultima_pregunta INT NOT NULL,
    fecha_hora DATETIME,
    FOREIGN KEY (jugador_id) REFERENCES jugador(id)
);

-- Crear la tabla pregunta sugerida
CREATE TABLE Pregunta_sugerida (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT,
    pregunta TEXT NOT NULL,
    respuesta_correcta VARCHAR(100) NOT NULL,
    incorrecta_1 VARCHAR(100) NOT NULL,
    incorrecta_2 VARCHAR(100) NOT NULL,
    incorrecta_3 VARCHAR(100) NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id) 
);

-- Insertar Rol
INSERT INTO Rol (id, rol) VALUES
    (1, 'Administrador'),
    (2, 'Editor'),
    (3, 'Jugador');

-- Insertar Dificultad
INSERT INTO Dificultad (id, dificultad) VALUES
    (1, 'Facil'),
    (2, 'Intermedio'),
    (3, 'Dificil');

-- Insertar categorias
INSERT INTO Categoria(categoria) VALUES
    ('Geografia'),
    ('Historia'),
    ('Ciencia'),
    ('Deportes');

-- Insertar preguntas
INSERT INTO Pregunta (pregunta, categoria_id, respuestas_correctas, respuestas_incorrectas, total_respuestas, dificultad_id) VALUES
    ('¿Cuál es la capital de Francia?', 1, 9, 1, 10, 1),
    ('¿Quién descubrió América?', 2, 9, 1, 10, 1),
    ('¿Cuál es el símbolo químico del agua?', 3, 9, 1, 10, 1),
    ('¿Quién ha ganado más Copas del Mundo de fútbol?', 4, 9, 1, 10, 1),
    ('¿Cuál es el país más grande del mundo?', 1, 9, 1, 10, 1),
    ('¿En qué año comenzó la Primera Guerra Mundial?', 2, 1, 9, 10, 3),
    ('¿Qué planeta es conocido como el Planeta Rojo?', 3, 5, 5, 10, 2),
    ('¿Qué país ha ganado más medallas en los Juegos Olímpicos?', 4, 5, 5, 10, 2),
    ('¿Cuál es el río más largo del mundo?', 1, 5, 5, 10, 2),
    ('¿Quién fue el primer presidente de los Estados Unidos?', 2, 5, 5, 10, 2),
    ('¿Cuál es el elemento más abundante en la Tierra?', 3, 1, 9, 10, 3),
    ('¿Quién es considerado el mejor jugador de baloncesto de todos los tiempos?', 4, 5, 5, 10, 2),
    ('¿Cuál es el desierto más grande del mundo?', 1, 5, 5, 10, 2),
    ('¿En qué año se derrumbó el Muro de Berlín?', 2, 1, 9, 10, 3),
    ('¿Qué tipo de célula carece de núcleo?', 3, 1, 9, 10, 3),
    ('¿Cuál es el récord mundial de los 100 metros planos?', 4, 5, 5, 10, 2),
    ('¿Cuál es el país más poblado del mundo?', 1, 5, 5, 10, 2),
    ('¿Quién fue la primera mujer en ir al espacio?', 2, 5, 5, 10, 2),
    ('¿Cuál es la fórmula química del dióxido de carbono?', 3, 5, 5, 10, 2),
    ('¿Qué equipo de fútbol ha ganado más Champions League?', 4, 5, 5, 10, 2),
    ('¿Cuál es la montaña más alta del mundo?', 1, 5, 5, 10, 2),
    ('¿Quién fue el primer emperador de Roma?', 2, 5, 5, 10, 2),
    ('¿Cuál es el órgano más grande del cuerpo humano?', 3, 5, 5, 10, 2),
    ('¿Qué país ganó la Copa del Mundo de Rugby en 2019?', 4, 5, 5, 10, 2),
    ('¿Cuál es el océano más grande del mundo?', 1, 5, 5, 10, 2),
    ('¿En qué año terminó la Segunda Guerra Mundial?', 2, 5, 5, 10, 2),
    ('¿Qué partícula subatómica tiene carga negativa?', 3, 5, 5, 10, 2),
    ('¿Qué tenista ha ganado más títulos de Grand Slam?', 4, 5, 5, 10, 2);

-- Insertar respuestas
INSERT INTO Respuesta (respuesta, pregunta_id, correcta) VALUES
    ('París', 1, TRUE),
    ('Londres', 1, FALSE),
    ('Madrid', 1, FALSE),
    ('Berlín', 1, FALSE),

    ('Cristóbal Colón', 2, TRUE),
    ('Américo Vespucio', 2, FALSE),
    ('Fernando de Magallanes', 2, FALSE),
    ('Leif Erikson', 2, FALSE),

    ('H2O', 3, TRUE),
    ('O2', 3, FALSE),
    ('CO2', 3, FALSE),
    ('H2', 3, FALSE),

    ('Brasil', 4, TRUE),
    ('Alemania', 4, FALSE),
    ('Italia', 4, FALSE),
    ('Argentina', 4, FALSE),

    ('Rusia', 5, TRUE),
    ('Canadá', 5, FALSE),
    ('China', 5, FALSE),
    ('Estados Unidos', 5, FALSE),

    ('1914', 6, TRUE),
    ('1912', 6, FALSE),
    ('1916', 6, FALSE),
    ('1918', 6, FALSE),

    ('Marte', 7, TRUE),
    ('Júpiter', 7, FALSE),
    ('Saturno', 7, FALSE),
    ('Venus', 7, FALSE),

    ('Estados Unidos', 8, TRUE),
    ('China', 8, FALSE),
    ('Rusia', 8, FALSE),
    ('Alemania', 8, FALSE),

    ('Nilo', 9, TRUE),
    ('Amazonas', 9, FALSE),
    ('Yangtsé', 9, FALSE),
    ('Misisipi', 9, FALSE),

    ('George Washington', 10, TRUE),
    ('Abraham Lincoln', 10, FALSE),
    ('Thomas Jefferson', 10, FALSE),
    ('John Adams', 10, FALSE),

    ('Oxígeno', 11, TRUE),
    ('Hidrógeno', 11, FALSE),
    ('Carbono', 11, FALSE),
    ('Nitrógeno', 11, FALSE),

    ('Michael Jordan', 12, TRUE),
    ('LeBron James', 12, FALSE),
    ('Kobe Bryant', 12, FALSE),
    ('Shaquille O Neal', 12, FALSE),

    ('Sahara', 13, TRUE),
    ('Gobi', 13, FALSE),
    ('Kalahari', 13, FALSE),
    ('Atacama', 13, FALSE),

    ('1989', 14, TRUE),
    ('1990', 14, FALSE),
    ('1991', 14, FALSE),
    ('1988', 14, FALSE),

    ('Procariota', 15, TRUE),
    ('Eucariota', 15, FALSE),
    ('Animal', 15, FALSE),
    ('Vegetal', 15, FALSE),

    ('Usain Bolt', 16, TRUE),
    ('Tyson Gay', 16, FALSE),
    ('Yohan Blake', 16, FALSE),
    ('Justin Gatlin', 16, FALSE),

    ('China', 17, TRUE),
    ('India', 17, FALSE),
    ('Estados Unidos', 17, FALSE),
    ('Indonesia', 17, FALSE),

    ('Valentina Tereshkova', 18, TRUE),
    ('Sally Ride', 18, FALSE),
    ('Mae Jemison', 18, FALSE),
    ('Eileen Collins', 18, FALSE),

    ('CO2', 19, TRUE),
    ('H2O', 19, FALSE),
    ('O2', 19, FALSE),
    ('CO', 19, FALSE),

    ('Real Madrid', 20, TRUE),
    ('Barcelona', 20, FALSE),
    ('Manchester United', 20, FALSE),
    ('Bayern Munich', 20, FALSE),

    ('Everest', 21, TRUE),
    ('K2', 21, FALSE),
    ('Kangchenjunga', 21, FALSE),
    ('Lhotse', 21, FALSE),

    ('Augusto', 22, TRUE),
    ('Julio César', 22, FALSE),
    ('Nerón', 22, FALSE),
    ('Calígula', 22, FALSE),

    ('Piel', 23, TRUE),
    ('Hígado', 23, FALSE),
    ('Cerebro', 23, FALSE),
    ('Pulmones', 23, FALSE),

    ('Sudáfrica', 24, TRUE),
    ('Nueva Zelanda', 24, FALSE),
    ('Australia', 24, FALSE),
    ('Inglaterra', 24, FALSE),

    ('Pacífico', 25, TRUE),
    ('Atlántico', 25, FALSE),
    ('Índico', 25, FALSE),
    ('Ártico', 25, FALSE),

    ('1945', 26, TRUE),
    ('1944', 26, FALSE),
    ('1946', 26, FALSE),
    ('1947', 26, FALSE),

    ('Electrón', 27, TRUE),
    ('Protón', 27, FALSE),
    ('Neutrón', 27, FALSE),
    ('Quark', 27, FALSE),

    ('Rafael Nadal', 28, TRUE),
    ('Roger Federer', 28, FALSE),
    ('Novak Djokovic', 28, FALSE),
    ('Pete Sampras', 28, FALSE);

-- insertar admin y editor
    INSERT INTO Usuario (email, nombre_completo, ano_nacimiento, sexo, pais, nombre_usuario, foto_perfil, ciudad, cuenta_validada) VALUES
    ("editor@mastertrivia.com", "Don Pepito", "2001", "Masculino", "Argentina", "editor", "nulo", "Merlo", 1);

    INSERT INTO Usuario (email, nombre_completo, ano_nacimiento, sexo, pais, nombre_usuario, foto_perfil, ciudad, cuenta_validada) VALUES
    ("admin@mastertrivia.com", "Don Jose", "2001", "Masculino", "Argentina", "admin", "nulo", "Merlo", 1);

    INSERT INTO Login (username, password, id_usuario, id_rol,fecha_creacion) VALUES
    ("editor", "b59c67bf196a4758191e42f76670ceba", 1, 2, NOW());

    INSERT INTO Login (username, password, id_usuario, id_rol,fecha_creacion) VALUES
    ("admin", "b59c67bf196a4758191e42f76670ceba", 2, 1, NOW());

-- Insertar Jugador facil e intermedio
    INSERT INTO Usuario (email, nombre_completo, ano_nacimiento, sexo, pais, nombre_usuario, foto_perfil, ciudad, cuenta_validada) VALUES
    ("jugadorFacil@mastertrivia.com", "Spiderman", "2001", "Femenino", "Perú", "spiderman", "public/img/spiderman.png", "Lima", 1);

    INSERT INTO Usuario (email, nombre_completo, ano_nacimiento, sexo, pais, nombre_usuario, foto_perfil, ciudad, cuenta_validada) VALUES
    ("jugadorDificil@mastertrivia.com", "Deadpool", "1930", "Masculino", "Argentina", "deadpool", "public/img/deadpool.png", "Merlo", 1);

    INSERT INTO Login (username, password, id_rol, id_usuario, fecha_creacion) VALUES
    ("spiderman", "b59c67bf196a4758191e42f76670ceba", 3, 3, NOW());

    INSERT INTO Login (username, password, id_rol, id_usuario, fecha_creacion) VALUES
    ("deadpool", "b59c67bf196a4758191e42f76670ceba", 3, 4, NOW());

    INSERT INTO Jugador(respuestas_correctas, respuestas_incorrectas, total_respuestas, nivel_id, usuario_id) VALUES
    (1, 20, 21, 1, 3);

    INSERT INTO Jugador(respuestas_correctas, respuestas_incorrectas, total_respuestas, nivel_id, usuario_id) VALUES
    (20, 1, 21, 3, 4);