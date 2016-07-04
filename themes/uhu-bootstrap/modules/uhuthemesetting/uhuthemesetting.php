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

class Uhuthemesetting extends Module
{
	public function __construct()
	{
		$this->name = 'uhuthemesetting';
		$this->tab = 'others';
		$this->version = '1.3.7';
		$this->bootstrap = true;
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Theme configurator';
		$this->description = $this->l('Change all settings of the theme.');
	}

	public function install()
	{
		Configuration::updateValue('uhu_css_2012_front_panel', 0);
		Configuration::updateValue('uhu_css_2012_column', 0);
		Configuration::updateValue('PS_UHU_THEME', 'theme1');
		Configuration::updateValue('PS_UHU_STYLE', 'style1');
		Configuration::updateValue('PS_UHU_HEADER', 'header1');
		Configuration::updateValue('PS_UHU_FOOTER', 'footer1');
		Configuration::updateValue('uhu_responsive', 0);

		Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'theme` SET
									`responsive` = \'1\',
									`default_left_column` = \'1\',
									`default_right_column` = \'0\',
									`product_per_page` = \'12\'
									WHERE name = \'uhu-bootstrap\'');

		$theme_id = Db::getInstance()->getValue('SELECT id_theme FROM '._DB_PREFIX_.'theme WHERE name=\'uhu-bootstrap\'');

		$tmp_meta = array();
		$metas_xml = array();
		$new_theme = new Theme();
		$new_theme->id = $theme_id;
		$metas = Db::getInstance()->executeS('SELECT id_meta FROM '._DB_PREFIX_.'meta');
		foreach ($metas as $meta)
		{
			$tmp_meta['id_meta'] = (int)$meta['id_meta'];
			$tmp_meta['left'] = 0;
			$tmp_meta['right'] = 0;
			$metas_xml[] = $tmp_meta;
		}
		$new_theme->updateMetas($metas_xml, true);

		Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'theme_meta` SET `left_column` = \'1\' WHERE id_theme='.$theme_id.'
		AND (id_meta = \'2\' OR id_meta = \'5\' OR id_meta = \'6\' OR id_meta = \'8\' OR id_meta = \'9\' OR id_meta = \'22\' OR id_meta = \'28\')');

		Configuration::updateValue('uhu_responsive', 1);

		$this->loadConfigFile();

		$mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
		Configuration::updateValue('uhu_Theme_Name', $mvalue[0]);
		Configuration::updateValue('uhu_Theme_Version', $mvalue[1]);
		Configuration::updateValue('uhu_Theme_Date', $mvalue[2]);
		Configuration::updateValue('uhu_Theme_Names', $mvalue[3]);
		Configuration::updateValue('uhu_Theme_Colors', $mvalue[4]);
		Configuration::updateValue('uhu_Theme_Styles', $mvalue[5]);
		Configuration::updateValue('uhu_Style_Titles', $mvalue[6]);
		Configuration::updateValue('uhu_tab_content_slider', $mvalue[10]);
		Configuration::updateValue('uhu_fixed_menu', $mvalue[11]);
		if ($mvalue[12] <> '')
			Configuration::updateValue('PS_UHU_STYLE', $mvalue[12]);
		if ($mvalue[19] <> '')
			Configuration::updateValue('PS_UHU_HEADER', $mvalue[19]);
		if ($mvalue[20] <> '')
			Configuration::updateValue('PS_UHU_FOOTER', $mvalue[20]);

		if (!parent::install() ||
			!$this->registerHook('displayHeader') ||
			!$this->registerHook('displayFooter') ||
			!$this->registerHook('displayBackOfficeHeader'))
			return false;

