<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\AfterScenarioScope;

use Symfony\Component\Finder\Finder;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    private $dir;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there is :dir directory in root of the application
     */
    public function thereIsDirectoryInRootOfTheApplication($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $this->dir = $dir;
    }

    /**
     * @Given I am in a root directory of application
     */
    public function iAmInARootDirectoryOfApplication()
    {

    }

    /**
    * @Given I run :command
    */
    public function iRun($command)
    {
        exec($command);
    }

    /**
     * @Then I see the file witch name contains :fileName
     */
    public function iSeeTheFileWitchNameContains($fileName)
    {
        $this->fileName = $fileName;
        $pattern =  getcwd() . '/' . $this->dir;
        if (count((new Finder())->files()->in($pattern)->name('*' . $fileName)) === 0) {
            throw new \Exception('File does not exist');
        }
    }

    /**
     * @Then this file body contains:
     */
    public function thisFileBodyContains(PyStringNode $string)
    {
        $pattern =  getcwd() . '/' . $this->dir;
        $content = '';
        foreach ((new Finder())->files()->in($pattern)->name('*' . $this->fileName) as $file) {
            $content = $file->getContents();
        }
        if (strpos($content, $string->getRaw()) === false) {
            throw new \Exception('File contains wrong data');
        }
    }

    /**
      * @AfterScenario
      */
     public function deleteMigrations(AfterScenarioScope $scope)
     {
         $pattern =  getcwd() . '/' . $this->dir . '/*.php';
         array_map('unlink', glob($pattern));
     }
}
