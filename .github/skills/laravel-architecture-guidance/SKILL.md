---
name: laravel-architecture-guidance
description: "Use when designing or refactoring the architecture of a small or medium Laravel module. Trigger for decisions about responsibility boundaries across controllers, Livewire, Filament, models, Form Requests, Actions, Policies, Jobs, Query Objects, and DTOs. Covers enhanced MVC defaults, extraction heuristics, anti-boilerplate rules, default folder choices, and verification steps. Do not use for routine CRUD tweaks or generic Laravel best-practice questions already covered by laravel-best-practices."
license: MIT
metadata:
  author: GitHub Copilot
---

# Laravel Architecture Guidance

Use this skill to choose the lightest architecture that keeps a Laravel codebase clean as it grows. It complements `laravel-best-practices` instead of replacing it. For exact framework APIs, verify with `search-docs`.

## Consistency First

Before introducing a new layer, inspect the surrounding module. Existing project conventions win over this document.

Do not add a second architectural style to a module unless the current style is clearly failing. Prefer one coherent approach over a theoretically cleaner but inconsistent rewrite.

## Default Architecture

For small and medium Laravel projects, default to enhanced MVC.

- Controllers, Livewire components, and Filament resources or pages are thin entrypoints.
- Form Requests or component-level validation guard the input boundary.
- Policies and gates handle authorization at the boundary.
- Models own persistence concerns, relationships, casts, scopes, and small cohesive domain helpers.
- Actions handle named business use cases.
- Jobs handle slow, external, heavy, or post-response work.
- Query Objects are reserved for complex or reusable reads.
- DTOs are reserved for clear boundaries, not everyday CRUD.

Start simple. Extract by complexity, not by aesthetics.

## Entry Point Rules

Treat these classes as delivery mechanisms, not as the home of business workflows:

- Controllers should validate, authorize, delegate, and return a response.
- Livewire components should manage UI state, validation, authorization, and delegation.
- Filament resources, pages, and actions should configure the UI and delegate business work.

If an entrypoint contains branching business rules, multiple writes, transactions, external calls, or logic reused elsewhere, move that work out.

## Decision Tree

### 1. Keep it inline only if all of these are true

- The logic is short and obvious.
- It serves one endpoint or one component only.
- It performs one simple read or write.
- It has no transaction.
- It has no external integration.
- It has no meaningful business name beyond the current method.

If any of those stop being true, extract.

### 2. Extract to an Action when the code represents a use case

Use an Action when the workflow:

- has a business name such as `CreateTicket`, `CloseQuiniela`, or `ProcessDeposit`,
- is reused by multiple entrypoints,
- needs a transaction,
- coordinates side effects,
- combines validation output with domain rules,
- or is difficult to test through an entrypoint alone.

Prefer one Action per use case. Name it with a verb and a clear outcome.

### 3. Keep query logic on the model until it becomes hard to read or reuse

Use model scopes, relationship methods, and builder composition for:

- simple filters,
- default ordering,
- small reusable constraints,
- and common relationship access.

Extract a Query Object only when the read path becomes:

- complex enough to hide intent,
- reused across multiple screens or APIs,
- reporting-oriented,
- or performance-sensitive with joins, subqueries, aggregates, or dynamic filters.

### 4. Introduce a DTO only at a real boundary

Prefer validated arrays and small value objects by default.

Create a DTO when you need:

- a typed payload crossing layers,
- a nested or structured request payload that is hard to reason about as an array,
- a stable contract for an external API,
- or a payload shared by multiple Actions, Jobs, or integrations.

Do not wrap every validated array in a DTO just to look architectural.

### 5. Dispatch a Job only when latency or resilience matters

Use a Job for work that is:

- slow,
- external,
- retryable,
- heavy enough to block the request,
- or safe to run after the response is returned.

Do not move synchronous business logic to a queue just to make the controller look smaller.

## Layer Defaults

### Models

Keep on the model:

