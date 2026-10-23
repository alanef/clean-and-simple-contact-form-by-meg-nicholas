# Combined Verification Report: PLUGINS_SMALL-6 (CSCF)

**Issue:** free_plugin_lib: setup notice can't be dismissed and never goes away (all free plugins)
**Date:** 2026-10-23
**Commit:** 0079249 (CSCF); free_plugin_lib v1.3.3 (4f34052)

## Executive Summary

| Review | Status |
|--------|--------|
| Commit Integrity | not-applicable (the issue claims no SHAs); change-set non-empty (d1cb31a, 0079249) |
| Code Review | passed: the library diff v1.3.0..v1.3.2 had an independent review, and its critical finding was fixed in 1.3.2 |
| Acceptance Review | passed, in CSCF's wp-env over HTTP |
| Browser-Test Gate | n-a: no layout change. The rendered notice HTML and the redirect chain were checked over HTTP |
| **Overall** | **APPROVED WITH NOTES** |

## Where the code is

The fix is in alanef/free_plugin_lib. CSCF's change is the constraint `^1.3.3`, with the bundled copy upgraded
from 1.2.4 to 1.3.3, plus changelog bullets. The full library evidence is in stop-user-enumeration
`.sdlc/PLUGINS_SMALL-6/` (eb7d425):
- the independent review, with 1 critical finding fixed in 1.3.2
- the per-fix mutation results
- the before/after HTTP repro of the empty-page redirect
- the wp-env proof of the shared static shortname

The library suite at v1.3.3 has 35 tests and 78 assertions, and its CI is green.

## How CSCF uses the library

`new Main(..., 'CSCF', 'settings_page_contact-form-settings', ...)`. That is a hook suffix, not a slug. Before
1.3.2, the redirect from CSCF's settings never fired for this plugin. 1.3.2 derives the slug
`contact-form-settings`.

## Acceptance: HTTP checks in CSCF's wp-env (:8500, admin), status `rendering`

| Check | Result |
|---|---|
| GET /wp-admin/ | 200 (no redirect) |
| POST admin-ajax.php heartbeat | 200 (no redirect) |
| Notice | rendered, `data-shortname="CSCF"`, links to `options-general.php?page=ffpl-opt-in-CSCF` ("Opt in or skip") |
| Dismiss AJAX with the page's nonce | `{"success":true}`; `CSCF_notice_dismissed` = 1; the notice is gone on reload |
| GET contact-form-settings | 302 to `ffpl-opt-in-CSCF` |
| Skip link | 302; status `optout`; settings then 200 |

Options and meta were reset afterwards. `composer run check` is clean, and `npm test` passed 37 tests in wp-env.

## Uninstall removes the library's option (0079249)

Since 1.3.0 the library no longer registers an uninstall hook, so each plugin must call
`Main::plugin_uninstall()` itself. CSCF's `uninstall.php` now requires `vendor/autoload.php`, because the plugin
is not loaded during uninstall, and calls `Main::plugin_uninstall('CSCF')`.

The real WordPress path in wp-env: `wp plugin deactivate`, then `wp plugin uninstall --skip-delete`, which runs
uninstall_plugin() and keeps the files, since they are the bind-mounted working tree.

| Step | CSCF_form_rendered | cscf_options | cscf_version |
|---|---|---|---|
| before | present (`optout`) | present | present |
| after deactivate | present | present | present |
| after uninstall | **absent** | absent | absent |
| control: the same run with the previous uninstall.php (HEAD~) | **`'optout'` remains** | removed | removed |

The plugin was reactivated afterwards: `composer run check` clean, `npm test` OK (37 tests), and CI green on 0079249.

## Notes
- An admin opening CSCF's settings with no choice made is redirected before any notice could show there, so
  the hook-suffix "notice on own settings screen" fix has no visible effect. It is not claimed in the changelog.
- On sites with several Fullworks free plugins, the first copy of the library to load serves them all. CSCF
  sorts first alphabetically, so its copy usually wins.

## Commit Range Covered

| SHA | Subject |
|-----|---------|
| d1cb31a | Take free_plugin_lib 1.3.3 (PLUGINS_SMALL-6, PLUGINS_SMALL-7) |
| 0079249 | Remove the opt-in option on uninstall (PLUGINS_SMALL-6) |

No out-of-range commits.

---
Verified by: AI Assistant
Date: 2026-10-23
