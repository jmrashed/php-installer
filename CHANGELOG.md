# Changelog

All notable changes to this project will be documented in this file.

## [2.1.0] - 2026-07-29

Security and correctness hardening release. No new user-facing features; every
change below closes a finding from a full security/code-quality/architecture
audit of the installer.

### Security

- **Critical: PHP code injection in the generated application config file (RCE).**
  `app_config` previously built the generated `includes/config.php` by
  concatenating raw session values (including the database password) directly
  into single-quoted PHP string literals. A value containing `');...` could
  break out of the string and inject arbitrary executable PHP. Config values
  are now emitted with `var_export()`, which is safe for any input.
- **Critical: unescaped view output (XSS), including the database password field.**
  Every value echoed into a view — form field values, alert messages, system
  check results — is now passed through a new `Utils::e()` output-escaping
  helper (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) at the point it is
  rendered, instead of relying solely on input-time sanitization.
- **Critical: plaintext database credentials disclosed via unauthenticated `?debug=1`.**
  Debug output no longer has a fallback that can be enabled by an unauthenticated
  request parameter. Debug output is now controlled exclusively by `APP_DEBUG`
  in `.env` and defaults closed when no `.env` is found. Full `$_SESSION`/`$_POST`
  dumps (which included the DB password and admin password) have been removed
  from all debug logging call sites.
- **Critical: hardcoded, committed database credentials shipped in `database/db.sql`.**
  The seeded `tbl_admin` rows (including a real-looking plaintext admin
  password) have been removed from the shipped schema. If you deployed this
  schema verbatim, rotate any credentials that matched the removed rows.
- **`Utils::sanitizeInput()` no longer HTML-escapes.** It now only normalizes
  input (trim + `stripslashes`); HTML escaping happens once, at output, via
  `Utils::e()`. This also fixes a real data-corruption bug where values were
  being HTML-entity-encoded before being written into the generated config
  file (e.g. an app name containing `'` would have been persisted as `&#039;`).

### Fixed

- Three sites (`Installer::handle()`, `DatabaseManager::runMigrations()`,
  `DatabaseManager::runSeeders()`, `StepController::postStep()`) caught bare
  `Exception`/`Error` inside a namespace, which PHP resolves to
  non-existent classes (`Installer\Core\Exception`), producing an uncaught
  fatal error and a blank page instead of the intended error handling. These
  now catch `\Throwable`.
- The installer no longer allows itself to be re-run indefinitely against an
  already-installed target: `Installer::handle()` now checks
  `isInstalled()` and refuses to serve any step once `storage/installer.lock`
  exists.
- Removed unconditional debug `echo` statements in `StepController` that
  printed directly into the HTML response on every request regardless of any
  debug flag.

### Changed

- `storage/installer.lock` is no longer committed to the repository (it's a
  runtime artifact) and is now gitignored.
- `config/installer.php` is no longer committed with live-looking default
  values. The shipped template is now `config/installer.php.dist` — copy it
  to `config/installer.php` as part of setup. `config/installer.php` is
  gitignored so per-deployment secrets never get committed accidentally.
  Code paths that read this config now handle a missing file gracefully.

### Upgrading from 2.0.x

1. Copy `config/installer.php.dist` to `config/installer.php` and adjust values
   for your deployment (this file is now gitignored).
2. If you rely on `?debug=1` for troubleshooting, switch to setting
   `APP_DEBUG=true` in your `.env` file instead — the query-parameter trigger
   has been removed.
3. If any code calls `Utils::sanitizeInput()` and depended on it returning
   HTML-escaped output, escape at the point of output instead (`Utils::e()`).
4. If `database/db.sql` was deployed as-is in a prior install, rotate the
   admin credentials it used to seed (`admin`/`bR@mWB` and `shaheen`/`shaheen123`)
   — they no longer ship with this package and should not remain live anywhere.

## [2.0.0] - 2025-01-14

### Added
- **PHP Migration Support**: Execute PHP-based migration files instead of just SQL
- **Seeder Integration**: Automatic seeder execution after migrations
- **Environment-based Debug Control**: Debug output controlled by `.env` APP_DEBUG setting
- **Enhanced Database Support**: Improved MySQL, PostgreSQL, and SQLite support
- **Smart URL Detection**: Automatic base URL detection for application configuration
- **Migration Detection**: Automatic detection of PHP migration files
- **Comprehensive Logging**: Detailed logging for migration and seeder execution

### Changed
- **Database Import Options**: Now offers three distinct import methods
- **Configuration Structure**: Enhanced installer configuration with migration paths
- **Debug System**: Centralized debug output through `Debug::log()` method
- **User Interface**: Improved labels and descriptions for installation options

### Fixed
- **Path Resolution**: Correct application path calculation for config file creation
- **URL Generation**: Proper HTTP URL generation instead of file paths
- **Asset Loading**: Fixed CSS and JS loading with CDN fallbacks
- **Route Handling**: Proper routing for clean URLs in installer

### Technical Improvements
- Added `Debug` class for centralized debug control
- Enhanced `DatabaseManager` with PHP migration support
- Improved error handling and user feedback
- Better separation of concerns in installer components

## [1.0.0] - 2024-01-01

### Added
- Initial release with basic installation wizard
- System requirements checking
- Database configuration and import
- Application configuration
- Admin account creation
- Installation lock mechanism