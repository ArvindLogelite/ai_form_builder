# DECISIONS

## Assumptions

- Forms remain in Draft mode until published.
- Only published forms are publicly accessible.
- Imported forms are editable before saving.
- AI generation currently uses a deterministic approach.
- Import parser follows a documented format.

---

# Part D Improvements

## 1. Publish / Draft Workflow

Problem

Users should not expose incomplete forms publicly.

Implementation

Added Draft and Published status.

Only Published forms are available through public URLs.

Trade-off

Did not implement scheduling.

Future

Publish scheduling and approval workflow.

---

## 2. CSV Export

Problem

Users should download collected submissions.

Implementation

CSV export endpoint.

Trade-off

Currently CSV only.

Future

Excel and PDF export.

---

## 3. Search + Pagination

Problem

Large numbers of submissions become difficult to manage.

Implementation

Server-side pagination and search.

Trade-off

Only basic keyword search implemented.

Future

Advanced filters.

---

# AI Strategy

Current

Rule-based schema generation.

Future

OpenAI/Gemini integration.

Automatic

- validation detection
- field inference
- section inference

---

# Import Strategy

Current

Deterministic parser.

Word

Heading → Section

Question → Field

Choice List → Dropdown/Radio

Excel

Header-based parser.

Future

Hybrid parser with AI-assisted inference.

---

# Trade-offs

Skipped

- Live deployment
- Conditional logic
- Version history
- Redis caching
- Docker
- Automated testing

Reason

Priority was given to completing mandatory assignment requirements.

---

# Validation Strategy

The application follows a review-before-publish workflow.

Every automatically generated or imported form passes through a manual review step before it becomes publicly available.

This reduces the risk of:

- Missing fields
- Incorrect field types
- Wrong dropdown options
- Incorrect validations
- Accidental publication

---

# Known Risks / Safe Guards

## AI Form Generation

Current implementation uses a deterministic schema generator instead of a real LLM.

Possible limitations:

- AI may not generate every expected field from a complex prompt.
- Validation rules may require manual adjustment.
- Generated field labels may need refinement.
- Complex business logic cannot be inferred automatically.

Safe Guard

Every generated form opens inside the Form Builder where users can review, modify, add or remove fields before publishing.

---

## Word Import

The parser is deterministic.

Possible limitations:

- Complex document formatting may not be parsed correctly.
- Tables, images and nested layouts are not fully supported.
- Some headings may not be detected as sections.
- Very long paragraphs are reported as unparsed.

Safe Guard

Users receive an editable preview before saving the imported form and can manually change field types or modify the generated structure.

---

## Excel Import

The parser expects a documented layout.

Possible limitations:

- Unsupported sheet structures may not be parsed completely.
- Merged cells and complex formatting are ignored.

Safe Guard

Users can review the generated schema and update field mappings before saving.

---

## Public Forms

Only forms with **Published** status are publicly accessible.

Safe Guard

Newly created forms remain in **Draft** mode by default to prevent accidental exposure.

# Two More Weeks

If more time were available, I would implement:

- Conditional Logic
- Form Templates
- Form Versioning
- QR Code Sharing
- Multi-language Forms
- Analytics Dashboard
- Webhooks
- Redis
- Docker
- CI/CD
- Unit Tests
- Role-based Authentication

---