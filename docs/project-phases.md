# TuQuiniela — Project Phases

> Auto-generated project plan based on `docs/project-description.md` and `docs/user-stories.md`.
> Each task includes acceptance criteria as automated Pest feature tests.

---

## Phase 1: Database Structure [Completed]

**Goal:** Create all models, migrations, factories, and seeders for the complete domain.

### Phase 1.1: Install Dependencies [Completed]

Install `spatie/laravel-medialibrary` for file/image uploads (deposit proof of payment, team logos, profile photos). Run pending `bavix/laravel-wallet` migrations.

**Steps:**
- Run `composer require spatie/laravel-medialibrary`
- Publish and run the medialibrary migration (`php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"`)
- Run `php artisan migrate` to apply wallet and medialibrary migrations

**Tests:**
- `tests/Feature/Database/DependenciesTest.php`
  - `it('has wallets table')` — assert wallets table exists
  - `it('has transactions table')` — assert wallet transactions table exists
  - `it('has media table')` — assert media table exists

---

### Phase 1.2: Update Users Table & Model [Completed]

Add `is_admin` boolean column (default `false`) to the `users` table. Add the `HasWallet` trait from `bavix/laravel-wallet` and `InteractsWithMedia` from spatie/medialibrary to the `User` model.

**Migration: `add_is_admin_to_users_table`**

| Column | Type | Notes |
|--------|------|-------|
| `is_admin` | `boolean` | Default `false` |

**Model changes:**
- Add `HasWallet` trait (from `Bavix\Wallet\Traits\HasWallet`)
- Add `HasWalletFloat` trait (from `Bavix\Wallet\Traits\HasWalletFloat`) — for decimal amounts
- Add `InteractsWithMedia` trait (from `Spatie\MediaLibrary\HasMedia`)
- Implement `HasMedia` interface
- Add `is_admin` to fillable and cast as `boolean`
- Register medialibrary collection `avatar` (single file)

**Factory update:**
- Add `is_admin` field (default `false`)
- Add `admin()` state that sets `is_admin` to `true`

**Seeder update:**
- Create an admin user: `admin@tuquiniela.com`, `is_admin: true`
- Create a few player users for testing

**Tests:**
- `tests/Feature/Database/UserModelTest.php`
  - `it('has is_admin attribute')` — create user, assert `is_admin` defaults to `false`
  - `it('can be created as admin')` — create user with `is_admin: true`, assert `is_admin` is `true`
  - `it('has a wallet')` — create user, assert `$user->wallet` is not null
  - `it('can deposit and withdraw from wallet')` — test basic wallet operations
  - `it('has an avatar media collection')` — attach media, assert collection exists

---

### Phase 1.3: Teams Table & Model [Completed]

**Migration: `create_teams_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `name` | `string` | Team name, e.g. "Real Madrid" |
| `short_name` | `string` | Nullable. Abbreviation, e.g. "RMA" |
| `timestamps` | | |

Team logo: stored via `spatie/medialibrary` (`logo` collection on Team model, single file).

**Model: `Team`**
- Implements `HasMedia` interface
- Traits: `InteractsWithMedia`
- Relations: `homeMatches()` hasMany `QuinielaMatch` (as `team_1_id`), `awayMatches()` hasMany `QuinielaMatch` (as `team_2_id`)
- Media collection: `logo` (single file)

**Factory: `TeamFactory`**
- Default: name (fake city + "FC"), short_name (3-letter abbreviation)

**Seeder: `TeamSeeder`**
- Create a set of common football teams for development/demo

**Tests:**
- `tests/Feature/Database/TeamModelTest.php`
  - `it('can create a team')` — factory create, assert DB has record
  - `it('has a logo media collection')` — attach media, assert collection exists
  - `it('has home matches relationship')` — create match as team_1, assert relationship
  - `it('has away matches relationship')` — create match as team_2, assert relationship

---

### Phase 1.4: Quinielas Table & Model [Completed]

**Migration: `create_quinielas_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `name` | `string` | Quiniela title |
| `prediction_type` | `string` | Comment: `result`, `score` — cast to PHP Enum `PredictionType` |
| `ticket_cost` | `decimal(10,2)` | Cost per ticket |
| `closing_at` | `datetime` | Deadline for ticket purchases/prediction edits |
| `status` | `string` | Comment: `draft`, `open`, `closed`, `completed` — cast to PHP Enum `QuinielaStatus` |
| `points_correct_result` | `integer` | Default `1`. Points in "result" mode, or partial points for correct result in "score" mode |
| `points_exact_score` | `integer` | Default `4`. Points for exact score match (score mode only) |
| `points_wrong` | `integer` | Default `-1`. Penalty for wrong prediction (score mode only) |
| `prize_type` | `string` | Comment: `fixed`, `percentage` — cast to PHP Enum `PrizeType` |
| `prize_pool_amount` | `decimal(10,2)` | Nullable. Total fixed prize pool |
| `prize_pool_percentage` | `decimal(5,2)` | Nullable. % of ticket sales as prize pool |
| `timestamps` | | |

**Enums (create in `app/Enums/`):**
- `PredictionType`: `Result`, `Score`
- `QuinielaStatus`: `Draft`, `Open`, `Closed`, `Completed`
- `PrizeType`: `Fixed`, `Percentage`

All enums implement `HasLabel`, `HasColor`, `HasIcon` interfaces for Filament.

**Model: `Quiniela`**
- Casts: `prediction_type` → `PredictionType`, `status` → `QuinielaStatus`, `prize_type` → `PrizeType`, `closing_at` → `datetime`, `ticket_cost` → `decimal:2`
- Relations: `matches()` hasMany `QuinielaMatch`, `prizePositions()` hasMany `PrizePosition`, `tickets()` hasMany `Ticket`
- Scopes: `open()`, `closed()`, `completed()`, `draft()`

**Factory: `QuinielaFactory`**
- Default: name (fake sentence), prediction_type `Result`, ticket_cost `1.00`, closing_at (future), status `Draft`, default point values, prize_type `Fixed`, prize_pool_amount `100.00`
- States: `open()`, `closed()`, `completed()`, `byScore()`, `percentagePrize()`

**Seeder: `QuinielaSeeder`**
- Create sample quinielas in different statuses with matches and prize positions

**Tests:**
- `tests/Feature/Database/QuinielaModelTest.php`
  - `it('can create a quiniela')` — factory create, assert DB has record
  - `it('casts prediction_type to enum')` — assert `prediction_type` is `PredictionType` instance
  - `it('casts status to enum')` — assert `status` is `QuinielaStatus` instance
  - `it('has matches relationship')` — create quiniela with matches, assert count
  - `it('has prize_positions relationship')` — create with prize positions, assert count
  - `it('has tickets relationship')` — create with tickets, assert count
  - `it('can scope by status')` — test `open()`, `closed()`, etc. scopes

---

### Phase 1.5: Quiniela Matches Table & Model [Completed]

