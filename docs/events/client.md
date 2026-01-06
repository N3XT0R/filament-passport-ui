## Client Events (OAuth Clients)

This document describes the domain events related to the **OAuth Client lifecycle**.
All events are dispatched explicitly via Use Cases and represent intentional,
traceable domain state changes.

---

## Event Overview (Derived from Use Cases)

| Event                     | Use Case              | Fired When                                                                                | Description                                                                                                                                                                              |
|---------------------------|-----------------------|-------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `OAuthClientCreatedEvent` | `CreateClientUseCase` | **After the OAuth client has been created and all scopes have been assigned**             | Fired once a new OAuth client is fully initialized. The client is persisted, scopes are granted, and the plain secret is available. This event signals that the client is ready for use. |
| `OauthClientDeletedEvent` | `DeleteClientUseCase` | **After all scope grants have been removed and the client has been successfully deleted** | Fired when an OAuth client has been completely removed from the system, including all related scope grants. This represents a final, irreversible state.                                 |
| `OAuthClientRevokedEvent` | `EditClientUseCase`   | **When the client is updated with `revoked = true`**                                      | Fired when an existing OAuth client is explicitly revoked during an update operation. The client still exists but is security-wise disabled and must no longer be used.                  |
| `OAuthClientUpdatedEvent` | `EditClientUseCase`   | **After an existing OAuth client has been successfully updated**                          | Fired after every successful client update, regardless of revocation. This event represents a completed and persisted state change of the client configuration.                          |

---

## Temporal Flow per Use Case

### `CreateClientUseCase`

1. Resolve owner
2. Create OAuth client
3. Assign scopes
4. **Dispatch `OAuthClientCreatedEvent`**

The event is intentionally dispatched at the very end to ensure the client is in a
fully consistent and usable state.

---

### `DeleteClientUseCase`

1. Remove all scope grants
2. Delete the client
3. **Dispatch `OauthClientDeletedEvent`**

The event represents a completed teardown. No permissions, references, or
security-relevant data remain.

---

### `EditClientUseCase`

1. Resolve owner and build DTO
2. Update client
3. **If `revoked === true`, dispatch `OAuthClientRevokedEvent`**
4. **Dispatch `OAuthClientUpdatedEvent`**
5. Upsert scope grants

Key aspects:

- `OAuthClientUpdatedEvent` is always fired after a successful update
- `OAuthClientRevokedEvent` represents a **specialized security state transition**
- Revocation does **not** replace the update event, but complements it

This allows listeners to:

- react generically to client changes, or
- react specifically to security-relevant revocation events

---

## Domain Classification

All client-related events are **domain-oriented application events**:

- Not technical lifecycle hooks
- Not tied to persistence observers
- Represent explicit business decisions
- Always reference:
    - the affected `Client`
    - the optional `actor`

Typical use cases include:

- Audit logging
- Security monitoring and enforcement
- Cache invalidation and UI synchronization
- Authorization graph recalculation
- Future event-driven or event-sourced extensions

---

## Architectural Note

Client lifecycle changes are **explicitly orchestrated at the Use Case level**.
This guarantees that every emitted event is intentional, explainable, and
structurally sound — without relying on implicit or “magical” behavior.
