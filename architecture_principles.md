# Recruitment Agency Platform - Architecture Principles

## Overall Architecture

The platform follows a modular, domain-driven design with a clear separation of concerns:

1. **Modular Structure**
   - Organized by business domains (CRM, JobBoard, ATS, Marketing)
   - Each module has its own models, services, and UI components
   - Clear boundaries between modules with defined interfaces

2. **TALL Stack Implementation**
   - Tailwind CSS for utility-first styling
   - Alpine.js for lightweight client-side interactivity
   - Laravel for backend framework and routing
   - Livewire for reactive components without building an API

## Code Organization

### Domain-Driven Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Public/
│   │   └── Admin/
│   │       ├── Marketing/
│   │       ├── JobBoard/
│   │       ├── Crm/
│   │       └── Ats/
├── Models/
│   ├── Marketing/
│   ├── JobBoard/
│   ├── Crm/
│   └── Ats/
├── Services/
│   ├── Marketing/
│   ├── JobBoard/
│   ├── Crm/
│   └── Ats/
└── Livewire/
    ├── Public/
    └── Admin/
        ├── Marketing/
        ├── JobBoard/
        ├── Crm/
        └── Ats/
```

## Design Principles

1. **Single Responsibility Principle**
   - Each class has one reason to change
   - Services handle business logic
   - Models represent domain entities
   - Controllers/Livewire components manage HTTP/UI concerns

2. **Repository Pattern**
   - Abstract data access behind repository interfaces
   - Keep domain logic free from query details
   - Enable easier testing and data source changes

3. **Service Layer**
   - Implement business rules in service classes
   - Coordinate between multiple repositories/models
   - Handle cross-cutting concerns (logging, events)

4. **Value Objects**
   - Use immutable value objects for complex attributes
   - Implement domain-specific validation
   - Encapsulate related attributes (Address, PhoneNumber)

5. **Domain Events**
   - Use events for cross-boundary communication
   - Decouple components through event listeners
   - Enable extensibility through event subscribers

## Laravel Best Practices

1. **Eloquent Usage**
   - Prefer Eloquent over raw queries
   - Use scopes for common query patterns
   - Implement proper relationships between models

2. **Route Organization**
   - Group routes by domain context
   - Use route model binding
   - Implement middleware for authorization

3. **Validation**
   - Use form requests for validation logic
   - Implement domain-specific validation rules
   - Provide clear, localized error messages

4. **Testing Strategy**
   - Write feature tests for core workflows
   - Implement unit tests for services
   - Use factories for test data generation 