**Migration: `create_quiniela_matches_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `quiniela_id` | `foreignId` | FK → `quinielas`, `cascadeOnDelete` |
| `team_1_id` | `foreignId` | FK → `teams`, `cascadeOnDelete` |
| `team_2_id` | `foreignId` | FK → `teams`, `cascadeOnDelete` |
| `match_date` | `datetime` | Scheduled match date/time |
| `sort_order` | `integer` | Default `0` |
| `team_1_score` | `integer` | Nullable. Actual final score |
| `team_2_score` | `integer` | Nullable. Actual final score |
| `timestamps` | | |

**Model: `QuinielaMatch`**
- Casts: `match_date` → `datetime`, `team_1_score` → `integer`, `team_2_score` → `integer`
- Relations: `quiniela()` belongsTo `Quiniela`, `team1()` belongsTo `Team`, `team2()` belongsTo `Team`, `predictions()` hasMany `Prediction`
- Accessors: `hasResult(): bool` — checks if both scores are not null, `result(): ?MatchResult` — returns `MatchResult::Team1`, `MatchResult::Team2`, or `MatchResult::Draw` based on scores

**Factory: `QuinielaMatchFactory`**
- Default: quiniela_id (factory), team_1_id (factory → Team), team_2_id (factory → Team), match_date (future), sort_order auto-increment
- States: `withResult()` — sets random scores

**Tests:**
- `tests/Feature/Database/QuinielaMatchModelTest.php`
  - `it('belongs to a quiniela')` — assert relationship
  - `it('belongs to team 1')` — assert `team1()` relationship
  - `it('belongs to team 2')` — assert `team2()` relationship
  - `it('can determine match result')` — test `result()` accessor for team1 win, team2 win, draw
  - `it('knows if result has been entered')` — test `hasResult()`
  - `it('has predictions relationship')` — assert relationship

---

### Phase 1.6: Prize Positions Table & Model [Completed]

**Migration: `create_prize_positions_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `quiniela_id` | `foreignId` | FK → `quinielas`, `cascadeOnDelete` |
| `position` | `integer` | 1st, 2nd, 3rd, etc. |
| `percentage` | `decimal(5,2)` | % of prize pool for this position |
| `timestamps` | | |

**Unique constraint:** `(quiniela_id, position)`

**Model: `PrizePosition`**
- Relations: `quiniela()` belongsTo `Quiniela`

**Factory: `PrizePositionFactory`**
- Default: quiniela_id (factory), position 1, percentage 100.00

**Tests:**
- `tests/Feature/Database/PrizePositionModelTest.php`
  - `it('belongs to a quiniela')` — assert relationship
  - `it('enforces unique position per quiniela')` — assert unique constraint

---

### Phase 1.7: Tickets Table & Model [Completed]

**Migration: `create_tickets_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `quiniela_id` | `foreignId` | FK → `quinielas`, `cascadeOnDelete` |
| `user_id` | `foreignId` | FK → `users`, `cascadeOnDelete` |
| `total_points` | `integer` | Nullable. Calculated after scoring |
| `prize_amount` | `decimal(10,2)` | Nullable. Prize won |
| `timestamps` | | |

**Model: `Ticket`**
- Relations: `quiniela()` belongsTo `Quiniela`, `user()` belongsTo `User`, `predictions()` hasMany `Prediction`

**Factory: `TicketFactory`**
- Default: quiniela_id (factory), user_id (factory)

**Tests:**
- `tests/Feature/Database/TicketModelTest.php`
  - `it('belongs to a quiniela')` — assert relationship
  - `it('belongs to a user')` — assert relationship
  - `it('has predictions relationship')` — assert relationship

---

### Phase 1.8: Predictions Table & Model [Completed]

**Migration: `create_predictions_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `ticket_id` | `foreignId` | FK → `tickets`, `cascadeOnDelete` |
| `quiniela_match_id` | `foreignId` | FK → `quiniela_matches`, `cascadeOnDelete` |
| `predicted_result` | `string` | Nullable. Comment: `team1`, `team2`, `draw` — cast to PHP Enum `MatchResult` |
| `predicted_team_1_score` | `integer` | Nullable. For score mode |
| `predicted_team_2_score` | `integer` | Nullable. For score mode |
| `points_earned` | `integer` | Nullable. Calculated after scoring |
| `timestamps` | | |

**Unique constraint:** `(ticket_id, quiniela_match_id)`

**Enum:**
- `MatchResult`: `Team1`, `Team2`, `Draw` (in `app/Enums/`)

**Model: `Prediction`**
- Casts: `predicted_result` → `MatchResult`
- Relations: `ticket()` belongsTo `Ticket`, `quinielaMatch()` belongsTo `QuinielaMatch`

**Factory: `PredictionFactory`**
- Default: ticket_id (factory), quiniela_match_id (factory), predicted_result (random MatchResult)
- States: `byScore()` — sets predicted_team_1_score and predicted_team_2_score

**Tests:**
- `tests/Feature/Database/PredictionModelTest.php`
  - `it('belongs to a ticket')` — assert relationship
  - `it('belongs to a quiniela match')` — assert relationship
  - `it('casts predicted_result to enum')` — assert cast works
  - `it('enforces unique prediction per ticket per match')` — assert unique constraint

---

### Phase 1.9: Payment Methods & Fields Tables & Models [Completed] [Completed]

**Migration: `create_payment_methods_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `name` | `string` | e.g. "Binance Pay" |
| `slug` | `string`, unique | e.g. "binance-pay" |
| `is_active` | `boolean` | Default `true` |
| `platform_details` | `json` | Nullable. Platform's own payment info for this method |
| `sort_order` | `integer` | Default `0` |
| `timestamps` | | |

**Migration: `create_payment_method_fields_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `payment_method_id` | `foreignId` | FK → `payment_methods`, `cascadeOnDelete` |
| `field_name` | `string` | e.g. `binance_id` |
| `field_label` | `string` | e.g. "Binance ID or Email" |
| `field_type` | `string` | e.g. `text`, `email`, `tel` |
| `is_required` | `boolean` | Default `true` |
| `sort_order` | `integer` | Default `0` |
| `timestamps` | | |

**Model: `PaymentMethod`**
- Relations: `fields()` hasMany `PaymentMethodField`, `deposits()` hasMany `Deposit`, `withdrawals()` hasMany `Withdrawal`
- Scopes: `active()`

**Model: `PaymentMethodField`**
- Relations: `paymentMethod()` belongsTo `PaymentMethod`

**Factory: `PaymentMethodFactory`**
- Default: name ("Binance Pay"), slug ("binance-pay"), is_active (true)

**Factory: `PaymentMethodFieldFactory`**
- Default: payment_method_id (factory), field_name, field_label, field_type "text", is_required true

**Seeder: `PaymentMethodSeeder`**
- Create "Binance Pay" method with fields: Binance ID or email

**Tests:**
- `tests/Feature/Database/PaymentMethodModelTest.php`
  - `it('has fields relationship')` — assert relationship
  - `it('can scope active methods')` — test active scope
  - `it('has deposits relationship')` — assert relationship
  - `it('has withdrawals relationship')` — assert relationship

---

### Phase 1.10: Deposits Table & Model [Completed]

**Migration: `create_deposits_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `user_id` | `foreignId` | FK → `users`, `cascadeOnDelete` |
| `payment_method_id` | `foreignId` | FK → `payment_methods`, `cascadeOnDelete` |
| `amount` | `decimal(10,2)` | |
| `status` | `string` | Comment: `pending`, `approved`, `rejected` — cast to PHP Enum `TransactionStatus` |
| `rejection_reason` | `text` | Nullable |
| `payment_details` | `json` | Nullable. User-submitted payment info |
| `approved_at` | `datetime` | Nullable |
| `rejected_at` | `datetime` | Nullable |
| `timestamps` | | |

Proof of payment image: stored via `spatie/medialibrary` (`proof` collection on Deposit model).

**Enum:**
- `TransactionStatus`: `Pending`, `Approved`, `Rejected` (in `app/Enums/`, shared by Deposits & Withdrawals)

**Model: `Deposit`**
- Implements `HasMedia` interface
- Traits: `InteractsWithMedia`
- Casts: `status` → `TransactionStatus`, `payment_details` → `array`, `amount` → `decimal:2`
- Relations: `user()` belongsTo `User`, `paymentMethod()` belongsTo `PaymentMethod`
- Media collection: `proof` (single file)

**Factory: `DepositFactory`**
- Default: user_id (factory), payment_method_id (factory), amount (random 5.00-100.00), status `Pending`
- States: `approved()`, `rejected()`

**Tests:**
- `tests/Feature/Database/DepositModelTest.php`
  - `it('belongs to a user')` — assert relationship
  - `it('belongs to a payment method')` — assert relationship
  - `it('casts status to enum')` — assert cast
  - `it('can store proof of payment media')` — attach media to proof collection

---

### Phase 1.11: Withdrawals Table & Model [Completed]

**Migration: `create_withdrawals_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `user_id` | `foreignId` | FK → `users`, `cascadeOnDelete` |
| `payment_method_id` | `foreignId` | FK → `payment_methods`, `cascadeOnDelete` |
| `amount` | `decimal(10,2)` | |
| `status` | `string` | Comment: `pending`, `approved`, `rejected` — cast to PHP Enum `TransactionStatus` |
| `rejection_reason` | `text` | Nullable |
| `payment_details` | `json` | Nullable |
| `approved_at` | `datetime` | Nullable |
| `rejected_at` | `datetime` | Nullable |
| `timestamps` | | |

