CREATE DATABASE smart_wallet;

CREATE SCHEMA IF NOT EXISTS hanan;

SET search_path TO hanan;

SHOW search_path;

SELECT current_database();

DROP TABLE IF EXISTS expenses CASCADE;
DROP TABLE IF EXISTS incomes CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id_cat SERIAL PRIMARY KEY,
    name_cat VARCHAR(50) NOT NULL,
    type_cat VARCHAR(10) NOT NULL CHECK (type_cat IN ('income', 'expense')),
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_category_user FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE SET NULL
);

CREATE TABLE incomes (
    id_in SERIAL PRIMARY KEY,
    amount_in DECIMAL(10,2) NOT NULL,
    date_in DATE NOT NULL,
    description_in VARCHAR(250) DEFAULT 'Unknown',
    user_id INT NOT NULL,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_income_user FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    CONSTRAINT fk_income_category FOREIGN KEY (category_id) REFERENCES categories(id_cat) ON DELETE SET NULL
);

CREATE TABLE expenses (
    id_ex SERIAL PRIMARY KEY,
    amount_ex DECIMAL(10,2) NOT NULL,
    date_ex DATE NOT NULL,
    description_ex VARCHAR(250) DEFAULT 'Unknown',
    user_id INT NOT NULL,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_expense_user FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    CONSTRAINT fk_expense_category FOREIGN KEY (category_id) REFERENCES categories(id_cat) ON DELETE SET NULL
);

INSERT INTO categories (name_cat, type_cat) VALUES
('Salary', 'income'),
('Freelance', 'income'),
('Investment', 'income'),
('Gift', 'income'),
('Other Income', 'income'),
('Food', 'expense'),
('Transport', 'expense'),
('Housing', 'expense'),
('Entertainment', 'expense'),
('Healthcare', 'expense'),
('Education', 'expense'),
('Shopping', 'expense'),
('Other Expense', 'expense');