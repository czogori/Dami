<?php echo '<?php' ?>

use Dami\Migration\Api\MigrationApi;

class <?php echo $migrationName ?>Migration extends MigrationApi
{
    public function up()
    {
        <?php echo $initUp . "\n" ?>
    }

    public function down()
    {
        <?php echo $initDown . "\n" ?>
    }
}
