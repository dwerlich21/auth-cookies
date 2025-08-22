# Auth-Cookies System

[English](#english) | [Português](#português)

---

## English

### 📋 Overview

A fully integrated authentication system between Laravel 12 backend and Vue.js 3 frontend. This monorepo project implements a robust cookie-based authentication mechanism using Laravel Sanctum with dual-token strategy and automatic token refresh.

### 🔐 Authentication Features

- **Dual-Token System:**
  - `access_token` - Valid for 10 minutes
  - `refresh_token` - Valid for 24 hours
- **Automatic Token Refresh:** Custom middleware automatically refreshes tokens near expiration
- **Secure Cookie Storage:** HttpOnly, Secure, and SameSite protection
- **Multi-tenancy Support:** Built-in tenant isolation with global scopes

### ✨ Key Features

- **Complete User CRUD:** Full user management system with repository pattern
- **Automatic Email Notifications:** Observer pattern implementation for sending welcome emails to new users via queue jobs
- **Permission System:** Role-based access control with granular permissions
- **API Architecture:**
  - Repository Pattern for data access
  - Service Layer for business logic
  - Custom Exception Handling
  - Request Validation Classes

### 🚀 Quick Start

#### Backend Setup (Laravel)
```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

#### Frontend Setup (Vue 3)
```bash
cd front
yarn install
yarn dev
```

### 🛠️ Technology Stack

**Backend:**
- Laravel 12
- Laravel Sanctum
- MySQL/PostgreSQL
- PHP 8.2+

**Frontend:**
- Vue 3 (Composition API)
- Pinia (State Management)
- Vue Router
- Axios
- Bootstrap Vue 3
- SCSS

### 📁 Project Structure

```
auth-cookies/
├── api/                 # Laravel Backend
│   ├── app/
│   │   ├── Models/      # Eloquent Models with Tenant Scoping
│   │   ├── Services/    # Business Logic Layer
│   │   ├── Repositories/# Data Access Layer
│   │   ├── Observers/   # Model Observers (User email notifications)
│   │   └── Guards/      # Custom Authentication Guards
│   └── ...
├── front/               # Vue 3 Frontend
│   ├── src/
│   │   ├── components/  # Reusable Components
│   │   ├── composables/ # Vue Composables
│   │   ├── services/    # API Services
│   │   ├── stores/      # Pinia Stores
│   │   └── views/       # Page Components
│   └── ...
└── README.md
```

---

## Português

### 📋 Visão Geral

Um sistema de autenticação totalmente integrado entre o backend Laravel 12 e o frontend Vue.js 3. Este projeto monorepo implementa um mecanismo robusto de autenticação baseado em cookies usando Laravel Sanctum com estratégia de token duplo e atualização automática de tokens.

### 🔐 Recursos de Autenticação

- **Sistema de Token Duplo:**
  - `access_token` - Válido por 10 minutos
  - `refresh_token` - Válido por 24 horas
- **Atualização Automática de Token:** Middleware personalizado atualiza automaticamente os tokens próximos ao vencimento
- **Armazenamento Seguro em Cookies:** Proteção HttpOnly, Secure e SameSite
- **Suporte Multi-tenant:** Isolamento de tenant integrado com escopos globais

### ✨ Recursos Principais

- **CRUD Completo de Usuários:** Sistema completo de gerenciamento de usuários com padrão repository
- **Notificações Automáticas por Email:** Implementação do padrão Observer para envio de emails de boas-vindas para novos usuários via jobs em fila
- **Sistema de Permissões:** Controle de acesso baseado em papéis com permissões granulares
- **Arquitetura da API:**
  - Padrão Repository para acesso a dados
  - Camada de Serviço para lógica de negócios
  - Tratamento Personalizado de Exceções
  - Classes de Validação de Requisições

### 🚀 Início Rápido

#### Configuração do Backend (Laravel)
```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

#### Configuração do Frontend (Vue 3)
```bash
cd front
yarn install
yarn dev
```

### 🛠️ Stack Tecnológica

**Backend:**
- Laravel 12
- Laravel Sanctum
- MySQL/PostgreSQL
- PHP 8.2+

**Frontend:**
- Vue 3 (Composition API)
- Pinia (Gerenciamento de Estado)
- Vue Router
- Axios
- Bootstrap Vue 3
- SCSS

### 📁 Estrutura do Projeto

```
auth-cookies/
├── api/                 # Backend Laravel
│   ├── app/
│   │   ├── Models/      # Modelos Eloquent com Escopo de Tenant
│   │   ├── Services/    # Camada de Lógica de Negócios
│   │   ├── Repositories/# Camada de Acesso a Dados
│   │   ├── Observers/   # Observers de Modelo (notificações de email)
│   │   └── Guards/      # Guards de Autenticação Personalizados
│   └── ...
├── front/               # Frontend Vue 3
│   ├── src/
│   │   ├── components/  # Componentes Reutilizáveis
│   │   ├── composables/ # Composables do Vue
│   │   ├── services/    # Serviços de API
│   │   ├── stores/      # Stores Pinia
│   │   └── views/       # Componentes de Página
│   └── ...
└── README.md
```

---

## 📝 License / Licença

This project is open source and available under the [MIT License](LICENSE).  
Este projeto é open source e está disponível sob a [Licença MIT](LICENSE).
