# Project Management API

REST API built with **Symfony** for managing users and projects with authentication, role-based authorization and project ownership.

This project was developed as part of my continued practice of backend development with Symfony, with a focus on application security, REST APIs, database relationships and automated testing.

## Features

### Authentication & Users
- User authentication with Symfony Security
- Session-based authentication
- User roles (`ROLE_USER`, `ROLE_ADMIN`)
- Access control for protected routes

### Project Management
- Create projects
- Retrieve project list
- Retrieve a project by ID
- Update projects
- Delete projects
- Project status management (`todo`, `doing`, `done`)
- Automatic association between a project and its owner

### Authorization

Project permissions are managed using a custom **Symfony Voter**.

A standard user can:
- View their own projects
- Edit their own projects
- Delete their own projects

An administrator can access and manage projects regardless of ownership.

### Validation & Error Handling
- JSON request validation
- Project name and description validation
- Status validation
- HTTP error responses (`400`, `403`, `404`, etc.)

### Testing
Functional tests are progressively implemented with **PHPUnit** to validate authentication and authorization rules.

Examples:
- Unauthenticated users cannot access protected project routes
- Authenticated users can create their own projects
- A user cannot modify another user's project
- Administrator permissions

## Tech Stack

### Backend
- PHP
- Symfony
- Doctrine ORM
- REST API

### Database
- PostgreSQL

### Security
- Symfony Security
- Session authentication
- Role-based access control
- Symfony Voters
- Project ownership

### Testing
- PHPUnit
- Symfony WebTestCase

### Development
- Git / GitHub
- Composer
- Docker
- Linux / Windows development environment

## API Endpoints

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/projects` | Get projects |
| `GET` | `/api/projects/{id}` | Get a project |
| `POST` | `/api/projects` | Create a project |
| `PATCH` | `/api/projects/{id}` | Update a project |
| `DELETE` | `/api/projects/{id}` | Delete a project |

Protected endpoints require authentication.

## Example

### Create a project

```http
POST /api/projects
Content-Type: application/json
```

```json
{
  "name": "Portfolio API",
  "description": "Project created with Symfony",
  "status": "todo"
}
```

Example response:

```json
{
  "message": "Project created successfully"
}
```

## Project Structure

```text
src/
├── Controller/
│   └── ProjectController.php
├── Entity/
│   ├── Project.php
│   └── User.php
├── Repository/
├── Security/
│   └── Voter/
│       └── ProjectVoter.php
└── ...

tests/
└── Controller/
    └── ProjectControllerTest.php
```

## Installation

Clone the repository:

```bash
git clone YOUR_REPOSITORY_URL
cd YOUR_PROJECT_DIRECTORY
```

Install dependencies:

```bash
composer install
```

Configure your local environment:

```bash
cp .env .env.local
```

Configure the PostgreSQL connection in `.env.local`:

```env
DATABASE_URL="postgresql://USER:PASSWORD@127.0.0.1:5432/DATABASE"
```

Create the database:

```bash
php bin/console doctrine:database:create
```

Run migrations:

```bash
php bin/console doctrine:migrations:migrate
```

Start the application:

```bash
symfony server:start
```

## Running Tests

Create the test database:

```bash
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test --no-interaction
```

Run PHPUnit:

```bash
php bin/phpunit
```

## Roadmap

The project is still evolving. Planned improvements include:

- [x] Project CRUD
- [x] Authentication
- [x] User/project ownership
- [x] Role-based authorization
- [x] Symfony Voter
- [x] Initial functional tests
- [ ] Expand the functional test suite
- [ ] Improve validation with Symfony Validator
- [ ] Add pagination and filtering
- [ ] Improve API error responses
- [ ] Add API documentation
- [ ] Develop a React/TypeScript frontend
- [ ] Containerize the complete application

## Purpose

The objective of this project is to strengthen and demonstrate practical skills in:

- Symfony backend development
- REST API design
- Object-oriented programming
- Relational database modeling
- Authentication and authorization
- Automated testing
- Git workflow and software maintainability

## Author

**Jérôme**

Full-Stack Developer  
PHP / Symfony • TypeScript / React • Node.js • SQL • Docker
