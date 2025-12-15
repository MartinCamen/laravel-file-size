# Contributing

Contributions are welcome and will be fully credited.

## Pull Requests

- **[PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)** - The easiest way to apply the conventions is to run `composer format`.
- **Add tests** - Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Consider our release cycle** - We try to follow [SemVer v2.0.0](https://semver.org/). Randomly breaking public APIs is not an option.
- **Create feature branches** - Don't ask us to pull from your main branch.
- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.
- **Send coherent history** - Make sure each individual commit in your pull request is meaningful.

## Development Setup

```bash
# Clone the repository
git clone https://github.com/martincamen/laravel-file-size.git
cd laravel-file-size

# Install dependencies
composer install
```

## Running Tests

```bash
# Run tests
composer test

# Run tests with coverage
composer test-coverage
```

## Code Quality

```bash
# Fix code style
composer format

# Run static analysis
composer analyse

# Check for modernization opportunities
composer rector

# Apply rector fixes
composer rector-fix
```

## Commit Messages

Please use meaningful commit messages. Consider using [Conventional Commits](https://www.conventionalcommits.org/) format:

- `feat:` for new features
- `fix:` for bug fixes
- `docs:` for documentation changes
- `test:` for test additions or modifications
- `refactor:` for code refactoring
- `chore:` for maintenance tasks

## Code of Conduct

Please note that this project is released with a [Contributor Code of Conduct](https://www.contributor-covenant.org/version/2/1/code_of_conduct/). By participating in this project you agree to abide by its terms.

## Security

If you discover any security-related issues, please email the maintainer instead of using the issue tracker.
