<?php
namespace EvoStorm\EvoStorm;

class CoreParasite extends \EvolutionCMS\Core
{
	public $esm;

	public $sendStrictURI = true;
	public $sendErrorPage = false;

	public function __construct()
	{
		$res = parent::__construct();

		$this->esm = new Storm;

		return $res;
	}
	
	public function getDocumentIdentifier($method)
	{
		$url = parent::getDocumentIdentifier($method);
		if ($method == 'alias') {
			$url = $this->esm->parseURL($url);
			if ($this->sendErrorPage) {
				$this->sendErrorPage();
			}
		}
		return $url;
	}
	
	public function sendStrictURI()
	{
		if ( ! $this->sendStrictURI) {
			return;
		} else {
			return parent::sendStrictURI();
		}
	}
}
