# Site migration workspace

This repository is prepared as a clean workspace for migrating the legacy site from a custom CMS to a modern stack.

## What to upload

The legacy project inputs are stored at the repository root:

```text
tomskagroinvest.zip         # archive with the current site files
u2818473_agroinvest.sql    # MySQL database dump
```

The site archive currently expands into `tomskagroinvest.ru/` and contains the custom CMS source, public assets, and templates.

## Recommended safety checks before upload

Before committing the legacy archive or dump, remove or replace sensitive values when possible:

- database passwords;
- API tokens;
- SMTP credentials;
- private keys;
- production user personal data that is not required for migration.

## Next migration steps

After the archive and dump are available in this repository, the migration work can proceed with:

1. auditing the legacy CMS structure;
2. documenting existing page types, modules, and database tables;
3. selecting the replacement stack;
4. designing the new schema and application architecture;
5. implementing the rebuilt site;
6. writing data migration scripts;
7. testing and preparing deployment instructions.
