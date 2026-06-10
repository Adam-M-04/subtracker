DO $$
    DECLARE
        v_admin_id INT;
        v_user_id INT;
    BEGIN
        -- Hasło dla obu kont to: password
        -- Używamy standardowego hasha BCRYPT wygenerowanego dla słowa "Test123#"

        -- 1. Tworzenie konta Administratora
        INSERT INTO users (email, password_hash, role_id)
        VALUES ('admin@subtracker.test', '$2y$12$O09tQc.CRVyKcIotGrXeUO5fCVH2pCX9Ka/cL4gooKmvsGa5m7g8.', 2)
        RETURNING id INTO v_admin_id;

        INSERT INTO user_profiles (user_id, first_name, last_name, currency_id)
        VALUES (v_admin_id, 'System', 'Admin', 1);

        -- 2. Tworzenie konta zwykłego Użytkownika
        INSERT INTO users (email, password_hash, role_id)
        VALUES ('user@subtracker.test', '$2y$12$SiaZwwq5Azke6QW9YoowouuLjIA8Y8B5L6w3rY8Ufi.f0gdpwD/H6', 1)
        RETURNING id INTO v_user_id;

        INSERT INTO user_profiles (user_id, first_name, last_name, currency_id)
        VALUES (v_user_id, 'Jan', 'Kowalski', 3);

        -- 3. Dodawanie testowych subskrypcji dla Administratora
        -- Kategorie: 1:Entertainment, 2:Productivity, 3:Utilities, 4:Software, 5:General
        -- Waluty: 1:USD, 2:EUR, 3:PLN
        -- Cykl: 1:Monthly, 2:Yearly
        -- Status: 1:Active, 2:Paused, 3:Inactive

        INSERT INTO subscriptions (user_id, name, price, currency_id, billing_cycle_id, category_id, status_id, next_payment_date) VALUES
                -- Aktywne (1)
               (v_admin_id, 'Netflix', 15.99, 1, 1, 1, 1, CURRENT_DATE + INTERVAL '5 days'),
               (v_admin_id, 'Spotify', 4.99, 2, 1, 1, 1, CURRENT_DATE + INTERVAL '12 days'),
               (v_admin_id, 'Adobe', 54.99, 1, 1, 4, 1, CURRENT_DATE + INTERVAL '2 days'),
               (v_admin_id, 'ChatGPT', 20.00, 1, 1, 2, 1, CURRENT_DATE + INTERVAL '15 days'),
               (v_admin_id, 'PlayStation', 295.00, 3, 2, 1, 1, CURRENT_DATE + INTERVAL '45 days'),
               (v_admin_id, 'Notion', 8.00, 1, 1, 2, 1, CURRENT_DATE + INTERVAL '8 days'),

               -- Wstrzymane (2)
               (v_admin_id, 'Disney', 10.99, 1, 1, 1, 2, CURRENT_DATE + INTERVAL '20 days'),
               (v_admin_id, 'Xbox', 40.00, 3, 1, 1, 2, CURRENT_DATE + INTERVAL '10 days'),

               -- Nieaktywne / Historia (3)
               (v_admin_id, 'Canva', 12.99, 1, 1, 4, 3, CURRENT_DATE - INTERVAL '100 days'),
               (v_admin_id, 'Gym Pass', 120.00, 3, 1, 5, 3, CURRENT_DATE - INTERVAL '30 days');

        INSERT INTO subscription_tags (subscription_id, tag_id)
        SELECT s.id, t.id
        FROM subscriptions s
        JOIN tags t ON t.name IN ('Work', 'Entertainment')
        WHERE s.user_id = v_admin_id
          AND s.name IN ('Adobe', 'Netflix');

    END $$;