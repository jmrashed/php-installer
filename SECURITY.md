# Security Policy

## Supported Versions

Only the latest tagged release is supported with security fixes. Please
upgrade to the latest version before reporting an issue.

## Reporting a Vulnerability

Please **do not** open a public GitHub issue for security vulnerabilities.

Instead, email **jmrashed@gmail.com** with:

- A description of the vulnerability and its potential impact.
- Steps to reproduce it (a minimal repro is very helpful).
- Which version/commit you tested against.

You should expect an initial response within **5 business days**. If the
issue is confirmed, a fix will be prioritized and a new release cut; you'll
be credited in the release notes unless you'd prefer otherwise.

## Scope

This installer collects database credentials and an admin password during
installation, and writes a generated configuration file to disk. Reports
concerning any of the following are especially welcome:

- Injection into the generated application config file.
- Cross-site scripting (XSS) in any installer view.
- Disclosure of credentials or session data (e.g. via debug output).
- CSRF or session-handling weaknesses in the install flow.
- Path traversal or unsafe file handling in the SQL/ZIP upload flow.

For non-security bugs, please use the normal GitHub issue tracker instead.
