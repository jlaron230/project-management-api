# Changelog

All notable changes to this project will be documented in this file.

The project is currently under active development.

---

## [Unreleased]

### Planned

- Expand functional test coverage.
- Improve validation using Symfony Validator.
- Add project filtering and pagination.
- Improve API error handling.
- Add API documentation.
- Develop a React/TypeScript frontend.

---

## [0.2.0] - 2026-09

### Added

- Project ownership system.
- `ROLE_USER` and `ROLE_ADMIN` authorization.
- Custom `ProjectVoter`.
- `PROJECT_VIEW` permission.
- `PROJECT_EDIT` permission.
- `PROJECT_DELETE` permission.
- Functional tests for authentication and authorization.
- User/project Doctrine relationship.

### Changed

- Project access now depends on ownership.
- Administrators can manage projects regardless of ownership.
- Project update endpoint supports partial modifications.
- Improved HTTP responses for invalid requests.

### Security

- Project ownership is assigned from the authenticated user.
- Unauthorized users cannot modify projects owned by another user.
- Project permissions are centralized inside `ProjectVoter`.

---

## [0.1.0] - 2026-08

### Added

- Initial Symfony application.
- PostgreSQL database integration.
- Doctrine ORM configuration.
- User entity.
- Project entity.
- Project CRUD API.
- Project status management:
    - `todo`
    - `doing`
    - `done`
- Session authentication.
- Basic request validation.

---

## Versioning

This project follows a simple semantic versioning approach:

```text
MAJOR.MINOR.PATCH
