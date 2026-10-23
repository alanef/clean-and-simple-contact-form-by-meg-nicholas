# Change Impact Assessment: 4.13

## Risk Level: Low

### Factors Considered
- Number of issues: 3 (one is tooling with nothing shipped but metadata)
- Security-sensitive changes: 0 in CSCF code. The library adds a capability check on Skip.
- API changes: no
- Database changes: no (same option and user-meta keys)
- Breaking changes: no

## Affected Areas

### Code Areas
- Bundled `alanef/free_plugin_lib` (vendor/), 1.2.4 to 1.3.3: the opt-in notice, dismiss, the redirect from
  `contact-form-settings` to the opt-in screen, and AJAX handlers scoped per plugin.
- uninstall.php: also loads the vendor autoloader and calls `Main::plugin_uninstall('CSCF')`.
- readme.txt: the FAQ entry and the `Type: free` header. No CSCF plugin code changed.

### Configuration
- None.

### Dependencies
- alanef/free_plugin_lib ^v1.0.0 to ^1.3.3 (excludes the bad 1.3.1).

## Behaviour change users will notice
- Before, CSCF never redirected to the opt-in screen, because it passes a hook suffix. Now admins who have
  made no choice are sent to the opt-in screen from the contact form settings until they click Allow or Skip.
- On sites with several Fullworks free plugins, each may now ask once for its own choice.

## Rollback Plan

### Immediate Rollback
```bash
git revert <Release 4.13>..d1cb31a   # then release as 4.13.1
```

### Database Rollback
N/A

### Configuration Rollback
N/A

## Monitoring
- wp.org support forum for reports of being redirected to the opt-in screen
- wp.org reviews during the 4.13 period
