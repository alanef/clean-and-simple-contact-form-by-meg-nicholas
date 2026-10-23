# Version Contract: 4.13

**Date:** 2026-10-23

## Canonical Source

Plugin header `Version:` and `CSCF_VERSION_NUM` in clean-and-simple-contact-form-by-meg-nicholas.php, and
`Stable tag:` in readme.txt. All are written and read back by `scripts/release/bump-version.sh`. CI fails when
they disagree.

## Expected State After Deploy

- main has every version location at `4.13`, with `= 4.13 =` at the top of changelog.txt
- Git tag `4.13` (this repo uses no `v` prefix) on main's HEAD; release.yml builds the zip and deploys to
  wp.org SVN (`Type: free`)
- wordpress.org/plugins/clean-and-simple-contact-form-by-meg-nicholas reports 4.13 after release confirmation
- The zip bundles alanef/free_plugin_lib 1.3.3

## Required Deploy Command

```
/tk-deploy live
```

## Post-Deploy Verification

See `.claude/commands/deploy-live.md`: GitHub release asset, SVN tag check, wp.org release confirmation, KB
changelog, YouTrack.
