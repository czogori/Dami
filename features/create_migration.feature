Feature: Create a new migration
    We need to be able to create a new migration.

    Background:
        Given I am in a root directory of application
        And there is "migrations" directory in root of the application

    Scenario:
        Given I run "bin/dami create AddTableFoo --env=test"
        Then I see the file with name contains "add_table_foo.php"
        And this file body contains:
        """
        <?php
        use Dami\Migration\Api\MigrationApi;

        class AddTableFooMigration extends MigrationApi
        {
            public function up()
            {
                $this->createTable('foo');
            }

            public function down()
            {
                $this->dropTable('foo');
            }
        }

        """
