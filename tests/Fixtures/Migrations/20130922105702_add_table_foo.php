<?php

use Dami\Migration\Api\MigrationApi;

class AddTableFooMigration extends MigrationApi
{
    public function up()
    {
        $this->createTable('foo')
            ->addStringColumn('test', array('not_null' => true))
        ;
    }

    public function down()
    {
        $this->dropTable('foo');
    }
}
