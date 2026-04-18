-- Database: jserrano_db

-- DROP DATABASE IF EXISTS jserrano_db;

CREATE DATABASE jserrano_db
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Spain.1252'
    LC_CTYPE = 'Spanish_Spain.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password TEXT,
    role VARCHAR(20) DEFAULT 'user'
);

CREATE USER jserrano WITH ENCRYPTED PASSWORD 'P@ssw0rd';
GRANT CONNECT ON DATABASE jserrano_db TO jserrano;
GRANT ALL PRIVILEGES ON DATABASE jserrano_db TO jserrano;