**Model: `Withdrawal`**
- Casts: `status` → `TransactionStatus`, `payment_details` → `array`, `amount` → `decimal:2`
- Relations: `user()` belongsTo `User`, `paymentMethod()` belongsTo `PaymentMethod`

**Factory: `WithdrawalFactory`**
- Default: user_id (factory), payment_method_id (factory), amount (random 5.00-50.00), status `Pending`
- States: `approved()`, `rejected()`

**Tests:**
- `tests/Feature/Database/WithdrawalModelTest.php`
  - `it('belongs to a user')` — assert relationship
  - `it('belongs to a payment method')` — assert relationship
  - `it('casts status to enum')` — assert cast

---

### Phase 1.12: Settings Table & Model [Completed]

**Migration: `create_settings_table`**

| Column | Type | Notes |
|--------|------|-------|
| `id` | `bigIncrements` | PK |
| `key` | `string`, unique | Setting key, e.g. `min_deposit_amount` |
| `value` | `text` | Nullable. Setting value (stored as string, cast in code) |
| `timestamps` | | |

**Model: `Setting`**
- Static helper: `Setting::get(string $key, mixed $default = null): mixed`
- Static helper: `Setting::set(string $key, mixed $value): void`

**Seeder: `SettingSeeder`**
- `min_deposit_amount` → `5.00`
- `max_deposit_amount` → `10000.00`
- `min_withdrawal_amount` → `5.00`
- `max_withdrawal_amount` → `10000.00`

**Tests:**
- `tests/Feature/Database/SettingModelTest.php`
  - `it('can get a setting by key')` — assert `Setting::get()` works
  - `it('returns default when key not found')` — assert default fallback
  - `it('can set a setting value')` — assert `Setting::set()` persists

---

### Phase 1.13: User Model Relations & DatabaseSeeder [Completed]

Add remaining relations to the `User` model:
- `tickets()` hasMany `Ticket`
- `deposits()` hasMany `Deposit`
- `withdrawals()` hasMany `Withdrawal`

Update `DatabaseSeeder` to call all seeders in correct order:
1. `SettingSeeder`
2. `PaymentMethodSeeder`
3. `TeamSeeder`
4. Admin user + test player users
5. `QuinielaSeeder` (optional, for development)

**Tests:**
- `tests/Feature/Database/UserRelationsTest.php`
  - `it('has tickets relationship')` — assert relationship
  - `it('has deposits relationship')` — assert relationship
  - `it('has withdrawals relationship')` — assert relationship

---

## Phase 2: Admin Access & User Management

**Goal:** Restrict Filament panel to admin users, build user and team management resources.

**User Stories:** US-1.4 (partial — profile photo), US-6.3, US-11.1

### Phase 2.1: Admin Panel Authorization

Restrict the Filament admin panel so only users with `is_admin = true` can access it.

**Steps:**
- Add authorization gate or policy in `AdminPanelProvider` using `->authMiddleware()` or `->login()` with custom guard
- Override `canAccessPanel()` on the `User` model or use Filament's built-in `->authorization()` method
- Redirect non-admin users who try to access `/admin`

**Tests:**
- `tests/Feature/Admin/AdminAccessTest.php`
  - `it('allows admin users to access the admin panel')` — login as admin, assert 200 on `/admin`
  - `it('denies non-admin users access to the admin panel')` — login as player, assert redirect/403
  - `it('redirects guests to login')` — unauthenticated request, assert redirect to login

---

### Phase 2.2: User Management Filament Resource

Create a Filament resource for managing players (US-11.1).

**Resource: `UserResource`**
- **Table columns:** name, email, balance (from wallet), is_admin (toggle), email_verified_at, created_at
- **Table filters:** is_admin, email_verified
- **Table search:** name, email
- **Form fields:** name, email, password (create only), is_admin toggle
- **Actions:** View player details, deactivate/activate account

**Tests:**
- `tests/Feature/Admin/UserResourceTest.php`
  - `it('can render the users list page')` — assert page renders
  - `it('can list users in the table')` — assert users visible
  - `it('can search users by name')` — search, assert filtered results
  - `it('can search users by email')` — search, assert filtered results
  - `it('can create a new user')` — fill form, submit, assert DB
  - `it('can edit a user')` — edit form, submit, assert DB updated

---

### Phase 2.3: Admin Balance Management

Allow admin to view/adjust any player's balance and transaction history (US-6.3).

**Implementation:**
- Add a relation manager or custom page on `UserResource` showing wallet transactions
- Add a custom action on `UserResource` to manually adjust balance (with required reason/note)
- Balance adjustments recorded as wallet transactions with descriptive meta

**Tests:**
- `tests/Feature/Admin/AdminBalanceManagementTest.php`
  - `it('can view a player balance')` — assert balance displayed on user detail
  - `it('can view player transaction history')` — assert transactions listed
  - `it('can manually adjust player balance with a reason')` — perform adjustment, assert wallet transaction created with meta

---

### Phase 2.4: Team Management Filament Resource

Create a Filament resource for managing football teams.

**Resource: `TeamResource`**
- **Table columns:** logo (image), name, short_name, matches count, created_at
- **Table search:** name, short_name
- **Form fields:** name, short_name, logo (file upload via medialibrary)

**Tests:**
- `tests/Feature/Admin/TeamResourceTest.php`
  - `it('can render the teams list page')` — assert page renders
  - `it('can create a team')` — fill form, submit, assert DB
  - `it('can edit a team')` — edit, submit, assert DB updated
  - `it('can search teams by name')` — search, assert filtered results
  - `it('can upload a team logo')` — upload file, assert media attached

---

## Phase 3: Wallet & Balance Integration

**Goal:** Set up bavix/laravel-wallet, display balance to players.

**User Stories:** US-6.1, US-6.2

### Phase 3.1: Wallet Configuration

Configure the `bavix/laravel-wallet` package on the `User` model. Ensure every new user gets a default wallet upon creation.

**Steps:**
- Verify `HasWallet` and `HasWalletFloat` traits are on User model (done in Phase 1.2)
- Configure wallet to create automatically on first access
- Add wallet balance display to the player dashboard sidebar/header

**Tests:**
- `tests/Feature/Wallet/WalletSetupTest.php`
  - `it('creates a wallet for new users')` — create user, assert wallet exists
  - `it('starts with zero balance')` — create user, assert balance is 0

---

### Phase 3.2: Player Balance Display

Show current balance prominently on the player dashboard (US-6.1).

**Implementation:**
- Add balance to the dashboard layout (header or sidebar component)
- Display formatted currency amount

**Tests:**
- `tests/Feature/Player/BalanceDisplayTest.php`
  - `it('displays the player balance on the dashboard')` — login, visit dashboard, assert balance text visible

---

### Phase 3.3: Player Balance History Page

