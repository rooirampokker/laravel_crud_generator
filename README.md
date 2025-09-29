## Description
- Creates all the files required for a full CRUD+ endpoint for a whitelisted RESTful API.
- Templates can be added or updated in this projects `resources/templates` directory.
- The following files are either created up updated as necessary:

**ROUTING**
 - [UPDATE] routes/api_`<api_version>`.php
 - [CREATE] routes/`<model>`.php

**BUSINESS LOGIC**
- [CREATE] app/Models/`<Model>`.php
- [CREATE] app/Http/Controllers/api/`<api_version>`/`<Model>`Controller.php
- [CREATE] app/Repository/api/`<api_version>`/`<Model`>`Repository.php
- [CREATE] app/Repository/Interfaces/api/`<api_version>`/`<Model>`RepositoryInterface.php

- **TODO:**
  - [CREATE] app/Services/api/`<api_version>`/`<Model>`Service.php
  - [CREATE] app/Services/Interfaces/api/`<api_version>`/`<Model>`Service.php
  - [CREATE] app/Services/Factories/api/`<api_version>`/`<Model>`ServiceFactory.php
  - [CREATE] app/Services/Factories/Interfacesapi/`<api_version>`/`<Model>`Service.php

**VALIDATION**
- [CREATE] app/Http/Requests/`<Model>`StoreRequest.php
- [CREATE] app/Http/Requests/`<Model>`UpdateRequest.php
- [CREATE] app/Policies/`<Model>`Policy.php
- [CREATE] app/Policies/Interfaces/`<Model>`PolicyInterface.php
- [UPDATE] config/role_permissions.php

RESPONSE FORMATTING
- [CREATE] app/Http/Resources/api/`<api_version>`/`<Model>`Resource.php

UNIT TESTS
- [CREATE] tests/Feature/`<Model>`Test.php

~~DOCUMENTATION~~
~~- [CREATE] resources/yml/api/<api_version>/<models>/<models>.endpoints.yml~~
~~- [CREATE] resources/yml/api/<api_version>/schemas/<models>.schema.yml~~

LANGUAGE
- [CREATE] resources/lang/en/`<models>`.php

DATABASE
- [CREATE] database/migrations/0000_00_000000_create_`<model>`_table.php
- [CREATE] database/factories/`<Model>`Factory.php
```
## Installation
Clone this repo into the same root directory that contains your host laravel application.

Include the package into your host laravel application's `composer.json` file with:
```json
...  
  "require-dev": {
        ...
        "support/laravel-crud-generator": "@dev",
        ...
    }
    "repositories": [
      {
        "type": "path",
        "url": "../laravel-crud-generator",
        "options": {
          "symlink": true
        }
      }
    ]
...
```
## Usage

- Simply run from CLI and provide input at the prompts:
```bash
php artisan laravel-crud-generator
```
- Double-check files created/touched and update as necessary, especially the migration, Store and UpdateRequest files which don't consider the Model fields specified during setup.
- Run migrations:
```bash
php artisan migrate
```

## Credits
- [Leslie Albrecht](https://github.com/leslie-eventogy)
