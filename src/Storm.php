<?php
namespace EvoStorm\EvoStorm;

use \EvolutionCMS\ServiceProvider;

class Storm
{
	protected $modx;

	public $esc;

	public $realURL;
	public $modxURL;
	public $getPrms;
	public $catalogID = false;
	public $catalogPrms = array();
	public $catalogPrmsURL;

	public function __construct()
	{
		$this->modx = evolutionCMS();


		$this->esc = new StormConfig;
	}
	
	public function parseURL($url)
	{
		$this->realURL = $url;
		$url           = trim($url, '/');
		$modx_url      = $url;

		$this->getPrms = $_SERVER['REQUEST_URI'];
		$this->getPrms = str_replace('/'.$url.'/', '', $this->getPrms);

		$pos = strrpos($url, '/_');

		if ($pos === false) {
			$this->modxURL = $modx_url .'/';
			return $this->modxURL;
		}

		$modx_url = substr($url, 0, $pos);
		$params = substr($url, $pos+2);
		$params = trim($params, '/');
		$this->catalogPrmsURL = $params;
		$this->modx->sendStrictURI = false;

		if ( ! $params) {
			$this->modx->sendErrorPage = true;
			return;
		}

		$params = explode('/', $params);
		if ( ! is_array($params)) {
			$this->modx->sendErrorPage = true;
			return;
		}

		$cid   = false;
		$p_max = 0;
		$pg    = false;
		$prms  = false;
		foreach ($params AS $prm) {
			if ($cid || $pg) {
				$this->modx->sendErrorPage = true;
				return;
			}
			
			if (preg_match("/^i-[0-9]+$/", $prm)) {
				$cid = intval(substr($prm, 2));
				$cid = $cid ? $cid : false;
				$this->catalogID = $cid;

			} elseif (preg_match("/^p-[0-9]+$/", $prm)) {
				$pg = intval(substr($prm, 2));
				$pg = $pg ? $pg : 1;
				$this->pageNum = $pg;

			} elseif (preg_match("/^([0-9]+-)+[0-9]+$/", $prm)) {
				$prm = explode('-', $prm);
				$pid = array_shift($prm);

				$pi_max= 0;
				foreach ($prm AS $prmi) {
					if ($prmi > $pi_max) {
						$pi_max = $prmi;
					} else {
						$this->modx->sendErrorPage = true;
						return;
					}
					$this->catalogPrms[$pid][$prmi] = $prmi;
				}

				if ($pid > $p_max) {
					$p_max = $pid;
				} else {
					$this->modx->sendErrorPage = true;
					return;
				}

				$prms = true;

			} else {
				$this->modx->sendErrorPage = true;
				return;
			}
		}

		$this->modxURL = $modx_url .'/';
		return $this->modxURL;
	}
}
