# Contributing

Thank you for your interest in this project.

This repository is primarily a personal learning and portfolio project focused on Symfony backend development, REST APIs, security and automated testing.

## Development Setup

Clone the repository:

```bash
git clone YOUR_REPOSITORY_URL
cd YOUR_PROJECT_DIRECTORY
```

Install dependencies:

```bash
composer install
```

Configure your local environment using `.env.local`.

Create the database and run migrations:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Development Workflow

For new features or fixes, create a dedicated branch:

```bash
git checkout -b feature/project-filtering
```

Recommended branch prefixes:

- `feature/` — new functionality
- `fix/` — bug fixes
- `refactor/` — code improvements without changing behavior
- `test/` — tests
- `docs/` — documentation

## Commit Convention

Commits should remain small and focused.

Examples:

```text
feat: add project ownership
fix: validate project status before update
test: add project authorization tests
refactor: simplify project voter
docs: update API documentation
```

## Testing

Before committing important changes, run:

```bash
php bin/phpunit
```

New authorization or business rules should ideally include corresponding tests.

## Code Guidelines

- Follow Symfony and PHP best practices.
- Keep controllers focused on HTTP-related responsibilities.
- Keep authorization logic inside Symfony Voters when appropriate.
- Validate incoming data before persistence.
- Never trust ownership information received from the client.
- Do not commit credentials, secrets or local environment files.

## Pull Requests

A pull request should:

1. Describe what was changed.
2. Explain why the change was necessary.
3. Include tests when relevant.
4. Avoid unrelated changes.