Create a page where players can view their transaction history (US-6.2). This is a **controller + Blade view** (not Livewire), unless filtering requires dynamic behavior.

**Implementation:**
- Route: `GET /balance/history`
- Controller: `BalanceController@history`
- View: list of wallet transactions with date, type (from meta), description (from meta), amount, running balance
- Filters: transaction type, date range (consider Livewire for dynamic filtering)

**Tests:**
- `tests/Feature/Player/BalanceHistoryTest.php`
  - `it('displays the balance history page')` — login, visit `/balance/history`, assert 200
  - `it('shows transaction entries')` — create wallet transactions, visit page, assert entries visible
  - `it('requires authentication')` — guest access, assert redirect

---

## Phase 4: Design & Style Guidelines

**Goal:** Define and document the visual identity, component patterns, and layout conventions for every player-facing view, **before** building any application UI.

> **CRITICAL.** This phase must be completed before any work on Phases 5–8 (player-facing views). All subsequent views must conform to the guidelines established here.

### Phase 4.1: Visual Identity & Theme Configuration

Define the application-wide look and feel.

**Deliverables:**
- Color palette definition (primary, secondary, accent, success, warning, danger, neutral) configured in Tailwind/Flux theme
- Typography choices (font family, heading sizes, body text, weight conventions)
- Border radius, shadow, and spacing tokens
- Dark mode strategy (support / not support, toggle behavior)
- Logo and branding placement on public pages, player dashboard, and auth pages

**Implementation:**
- Update `resources/css/app.css` with Tailwind theme customizations
- Configure Flux UI theme variables if applicable
- Create or update `resources/views/components/app-logo.blade.php` and `app-logo-icon.blade.php`

**Tests:**
- `tests/Feature/Design/ThemeConfigurationTest.php`
  - `it('loads the welcome page with correct branding')` — visit `/`, assert logo and brand elements present
  - `it('loads the dashboard with consistent theme')` — login, visit `/dashboard`, assert layout elements present

---

### Phase 4.2: Layout & Navigation Patterns

Define the page layout structure and navigation for the player area.

**Deliverables:**
- **Player dashboard layout:** sidebar (or topbar) navigation, main content area, balance widget placement
- **Navigation menu items** (ordered):
  - Dashboard
  - Quinielas (browse)
  - My Tickets
  - Balance (history)
  - Deposits (placeholder — future phase)
  - Withdrawals (placeholder — future phase)
  - Settings (profile, security, appearance)
- **Public pages layout:** welcome/landing, quiniela browse (for visitors)
- **Responsive behavior:** mobile sidebar collapse, stacked layouts for small screens
- **Footer** content and placement

**Implementation:**
- Update `resources/views/layouts/app.blade.php` with final navigation structure
- Update sidebar component with player menu items
- Ensure placeholder links for deposits/withdrawals point to a "coming soon" or are conditionally hidden

**Tests:**
- `tests/Feature/Design/NavigationTest.php`
  - `it('shows navigation items for authenticated players')` — login, assert nav items visible
  - `it('shows public navigation for guests')` — visit `/`, assert public nav items
  - `it('highlights the active navigation item')` — visit each route, assert active state

---

### Phase 4.3: Component & Page Design Conventions

Document the design patterns to be followed for all player-facing views.

**Deliverables:**
- **Card component** style: used for quiniela list items, ticket summaries, stats cards
- **Table component** style: used for balance history, ticket list — column alignment, row hover, pagination style
- **Form component** style: input fields, selects, radio groups (for predictions), buttons (primary, secondary, danger)
- **Badge/tag** style: for statuses (open, closed, completed, pending, approved, rejected)
- **Countdown timer** style: for quiniela closing date
- **Empty state** patterns: when lists have no items
- **Loading/spinner** pattern for Livewire interactions
- **Flash message / notification** style: success, error, info toasts

**Implementation:**
- Create reusable Blade components where applicable (e.g., `<x-status-badge :status="$status" />`)
- Document conventions in a `docs/design-guidelines.md` file for reference by future phases

**Tests:**
- `tests/Feature/Design/ComponentTest.php`
  - `it('renders the status badge component for each status')` — render component with different statuses, assert correct CSS classes
  - `it('renders empty state when no items')` — visit page with no data, assert empty state message

---

## Phase 5: Quiniela Management (Admin)

**Goal:** Full admin CRUD for quinielas, matches, prize configuration, status transitions, and result entry.

**User Stories:** US-2.1, US-2.2, US-2.3, US-2.4, US-2.5, US-5.1, US-11.2

### Phase 5.1: Quiniela Filament Resource

**Resource: `QuinielaResource`**
- **Table columns:** name, prediction_type, ticket_cost, tickets_sold (count), status, closing_at, prize_type
- **Table filters:** status, prediction_type, prize_type
- **Table search:** name
- **Form fields:** name, prediction_type select, ticket_cost, closing_at datetime picker, status select, point configuration fields (conditional on prediction_type), prize_type select, prize_pool_amount (visible if fixed), prize_pool_percentage (visible if percentage)
- **Actions:** Open, Close (status transitions)
- **Pages:** List, Create, Edit

**Tests:**
- `tests/Feature/Admin/QuinielaResourceTest.php`
  - `it('can render the quinielas list page')` — assert page renders
  - `it('can create a quiniela')` — fill form, submit, assert DB
  - `it('can edit a quiniela')` — edit, submit, assert DB updated
  - `it('can filter quinielas by status')` — apply filter, assert results
  - `it('can search quinielas by name')` — search, assert results
  - `it('can delete a draft quiniela')` — delete, assert removed

---

### Phase 5.2: Match Management (Relation Manager)

Add a relation manager on `QuinielaResource` for managing matches.

**Relation Manager: `QuinielaMatchesRelationManager`**
- **Table columns:** sort_order, team_1 (from relationship with logo), team_2 (from relationship with logo), match_date, team_1_score, team_2_score
- **Form fields:** team_1_id (select with search from teams table), team_2_id (select with search from teams table), match_date, sort_order
- **Actions:** Create, Edit, Delete, Reorder
- **Constraints:** Cannot add/edit/delete matches on completed quinielas

**Tests:**
- `tests/Feature/Admin/QuinielaMatchesRelationManagerTest.php`
  - `it('can list matches for a quiniela')` — assert matches displayed
  - `it('can add a match to a quiniela')` — create match, assert DB
  - `it('can edit a match')` — edit, assert DB updated
  - `it('can delete a match')` — delete, assert removed
  - `it('cannot modify matches on a completed quiniela')` — assert action blocked

---

### Phase 5.3: Prize Position Management (Relation Manager)

Add a relation manager on `QuinielaResource` for configuring prize positions.

**Relation Manager: `PrizePositionsRelationManager`**
- **Table columns:** position, percentage
- **Form fields:** position (integer), percentage (decimal)
- **Validation:** percentages for all positions must sum to 100%

**Tests:**
- `tests/Feature/Admin/PrizePositionsRelationManagerTest.php`
  - `it('can list prize positions for a quiniela')` — assert displayed
  - `it('can add a prize position')` — create, assert DB
  - `it('can edit a prize position')` — edit, assert DB
  - `it('can delete a prize position')` — delete, assert removed

---

### Phase 5.4: Quiniela Status Transitions

Implement status transition logic with validation (US-2.3, US-2.4).

**Implementation:**
- **Open action** on QuinielaResource: validates at least 1 match, prize config exists, closing_at is future → sets status to `open`
- **Close action**: sets status to `closed`, prevents new tickets/prediction edits
- **Auto-close**: Scheduled command `quiniela:close-expired` runs every minute, closes quinielas past `closing_at`

