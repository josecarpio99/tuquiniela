# User Stories — TuQuiniela

## Overview

This document contains user stories for TuQuiniela, a football prediction game platform where users predict match outcomes in quinielas, earn points for correct predictions, and win monetary prizes.

**User Types:**
- **Player** — Registered user who participates in quinielas by purchasing tickets and making predictions
- **Admin** — Platform administrator who manages quinielas, matches, deposits, withdrawals, and platform settings via the Filament panel
- **Visitor** — Unauthenticated user browsing the platform

---

## 1. Authentication & Registration

### US-1.1: Player Registration
**As a** Visitor
**I want to** register with my email and password
**So that** I can create an account and start participating in quinielas

**Acceptance Criteria:**
- [ ] Registration form collects: name, email, password, password confirmation
- [ ] Email must be unique in the system
- [ ] Password must meet minimum security requirements (8+ characters)
- [ ] User receives email verification link
- [ ] Account is created with "player" role and a $0.00 balance
- [ ] User is redirected to the player dashboard after verification

**Expected Result:** Player account is created and user can access the platform features.

---

### US-1.2: User Login
**As a** registered user (Player or Admin)
**I want to** log in to my account
**So that** I can access my personalized dashboard

**Acceptance Criteria:**
- [ ] Login form accepts email and password
- [ ] Invalid credentials show appropriate error message
- [ ] Successful login redirects to role-specific dashboard (player dashboard or Filament admin panel)
- [ ] "Remember me" option persists session

**Expected Result:** User is authenticated and redirected to their appropriate dashboard.

---

### US-1.3: Password Reset
**As a** registered user
**I want to** reset my password if I forget it
**So that** I can regain access to my account

**Acceptance Criteria:**
- [ ] "Forgot password" link on login page
- [ ] User enters email address
- [ ] Password reset link sent to email (valid for 60 minutes)
- [ ] User can set new password via reset link
- [ ] Confirmation message shown after successful reset

**Expected Result:** User receives reset email and can set a new password.

---

### US-1.4: Profile Management
**As a** Player
**I want to** manage my profile information
**So that** my account details are up to date

**Acceptance Criteria:**
- [ ] Player can edit: name, email, password
- [ ] Player can upload/change profile photo
- [ ] Email change requires re-verification
- [ ] Password change requires current password confirmation
- [ ] Changes are saved and reflected immediately

**Expected Result:** Player profile is updated successfully.

---

## 2. Quiniela Management (Admin)

### US-2.1: Create Quiniela
**As an** Admin
**I want to** create a new quiniela
**So that** players can participate in prediction games

**Acceptance Criteria:**
- [ ] Creation form in Filament panel collects:
  - Name/title of the quiniela
  - Prediction type: "By Result" or "By Score"
  - Ticket cost (configurable monetary amount, e.g., $1.00)
  - Closing date/time (deadline for ticket purchases and prediction changes)
  - Status (draft, open, closed, completed)
- [ ] Point values are configurable:
  - By Result mode: points per correct result (default: 1)
  - By Score mode: points for exact score (default: 4), correct result (default: 2), wrong prediction (default: -1)
- [ ] Prize configuration (see US-5.1 for details)
- [ ] Quiniela is created in "draft" status by default
- [ ] Admin can add matches to the quiniela after creation

**Expected Result:** Quiniela is created and ready for matches to be added.

---

### US-2.2: Add Matches to Quiniela
**As an** Admin
**I want to** add football matches to a quiniela
**So that** players have matches to predict

**Acceptance Criteria:**
- [ ] Admin can add multiple matches to a quiniela
- [ ] Each match requires: Team 1 name, Team 2 name, match date/time
- [ ] Matches can be reordered within the quiniela
- [ ] Matches can be edited or removed before the quiniela closes
- [ ] Cannot add matches to a completed quiniela

**Expected Result:** Quiniela contains a list of matches that players will predict.

