# Event Dispatching Strategy

## Explicit Event Dispatching via Use Cases

All domain events in this module are **only dispatched when the corresponding Use Cases are executed**.

There are **no Eloquent observers, model hooks, or implicit lifecycle listeners** involved in triggering these events.

This is a **deliberate architectural decision**.

---

## Why Events Are Bound to Use Cases

Events represent **explicit domain state changes**, not technical side effects.

By dispatching events exclusively inside Use Cases:

- The **execution path is always explicit**
- The **business intent is clearly visible in code**
- The **order and conditions of event dispatching are deterministic**
- The domain logic remains **traceable and debuggable**

If an event is fired, it is guaranteed that:

- a specific Use Case was executed
- a defined business operation was performed
- all required invariants were satisfied beforehand

---

## Why Observers Were Intentionally Avoided

Model observers were **intentionally not used** for the following reasons:

- Observers introduce **implicit (“magical”) behavior**
- They obscure *when* and *why* domain logic is executed
- They couple domain behavior to persistence-layer side effects
- They make reasoning about execution flow harder, especially in security-relevant contexts

Instead of reacting to *technical* events (e.g. `created`, `updated`, `deleted`),
this system reacts to **intentional domain actions**.

---

## Structural Clarity over Magic

The chosen approach favors:

- **Structural transparency** over convenience
- **Explicit orchestration** over implicit side effects
- **Domain intent** over technical lifecycle coupling

As a result:

- Events are easy to reason about
- Business rules are centralized in Use Cases
- Refactoring and auditing are significantly simpler
- The system remains predictable under change

---

## Architectural Guideline

> **If a domain event is fired, it must be traceable to a Use Case.**
>
> There should be no scenario where an event is triggered without an explicit
> application-level decision.

This ensures that the system’s behavior is always:

- intentional
- explainable
- structurally sound

---

## Events

| Category                        | Description                                                                 |
|---------------------------------|-----------------------------------------------------------------------------|
| [Clients](events/client.md)     | Domain events related to OAuth client lifecycle and security state changes  |
| [Resources](events/resource.md) | Domain events related to OAuth Passport scope recources and their lifecycle |
| [Actions](events/action.md)     | Domain events related to OAuth Passport scope actions and their lifecycle   | 
