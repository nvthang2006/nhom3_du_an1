DA1 - Tour Management (Rebuilt Base)
------------------------------------
- PHP OOP, no Composer.
- Minimal MVC-like structure, role-based access (admin, hdv, customer).
- PDO for DB access, prepared statements.
- Use `public/index.php` as webroot entry.

Quick start:
1. Import sql/schema.sql into MySQL.
2. Configure DB credentials in app/Core/Database.php.
3. Put `public` as your web root (or run `php -S localhost:8000 -t public`).
4. Create admin user via seed or SQL (see sql/seed.sql).