---

### US-2.3: Open Quiniela for Participation
**As an** Admin
**I want to** change a quiniela's status to "open"
**So that** players can start purchasing tickets and making predictions

**Acceptance Criteria:**
- [ ] Admin can change status from "draft" to "open"
- [ ] Quiniela must have at least one match to be opened
- [ ] Quiniela must have prize configuration to be opened
- [ ] Closing date must be in the future
- [ ] Once open, the quiniela appears in the player-facing quiniela list

**Expected Result:** Quiniela is visible and available for players to participate.

---

### US-2.4: Close Quiniela
**As an** Admin
**I want to** close a quiniela manually or have it auto-close
**So that** no more tickets can be purchased or predictions changed

**Acceptance Criteria:**
- [ ] Quiniela automatically closes when the closing date/time is reached
- [ ] Admin can manually close a quiniela before the deadline
- [ ] Once closed, players cannot buy new tickets or modify predictions
- [ ] Existing tickets and predictions are preserved
- [ ] Status changes to "closed"

**Expected Result:** Quiniela is locked and awaiting match results.

---

### US-2.5: Enter Match Results
**As an** Admin
**I want to** enter the final score for each match in a quiniela
**So that** predictions can be scored and prizes awarded

**Acceptance Criteria:**
- [ ] Admin can enter final score (Team 1 goals, Team 2 goals) for each match
- [ ] Results can only be entered for closed quinielas
- [ ] Admin can edit results in case of error (before completing the quiniela)
- [ ] System shows how many matches still need results
- [ ] Scoring is triggered once all match results are entered (or per-match as results are entered)

**Expected Result:** Match results are recorded and available for scoring.

---

### US-2.6: Complete Quiniela and Distribute Prizes
**As an** Admin
**I want to** finalize a quiniela and distribute prizes
**So that** winning players receive their rewards

**Acceptance Criteria:**
- [ ] All match results must be entered before completing
- [ ] Admin can review the final standings/leaderboard before confirming
- [ ] System calculates final points for all tickets
- [ ] System determines winners based on rankings and prize configuration
- [ ] Ties are resolved by splitting the prize equally among tied positions
- [ ] Prize amounts are credited to winners' balances automatically
- [ ] Balance transactions are recorded (see US-6.2)
- [ ] Status changes to "completed"
- [ ] Winners are notified (email and/or Telegram/WhatsApp)

**Expected Result:** Prizes are distributed to winners and quiniela is finalized.

---

## 3. Tickets & Predictions (Player)

### US-3.1: Browse Available Quinielas
**As a** Player or Visitor
**I want to** see a list of open quinielas
**So that** I can choose which ones to participate in

**Acceptance Criteria:**
- [ ] List of open quinielas displayed with:
  - Name/title
  - Prediction type (By Result or By Score)
  - Ticket cost
  - Number of matches
  - Closing date/time with countdown
  - Number of tickets sold (optional)
- [ ] Completed quinielas are viewable in a separate section or tab (with results)
- [ ] Quinielas sorted by closing date (soonest first)

**Expected Result:** Player can see all available quinielas and their key details.

---

### US-3.2: Purchase Ticket
**As a** Player
**I want to** purchase a ticket for a quiniela
**So that** I can submit my predictions

**Acceptance Criteria:**
- [ ] Player selects an open quiniela
- [ ] System verifies player has sufficient balance for the ticket cost
- [ ] If insufficient balance, player is prompted to make a deposit
- [ ] Ticket cost is deducted from player's balance
- [ ] Balance transaction is recorded (debit)
- [ ] Player can purchase multiple tickets for the same quiniela
- [ ] Player is redirected to the prediction form after purchase

**Expected Result:** Ticket is purchased, balance deducted, and player can make predictions.

---

### US-3.3: Submit Predictions (By Result)
**As a** Player
**I want to** predict the outcome of each match (Team 1 wins, Team 2 wins, or Draw)
**So that** I can earn points for correct predictions

