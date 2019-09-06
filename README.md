# CommerceTools migrations
This bundle brings the power of migrations to CommerceTools. You can define new migrations files which will be executed 
and check on composer install or update.

**This bundle is in development and not production ready!**

```console
$ composer require best-it/commercetools-migrations-bundle
```

Configuration
---------------------------
There is a small configuration you have to complete until you can start. The bundle needs a client for your CommerceTools
stage and a path where it can find your migrations.

```yml
migrations_bundle:
    
    # Your CommerceTools client service id                                              # Required
    client: 'my_project.commercetools.client'      

    # Your path where migrations can be found                                           # Required
    path: '%project_root%/src/Migrations'

    # Namespace of your migration files / field must be able to autoload                # Optional
    namespace: 'App\Migrations'

    # Name of the custom object container where we save executed migrations             # Optional
    container_name: 'migrations'

    # Template to use for new migrations                                                # Optional
    template: 'migrations'
```

Create new migration file
---------------------------
You can execute a command for creating a new migration file template. The command will print the path of the new file.

```bash
$ php bin/console commercetools:migrations:create
> New migration file created: `src/Migrations/Version20190325195150.php`
```

Implement migration file
---------------------------
After creation a new file, you can use the `up` and `down` methods for implement the migration you want to execute.
The CommerceTools client will be injected automatically.

```php
<?php

declare(strict_types=1);

namespace App\Migrations;

use BestIt\CommerceTools\Migrations\AbstractMigration;
use Commercetools\Core\Client;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190325195150 extends AbstractMigration
{
    public function up(Client $client): void
    {
        // this up() migration is auto-generated, please modify it to your needs
    }

    public function down(Client $client): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
```

Apply migration
---------------------------
Just execute the migrate command for starting the migrations. The command will fetch all already executed migrations
and match this with all migrations defined in your migration path. All non applied migration will be sort by version
and successively executed. Please beware, that unless to database migrations, there are no transactions. There is no way to revert
when one of your migrations fails and leave corrupt data. Please test your migrations before it goes to production. 

After apply, the migration will be marked as "migrated" into CommerceTools due a custom object.

```bash
$ php bin/console commercetools:migrations:migrate
> Found new migration: 20190325195150
> Apply: 20190325195150
> Mark: 20190325195150
> Found new migration: 20190405084411
> Apply: 20190405084411
> Mark: 20190405084411
> Found new migration: 20190906104212
> Apply: 20190906104212
> Mark: 20190906104212
```

For testing purpose, you can run this command with `--dry-run`. It only checks which migrations would apply:
```bash
$ php bin/console commercetools:migrations:migrate --dry-run
```

**Notice**: Currently is only the `up` migrations supported.

Auto migration
---------------------------
You can add the migrate command to your `composer.json`. This will effect that on every install or update all migrations
will be checked and missing applied. 

```json
{
    "scripts": {
        "auto-scripts": {
            "commercetools:migrations:migrate": "symfony-cmd"
        },
        "post-install-cmd": [
            "@auto-scripts"
        ],
        "post-update-cmd": [
            "@auto-scripts"
        ]
    }
}
```