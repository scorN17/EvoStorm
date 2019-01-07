<?php
namespace EvoStorm\EvoStorm;

class Storm extends \EvolutionCMS\Core
{
	public $esc;

	public function __construct()
	{
		$res = parent::__construct();

		$this->esc = new StormConfig($this);

		return $res;
	}
	
	public function getDocumentIdentifier($method)
	{
		$url = parent::getDocumentIdentifier($method);
		if ($method == 'alias') {
			$url = $this->ES_parseURL($url);
			if ($this->sendErrorPage) {
				$this->sendErrorPage();
			}
		}
		return $url;
	}
	
	public function ES_parseURL($url)
	{
		$this->realURL = $url;
		$url           = trim($url, '/');
		$modx_url      = $url;

		$this->gets = $_SERVER['REQUEST_URI'];
		$this->gets = str_replace('/'.$url.'/', '', $this->gets);

		$pos = strrpos($url, '/_');
		if ($pos !== false) {
			$modx_url = substr($url, 0, $pos);
			$params = substr($url, $pos+2);
			$params = trim($params, '/');
			$this->catalogParamsURL = $params;
			$this->sendStrictURI = false;

			if ( ! $params) {
				$this->sendErrorPage = true;
				return;
			}
		}

		if ($modx_url) {
			$modx_url_arr  = explode('/', $modx_url);
			$catalog_alias = end($modx_url_arr);

			if (preg_match("/^(.*)-i([0-9]*)$/", $catalog_alias, $matches) === 1) {
				$catalog_alias   = $matches[1];
				$catalog_alias_q = $this->db->escape($catalog_alias);
				$id_product      = $matches[2];
				$id_product_q    = $this->db->escape($id_product);

				$id_catalog = $this->db->getValue("SELECT ct.id
					FROM ".$this->_T_PRODUCT." pt
					INNER JOIN {$this->_T_C} ct ON ct.id=pt.id_catalog
					WHERE
						pt.id='{$id_product_q}' AND
						ct.alias='{$catalog_alias_q}' AND
						pt.enabled='y'
					LIMIT 1");
				if ($id_catalog) {
					$this->productId    = $id_product;
					$this->catalogId    = $id_catalog;
					$this->catalogAlias = $catalog_alias;
				}

			} else {
				$catalog_alias_q = $this->db->escape($catalog_alias);

				$id_catalog = $this->db->getValue("SELECT id
					FROM {$this->_T_C}
					WHERE alias='{$catalog_alias_q}' LIMIT 1");
				if ($id_catalog) {
					$this->catalogId    = $id_catalog;
					$this->catalogAlias = $catalog_alias;
				}
			}

			if ($this->catalogId) {
				array_pop($modx_url_arr);
				$modx_url = implode('/', $modx_url_arr);
				$this->sendStrictURI = false;
			}
		}

		if ($params) {
			$params = explode('/', $params);
			if (is_array($params)) {
				$cid = $this->catalogId ? true : false;
				$i = $this->productId ? true : false;
				$p_max = 0;
				$pg = false;
				$st = false;
				foreach ($params AS $prm) {
					if (preg_match("/^p-[0-9]+$/", $prm)) {
						if ($i || $pg) {
							$this->sendErrorPage = true;
							return;
						}
						$this->pageNum = intval(substr($prm, 2));
						$this->pageNum = $this->pageNum ? $this->pageNum : 1;
						$pg = true;

					} elseif (preg_match("/^([0-9]+-)+[0-9]+$/", $prm)) {
						$prm = explode('-', $prm);
						$pid = array_shift($prm);

						foreach ($prm AS $prmi) {
							$this->catalogParams[$pid][$prmi] = $prmi;
						}

						if ($pid > $p_max) {
							$p_max = $pid;
						} else {
							$this->sendErrorPage = true;
							return;
						}

						$foo_max= 0;
						foreach ($prm AS $row) {
							if ($row > $foo_max) {
								$foo_max = $row;
							} else {
								$this->sendErrorPage = true;
								return;
							}
						}

					} else {
						$this->sendErrorPage = true;
						return;
					}
				}
			}
		}

		$this->modxURL = $modx_url .'/';
		return $this->modxURL;
	}
}
