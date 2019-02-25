<?php
namespace EvoStorm\EvoStorm;

class StormConfig
{
	private $config;

	public function __construct()
	{
		$this->config = array(
			'images_formats' => '/.jpg/.jpeg/.png/.svg/',

			'privacy_policy' => 17,

			'shop_email_manager'       => 'scorn17@gmail.com',
			'shop_page_cart'           => 24,
			'shop_page_checkout_order' => 25,

			'user_page_lk'       => 26,
			'user_page_auth'     => 32,
			'user_page_recovery' => 33,
			'user_page_password' => 30,
			'user_page_orders'   => 29,
			'user_lk_menu'       => 27,
			'user_lk_menu_show_in' => array(
				26, 33, 30, 29
			),

			'catalog_categories'    => '7', //через запятую
			'catalog_images_folder' => 'assets/images/catalog/',

			'catalog_import_moysklad_user' => 'moysklad',
			'catalog_import_moysklad_pw'   => 'H9RaL7zq_usg_92Z43Ob',

			'catalog_nophoto' => 'tpl/img/nophoto.svg',

			'catalog_items_in_page' => 24,

			'product_status' => array(
				'1' => 'В наличии',
				'2' => 'Нет в наличии',
			),

			'product_prms_type' => array(
				'pricemod' => 'inputtext',
				'status'   => 'select',
				'content'  => 'richtext',
			),

			'product_prm_value_fields' => array(
				'id_value', 'value', 'hash',
				'prm1', 'prm2', 'prm3',
			),

			'product_order' => 'name, price NUM',
			'product_image_width'  => 350,
			'product_image_height' => 300,

			'category_prms' => array(
				'subcats'     => 'y',
				'products'    => 'last',
				'producttype' => 'y',
			),
			'category_order_by'     => 'menuindex',
			'category_image_width'  => 250,
			'category_image_height' => 200,
		);
	}

	public function get($prm)
	{
		return $this->config[$prm];
	}
}
