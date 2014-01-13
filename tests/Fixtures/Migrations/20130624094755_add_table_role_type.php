<?php

use Dami\Migration\Api\MigrationApi;

class AddTableRoleTypeMigration extends MigrationApi
{
    public function up()
    {
        $this->createTable('role_type')
            ->addStringColumn('name', array('not_null' => true))
            ->addTimestamps();
    }

    public function down()
    {
        $this->dropTable('role_type');
    }
}
