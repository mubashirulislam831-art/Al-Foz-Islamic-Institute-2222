# Production System Rules & Guidelines

This system is in live use. These rules MUST be strictly followed by all agents and developers during any updates, refactoring, or republishing of the application.

## 🛑 Data Safety & Database Integrity Rules

- **Never reset the database**: Do not clear, truncate, or wipe database collections/tables.
- **Never recreate database tables**: Drop commands or table drops are strictly forbidden.
- **Never delete existing records**: Do not delete live user records or system data unless explicitly requested by the user.
- **Never overwrite core data**: Student, Teacher, Parent, Attendance, Fee, Salary, or Report data must never be overwritten, reset, or corrupted.
- **Never remove uploaded files**: Keep all physical or local media uploads, documents, and student files intact.
- **Keep all IDs unchanged**: Maintain stable ID sequences and unique reference codes (e.g. roll numbers, employee IDs, etc.).

## 🔄 Deployment & Update Process

- **Code-Only Updates**: During republishing/redeploying, update ONLY the application logic (PHP, CSS, JavaScript, HTML, and UI files).
- **Preserve 100% of existing data**: Ensure data persistence remains 100% stable through the update process.
- **Non-destructive features**: Any new feature or module must be added without affecting existing database structures or records.
- **Continuous availability**: Existing modules, functions, and views must remain fully operational and tested after every code change.
- **Safe database migrations**: Database schema changes (if required) must only be done using non-destructive, additive migrations.
- **Backward compatibility**: Always maintain backward compatibility with older data formats, existing records, and previous session states.
