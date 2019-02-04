[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fmastercad%2FsymfonyOneToManyExample.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Fmastercad%2FsymfonyOneToManyExample?ref=badge_shield)

oneToMany
=========

A Symfony project created on April 8, 2017, 9:50 pm.

Steps:
======
- symfony new oneToMany
- cd oneToMany
- composer.phar require symfony/symfony
- php bin/console server:start
- sudo apt-get install php7.0-sqlite3 php7.0-xml
- symfony new oneToMany
- composer.phar require doctrine/doctrine-migrations-bundle
- php bin/console doctrine:database:create
- eintragen des neuen paketes in den AppKernel "new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle()" unter registerBundles()
- entities für die tabellen anlegen:
    - php bin/console doctrine:generate:entity --no-interaction \
          --entity="AppBundle:Category" \
          --fields="name:string(255)"
    - php bin/console doctrine:generate:entity --no-interaction \
          --entity="AppBundle:Product" \
          --fields="name:string(255)"
    - category in product bekannt machen:
        /**
         * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
         * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
         */
        private $category;
    - products in category ermöglichen:
        /**
         * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
         */
        private $products;

        public function __construct()
        {
            $this->products = new ArrayCollection();
        }
    - übrige getter und setter in den entities anlegen:
        - php bin/console doctrine:generate:entities AppBundle
- php bin/console doctrine:migrations:diff => erstellt einen ordner app/DoctrineMigrations und darin eine erste versionierungsdatei
- tabellen migrieren/erstellen: php bin/console doctrine:migrations:migrate
- ansehen des migrations status: php bin/console doctrine:migrations:status
- forms anlegen:
    - php bin/console doctrine:generate:form AppBundle:Product
    - php bin/console doctrine:generate:form AppBundle:Category


Links:
======
Doctrine Migration:
https://knpuniversity.com/screencast/symfony-doctrine/migrations

Associations mit Doctrine
http://symfony.com/doc/current/doctrine/associations.html

ManyToMany stackoverflow
http://stackoverflow.com/questions/18242544/symfony2-try-to-insert-manytomany-relation-before-insert-one-part-of-both-sides

Troubleshooting:
================
beim ausführen von composer.phar require "doctrine/migrations:1.0.*@dev" kam ständig
[RuntimeException]
   An error occurred when executing the "'cache:clear --no-warmup'" command:

     [Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException]
     You have requested a non-existent parameter "database_driver". Did you mean
      one of these: "database_name", "database_user"?

-> das problem ist hier, das database_driver nicht in **parameters.yml.dist** enthalten ist und damit unbekannt ist.

- das selbe problem mit **database_path**


beim initialen migrieren kam folgender fehler:
"No mapping information to process"
-> es mussten erst entities angelegt werden, die zum erstellen bzw. migrieren benutzt werden

- beim starten der app kam folgender fehler:
    "Uncaught PHP Exception Doctrine\DBAL\Exception\DriverException: "An exception occured in driver: could not find driver" at /media/DATA/Projekte/Webserver/Projekte/symfony3/oneToMany/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/AbstractSQLiteDriver.php line 85 "
    - der server musste nach installation der pdo klassen für sqlite neu gestartet werden:
        - php bin/console server:stop
        - php bin/console server:start

- es kam immer der fehler
    "Catchable Fatal Error: Object of class AppBundle\Entity\Category could not be converted to string"
    - geändert mit
    public function __toString() {
        return $this->getName();
    }


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fmastercad%2FsymfonyOneToManyExample.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fmastercad%2FsymfonyOneToManyExample?ref=badge_large)