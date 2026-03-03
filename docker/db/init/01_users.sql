CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user' CHECK (role IN ('user', 'admin')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_profiles (
    user_id INTEGER PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    currency VARCHAR(3) DEFAULT 'USD'
);

INSERT INTO users (id, email, password_hash, role) VALUES
        (1, 'admin@subtracker.pl', '$2y$10$wN2L02.aJ6lA15gBvI07IezP73V0H0b7P3F9xT3m8WJt.h/L4nJ3m', 'admin'),
        (2, 'user@example.com', '$2y$10$wN2L02.aJ6lA15gBvI07IezP73V0H0b7P3F9xT3m8WJt.h/L4nJ3m', 'user');

INSERT INTO user_profiles (user_id, first_name, last_name, currency) VALUES
        (1, 'System', 'Admin', 'PLN'),
        (2, 'Alex', 'Morgan', 'USD');

SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));