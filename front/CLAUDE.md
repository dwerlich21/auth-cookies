# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Essential commands (use yarn, not npm):
- `yarn install` - Install dependencies
- `yarn serve` - Start development server with hot-reload
- `yarn build` - Build for production
- `yarn lint` - Lint and fix files

## Project Architecture

This is a Vue 3 application using the Composition API with a corporate/admin dashboard theme called "Velzon". The application follows a modular architecture with clear separation of concerns.

### Core Structure:
- **Vue 3** with Composition API
- **Vue Router** for routing with authentication guards
- **Vuex** for centralized state management
- **Bootstrap Vue 3** for UI components
- **Axios** for HTTP requests with interceptors
- **SCSS** for styling with corporate theme structure

### Key Directories:
- `src/components/base/` - Reusable base components (page-base, modal-form, table-lists, etc.)
- `src/components/composables/` - Composable functions for business logic (cruds.js, messages.js, functions.js)
- `src/constants/` - Static data definitions for different entities
- `src/views/` - Page components organized by feature (patrimonies, dashboard, reports, etc.)
- `src/state/modules/` - Vuex modules (api.js for CRUD operations, auth.js, layout.js)
- `src/assets/scss/` - SCSS files organized by theme structure

### Authentication & HTTP:
- JWT token-based authentication stored in localStorage
- Axios interceptors handle automatic token attachment and 401/403 responses
- Router guards check authentication and redirect to login when necessary
- Custom permission directive for role-based access control

### State Management Pattern:
- Vuex store with modular structure
- `api.js` module handles all CRUD operations with standardized methods
- Session-based caching using localStorage for API requests
- Loading and submission states managed centrally

### Form & Data Handling:
- Custom CRUD composables in `cruds.js` for standardized form operations
- FormData conversion utilities for file uploads
- Form validation helpers
- Standardized success/error notification system using Notivue

### UI Components:
- Base components for common patterns (pagination, filters, modals)
- Corporate theme with SCSS variable system
- Responsive design with Bootstrap 5
- Custom widgets and charts using ApexCharts

### Key Files to Understand:
- `src/components/composables/cruds.js` - CRUD operation helpers
- `src/state/modules/api.js` - Centralized API state management
- `src/http/index.js` - Axios configuration with auth interceptors
- `src/router/index.js` - Route definitions with auth guards
- `src/components/base/page-base.vue` - Base layout wrapper