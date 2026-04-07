# API Documentation

## Overview

This document provides comprehensive API documentation for the I.M System platform.

## Authentication

All API endpoints (except public routes) require authentication. Users must be logged in to access protected routes.

### Login
- **Endpoint:** `POST /login`
- **Parameters:**
  - `username` (required): User's username
  - `password` (required): User's password
  - `remember` (optional): Remember me checkbox
- **Response:** Redirects to user's dashboard or intended URL

### Logout
- **Endpoint:** `POST /logout`
- **Response:** Redirects to home page

## Form Submission Endpoints

### Investment Form
- **Endpoint:** `POST /forms/investment`
- **Authentication:** Required (user role)
- **Content-Type:** `multipart/form-data`
- **Required Fields:**
  - `first_name`, `last_name`, `email`, `phone`
  - `date_of_birth`, `nationality`, `address`, `city`, `region`, `postal_code`
  - `project_name`, `project_description`, `investment_amount`, `currency`
  - `investment_type`, `sector`, `investment_purpose`, `business_stage`
  - `target_market`, `motivation`
  - `accept_terms`, `accept_data_processing`
- **Optional Fields:**
  - `cv`, `business_plan`, `financial_projections`, `market_analysis`, `legal_documents`
- **Response:** JSON with `message` and `submission_number`

### Project Carrier Form
- **Endpoint:** `POST /forms/project-carrier`
- **Authentication:** Required (user role)
- **Content-Type:** `multipart/form-data`
- **Required Fields:**
  - Personal information (same as Investment form)
  - `project_name`, `project_description`, `sector`
  - `development_stage`, `project_type`, `target_market`
  - `team_size`, `funding_required`, `funding_currency`, `funding_purpose`
  - `location_region`, `location_city`
  - `motivation`, `accept_terms`, `accept_data_processing`
- **Response:** JSON with `message` and `submission_number`

### Auto-Entrepreneur Form
- **Endpoint:** `POST /forms/auto-entrepreneur`
- **Authentication:** Required (user role)
- **Content-Type:** `multipart/form-data`
- **Required Fields:**
  - Personal information
  - `business_name`, `business_description`, `business_type`, `business_sector`
  - `start_date`, `expected_monthly_revenue`
  - `business_address`, `business_city`, `business_region`
  - `has_legal_status`, `initial_investment`, `funding_source`
  - `monthly_expenses`, `has_bank_account`
  - `identity_document` (file, required)
  - `target_market`, `motivation`
  - `accept_terms`, `accept_data_processing`
- **Response:** JSON with `message` and `submission_number`

### INDH Form
- **Endpoint:** `POST /forms/indh`
- **Authentication:** Required (user role)
- **Content-Type:** `multipart/form-data`
- **Required Fields:**
  - Personal information
  - `project_title`, `project_description`, `project_type`, `project_category`
  - `community_impact`, `target_beneficiaries`, `project_goals`, `expected_outcomes`
  - `funding_required`, `funding_currency`, `project_duration_months`
  - `location_region`, `location_city`, `project_scope`
  - `community_involvement`, `motivation`
  - `accept_terms`, `accept_data_processing`
- **Response:** JSON with `message` and `submission_number`

### Training Form
- **Endpoint:** `POST /forms/training`
- **Authentication:** Required (user role)
- **Content-Type:** `multipart/form-data`
- **Required Fields:**
  - Personal information
  - `training_title`, `training_description`, `training_type`, `training_category`
  - `target_audience`, `participant_count`, `duration_hours`
  - `training_format`, `language_preference`
  - `learning_objectives`, `expected_outcomes`, `motivation`
  - `accept_terms`, `accept_data_processing`
- **Response:** JSON with `message` and `submission_number`

## Dashboard Endpoints

### User Dashboard
- **Endpoint:** `GET /user/dashboard`
- **Authentication:** Required (user role)
- **Response:** Dashboard view with user statistics and recent submissions

### Admin Dashboard
- **Endpoint:** `GET /admin/dashboard`
- **Authentication:** Required (admin role)
- **Response:** Admin dashboard with all submissions and statistics

### Sectoral Admin Dashboard
- **Endpoint:** `GET /sectoral/dashboard`
- **Authentication:** Required (sectoral_admin role)
- **Response:** Sectoral admin dashboard filtered by sector

## Error Responses

All endpoints return appropriate HTTP status codes:
- `200`: Success
- `302`: Redirect
- `401`: Unauthorized
- `403`: Forbidden
- `422`: Validation Error
- `500`: Server Error

Validation errors return JSON in the following format:
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```