- relationships,
- casts,
- local scopes,
- derived attributes,
- small domain predicates,
- and persistence-adjacent behavior that stays cohesive with the record.

Do not turn models into a dumping ground for unrelated workflows.

### Actions

Actions are the default extraction target for business workflows.

- Prefer specific classes such as `CreateUser`, `SettlePrizePool`, or `SyncPaymentStatus`.
- Inject dependencies explicitly.
- Return useful results instead of mutating hidden global state.
- Keep each Action centered on one use case.

### Services

Do not create generic service classes by default.

- Avoid names like `UserService`, `OrderService`, or `PaymentService` unless the class is clearly an orchestrator with a narrow purpose.
- If the class name does not reveal a concrete use case, the abstraction is probably too vague.

### Repositories

Do not introduce repositories by default in Laravel small or medium projects.

Eloquent is already a capable data access abstraction. Repositories are justified only when they add a real seam such as:

- switching data sources,
- isolating a legacy boundary,
- or encapsulating a genuinely complex persistence strategy.

If a repository mostly forwards calls to Eloquent, do not create it.

## Anti-Patterns

Avoid these unless you can defend them with a concrete constraint:

- repositories that mirror Eloquent method-for-method,
- generic `*Service` classes with unrelated responsibilities,
- one DTO per request with no real boundary benefit,
- Query Objects for trivial reads,
- Fat controllers, Livewire components, or Filament actions doing domain orchestration,
- heavy Clean Architecture or DDD layers for a module that is still simple,
- interfaces and ports without multiple realistic implementations,
- and refactors that only move lines around without reducing coupling or duplication.

## Default Folder Map

Use these as defaults when the project has no established convention:

- `app/Http/Controllers` for HTTP entrypoints
- `app/Livewire` for Livewire entrypoints
- `app/Filament` for Filament entrypoints
- `app/Actions` for business use cases
- `app/Queries` for complex read models and query builders
- `app/Data` for DTOs and structured payloads
- `app/Policies` for authorization policies
- `app/Jobs` for queued work
- `app/Models` for Eloquent models and persistence-adjacent behavior

If the repository already uses another coherent structure, follow that instead.

## Examples

### Controller flow

Use this shape for a non-trivial HTTP write:

`Controller -> Form Request -> Policy -> Action -> Job if needed -> Response`

Example: a checkout controller validates the request, authorizes the user, delegates to `CreateOrder`, then dispatches `SendOrderConfirmation` only if the email can be sent asynchronously.

### Livewire flow

Use this shape for interactive UI work:

`Livewire component -> validate -> authorize -> Action -> refresh UI state`

Keep filtering, persistence, and side effects out of the component once they stop being trivial.

### Filament flow

Use this shape for admin actions:

`Filament resource or page -> form schema and action config -> authorize -> Action`

If a table filter or metric query becomes complex or reused, move it to a Query Object instead of growing large anonymous closures.

### External integration flow

Use this shape when crossing a boundary:

`entrypoint -> validate boundary -> DTO -> Action -> Job or notification if slow`

Example: a payment webhook builds a typed payload, hands it to `ProcessPaymentWebhook`, then queues reconciliation work if the provider call is slow or retryable.

## Verification

Before finalizing an architectural change, check all of these:

1. Did the change reduce coupling, duplication, or branching, instead of only moving code?
2. Is validation still enforced at the boundary?
3. Is authorization still explicit at the boundary?
4. Did you keep entrypoints thinner after the refactor?
5. Did you avoid adding repositories, generic services, DTOs, or Query Objects without a clear need?
6. Are the new class names intention-revealing?
7. Are tests still centered on user-visible behavior and the affected use case?

## How to Apply

1. Identify the entrypoint: controller, Livewire, Filament, command, or job.
2. Keep the simplest possible shape that still reads clearly.
3. Extract only when the decision tree says the complexity justifies it.
4. Prefer Actions before inventing broader layers.
5. Recheck the module for consistency before introducing a new folder or abstraction.
