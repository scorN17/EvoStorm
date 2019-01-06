<?php
namespace EvoStorm\EvoStorm;

class Storm extends \EvolutionCMS\Core
{
	public function __construct()
	{
		return parent::__construct();
	}
	
	public function getDocumentIdentifier($method)
	{
		$url = parent::getDocumentIdentifier($method);
		// if ($method == 'alias') {
		// 	$url = $this->c->parseURLParams($url);
		// 	if ($this->sendErrorPage) {
		// 		$this->sendErrorPage();
		// 	}
		// }
		return $url;
	}
}
