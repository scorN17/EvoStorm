<?php
namespace EvoStorm\EvoStorm;

use \EvolutionCMS\ServiceProvider;

class EvoStormServiceProvider extends ServiceProvider
{
	protected $namespace = 'ESM';

	public function register()
	{
		$this->loadSnippetsFrom(
			__DIR__ . '/snippets/',
			$this->namespace
		);
		
		$this->loadChunksFrom(
			__DIR__ . '/chunks/',
			$this->namespace
		);
	}
}
