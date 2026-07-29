# Contributing

Thanks for considering a contribution to `php-installer`.

## Getting started

1. Fork the repository.
2. Create your feature branch: `git checkout -b feature/amazing-feature`.
3. Install dependencies: `composer install`.

## Before opening a pull request

Run the same checks CI runs (`.github/workflows/ci.yml`):

```bash
composer test       # PHPUnit test suite
composer stan        # PHPStan static analysis (level 2)
composer cs            # PHP_CodeSniffer against PSR-12
composer cs-fix          # auto-fix what phpcbf can fix
```

- **Coding standard**: [PSR-12](https://www.php-fig.org/psr/psr-12/), enforced
  by `phpcs.xml`. `composer cs-fix` handles most mechanical issues
  automatically; `composer cs` should report zero errors before you push
  (line-length warnings in view templates are accepted and don't need fixing).
- **Static analysis**: `composer stan` must report zero errors. `src/Views/**`
  is excluded from analysis — see the comment in `phpstan.neon` for why.
- **Tests**: add a test for any bug fix or new behavior. `tests/InstallerTest.php`
  is a standard `PHPUnit\Framework\TestCase` — add new test methods there or
  create a new test class alongside it.

If you're fixing a security issue, see [SECURITY.md](SECURITY.md) instead of
opening a public PR/issue first.

## Submitting

1. Commit your changes: `git commit -m 'Add amazing feature'`.
2. Push to the branch: `git push origin feature/amazing-feature`.
3. Open a Pull Request describing what changed and why.

CI (GitHub Actions) runs the same checks above on PHP 8.1/8.2/8.3 — a PR
won't be merged with a red build.