**Acceptance Criteria:**
- [ ] Prediction form shows all matches in the quiniela
- [ ] For each match, player selects one of: Team 1, Draw, Team 2
- [ ] All matches must have a prediction before submitting
- [ ] Predictions are saved and confirmed
- [ ] Player can see a summary of their submitted predictions

**Expected Result:** Predictions are saved for the ticket.

---

### US-3.4: Submit Predictions (By Score)
**As a** Player
**I want to** predict the exact score of each match
**So that** I can earn points for correct predictions

**Acceptance Criteria:**
- [ ] Prediction form shows all matches in the quiniela
- [ ] For each match, player enters: Team 1 goals, Team 2 goals
- [ ] Goals must be non-negative integers
- [ ] All matches must have a prediction before submitting
- [ ] Predictions are saved and confirmed
- [ ] Player can see a summary of their submitted predictions

**Expected Result:** Score predictions are saved for the ticket.

---

### US-3.5: Edit Predictions
**As a** Player
**I want to** change my predictions before the quiniela closes
**So that** I can update my picks if I change my mind

**Acceptance Criteria:**
- [ ] Player can access their ticket and modify predictions
- [ ] Edits are only allowed while the quiniela is "open" (before closing date)
- [ ] Once the quiniela is closed, predictions are locked
- [ ] System shows clear indication of whether predictions can still be edited
- [ ] Changes are saved immediately

**Expected Result:** Player's predictions are updated while the quiniela is still open.

---

### US-3.6: View My Tickets
**As a** Player
**I want to** view all my tickets across quinielas
**So that** I can track my participation and results

**Acceptance Criteria:**
- [ ] Dashboard shows tickets grouped by quiniela status (active, closed, completed)
- [ ] Each ticket displays:
  - Quiniela name
  - Prediction type
  - Ticket cost paid
  - Predictions made
  - Points earned (for closed/completed quinielas)
  - Prize won, if any (for completed quinielas)
- [ ] Player can click into a ticket to see full prediction details vs. actual results

**Expected Result:** Player has complete visibility of their ticket history and results.

---

## 4. Scoring

### US-4.1: Calculate Points (By Result Mode)
**As the** System
**I want to** calculate points for each ticket in a "By Result" quiniela
**So that** players are scored correctly

**Acceptance Criteria:**
- [ ] For each match in the ticket:
  - Prediction matches actual result (Team 1 win / Team 2 win / Draw): award configured points (default: +1)
  - Prediction does not match: 0 points
- [ ] Total points = sum of all match points for the ticket
- [ ] Points are calculated when match results are entered
- [ ] Points are visible to the player on their ticket

**Expected Result:** Each ticket has an accurate total point score.

---

### US-4.2: Calculate Points (By Score Mode)
**As the** System
**I want to** calculate points for each ticket in a "By Score" quiniela
**So that** players are scored with the tiered point system

**Acceptance Criteria:**
- [ ] For each match in the ticket:
  - Exact score match: award configured points (default: +4)
  - Correct result but wrong score: award configured points (default: +2)
  - Wrong result: apply configured penalty (default: -1)
- [ ] Total points = sum of all match points for the ticket
- [ ] A ticket's total points can be negative
- [ ] Points are calculated when match results are entered
- [ ] Points breakdown is visible to the player (per match and total)

**Expected Result:** Each ticket has an accurate total point score with tiered rewards.

---

## 5. Prizes

### US-5.1: Configure Prize Structure
**As an** Admin
**I want to** configure the prize structure for a quiniela
**So that** winners know what they can win

**Acceptance Criteria:**
- [ ] Admin selects prize type:
  - **Fixed Amount**: Define a total prize pool (e.g., $100). Assign a percentage to each winning position (e.g., 1st: 80%, 2nd: 20%)
  - **Percentage of Pool**: Define a percentage of total ticket sales as the prize pool (e.g., 60%). Assign a percentage to each winning position (e.g., 1st: 40%, 2nd: 20%)
