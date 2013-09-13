<?php

namespace Dami\Migration;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;


class TemplateRenderer
{
	private $templateInitialization;

	public function __construct(TemplateInitialization $templateInitialization)
	{
		$this->templateInitialization = $templateInitialization;
	}

	public function render($migrationName)
	{
		$loader = new FilesystemLoader(__DIR__ . '/views/%name%');

		$view = new PhpEngine(new TemplateNameParser(), $loader);

		$this->templateInitialization->setMigrationName($migrationName);

		return $view->render('/home/aj/projects/Dami/src/Dami/Migration/Template.php', array(
			'migrationName' => $migrationName,
			'initUp' => $this->templateInitialization->getInitUp(),
			'initDown' => $this->templateInitialization->getInitDown(),
		));
	}
}