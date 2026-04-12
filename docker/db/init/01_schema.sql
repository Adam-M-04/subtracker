DROP TABLE IF EXISTS subscriptions, user_profiles, users, roles, currencies, categories, billing_cycles, statuses CASCADE;

-- SŁOWNIKI
CREATE TABLE roles (id SERIAL PRIMARY KEY, name VARCHAR(20) NOT NULL);
INSERT INTO roles (id, name) VALUES (1, 'User'), (2, 'Admin');

CREATE TABLE currencies (id SERIAL PRIMARY KEY, code VARCHAR(3) NOT NULL, symbol VARCHAR(5) NOT NULL);
INSERT INTO currencies (id, code, symbol) VALUES (1, 'USD', '$'), (2, 'EUR', '€'), (3, 'PLN', 'zł');

CREATE TABLE categories (id SERIAL PRIMARY KEY, name VARCHAR(50) NOT NULL);
INSERT INTO categories (id, name) VALUES (1, 'Entertainment'), (2, 'Productivity'), (3, 'Utilities'), (4, 'Software'), (5, 'General');

CREATE TABLE billing_cycles (id SERIAL PRIMARY KEY, name VARCHAR(20) NOT NULL);
INSERT INTO billing_cycles (id, name) VALUES (1, 'Monthly'), (2, 'Yearly');

CREATE TABLE statuses (id SERIAL PRIMARY KEY, name VARCHAR(20) NOT NULL);
INSERT INTO statuses (id, name) VALUES
    (1, 'Active'),
    (2, 'Paused'),
    (3, 'Inactive');

-- TABELE GŁÓWNE
CREATE TABLE users (
   id SERIAL PRIMARY KEY,
   email VARCHAR(255) UNIQUE NOT NULL,
   password_hash VARCHAR(255) NOT NULL,
   role_id INTEGER REFERENCES roles(id) DEFAULT 1,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_profiles (
   user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
   first_name VARCHAR(100),
   last_name VARCHAR(100),
   currency_id INTEGER REFERENCES currencies(id) DEFAULT 1
);

CREATE TABLE subscriptions (
   id SERIAL PRIMARY KEY,
   user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
   name VARCHAR(255) NOT NULL,
   price NUMERIC(10, 2) NOT NULL,
   currency_id INTEGER REFERENCES currencies(id) DEFAULT 1,
   billing_cycle_id INTEGER REFERENCES billing_cycles(id) DEFAULT 1,
   category_id INTEGER REFERENCES categories(id) DEFAULT 5,
   status_id INTEGER REFERENCES statuses(id) DEFAULT 1,
   next_payment_date DATE NOT NULL,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
