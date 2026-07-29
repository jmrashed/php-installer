# Changelog

All notable changes to this project will be documented in this file.

## [2.4.0] - 2026-07-29

Documentation and open-source-readiness. No code/behavior changes to the
installer itself except where noted. Closes Phase 4 of the audit checklist
(`audit/CHECKLIST.md`).

### Added

- `SECURITY.md` — private vulnerability-reporting process and scope.
- `CONTRIBUTING.md` — contributor workflow (`composer test`/`stan`/`cs`),
  PSR-12 as the coding standard, linked from the README.
- `CODE_OF_CONDUCT.md` — Contributor Covenant v2.1.
- `.github/ISSUE_TEMPLATE/bug_report.md`, `.github/ISSUE_TEMPLATE/feature_request.md`,
  `.github/PULL_REQUEST_TEMPLATE.md`.
- `.env.example` documenting `APP_DEBUG`, referenced from the README.

### Changed

- **`config/installer.php.dist` now only contains keys the installer
  actually reads.** It previously shipped `app_name`, `app_url`, and
  `installer_lock`, none of which any code path reads (app name/URL come
  from the session during the `app_config` step; the lock file's presence,
  not a config flag, is what `Installer::isInstalled()` checks). The
  template now lists exactly `database_file`, `migration_support`,
  `migration_path`, `seeder_path`, and `supported_databases` — the same set
  `SystemChecker`/`StepController` consume — each with a comment explaining
  what reads it. If you have an existing `config/installer.php` copied from
  an older `.dist`, it still works as-is; this only changes the template.
- **README's config examples (Basic Setup, Laravel, Custom PHP Project
  sections) rewritten to match `config/installer.php.dist` exactly** —
  previously they documented `version`, `php_version`, `required_extensions`,
  and `writable_dirs`, none of which the installer reads.
- **Removed the static `version` field from `composer.json`.** Composer/
  Packagist derive the installed version from git tags; hand-maintaining a
  duplicate `version` string is exactly what caused earlier releases'
  `composer.json`/CHANGELOG/README version numbers to disagree. Going
  forward, the version lives in git tags and this CHANGELOG only.

## [2.3.0] - 2026-07-29

Testing and automation infrastructure. No behavioral changes to the
installer itself — this release closes Phase 3 of the audit checklist
(`audit/CHECKLIST.md`): a real test suite, static analysis, style
enforcement, and CI wired together for the first time.

### Added

- **PHPUnit test suite.** `tests/InstallerTest.php` is now a proper
  `PHPUnit\Framework\TestCase` (`composer require --dev phpunit/phpunit`,
  `phpunit.xml`), replacing the hand-rolled runner that manually
  `require_once`d six files instead of using the Composer autoloader.
  `composer test` runs it.
- **Regression tests for the Phase 1 security fixes**: one confirms
  `Utils::e()` escapes both a stray quote and an HTML tag (C2, XSS);
  another confirms `Installer::isInstalled()` correctly reflects
  `createLockFile()`/`deleteLockFile()` (the re-install gap from
  `feature-gap-analysis.md`).
- **PHPStan static analysis** at level 2 (`phpstan.neon`, `composer stan`),
  clean with zero errors. `src/Views/**` is excluded — those templates
  receive their variables dynamically via `extract($data)` in
  `StepController::renderView()`, which PHPStan can't see, so it can't
  meaningfully type-check them.
- **PHP_CodeSniffer against PSR-12** (`phpcs.xml`, `composer cs` /
  `composer cs-fix`). `vendor/bin/phpcs src` reports zero errors (91
  auto-fixed via `phpcbf` — brace placement, trailing whitespace, spacing);
  45 line-length warnings remain, concentrated in view templates that mix
  inline HTML and PHP, and are treated as accepted style debt rather than a
  build blocker.
- **GitHub Actions CI** (`.github/workflows/ci.yml`): runs on PHP 8.1/8.2/8.3
  on every push/PR — `composer validate`, `composer install`, `composer test`,
  PHPStan, and PHPCS (errors only; warnings don't fail the build).

### Fixed

- Removed one genuinely dead `break;` after an `exit;` in
  `StepController::postStep()`'s `finish` case, found by PHPStan at a
  higher level than this release settles on.

## [2.2.0] - 2026-07-29

Correctness fixes across multi-database support, SQL import, and the debug
subsystem. Closes the remaining Phase 2 items from the code-quality/
architecture audit (see `audit/CHECKLIST.md`).

### Fixed

- **`AdminCreator` now works on SQLite, not just MySQL/PostgreSQL.** It
  previously hardcoded `NOW()` in the admin-account INSERT, which SQLite
  doesn't support. The timestamp is now computed in PHP and bound as a
  parameter, so it works identically across all three supported drivers.
- **`Debug::isEnabled()`'s `.env` lookup no longer assumes one fixed install
  layout.** It previously guessed the `.env` location by walking up exactly
  four directories, which only works when this package sits at
  `vendor/<vendor>/php-installer`. It now checks, in order: an explicit
  `INSTALLER_ENV_PATH` constant (for full consumer control), a `.env` next to
  `INSTALLER_BASE_PATH` (the "copy the folder into your project root" install
  method the README documents), then three levels above that (the Composer
  install layout), before falling back to the old fixed-depth guess.
- **SQL import no longer corrupts dumps containing semicolons inside string
  values or quoted identifiers.** `DatabaseManager::importSqlFile()` used
  `explode(';', $sql)`, which splits mid-statement on any `;` inside a quoted
  string (e.g. `INSERT INTO t (bio) VALUES ('Hello; World');`) or a
  backtick-quoted identifier. Statements are now split with a small
  state-machine parser that tracks quote context and only splits on
  statement-terminating semicolons.

### Changed

- **Consolidated on one migration mechanism.** `database/migrations/` used to
  ship both `.sql` files (which `DatabaseManager::runMigrations()` never
  actually executed — it only globs `*.php`) and document a PHP-callable
  format in the README. The `.sql` migration files have been removed and
  replaced with equivalent, driver-aware `.php` migrations
  (`001_create_users_table.php`, `002_create_admin_table.php`) that the
  runner actually executes. `database/migrations/README.md` now documents
  the `.php` format exclusively.
- **Finished aligning the PHP version requirement.** `src/Core/SystemChecker.php`
  and the README's example config previously still said `7.4` despite
  `composer.json` requiring `>=8.1` (and the README badge already having been
  corrected in 2.1.0). All references now agree on `8.1`.

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