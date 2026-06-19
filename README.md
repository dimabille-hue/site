# Site migration workspace

This repository is prepared as a clean workspace for migrating the legacy site from a custom CMS to a modern stack.

## What to upload

Please add the legacy project files to this repository, preferably using this structure:

```text
legacy/
  site.zip              # archive with the current site files
  database.sql          # database dump, or a compressed .sql.gz dump
```

If the archive is already unpacked, place it under:

```text
legacy/source/
```

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
