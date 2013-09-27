<?php echo '<?php' ?>

use Dami\Migration\Api\MigrationApi;

class <?php echo $migrationName ?>Migration extends MigrationApi
{
    public function up()
    {
        <?php echo $initUp ?>
        <?php echo "\n" ?>
    }

    public function down()
    {
        <?php echo $initDown ?>
        <?php echo "\n" ?>
    }
}
