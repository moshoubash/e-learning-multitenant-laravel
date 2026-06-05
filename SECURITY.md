# Security Policy

## Supported Versions

| Version | Supported          |
|---------|--------------------|
| main    | :white_check_mark: |
| <main   | :x:                |

## Reporting a Vulnerability

Please **do not** open a public GitHub issue for suspected security
vulnerabilities. Email security concerns to the maintainers privately
at the address listed on the repository owner profile.

We aim to acknowledge reports within 48 hours and to ship a fix or
mitigation within 30 days for critical issues, 90 days for others.

## Auditing Dependencies

This project tracks known-vulnerable dependencies via Composer's
[Roave/BetterReflection](https://github.com/Roave/SecurityAdvisories)
[Security Advisories Database](https://github.com/FriendsOfPHP/security-advisories).

Run locally:

```bash
composer audit
```

The audit is also executed in CI on every push and pull request
(`.github/workflows/security-audit.yml`). PRs that introduce a new
advisory at `medium` or higher severity will fail the audit job.

## Cryptographic Material

- `APP_KEY` must be 32+ random bytes. Never commit the real value;
  the `.env` file is git-ignored and `.env.example` ships with an
  empty `APP_KEY=` placeholder.
- All session cookies are marked `Secure`, `HttpOnly`, `SameSite=lax`
  and the session payload itself is encrypted at rest.
- HTTPS is enforced in production by
  `App\Http\Middleware\ForceHttpsInProduction`.