**Tests:**
- `tests/Feature/Admin/QuinielaStatusTransitionTest.php`
  - `it('can open a draft quiniela with matches and prizes')` — assert status changes to open
  - `it('cannot open a quiniela without matches')` — assert validation error
  - `it('cannot open a quiniela without prize positions')` — assert validation error
  - `it('cannot open a quiniela with past closing date')` — assert validation error
  - `it('can close an open quiniela')` — assert status changes to closed
- `tests/Feature/Commands/CloseExpiredQuinielasTest.php`
  - `it('auto-closes quinielas past closing date')` — run command, assert status changed
  - `it('does not close quinielas with future closing date')` — assert status unchanged

---

### Phase 5.5: Enter Match Results

Allow admin to enter final scores for matches in closed quinielas (US-2.5).

**Implementation:**
- Action or inline editing on `QuinielaMatchesRelationManager` for entering team_1_score and team_2_score
- Only available when quiniela status is `closed`
- Show progress indicator: "X of Y matches have results"

**Tests:**
- `tests/Feature/Admin/EnterMatchResultsTest.php`
  - `it('can enter match results for a closed quiniela')` — fill scores, assert DB updated
  - `it('cannot enter results for an open quiniela')` — assert action blocked
  - `it('can edit previously entered results')` — change scores, assert DB updated

---

## Phase 6: Tickets & Predictions (Player)

**Goal:** Player-facing quiniela browsing, ticket purchase, and prediction submission.

**User Stories:** US-3.1, US-3.2, US-3.3, US-3.4, US-3.5, US-3.6

### Phase 6.1: Browse Available Quinielas

List of open quinielas for players and visitors (US-3.1).

**Implementation:**
- Route: `GET /quinielas` → Controller `QuinielaController@index`
- View: list of open quinielas with name, prediction_type, ticket_cost, match count, closing_at (countdown), tickets sold
- Separate section/tab for completed quinielas (with results)
- Route: `GET /quinielas/{quiniela}` → Controller `QuinielaController@show`
- Detail view: quiniela info, matches list (with team logos), prize structure, buy ticket button

**Tests:**
- `tests/Feature/Player/BrowseQuinielasTest.php`
  - `it('displays open quinielas')` — create open quinielas, visit page, assert visible
  - `it('does not display draft quinielas')` — create draft, assert not visible
  - `it('displays completed quinielas in separate section')` — assert completed visible
  - `it('shows quiniela detail page')` — visit detail, assert info visible
  - `it('is accessible to guests')` — unauthenticated, assert 200

---

### Phase 6.2: Purchase Ticket

Allow players to buy a ticket for a quiniela (US-3.2). Requires wallet interaction → controller with POST action.

**Implementation:**
- Route: `POST /quinielas/{quiniela}/tickets` → Controller `TicketController@store`
- Validates: quiniela is open, player has sufficient balance
- Deducts ticket_cost from player wallet (recorded as wallet transaction with quiniela reference in meta)
- Creates Ticket record
- Redirects to prediction form

**Tests:**
- `tests/Feature/Player/PurchaseTicketTest.php`
  - `it('can purchase a ticket for an open quiniela')` — post, assert ticket created and balance deducted
  - `it('cannot purchase a ticket without sufficient balance')` — assert error message
  - `it('cannot purchase a ticket for a closed quiniela')` — assert 403 or redirect
  - `it('can purchase multiple tickets for the same quiniela')` — assert both tickets created
  - `it('requires authentication')` — guest, assert redirect
  - `it('records wallet transaction with quiniela reference')` — assert meta contains quiniela info

---

### Phase 6.3: Submit Predictions (By Result)

Prediction form for "result" mode quinielas (US-3.3). Interactive form → **Livewire component**.

**Implementation:**
- Route: `GET /tickets/{ticket}/predictions` → Livewire component
- Shows all matches with team logos; for each, player selects Team 1 / Draw / Team 2
- All matches must have a prediction to submit
- On submit: create/update Prediction records
- Authorization: only ticket owner, quiniela must be open

**Tests:**
- `tests/Feature/Player/SubmitPredictionsByResultTest.php`
  - `it('can render the prediction form')` — assert 200, matches visible
  - `it('can submit predictions for all matches')` — submit, assert predictions saved in DB
  - `it('requires predictions for all matches')` — submit partial, assert validation error
  - `it('can only predict on own tickets')` — other user's ticket, assert 403
  - `it('cannot submit predictions for closed quiniela')` — assert error

---

### Phase 6.4: Submit Predictions (By Score)

Prediction form for "score" mode quinielas (US-3.4). Interactive form → **Livewire component**.

**Implementation:**
- Route: same as 6.3 (`/tickets/{ticket}/predictions`), form adapts based on quiniela prediction_type
- For each match: player enters team_1_score and team_2_score (non-negative integers)
- Same validation and authorization as 6.3

**Tests:**
- `tests/Feature/Player/SubmitPredictionsByScoreTest.php`
  - `it('can render the score prediction form')` — assert score inputs visible
  - `it('can submit score predictions for all matches')` — submit, assert predictions saved
  - `it('validates scores are non-negative integers')` — submit negative, assert validation error
  - `it('requires predictions for all matches')` — submit partial, assert validation error

---

### Phase 6.5: Edit Predictions

Allow players to edit predictions before quiniela closes (US-3.5).

**Implementation:**
- Same route/component as 6.3/6.4 — if predictions already exist, form is pre-filled
- Save button updates existing predictions
- After closing_at, form is read-only with clear indication

**Tests:**
- `tests/Feature/Player/EditPredictionsTest.php`
  - `it('can edit existing predictions while quiniela is open')` — change prediction, assert DB updated
  - `it('cannot edit predictions after quiniela closes')` — assert form is locked / submit rejected
  - `it('pre-fills existing predictions')` — visit form, assert values pre-filled

---

### Phase 6.6: View My Tickets

Player ticket history across all quinielas (US-3.6).

**Implementation:**
- Route: `GET /tickets` → Controller `TicketController@index`
- View: tickets grouped by quiniela status (active/closed/completed), showing quiniela name, prediction_type, cost, points earned, prize won
- Route: `GET /tickets/{ticket}` → Controller `TicketController@show`
- Detail view: full prediction list vs actual results (for closed/completed), with team logos

**Tests:**
- `tests/Feature/Player/ViewTicketsTest.php`
  - `it('displays the tickets list page')` — assert 200
  - `it('shows only the authenticated player tickets')` — assert correct tickets
  - `it('groups tickets by quiniela status')` — assert grouping
  - `it('shows ticket detail with predictions')` — visit detail, assert predictions visible
  - `it('requires authentication')` — guest, assert redirect

---

## Phase 7: Scoring & Prize Distribution

**Goal:** Implement point calculation and prize awarding logic.

**User Stories:** US-4.1, US-4.2, US-5.2, US-2.6

### Phase 7.1: Calculate Points (By Result)

Scoring engine for "result" mode quinielas (US-4.1).

**Implementation:**
- Service class: `App\Services\ScoringService`
- Method: `scoreTicket(Ticket $ticket): void`
  - For each prediction: compare `predicted_result` with match `result()`, award `points_correct_result` or 0
  - Update `predictions.points_earned` and `tickets.total_points`
- Triggered when match results are entered (via event or manually)

**Tests:**
- `tests/Feature/Scoring/ScoreByResultTest.php`
  - `it('awards points for correct result predictions')` — predict correctly, score, assert points
  - `it('awards zero points for wrong predictions')` — predict wrong, score, assert 0
  - `it('calculates total points across all matches')` — multiple matches, assert total
  - `it('uses configurable point values')` — custom points, assert correct amounts

---

### Phase 7.2: Calculate Points (By Score)

Scoring engine for "score" mode quinielas (US-4.2).

