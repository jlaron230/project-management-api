# Security Policy

## Overview

Security is an important part of this project.

The API uses Symfony Security to manage authentication and authorization.

## Authentication

Protected routes require an authenticated user.

Authentication is currently session-based and managed through Symfony Security.

## Authorization

The application currently supports two main roles:

```text
ROLE_USER
ROLE_ADMIN
```

Project-level permissions are handled through a custom `ProjectVoter`.

The following permissions are currently defined:

```text
PROJECT_VIEW
PROJECT_EDIT
PROJECT_DELETE
```

## Project Ownership

Projects belong to a specific authenticated user.

A standard user can only:

- View projects they own.
- Edit projects they own.
- Delete projects they own.

Administrators can manage projects regardless of ownership.

The project owner must be determined by the authenticated user on the server side and must never be trusted directly from client input.

Example:

```php
$project->setOwner($this->getUser());
```

## Input Validation

Incoming JSON data is validated before being persisted.

Project statuses are restricted to:

```text
todo
doing
done
```

Additional validation improvements using Symfony Validator are planned.

## Secrets

Sensitive configuration must never be committed to the repository.

Files containing local credentials should remain ignored by Git.

Examples:

```text
.env.local
.env.*.local
```

Public configuration examples must only contain placeholder credentials.

## Testing

Functional tests are used to verify security rules, including:

- Protected route access.
- Project ownership.
- Unauthorized project modification.
- Role-based permissions.
- Administrator access.

## Reporting a Security Issue

If you discover a security issue in this project, please avoid publishing sensitive information in a public issue.

Contact the repository owner directly instead.

## Disclaimer

This project is primarily intended for learning and portfolio purposes and should not be considered production-ready without an additional security review.
