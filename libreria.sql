CREATE DATABASE IF NOT EXISTS libreria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE libreria;

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL,
    descripcion TEXT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categorias_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS autores (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(160) NOT NULL,
    biografia TEXT NULL,
    foto VARCHAR(255) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    KEY idx_autores_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS libros (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL,
    sinopsis TEXT NULL,
    precio DECIMAL(10, 2) UNSIGNED NOT NULL DEFAULT 0.00,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    isbn VARCHAR(20) NULL,
    portada VARCHAR(255) NULL,
    categoria_id INT UNSIGNED NOT NULL,
    autor_id INT UNSIGNED NOT NULL,
    destacado TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_libros_slug (slug),
    UNIQUE KEY uq_libros_isbn (isbn),
    KEY idx_libros_categoria (categoria_id),
    KEY idx_libros_autor (autor_id),
    KEY idx_libros_destacado_fecha (destacado, created_at),
    CONSTRAINT fk_libros_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias (id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_libros_autor
        FOREIGN KEY (autor_id) REFERENCES autores (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(160) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    estado ENUM('pendiente', 'pagado', 'enviado', 'completado', 'cancelado')
        NOT NULL DEFAULT 'pendiente',
    total DECIMAL(10, 2) UNSIGNED NOT NULL DEFAULT 0.00,
    direccion_envio VARCHAR(500) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    KEY idx_pedidos_usuario_fecha (usuario_id, created_at),
    KEY idx_pedidos_estado (estado),
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS detalle_pedidos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    pedido_id INT UNSIGNED NOT NULL,
    libro_id INT UNSIGNED NOT NULL,
    titulo_libro VARCHAR(200) NOT NULL,
    precio_unitario DECIMAL(10, 2) UNSIGNED NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10, 2) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_detalle_pedido_libro (pedido_id, libro_id),
    KEY idx_detalle_libro (libro_id),
    CONSTRAINT fk_detalle_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_libro
        FOREIGN KEY (libro_id) REFERENCES libros (id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO categorias (nombre, slug, descripcion) VALUES
    ('Narrativa', 'narrativa', 'Novelas, cuentos y nuevas voces.'),
    ('Ciencia y conocimiento', 'ciencia-conocimiento', 'Ideas para entender mejor el mundo.'),
    ('Infantil', 'infantil', 'Lecturas para descubrir en familia.'),
    ('Desarrollo personal', 'desarrollo-personal', 'Herramientas para crecer y aprender.');

INSERT INTO autores (nombre, biografia)
SELECT 'Lucía del Valle', 'Autora de historias sobre viajes, memoria y nuevos comienzos.'
WHERE NOT EXISTS (SELECT 1 FROM autores WHERE nombre = 'Lucía del Valle');

INSERT INTO autores (nombre, biografia)
SELECT 'Mateo Ríos', 'Escritor dedicado a acercar la ciencia a todos los lectores.'
WHERE NOT EXISTS (SELECT 1 FROM autores WHERE nombre = 'Mateo Ríos');

INSERT INTO autores (nombre, biografia)
SELECT 'Elena Robles', 'Autora de relatos ilustrados para lectores jóvenes.'
WHERE NOT EXISTS (SELECT 1 FROM autores WHERE nombre = 'Elena Robles');

INSERT INTO autores (nombre, biografia)
SELECT 'Tomás del Bosque', 'Ensayista y divulgador sobre hábitos y creatividad.'
WHERE NOT EXISTS (SELECT 1 FROM autores WHERE nombre = 'Tomás del Bosque');

INSERT IGNORE INTO libros (
    titulo, slug, sinopsis, precio, stock, isbn, categoria_id, autor_id, destacado, created_at, updated_at
)
SELECT 'El mapa de los días tranquilos', 'el-mapa-de-los-dias-tranquilos',
       'Una viajera vuelve a su pueblo y encuentra cartas que cambian la historia de su familia.',
       18.90, 12, NULL, c.id, a.id, 1, NOW(), NOW()
FROM categorias c JOIN autores a ON a.nombre = 'Lucía del Valle'
WHERE c.slug = 'narrativa';

INSERT IGNORE INTO libros (
    titulo, slug, sinopsis, precio, stock, isbn, categoria_id, autor_id, destacado, created_at, updated_at
)
SELECT 'La casa junto al río', 'la-casa-junto-al-rio',
       'Tres generaciones se reúnen para decidir qué hacer con la casa donde crecieron.',
       16.50, 8, NULL, c.id, a.id, 0, NOW(), NOW()
FROM categorias c JOIN autores a ON a.nombre = 'Lucía del Valle'
WHERE c.slug = 'narrativa';

INSERT IGNORE INTO libros (
    titulo, slug, sinopsis, precio, stock, isbn, categoria_id, autor_id, destacado, created_at, updated_at
)
SELECT 'Pequeñas preguntas sobre el universo', 'pequenas-preguntas-universo',
       'Una introducción amena a los planetas, las estrellas y las preguntas que aún investigamos.',
       21.00, 10, NULL, c.id, a.id, 1, NOW(), NOW()
FROM categorias c JOIN autores a ON a.nombre = 'Mateo Ríos'
WHERE c.slug = 'ciencia-conocimiento';

INSERT IGNORE INTO libros (
    titulo, slug, sinopsis, precio, stock, isbn, categoria_id, autor_id, destacado, created_at, updated_at
)
SELECT 'El bosque que aprendió a cantar', 'el-bosque-que-aprendio-a-cantar',
       'Una historia ilustrada sobre la amistad, la curiosidad y el cuidado de la naturaleza.',
       13.75, 15, NULL, c.id, a.id, 0, NOW(), NOW()
FROM categorias c JOIN autores a ON a.nombre = 'Elena Robles'
WHERE c.slug = 'infantil';

INSERT IGNORE INTO libros (
    titulo, slug, sinopsis, precio, stock, isbn, categoria_id, autor_id, destacado, created_at, updated_at
)
SELECT 'Un paso a la vez', 'un-paso-a-la-vez',
       'Ideas sencillas para construir hábitos sostenibles y hacer espacio para lo importante.',
       17.25, 9, NULL, c.id, a.id, 1, NOW(), NOW()
FROM categorias c JOIN autores a ON a.nombre = 'Tomás del Bosque'
WHERE c.slug = 'desarrollo-personal';