**Implementation:**
- Extend `ScoringService` with score-mode logic
  - Exact score match: `points_exact_score`
  - Correct result but wrong score: `points_correct_result`
  - Wrong result: `points_wrong`
- Update `predictions.points_earned` and `tickets.total_points`
- Total can be negative

**Tests:**
- `tests/Feature/Scoring/ScoreByScoreTest.php`
  - `it('awards maximum points for exact score match')` — assert `points_exact_score` awarded
  - `it('awards partial points for correct result with wrong score')` — assert `points_correct_result` awarded
  - `it('applies penalty for wrong prediction')` — assert `points_wrong` applied
  - `it('allows negative total points')` — all wrong, assert negative total
  - `it('calculates total points across all matches')` — mixed results, assert correct total
  - `it('uses configurable point values')` — custom points, assert correct amounts

---

### Phase 7.3: Complete Quiniela & Distribute Prizes

Finalize quiniela, calculate rankings, distribute prizes (US-2.6, US-5.2).

**Implementation:**
- Service class: `App\Services\PrizeDistributionService`
- Method: `distribute(Quiniela $quiniela): void`
  - Validates all match results entered
  - Scores all tickets (calls `ScoringService`)
  - Ranks tickets by total_points (highest first)
  - Handles ties: tied positions split combined prize equally
  - Calculates prize amounts based on prize_type (fixed or percentage of total sales)
  - Credits winners' wallets with prize amounts (recorded as wallet transactions with meta)
  - Updates `tickets.prize_amount` for winners
  - Sets quiniela status to `completed`
- Admin action on QuinielaResource: "Complete & Distribute Prizes" with confirmation and preview of standings

**Tests:**
- `tests/Feature/Scoring/PrizeDistributionTest.php`
  - `it('distributes fixed prize pool correctly')` — assert correct amounts credited
  - `it('distributes percentage-based prize pool correctly')` — calculate from ticket sales, assert amounts
  - `it('handles ties by splitting combined prize equally')` — two tied for 1st, assert split
  - `it('credits winners wallets')` — assert wallet transactions exist
  - `it('records prize amounts on tickets')` — assert `prize_amount` set
  - `it('sets quiniela status to completed')` — assert status changed
  - `it('fails if not all match results are entered')` — assert exception/error
  - `it('handles single winner correctly')` — assert full prize to 1st place
  - `it('handles quiniela with no tickets gracefully')` — assert no errors

---

## Phase 8: Leaderboard & Results

**Goal:** Public leaderboards and detailed result views for players.

**User Stories:** US-9.1, US-9.2

### Phase 8.1: Quiniela Leaderboard

Show rankings for closed/completed quinielas (US-9.1).

**Implementation:**
- Route: `GET /quinielas/{quiniela}/leaderboard` → Controller `QuinielaController@leaderboard`
- View: ranked list of tickets (player name, total points, prize won)
- Highlight current player's position
- Tied positions show same rank
- Only available for closed/completed quinielas
- Hidden for open quinielas

**Tests:**
- `tests/Feature/Player/LeaderboardTest.php`
  - `it('displays the leaderboard for a closed quiniela')` — assert page renders with rankings
  - `it('highlights the logged-in player position')` — assert own entry highlighted
  - `it('handles tied positions correctly')` — assert same rank for ties
  - `it('returns 404 or redirect for open quinielas')` — assert leaderboard not available
  - `it('shows prize amounts for completed quinielas')` — assert prizes visible

---

### Phase 8.2: Detailed Ticket Results

Show per-match prediction breakdown vs actual results (US-9.2).

**Implementation:**
- Part of the ticket detail page (`GET /tickets/{ticket}`)
- For each match: team logos, player's prediction, actual result, points earned, visual indicator (correct ✅ / partial 🟡 / wrong ❌)
- Summary: total points, prize won (if any)
- Only visible after match results are entered

**Tests:**
- `tests/Feature/Player/DetailedTicketResultsTest.php`
  - `it('shows prediction vs actual result per match')` — assert both displayed
  - `it('shows points earned per match')` — assert points visible
  - `it('shows visual indicators for prediction accuracy')` — assert correct indicators
  - `it('shows total points and prize summary')` — assert summary visible
  - `it('hides results before match results are entered')` — assert results not shown

---

## Phase 9: Admin Dashboard & Settings

**Goal:** Financial overview, quiniela management dashboard, platform settings.

**User Stories:** US-11.2, US-11.3, US-11.4

### Phase 9.1: Admin Dashboard Widgets

Add Filament dashboard widgets for key metrics (US-11.3).

**Implementation:**
- **Stats widget:** total deposits, total withdrawals, total ticket sales, total prizes distributed, platform revenue
- **Stats per period:** this month / all time toggle
- **Pending actions widget:** pending deposits count, pending withdrawals count
- **Recent quinielas widget:** latest quinielas with status

**Tests:**
- `tests/Feature/Admin/AdminDashboardTest.php`
  - `it('displays the admin dashboard with widgets')` — assert dashboard renders
  - `it('shows financial stats')` — create data, assert stats visible
  - `it('shows pending action counts')` — create pending deposits/withdrawals, assert counts

---

### Phase 9.2: Quiniela Detail Management

Enhanced quiniela oversight in Filament (US-11.2).

**Implementation:**
- Quiniela detail page with tabs: Info, Matches, Tickets/Leaderboard, Prizes
- Tickets tab shows all tickets purchased with player info and points
- Revenue per quiniela (tickets sold × ticket_cost)

**Tests:**
- `tests/Feature/Admin/QuinielaDetailManagementTest.php`
  - `it('shows quiniela detail with all tabs')` — assert tabs render
  - `it('lists tickets for a quiniela')` — assert tickets visible
  - `it('shows revenue for a quiniela')` — assert revenue calculated

---

### Phase 9.3: Platform Settings Management

Admin CRUD for global platform settings (US-11.4).

**Implementation:**
- Filament page: `ManageSettings` (custom page, not resource)
- Form fields for: min/max deposit, min/max withdrawal, default point values per prediction type, notification channel toggles
- Saves to `settings` table via `Setting::set()`

**Tests:**
- `tests/Feature/Admin/PlatformSettingsTest.php`
  - `it('can render the settings page')` — assert page renders
  - `it('can update platform settings')` — change values, assert DB updated
  - `it('only accessible by admins')` — non-admin, assert denied

---

## Phase 10: Notifications

**Goal:** Notify players and admins of important events.

**User Stories:** US-10.1, US-10.2, US-10.3

### Phase 10.1: Email Notifications

Send queued email notifications for key events (US-10.1).

**Implementation:**
- Notification classes (Laravel notifications, queued):
  - `DepositApprovedNotification`
  - `DepositRejectedNotification`
  - `WithdrawalApprovedNotification`
  - `WithdrawalRejectedNotification`
  - `QuinielaResultsPublishedNotification`
  - `PrizeWonNotification`
- Triggered from relevant service methods/actions

**Tests:**
- `tests/Feature/Notifications/EmailNotificationTest.php`
  - `it('sends email when deposit is approved')` — approve deposit, assert notification sent
  - `it('sends email when deposit is rejected')` — reject deposit, assert notification sent
  - `it('sends email when withdrawal is approved')` — assert notification sent
  - `it('sends email when withdrawal is rejected')` — assert notification sent
  - `it('sends email when quiniela results are published')` — complete quiniela, assert notification
  - `it('sends email when prize is won')` — distribute prizes, assert notification
  - `it('notifications are queued')` — assert ShouldQueue interface

---

### Phase 10.2: Telegram/WhatsApp Notifications

Extend notification channels for messaging platforms (US-10.2).

**Implementation:**
- Add Telegram notification channel (e.g., via `laravel-notification-channels/telegram`)
- Player settings: opt-in for Telegram/WhatsApp, store chat IDs
- Same notification classes from Phase 10.1, add `toTelegram()` / `toWhatsApp()` methods
- Player can enable/disable messaging notifications independently

