# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2024-12-19

### Added
- File upload support for SQL and ZIP files in database import step
- Comprehensive debug logging system with step-by-step tracking
- Beautiful step indicators with circular progress and animations
- Enhanced CSS styling with gradients and modern design
- ZIP file extraction capability for SQL imports
- Database cleanup functionality (drops existing tables before import)
- Improved error handling with detailed SQL error messages
- License agreement validation
- Enhanced system requirements checker with auto-directory creation

### Fixed
- Step navigation issues with URL parameter handling
- Redirect problems causing ERR_UNSAFE_REDIRECT errors
- PDO namespace issues in admin account creation
- SQL import handling for multiple statements
- File path resolution for cross-platform compatibility
- Bootstrap CDN integration for consistent styling

### Improved
- Database import now supports both default and custom SQL files
- Better SQL cleaning and parsing for MySQL dump files
- Enhanced progress tracking with visual feedback
- Improved logging system with multiple fallback paths
- More robust file upload validation
- Better error messages with specific database names

## [1.0.0] - 2024-12-19

### Added
- Initial release of PHP Installer Package
- Complete web-based installation wizard
- System requirements validation (PHP version, extensions, permissions)
- Database configuration and automatic setup
- Database schema import functionality
- Application configuration management
- Admin account creation with secure password hashing
- CSRF protection for all forms
- Responsive Bootstrap UI
- Installation lock mechanism
- Comprehensive error handling and validation
- Template system for configuration files
- Session-based installation progress tracking
- Professional documentation and README

### Features
- **Core Classes:**
  - `Installer` - Main installer orchestration
  - `SystemChecker` - System requirements validation
  - `DatabaseManager` - Database operations
  - `ConfigWriter` - Configuration file generation
  - `AdminCreator` - Admin user management
  - `Utils` - Common utilities and helpers

- **Installation Steps:**
  - Welcome screen with project introduction
  - License agreement acceptance
  - System requirements check
  - Database configuration
  - Database schema import
  - Application settings
  - Admin account setup
  - Installation completion

- **Security Features:**
  - CSRF token validation
  - Input sanitization
  - Password hashing
  - Installation lock file
  - Secure session management

- **UI/UX:**
  - Bootstrap 5 responsive design
  - Progress indicator
  - Alert system for feedback
  - Professional styling
  - Mobile-friendly interface

### Technical Details
- PHP 7.4+ compatibility
- PDO database abstraction
- MVC architecture pattern
- Template-based configuration
- Comprehensive error logging
- Modular and extensible design

### Documentation
- Complete README with examples
- Installation and configuration guide
- Customization instructions
- Contributing guidelines
- License information