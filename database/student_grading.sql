-- ============================================================================
-- Student Grading System (classroom version) - CRM 1963 Final Assessment
-- Run this whole file once: phpMyAdmin > SQL tab > paste > Go
-- (or any MySQL tool).  It creates the database, the two tables and one login.
-- To start again from nothing: DROP DATABASE student_grading;  then run it again.
-- ============================================================================

CREATE DATABASE IF NOT EXISTS student_grading
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_grading;

CREATE TABLE users (
    user_id       INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE results (
    result_id    INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    matric_no    VARCHAR(20)  NOT NULL,
    subject_name VARCHAR(100) NOT NULL,
    subject_code VARCHAR(20)  NOT NULL,
    test1        DECIMAL(5,2) NOT NULL,
    test2        DECIMAL(5,2) NOT NULL,
    final_exam   DECIMAL(5,2) NOT NULL,
    total_marks  DECIMAL(5,2) NOT NULL,
    grade        CHAR(1)      NOT NULL,
    UNIQUE (matric_no, subject_code)
);

-- The first user. The password is stored as a hash, never as plain text.
-- Username: admin    Password: see README.md
INSERT INTO users (username, password_hash)
VALUES ('admin', '$2y$12$Du4Gou.9dj0JMtLxQLM03efjs0P2zgDczThuHuyjbwIVzG/yHeX9a');
