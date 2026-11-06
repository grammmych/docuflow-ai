# DocuFlow AI

A document processing REST API built with PHP 8.4 and Symfony 7.4 LTS.

This project is developed as a hands-on backend engineering exercise, focusing on API design, relational databases, asynchronous processing, automated testing, and AI integration.

## Tech Stack

**Currently implemented:**

- PHP 8.4, Symfony 7.4 LTS
- Nginx, PHP-FPM
- Docker Compose
- Composer
- Bruno API Client

**Planned:**

- PostgreSQL and Doctrine ORM
- RabbitMQ and Symfony Messenger
- PHPUnit (unit and integration tests)
- AI-powered document analysis
- Structured logging and health checks

## Project Goals

The application will support:

- Document metadata management through a REST API
- Asynchronous document processing
- AI-powered text summarization and information extraction
- Document processing status tracking
- Error handling and retry mechanisms
- Automated testing

## Getting Started

### Requirements

- Docker Desktop with Docker Compose
- Git

PHP and Composer do not need to be installed on the host machine.

### Installation

Clone the repository:

```bash
git clone https://github.com/grammmych/docuflow-ai.git
cd docuflow-ai
```

Build and start the containers:

```bash
docker compose up -d --build
```

Check the Symfony version:

```bash
docker compose exec app php bin/console --version
```

### API

The following endpoint is currently available:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/health` | Basic application health check |

Example:

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

## API Testing

The repository includes a Bruno collection in `api-tests/DocuFlow AI/`.

Open the collection in Bruno, select the `Local` environment, and execute the Health Check request.

## Development Roadmap

- [x] Docker development environment
- [x] Symfony installation and basic API endpoint
- [x] Bruno API collection
- [ ] PostgreSQL and Doctrine ORM
- [ ] Document CRUD API
- [ ] RabbitMQ and Symfony Messenger
- [ ] Background document processing
- [ ] AI integration
- [ ] Unit and integration tests
- [ ] Production-oriented improvements

## Project Status

Early development. The project is being built incrementally with an emphasis on understanding the underlying architecture and implementation decisions.
