<?php

use Dami\Migration\Api\MigrationApi;

class AddTableFooMigration extends MigrationApi
{
    public function up()
    {
        $this->createTable('foo')
            ->addStringColumn('test', array('not_null' => true))
            ->addIntegerColumn('role_type_id')
            ->addForeignKey('role_type', 'role_type_id')
        ;
    }

    public function down()
    {
        $this->dropTable('foo');
    }
}
