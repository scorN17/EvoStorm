<?php
namespace EvoStorm\EvoStorm;

use \EvolutionCMS\ServiceProvider;

class EvoStormServiceProvider extends ServiceProvider
{
	protected $namespace = 'ES';

	public function register()
	{
		$this->loadSnippetsFrom(
			__DIR__ . '/Catalog/snippets/',
			$this->namespace.'C'
		);
		
		$this->loadChunksFrom(
			__DIR__ . '/Catalog/chunks/',
			$this->namespace.'C'
		);
	}
}
