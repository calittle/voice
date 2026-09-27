# VOICE

> **Archived school project (2017).** This was my first PHP project. It is preserved as a learning artifact, not as an example of production-ready software. **Do not use it to conduct an election—or, frankly, to secure anything important.**

VOICE stands for **Vote-Online Interactive Civic Enablement**, an admittedly unwieldy name for an online voting-system prototype.

## What this project attempted

The application models a surprisingly broad slice of an election system:

- user registration and authentication;
- voter/registrant records and approval;
- role-based administration;
- residences, districts, parties, and eligibility;
- elections, measures, options, and ballot casting;
- ballot receipts and an attempt at cryptographic verification; and
- a relational MySQL schema built around stored procedures.

For a first PHP project, the scope was wildly ambitious.

## What it got right

There are some respectable ideas in here, especially given its age and purpose:

- Most database operations use PDO prepared statements rather than interpolated SQL.
- Passwords were hashed with a bcrypt-style `crypt()` workflow instead of stored in plaintext.
- The application distinguishes authentication from role-based authorization on its main pages.
- Election access is checked against the logged-in registrant before a ballot is accepted.
- Duplicate voting appears to be constrained at the database layer.
- The repository contains requirements, risk, security, data-model, milestone, and work-breakdown documentation.
- The schema attempts to represent the actual domain rather than reducing everything to a few toy tables.
- The UI is reasonably complete for a student prototype and includes both voter and administrative workflows.

In other words: it demonstrates useful instincts, even where the implementation does not hold up.

## The archaeological findings

This code should not be deployed. Among other problems:

### Security

- A database credential is committed directly in `config.php`. It must be considered compromised and should never be reused.
- `ajx_functions.php` exposes privileged mutations—including changing roles, approving registrants, and modifying districts—without performing its own authentication or administrator authorization check.
- `ac.php` accepts a user ID from POST data as an authentication input instead of relying exclusively on a validated server-side session.
- State-changing requests have no CSRF protection.
- Successful login does not rotate the session ID, leaving room for session-fixation attacks.
- Database and application errors are sometimes returned directly to clients, leaking internal details.
- Database values are frequently written into HTML without contextual escaping, creating stored-XSS risk.
- Sensitive voter identity data, including state/federal identifiers and dates of birth, is handled without a defensible privacy or data-retention design.
- There is no visible rate limiting, account lockout, audit-log design, secure recovery flow, or modern security-header policy.

### Ballot integrity and secrecy

- The ballot "signature" is public-key encryption followed by server-side private-key decryption. That is not a digital signature.
- Both public and private voter keys are stored in the database, and private keys are copied into PHP sessions. The server can therefore forge whatever the scheme is intended to prove.
- The encrypted ballot material includes user and registrant identifiers alongside selections, undermining secret-ballot requirements.
- Client-supplied measure and option IDs are insufficiently validated against the exact server-side ballot definition.
- Ballots submitted outside an election window are still recorded and merely marked provisional.
- Multi-measure ballots are written one measure at a time without an obvious transaction around the complete ballot, allowing partial ballot state.
- The system lacks the threat model, independent auditability, end-to-end verifiability, key custody, operational controls, and accessibility work expected of real election technology.

### Compatibility and maintainability

- The code targets the PHP 5 era. It does not parse cleanly on modern PHP because of unparenthesized nested ternaries.
- It uses `mcrypt_create_iv()`, from an extension removed from PHP long ago.
- It relies on bundled, obsolete front-end dependencies such as Bootstrap 3 and an old jQuery release.
- Configuration, credentials, database access, HTML rendering, and much of the business logic are tightly coupled.
- `config.php` is more than a thousand lines long and contains a large collection of unrelated global functions.
- There is no dependency manifest, reproducible development environment, automated test suite, continuous integration, migration system, or deployment process.
- Error handling and return shapes are inconsistent, and several redirects do not terminate execution.
- The repository contains duplicate assets, old copies of pages, generated documentation, and debug artifacts.

## Historical verdict

VOICE is not election software. It is a snapshot of someone learning PHP, SQL, sessions, AJAX, cryptography, and application design all at once—and choosing an extraordinarily unforgiving problem domain in which to do it.

That makes it unsafe software, but a successful learning project. The right lesson is not that the attempt was embarrassing; it is that building it exposed exactly why authentication, cryptography, privacy, election integrity, and maintainable architecture are separate disciplines that cannot be improvised into existence.

Here lies VOICE, 2017–2017: ambitious, educational, and permanently relieved of election duty.
