# Tests Passed: 4.13.0

**Date:** 2026-11-27T15:59:35Z
**Commit:** ec893d8bc160fa9c4dd2394636d66ba8cc1f84cf
**Tracked changes at run time:** 1

| Gate | Result |
|---|---|
| composer install | pass |
| composer validate --strict | pass |
| composer run check | pass |
| version consistency | pass |
| build (composer run build) | pass |
| Plugin Check | not run locally (no plugin-check script); CI runs it on the built zip |
| PHPUnit (npm test) | pass |

PHPUnit summaries:

    OK (37 tests, 80 assertions)

Full output: tests.log (not committed).
