## Action Events (Passport Scope Actions)

This document describes the domain events related to **OAuth Passport Scope Actions**.
All events are dispatched explicitly via Use Cases and represent intentional domain
state changes.

---

## Event Overview (Derived from Use Cases)

| Event                | Use Case              | Fired When                                             | Description                                                                                                                                                                                             |
|----------------------|-----------------------|--------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `ActionCreatedEvent` | `CreateActionUseCase` | **After a new action has been successfully persisted** | Fired when a new Passport Scope Action is created and confirmed to exist in persistence. The event signals that the action is now part of the domain model and can be referenced by scopes or policies. |
| `ActionDeletedEvent` | `DeleteActionUseCase` | **After the action has been successfully deleted**     | Fired once an existing Passport Scope Action has been removed. This represents a final removal of the action from the system.                                                                           |
| `ActionUpdatedEvent` | `EditActionUseCase`   | **After an existing action has been updated**          | Fired whenever an existing Passport Scope Action is modified. The event signals a completed domain update with the new state already persisted.                                                         |

---

## Temporal Flow per Use Case

### `CreateActionUseCase`

1. Action is created via `ActionService`
2. Persistence is verified (`$result->exists === true`)
3. **Dispatch `ActionCreatedEvent`**

The event is only fired if the action is actually persisted, ensuring that listeners
never react to transient or invalid state.

---

### `DeleteActionUseCase`

1. Action is deleted via `ActionService`
2. Delete operation returns `true`
3. **Dispatch `ActionDeletedEvent`**

The event represents a completed teardown of the action entity.

---

### `EditActionUseCase`

1. Action is updated via `ActionService`
2. Updated state is persisted
3. **Dispatch `ActionUpdatedEvent`**

The event is fired unconditionally after a successful update, representing a
completed domain mutation.

---

## Domain Classification

All Action-related events are **domain-oriented application events**:

- They are dispatched exclusively via Use Cases
- They represent explicit domain decisions
- They are not coupled to model lifecycle hooks or observers
- They always reference:
    - the affected `PassportScopeAction`
    - the optional `actor`

Typical use cases include:

- Audit logging
- Permission or policy recalculation
- Cache invalidation
- Synchronization with external authorization layers

---

## Architectural Note

Actions are treated as **first-class domain concepts**, not as technical metadata.
Therefore, their lifecycle is explicitly controlled and observable through
well-defined events.

This keeps the authorization model predictable, auditable, and structurally clear.
