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
        (1, 'admin@subtracker.pl', '$2y$12$O09tQc.CRVyKcIotGrXeUO5fCVH2pCX9Ka/cL4gooKmvsGa5m7g8.', 'admin'),
        (2, 'user@subtracker.pl', '$2y$12$SiaZwwq5Azke6QW9YoowouuLjIA8Y8B5L6w3rY8Ufi.f0gdpwD/H6', 'user');

INSERT INTO user_profiles (user_id, first_name, last_name, currency) VALUES
        (1, 'System', 'Admin', 'PLN'),
        (2, 'Alex', 'Morgan', 'USD');

SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));