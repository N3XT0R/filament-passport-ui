## Resource Events (Passport Scope Resources)

This document describes the domain events related to **OAuth Passport Scope Resources**.
All events are dispatched explicitly via Use Cases and represent intentional domain
state changes.

---

## Event Overview (Derived from Use Cases)

| Event                  | Use Case                | Fired When                                             | Description                                                                                                                                                       |
|------------------------|-------------------------|--------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `ResourceCreatedEvent` | `CreateResourceUseCase` | **After a new resource has been successfully created** | Fired once a new Passport Scope Resource has been created and persisted. The resource is now available to be referenced by scopes and actions.                    |
| `ResourceDeletedEvent` | `DeleteResourceUseCase` | **After the resource has been successfully deleted**   | Fired when an existing Passport Scope Resource has been removed from the system. This represents a final removal of the resource entity.                          |
| `ResourceUpdatedEvent` | `EditResourceUseCase`   | **After an existing resource has been updated**        | Fired after a Passport Scope Resource has been modified and the updated state has been persisted. The event signals that the new resource state is authoritative. |

---

## Temporal Flow per Use Case

### `CreateResourceUseCase`

1. Resource is created via `ResourceService`
2. Persistence is completed
3. **Dispatch `ResourceCreatedEvent`**

The event is dispatched unconditionally after successful creation, ensuring that
listeners only react to valid, persisted resources.

---

### `DeleteResourceUseCase`

1. Resource is deleted via `ResourceService`
2. Delete operation returns `true`
3. **Dispatch `ResourceDeletedEvent`**

The event represents a completed teardown of the resource entity.

---

### `EditResourceUseCase`

1. Resource is updated via `ResourceService`
2. Updated state is persisted
3. **Dispatch `ResourceUpdatedEvent`**

The event signals a completed domain update and may be used to trigger downstream
synchronization or recalculation logic.

---

## Domain Classification

All Resource-related events are **domain-oriented application events**:

- Dispatched exclusively via Use Cases
- Represent explicit domain decisions
- Not coupled to model observers or persistence hooks
- Always reference:
    - the affected `PassportScopeResource`
    - the optional `actor`

Typical use cases include:

- Authorization model synchronization
- Cache invalidation
- Audit logging
- Policy or permission graph updates

---

## Architectural Note

Resources define the **structural surface of authorization** within the system.
By modeling their lifecycle explicitly and exposing it through events, the system
remains predictable, auditable, and easy to reason about.
