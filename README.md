# Contact Form Clean and Simple

<!-- tooling:start (managed by wordpress-plugin-boilerplate/tooling - do not edit by hand) -->
## Development

This repository uses the standard Fullworks free-plugin tooling, documented in
[wordpress-plugin-boilerplate](https://github.com/alanef/wordpress-plugin-boilerplate/blob/main/CLAUDE.md).

[![Plugin Check](https://github.com/alanef/clean-and-simple-contact-form-by-meg-nicholas/actions/workflows/checks.yml/badge.svg)](https://github.com/alanef/clean-and-simple-contact-form-by-meg-nicholas/actions/workflows/checks.yml)

```
clean-and-simple-contact-form-by-meg-nicholas/                     # repository root: development tooling
├── .github/workflows/             # checks.yml on push/PR, release.yml on tag
├── tests/                         # PHPUnit suite, run inside wp-env
├── .wp-env.json                   # dev :8500, tests :8501
├── composer.json                  # dev dependencies and quality scripts
├── package.json                   # wp-env and test scripts
├── phpunit.xml.dist / run-tests.sh
└── clean-and-simple-contact-form-by-meg-nicholas/                # the plugin (shipped as-is via .distignore)
```

```bash
composer install && npm install        # dev tools
npm run start                          # http://localhost:8500  (admin / password)
composer run check                     # PHPCompatibility + security sniffs
npm test                               # PHPUnit in the wp-env tests container
composer run build                     # zipped/clean-and-simple-contact-form-by-meg-nicholas-free.zip
```

Changes go in `clean-and-simple-contact-form-by-meg-nicholas/changelog.txt` under `= Unreleased =`. Releases run through
`/tk-release X.Y.Z` then `/tk-deploy live` (see CLAUDE.md): the tag makes CI build the zip,
create the GitHub release and deploy wherever the readme `Type:` says.
<!-- tooling:end -->
