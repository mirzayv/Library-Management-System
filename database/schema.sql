-- Library Management System — MySQL schema
-- Tables and columns match the queries in the PHP pages.

CREATE DATABASE IF NOT EXISTS library CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library;

CREATE TABLE members (
  member_id  INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  email      VARCHAR(100) NOT NULL,
  password   VARCHAR(255) NULL,                 -- password_hash(); NULL for members added by an admin
  role       ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

CREATE TABLE books (
  book_id          INT AUTO_INCREMENT PRIMARY KEY,
  ISBN             VARCHAR(20)  NOT NULL UNIQUE,
  title            VARCHAR(255) NOT NULL,
  author           VARCHAR(255) NOT NULL,
  publication_year INT,
  publisher        VARCHAR(255),
  total_copies     INT NOT NULL DEFAULT 1,
  available_copies INT NOT NULL DEFAULT 1
);

CREATE TABLE borrowings (
  borrowing_id INT AUTO_INCREMENT PRIMARY KEY,
  book_id      INT  NOT NULL,
  member_id    INT  NOT NULL,
  borrow_date  DATE NOT NULL,
  due_date     DATE NOT NULL,
  return_date  DATE NULL,
  FOREIGN KEY (book_id)   REFERENCES books(book_id)     ON DELETE CASCADE,
  FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- First admin: sign up through the app, then promote the account:
-- UPDATE members SET role = 'admin' WHERE username = 'your_username';