- [ ] Admin can define any number of winning positions (1st, 2nd, 3rd, etc.)
- [ ] The sum of position percentages must equal 100% of the prize pool
- [ ] Prize configuration is displayed to players on the quiniela detail page

**Expected Result:** Prize structure is defined and visible to participants.

---

### US-5.2: Award Prizes
**As the** System
**I want to** distribute prizes to winning players
**So that** winners receive their monetary rewards

**Acceptance Criteria:**
- [ ] Tickets are ranked by total points (highest first)
- [ ] Tied positions split the combined prize equally:
  - Example: Two players tie for 1st. If 1st gets $80 and 2nd gets $20, both receive ($80 + $20) / 2 = $50
- [ ] For "Fixed Amount" prizes: amounts are calculated from the fixed pool
- [ ] For "Percentage of Pool" prizes: amounts are calculated from total ticket sales × configured percentage
- [ ] Prize amounts are credited to winners' balances
- [ ] Balance transactions are recorded with reference to the quiniela
- [ ] Admin can review prize distribution before final confirmation

**Expected Result:** Correct prize amounts are distributed to the right players.

---

## 6. Balance System

### US-6.1: View Balance
**As a** Player
**I want to** see my current balance
**So that** I know how much I have available to play

**Acceptance Criteria:**
- [ ] Current balance is prominently displayed on the player dashboard
- [ ] Balance is shown in the platform's currency
- [ ] Balance is always ≥ $0.00
- [ ] Balance updates in real-time after transactions

**Expected Result:** Player can always see their current available balance.

---

### US-6.2: View Balance History
**As a** Player
**I want to** see my balance transaction history
**So that** I can track all credits and debits

**Acceptance Criteria:**
- [ ] Transaction history shows:
  - Date/time
  - Type (deposit, withdrawal, ticket purchase, prize won)
  - Description (e.g., "Ticket for Quiniela Jornada 5", "Prize — 1st place Jornada 5")
  - Amount (+ for credits, - for debits)
  - Running balance after transaction
- [ ] Transactions sorted by date (newest first)
- [ ] Can filter by transaction type
- [ ] Can filter by date range

**Expected Result:** Player has complete visibility into all balance movements.

---

### US-6.3: Admin Balance Overview
**As an** Admin
**I want to** view balance and transaction history for any player
**So that** I can monitor financial activity and resolve disputes

**Acceptance Criteria:**
- [ ] Admin can search for a player by name or email
- [ ] Admin can view any player's current balance
- [ ] Admin can view any player's full transaction history
- [ ] Admin can manually adjust a player's balance (with a reason/note)
- [ ] Manual adjustments are logged in the transaction history

**Expected Result:** Admin has full visibility and control over player balances.

---

## 7. Deposits

### US-7.1: Request Deposit
**As a** Player
**I want to** submit a deposit request to add funds to my balance
**So that** I can purchase tickets for quinielas

**Acceptance Criteria:**
- [ ] Player selects a payment method (initially: Binance Pay)
- [ ] Form collects payment-method-specific fields:
  - Binance Pay: Binance ID or email
- [ ] Player enters the deposit amount
- [ ] Player uploads proof of payment (screenshot or reference number)
- [ ] Deposit request is created with status "pending"
- [ ] Player is shown the platform's payment details (where to send the payment)
- [ ] Player receives confirmation that the deposit request was submitted

**Expected Result:** Deposit request is submitted and awaiting admin review.

---

### US-7.2: Admin Review Deposits
**As an** Admin
**I want to** review and approve or reject deposit requests
**So that** only verified deposits are credited to player balances

**Acceptance Criteria:**
- [ ] List of pending deposit requests in Filament panel
- [ ] Each request shows:
  - Player name and email
  - Amount
  - Payment method
  - Payment details (Binance ID, etc.)
  - Proof of payment (viewable image/document)
  - Request date