**Tests:**
- `tests/Feature/Notifications/MessagingNotificationTest.php`
  - `it('sends telegram notification when opted in')` — mock channel, assert sent
  - `it('does not send telegram notification when opted out')` — assert not sent
  - `it('player can configure notification preferences')` — update settings, assert saved

---

### Phase 10.3: Admin Notifications

Notify admins of actions requiring attention (US-10.3).

**Implementation:**
- Filament database notification channel
- `NewDepositRequestNotification` — sent when player submits deposit
- `NewWithdrawalRequestNotification` — sent when player submits withdrawal
- Show unread count in Filament panel header

**Tests:**
- `tests/Feature/Notifications/AdminNotificationTest.php`
  - `it('notifies admin of new deposit requests')` — submit deposit, assert admin notification created
  - `it('notifies admin of new withdrawal requests')` — submit withdrawal, assert admin notification created
  - `it('shows notification count in admin panel')` — assert unread count visible

---

## Phase 11: Payment Methods Management

**Goal:** Admin CRUD for payment methods and their dynamic fields.

**User Stories:** US-7.3, US-11.4 (partial)

### Phase 11.1: Payment Method Filament Resource

**Resource: `PaymentMethodResource`**
- **Table columns:** name, slug, is_active (toggle), fields count, sort_order
- **Form fields:** name, slug (auto-generated from name), is_active, platform_details (key-value editor), sort_order
- **Relation manager:** `PaymentMethodFieldsRelationManager` for managing fields per method
- **Actions:** Enable/disable toggle

**Tests:**
- `tests/Feature/Admin/PaymentMethodResourceTest.php`
  - `it('can render the payment methods list page')` — assert page renders
  - `it('can create a payment method')` — fill form, submit, assert DB
  - `it('can edit a payment method')` — edit, submit, assert DB updated
  - `it('can toggle payment method active status')` — toggle, assert status changed
  - `it('can manage payment method fields')` — add field via relation manager, assert DB

---

## Phase 12: Deposits

**Goal:** Player deposit request flow and admin review.

**User Stories:** US-7.1, US-7.2

### Phase 12.1: Player Deposit Request

Create a page where players submit deposit requests. This requires dynamic behavior (payment method selection changes visible fields), so it should be a **Livewire component**.

**Implementation:**
- Route: `GET /deposits/create` → Livewire component
- Payment method selector (shows active methods only)
- Dynamic fields based on selected payment method
- Amount input (validated against min/max from settings)
- Proof of payment file upload
- Platform payment details shown (where to send payment)
- On submit: create Deposit record, attach proof media, show confirmation

**Tests:**
- `tests/Feature/Player/DepositRequestTest.php`
  - `it('can render the deposit request page')` — assert 200
  - `it('shows active payment methods')` — assert payment methods visible
  - `it('can submit a deposit request')` — fill form, submit, assert deposit created in DB with status pending
  - `it('validates required payment method fields')` — submit without required fields, assert validation errors
  - `it('validates amount against min/max settings')` — submit out-of-range amount, assert validation error
  - `it('requires authentication')` — guest, assert redirect

### Phase 12.2: Player Deposit History

Players can view their deposit requests and statuses.

**Implementation:**
- Route: `GET /deposits` → Controller `DepositController@index`
- View: list of deposits with date, amount, payment method, status, rejection reason (if any)

**Tests:**
- `tests/Feature/Player/DepositHistoryTest.php`
  - `it('displays the deposits list page')` — assert 200
  - `it('shows only the authenticated player deposits')` — assert correct deposits listed
  - `it('requires authentication')` — guest, assert redirect

### Phase 12.3: Admin Deposit Review (Filament)

**Resource: `DepositResource`**
- **Table columns:** player name, player email, amount, payment method, status, payment_details, created_at
- **Table filters:** status (pending/approved/rejected), payment method
- **Table default sort:** pending first, then by created_at desc
- **Actions:**
  - **Approve**: credits player wallet, sets status to `approved`, sets `approved_at`, records wallet transaction with meta
  - **Reject**: sets status to `rejected`, sets `rejected_at`, requires rejection_reason
- **Detail view:** shows proof of payment image, payment details, player info

**Tests:**
- `tests/Feature/Admin/DepositResourceTest.php`
  - `it('can render the deposits list page')` — assert page renders
  - `it('can list pending deposits')` — assert deposits visible
  - `it('can approve a deposit')` — approve action, assert status changed and player wallet credited
  - `it('can reject a deposit with reason')` — reject action, assert status changed and reason stored
  - `it('cannot approve an already processed deposit')` — assert action not available

---

## Phase 13: Withdrawals

**Goal:** Player withdrawal request flow and admin review.

**User Stories:** US-8.1, US-8.2

### Phase 13.1: Player Withdrawal Request

Create a page for withdrawal requests. Dynamic fields needed → **Livewire component**.

**Implementation:**
- Route: `GET /withdrawals/create` → Livewire component
- Payment method selector (active methods)
- Dynamic fields based on selected method
- Amount input (validated against min/max settings and player balance)
- On submit: deduct amount from player wallet (hold), create Withdrawal record with status `pending`

**Tests:**
- `tests/Feature/Player/WithdrawalRequestTest.php`
  - `it('can render the withdrawal request page')` — assert 200
  - `it('can submit a withdrawal request')` — fill form, submit, assert withdrawal created and wallet debited
  - `it('prevents withdrawal exceeding balance')` — submit excess amount, assert validation error
  - `it('validates amount against min/max settings')` — assert validation error
  - `it('requires authentication')` — guest, assert redirect

### Phase 13.2: Player Withdrawal History

**Implementation:**
- Route: `GET /withdrawals` → Controller `WithdrawalController@index`
- View: list of withdrawals with date, amount, payment method, status, rejection reason

**Tests:**
- `tests/Feature/Player/WithdrawalHistoryTest.php`
  - `it('displays the withdrawals list page')` — assert 200
  - `it('shows only the authenticated player withdrawals')` — assert correct withdrawals listed

### Phase 13.3: Admin Withdrawal Review (Filament)

**Resource: `WithdrawalResource`**
- **Table columns:** player name, player email, amount, payment method, status, payment_details, created_at
- **Table filters:** status, payment method
- **Actions:**
  - **Approve**: confirms the wallet withdrawal, sets status to `approved`, sets `approved_at`
  - **Reject**: refunds the held amount to player wallet, sets status to `rejected`, requires reason
- **Detail view:** payment details, player balance info

**Tests:**
- `tests/Feature/Admin/WithdrawalResourceTest.php`
  - `it('can render the withdrawals list page')` — assert page renders
  - `it('can list pending withdrawals')` — assert visible
  - `it('can approve a withdrawal')` — approve, assert status changed
  - `it('can reject a withdrawal and refund balance')` — reject, assert status changed and wallet refunded
  - `it('cannot process an already processed withdrawal')` — assert action not available

---

## Phase 14: Nice-to-Have Features

**Goal:** Additional features to enhance the user experience.

**User Stories:** US-12.1, US-12.2, US-12.3

### Phase 14.1: Share Quiniela

Allow players to share quiniela links (US-12.1).

**Implementation:**
- Share button on quiniela detail page generating a shareable URL
- Signed URL or simple slug-based URL
- Unregistered users redirected to register, then back to quiniela

**Tests:**
- `tests/Feature/Player/ShareQuinielaTest.php`
  - `it('generates a shareable link for a quiniela')` — assert link generated
  - `it('shareable link resolves to quiniela detail')` — visit link, assert quiniela page shown
  - `it('redirects unregistered users to registration')` — guest visits private quiniela link, assert redirect

