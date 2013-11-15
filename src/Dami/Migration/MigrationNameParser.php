<?php

namespace Dami\Migration;

use Dami\Helper\StringHelper;

class MigrationNameParser
{
    private $migrationName;
    private $action;
    private $actionObject;
    private $model;
    private $validActions = array('create', 'add', 'drop', 'remove');
    private $validActionObjects = array('table', 'column', 'index');

    /**
     * Sets a migration name.
     *
     * @param string $migrationName A migration name.
     *
     * @return void
     */
    public function setMigrationName($migrationName)
    {
        $this->migrationName = $migrationName;
        $migrationNameAsUnderscoreSrtring = StringHelper::underscore($migrationName);
        $items = explode('_', $migrationNameAsUnderscoreSrtring);
        if (count($items) >= 3) {
            $this->action = in_array($items[0], $this->validActions)
                ? $items[0]
                : null;            
            $this->actionObject = in_array($items[1], $this->validActionObjects)
                ? $items[1]
                : null;
            $this->model = str_replace($items[0] . '_' . $items[1] . '_', '', $migrationNameAsUnderscoreSrtring);
        }
    }

    /**
     * Gets an action name of migration.
     *
     * @return string Action name.
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Gets an object  of action.
     *
     * @return string Object action name.
     */
    public function getActionObject()
    {
        return $this->actionObject;
    }

    /**
     * Gets a model name of migration.
     *
     * @return string Model name.
     */
    public function getModel()
    {
        return $this->model;
    }
}
