# PHP Installer Package

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue.svg)](https://php.net/)
[![GitHub release](https://img.shields.io/github/release/jmrashed/php-installer.svg)](https://github.com/jmrashed/php-installer/releases)

A professional, reusable web installer for any PHP application. Simplify your deployment process with an intuitive step-by-step installation wizard.

## ✨ Features

- **System Requirements Check** - Validates PHP version, extensions, and directory permissions
- **Database Setup** - Automated database creation and schema import
- **Configuration Management** - Generates `.env` and config files from templates
- **Admin Account Creation** - Optional administrator user setup
- **Installation Lock** - Prevents reinstallation after completion
- **CSRF Protection** - Secure form handling
- **Responsive UI** - Bootstrap-powered interface
- **Error Handling** - Comprehensive validation and user feedback

## 🚀 Quick Start

### Installation

```bash
# Clone the repository
git clone git@github.com:jmrashed/php-installer.git

# Or download and extract to your project
wget https://github.com/jmrashed/php-installer/archive/main.zip
```

### Integration

1. Copy the `php-installer` folder to your project root
2. Create your database schema file at `database/db.sql`
3. Configure installer settings in `config/installer.php`
4. Access via browser: `http://yourdomain.com/php-installer/`

## 📋 Requirements

- PHP 7.4 or higher
- PDO extension
- MySQL/MariaDB database
- Web server (Apache/Nginx)

## 🛠️ Configuration

### Basic Setup

Edit `config/installer.php`:

```php
<?php
return [
    'app_name' => 'Your Application',
    'version' => '1.0.0',
    'php_version' => '7.4',
    'required_extensions' => ['pdo_mysql', 'curl', 'mbstring'],
    'writable_dirs' => ['config', 'storage', 'uploads'],
    'database_file' => __DIR__ . '/../database/db.sql'
];
```

### Database Schema

Place your SQL schema in `database/db.sql`:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 📁 Directory Structure

```
php-installer/
├── config/
│   └── installer.php          # Configuration settings
├── database/
│   └── db.sql                 # Database schema
├── public/
│   └── index.php              # Entry point
├── src/
│   ├── Core/                  # Core installer classes
│   ├── Controllers/           # Request handlers
│   ├── Views/                 # UI templates
│   ├── Assets/                # CSS, JS, images
│   └── Templates/             # Config templates
└── storage/
    ├── logs/                  # Installation logs
    └── installer.lock         # Installation lock file
```

## 🎯 Usage Example

### For Laravel Projects

```php
// config/installer.php
return [
    'app_name' => 'Laravel Application',
    'required_extensions' => ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer'],
    'writable_dirs' => ['storage', 'bootstrap/cache'],
];
```

### For Custom PHP Projects

```php
// config/installer.php
return [
    'app_name' => 'Custom PHP App',
    'required_extensions' => ['pdo_mysql', 'curl', 'gd'],
    'writable_dirs' => ['uploads', 'cache', 'logs'],
];
```

## 🔧 Customization

### Custom Installation Steps

Extend the installer by modifying `src/Core/Installer.php`:

```php
private $steps = [
    'welcome',
    'license',
    'system_check',
    'db_config',
    'db_import',
    'app_config',
    'admin_account',
    'custom_step',    // Add your custom step
    'finish'
];
```

### Custom Templates

Create custom config templates in `src/Templates/`:

- `config_template.php` - Application configuration
- `env_template.php` - Environment variables

## 📋 Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed list of changes and version history.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Md Rasheduzzaman**  
Full-Stack Engineer & Technical Project Manager

- Email: jmrashed@gmail.com
- GitHub: [@jmrashed](https://github.com/jmrashed)
- LinkedIn: [Md Rasheduzzaman](https://linkedin.com/in/jmrashed)

## 🙏 Acknowledgments

- Bootstrap for the responsive UI framework
- PHP community for best practices and standards

## 📊 Stats

![GitHub stars](https://img.shields.io/github/stars/jmrashed/php-installer?style=social)
![GitHub forks](https://img.shields.io/github/forks/jmrashed/php-installer?style=social)
![GitHub issues](https://img.shields.io/github/issues/jmrashed/php-installer)

---

⭐ **Star this repository if it helped you!**