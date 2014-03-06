<?php

use Dami\Migration\Api\MigrationApi;

class AddTableFooMigration extends MigrationApi
{
    public function up()
    {
        $this->createTable('foo')
            ->addBigIntegerColumn('foo_big_integer')
            ->addBinaryColumn('foo_binary')
            ->addBooleanColumn('foo_boolean')
            ->addDateColumn('foo_date')
            ->addDateTimeColumn('foo_datetime')
            ->addDecimalColumn('foo_decimal')
            ->addFloatColumn('foo_float')
            ->addIntegerColumn('foo_integer')
            ->addSmallIntegerColumn('foo_small_integer')
            ->addStringColumn('foo_string')
            ->addTextColumn('foo_text')
            ->addTimeColumn('foo_time')
        ;
    }

    public function down()
    {
        $this->dropTable('foo');
    }
}