- [ ] Admin can approve:
  - Amount is credited to player's balance
  - Balance transaction is recorded
  - Player is notified (email and/or Telegram/WhatsApp)
  - Status changes to "approved"
- [ ] Admin can reject:
  - Admin provides rejection reason
  - Player is notified with the reason
  - Status changes to "rejected"
- [ ] Admin can view history of processed deposits (approved and rejected)

**Expected Result:** Verified deposits are credited; fraudulent ones are rejected.

---

### US-7.3: Extensible Payment Methods
**As an** Admin
**I want** the deposit system to support multiple and future payment methods
**So that** I can add new methods (crypto, pago móvil, AirTM, PayPal) without major refactoring

**Acceptance Criteria:**
- [ ] Payment methods are configurable (add/edit/disable via admin panel)
- [ ] Each payment method defines its own required fields:
  - Binance Pay: Binance ID or email
  - Crypto: wallet address
  - Pago Móvil: phone number, cédula, bank
  - AirTM: email
  - PayPal: email
- [ ] Platform payment details per method are configurable (e.g., platform's Binance ID)
- [ ] Adding a new payment method does not require code changes to the deposit form
- [ ] Payment methods can be enabled/disabled independently

**Expected Result:** Platform supports flexible payment methods that can evolve over time.

---

## 8. Withdrawals

### US-8.1: Request Withdrawal
**As a** Player
**I want to** request a withdrawal from my balance
**So that** I can cash out my winnings

**Acceptance Criteria:**
- [ ] Player selects a payment method for receiving funds
- [ ] Form collects payment-method-specific fields (same as deposits)
- [ ] Player enters the withdrawal amount
- [ ] System validates player has sufficient balance
- [ ] Withdrawal amount is held (reserved) from balance to prevent overspending
- [ ] Withdrawal request is created with status "pending"
- [ ] Player receives confirmation that the withdrawal request was submitted

**Expected Result:** Withdrawal request is submitted and balance is reserved.

---

### US-8.2: Admin Review Withdrawals
**As an** Admin
**I want to** review and process withdrawal requests
**So that** players receive their funds

**Acceptance Criteria:**
- [ ] List of pending withdrawal requests in Filament panel
- [ ] Each request shows:
  - Player name and email
  - Amount
  - Payment method
  - Payment details (where to send funds)
  - Request date
  - Player's current balance
- [ ] Admin can approve:
  - Reserved amount is deducted from player's balance
  - Balance transaction is recorded
  - Player is notified (email and/or Telegram/WhatsApp)
  - Status changes to "approved"
- [ ] Admin can reject:
  - Reserved amount is released back to player's balance
  - Admin provides rejection reason
  - Player is notified with the reason
  - Status changes to "rejected"
- [ ] Admin can view history of processed withdrawals

**Expected Result:** Withdrawals are processed and players receive their funds or rejection notice.

---

## 9. Leaderboard

### US-9.1: View Quiniela Leaderboard
**As a** Player
**I want to** see the leaderboard for a quiniela
**So that** I can see how I rank against other participants

**Acceptance Criteria:**
- [ ] Leaderboard is available for closed and completed quinielas
- [ ] Leaderboard shows:
  - Rank / position
  - Player name (or username)
  - Total points
  - Prize won (for completed quinielas)
- [ ] Player's own position is highlighted
- [ ] Tied positions show the same rank number
- [ ] Leaderboard updates as match results are entered
- [ ] For open quinielas, leaderboard is hidden to prevent spoiling strategies

**Expected Result:** Player can see rankings and compare their performance.

---

### US-9.2: View Detailed Ticket Results
**As a** Player
**I want to** see a detailed breakdown of my predictions vs. actual results
**So that** I understand how my points were calculated

**Acceptance Criteria:**
- [ ] Available after match results are entered
- [ ] For each match:
  - My prediction (result or score)
  - Actual result/score
  - Points earned/lost for that match
  - Visual indicator (correct/partially correct/wrong)
- [ ] Total points summary at the bottom
- [ ] Prize won (if applicable)

**Expected Result:** Player understands exactly how their score was calculated.

---

## 10. Notifications

### US-10.1: Email Notifications
**As a** Player
**I want to** receive email notifications for important events
**So that** I stay informed about my activity

**Acceptance Criteria:**
- [ ] Notifications sent for:
  - Deposit approved/rejected
  - Withdrawal approved/rejected
  - Quiniela results published (if participated)
  - Prize won
- [ ] Emails include relevant details (amounts, quiniela name, etc.)
- [ ] Emails are sent asynchronously (queued)

**Expected Result:** Player receives timely email notifications for key events.

---

### US-10.2: Telegram/WhatsApp Notifications
**As a** Player
**I want to** receive notifications via Telegram or WhatsApp
**So that** I get faster alerts on my preferred messaging platform

**Acceptance Criteria:**
- [ ] Player can opt in and configure their preferred messaging channel
- [ ] Notifications sent for the same events as email (US-10.1)
- [ ] Messages are concise and include key details
- [ ] Player can disable messaging notifications independently from email

**Expected Result:** Player receives notifications on their preferred messaging platform.

---

### US-10.3: Admin Notifications
**As an** Admin
**I want to** receive notifications when actions require my attention
**So that** I can process requests promptly

**Acceptance Criteria:**
- [ ] Admin is notified when:
  - A new deposit request is submitted
  - A new withdrawal request is submitted
- [ ] Notifications appear in the Filament admin panel
- [ ] Optionally sent via email or Telegram/WhatsApp

**Expected Result:** Admin is alerted to pending actions requiring review.

---

## 11. Admin Dashboard

### US-11.1: View All Players
**As an** Admin
**I want to** view all registered players
**So that** I can manage the platform's user base

**Acceptance Criteria:**
- [ ] List of all players in Filament panel
- [ ] Searchable by name or email
- [ ] Shows: name, email, balance, registration date, status
- [ ] Can view player details (profile, balance history, tickets)
- [ ] Can deactivate/suspend player accounts

**Expected Result:** Admin has complete visibility and control over player accounts.

---

### US-11.2: Manage Quinielas
**As an** Admin
**I want to** view and manage all quinielas
**So that** I can oversee the platform's games

**Acceptance Criteria:**
- [ ] List of all quinielas in Filament panel
- [ ] Filterable by status (draft, open, closed, completed)
- [ ] Shows: name, prediction type, ticket cost, number of tickets sold, status, closing date
- [ ] Can view detailed quiniela information (matches, tickets, leaderboard, prizes)
- [ ] Can edit quiniela settings (before closing)
- [ ] Can delete draft quinielas

**Expected Result:** Admin has full oversight of all quinielas on the platform.

---

### US-11.3: Revenue & Financial Overview
**As an** Admin
**I want to** see financial metrics for the platform
**So that** I can track performance and revenue

**Acceptance Criteria:**
- [ ] Dashboard shows:
  - Total deposits (all time / this month)
  - Total withdrawals (all time / this month)
  - Total ticket sales (all time / this month)
  - Total prizes distributed (all time / this month)
  - Platform revenue (ticket sales minus prizes)
- [ ] Can filter by date range
- [ ] Shows summary per quiniela (tickets sold, revenue, prizes paid)

**Expected Result:** Admin can track and analyze platform financial performance.

---

### US-11.4: Configure Platform Settings
**As an** Admin
**I want to** manage platform-wide settings
**So that** I can configure how the platform operates

**Acceptance Criteria:**
- [ ] Configure default point values for each prediction type
- [ ] Manage payment methods (add, edit, enable/disable)
- [ ] Configure platform payment details per method
- [ ] Configure notification settings (enabled channels)
- [ ] Configure minimum/maximum deposit and withdrawal amounts

**Expected Result:** Admin can customize platform behavior without code changes.

---

## 12. Additional Features (Nice-to-Have)

### US-12.1: Share Quiniela
**As a** Player
**I want to** share a quiniela link with friends
**So that** they can join and participate

**Acceptance Criteria:**
- [ ] Share button on quiniela detail page
- [ ] Generates a shareable link
- [ ] Link works for both registered and unregistered users
- [ ] Unregistered users are prompted to register

---

### US-12.2: Live Score Updates
**As a** Player
**I want to** see match results update in real-time as the admin enters them
**So that** I can follow the quiniela progress live

**Acceptance Criteria:**
- [ ] Leaderboard and ticket results update without page refresh
- [ ] Players see their points change as each match result is entered

---

### US-12.3: Quiniela History & Statistics
**As a** Player
**I want to** see my overall statistics across all quinielas
**So that** I can track my prediction accuracy over time

**Acceptance Criteria:**
- [ ] Player profile shows:
  - Total quinielas participated in
  - Total tickets purchased
  - Win rate / accuracy percentage
  - Total prizes won
  - Prediction accuracy breakdown

---

## Appendix: User Story Status

| ID | Story | Priority | Status |
|----|-------|----------|--------|
| US-1.1 | Player Registration | High | Pending |
| US-1.2 | User Login | High | Pending |
| US-1.3 | Password Reset | Medium | Pending |
| US-1.4 | Profile Management | Medium | Pending |
| US-2.1 | Create Quiniela | High | Pending |
| US-2.2 | Add Matches to Quiniela | High | Pending |
| US-2.3 | Open Quiniela | High | Pending |
| US-2.4 | Close Quiniela | High | Pending |
| US-2.5 | Enter Match Results | High | Pending |
| US-2.6 | Complete Quiniela & Distribute Prizes | High | Pending |
| US-3.1 | Browse Available Quinielas | High | Pending |
| US-3.2 | Purchase Ticket | High | Pending |
| US-3.3 | Submit Predictions (By Result) | High | Pending |
| US-3.4 | Submit Predictions (By Score) | High | Pending |
| US-3.5 | Edit Predictions | Medium | Pending |
| US-3.6 | View My Tickets | Medium | Pending |
| US-4.1 | Calculate Points (By Result) | High | Pending |
| US-4.2 | Calculate Points (By Score) | High | Pending |
| US-5.1 | Configure Prize Structure | High | Pending |
| US-5.2 | Award Prizes | High | Pending |
| US-6.1 | View Balance | High | Pending |
| US-6.2 | View Balance History | Medium | Pending |
| US-6.3 | Admin Balance Overview | Medium | Pending |
| US-7.1 | Request Deposit | High | Pending |
| US-7.2 | Admin Review Deposits | High | Pending |
| US-7.3 | Extensible Payment Methods | Medium | Pending |
| US-8.1 | Request Withdrawal | High | Pending |
| US-8.2 | Admin Review Withdrawals | High | Pending |
| US-9.1 | View Quiniela Leaderboard | Medium | Pending |
| US-9.2 | View Detailed Ticket Results | Medium | Pending |
| US-10.1 | Email Notifications | Medium | Pending |
| US-10.2 | Telegram/WhatsApp Notifications | Low | Pending |
| US-10.3 | Admin Notifications | Medium | Pending |
| US-11.1 | View All Players | Medium | Pending |
| US-11.2 | Manage Quinielas | Medium | Pending |
| US-11.3 | Revenue & Financial Overview | Medium | Pending |
| US-11.4 | Configure Platform Settings | Low | Pending |
| US-12.1 | Share Quiniela | Low | Pending |
| US-12.2 | Live Score Updates | Low | Pending |
| US-12.3 | Quiniela History & Statistics | Low | Pending |
