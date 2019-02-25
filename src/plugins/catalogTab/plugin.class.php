<?php
include_once(MODX_BASE_PATH.'assets/lib/SimpleTab/plugin.class.php');
use \SimpleTab\Plugin;

class stormCatalogTabPlugin extends Plugin
{
	public $table      = '_catalog';
	public $pluginName = 'StormCatalogTab';
	public $tpl        = 'core/vendor/evostorm/evostorm/src/editor/view/tab.tpl';
	public $emptyTpl   = 'core/vendor/evostorm/evostorm/src/editor/view/tab_empty.tpl';

	public function getTplPlaceholders()
	{
		$ph = array(
			'tabName' => 'Предложения',
		);
		return array_merge($this->params, $ph);
	}

	public function createTable()
	{
		return true;
	}
}
