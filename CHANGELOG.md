# Changelog

All notable changes to this project will be documented in this file.

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