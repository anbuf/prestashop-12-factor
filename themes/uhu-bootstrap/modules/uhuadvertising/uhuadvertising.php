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

class Uhuadvertising extends Module
{

	public function __construct()
	{
		$this->name = 'uhuadvertising';
		$this->tab = 'others';
		$this->version = '1.1.1';
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Advertising Block';
		$this->description = $this->l('Adds a block to display 1-6 advertising image.');
		$this->init();
	}

	protected function init()
	{
		$this->mod_name = 'advertising';
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
		if (!parent::install() ||
			!$this->registerHook('displayBanner') ||
			!$this->registerHook('displayNav') ||
			!$this->registerHook('top') ||
			!$this->registerHook('displayTopColumn') ||
			!$this->registerHook('displayTopLeft') ||
			!$this->registerHook('displayTopRight') ||
			!$this->registerHook('displayTopBanner') ||
			!$this->registerHook('home') ||
			!$this->registerHook('displayBottomLeft') ||
			!$this->registerHook('displayBottomRight') ||
			!$this->registerHook('displayBottomBanner') ||
			!$this->registerHook('displayFooterNav') ||
			!$this->registerHook('footer'))
			return false;
		return true;
	}

	public function displayHook($params, $pos)
	{
		$html = '';
		if ($pos == 0)
		{
			$display = 'no';
			if (strstr($this->mod_value[$pos], 'yes') <> '')
				$display = 'yes';
		}
		else if ($pos == 135)
		{
			$displays = explode('|', $this->mod_value[$pos]);
			if (isset($displays[1]) && $displays[1] == 'yes')
				$display = $displays[1];
			else
				$display = $displays[0];
		}
		else
			$display = $this->mod_value[$pos];
		if ($display == 'yes')
		{
			$enable_advertising = $pos + 1;
			$enables = explode('|', $this->mod_value[$enable_advertising]);
			if (isset($enables[$this->styleid]))
				$enable_advertising = $enables[$this->styleid];
			else
				$enable_advertising = $enables[0];
			if ($enable_advertising == 'yes')
			{
				$adv_number = 6 + $pos;
				$adv_number = $this->mod_value[$adv_number];

				$adv_image = array();
				$totalgrid = 2 + $pos;
				$adgrid = 3 + $pos;

				$owl = 4 + $pos;
				if (strstr($this->mod_value[$owl], 'yes') <> '')
				{
					$owls = explode(':', $this->mod_value[$owl]); //yes|responsive

					$this->smarty->assign('owlslider', $owls[0]);
					$responsive = explode('|', $owls[1]);
					$this->smarty->assign('responsive1', $responsive[0]);
					$this->smarty->assign('responsive2', $responsive[1]);
					$this->smarty->assign('responsive3', $responsive[2]);
					$this->smarty->assign('sliderid', $owls[2]);
				}
				else
					$this->smarty->assign('owlslider', 'no');

				$zoom = 5 + $pos;
				$all_effects = explode('|', $this->mod_value[$zoom]);
				$this->smarty->assign('zoom', $all_effects[0]);
				if (isset($all_effects[1]))
				{
					$effects = explode('^', $all_effects[1]);
					for ($i = 0; $i < $adv_number; $i++)
					{
						if (isset($effects[$i]))
						{
							$effect = explode(':', $effects[$i]);
							$this->smarty->assign('animate_'.$i, $effect[0]);
							$this->smarty->assign('delay_'.$i, $effect[1]);
						}
						else
						{
							$this->smarty->assign('animate_'.$i, '');
							$this->smarty->assign('delay_'.$i, '');
						}
					}
				}
				else
				{
					for ($i = 0; $i < $adv_number; $i++)
					{
						$this->smarty->assign('animate_'.$i, '');
						$this->smarty->assign('delay_'.$i, '');
					}
				}

				//for ($i = 0; $i < $adv_number; $i++)
				//	$adv_image[$i] = 6 + $pos + $i;
				$adimgs = 11 + $pos;
				$all_images = $this->language($params, $adimgs);
				$adv_image = explode('^', $all_images);

				$adlink = 12 + $pos;
				$id_name = 13 + $pos;
				$mobilegrid = 14 + $pos;

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

				$this->smarty->assign(array(
					'adv_number' => $adv_number,
					'id_name' => $this->mod_value[$id_name]
				));

				$adv_grid = array();
				$grids = explode('|', $this->mod_value[$adgrid]);
				if (count($grids) == 1)
				{
					for ($i = 0; $i < $adv_number; $i++)
						$adv_grid[$i] = $grids[0];
				}
				else
				{
					for ($i = 0; $i < $adv_number; $i++)
						if (isset($grids[$i]))
							$adv_grid[$i] = $grids[$i];
						else
							$adv_grid[$i] = '';
				}

				$adv_mobile = array();
				$grids = explode('|', $this->mod_value[$mobilegrid]);
				if (count($grids) == 1)
				{
					for ($i = 0; $i < $adv_number; $i++)
						$adv_mobile[$i] = $grids[0];
				}
				else
				{
					for ($i = 0; $i < $adv_number; $i++)
						if (isset($grids[$i]))
							$adv_mobile[$i] = $grids[$i];
						else
							$adv_mobile[$i] = '';
				}

				$adv_link = array();
				$links = explode('|', $this->mod_value[$adlink]);
				for ($i = 0; $i < $adv_number; $i++)
					if (isset($links[$i]))
						$adv_link[$i] = $links[$i];
					else
						$adv_link[$i] = '';

				for ($i = 0; $i < $adv_number; $i++)
				{
					$this->smarty->assign('adv_link_'.$i, $adv_link[$i]);
					$this->smarty->assign('adv_grid_'.$i, $adv_grid[$i]);
					$this->smarty->assign('adv_mobile_'.$i, $adv_mobile[$i]);
				}

				$baseurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
				$imgurl = $baseurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';

				for ($i = 0; $i < $adv_number; $i++)
				{
					//$advimgs = explode('|', $this->mod_value[$adv_image[$i]]);
					//if (isset($advimgs[$lang_id]))
					//	$adv = $advimgs[$lang_id];
					//else
					//	$adv = $advimgs[0];
					$adv = $adv_image[$i];

					if ($adv <> '')
					{
						if (Configuration::get('PS_UHU_DEVELOPER_MODE'))
							$this->smarty->assign('adv_image_'.$i, $imgurl.basename($adv));
						else
						{
							if (strstr($adv, 'http://') <> '')
								$this->smarty->assign('adv_image_'.$i, $adv);
							else
								$this->smarty->assign('adv_image_'.$i, $imgurl.$adv);
						}
					}
					else
						$this->smarty->assign('adv_image_'.$i, '');
				}

				if ($pos == 135)
					$this->smarty->assign('show_footer', 'yes');
				else
					$this->smarty->assign('show_footer', 'no');

				if ($this->mod_value[$id_name] == 'uhuimagescroll')
				{
					$grids = explode('|', $this->mod_value[$mobilegrid]);
					$this->smarty->assign('holder', $grids[0]);
					$this->smarty->assign('container', $grids[1]);
					if (isset($grids[2]))
						$this->smarty->assign('speed', $grids[2]);
					else
						$this->smarty->assign('speed', '0.2');
					if (isset($grids[3]))
						$this->smarty->assign('maxheight', $grids[3]);
					else
						$this->smarty->assign('maxheight', '1050');
					$html .= $this->display(__FILE__, $this->mod_value[$id_name].'.tpl');
				}
				else if ($this->mod_value[$id_name] == 'uhubanner')
				{
					$mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
					if ($mvalue[14] == 'content')
						$this->smarty->assign('show_close_header', 'no');
					else
						$this->smarty->assign('show_close_header', 'yes');
					if (Configuration::get('uhu_top_banner') == 1)
						$this->smarty->assign('show_topbanner', 'yes');
					else
						$this->smarty->assign('show_topbanner', 'no');
					if ($adv_image[0] <> '')
						$html .= $this->display(__FILE__, 'uhubanner.tpl');
				}
				else
				{
					if ($this->mod_value[$mobilegrid] == 'uhuimagetext')
					{
						for ($i = 0; $i < $adv_number; $i++)
						{
							$all_titles = 8 + $pos;
							if ($this->mod_value[$all_titles] <> '')
							{
								$adv_titles = $this->language($params, $all_titles);
								$titles = explode('^', $adv_titles);
								$this->smarty->assign('adv_title_'.$i, $titles[$i]);
							}
							$all_texts = 9 + $pos;
							if ($this->mod_value[$all_texts] <> '')
							{
								$adv_texts = $this->language($params, $all_texts);
								$texts = explode('^', $adv_texts);
								$this->smarty->assign('adv_text_'.$i, $texts[$i]);
							}
						}
						$html .= $this->display(__FILE__, 'uhuimagetext.tpl');
					}
					else if ($this->mod_value[$mobilegrid] == 'line')
						$html .= $this->display(__FILE__, 'uhuadvline.tpl');
					else
						$html .= $this->display(__FILE__, $this->name.'.tpl');
				}
			}
		}

		return $html;
	}

