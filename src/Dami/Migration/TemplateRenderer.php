<?php

namespace Dami\Migration;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;


class TemplateRenderer
{
	private $templateInitialization;

	/**
	 * Constructor.
	 * 
	 * @param TemplateInitialization $templateInitialization TemplateInitialization instance.
	 */
	public function __construct(TemplateInitialization $templateInitialization)
	{
		$this->templateInitialization = $templateInitialization;
	}

	/**
	 * Render template
	 * 
	 * @param  string $migrationName Migration name.
	 * 
	 * @return string Rendered tempalte.
	 */
	public function render($migrationName)
	{
		$loader = new FilesystemLoader(__DIR__ . '/views/%name%');

		$view = new PhpEngine(new TemplateNameParser(), $loader);

		$this->templateInitialization->setMigrationName($migrationName);

		return $view->render($this->getTemplatePath(), array(
			'migrationName' => $migrationName,
			'initUp' => $this->templateInitialization->getInitUp(),
			'initDown' => $this->templateInitialization->getInitDown(),
		));
	}

	/**
	 * Get template path.
	 * 
	 * @return string Template path.
	 */
	private function getTemplatePath()
	{
		return __DIR__ . '/Template.php';
	}
}