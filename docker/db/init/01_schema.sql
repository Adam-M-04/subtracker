DROP VIEW IF EXISTS vw_user_subscription_summary, vw_subscription_details CASCADE;
DROP FUNCTION IF EXISTS get_user_active_subscription_count(INTEGER) CASCADE;
DROP FUNCTION IF EXISTS touch_updated_at() CASCADE;
DROP TABLE IF EXISTS subscription_tags, tags, subscriptions, user_profiles, users, roles, currencies, categories, billing_cycles, statuses CASCADE;

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

CREATE TABLE tags (
   id SERIAL PRIMARY KEY,
   name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO tags (name) VALUES ('Work'), ('Entertainment'), ('Family'), ('Finance');

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
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscription_tags (
   subscription_id INTEGER NOT NULL REFERENCES subscriptions(id) ON DELETE CASCADE,
   tag_id INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
   PRIMARY KEY (subscription_id, tag_id)
);

CREATE OR REPLACE FUNCTION touch_updated_at()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
   NEW.updated_at = CURRENT_TIMESTAMP;
   RETURN NEW;
END;
$$;

CREATE TRIGGER trg_subscriptions_updated_at
BEFORE UPDATE ON subscriptions
FOR EACH ROW
EXECUTE FUNCTION touch_updated_at();

CREATE OR REPLACE FUNCTION get_user_active_subscription_count(p_user_id INTEGER)
RETURNS INTEGER
LANGUAGE sql
STABLE
AS $$
    SELECT COUNT(*)::INTEGER
    FROM subscriptions s
    WHERE s.user_id = p_user_id
      AND s.status_id = 1;
$$;

CREATE VIEW vw_subscription_details AS
SELECT
   s.id,
   s.user_id,
   u.email,
   up.first_name,
   up.last_name,
   s.name AS subscription_name,
   s.price,
   c.code AS currency_code,
   c.symbol AS currency_symbol,
   bc.name AS billing_cycle,
   cat.name AS category,
   st.name AS status,
   s.next_payment_date,
   s.created_at,
   s.updated_at
FROM subscriptions s
JOIN users u ON u.id = s.user_id
LEFT JOIN user_profiles up ON up.user_id = u.id
JOIN currencies c ON c.id = s.currency_id
JOIN billing_cycles bc ON bc.id = s.billing_cycle_id
JOIN categories cat ON cat.id = s.category_id
JOIN statuses st ON st.id = s.status_id;

CREATE VIEW vw_user_subscription_summary AS
SELECT
   u.id AS user_id,
   u.email,
   COALESCE(up.first_name, '') AS first_name,
   COALESCE(up.last_name, '') AS last_name,
   COUNT(s.id) AS total_subscriptions,
   COUNT(s.id) FILTER (WHERE s.status_id = 1) AS active_subscriptions,
   COALESCE(SUM(s.price) FILTER (WHERE s.status_id = 1), 0) AS total_active_price,
   get_user_active_subscription_count(u.id) AS active_subscriptions_via_function
FROM users u
LEFT JOIN user_profiles up ON up.user_id = u.id
LEFT JOIN subscriptions s ON s.user_id = u.id
GROUP BY u.id, u.email, up.first_name, up.last_name;