---

### Phase 14.2: Live Score Updates

Real-time leaderboard/result updates as admin enters scores (US-12.2).

**Implementation:**
- Use Livewire polling or Laravel Echo + broadcasting
- Leaderboard and ticket result pages update without page refresh
- Broadcast events when match results are entered

**Tests:**
- `tests/Feature/Player/LiveScoreUpdatesTest.php`
  - `it('updates leaderboard when match results change')` — enter result, assert leaderboard reflects new scores (can test via Livewire component test)

---

### Phase 14.3: Player Statistics

Overall player statistics across all quinielas (US-12.3).

**Implementation:**
- Route: `GET /profile/statistics` → Controller `ProfileController@statistics`
- View: total quinielas participated, total tickets, win rate, accuracy %, total prizes won, prediction accuracy breakdown

**Tests:**
- `tests/Feature/Player/PlayerStatisticsTest.php`
  - `it('displays player statistics page')` — assert 200, stats visible
  - `it('calculates correct accuracy percentage')` — create data, assert correct percentage
  - `it('shows total prizes won')` — assert total matches expected
  - `it('requires authentication')` — guest, assert redirect

---

## Status Summary

| Phase | Description | Status |
|-------|-------------|--------|
| **Phase 1** | Database Structure | ✅ Completed |
| **Phase 1.1** | Install Dependencies | ✅ Completed |
| **Phase 1.2** | Update Users Table & Model | ✅ Completed |
| **Phase 1.3** | Teams Table & Model | ✅ Completed |
| **Phase 1.4** | Quinielas Table & Model | ✅ Completed |
| **Phase 1.5** | Quiniela Matches Table & Model | ✅ Completed |
| **Phase 1.6** | Prize Positions Table & Model | ✅ Completed |
| **Phase 1.7** | Tickets Table & Model | ✅ Completed |
| **Phase 1.8** | Predictions Table & Model | ✅ Completed |
| **Phase 1.9** | Payment Methods & Fields Tables | ✅ Completed |
| **Phase 1.10** | Deposits Table & Model | ✅ Completed |
| **Phase 1.11** | Withdrawals Table & Model | ✅ Completed |
| **Phase 1.12** | Settings Table & Model | ✅ Completed |
| **Phase 1.13** | User Model Relations & Seeder | ✅ Completed |
| **Phase 2** | Admin Access & User Management | ⬜ Not Started |
| **Phase 2.1** | Admin Panel Authorization | ⬜ Not Started |
| **Phase 2.2** | User Management Filament Resource | ⬜ Not Started |
| **Phase 2.3** | Admin Balance Management | ⬜ Not Started |
| **Phase 2.4** | Team Management Filament Resource | ⬜ Not Started |
| **Phase 3** | Wallet & Balance Integration | ⬜ Not Started |
| **Phase 3.1** | Wallet Configuration | ⬜ Not Started |
| **Phase 3.2** | Player Balance Display | ⬜ Not Started |
| **Phase 3.3** | Player Balance History Page | ⬜ Not Started |
| **Phase 4** | Design & Style Guidelines | ⬜ Not Started |
| **Phase 4.1** | Visual Identity & Theme | ⬜ Not Started |
| **Phase 4.2** | Layout & Navigation Patterns | ⬜ Not Started |
| **Phase 4.3** | Component & Page Design Conventions | ⬜ Not Started |
| **Phase 5** | Quiniela Management (Admin) | ⬜ Not Started |
| **Phase 5.1** | Quiniela Filament Resource | ⬜ Not Started |
| **Phase 5.2** | Match Management | ⬜ Not Started |
| **Phase 5.3** | Prize Position Management | ⬜ Not Started |
| **Phase 5.4** | Quiniela Status Transitions | ⬜ Not Started |
| **Phase 5.5** | Enter Match Results | ⬜ Not Started |
| **Phase 6** | Tickets & Predictions (Player) | ⬜ Not Started |
| **Phase 6.1** | Browse Available Quinielas | ⬜ Not Started |
| **Phase 6.2** | Purchase Ticket | ⬜ Not Started |
| **Phase 6.3** | Submit Predictions (By Result) | ⬜ Not Started |
| **Phase 6.4** | Submit Predictions (By Score) | ⬜ Not Started |
| **Phase 6.5** | Edit Predictions | ⬜ Not Started |
| **Phase 6.6** | View My Tickets | ⬜ Not Started |
| **Phase 7** | Scoring & Prize Distribution | ⬜ Not Started |
| **Phase 7.1** | Calculate Points (By Result) | ⬜ Not Started |
| **Phase 7.2** | Calculate Points (By Score) | ⬜ Not Started |
| **Phase 7.3** | Complete Quiniela & Distribute | ⬜ Not Started |
| **Phase 8** | Leaderboard & Results | ⬜ Not Started |
| **Phase 8.1** | Quiniela Leaderboard | ⬜ Not Started |
| **Phase 8.2** | Detailed Ticket Results | ⬜ Not Started |
| **Phase 9** | Admin Dashboard & Settings | ⬜ Not Started |
| **Phase 9.1** | Admin Dashboard Widgets | ⬜ Not Started |
| **Phase 9.2** | Quiniela Detail Management | ⬜ Not Started |
| **Phase 9.3** | Platform Settings Management | ⬜ Not Started |
| **Phase 10** | Notifications | ⬜ Not Started |
| **Phase 10.1** | Email Notifications | ⬜ Not Started |
| **Phase 10.2** | Telegram/WhatsApp Notifications | ⬜ Not Started |
| **Phase 10.3** | Admin Notifications | ⬜ Not Started |
| **Phase 11** | Payment Methods Management | ⬜ Not Started |
| **Phase 11.1** | Payment Method Filament Resource | ⬜ Not Started |
| **Phase 12** | Deposits | ⬜ Not Started |
| **Phase 12.1** | Player Deposit Request | ⬜ Not Started |
| **Phase 12.2** | Player Deposit History | ⬜ Not Started |
| **Phase 12.3** | Admin Deposit Review | ⬜ Not Started |
| **Phase 13** | Withdrawals | ⬜ Not Started |
| **Phase 13.1** | Player Withdrawal Request | ⬜ Not Started |
| **Phase 13.2** | Player Withdrawal History | ⬜ Not Started |
| **Phase 13.3** | Admin Withdrawal Review | ⬜ Not Started |
| **Phase 14** | Nice-to-Have Features | ⬜ Not Started |
| **Phase 14.1** | Share Quiniela | ⬜ Not Started |
| **Phase 14.2** | Live Score Updates | ⬜ Not Started |
| **Phase 14.3** | Player Statistics | ⬜ Not Started |

### Pre-existing (Already Completed)

The following functionality was already present in the codebase before this plan and is **not** included as tasks above:

| Feature | User Story | Status |
|---------|-----------|--------|
| User registration (name, email, password) | US-1.1 | ✅ Done |
| Email verification | US-1.1 | ✅ Done |
| User login with redirect | US-1.2 | ✅ Done |
| "Remember me" session persistence | US-1.2 | ✅ Done |
| Password reset via email | US-1.3 | ✅ Done |
| Profile management (name, email, password) | US-1.4 | ✅ Partial — missing profile photo upload |
| Two-factor authentication | — | ✅ Done |
| Account deletion | — | ✅ Done |
| Appearance/theme settings | — | ✅ Done |
| Filament admin panel (empty, configured) | — | ✅ Structure only |
| bavix/laravel-wallet package installed | — | ✅ Installed, migrations pending |

> **Note on US-1.4 (profile photo):** Profile photo upload is not yet implemented. This is addressed in Phase 1.2 via spatie/medialibrary `avatar` collection on the User model. A small UI task is needed on the existing settings/profile page to add the upload field.
