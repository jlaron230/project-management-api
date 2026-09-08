# Architecture

## Overview

Project Management API is a Symfony application designed to manage users
and projects through a secured REST API.

The current architecture separates HTTP handling, persistence and
authorization responsibilities using Symfony, Doctrine and PostgreSQL.

## High-Level Architecture

``` text
Client
  |
  | HTTP / JSON
  v
Symfony Router
  |
  v
ProjectController
  |
  +-- Authentication
  +-- Authorization --> ProjectVoter
  |
  +-- Doctrine ORM --> PostgreSQL
```

## Main Components

### Controllers

Controllers expose the REST API and handle HTTP requests.

The main project routes are:

``` text
GET     /api/projects
GET     /api/projects/{id}
POST    /api/projects
PATCH   /api/projects/{id}
DELETE  /api/projects/{id}
```

The controller is responsible for:

-   Receiving HTTP requests
-   Decoding JSON payloads
-   Performing basic validation
-   Calling authorization mechanisms
-   Persisting entities through Doctrine
-   Returning JSON responses

As the application evolves, validation and business logic can
progressively be extracted from controllers.

## Entities

### User

The `User` entity represents an authenticated application user.

Main responsibilities:

-   Authentication identity
-   Password and roles
-   Email verification
-   Relationship with owned projects

### Project

The `Project` entity represents a project managed by the API.

A project currently contains:

``` text
id
name
description
status
createdAt
owner
```

Each project belongs to one user.

``` text
User (1) -------- owns -------- (*) Project
```

## Persistence

Persistence is handled with Doctrine ORM.

``` text
Symfony --> Doctrine ORM --> PostgreSQL
```

Repositories are used to retrieve entities from the database.

Example:

``` php
$project = $projectRepository->find($id);
```

Database schema changes are managed with Doctrine migrations.

## Authentication

Authentication is handled by Symfony Security.

The application currently uses session-based authentication through
`form_login`.

Once authenticated, the current user is available through:

``` php
$this->getUser();
```

Protected `/api` routes require an authenticated user.

## Authorization

Project-level authorization is handled by a custom Symfony Voter.

The `ProjectVoter` currently manages:

``` text
PROJECT_VIEW
PROJECT_EDIT
PROJECT_DELETE
```

Example:

``` php
$this->denyAccessUnlessGranted(
    ProjectVoter::EDIT,
    $project
);
```

The voter decides whether the authenticated user can perform a specific
action on a specific project.

### Ownership Rule

For a standard user:

``` text
project.owner === authenticated user
        |
     YES / NO
      |     |
   ALLOW   DENY
```

Users with `ROLE_ADMIN` can manage projects regardless of ownership.

This avoids duplicating authorization rules inside each controller
action.

## Project Creation

Project ownership is assigned by the backend from the authenticated
user.

``` text
Authenticated User
       |
       v
POST /api/projects
       |
       v
ProjectController
       |
       +-- Validate payload
       +-- Create Project
       +-- Assign current user as owner
       |
       v
Doctrine
       |
       v
PostgreSQL
```

The owner must not be trusted from client input.

Example:

``` php
$project->setOwner($this->getUser());
```

## Project Listing

For standard users, the repository only returns projects belonging to
the authenticated user.

``` php
$projects = $projectRepository->findBy([
    'owner' => $this->getUser(),
]);
```

Administrators can retrieve all projects.

## Validation

The API currently performs basic validation directly in the controller.

Examples:

-   Project name required
-   Minimum name length
-   Description validation
-   Allowed project statuses

Current allowed statuses:

``` text
todo
doing
done
```

A future improvement is to move validation to Symfony Validator and
dedicated DTOs.

## Testing

Functional tests are implemented with Symfony `WebTestCase`.

Current and planned scenarios include:

-   Unauthenticated users cannot access protected routes
-   Authenticated users can create projects
-   Created projects belong to the authenticated user
-   User B cannot modify a project owned by User A
-   User B cannot delete a project owned by User A
-   Administrators can manage projects regardless of ownership

Example authorization flow:

``` text
User B
  |
  | PATCH /api/projects/{id}
  v
ProjectController
  |
  v
ProjectVoter
  |
  +-- Owner? No
  +-- Admin? No
  |
  v
403 Forbidden
```

## Current Technology Stack

### Backend

-   PHP
-   Symfony
-   Doctrine ORM
-   REST API

### Database

-   PostgreSQL

### Security

-   Symfony Security
-   Session authentication
-   Role-based access
-   Project ownership
-   Custom Symfony Voter

### Testing

-   PHPUnit
-   Symfony WebTestCase

### Development

-   Git
-   GitHub
-   Docker
-   Composer
-   Linux / Windows development environment

## Planned Evolution

The project is intended to evolve toward a more complete full-stack
architecture.

``` text
React / TypeScript
        |
        | HTTP / JSON
        v
    Symfony API
        |
   +----+-----+
   |          |
Security   Doctrine
   |          |
ProjectVoter  |
              v
          PostgreSQL
```

Planned improvements:

-   Expand functional test coverage
-   Use Symfony Validator
-   Introduce DTOs for API payloads
-   Add pagination and filtering
-   Improve API error responses
-   Add API documentation
-   Build a React / TypeScript frontend
-   Improve Docker configuration
-   Add CI workflow
