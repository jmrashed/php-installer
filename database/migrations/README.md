# Database Migrations

This directory contains PHP migration files, executed in alphabetical order
by `DatabaseManager::runMigrations()` during installation. This is the only
migration mechanism the installer supports — SQL-file migrations are not
executed by the runner and should not be placed here.

## Migration File Naming Convention

Migration files should follow this naming pattern:
```
XXX_description_of_migration.php
```

Where:
- `XXX` is a 3-digit number (001, 002, 003, etc.)
- `description_of_migration` describes what the migration does
- Files must have `.php` extension

## Examples

- `001_create_users_table.php`
- `002_create_admin_table.php`
- `003_add_user_roles.php`

## Migration Content

Each migration file must `return` a callable that receives the `\PDO`
connection and runs whatever DDL/DML it needs:

```php
<?php
// 003_add_user_roles.php
return function (\PDO $pdo) {
    $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user'");
};
```

Because the callable receives the live `PDO` instance, it can branch on
`$pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)` (`mysql`, `pgsql`, or `sqlite`)
to run driver-specific SQL — see `001_create_users_table.php` for an example.

## Usage

1. Place your migration files in this directory
2. Enable migration support in `config/installer.php`:
   ```php
   'migration_support' => true,
   'migration_path' => __DIR__ . '/../database/migrations'
   ```
3. During installation, select "Run database migrations & seeders"

## Notes

- Migrations are executed in alphabetical order
- If any migration throws, the installation process stops and reports the error
- Write driver-aware SQL (see above) rather than assuming one database engine