		return true;
	}

	public function uninstall()
	{
		Configuration::deleteByName('uhu_css_2012_front_panel');
		Configuration::deleteByName('uhu_css_2012_column');
		Configuration::deleteByName('uhu_responsive');

		if (!parent::uninstall())
			return false;
		return true;
	}

	public function getContent()
	{
		$this->_html = '';

		$this->postProcess();
		$this->displayToolbar();
		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == 1)
			$this->loadConfigFile();
		$this->loadVersion();
		$this->displayForm();

		return $this->_html;
	}

	public function hookDisplayBackOfficeHeader()
	{
		$this->context->controller->addJquery();
		$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.colorpicker.js');
	}

	public function hookdisplayHeader()
	{
		$this->context->controller->addCss($this->_path.'views/css/theme_style.css', 'all');
		$this->context->controller->addCss($this->_path.'views/css/theme_font.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/animate.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/owl.carousel.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/refineslide.css', 'all');

		$theme = Configuration::get('PS_UHU_THEME');
		$style = Configuration::get('PS_UHU_STYLE');
		$header = Configuration::get('PS_UHU_HEADER');
		$footer = Configuration::get('PS_UHU_FOOTER');
		if (Configuration::get('PS_UHU_LIVE_DEMO') == 1 || Configuration::get('PS_UHU_DEVELOPER_MODE') == 1)
		{
			if (Tools::getValue('theme'))
			{
				$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme').'.css', 'all');
				if (Tools::getValue('theme_style'))
					$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme').'_'.Tools::getValue('theme_style').'.css', 'all');
				else
					$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme').'_'.$style.'.css', 'all');
			}
			else
			{
				$this->context->controller->addCss($this->_path.'views/css/'.$theme.'.css', 'all');
				if (Tools::getValue('theme_style'))
					$this->context->controller->addCss($this->_path.'views/css/'.$theme.'_'.Tools::getValue('theme_style').'.css', 'all');
				else
					$this->context->controller->addCss($this->_path.'views/css/'.$theme.'_'.$style.'.css', 'all');
			}
			//if (Tools::getValue('theme_style'))
			//	$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme_style').'_width.css', 'all');
			//else
			//	$this->context->controller->addCss($this->_path.'views/css/'.$style.'_width.css', 'all');
			//if (Tools::getValue('theme_style'))
			//	$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme_style').'_border.css', 'all');
			//else
			//	$this->context->controller->addCss($this->_path.'views/css/'.$style.'_border.css', 'all');
			if (Tools::getValue('theme_style'))
				$this->context->controller->addCss($this->_path.'views/css/'.Tools::getValue('theme_style').'_layout.css', 'all');
			else
				$this->context->controller->addCss($this->_path.'views/css/'.$style.'_layout.css', 'all');
		}
		else
		{
			$this->context->controller->addCss($this->_path.'views/css/'.$theme.'.css', 'all');
			$this->context->controller->addCss($this->_path.'views/css/'.$theme.'_'.$style.'.css', 'all');
			//$this->context->controller->addCss($this->_path.'views/css/'.$style.'_width.css', 'all');
			//$this->context->controller->addCss($this->_path.'views/css/'.$style.'_border.css', 'all');
			$this->context->controller->addCss($this->_path.'views/css/'.$style.'_layout.css', 'all');
		}
		$this->context->controller->addCss($this->_path.'views/css/style_all.css', 'all');
		$this->context->controller->addCss($this->_path.'views/css/'.$style.'.css', 'all');
		$this->context->controller->addCss($this->_path.'views/css/'.$header.'.css', 'all');
		$this->context->controller->addCss($this->_path.'views/css/'.$footer.'.css', 'all');

		$this->context->controller->addCss($this->_path.'views/css/mycolor.css', 'all');
		$this->context->controller->addCss($this->_path.'views/css/myfont.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/mybgimg.css', 'all');
		$this->context->controller->addCSS($this->_path.'views/css/custom.css', 'all');

		$mvalue = Tools::unserialize(Configuration::get('uhu_value_slider'));
		if ($mvalue[4] == 'RefineSlide')
			$this->context->controller->addJS(($this->_path).'views/js/jquery.refineslide.js');
		if ($mvalue[4] == 'fullPage')
			$this->context->controller->addJS(($this->_path).'views/js/jquery.fullPage.js');

		$mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
		if ($mvalue[15] == 'yes')
		{
			$this->context->controller->addJS(($this->_path).'views/js/jquery.stellar.js');
			$this->context->controller->addJS(($this->_path).'views/js/uhu.js');
		}
		if ($mvalue[16] == 'yes')
			$this->context->controller->addJS(($this->_path).'views/js/jquery.imageScroll.js');
		$this->context->controller->addJS(($this->_path).'views/js/owl.carousel.js');
		$this->context->controller->addJS(($this->_path).'views/js/wow.min.js');
		$this->context->controller->addJS(($this->_path).'views/js/uhutopmenu.js');
		//$this->context->controller->addJqueryPlugin(array('bxslider'));
	}

	public function hookDisplayFooter()
	{
		if (Configuration::get('PS_UHU_LIVE_DEMO') == 1	|| Configuration::get('PS_UHU_DEVELOPER_MODE') == 1
			|| Configuration::get('uhu_color_front_panel') == 1	|| Configuration::get('uhu_font_front_panel') == 1)
		{
			$this->context->controller->addCSS($this->_path.'views/css/live_configurator.css');
			$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.colorpicker.js');
			$this->context->controller->addJS($this->_path.'views/js/live_configurator.js');
		}

		if (Configuration::get('uhu_fixed_menu') == 1)
			$this->context->controller->addJS(($this->_path).'views/js/fixmenu.js');

		$html = '';

		$googlefont = Configuration::get('uhu_googlefonts');

		if ($googlefont <> '')
			$this->smarty->assign('googlefont', $googlefont);

		$slider = Configuration::get('uhu_tab_content_slider');

		if ($slider <> '')
			$this->smarty->assign('slider', $slider);

		$mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
		if ($mvalue[13] == '')
			$mvalue[13] = '4|4|2';
		$responsive = explode('|', $mvalue[13]);
		$this->smarty->assign('responsive1', $responsive[0]);
		$this->smarty->assign('responsive2', $responsive[1]);
		$this->smarty->assign('responsive3', $responsive[2]);

		if ($mvalue[17] == 'yes')
		{
			$module = Module::getInstanceByName('blocknewsletter');
			if ($module <> false && $module->active)
				$this->smarty->assign('shownewsletter', true);
			else
				$this->smarty->assign('shownewsletter', false);

			$mod_name = 'newsletter';
			$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));
			$this->smarty->assign(array(
				'newsletter_time' => $mvalue[0],
				'newsletter_width' => $mvalue[1],
				'newsletter_height' => $mvalue[2],
				'newsletter_file' => $mvalue[3],
				'newsletter_days' => $mvalue[4],
				'newsletter_padding' => $mvalue[5]
			));
		}
		else
			$this->smarty->assign('shownewsletter', false);

		return $html.$this->display(__FILE__, 'hook.tpl');
	}

	public function postProcess()
	{
		$errors = '';

		if (Tools::isSubmit('submitCustomCSS'))
		{
			// custom css
			$customcss = Tools::getValue('customcss');
			Configuration::updateValue('uhu_custom_css', $customcss);

			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/custom.css', 'wb');
			fputs($fp, $customcss);
			fclose($fp);
		}

		/*
			保存插件内容配置
		*/
		if (Tools::isSubmit('submitCustomConfig'))
		{
			Configuration::updateValue('uhu_fixed_menu', Tools::getValue('uhu_fixed_menu'));
			Configuration::updateValue('uhu_top_banner', Tools::getValue('uhu_top_banner'));

			$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
			if ($fp)
			{
				$count = fgets($fp);
				$languages = Language::getLanguages(true);
				for ($mid = 0; $mid < $count; $mid++)
				{
					$mod_name = trim(fgets($fp));
					$mod_title = trim(fgets($fp));
					$mod_total = trim(fgets($fp));
					fgets($fp);

					if ($mod_name <> 'setting' && $mod_name <> 'border' && $mod_name <> 'font' && $mod_name <> 'color' && $mod_name <> 'width')
					{
						$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

						if ($mod_name == 'slider')
							$total = 20 + 10 * $mvalue[2];
						elseif ($mod_name == 'categories')
							$total = 10 + 10 * $mvalue[5];
						elseif ($mod_name == 'news')
							$total = 10 + 10 * $mvalue[5];
						else
							$total = $mod_total;
						$moduletitle = $mod_title;

						if ((strstr($moduletitle, 'uhu') <> '' || strstr($moduletitle, 'block') <> '') && strstr($moduletitle, '全局') == '')
						{
							$module = Module::getInstanceByName($moduletitle);
							if ($module <> false && $module->active)
							{
								for ($j = 0; $j < $total; $j++)
								{
									$input_id = 'link'.$mod_name.'_'.$j;
									if (Tools::getIsset($input_id))
										$mvalue[$j] = Tools::getValue($input_id);
									else
									{
										if (count($languages) > 1)
										{
											$mvalue_br = '';
											foreach ($languages as $lang)
											{
												$input_id = 'link'.$mod_name.'_'.$j.'_'.$lang['id_lang'];
												if (Tools::getIsset($input_id))
													$mvalue_br .= Tools::getValue($input_id).'¤'.$lang['iso_code'].'|';
											}
											if ($mvalue_br <> '')
												$mvalue[$j] = $mvalue_br;
										}
									}
								}
								if ($moduletitle == 'uhutopmenu')
									$mvalue = $this->updateCustomMenu($mvalue);

								if ($moduletitle == 'uhufacebook')
									$this->updateFacebook($mvalue[5]);

								Configuration::updateValue('uhu_value_'.$mod_name, serialize($mvalue));
							}
						}
					}
				}
			}
			fclose($fp);
		}

		if (Tools::isSubmit('submitChoiseThemeColor'))
		{
			$selected_theme_name = Tools::getValue('selected_theme_color');
			Configuration::updateValue('PS_UHU_THEME', $selected_theme_name);
			$my_color = Tools::getValue('mycolor');
			Configuration::updateValue('PS_UHU_MYCOLOR', $my_color);

			$my_border = Tools::getValue('myborder');
			Configuration::updateValue('PS_UHU_MYBORDER', $my_border);

			$my_background = Tools::getValue('mybackground');
			Configuration::updateValue('PS_UHU_MYBACKGROUND', $my_background);

			if ($selected_theme_name == 'theme0')
			{
				$result = Tools::file_get_contents(_PS_MODULE_DIR_.'uhuthemesetting/views/css/theme1.css');
				$themes_colors = explode('|', Configuration::get('uhu_Theme_Colors'));
				$result = str_replace('color:'.$themes_colors[0], 'color:'.Configuration::get('PS_UHU_MYCOLOR'), $result);

				$themes_colors = explode('|', Configuration::get('uhu_Color_Border'));
				$result = str_replace('border-color:'.$themes_colors[0], 'border-color:'.Configuration::get('PS_UHU_MYBORDER'), $result);

				$themes_colors = explode('|', Configuration::get('uhu_Color_Background'));
				$result = str_replace('background-color:'.$themes_colors[0], 'background-color:'.Configuration::get('PS_UHU_MYBACKGROUND'), $result);
				file_put_contents(_PS_MODULE_DIR_.'uhuthemesetting/views/css/theme0.css', $result);
			}
		}

		if (Tools::isSubmit('submitChoiseThemeStyle'))
		{
			$selected_style_name = Tools::getValue('selected_theme_style');
			Configuration::updateValue('PS_UHU_STYLE', $selected_style_name);
		}

		if (Tools::isSubmit('submitChoiseHeaderStyle'))
		{
			$selected_style_name = Tools::getValue('selected_theme_header');
			Configuration::updateValue('PS_UHU_HEADER', $selected_style_name);
		}

		/* Deletes */
		if (Tools::isSubmit('delete_id_image'))
		{
			$slide = Tools::getValue('delete_id_image');
			if (file_exists(_PS_MODULE_DIR_.'uhuthemesetting/views/img/'.$slide))
				unlink(_PS_MODULE_DIR_.'uhuthemesetting/views/img/'.$slide);
			$url = explode('&delete_id_image', $_SERVER['REQUEST_URI']);
			Tools::redirect(Tools::getShopProtocol().$_SERVER['SERVER_NAME'].$url[0]);
		}

		if (Tools::isSubmit('submitNativeProductConfig'))
		{
			foreach ($this->getConfigurableModules() as $module)
			{
				$module_instance = Module::getInstanceByName($module['name']);
				if ($module_instance === false || !is_object($module_instance))
					continue;

				$is_installed = (int)Validate::isLoadedObject($module_instance);
				if ($is_installed)
				{
					if (($active = (int)Tools::getValue($module['name'])) == $module_instance->active)
						continue;

					if ($active)
						$module_instance->enable();
					else
						$module_instance->disable();
				}
				else
					if ((int)Tools::getValue($module['name']))
						$module_instance->install();
			}
		}

		if (Tools::isSubmit('submitNativeProductConfig2'))
		{
			Configuration::updateValue('uhu_tab_content_slider', Tools::getValue('uhu_tab_content_slider'));

			Configuration::updateValue('HOME_FEATURED_NBR', Tools::getValue('HOME_FEATURED_NBR'));
			Configuration::updateValue('HOME_FEATURED_CAT', Tools::getValue('HOME_FEATURED_CAT'));
			Configuration::updateValue('HOME_FEATURED_RANDOMIZE', Tools::getValue('HOME_FEATURED_RANDOMIZE'));

			Configuration::updateValue('NEW_PRODUCTS_NBR', Tools::getValue('NEW_PRODUCTS_NBR'));
			Configuration::updateValue('PS_NB_DAYS_NEW_PRODUCT', Tools::getValue('PS_NB_DAYS_NEW_PRODUCT'));
			Configuration::updateValue('PS_BLOCK_NEWPRODUCTS_DISPLAY', Tools::getValue('PS_BLOCK_NEWPRODUCTS_DISPLAY'));

			Configuration::updateValue('PS_BLOCK_BESTSELLERS_TO_DISPLAY', Tools::getValue('PS_BLOCK_BESTSELLERS_TO_DISPLAY'));
			Configuration::updateValue('PS_BLOCK_BESTSELLERS_DISPLAY', Tools::getValue('PS_BLOCK_BESTSELLERS_DISPLAY'));

			Configuration::updateValue('BLOCKSPECIALS_NB_CACHES', Tools::getValue('BLOCKSPECIALS_NB_CACHES'));
			Configuration::updateValue('BLOCKSPECIALS_SPECIALS_NBR', Tools::getValue('BLOCKSPECIALS_SPECIALS_NBR'));
			Configuration::updateValue('PS_BLOCK_SPECIALS_DISPLAY', Tools::getValue('PS_BLOCK_SPECIALS_DISPLAY'));
		}

		if (Tools::isSubmit('submitConfigBackground'))
		{
			$file = '';
			$file .= $this->postProcessColorStyle(16);
			$file .= $this->postProcessBackgroundStyle(17, true);
			$file .= $this->postProcessBackgroundStyle(18);
			$file .= $this->postProcessBackgroundStyle(20);
			$file .= $this->postProcessBackgroundStyle(21);
			$file .= $this->postProcessBackgroundStyle(470);

			$file .= $this->postProcessColorStyle(19);
			$file .= $this->postProcessBackgroundStyle(23, true);
			$file .= $this->postProcessBackgroundStyle(24);
			$file .= $this->postProcessBackgroundStyle(26);
			$file .= $this->postProcessBackgroundStyle(27);
			$file .= $this->postProcessBackgroundStyle(471);

			$file .= $this->postProcessColorStyle(25);
			$file .= $this->postProcessBackgroundStyle(426, true);
			$file .= $this->postProcessBackgroundStyle(427);
			$file .= $this->postProcessBackgroundStyle(428);
			$file .= $this->postProcessBackgroundStyle(429);
			$file .= $this->postProcessBackgroundStyle(472);

			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/mybgimg.css', 'wb');
			fputs($fp, $file);
			fclose($fp);
		}

		if (Tools::isSubmit('submitUpdatePanel'))
		{
			if (Tools::getValue('PS_SHOP_ENABLE') == 1)
			{
				Configuration::updateValue('PS_SHOP_ENABLE', Tools::getValue('PS_SHOP_ENABLE'));
				Configuration::updateValue('uhu_color_front_panel', 0);
				Configuration::updateValue('uhu_font_front_panel', 0);
			}
			else
			{
				Configuration::updateValue('PS_SHOP_ENABLE', Tools::getValue('PS_SHOP_ENABLE'));
				Configuration::updateValue('PS_MAINTENANCE_IP', Tools::getValue('PS_MAINTENANCE_IP'));
				Configuration::updateValue('uhu_color_front_panel', Tools::getValue('front_panel_color'));
				Configuration::updateValue('uhu_font_front_panel', Tools::getValue('front_panel_font'));
			}
		}
		else
		{
			$this->checkUploadModules();
			$this->checkUploadSubmit('adv_bkimg', '/views/img/bkimg/');
		}

		if ($errors)
			echo $this->displayError($errors);
	}

	private function postProcessColorStyle($m_id)
	{
		$mid = $m_id + 1;
		$result = Db::getInstance()->getRow('
			SELECT modid, display, selector
			FROM `'._DB_PREFIX_.'uhucolors`
			WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mid);
		$css_id = $result['modid'];
		$csstitle = $result['display'];
		$selectors = $result['selector'];

		$code = '';
		if (Tools::getIsset('link'.$css_id))
		{
			$cssvalue = Tools::getValue('link'.$css_id);

			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'uhucolors` SET
					mycolor = \''.pSQL($cssvalue).'\'
					WHERE id_item = '.(int)$mid
				);

			if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '')
				$code = $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";
		}
		return $code;
	}

	private function postProcessBackgroundStyle($m_id, $img)
	{
		$mid = $m_id + 1;
		$result = Db::getInstance()->getRow('
			SELECT modid, display, selector
			FROM `'._DB_PREFIX_.'uhucolors`
			WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mid);
		$css_id = $result['modid'];
		$csstitle = $result['display'];
		$selectors = $result['selector'];

		$code = '';
		if (Tools::getIsset('link'.$css_id))
		{
			$cssvalue = Tools::getValue('link'.$css_id);

			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'uhucolors` SET
					colors = \''.pSQL($cssvalue).'\'
					WHERE id_item = '.(int)$mid
				);

			if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '')
				if ($img)
					$code = $selectors.' {'.$csstitle.': url(../../views/img/bkimg/'.$cssvalue.')'.";}\n";
				else
					$code = $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";
		}
		return $code;
	}

	private function updateCustomMenu($mvalue)
	{
		$itme1 = $mvalue[25];
		$itme2 = $mvalue[33];
		$itme3 = $mvalue[37];
		$menu_items = 'CAT'.$itme1.',CAT'.$itme2.',CAT'.$itme3.',';

		$item456 = explode('|', $mvalue[43]);
		foreach ($item456 as $item)
		{
			if (!$item)
				continue;
			$menu_items .= 'CAT'.$item.',';
		}

		$items = explode(',', $mvalue[30]);
		foreach ($items as $item)
		{
			if (!$item)
				continue;
			$menu_items .= 'CATA'.$item.',';
		}
		$items = explode(',', $mvalue[34]);
		foreach ($items as $item)
		{
			if (!$item)
				continue;
			$menu_items .= 'CATB'.$item.',';
		}
		$items = explode(',', $mvalue[38]);
		foreach ($items as $item)
		{
			if (!$item)
				continue;
			$menu_items .= 'CATC'.$item.',';
		}

		$cat_main = 'D';
		$more_itmes = explode('|', $mvalue[44]);
		foreach ($more_itmes as $item456)
		{
			if (!$item456)
				continue;
			$items = explode(',', $item456);
			foreach ($items as $item)
			{
				if (!$item)
					continue;
				$menu_items .= 'CAT'.$cat_main.$item.',';
			}
			$cat_main = chr(ord($cat_main) + 1);
		}
		$mvalue[12] = $menu_items;

		// images
		$image_items = 'CATA:'.$mvalue[31].'|';
		$image_items .= 'CATB:'.$mvalue[35].'|';
		$image_items .= 'CATC:'.$mvalue[39];
		$cat_main = 'D';
		$more_itmes = explode('|', $mvalue[45]);
		foreach ($more_itmes as $item456)
		{
			if (!$item456)
				continue;
			$image_items .= '|CAT'.$cat_main.':'.$item456;
			$cat_main = chr(ord($cat_main) + 1);
		}
		$mvalue[28] = $image_items;

		// links
		$link_items = 'CATA:'.$mvalue[32].'|';
		$link_items .= 'CATB:'.$mvalue[36].'|';
		$link_items .= 'CATC:'.$mvalue[40];
		$cat_main = 'D';
		$more_itmes = explode('|', $mvalue[46]);
		foreach ($more_itmes as $item456)
		{
			if (!$item456)
				continue;
			$link_items .= '|CAT'.$cat_main.':'.$item456;
			$cat_main = chr(ord($cat_main) + 1);
		}
		$mvalue[29] = $link_items;

		return $mvalue;
	}

	private function updateFacebook($sandbox)
	{
		if ($sandbox == 'false' && Configuration::get('uhu_update_fblike') <> 'no')
		{
			Configuration::updateValue('uhu_update_fblike', 'no');
			$result = Tools::file_get_contents(_PS_MODULE_DIR_.'uhufacebook/uhufacebook.php');
			$result = str_replace('/* uhupage', '/* uhupage */', $result);
			$result = str_replace('*/ //uhupage', '/* uhupage */', $result);
			file_put_contents(_PS_MODULE_DIR_.'uhufacebook/uhufacebook.php', $result);
		}
	}

	private function checkUploadSubmit($css_id, $imagefolder)
	{
		$errors = array();
		if (Tools::isSubmit('submitBackpattern_'.$css_id))
		{
			if (isset($_FILES[$css_id.'_file']) && isset($_FILES[$css_id.'_file']['tmp_name']) && !empty($_FILES[$css_id.'_file']['tmp_name']))
			{
				if ($error = ImageManager::validateUpload($_FILES[$css_id.'_file'], Tools::convertBytes(ini_get('upload_max_filesize'))))
					$errors .= $error;
				else
				{
					$fname = explode('/', $imagefolder);

					$path = _PS_MODULE_DIR_.'/'.$this->name.'/'.$fname[1].'/'.$fname[2];
					if (!is_dir($path))
					{
						mkdir($path, 0755);

						$s_dir = _PS_MODULE_DIR_.'/'.$this->name.'/views/img/index.php';
						$d_dir = $path.'/index.php';
						Tools::copy($s_dir, $d_dir);

						$s_dir = _PS_MODULE_DIR_.'/'.$this->name.'/views/img/0_noimage.gif';
						$d_dir = $path.'/0_noimage.gif';
						Tools::copy($s_dir, $d_dir);
					}

					if (count($fname) > 3)
					{
						$path = _PS_MODULE_DIR_.'/'.$this->name.'/'.$fname[1].'/'.$fname[2].'/'.$fname[3];
						if (!is_dir($path))
						{
							mkdir($path, 0755);

							$s_dir = _PS_MODULE_DIR_.'/'.$this->name.'/views/img/index.php';
							$d_dir = $path.'/index.php';
							Tools::copy($s_dir, $d_dir);

							$s_dir = _PS_MODULE_DIR_.'/'.$this->name.'/views/img/0_noimage.gif';
							$d_dir = $path.'/0_noimage.gif';
							Tools::copy($s_dir, $d_dir);

						}
					}
					if (!move_uploaded_file($_FILES[$css_id.'_file']['tmp_name'],
						_PS_MODULE_DIR_.'/'.$this->name.$imagefolder.$_FILES[$css_id.'_file']['name']))
						$errors .= $this->l('Error move uploaded file');
				}
			}
		}
	}

	private function checkUploadModules()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$count = fgets($fp);
			for ($mid = 0; $mid < $count; $mid++)
			{
				$mod_name = trim(fgets($fp));
				fgets($fp);
				fgets($fp);
				$mod_adver = trim(fgets($fp));
				if ($mod_adver == 'true')
					$this->checkUploadSubmit('adv_'.$mod_name, '/views/img/'.$mod_name.'/');
			}
		}
		fclose($fp);
	}

	private function displayToolbar()
	{
		if (_PS_VERSION_ < 1.6)
		{
			$this->_html .= '<script type="text/javascript">';
			$this->_html .= "$(document).ready(function() {
								$('#content').addClass('bootstrap');
								$('div.productTabs').find('a').each(function() { $(this).attr('href', '#');	});
								$('div.productTabs a').click(function() {
									var id = $(this).attr('id');
									$('.nav-profile').removeClass('active');
									$(this).addClass('active');
									$('.tab-profile').hide();
									$('.'+id).show();
									});
							});";
			$this->_html .= '</script>';

			$this->_html .= '
							<div class="toolbar-placeholder">
								<div class="toolbarBox toolbarHead">
									<ul class="my_button" style="float: right;">
										<li style="color:#666666; float:left; height:48px;
											list-style: none outside none; padding: 1px 1px 3px 4px; text-align: center;">
											<a id="desc-doc-help" class="toolbar_btn" href="http://doc.uhupage.com"
												title="Document" style="display: block; " >
												<span class="process-icon-export "></span>
												<div>Document</div>
											</a>
										</li>
									</ul>';

			$this->_html .= '
									<div class="pageTitle">
										<h2><span id="current_obj" style="font-weight: normal;">
											<span class="breadcrumb item-2 ">Theme Setting</span> v'.$this->version.'</span>
										</h2>
									</div>
								</div>
							</div>';
		}
		else
		{
			$this->_html .= '<script type="text/javascript">';
			$this->_html .= "$(document).ready(function() {
								$('div.productTabs').find('a').each(function() { $(this).attr('href', '#');	});
								$('div.productTabs a').click(function() {
									var id = $(this).attr('id');
									$('.nav-profile').removeClass('active');
									$(this).addClass('active');
									$('.tab-profile').hide();
									$('.'+id).show();
									});
								$('#logo_font').change(function(){
									var gfont=$('option:selected',this).val();
									if($('head').find('link#link_logo').length<1){
										$('head').append('<link id=\"link_logo\" href=\"\" rel=\"stylesheet\" type=\"text/css\"/>');
									}
									$('link#link_logo').attr({href:'http://fonts.googleapis.com/css?family='+gfont});
									$('#logo_text').css({'font-family':gfont});
								});
								$('#logo_size').change(function(){
									var gsize=$('option:selected',this).val();
									$('#logo_text').css({'font-size':gsize});
								});
								$('#logo_color').bind('change',function(){
									var gcolor=$(this).val();
									$('p#logo_text').css({'color':gcolor});
									});
							});";
			$this->_html .= '</script>';
		}
	}

	private function displayForm()
	{
		$this->_html .= '<div class="row">';

		$this->_html .= '<div class="productTabs col-lg-3">
							<div class="tab list-group">';

		//
		// General
		//
		$this->_html .= '<span class="nav-profile list-group-item" style="font-size: 14px; background-color: #fcfdfe;">';
		$this->_html .= '	<i class="icon-edit" style="font-size:20px;"></i>'.$this->l('Configure');
		$this->_html .= '</span>';
		$this->_html .= '<a class="nav-profile list-group-item active" id="profile-0" href="#">'.$this->l('General').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-3" href="#">'.$this->l('Background').'</a>';

		//
		// modules
		//
		$this->_html .= '<span class="nav-profile list-group-item" style="font-size: 14px; background-color: #fcfdfe;">';
		$this->_html .= '	<i class="icon-edit" style="font-size:20px;"></i>'.$this->l('Modules');
		$this->_html .= '</span>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-10" href="#">'.$this->l('Logo').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-11" href="#">'.$this->l('Top Menu').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-12" href="#">'.$this->l('Image Slider').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-13" href="#">'.$this->l('Advertising').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-14" href="#">'.$this->l('Native Product Block').'</a>';

		$module = Module::getInstanceByName('uhuhomefeatured');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-15" href="#">'.$this->l('Featured Product').'</a>';

		$module = Module::getInstanceByName('uhunewproducts');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-17" href="#">'.$this->l('New Product').'</a>';

		$module = Module::getInstanceByName('uhubestsellers');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-21" href="#">'.$this->l('Best Seller').'</a>';

		$module = Module::getInstanceByName('uhufacebook');
		if ($module <> false && $module->active)
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-23" href="#">'.$this->l('Facebook Fan').'</a>';

		$module = Module::getInstanceByName('uhucategories');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-25" href="#">'.$this->l('Categories Block').'</a>';

		$module = Module::getInstanceByName('uhucategories');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-26" href="#">'.$this->l('News Block').'</a>';

		$this->_html .= '<a class="nav-profile list-group-item " id="profile-30" href="#">'.$this->l('Contact block').'</a>';

		$module = Module::getInstanceByName('uhureinsurance');
		if ($module <> false && $module->active)
			$this->_html .= '<a class="nav-profile list-group-item " id="profile-31" href="#">'.$this->l('Reassurance block').'</a>';

		$this->_html .= '<a class="nav-profile list-group-item " id="profile-32" href="#">'.$this->l('Social Networking').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-38" href="#">'.$this->l('Copyright').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-39" href="#">'.$this->l('Newsletter').'</a>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-40" href="#">'.$this->l('Tags').'</a>';

		//
		// custom
		//
		$this->_html .= '<span class="nav-profile list-group-item" style="font-size: 14px; background-color: #fcfdfe;">';
		$this->_html .= '	<i class="icon-edit" style="font-size:20px;"></i>'.$this->l('Custom');
		$this->_html .= '</span>';
		$this->_html .= '<a class="nav-profile list-group-item " id="profile-99" href="#">'.$this->l('CSS code').'</a>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		//
		// Tab list: General
		//
		$this->displayFormTabConfig(0);
		$this->displayFormTabBackground(3);

		//
		// Tab list: Modules
		//
		$this->_html .= '<form class="form-horizontal col-lg-9" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'"
								method="post" enctype="multipart/form-data">';
		$this->displayFormTabProductBlock(14);
		$this->_html .= '</form>';

		$this->_html .= '<form class="form-horizontal col-lg-9" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'"
								method="post" enctype="multipart/form-data">';
		$this->displayFormTabLogo(10);
		$this->displayFormTabTopmenu(11);
		$this->displayFormTabSlider(12);
		$this->displayFormTabAdvertising(13);

		$module = Module::getInstanceByName('uhuhomefeatured');
		if ($module <> false && $module->active)
			$this->displayFormTabFeatured(15);

		$module = Module::getInstanceByName('uhunewproducts');
		if ($module <> false && $module->active)
			$this->displayFormTabNew(17);

		$module = Module::getInstanceByName('uhubestsellers');
		if ($module <> false && $module->active)
			$this->displayFormTabBest(21);

		$module = Module::getInstanceByName('uhufacebook');
		if ($module <> false && $module->active)
			$this->displayFormTabFacebook(23);

		$module = Module::getInstanceByName('uhucategories');
		if ($module <> false && $module->active)
			$this->displayFormCategories(25);

		$module = Module::getInstanceByName('uhucategories');
		if ($module <> false && $module->active)
			$this->displayFormNews(26);

		$this->displayFormTabContact(30);

		$module = Module::getInstanceByName('uhureinsurance');
		if ($module <> false && $module->active)
			$this->displayFormTabReassurance(31);

			$this->displayFormTabSocial(32);

		$this->displayFormTabCopyright(38);
		$this->displayFormTabNewsletter(39);
		$module = Module::getInstanceByName('uhutags');
		if ($module <> false && $module->active)
			$this->displayFormTabTags(40);
		$this->_html .= '</form>';

		//
		// Tab list: Custom
		//
		$this->_html .= '<form class="form-horizontal col-lg-9" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'
								"method="post" enctype="multipart/form-data">';
		$this->displayFormTabCustom(99);
		$this->_html .= '</form>';

		$this->_html .= '</div>';
	}

	private function displayFormTabConfig($tab)
	{
		$this->_html .= '<form class="form-horizontal col-lg-9" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'
								"method="post" enctype="multipart/form-data">';
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:block">';

		$this->_html .= '<div class="panel product-tab" id="tabPane1">';
		$this->_html .= '<h3>'.$this->l('Front Panel Setting').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Enable Shop').'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="PS_SHOP_ENABLE" id="PS_SHOP_ENABLE_1" value="1" '.
										((Configuration::get('PS_SHOP_ENABLE') == 1) ? 'checked="checked"' : '').'>
										<label for="PS_SHOP_ENABLE_1">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="PS_SHOP_ENABLE" id="PS_SHOP_ENABLE_0" value="0" '.
										((Configuration::get('PS_SHOP_ENABLE') == 0) ? 'checked="checked"' : '').'>
										<label for="PS_SHOP_ENABLE_0">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>
							<div class="help-block">
								Activate or deactivate your shop.
							</div>
						</div>';
		$this->_html .= '</div>';

		$hint = $this->l('IP addresses allowed to access the Front Office even if the shop is disabled. 
						Please use a comma to separate them (e.g. 42.24.4.2,127.0.0.1,99.98.97.96)');

		$this->_html .= '<div class="form-group">
							<div id="conf_id_PS_MAINTENANCE_IP">
								<label class="control-label col-lg-3">
									<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="'.$hint.'" data-html="true">Maintenance IP</span>
								</label>';
		$this->_html .= '
						<script type="text/javascript">
							function addRemoteAddr()
							{
								var length = $(\'input[name=PS_MAINTENANCE_IP]\').attr(\'value\').length;
								if (length > 0)
									$(\'input[name=PS_MAINTENANCE_IP]\').attr(\'value\',$(\'input[name=PS_MAINTENANCE_IP]\').attr(\'value\') +\','.Tools::getRemoteAddr().'\');
								else
									$(\'input[name=PS_MAINTENANCE_IP]\').attr(\'value\',\''.Tools::getRemoteAddr().'\');
							}
						</script>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="col-lg-8">
									<input type="text" size="5" name="PS_MAINTENANCE_IP" value="'.Configuration::get('PS_MAINTENANCE_IP').'">
								</div>
								<div class="col-lg-1">
									<button type="button" class="btn btn-default" onclick="addRemoteAddr();"><i class="icon-plus"></i> '.$this->l('Add my IP').'</button>
								</div>
							</div>
						</div>
						</div>
						</div>';

		if (Configuration::get('PS_SHOP_ENABLE') == 0)
		{
			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Show Front Color Editor').'</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="row">
									<div class="input-group col-lg-2">
										<span class="switch prestashop-switch">
											<input type="radio" name="front_panel_color" id="front_panel_color_1" value="1" '.
											((Configuration::get('uhu_color_front_panel') == 1) ? 'checked="checked"' : '').'>
											<label for="front_panel_color_1">
												<i class="icon-check-sign color_success"></i> Yes
											</label>
											<input type="radio" name="front_panel_color" id="front_panel_color_0" value="0" '.
											((Configuration::get('uhu_color_front_panel') == 0) ? 'checked="checked"' : '').'>
											<label for="front_panel_color_0">
												<i class="icon-ban-circle color_danger"></i> No
											</label>
											<a class="slide-button btn btn-default"></a>
										</span>
									</div>
								</div>
							</div>';
			$this->_html .= '</div>';

			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Show Front Font Editor').'</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="row">
									<div class="input-group col-lg-2">
										<span class="switch prestashop-switch">
											<input type="radio" name="front_panel_font" id="front_panel_font_1" value="1" '.
											((Configuration::get('uhu_font_front_panel') == 1) ? 'checked="checked"' : '').'>
											<label for="front_panel_font_1">
												<i class="icon-check-sign color_success"></i> Yes
											</label>
											<input type="radio" name="front_panel_font" id="front_panel_font_0" value="0" '.
											((Configuration::get('uhu_font_front_panel') == 0) ? 'checked="checked"' : '').'>
											<label for="front_panel_font_0">
												<i class="icon-ban-circle color_danger"></i> No
											</label>
											<a class="slide-button btn btn-default"></a>
										</span>
									</div>
								</div>
							</div>';
			$this->_html .= '</div>';
		}
		else
		{
			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Show Front Color Editor').'</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="row">
									<div class="input-group col-lg-2">
										<span class="switch prestashop-switch">
											<input type="radio" name="front_panel_color" id="front_panel_color_1" value="1" '.
											((Configuration::get('uhu_color_front_panel') == 1) ? 'checked="checked"' : '').' disabled="disabled">
											<label for="front_panel_color_1">
												<i class="icon-check-sign color_success"></i> Yes
											</label>
											<input type="radio" name="front_panel_color" id="front_panel_color_0" value="0" '.
											((Configuration::get('uhu_color_front_panel') == 0) ? 'checked="checked"' : '').' disabled="disabled">
											<label for="front_panel_color_0">
												<i class="icon-ban-circle color_danger"></i> No
											</label>
											<a class="slide-button btn btn-default"></a>
										</span>
									</div>
								</div>
							</div>';
			$this->_html .= '</div>';

			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Show Front Font Editor').'</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="row">
									<div class="input-group col-lg-2">
										<span class="switch prestashop-switch">
											<input type="radio" name="front_panel_font" id="front_panel_font_1" value="1" '.
											((Configuration::get('uhu_font_front_panel') == 1) ? 'checked="checked"' : '').' disabled="disabled">
											<label for="front_panel_font_1">
												<i class="icon-check-sign color_success"></i> Yes
											</label>
											<input type="radio" name="front_panel_font" id="front_panel_font_0" value="0" '.
											((Configuration::get('uhu_font_front_panel') == 0) ? 'checked="checked"' : '').' disabled="disabled">
											<label for="front_panel_font_0">
												<i class="icon-ban-circle color_danger"></i> No
											</label>
											<a class="slide-button btn btn-default"></a>
										</span>
									</div>
								</div>
							</div>';
			$this->_html .= '</div>';
		}

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitUpdatePanel">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		//
		// switch theme
		//
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';
		$this->_html .= '<h3>'.$this->l('Theme: Color').'</h3>';
		$themes_names = explode('|', Configuration::get('uhu_Theme_Names'));
		$c_value = explode('|', Configuration::get('uhu_Theme_Colors'));
		$id = 0;
		foreach ($themes_names as $themes_name)
		{
			$selected = (Configuration::get('PS_UHU_THEME') == $themes_name) ? 'checked="checked"' : '';
			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$themes_name;
			$this->_html .= ' <input type="radio" name="selected_theme_color" id="selected_'.$themes_name.'
								" value="'.$themes_name.'" '.$selected.'>';
			$this->_html .= '</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="form-group">
									<div class="col-lg-1">
										<div class="row">
											<div class="input-group">
												<div class="attributes-color-container" style="background-color:'.$c_value[$id ++].';"></div>
											</div>
										</div>
									</div>
								</div>			
							</div>';
			$this->_html .= '</div>';
		}

		$themes_name = 'theme0';
		$selected = (Configuration::get('PS_UHU_THEME') == $themes_name) ? 'checked="checked"' : '';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">';
		$this->_html .= $this->l('My Color');
		$this->_html .= ' <input type="radio" name="selected_theme_color" id="selected_'.$themes_name.'
								" value="'.$themes_name.'" '.$selected.'>';
		$this->_html .= '</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="form-group">';

		/*
			Set custom main text color
		*/
		$this->_html .= '		<div class="col-lg-2">
									<div class="row">
										<div class="input-group">';

		$this->_html .= '<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker"
							name="mycolor" value="'.Configuration::get('PS_UHU_MYCOLOR').'" id="color_0"
							style="color: black; background-color: '.Configuration::get('PS_UHU_MYCOLOR').';">';
		$this->_html .= '<span style="cursor:pointer;" id="icp_color_0" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">';
		$this->_html .= '<img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>';

		$this->_html .= '				</div>'.$this->l('Main Text Color').'
									</div>
								</div>';

		/*
			Set custom main border color
		*/
		$this->_html .= '		<div class="col-lg-offset-1 col-lg-2">
									<div class="row">
										<div class="input-group">';

		$this->_html .= '<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker"
							name="myborder" value="'.Configuration::get('PS_UHU_MYBORDER').'" id="color_1"
							style="color: black; background-color: '.Configuration::get('PS_UHU_MYBORDER').';">';
		$this->_html .= '<span style="cursor:pointer;" id="icp_color_1" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">';
		$this->_html .= '<img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>';

		$this->_html .= '				</div>'.$this->l('Main Border Color').'
									</div>
								</div>';

		/*
			Set custom main background color
		*/
		$this->_html .= '		<div class="col-lg-offset-1 col-lg-2">
									<div class="row">
										<div class="input-group">';

		$this->_html .= '<input type="text" data-hex="true" class="color mColorPickerInput mColorPicker"
							name="mybackground" value="'.Configuration::get('PS_UHU_MYBACKGROUND').'" id="color_2"
							style="color: black; background-color: '.Configuration::get('PS_UHU_MYBACKGROUND').';">';
		$this->_html .= '<span style="cursor:pointer;" id="icp_color_2" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">';
		$this->_html .= '<img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>';

		$this->_html .= '				</div>'.$this->l('Main Background Color').'
									</div>
								</div>';

		$this->_html .= '	</div>';
		$this->_html .= '	<p class="help-block">';
		$this->_html .= $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").');
		$this->_html .= '	</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitChoiseThemeColor">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		//
		// switch style
		//
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';
		$this->_html .= '<h3>'.$this->l('Theme: Style').'</h3>';
		$themes_styles = explode('|', Configuration::get('uhu_Theme_Styles'));
		$c_value = explode('|', Configuration::get('uhu_Style_Titles'));
		$id = 0;

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">Theme style</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		foreach ($themes_styles as $themes_style)
		{
			$selected = (Configuration::get('PS_UHU_STYLE') == $themes_style) ? 'checked="checked"' : '';
			$this->_html .= '	<div class="radio ">';
			$this->_html .= '		<label><input type="radio" name="selected_theme_style" id="selected_'.$themes_style.'
										" value="'.$themes_style.'" '.$selected.'>'.$themes_style.' - '.$c_value[$id ++].'</label>';
			$this->_html .= '	</div>';
		}
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitChoiseThemeStyle">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		//
		// switch Header
		//
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
		if ($mvalue[19] <> '')
		{
			$themes_styles = explode('|', $mvalue[19]);
			$this->_html .= '<div class="panel product-tab" id="tabPane1">';
			$this->_html .= '<h3>'.$this->l('Theme: header').'</h3>';
			$id = 0;
			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">Header style</label>';
			$this->_html .= '<div class="col-lg-9 ">';
			foreach ($themes_styles as $themes_style)
			{
				$selected = (Configuration::get('PS_UHU_HEADER') == $themes_style) ? 'checked="checked"' : '';
				$this->_html .= '	<div class="radio ">';
				$this->_html .= '		<label><input type="radio" name="selected_theme_header" id="selected_'.$themes_style.'
											" value="'.$themes_style.'" '.$selected.'>'.$themes_style.'</label>';
				$this->_html .= '	</div>';
			}
			$this->_html .= '</div>';
			$this->_html .= '</div>';

			$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
			$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitChoiseHeaderStyle">
								<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
			$this->_html .= '</div>';
			$this->_html .= '</div>';
		}

		$this->_html .= '</div>';
		$this->_html .= '</form>';
	}

	private function displayFormTabBackground($tab)
	{
		$this->_html .= '<form class="form-horizontal col-lg-9" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'
								"method="post" enctype="multipart/form-data">';
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$this->_html .= '<h3>'.$this->l('Body Background').'</h3>';
		$this->displayStyleColor(16);
		$this->displayStylePattern(17);
		$this->displayStylePattern(18);
		$this->displayStylePattern(20);
		$this->displayStylePattern(21);
		$this->displayStylePattern(470);

		$this->_html .= '<h3>'.$this->l('Header Background').'</h3>';
		$this->displayStyleColor(19);
		$this->displayStylePattern(23);
		$this->displayStylePattern(24);
		$this->displayStylePattern(26);
		$this->displayStylePattern(27);
		$this->displayStylePattern(471);

		$this->_html .= '<h3>'.$this->l('Footer Background').'</h3>';
		$this->displayStyleColor(25);
		$this->displayStylePattern(426);
		$this->displayStylePattern(427);
		$this->displayStylePattern(428);
		$this->displayStylePattern(429);
		$this->displayStylePattern(472);

		$this->_html .= '<h3>'.$this->l('All background images').'</h3>';
		$this->displayFormUploadConfig('bkimg', false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitConfigBackground">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
		$this->_html .= '</form>';
	}

	private function displayStyleColor($m_id)
	{
		$mid = $m_id + 1;
		$result = Db::getInstance()->getRow('
			SELECT modid, mycolor, title, colors
			FROM `'._DB_PREFIX_.'uhucolors`
			WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mid);

		$css_id = $result['modid'];//Configuration::get('uhu_modid_'.$m_name.'_'.$m_id);
		$c_value = $result['mycolor'];//Configuration::get('uhu_modvalue_'.$m_name.'_'.$m_id);
		$e_title = $result['title'];//Configuration::get('uhu_modname_'.$m_name.'_'.$m_id);
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$e_title.'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="form-group">
								<div class="col-lg-2">
									<div class="row">
										<div class="input-group">
											<input id="'.$css_id.'" name="link'.$css_id.'" type="text" size="33" data-hex="true"
												class="color mColorPickerInput mColorPicker" value="'.$c_value.'" style="background-color: '.$c_value.'; color: white; ">
											<span style="cursor:pointer;" id="icp_'.$css_id.'" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true">
											<img src="../img/admin/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
										</div>
									</div>
								</div>
							</div>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Choose a color with the color picker, or enter an HTML color (e.g. "lightblue", "#CC6600").');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayStylePattern($m_id)
	{
		$mid = $m_id + 1;
		$result = Db::getInstance()->getRow('
			SELECT modid, mycolor, title, colors
			FROM `'._DB_PREFIX_.'uhucolors`
			WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mid);

		$css_id = $result['modid'];
		$c_value = $result['colors'];
		$e_title = $result['title'];
		$this->_html .= '<div class="form-group ">';
		$this->_html .= '<label class="control-label col-lg-3">'.$e_title.'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-4">
									<input type="text" name="link'.$css_id.'" id="'.$css_id.'" value="'.$c_value.'">
								</div>
							</div>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Image filename you upload below');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabProductBlock($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';

		$this->_html .= '<div class="panel product-tab" id="tabPane1">';
		$this->_html .= '<h3>'.$this->l('Native Product Block').'</h3>';

		foreach ($this->getConfigurableModules() as $module)
		{
			$this->_html .= '<div class="form-group">';
			$this->_html .= '<label class="control-label col-lg-3">'.$module['label'].'</label>';
			$this->_html .= '<div class="col-lg-9">
								<div class="row">
									<div class="input-group col-lg-2">
										<span class="switch prestashop-switch">
											<input type="radio" name="'.$module['name'].'" id="'.$module['name'].'_on" value="1" '.
											(($module['value'] == 1) ? 'checked="checked"' : '').'>
											<label for="'.$module['name'].'_on">
												<i class="icon-check-sign color_success"></i> Yes
											</label>
											<input type="radio" name="'.$module['name'].'" id="'.$module['name'].'_off" value="0" '.
											(($module['value'] == 0) ? 'checked="checked"' : '').'>
											<label for="'.$module['name'].'_off">
												<i class="icon-ban-circle color_danger"></i> No
											</label>
											<a class="slide-button btn btn-default"></a>
										</span>
									</div>
								</div>
							</div>';
			$this->_html .= '</div>';
		}
		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitNativeProductConfig">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel product-tab" id="tabPane1">';
		$this->_html .= '<h3>'.$this->l('Setting').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Enable Slider').'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="uhu_tab_content_slider" id="uhu_tab_content_slider_on" value="1" '.
										((Configuration::get('uhu_tab_content_slider') == 1) ? 'checked="checked"' : '').'>
										<label for="uhu_tab_content_slider_on">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="uhu_tab_content_slider" id="uhu_tab_content_slider_off" value="0" '.
										((Configuration::get('uhu_tab_content_slider') == 0) ? 'checked="checked"' : '').'>
										<label for="uhu_tab_content_slider_off">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
		$this->_html .= '</br></br></br>';

		$this->_html .= '<h3>'.$this->l('Popular').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Number of products to be displayed').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="HOME_FEATURED_NBR" id="HOME_FEATURED_NBR" value="'.
								Configuration::get('HOME_FEATURED_NBR').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Set the number of products that you would like to display on homepage (default: 8).');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Category from which to pick products to be displayed').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="HOME_FEATURED_CAT" id="HOME_FEATURED_CAT" value="'.
								Configuration::get('HOME_FEATURED_CAT').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Choose the category ID of the products that you would like to display on homepage (default: 2 for "Home").');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Randomly display featured products').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<span class="switch prestashop-switch fixed-width-lg">';
		$this->_html .= '	<input type="radio" name="HOME_FEATURED_RANDOMIZE" id="HOME_FEATURED_RANDOMIZE_on" value="1" '.
										((Configuration::get('HOME_FEATURED_RANDOMIZE') == 1) ? 'checked="checked"' : '').'>
							<label for="HOME_FEATURED_RANDOMIZE_on">Yes</label>
							<input type="radio" name="HOME_FEATURED_RANDOMIZE" id="HOME_FEATURED_RANDOMIZE_off" value="0" '.
										((Configuration::get('HOME_FEATURED_RANDOMIZE') == 0) ? 'checked="checked"' : '').'>
							<label for="HOME_FEATURED_RANDOMIZE_off">No</label>
							<a class="slide-button btn"></a>';
		$this->_html .= '</span>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Enable if you wish the products to be displayed randomly (default: no).');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<h3>'.$this->l('New arrivals').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Products to display').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="NEW_PRODUCTS_NBR" id="NEW_PRODUCTS_NBR" value="'.
							Configuration::get('NEW_PRODUCTS_NBR').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Define the number of products to be displayed in this block.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Number of days for which the product is considered \'new\'').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="PS_NB_DAYS_NEW_PRODUCT" id="PS_NB_DAYS_NEW_PRODUCT" value="'.
							Configuration::get('PS_NB_DAYS_NEW_PRODUCT').'" class="fixed-width-xs">';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Always display this block').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<span class="switch prestashop-switch fixed-width-lg">';
		$this->_html .= '	<input type="radio" name="PS_BLOCK_NEWPRODUCTS_DISPLAY" id="PS_BLOCK_NEWPRODUCTS_DISPLAY_on" value="1" '.
										((Configuration::get('PS_BLOCK_NEWPRODUCTS_DISPLAY') == 1) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_NEWPRODUCTS_DISPLAY_on">Yes</label>
							<input type="radio" name="PS_BLOCK_NEWPRODUCTS_DISPLAY" id="PS_BLOCK_NEWPRODUCTS_DISPLAY_off" value="0" '.
										((Configuration::get('PS_BLOCK_NEWPRODUCTS_DISPLAY') == 0) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_NEWPRODUCTS_DISPLAY_off">No</label>
							<a class="slide-button btn"></a>';
		$this->_html .= '</span>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Show the block even if no new products are available.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<h3>'.$this->l('Best Sellers').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Products to display').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="PS_BLOCK_BESTSELLERS_TO_DISPLAY" id="PS_BLOCK_BESTSELLERS_TO_DISPLAY" value="'.
							Configuration::get('PS_BLOCK_BESTSELLERS_TO_DISPLAY').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Determine the number of product to display in this block');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Always display this block').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<span class="switch prestashop-switch fixed-width-lg">';
		$this->_html .= '	<input type="radio" name="PS_BLOCK_BESTSELLERS_DISPLAY" id="PS_BLOCK_BESTSELLERS_DISPLAY_on" value="1" '.
										((Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY') == 1) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_BESTSELLERS_DISPLAY_on">Yes</label>
							<input type="radio" name="PS_BLOCK_BESTSELLERS_DISPLAY" id="PS_BLOCK_BESTSELLERS_DISPLAY_off" value="0" '.
										((Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY') == 0) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_BESTSELLERS_DISPLAY_off">No</label>
							<a class="slide-button btn"></a>';
		$this->_html .= '</span>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Show the block even if no best sellers are available.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<h3>'.$this->l('Specials').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Number of cached files').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="BLOCKSPECIALS_NB_CACHES" id="BLOCKSPECIALS_NB_CACHES" value="'.
							Configuration::get('BLOCKSPECIALS_NB_CACHES').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Specials are displayed randomly on the front-end, but since it takes a lot of ressources, ');
		$this->_html .= $this->l('it is better to cache the results. The cache is reset daily. 0 will disable the cache.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Products to display').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<input type="text" name="BLOCKSPECIALS_SPECIALS_NBR" id="BLOCKSPECIALS_SPECIALS_NBR" value="'.
							Configuration::get('BLOCKSPECIALS_SPECIALS_NBR').'" class="fixed-width-xs">';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Define the number of products to be displayed in this block on home page.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Always display this block').'</label>';
		$this->_html .= '<div class="col-lg-9 ">';
		$this->_html .= '<span class="switch prestashop-switch fixed-width-lg">';
		$this->_html .= '	<input type="radio" name="PS_BLOCK_SPECIALS_DISPLAY" id="PS_BLOCK_SPECIALS_DISPLAY_on" value="1" '.
										((Configuration::get('PS_BLOCK_SPECIALS_DISPLAY') == 1) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_SPECIALS_DISPLAY_on">Yes</label>
							<input type="radio" name="PS_BLOCK_SPECIALS_DISPLAY" id="PS_BLOCK_SPECIALS_DISPLAY_off" value="0" '.
										((Configuration::get('PS_BLOCK_SPECIALS_DISPLAY') == 0) ? 'checked="checked"' : '').'>
							<label for="PS_BLOCK_SPECIALS_DISPLAY_off">No</label>
							<a class="slide-button btn"></a>';
		$this->_html .= '</span>';
		$this->_html .= '<p class="help-block">';
		$this->_html .= $this->l('Show the block even if no products are available.');
		$this->_html .= '</p>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitNativeProductConfig2">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
	}

	protected function getConfigurableModules()
	{
		return array(
			array(
				'label' => $this->l('Display Popular block on the home page'),
				'name' => 'homefeatured',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('homefeatured')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display New arrivals block on the home page'),
				'name' => 'blocknewproducts',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blocknewproducts')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display Best Sellers block on the home page'),
				'name' => 'blockbestsellers',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockbestsellers')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			),
			array(
				'label' => $this->l('Display Specials block on the home page'),
				'name' => 'blockspecials',
				'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockspecials')) && $module->isEnabledForShopContext(),
				'is_module' => true,
			)
		);
	}

	private function displayFormTabModContent($mod_name, $moduletitle)
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$count = fgets($fp);
			for ($mid = 0; $mid < $count; $mid++)
			{
				$mod_id = trim(fgets($fp));
				fgets($fp);
				$mod_total = trim(fgets($fp));
				$mod_adver = trim(fgets($fp));

				if ($mod_id == $mod_name)
				{
					$total = $mod_total;
					$adver = $mod_adver;
				}
			}
		}
		fclose($fp);

		if ($total > 0)
		{
			if (file_exists(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt'))
			{
				$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));
				$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt', 'rb');
				if ($fp2)
				{
					$this->_html .= '<h3>'.Module::getModuleName($moduletitle).'</h3>';
					for ($jorder = 0; $jorder < $total; $jorder++)
						for ($j = 0; $j < $total; $j++)
						{
							fseek($fp2, 0);
							for ($jj = 0; $jj < $j; $jj++)
							{
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
								fgets($fp2);
							}
							$morder = trim(fgets($fp2));
							fgets($fp2);
							$modid = trim(fgets($fp2));
							fgets($fp2);
							fgets($fp2);
							fgets($fp2);
							$mdisplay = trim(fgets($fp2));
							$modname = trim(fgets($fp2));
							$moddesp = trim(fgets($fp2));

							if ($jorder == $morder)
								if ($mdisplay == 'true')
									$this->displayFormInputConfig(
												$modname,
												'link'.$modid,
												$mvalue[$j],
												$moddesp,
												$mod_name
												);
						}
				}
				fclose($fp2);

				if ($adver == 'true')
					$this->displayFormUploadConfig($mod_name);
			}
		}
	}

	private function displayFormTabOneModContent($mvalue, $mod_name, $mid, $checkdisplay = '')
	{
		$modvalue = $mvalue[$mid];
		$moddesp = '';

		if (file_exists(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt'))
		{
			$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt', 'rb');
			if ($fp2)
			{
				for ($j = 0; $j < $mid; $j++)
				{
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
				}
				fgets($fp2);
				$mtype = trim(fgets($fp2));//fgets($fp2);
				$modid = trim(fgets($fp2));
				$modtitle = trim(fgets($fp2));//fgets($fp2);
				fgets($fp2);
				fgets($fp2);
				$mdisplay = trim(fgets($fp2));//fgets($fp2);
				$modname = trim(fgets($fp2));
				$moddesp = trim(fgets($fp2));
				fclose($fp2);
			}

			if ($checkdisplay == 'check')
			{
				if ($mdisplay == 'true')
				{
					$modid = 'link'.$modid;
					$this->displayFormInputConfig($modname, $modid, $modvalue, $moddesp, $mod_name, $mtype, $modtitle);
				}
			}
			else
			{
				$modid = 'link'.$modid;
				$this->displayFormInputConfig($modname, $modid, $modvalue, $moddesp, $mod_name, $mtype, $modtitle);
			}
		}
	}

	private function displayFormTabOneModContentCopy($mvalue, $mod_name, $mid, $newid, $checkdisplay = '')
	{
		$modvalue = $mvalue[$newid];
		$moddesp = '';

		if (file_exists(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt'))
		{
			$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt', 'rb');
			if ($fp2)
			{
				for ($j = 0; $j < $mid; $j++)
				{
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
					fgets($fp2);
				}
				fgets($fp2);
				$mtype = trim(fgets($fp2));//fgets($fp2);
				$modid = trim(fgets($fp2));
				$modtitle = trim(fgets($fp2));//fgets($fp2);
				fgets($fp2);
				fgets($fp2);
				$mdisplay = trim(fgets($fp2));//fgets($fp2);
				$modname = trim(fgets($fp2));
				$moddesp = trim(fgets($fp2));
				fclose($fp2);
			}

			if ($checkdisplay == 'check')
			{
				if ($mdisplay == 'true')
				{
					$modid = 'link'.$mod_name.'_'.$newid;
					$this->displayFormInputConfig($modname, $modid, $modvalue, $moddesp, $mod_name, $mtype, $modtitle);
				}
			}
			else
			{
				$modid = 'link'.$mod_name.'_'.$newid;
				$this->displayFormInputConfig($modname, $modid, $modvalue, $moddesp, $mod_name, $mtype, $modtitle);
			}
			//$modid = 'link'.$mod_name.'_'.$newid;
			//$this->displayFormInputConfig($modname, $modid, $modvalue, $moddesp, $mod_name, $mtype, $modtitle);
		}
	}

	private function displayFormTabLogo($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'logo';
		$moduletitle = 'uhulogo';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$title = array(
			'A' => 'position: Nav',
			'B' => 'position: Top',
			'C' => 'position: Footer Top',
			'D' => 'position: Footer',
			'E' => 'position: Footer Bottom'
			);
		$end = 'F';
		$pos = 0;

		for ($corder = 'A'; $corder < $end; $corder++)
		{
			$display = $mvalue[$pos];
			if ($display == 'yes')
			{
				$this->_html .= '<h3>'.$title[$corder].'</h3>';
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 2);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 3);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 5);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 6);
			}
			$pos = $pos + 7;
		}

		$this->_html .= '<h3>'.Module::getModuleName($moduletitle).'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabTopmenu($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$this->_html .= '<h3>'.$this->l('Setting').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Fixed Menu When Scrolling').'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="uhu_fixed_menu" id="uhu_fixed_menu_on" value="1" '.
										((Configuration::get('uhu_fixed_menu') == 1) ? 'checked="checked"' : '').'>
										<label for="uhu_fixed_menu_on">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="uhu_fixed_menu" id="uhu_fixed_menu_off" value="0" '.
										((Configuration::get('uhu_fixed_menu') == 0) ? 'checked="checked"' : '').'>
										<label for="uhu_fixed_menu_off">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
		$this->_html .= '</br></br></br>';

		//$mod_id = 27;
		$mod_name = 'topmenu';
		$moduletitle = 'uhutopmenu';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$this->_html .= '<h3>'.Module::getModuleName($moduletitle).'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 22);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 48);

		$this->_html .= '<h3>'.$this->l('Menuitem: Home').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 27);

		$this->_html .= '<h3>'.$this->l('Menuitem: Categories').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 41);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 8);

		$this->_html .= '<h3>'.$this->l('Menuitem: Products').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 6);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 14);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 15);

		$this->_html .= '<h3>'.$this->l('Menuitem: Brands').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 42);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 7);

		$this->_html .= '<h3>'.$this->l('Menuitem: News').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 49);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 50);

		$this->displayFormTabOneModContent($mvalue, $mod_name, 51);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 52);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 53);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 54);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 55);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 56);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 57);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 58);

		$this->displayFormTabOneModContent($mvalue, $mod_name, 59);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 9);

		$this->_html .= '<h3>'.$this->l('Menuitem: Custom Links').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 23);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 24);

		$this->_html .= '<h3>'.$this->l('Menuitem: Featured Categories with its sub-categories and thumbnails').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 26);

		$this->_html .= '<h3>'.$this->l('Menuitem: Full Custom A').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 25);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 30);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 31);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 32);

		$this->_html .= '<h3>'.$this->l('Menuitem: Full Custom B').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 33);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 34);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 35);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 36);

		$this->_html .= '<h3>'.$this->l('Menuitem: Full Custom C').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 37);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 38);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 39);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 40);

		$this->_html .= '<h3>'.$this->l('Menuitem: More Full Custom').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 43);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 44);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 45);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 46);

		$this->_html .= '<h3>'.$this->l('For Expert: Grid in Categories').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 0);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 1);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 11);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 13);

		$this->_html .= '<h3>'.$this->l('For Expert: Grid in Products').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 2);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 3);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 16);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 17);

		$this->_html .= '<h3>'.$this->l('For Expert: Grid in Brands').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 4);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 5);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 18);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 19);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 21);

		$this->_html .= '<h3>'.$this->l('For Mobile').'</h3>';
		$this->displayFormTabOneModContent($mvalue, $mod_name, 60);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 61);
		$this->displayFormTabOneModContent($mvalue, $mod_name, 62);

		$this->_html .= '<h3>'.$this->l('Advertising').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabSlider($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		//$mod_id = 32;
		$mod_name = 'slider';
		$moduletitle = 'uhuslider';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$this->_html .= '<h3>'.Module::getModuleName($moduletitle).'</h3>';
		$items = array('4','2','5','3','6','9','14','10','13','15','16');
		foreach ($items as $item)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $item, 'check');

		$slider_number = $mvalue[2];
		for ($i = 0; $i < $slider_number; $i++)
		{
			$this->_html .= '<h3>'.$this->l('Slider').':'.($i + 1).'</h3>';
			for ($j = 0; $j < 9; $j++)
				$this->displayFormTabOneModContentCopy($mvalue, $mod_name, 20 + $j, 20 + $i * 10 + $j);
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabFeatured($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'featured';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));
		$title = array(
			'A' => 'Featured products',
			'B' => 'Featured products with slider',
			'C' => 'Translations'
			);

		$this->_html .= '<h3>'.$title['A'].'</h3>';
		$items = array('7','1','2','3','6','9','10','11', '14');
		foreach ($items as $item)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $item);

		$this->_html .= '<h3>'.$title['B'].'</h3>';
		$items = array('22','16','17','18','23','24','25','28','32','33','34');
		foreach ($items as $item)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $item);

		$this->_html .= '<h3>'.$title['C'].'</h3>';
		$items = array('4','26','30');
		foreach ($items as $item)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $item);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabNew($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'new';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		if (strstr($mvalue[7], 'yes') <> '')
		{
			$this->_html .= '<h3>1: '.$this->l('New Products').'</h3>';
			$items = array('7','15','1','2','3','4','33','34','37', '14', '16', '13', '17', '18', '19', '20', '12');
			foreach ($items as $item)
				$this->displayFormTabOneModContent($mvalue, $mod_name, $item, 'check');
		}

		if (strstr($mvalue[8], 'yes') <> '')
		{
			$this->_html .= '<h3>'.$this->l('New Products use Tab Control').'</h3>';
			$items = array('8','10','6','23','24','25','26','27','28', '39', '30', '38', '21', '22', '29');
			foreach ($items as $item)
				$this->displayFormTabOneModContent($mvalue, $mod_name, $item, 'check');
		}

		if (strstr($mvalue[9], 'yes') <> '')
		{
			$this->_html .= '<h3>'.$this->l('New products with slider').'</h3>';
			$items = array('9','42','35','36','43','44','45','46','47', '40', '48', '31', '32', '41');
			foreach ($items as $item)
				$this->displayFormTabOneModContent($mvalue, $mod_name, $item, 'check');
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabBest($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		//$mod_id = 11;
		$mod_name = 'best';
		$moduletitle = 'uhubestsellers';
		$this->displayFormTabModContent($mod_name, $moduletitle);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabAdvertising($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$this->_html .= '<h3>'.$this->l('Top Banner Setting').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('Top Banner Open When visit').'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="uhu_top_banner" id="uhu_top_banner_on" value="1" '.
										((Configuration::get('uhu_top_banner') == 1) ? 'checked="checked"' : '').'>
										<label for="uhu_top_banner_on">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="uhu_top_banner" id="uhu_top_banner_off" value="0" '.
										((Configuration::get('uhu_top_banner') == 0) ? 'checked="checked"' : '').'>
										<label for="uhu_top_banner_off">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
		$this->_html .= '</br></br></br>';

		$mod_name = 'advertising';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$title = array(
			'A' => 'Position: Top',
			'B' => 'Position: TopColumn',
			'C' => 'Position: TopLeft',
			'D' => 'Position: TopRight',
			'E' => 'Position: TopBanner',
			'F' => 'Position: Home',
			'G' => 'Position: BottomLeft',
			'H' => 'Position: BottomRight',
			'I' => 'Position: BottomBanner',
			'J' => 'Position: Footer'
			);
		$end = 'K';
		$pos = 0;

		for ($corder = 'A'; $corder < $end; $corder++)
		{
			$display = $mvalue[$pos];
			if ($display <> 'no' && $display <> '')
			{
				if ($corder == 'A' && $mvalue[13] == 'uhubanner')
				{
					$this->_html .= '<h3>'.$title[$corder].'</h3>';
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 6, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 11, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 12, 'check');
				}
				else
				{
					$this->_html .= '<h3>'.$title[$corder].'</h3>';
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 4, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 5, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 6, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 7, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 8, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 9, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 10, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 11, 'check');
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 12, 'check');
				}
			}
			$pos = $pos + 15;
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabReassurance($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'reinsurance';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));
		$title = array(
			'A' => 'Position: Top',
			'B' => 'Position: TopColumn',
			'C' => 'Position: Home',
			'D' => 'Position: FooterNav',
			'E' => 'Position: Footer'
			);
		$end = 'F';
		$pos = 0;

		for ($corder = 'A'; $corder < $end; $corder++)
		{
			$displays = explode('|', $mvalue[$pos]);
			if (in_array('yes', $displays))
			{
				$this->_html .= '<h3>'.$title[$corder].'</h3>';
				$items = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19');
				foreach ($items as $item)
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + $item, 'check');
			}
			$pos = $pos + 20;
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabSocial($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'social';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));
		$title = array(
			'A' => 'hookDisplayBanner',
			'B' => 'hookdisplayTop',
			'C' => 'hookdisplayFooterNav',
			'D' => 'hookFooter',
			'E' => 'hookdisplayFooterBanner'
			);
		$end = 'F';
		$pos = 0;

		for ($corder = 'A'; $corder < $end; $corder++)
		{
			$display = $mvalue[$pos];
			if ($display == 'yes')
			{
				$this->_html .= '<h3>'.$title[$corder].'</h3>';
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 1);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 2);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 3);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 4);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 5);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 6);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 7);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 8);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 9);
			}
			$pos = $pos + 10;
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabFacebook($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'facebook';
		$moduletitle = 'uhufacebook';
		$this->displayFormTabModContent($mod_name, $moduletitle);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormCategories($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'categories';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$this->_html .= '<h3>'.$this->l('General').'</h3>';
		for ($j = 0; $j < 9; $j++)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $j, 'check');

		$item_number = $mvalue[5];
		for ($i = 0; $i < $item_number; $i++)
		{
			$this->_html .= '<h3>'.$this->l('Column').':'.($i + 1).'</h3>';
			for ($j = 0; $j < 10; $j++)
				$this->displayFormTabOneModContentCopy($mvalue, $mod_name, 10 + $j, 10 + $i * 10 + $j, 'check');
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormNews($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'news';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$this->_html .= '<h3>'.$this->l('General').'</h3>';
		for ($j = 0; $j < 9; $j++)
			$this->displayFormTabOneModContent($mvalue, $mod_name, $j, 'check');

		$item_number = $mvalue[5];
		for ($i = 0; $i < $item_number; $i++)
		{
			$this->_html .= '<h3>'.$this->l('Column').':'.($i + 1).'</h3>';
			for ($j = 0; $j < 10; $j++)
				$this->displayFormTabOneModContentCopy($mvalue, $mod_name, 10 + $j, 10 + $i * 10 + $j, 'check');
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabCopyright($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'copyright';
		$moduletitle = 'uhucopyright';
		$this->displayFormTabModContent($mod_name, $moduletitle);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabNewsletter($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'newsletter';
		$moduletitle = 'blocknewsletter';
		$this->displayFormTabModContent($mod_name, $moduletitle);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabTags($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'qttags';
		$moduletitle = 'uhutags';
		$this->displayFormTabModContent($mod_name, $moduletitle);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabContact($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$mod_name = 'contactus';
		$mvalue = Tools::unserialize(Configuration::get('uhu_value_'.$mod_name));

		$title = array(
			'A' => 'Position: Footer',
			'B' => 'Position: Top',
			'C' => 'Position: Banner',
			'D' => 'Position: Home',
			'E' => 'Position: Slider'
			);
		$end = 'F';
		$pos = 0;
		$sub = 55;

		for ($corder = 'A'; $corder < $end; $corder++)
		{
			$display = $pos + 5;
			$displays = explode('|', $mvalue[$display]);
			if (in_array('yes', $displays))
			{
				$this->_html .= '<h3>'.$title[$corder].'</h3>';

				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 10);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 0);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 6);

				$owl = $pos + 7;
				if (strstr($mvalue[$owl], 'yes') == '')
				{
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 1);
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 2);
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 3);
					$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 4);
				}
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 8);
				// subtitle
				$this->displayFormTabOneModContent($mvalue, $mod_name, $sub);
				$this->displayFormTabOneModContent($mvalue, $mod_name, $pos + 9);
			}
			$pos = $pos + 11;
			$sub++;
		}

		$this->_html .= '<h3>'.$this->l('Image').'</h3>';
		$this->displayFormUploadConfig($mod_name, false);

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/prefs.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
							class="button" type="submit" name="submitCustomConfig" value="'.$this->l('Save Custom Config').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormTabCustom($tab)
	{
		$this->_html .= '<div class="profile-'.$tab.' tab-profile product-tab-content" style="display:none">';
		$this->_html .= '<div class="panel product-tab" id="tabPane1">';

		$this->_html .= '<h3>'.$this->l('Add your CSS styles').'</h3>';
		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$this->l('CSS:').'</label>';
		$this->_html .= '<div class="col-lg-9">
								<textarea id="customcss" name="customcss" cols="100" rows="25">'.Configuration::get('uhu_custom_css').'</textarea>	
						</div>';
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer" id="toolbar-footer">';
		$this->_html .= '<input style="background:url(../img/admin/appearance.gif) no-repeat 8px 3px; padding: 4px 8px 4px 32px;"
									class="button" type="submit" name="submitCustomCSS" value="'.$this->l('Save Custom CSS').'"/>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormInputConfig($c_title, $css_id, $css_value, $pixel, $modname, $mtype = '', $mlang = '')
	{
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$default_form_language = $language->id;

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$c_title.'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="form-group" style="margin-bottom: 3px;">
								<div class="col-lg-11">';
		$css_lang = array();
		if ($mtype == 'textarea')
		{
			$languages = Language::getLanguages(true);
			if ($mlang == 'Multi-languages' && count($languages) > 1)
			{
				$values = explode('|', $css_value);
				foreach ($values as $value)
				{
					$langs = explode('¤', $value);
					if (isset($langs[1]) && $langs[1])
						$css_lang[$langs[1]] = $langs[0];
				}
				$this->_html .= '<div class="form-group">';
				foreach ($languages as $lang)
				{
					$this->_html .= '<div class="translatable-field lang-'.$lang['id_lang'].'"
											style="display: '.($lang['id_lang'] == $default_form_language ? 'block' : 'none').';">';
					$this->_html .= '<div class="col-lg-9">';
					$this->_html .= '<textarea class="textarea-autosize" name="'.$css_id.'_'.$lang['id_lang'].'"
											cols="30" rows="5">'.$css_lang[$lang['iso_code']].'</textarea>';
					$this->_html .= '</div>';
					$this->_html .= '<div class="col-lg-2">';
					$this->_html .= '<button type="button" class="btn btn-default dropdown-toggle"
											tabindex="-1" data-toggle="dropdown">'.$lang['iso_code'].'<i class="icon-caret-down"></i></button>';
					$this->_html .= '<ul class="dropdown-menu">';
					foreach ($languages as $language)
						$this->_html .= '<li><a href="javascript:hideOtherLanguage('.$language['id_lang'].');" tabindex="-1">'.$language['name'].'</a></li>';
					$this->_html .= '</ul>';
					$this->_html .= '</div>';
					$this->_html .= '</div>';
				}
				$this->_html .= '</div>';
			}
			else
			{
				$this->_html .= '<div class="form-group">';
				$this->_html .= '<div class="col-lg-9">';
				$this->_html .= '	<textarea class="textarea-autosize" name="'.$css_id.'" cols="30" rows="5">'.$css_value.'</textarea>';
				$this->_html .= '</div>';
				$this->_html .= '</div>';
			}
		}
		else
		{
			$languages = Language::getLanguages(true);
			if ($mlang == 'Multi-languages' && count($languages) > 1)
			{
				$values = explode('|', $css_value);
				foreach ($values as $value)
				{
					$langs = explode('¤', $value);
					if (isset($langs[1]) && $langs[1])
						$css_lang[$langs[1]] = $langs[0];
				}
				$this->_html .= '<div class="form-group">';
				foreach ($languages as $lang)
				{
					$this->_html .= '<div class="translatable-field lang-'.$lang['id_lang'].'"
											style="display: '.($lang['id_lang'] == $default_form_language ? 'block' : 'none').';">';
					$this->_html .= '<div class="col-lg-9">';
					$this->_html .= '<input type="text" name="'.$css_id.'_'.$lang['id_lang'].'" value="'.$css_lang[$lang['iso_code']].'">';
					$this->_html .= '</div>';
					$this->_html .= '<div class="col-lg-2">';
					$this->_html .= '<button type="button" class="btn btn-default dropdown-toggle"
											tabindex="-1" data-toggle="dropdown">'.$lang['iso_code'].'<i class="icon-caret-down"></i></button>';
					$this->_html .= '<ul class="dropdown-menu">';
					foreach ($languages as $language)
						$this->_html .= '<li><a href="javascript:hideOtherLanguage('.$language['id_lang'].');" tabindex="-1">'.$language['name'].'</a></li>';
					$this->_html .= '</ul>';
					$this->_html .= '</div>';
					$this->_html .= '</div>';
				}
				$this->_html .= '</div>';
			}
			else
			{
				$this->_html .= '<div class="form-group">';
				$this->_html .= '<div class="col-lg-9">';
				$this->_html .= '	<input type="text" name="'.$css_id.'" value="'.$css_value.'">';
				$this->_html .= '</div>';
				$this->_html .= '</div>';
			}
		}

		if ($pixel <> '')
			$this->_html .= '<p class="help-block" style="margin-bottom: 1px; font-style: normal;">'.$pixel.'</p>';

		$this->_html .= '		</div>';

		$html = '';
		if (strstr($css_value, '.jpg') <> '' || strstr($css_value, '.png') <> '' || strstr($css_value, '.gif') <> '')
		{
			$html .= '<div class="col-lg-11">';
			$html .= '<div class="form-group">';
			$html .= '<div class="col-lg-9">';
			if (strstr($css_value, ',') <> '')
				$item_all = explode(',', $css_value);
			else
				$item_all = explode('^', $css_value);
			foreach ($item_all as $itemss)
			{
				$items = explode('^', $itemss);
				foreach ($items as $item)
				{
					if (strstr($item, 'http://') <> '')
					{
						$html .= '<div style="float: left;">
											<img style="border:1px solid #ccc;padding:10px;margin:20px 20px 0 0;max-width:200px;max-height:100px;" src="'.$item.'" />';
						$html .= '</div>';
					}
					else
					{
						$html .= '<div style="float: left;">
											<img style="border:1px solid #ccc;padding:10px;margin:20px 20px 0 0;max-width:200px;max-height:100px;" 
											src="'._MODULE_DIR_.$this->name.'/views/img/'.$modname.'/'.$item.'" />';
						$html .= '</div>';
					}
				}
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		$this->_html .= '	</div>';
		$this->_html .= '</div>';
		$this->_html .= '</div>';
	}

	private function displayFormUploadConfig($css_id, $hr = true)
	{
		if ($hr)
			$this->_html .= '<hr>';

		if (is_dir(_PS_ROOT_DIR_.'/modules/'.$this->name.'/views/img/'.$css_id))
		{
			$patternfile = scandir(_PS_ROOT_DIR_.'/modules/'.$this->name.'/views/img/'.$css_id);
			$exclude_list = array('.', '..', 'index.php');
			$pattern = count($patternfile);
			for ($i = 0; $i < $pattern; $i++)
				if (!in_array($patternfile[$i], $exclude_list) && $patternfile[$i] != '')
				{
				$this->_html .= '<div class="form-group">';
				$this->_html .= '<label class="control-label col-lg-3">'.$patternfile[$i].'</label>';
				$this->_html .= '<div class="col-lg-9">';
				$this->_html .= '<div class="form-group">';
				$this->_html .= '<div class="col-lg-6">';
				$this->_html .= '	<img style="border:1px solid #ccc;padding:10px;margin:0;max-width:200px;max-height:100px;"
										src="'._MODULE_DIR_.$this->name.'/views/img/'.$css_id.'/'.$patternfile[$i].'" />';
				$this->_html .= '	<div class="btn-group-action pull-right" style="line-height: 12px;">';
				$this->_html .= '		<a class="btn btn-default" href="index.php?controller=AdminModules&amp;configure='.
											$this->name.'&amp;token='.Tools::getAdminTokenLite('AdminModules').'&amp;configure='.
											$this->name.'&amp;delete_id_image='.$css_id.'/'.$patternfile[$i].'">
											<i class="icon-trash"></i>
											Delete
										</a>';

				$this->_html .= '	</div>';
				$this->_html .= '</div>';
				$this->_html .= '</div>';
				$this->_html .= '</div>
								</div>';
				}
		}

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3 ">'.$this->l('New Pattern:').'</label>';
		$this->_html .= '<div class="col-lg-9">
							<input id="adv_'.$css_id.'_file" type="file" name="adv_'.$css_id.'_file" />
						</div>
						</div>';

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3 "></label>';
		$this->_html .= '<div class="col-lg-9">
							<input class="button" type="submit" name="submitBackpattern_adv_'.$css_id.'" value="'.$this->l('Upload Pattern').'"/>
						</div>
						</div>';

	}

	private function loadConfigFile()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$total = trim(fgets($fp));

			for ($i = 0; $i < $total; $i++)
			{
				$mod_name = trim(fgets($fp));
				fgets($fp);
				$mod_count = trim(fgets($fp));
				fgets($fp);

				if (file_exists(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt'))
				{
					$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_'.$mod_name.'.txt', 'rb');
					if ($mod_name <> 'font' && $mod_name <> 'width' && $mod_name <> 'color' && $mod_name <> 'border')
					{
						$mvalue = array();
						for ($j = 0; $j < $mod_count; $j++)
						{
							fgets($fp2);
							fgets($fp2);
							fgets($fp2);
							fgets($fp2);
							fgets($fp2);
							$myvalue = trim(fgets($fp2));
							$mvalue[$j] = str_replace('[br]', "\r\n", $myvalue); // 转换换行符
							fgets($fp2);
							fgets($fp2);
							fgets($fp2);
						}
						Configuration::updateValue('uhu_value_'.$mod_name, serialize($mvalue));
					}
					fclose($fp2);
				}
			}
		}
		fclose($fp);
	}

	private function loadVersion()
	{
		$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_setting.txt', 'rb');
		if ($fp2)
		{
			for ($i = 0; $i < 14; $i++)
				fgets($fp2);
			$temp = trim(fgets($fp2));
			if ($temp <> '')
			{
				if ($temp <> Configuration::get('uhu_Theme_Version'))
				{
					Configuration::updateValue('uhu_Theme_Version', $temp);
					$this->loadConfigFile();
				}
			}
			for ($i = 0; $i < 8; $i++)
				fgets($fp2);
			$temp = trim(fgets($fp2));
			if ($temp <> '')
			{
				if ($temp <> Configuration::get('uhu_Theme_Date'))
					Configuration::updateValue('uhu_Theme_Date', $temp);
			}
		}
		fclose($fp2);
	}
}