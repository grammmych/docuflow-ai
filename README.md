# DocuFlow AI

A REST API for document management and, eventually, asynchronous AI-powered document processing. Built as a hands-on PHP backend engineering project.

> **Status:** Work in progress. User registration and document metadata endpoints are implemented. JWT authentication, ownership enforcement, file uploads, message queues, and AI processing are not implemented yet.

## Tech Stack

**Implemented**

- PHP 8.4 and Symfony 7.4 LTS
- PostgreSQL 17, Doctrine ORM, Doctrine Migrations
- Symfony Security (user model and password hashing), Validator, UID (UUID v7)
- Nginx, PHP-FPM, Docker Compose, Composer
- Bruno API collection

**Planned**

- JWT authentication and per-user document access
- RabbitMQ and Symfony Messenger workers
- PDF uploads, text extraction, AI-powered analysis
- PHPUnit tests, OpenAPI documentation, CI, structured logging

## Implemented Features

- User registration with email normalization, request validation, password hashing, and duplicate-email handling
- Document metadata stored in PostgreSQL
- Public UUID v7 document identifiers (internal numeric IDs are not exposed in document responses)
- Required document-to-user relationship at the database level
- Health-check endpoint

**Known limitation:** Document creation is temporarily unavailable: the database now requires `documents.owner_id`, but the existing creation flow does not assign the authenticated user yet. Document endpoints are not yet protected by JWT or scoped to the current user. Do not deploy this API as a multi-user production service in its current state.

## Getting Started (Development)

### Prerequisites

- Docker Engine with the Docker Compose plugin **or** Docker Desktop with Compose
- Git

PHP and Composer do not need to be installed on the host.

### 1. Clone the repository

```bash
git clone https://github.com/grammmych/docuflow-ai.git
cd docuflow-ai
```

### 2. Configure the database password

Create a `.env` file **in the repository root** (next to `compose.yaml`):

```dotenv
POSTGRES_PASSWORD=replace_with_a_local_password
```

The password is used by Docker Compose for the PostgreSQL container and the Symfony `DATABASE_URL` environment variable. Do **not** commit the real `.env` file.

> Note: This password initializes the PostgreSQL user when the database volume is first created. Changing `.env` later does not automatically update the password in an existing PostgreSQL volume.

### 3. Build and start services

```bash
docker compose up -d --build
```

### 4. Install PHP dependencies

```bash
docker compose exec app composer install
```

The source directory `./app` is bind-mounted into the PHP container. The development Dockerfile includes PHP extensions and Composer, but does **not** install project dependencies during the image build.

### 5. Apply database migrations

```bash
docker compose exec app php bin/console doctrine:migrations:migrate
```

Verify the schema:

```bash
docker compose exec app php bin/console doctrine:schema:validate
```

### 6. Check the API

```bash
curl http://localhost:8080/api/health
```

Expected response:

```json
{
  "status": "ok",
  "application": "DocuFlow AI"
}
```

The HTTP API is available at `http://localhost:8080`. PostgreSQL is mapped to `127.0.0.1:5432` on the host; its data persists in the `postgres_data` Docker volume.

## API Endpoints

| Method | Endpoint | Status | Description |
| --- | --- | --- | --- |
| `GET` | `/api/health` | Working | Application health check |
| `POST` | `/api/auth/register` | Working | Register a user with an email and password |
| `GET` | `/api/documents` | Implemented, unprotected | List document metadata |
| `GET` | `/api/documents/{uuid}` | Implemented, unprotected | Find a document by its public UUID v7 |
| `POST` | `/api/documents` | Temporarily unavailable | Create document metadata; awaits authenticated ownership assignment |

Example registration request:

```bash
curl -X POST http://localhost:8080/api/auth/register \
  -H 'Content-Type: application/json' \
  -d '{"email":"test@example.com","password":"ExamplePassword123!"}'
```

A successful registration returns HTTP `201`:

```json
{
  "message": "User registered successfully"
}
```

Validation errors return HTTP `422`; an already registered email returns HTTP `409`.

## API Testing with Bruno

The Bruno collection is located in `api-tests/DocuFlow AI/`. Open it in Bruno, select the `Local` environment, and use `http://localhost:8080` as the base URL.

## Development Roadmap

- [x] Docker development environment and Symfony 7.4
- [x] PostgreSQL, Doctrine ORM, migrations
- [x] Document metadata endpoints and UUID v7
- [x] User entity, registration endpoint, password hashing, input validation
- [x] Required document-owner foreign key
- [ ] JWT login and authentication
- [ ] Authorization and per-user document isolation
- [ ] Restore document creation with authenticated ownership
- [ ] File uploads and PDF text extraction
- [ ] RabbitMQ, Symfony Messenger, background workers and retries
- [ ] AI summarization, classification and structured extraction
- [ ] PHPUnit tests, OpenAPI, CI and production hardening

## Notes

This is a learning project, not a production-ready service. The development Compose setup uses source-code bind mounts and local credentials; production deployment will require a separate configuration, stronger security controls, and automated tests.
