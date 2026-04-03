---
name: update-project-phase-status
description: "Use this skill whenever you start, progress, or complete a task defined in project-phases.md. It ensures the project's roadmap remains an accurate source of truth by updating phase headers with status markers (e.g., [In Progress], [Completed]) and synchronizing the Status Summary table at the end of the document. Trigger this skill after finishing migrations, models, or features to maintain high visibility of the project's current state."
---

# Update Project Phase Status

This skill ensures that the `docs/project-phases.md` file is kept up-to-date with the actual progress of the project. Whenever a phase or sub-phase is worked on, completed, or its scope changes, this skill should be used to update the document.

## Workflow

1.  **Identify the Phase**: Locate the specific phase (e.g., Phase 1.1) being worked on in `docs/project-phases.md`.
2.  **Determine Status**:
    *   `Pending`: Not started yet.
    *   `In Progress`: Currently being worked on.
    *   `Completed`: All steps finished and tests passing.
3.  **Update the Document**:
    *   Add a status indicator next to the phase header (e.g., `### Phase 1.1: Install Dependencies [Completed]`).
    *   Optionally, add a date or brief note if relevant.
    *   If a task was skipped or moved, update the description accordingly.
4.  **Verify Consistency**: Ensure that if a sub-phase is completed, the parent phase status is also considered for update.

## Quality Criteria

- The status in `docs/project-phases.md` MUST match the actual state of the codebase.
- Avoid leaving "In Progress" markers for tasks that are actually finished.
- Ensure that "Completed" tasks have corresponding passing tests as defined in the phase description.

## Example Prompts

- "I've finished the migrations for Phase 1.4. Update the project phases doc to mark it as completed."
- "Start working on Phase 2.1. Mark it as in-progress in the status summary."
- "Review the current codebase and update the status of all Phase 1 tasks in docs/project-phases.md."