	public function language($params, $id)
	{
		$lang = array();
		if ($this->mod_value[$id] == '')
			return;
		$lang_iso = Language::getIsoById($params['cookie']->id_lang);
		$values = explode('|', $this->mod_value[$id]);
		foreach ($values as $value)
		{
			$langs = explode('¤', $value);
			if (isset($langs[1]) && $langs[1])
				$lang[$langs[1]] = $langs[0];
		}
		if ($lang[$lang_iso] <> '')
			return $lang[$lang_iso];
		else
			return $lang['en'];
	}

	public function hookDisplayBanner($params)
	{
		$pos = 0;
		$displays = explode('|', $this->mod_value[$pos]);
		if (isset($displays[1]) && $displays[1] == 'yes')
			return $this->displayHook($params, $pos);
	}

	public function hookDisplayNav($params)
	{
		$pos = 0;
		$displays = explode('|', $this->mod_value[$pos]);
		if (isset($displays[2]) && $displays[2] == 'yes')
			return $this->displayHook($params, $pos);
	}

	public function hookdisplayTop($params)
	{
		$pos = 0;
		$displays = explode('|', $this->mod_value[$pos]);
		if (isset($displays[0]) && $displays[0] == 'yes')
			return $this->displayHook($params, $pos);
	}

	public function hookdisplayTopColumn($params)
	{
		$pos = 15;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayTopLeft($params)
	{
		$pos = 30;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayTopRight($params)
	{
		$pos = 45;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayTopBanner($params)
	{
		$pos = 60;
		return $this->displayHook($params, $pos);
	}

	public function hookHome($params)
	{
		$pos = 75;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayBottomLeft($params)
	{
		$pos = 90;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayBottomRight($params)
	{
		$pos = 105;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayBottomBanner($params)
	{
		$pos = 120;
		return $this->displayHook($params, $pos);
	}

	public function hookdisplayFooterNav($params)
	{
		$pos = 135;
		$displays = explode('|', $this->mod_value[$pos]);
		if (isset($displays[1]) && $displays[1] == 'yes')
			return $this->displayHook($params, $pos);
	}

	public function hookFooter($params)
	{
		$pos = 135;
		$displays = explode('|', $this->mod_value[$pos]);
		if ($displays[0] == 'yes')
		return $this->displayHook($params, $pos);
	}
}