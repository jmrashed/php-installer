# Database Migrations

This directory contains database migration files that are executed in alphabetical order during the installation process.

## Migration File Naming Convention

Migration files should follow this naming pattern:
```
XXX_description_of_migration.sql
```

Where:
- `XXX` is a 3-digit number (001, 002, 003, etc.)
- `description_of_migration` describes what the migration does
- Files must have `.sql` extension

## Examples

- `001_create_users_table.sql`
- `002_create_admin_table.sql`
- `003_add_user_roles.sql`

## Migration Content

Each migration file should contain valid SQL statements for your target database. The installer supports:

- **MySQL**: Standard MySQL syntax
- **PostgreSQL**: PostgreSQL-specific syntax
- **SQLite**: SQLite-compatible syntax

## Usage

1. Place your migration files in this directory
2. Enable migration support in `config/installer.php`:
   ```php
   'migration_support' => true,
   'migration_path' => __DIR__ . '/../database/migrations'
   ```
3. During installation, select "Run database migrations" option

## Notes

- Migrations are executed in alphabetical order
- Each migration file is executed as a separate transaction
- If any migration fails, the installation process will stop
- Make sure your SQL syntax is compatible with your target database