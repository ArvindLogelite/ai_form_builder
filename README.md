# AI Form Builder Assignment

## Repository

### Backend
https://github.com/ArvindLogelite/ai_form_builder

### Frontend
https://github.com/ArvindLogelite/ai_form_builder_front

---

## Live Demo

Not deployed.

Please clone both repositories and follow the setup instructions below to run the project locally.
Please find the .env.example file for the db details and more

---

# Project Overview

AI Form Builder is a web application that allows users to create dynamic forms using a drag-and-drop builder, generate forms using AI prompts, import forms from Word and Excel documents, publish forms publicly, and collect submissions.

The project is developed using Laravel 11, React.js, MySQL and Queue Jobs.

---

## Why Separate Frontend & Backend?

The project is divided into two independent repositories:

### Backend (Laravel)

Responsible for:

- REST API development
- Business logic
- Form generation
- AI job processing
- Import parsing
- Database operations
- Submission management

Repository:
https://github.com/ArvindLogelite/ai_form_builder

---

### Frontend (React.js)

Responsible for:

- User Interface
- Drag & Drop Form Builder
- Form Preview
- Dashboard
- AI Generator UI
- Import Preview
- Public Form Rendering

Repository:
https://github.com/ArvindLogelite/ai_form_builder_front

---

### Why this architecture?

The frontend and backend are intentionally separated to follow a modern client-server architecture.

Benefits:

- Clear separation of concerns.
- Independent development and deployment.
- Frontend can consume the API from any backend environment.
- Backend APIs can be reused by web or mobile applications.
- Easier maintenance and scalability.
- Better team collaboration where frontend and backend developers can work independently.

---

# Features

## Part A – Form Builder

- Drag & Drop Form Builder
- Dynamic Form Rendering
- Multiple Field Types
    - Text
    - Email
    - Textarea
    - Dropdown
    - Radio
    - Checkbox
    - Date
    - File Upload
- Field Properties Panel
- Required Fields
- Default Values
- Help Text
- Public Form Rendering
- Form Submission
- Dashboard
- Search
- Pagination
- CSV Export

---

## Part B – AI Form Generation

Implemented using Laravel Queue Jobs.

Current implementation uses a deterministic schema generator.

Future implementation can integrate:

- OpenAI
- Gemini
- Claude

AI flow:

User Prompt

↓

AI Job Created

↓

Queue Worker

↓

Schema Generated

↓

Form Saved

↓

Dashboard

---

## Part C – Import Word & Excel

Implemented Features

- DOCX Import
- XLSX Import
- Automatic Field Detection
- Section Detection
- Dropdown Detection
- Radio Detection
- Field Type Mapping
- Editable Preview
- Save Imported Form
- Defensive Parser
- Unparsed Block Reporting

Parser Strategy

Word documents are parsed deterministically.

Excel sheets are parsed using a predefined layout.

AI inference can be added later for ambiguous fields.

---

## Part D – Improvements

Implemented Improvements

### 1. Publish / Draft Workflow

Forms remain in Draft mode until published.

Only published forms are accessible publicly.

---

### 2. CSV Export

Submissions can be exported as CSV.

---

### 3. Search + Pagination

Dashboard supports searching and paginating submissions.

---

# Tech Stack

Backend

- Laravel 11
- PHP 8.2
- MySQL
- Queue Jobs

Frontend

- React.js
- Vite
- Bootstrap
- Axios
- React Router
- DnD Kit

---

# Packages Used

Backend

- phpoffice/phpword
- phpoffice/phpspreadsheet

Frontend

- @dnd-kit/core
- @dnd-kit/sortable
- axios
- bootstrap
- react-router-dom

---

# Installation

## Backend

```bash
git clone https://github.com/ArvindLogelite/ai_form_builder.git

cd ai_form_builder

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

## Frontend

```bash
git clone https://github.com/ArvindLogelite/ai_form_builder_front.git

cd ai_form_builder_front

npm install

npm run dev
```

---

# Environment Variables

```
APP_NAME=

APP_ENV=

APP_KEY=

APP_URL=

DB_CONNECTION=mysql

DB_HOST=

DB_PORT=

DB_DATABASE=

DB_USERNAME=

DB_PASSWORD=
```

---

# API Endpoints

| Method | Endpoint |
|---------|----------|
| GET | /api/forms |
| POST | /api/forms |
| GET | /api/public/forms/{slug} |
| POST | /api/forms/{slug}/submit |
| PATCH | /api/forms/{id}/status |
| GET | /api/forms/{id}/submissions |
| GET | /api/forms/{id}/submissions/export |
| POST | /api/ai/generate |
| POST | /api/import |
| POST | /api/import/save |

---

# Architecture

React UI

↓

Laravel REST APIs

↓

Business Services

↓

Database

↓

Queue Jobs

↓

AI Generator

---

# AI Prompt Strategy

Current implementation uses a rule-based schema generator.

Future implementation will:

- Generate fields
- Detect validations
- Detect dropdown options
- Detect sections
- Improve field labels

using an LLM.

---

# Import Strategy

Deterministic Parser

↓

Field Detection

↓

Preview

↓

User Mapping

↓

Save Form

Future improvement:

AI-assisted field type inference.

---

# Known Limitations

- Live deployment not included.
- AI uses rule-based schema generation.
- Import parser supports a documented layout.
- Queue is configured for local development.
- Advanced conditional logic is not implemented.

---

# Future Improvements

- Conditional Logic
- Form Versioning
- Multi-language Forms
- QR Code Sharing
- Rate Limiting
- Spam Detection
- Redis Cache
- Webhooks
- Docker
- Automated Tests

---

# Author

Arvind Vishwakarma