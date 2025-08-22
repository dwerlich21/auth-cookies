# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a monorepo containing a Laravel 12 backend API and Vue 3 frontend application with cookie-based authentication using Sanctum tokens. The authentication system uses dual tokens (access_token: 10 minutes, refresh_token: 24 hours) with automatic refresh via middleware.

## Development Commands

### Backend (Laravel API) - /api directory
```bash
cd api
composer install          # Install PHP dependencies
php artisan serve         # Start development server on port 8000
php artisan migrate       # Run database migrations
php artisan db:seed       # Seed the database
php artisan test         # Run PHPUnit tests
php artisan pint         # Format code with Laravel Pint
php artisan queue:listen # Start queue worker
composer dev             # Run full dev environment (server, queue, logs, vite)
```

### Frontend (Vue 3) - /front directory
```bash
cd front
yarn install             # Install dependencies (use yarn, not npm)
yarn dev                 # Start development server with hot-reload
yarn build              # Build for production
yarn lint               # Lint and fix files
```

## Architecture

### Backend Architecture (Laravel)

**Key Patterns:**
- Repository pattern for data access (`app/Repositories/`)
- Service layer for business logic (`app/Services/`)
- Custom exception handling with specific exception types
- Tenant-based multi-tenancy with global scopes
- Cookie-based authentication with Sanctum

**Authentication Flow:**
1. `CookieManager` service handles cookie creation/renewal (access_token: 10 min, refresh_token: 24 hours)
2. `CookieTokenGuard` provides custom authentication guard
3. `RefreshTokenMiddleware` automatically refreshes tokens near expiration
4. `CookieToTokenMiddleware` converts cookies to bearer tokens for API requests

**Directory Structure:**
- `app/Models/` - Eloquent models with tenant scoping trait
- `app/Services/` - Business logic layer (BaseService, CookieManager, TenantService, UserService)
- `app/Repositories/` - Data access layer extending BaseRepository
- `app/Http/Controllers/Api/` - API controllers
- `app/Exceptions/` - Custom exception types (ApiException, BusinessException, ValidationException, etc.)
- `app/Guards/` - Custom authentication guards
- `app/Observers/` - Model observers for automated actions

### Frontend Architecture (Vue 3)

**Technology Stack:**
- Vue 3 with Composition API
- Pinia for state management
- Vue Router with authentication guards
- Axios with interceptors for HTTP requests
- Bootstrap Vue 3 for UI components
- SCSS with corporate theme structure

**Key Directories:**
- `src/components/base/` - Reusable base components (Crud.vue, TableLists.vue, ModalForm.vue, etc.)
- `src/composables/` - Composable functions for business logic
- `src/services/` - API service classes extending BaseService
- `src/stores/` - Pinia stores (auth, layout, landingPage)
- `src/views/` - Page components organized by feature

**Important Patterns:**
- CRUD operations handled via `useCrud` composable
- Form handling with automatic FormData conversion for file uploads
- Permission-based access control via custom directive
- Standardized notification system using Notivue
- Session-based API response caching

## Testing

### Backend Testing
```bash
cd api
php artisan test                    # Run all tests
php artisan test --filter TestName  # Run specific test
```

### Frontend Testing
Currently no test framework configured. Consider adding Vitest or Jest.

## Important Files

### Backend
- `app/Services/CookieManager.php` - Cookie authentication management
- `app/Http/Middleware/RefreshTokenMiddleware.php` - Automatic token refresh
- `app/Repositories/BaseRepository.php` - Base CRUD operations
- `app/Services/BaseService.php` - Base service layer logic
- `config/auth.php` - Authentication configuration
- `config/sanctum.php` - Sanctum configuration

### Frontend
- `src/composables/cruds.js` - CRUD operation helpers
- `src/http/index.js` - Axios configuration with auth interceptors
- `src/router/index.js` - Route definitions with auth guards
- `src/stores/auth.js` - Authentication state management
- `src/components/base/Crud.vue` - Base CRUD component
- `src/services/BaseService.js` - Base API service class

## Database

The application uses MySQL/PostgreSQL with Laravel migrations. Key tables:
- `tenants` - Multi-tenant organizations
- `users` - User accounts with tenant association
- `permissions` - Role-based permissions
- `user_addresses` - User address information
- `cities` and `states` - Location data

## Security Considerations

- Cookies are HttpOnly, Secure (HTTPS only in production), and SameSite=lax
- Automatic token refresh prevents session hijacking
- Tenant isolation via global scopes
- Permission-based access control
- CSRF protection via Sanctum