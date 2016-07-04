<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. 
*
*  @author    uhuPage <support@uhupage.com>
*  @copyright 2007-2015 uhuPage
*  @license   GNU General Public License version 2
*/

if (!defined('_PS_VERSION_'))
	exit;

class Uhunewproducts extends Module
{
	public function __construct()
	{
		$this->name = 'uhunewproducts';
		$this->tab = 'others';
		$this->version = '1.0.6';
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'New products block';
		$this->description = $this->l('Displays a block featuring your store&#039;s newest products.');
		$this->init();
	}

	protected function init()
	{
		$this->mod_name = 'new';
		$this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));

		$themes_styles = explode('|', Configuration::get('uhu_Theme_Styles'));
		if (Configuration::get('PS_UHU_LIVE_DEMO') == 1 || Configuration::get('PS_UHU_DEVELOPER_MODE') == 1)
		{
			if (Tools::getValue('theme_style') && Tools::getValue('live_configurator') == 1)
				$themestyle = Tools::getValue('theme_style');
			else
				$themestyle = Configuration::get('PS_UHU_STYLE');
			$this->styleid = array_search($themestyle, $themes_styles);
		}
		else
			$this->styleid = array_search(Configuration::get('PS_UHU_STYLE'), $themes_styles);
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('home') && $this->registerHook('displayBottomBanner'));
	}

	public function hookdisplayBottomBanner($params)
	{
		$hook = 52;
		if ($this->mod_value[$hook] == 'bottom_banner')
			return $this->displayHook($params);
	}

	public function hookHome($params)
	{
		$hook = 52;
		if ($this->mod_value[$hook] <> 'bottom_banner')
			return $this->displayHook($params);
	}

	public function displayHook($params)
	{
		$html = '';

		$oneonly = 13;
		$langs = explode('|', $this->mod_value[$oneonly]);
		$lang_iso = Language::getIsoById($params['cookie']->id_lang);
		$this->lang_id = 0;
		if (array_search($lang_iso, $langs) <> false)
			$this->lang_id = array_search($lang_iso, $langs);

		$block_title = 49;
		$block_titles = explode('|', $this->mod_value[$block_title]);
		if (isset($block_titles[$this->lang_id]))
			$title = $block_titles[$this->lang_id];
		else
			$title = $block_titles[0];
		$this->smarty->assign('block_title', $title);

		$block_cart = 50;
		$block_carts = explode('|', $this->mod_value[$block_cart]);
		if (isset($block_carts[$this->lang_id]))
			$cart = $block_carts[$this->lang_id];
		else
			$cart = $block_carts[0];
		$this->smarty->assign('block_cart', $cart);

		$enables = explode('|', $this->mod_value[7]);
		if (isset($enables[$this->styleid]))
			$enable_new = $enables[$this->styleid];
		else
			$enable_new = $enables[0];
		if ($enable_new == 'yes')
			$html .= $this->hookHomeStyle1($params);

		$enables = explode('|', $this->mod_value[8]);
		if (isset($enables[$this->styleid]))
			$enable_new = $enables[$this->styleid];
		else
			$enable_new = $enables[0];
		if ($enable_new == 'yes')
			$html .= $this->hookHomeStyle2($params);

		$enables = explode('|', $this->mod_value[9]);
		if (isset($enables[$this->styleid]))
			$enable_new = $enables[$this->styleid];
		else
			$enable_new = $enables[0];
		if ($enable_new == 'yes')
		{
			$responsive = 35;
			if ($this->mod_value[$responsive] == '')
				$html .= $this->hookHomeStyle3($params);
			else
				$html .= $this->hookHomeStyle4($params);
		}

		return $html;
	}

	public function hookHomeStyle1($params)
	{
		$totalgrid = 0;
		$total_grids = explode('|', $this->mod_value[$totalgrid]);
		if (isset($total_grids[$this->styleid]))
			$totalgrid = $total_grids[$this->styleid];
		else
			$totalgrid = $total_grids[0];
		$this->smarty->assign('totalgrid', $totalgrid);

		$title_pos = 5;
		$csstype = 11;
		$this->smarty->assign(array(
			'title_pos' => $this->mod_value[$title_pos],
			'csstype' => $this->mod_value[$csstype]
		));

		$adgrid = 14;
		if ($this->mod_value[$adgrid] <> '' || $this->mod_value[$adgrid] <> '0')
		{
			$adv_number = 3;

			$zoom = 12;
			$zoom = explode('|', $this->mod_value[$zoom]);
			$picgrid = 16;
			$new_image = array(17,18,19);
			$newlink = 20;

			$adv_grid = array();
			$grids = explode(',', $this->mod_value[$picgrid]);
			if (count($grids) == 1)
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_grid[$i] = $grids[0];
			}
			else
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_grid[$i] = $grids[$i];
			}

			$adv_link = array();
			$links = explode(',', $this->mod_value[$newlink]);
			if (count($links) == 1)
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[0];
			}
			else
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[$i];
			}

			$this->smarty->assign(array(
				'adv_number' => $adv_number,
				'adgrid' => $this->mod_value[$adgrid],
				'zoom' => $zoom[0]
			));

			for ($i = 0; $i < $adv_number; $i++)
			{
				$this->smarty->assign('adv_link_'.$i, $adv_link[$i]);
				$this->smarty->assign('adv_grid_'.$i, $adv_grid[$i]);
			}

			$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_.'uhuthemesetting/views/img/'.$this->mod_name.'/';

			for ($i = 0; $i < $adv_number; $i++)
			{
				$advimgs = explode('|', $this->mod_value[$new_image[$i]]);
				if (isset($advimgs[$this->lang_id]))
					$adv = $advimgs[$this->lang_id];
				else
					$adv = $advimgs[0];

				if ($adv <> '')
				{
					if (strstr($adv, 'http://') <> '')
						$this->smarty->assign('adv_image_'.$i, $adv);
					else
						$this->smarty->assign('adv_image_'.$i, $imgurl.$adv);
				}
				else
					$this->smarty->assign('adv_image_'.$i, '');
			}
		}

		$pdgrid = 15;
		if ($this->mod_value[$pdgrid] <> '' || $this->mod_value[$pdgrid] <> '0')
		{
			$productgrid = 1;
			$nb_items_per_line = 2;
			$totalproducts = 3;
			$icon_type = 4;

			$description = 33;
			$desc_length = 34;
			$rating = 37;

			$zoom = 12;
			$csstype = explode('|', $this->mod_value[$zoom]);
			if (isset($csstype[1]))
			{
				$effects = explode('^', $csstype[1]);
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$effect = explode(':', $effects[$i]);
					$this->smarty->assign('animate_'.$i, $effect[0]);
					$this->smarty->assign('delay_'.$i, $effect[1]);
				}
			}
			else
			{
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$this->smarty->assign('animate_'.$i, '');
					$this->smarty->assign('delay_'.$i, '');
				}
			}

			$this->smarty->assign(array(
				'productgrid' => $this->mod_value[$productgrid],
				'icon_type' => $this->mod_value[$icon_type],
				'nb_items_per_line' => $this->mod_value[$nb_items_per_line],
				'pdgrid' => $this->mod_value[$pdgrid],
				'description' => $this->mod_value[$description],
				'desc_length' => $this->mod_value[$desc_length],
				'rating' => $this->mod_value[$rating]
				));

			$nb = $this->mod_value[$totalproducts];
			$grade = array();
			$products = Product::getNewProducts($params['cookie']->id_lang, 0, ($nb ? $nb : 10));
			if (Configuration::get('PRODUCT_COMMENTS_MODERATE') == '1')
			{
				foreach ($products as $product)
				{
					$average = $this->getAverageGrade((int)$product['id_product']);
					$grade[$product['id_product']] = round($average['grade']);
				}
				$this->smarty->assign('grade', $grade);
			}
			$this->smarty->assign('products', $products);
		}

		return $this->display(__FILE__, 'uhutj9501.tpl');
	}

	public function hookHomeStyle2($params)
	{
		$totalgrid = 0;
		$total_grids = explode('|', $this->mod_value[$totalgrid]);
		if (isset($total_grids[$this->styleid]))
			$totalgrid = $total_grids[$this->styleid];
		else
			$totalgrid = $total_grids[0];
		$this->smarty->assign('totalgrid', $totalgrid);

		$title_pos = 5;
		$csstype = 11;
		$this->smarty->assign(array(
			'title_pos' => $this->mod_value[$title_pos],
			'csstype' => $this->mod_value[$csstype]
		));

		$all_category = 38;
		$cats = explode(',', $this->mod_value[$all_category]);
		$category_number = count($cats);

		$adgrid = 30;
		if ($this->mod_value[$adgrid] <> '' || $this->mod_value[$adgrid] <> '0')
		{
			$adv_number = $category_number;

			$zoom = 29;
			$zoom = explode('|', $this->mod_value[$zoom]);
			$newimage = 21;
			$newlink = 22;

			$adv_link = array();
			$links = explode(',', $this->mod_value[$newlink]);
			if (count($links) == 1)
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[0];
			}
			else
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[$i];
			}

			$this->smarty->assign(array(
				'adv_number' => $adv_number,
				'adgrid' => $this->mod_value[$adgrid],
				'zoom' => $zoom[0]
			));

			for ($i = 0; $i < $adv_number; $i++)
				$this->smarty->assign('adv_link_'.$i, $adv_link[$i]);

			$advimgs = array();
			$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_.'uhuthemesetting/views/img/'.$this->mod_name.'/';
			$advimgs = explode('|', $this->mod_value[$newimage]);
			for ($i = 0; $i < $adv_number; $i++)
			{
				if (isset($advimgs[$i]) && $advimgs[$i] <> '')
				{
					if (strstr($advimgs[$i], 'http://') <> '')
						$this->smarty->assign('adv_image_'.$i, $advimgs[$i]);
					else
						$this->smarty->assign('adv_image_'.$i, $imgurl.$advimgs[$i]);
				}
				else
					$this->smarty->assign('adv_image_'.$i, '');
			}
		}

		$pdgrid = 10;
		if ($this->mod_value[$pdgrid] <> '' || $this->mod_value[$pdgrid] <> '0')
		{
			$productgrid = 23;
			$nb_items_per_line = 24;
			$totalproducts = 25;
			$icon_type = 6;

			$description = 26;
			$desc_length = 27;
			$rating = 28;
			$cat_grids = 39;

			$zoom = 29;
			$csstype = explode('|', $this->mod_value[$zoom]);
			if (isset($csstype[1]))
			{
				$effects = explode('^', $csstype[1]);
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$effect = explode(':', $effects[$i]);
					$this->smarty->assign('animate_'.$i, $effect[0]);
					$this->smarty->assign('delay_'.$i, $effect[1]);
				}
			}
			else
			{
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$this->smarty->assign('animate_'.$i, '');
					$this->smarty->assign('delay_'.$i, '');
				}
			}

			$this->smarty->assign(array(
				'category_number' => $category_number,
				'productgrid' => $this->mod_value[$productgrid],
				'icon_type' => $this->mod_value[$icon_type],
				'nb_items_per_line' => $this->mod_value[$nb_items_per_line],
				'pdgrid' => $this->mod_value[$pdgrid],
				'description' => $this->mod_value[$description],
				'desc_length' => $this->mod_value[$desc_length],
				'rating' => $this->mod_value[$rating],
				'cat_grids' => $this->mod_value[$cat_grids]
				));

			$nb = $this->mod_value[$totalproducts];
			$grade = array();
			for ($i = 0; $i < $category_number; $i++)
			{
				$categoryid = $cats[$i];
				$category = new Category($categoryid, Configuration::get('PS_LANG_DEFAULT'));
				$products = $category->getProducts($params['cookie']->id_lang, 1, ($nb ? $nb : 10), 'id_product', 'DESC');
				$this->smarty->assign('products_'.$i, $products);

				if (Configuration::get('PRODUCT_COMMENTS_MODERATE') == '1')
				{
					foreach ($products as $product)
					{
						$average = $this->getAverageGrade((int)$product['id_product']);
						$grade[$product['id_product']] = round($average['grade']);
					}
					$this->smarty->assign('grade', $grade);
				}

				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT c.`id_category`, cl.`name`
					FROM `'._DB_PREFIX_.'category` c
					LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`)
					WHERE cl.`id_lang` = '.(int)$params['cookie']->id_lang.' AND cl.`id_category` = '.$categoryid.' 
					ORDER BY c.`position`');

				$this->smarty->assign('title_'.$i, $result['name']);
			}
		}

		$tpl = 53;
		if ($this->mod_value[$tpl] == 'uhutj9502')
			return $this->display(__FILE__, 'uhutj9502.tpl');
		else
			return $this->display(__FILE__, 'uhuproducts.tpl');
	}

	public function hookHomeStyle3($params)
	{
		$totalgrid = 0;
		$total_grids = explode('|', $this->mod_value[$totalgrid]);
		if (isset($total_grids[$this->styleid]))
			$totalgrid = $total_grids[$this->styleid];
		else
			$totalgrid = $total_grids[0];
		$this->smarty->assign('totalgrid', $totalgrid);

		$title_pos = 5;
		$csstype = 11;
		$this->smarty->assign(array(
			'title_pos' => $this->mod_value[$title_pos],
			'csstype' => $this->mod_value[$csstype]
		));

		$all_category = 48;
		$cats = explode(',', $this->mod_value[$all_category]);
		$category_number = count($cats);

		$adgrid = 40;
		if ($this->mod_value[$adgrid] <> '' || $this->mod_value[$adgrid] <> '0')
		{
			$adv_number = $category_number;

			$zoom = 41;
			$zoom = explode('|', $this->mod_value[$zoom]);
			$newimage = 31;
			$newlink = 32;

			$adv_link = array();
			$links = explode(',', $this->mod_value[$newlink]);
			if (count($links) == 1)
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[0];
			}
			else
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[$i];
			}

			$this->smarty->assign(array(
				'adv_number' => $adv_number,
				'adgrid' => $this->mod_value[$adgrid],
				'zoom' => $zoom[0]
			));

			for ($i = 0; $i < $adv_number; $i++)
				$this->smarty->assign('adv_link_'.$i, $adv_link[$i]);

			$advimgs = array();
			$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_.'uhuthemesetting/views/img/'.$this->mod_name.'/';
			$advimgs = explode('|', $this->mod_value[$newimage]);
			for ($i = 0; $i < $adv_number; $i++)
			{
				if (isset($advimgs[$i]) && $advimgs[$i] <> '')
				{
					if (strstr($advimgs[$i], 'http://') <> '')
						$this->smarty->assign('adv_image_'.$i, $advimgs[$i]);
					else
						$this->smarty->assign('adv_image_'.$i, $imgurl.$advimgs[$i]);
				}
				else
					$this->smarty->assign('adv_image_'.$i, '');
			}
		}

		$pdgrid = 42;
		if ($this->mod_value[$pdgrid] <> '' || $this->mod_value[$pdgrid] <> '0')
		{
			$productgrid = 43;
			$nb_items_per_line = 44;
			$totalproducts = 45;
			$icon_type = 46;

			$description = 35;
			$desc_length = 36;
			$rating = 47;
			$blockgrid = 51;
			$block_grids = explode('|', $this->mod_value[$blockgrid]);
			if (isset($block_grids[1]))
				$this->smarty->assign('blockgridtablet', $block_grids[1]);
			else
				$this->smarty->assign('blockgridtablet', $block_grids[0]);
			$this->smarty->assign('blockgrid', $block_grids[0]);

			$zoom = 41;
			$csstype = explode('|', $this->mod_value[$zoom]);
			if (isset($csstype[1]))
			{
				$effects = explode('^', $csstype[1]);
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$effect = explode(':', $effects[$i]);
					$this->smarty->assign('animate_'.$i, $effect[0]);
					$this->smarty->assign('delay_'.$i, $effect[1]);
				}
			}
			else
			{
				for ($i = 0; $i < $this->mod_value[$nb_items_per_line]; $i++)
				{
					$this->smarty->assign('animate_'.$i, '');
					$this->smarty->assign('delay_'.$i, '');
				}
			}

			$this->smarty->assign(array(
				'category_number' => $category_number,
				'productgrid' => $this->mod_value[$productgrid],
				'icon_type' => $this->mod_value[$icon_type],
				'nb_items_per_line' => $this->mod_value[$nb_items_per_line],
				'pdgrid' => $this->mod_value[$pdgrid],
				'description' => $this->mod_value[$description],
				'desc_length' => $this->mod_value[$desc_length],
				'rating' => $this->mod_value[$rating]
				));

			$nb = $this->mod_value[$totalproducts];
			$grade = array();
			for ($i = 0; $i < $category_number; $i++)
			{
				$categoryid = $cats[$i];
				$category = new Category($categoryid, Configuration::get('PS_LANG_DEFAULT'));
				$products = $category->getProducts($params['cookie']->id_lang, 1, ($nb ? $nb : 10), 'id_product', 'DESC');
				$this->smarty->assign('products_'.$i, $products);

				if (Configuration::get('PRODUCT_COMMENTS_MODERATE') == '1')
				{
					foreach ($products as $product)
					{
						$average = $this->getAverageGrade((int)$product['id_product']);
						$grade[$product['id_product']] = round($average['grade']);
					}
					$this->smarty->assign('grade', $grade);
				}

				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT c.`id_category`, cl.`name`
					FROM `'._DB_PREFIX_.'category` c
					LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`)
					WHERE cl.`id_lang` = '.(int)$params['cookie']->id_lang.' AND cl.`id_category` = '.$categoryid.' 
					ORDER BY c.`position`');

				$this->smarty->assign('title_'.$i, $result['name']);
			}
		}

		return $this->display(__FILE__, 'uhutj9503.tpl');
	}

	public function hookHomeStyle4($params)
	{
		$totalgrid = 36;
		$total_grids = explode(':', $this->mod_value[$totalgrid]);
		if (isset($total_grids[$this->styleid]))
			$totalgrid = $total_grids[$this->styleid];
		else
			$totalgrid = $total_grids[0];
		$module_grids = explode('|', $totalgrid);
		if (isset($module_grids[2]))
			$mgrid3 = $module_grids[2];
		else
			$mgrid3 = $module_grids[0];
		if (isset($module_grids[1]))
			$mgrid2 = $module_grids[1];
		else
			$mgrid2 = $module_grids[0];
		$mgrid1 = $module_grids[0];
		$this->smarty->assign('mgrid1', $mgrid1);
		$this->smarty->assign('mgrid2', $mgrid2);
		$this->smarty->assign('mgrid3', $mgrid3);

		$title_pos = 5;
		$csstype = 11;
		$this->smarty->assign(array(
			'title_pos' => $this->mod_value[$title_pos],
			'csstype' => $this->mod_value[$csstype]
		));

		$all_category = 48;
		$cats = explode(',', $this->mod_value[$all_category]);
		$category_number = count($cats);

		$adgrid = 40;
		if ($this->mod_value[$adgrid] <> '' || $this->mod_value[$adgrid] <> '0')
		{
			$adv_number = $category_number;

			$zoom = 41;
			$zoom = explode('|', $this->mod_value[$zoom]);
			$newimage = 31;
			$newlink = 32;

			$adv_link = array();
			$links = explode(',', $this->mod_value[$newlink]);
			if (count($links) == 1)
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[0];
			}
			else
			{
				for ($i = 0; $i < $adv_number; $i++)
					$adv_link[$i] = $links[$i];
			}

			$this->smarty->assign(array(
				'adv_number' => $adv_number,
				'adgrid' => $this->mod_value[$adgrid],
				'zoom' => $zoom[0]
			));

			for ($i = 0; $i < $adv_number; $i++)
				$this->smarty->assign('adv_link_'.$i, $adv_link[$i]);

			$advimgs = array();
			$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_.'uhuthemesetting/views/img/'.$this->mod_name.'/';
			$advimgs = explode('|', $this->mod_value[$newimage]);
			for ($i = 0; $i < $adv_number; $i++)
			{
				if (isset($advimgs[$i]) && $advimgs[$i] <> '')
				{
					if (strstr($advimgs[$i], 'http://') <> '')
						$this->smarty->assign('adv_image_'.$i, $advimgs[$i]);
					else
						$this->smarty->assign('adv_image_'.$i, $imgurl.$advimgs[$i]);
				}
				else
					$this->smarty->assign('adv_image_'.$i, '');
			}
		}

		$pdgrid = 42;
		if ($this->mod_value[$pdgrid] <> '' || $this->mod_value[$pdgrid] <> '0')
		{
			$responsive = 35;
			if ($this->mod_value[$responsive] == '')
				$this->mod_value[$responsive] = '4|4|2';
			$responsive = explode('|', $this->mod_value[$responsive]);
			$this->smarty->assign('responsive1', $responsive[0]);
			$this->smarty->assign('responsive2', $responsive[1]);
			$this->smarty->assign('responsive3', $responsive[2]);

			$zoom = 41;
			$csstype = explode('|', $this->mod_value[$zoom]);

			$this->smarty->assign(array(
				'category_number' => $category_number,
				));

			$productgrid = 43;
			$this->smarty->assign('productgrid', $this->mod_value[$productgrid]);

			$totalproducts = 45;
			$nb = $this->mod_value[$totalproducts];
			$grade = array();
			for ($i = 0; $i < $category_number; $i++)
			{
				$categoryid = $cats[$i];
				$category = new Category($categoryid, Configuration::get('PS_LANG_DEFAULT'));
				$products = $category->getProducts($params['cookie']->id_lang, 1, ($nb ? $nb : 10), 'id_product', 'DESC');
				$this->smarty->assign('products_'.$i, $products);

				if (Configuration::get('PRODUCT_COMMENTS_MODERATE') == '1')
				{
					foreach ($products as $product)
					{
						$average = $this->getAverageGrade((int)$product['id_product']);
						$grade[$product['id_product']] = round($average['grade']);
					}
					$this->smarty->assign('grade', $grade);
				}

				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
					SELECT c.`id_category`, cl.`name`
					FROM `'._DB_PREFIX_.'category` c
					LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`)
					WHERE cl.`id_lang` = '.(int)$params['cookie']->id_lang.' AND cl.`id_category` = '.$categoryid.' 
					ORDER BY c.`position`');

				$this->smarty->assign('title_'.$i, $result['name']);
			}
		}

		return $this->display(__FILE__, 'uhutj9504.tpl');
	}

	public static function getAverageGrade($id_product)
	{
		$validate = Configuration::get('PRODUCT_COMMENTS_MODERATE');

		return Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
		SELECT (SUM(pc.`grade`) / COUNT(pc.`grade`)) AS grade
		FROM `'._DB_PREFIX_.'product_comment` pc
		WHERE pc.`id_product` = '.(int)$id_product.'
		AND pc.`deleted` = 0'.
		($validate == '1' ? ' AND pc.`validate` = 1' : ''));
	}
}