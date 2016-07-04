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

class Uhuthemecolor extends Module
{
	public function __construct()
	{
		$this->name = 'uhuthemecolor';
		$this->tab = 'others';
		$this->version = '1.2.0';
		$this->bootstrap = true;
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Theme configurator - Color';
		$this->description = $this->l('Change the colors of your theme.');

		$this->init();
	}

	protected function init()
	{
		$this->datadir = '/home/www/design/data_v20/';
		$this->changelist = array();
		$this->colorstyles = array();
	}

	protected function hookBefore()
	{
		$results = Db::getInstance()->executeS('SELECT DISTINCT modtitle FROM `'._DB_PREFIX_.'uhucolors`');
		$lid = 0;
		foreach ($results as $result)
		{
			if ($result['modtitle'] <> '')
			{
				$title = $result['modtitle'];
				$display = Db::getInstance()->getValue('SELECT active FROM `'._DB_PREFIX_.'uhucolors` WHERE modtitle = \''.pSQL($title).'\'');
				$this->changelist['name'][$lid] = 'color'.$lid;
				$this->changelist['title'][$lid] = $title;
				$this->changelist['active'][$lid] = $display;

				$listname = $this->changelist['name'][$lid];
				$this->changelist[$listname]['item'] = array();
				$items = Db::getInstance()->executeS('SELECT id_item FROM `'._DB_PREFIX_.'uhucolors` WHERE modtitle = \''.pSQL($title).'\'');
				foreach ($items as $key => $item)
					$this->colorstyles[$listname]['item'][$key] = $item['id_item'] - 1;
				foreach ($this->colorstyles[$listname]['item'] as $item)
				{
					$this->colorstyles[$listname]['modid'][$item] = $this->getColorModid($item);
					$this->colorstyles[$listname]['title'][$item] = $this->getColorTitle($item);
					$this->colorstyles[$listname]['type'][$item] = $this->getColorType($item);
					$this->colorstyles[$listname]['value'][$item] = $this->getColorValue($item);
					$this->colorstyles[$listname]['selector'][$item] = $this->getColorSelector($item);
				}
				$lid++;
			}
		}
		$this->list_total = $lid;
	}

	public function install()
	{
			//|| !$this->registerHook('displayBackOfficeHeader'))
		if (!parent::install() ||
			!$this->installDB() ||
			!$this->registerHook('displayFooter') ||
			!$this->registerHook('displayBackOfficeHeader'))
			return false;

		$this->loadConfigFile();

		return true;
	}

	private function installDB()
	{
		return (
			Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'uhucolors`') &&
			Db::getInstance()->Execute('
			CREATE TABLE `'._DB_PREFIX_.'uhucolors` (
					`id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`title` VARCHAR(50),
					`display` VARCHAR(30),
					`selector` TEXT,
					`colors` VARCHAR(100),
					`mycolor` VARCHAR(100),
					`modid` VARCHAR(20),
					`modinfo` VARCHAR(200),
					`type` VARCHAR(10),
					`modtitle` VARCHAR(50),
					`modorder` VARCHAR(10),
					`active` VARCHAR(10),
					PRIMARY KEY (`id_item`)
			) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;')
		);
	}

	public function uninstall()
	{
		Configuration::deleteByName('uhu_colorsetting');

		if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'uhucolors`') || !parent::uninstall())
			return false;
		return true;
	}

	public function hookDisplayFooter()
	{
		//$html = '';
		if (Tools::isSubmit('submitColorConfigurator'))
		{
			$this->postSave(true);
			$current_url = Tools::safeOutput(preg_replace('/&deleteFilterTemplate=[0-9]*&id_layered_filter=[0-9]*/', '', $_SERVER['REQUEST_URI']));
			Tools::redirect($current_url);
		}

		if (Configuration::get('uhu_color_front_panel') == 1 || Configuration::get('PS_UHU_LIVE_DEMO') == 1)
		{
			$this->hookBefore();
			$this->smarty->assign('changelist', $this->changelist);
			$this->smarty->assign('colorstyles', $this->colorstyles);
			$this->smarty->assign('livedemo', Configuration::get('PS_UHU_LIVE_DEMO'));

			return $this->display(__FILE__, 'live_colors.tpl');
		}
	}

	public function getContent()
	{
		$this->_html = '';

		$this->loadConfigFile();

		$this->postProcess();
		$this->displayForm();

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';

		if (Tools::isSubmit('submitConfigColor'))
		{
			$results = Db::getInstance()->executeS('SELECT DISTINCT type FROM `'._DB_PREFIX_.'uhucolors`');
			foreach ($results as $result)
			{
				if ($result['type'] <> '')
				{
					$type = $result['type'];
					$modid = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhucolors` WHERE type = \''.pSQL($type).'\'');
					if (Tools::getValue($modid) == 0)
						$active = 'hidden';
					else
						$active = '';
					$sql = 'UPDATE `'._DB_PREFIX_.'uhucolors` SET active = \''.pSQL($active).'\' WHERE type = \''.pSQL($type).'\'';
					Db::getInstance()->execute($sql);
				}
			}
		}

		if (Tools::isSubmit('submitCustomColor'))
			$this->postSave(true);

		if ($errors)
			echo $this->displayError($errors);
	}

	public function postSave($backimg)
	{
		$file = '';

		$item_total = Configuration::get('uhu_colorsetting');
		for ($i = 0; $i < $item_total; $i++)
			$file .= $this->postProcessStyleColor($i);

		$file .= $this->postProcessStyleBackground($backimg, '16', '17', '18', '/views/img/body/');
		$file .= $this->postProcessStyleBackground($backimg, '19', '20', '21', '/views/img/header/');
		$file .= $this->postProcessStyleBackground($backimg, '22', '23', '24', '/views/img/columns/');
		$file .= $this->postProcessStyleBackground($backimg, '25', '26', '27', '/views/img/footer/');

		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
		{
			if (Tools::getValue('theme') && Tools::getValue('live_configurator') == 1)
				$themecolor = Tools::getValue('theme');
			else
				$themecolor = Configuration::get('PS_UHU_THEME');
			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/'.$themecolor.'.css', 'wb');
			fputs($fp, $file);
			fclose($fp);
			$this->saveConfigFile();
		}
		else
		{
			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/mycolor.css', 'wb');
			fputs($fp, $file);
			fclose($fp);
		}
	}

	private function postProcessStyleColor($m_id)
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
		if (Tools::getIsset($css_id))
		{
			$cssvalue = Tools::getValue($css_id);

			if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			{
				$colors = Db::getInstance()->getValue('SELECT colors FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
				$color = explode('|', $colors);

				if (Tools::getValue('theme') && Tools::getValue('live_configurator') == 1)
					$themecolor = Tools::getValue('theme');
				else
					$themecolor = Configuration::get('PS_UHU_THEME');
				switch ($themecolor)
				{
				case 'theme1':
					$color[0] = $cssvalue;
					break;
				case 'theme2':
					$color[1] = $cssvalue;
					break;
				case 'theme3':
					$color[2] = $cssvalue;
					break;
				}
				$colors = $color[0].'|'.$color[1].'|'.$color[2];

				Db::getInstance()->execute('
					UPDATE `'._DB_PREFIX_.'uhucolors` SET
						colors = \''.pSQL($colors).'\'
						WHERE id_item = '.(int)$mid
					);
			}
			else
				Db::getInstance()->execute('
					UPDATE `'._DB_PREFIX_.'uhucolors` SET
						mycolor = \''.pSQL($cssvalue).'\'
						WHERE id_item = '.(int)$mid
					);

			if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '' && $cssvalue <> '#00000000')
				$code = $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";
		}
		return $code;
	}

	private function postProcessStyleBackground($backimg, $m_bg, $m_pattern, $m_bgpos, $imagefolder)
	{
		$code = '';

		if ($backimg)
		{
			$mbg = $m_bg + 1;
			$mpattern = $m_pattern + 1;
			$mbgpos = $m_bgpos + 1;

			$result_bg = Db::getInstance()->getRow('
				SELECT modid, display, selector
				FROM `'._DB_PREFIX_.'uhucolors`
				WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mbg);

			$css_bg = $result_bg['modid'];
			$csstitle = $result_bg['display'];
			$selectors = $result_bg['selector'];

			if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			{
				$colors = Db::getInstance()->getValue('SELECT colors FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mbg);
				$color = explode('|', $colors);
				$colors = Db::getInstance()->getValue('SELECT colors FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mpattern);
				$pattern = explode('|', $colors);
				$colors = Db::getInstance()->getValue('SELECT colors FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mbgpos);
				$pos = explode('|', $colors);

				if (Tools::getValue('theme') && Tools::getValue('live_configurator') == 1)
					$themecolor = Tools::getValue('theme');
				else
					$themecolor = Configuration::get('PS_UHU_THEME');
				switch ($themecolor)
				{
				case 'theme1':
					$bgcolor = $color[0];
					$bgpattern = $pattern[0];
					$bgpos = $pos[0];
					break;
				case 'theme2':
					$bgcolor = $color[1];
					$bgpattern = $pattern[1];
					$bgpos = $pos[1];
					break;
				case 'theme3':
					$bgcolor = $color[2];
					$bgpattern = $pattern[2];
					$bgpos = $pos[2];
					break;
				}
			}
			else
			{
				$bgcolor = Db::getInstance()->getValue('SELECT mycolor FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mbg);
				$bgpattern = Db::getInstance()->getValue('SELECT mycolor FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mpattern);
				$bgpos = Db::getInstance()->getValue('SELECT mycolor FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mbgpos);
			}

			$cssvalue = '';
			if ($bgcolor <> '')
				$cssvalue .= $bgcolor;

			if ($bgpattern <> '0_noimage.gif' && $bgpattern <> '')
			{
				$cssvalue .= ' url(../..'.$imagefolder.$bgpattern.')';
				$cssvalue .= ' '.$bgpos;
			}

			if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '')
				$code = $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";
		}
		else
		{
			$mbg = $m_bg + 1;
			$result_bg = Db::getInstance()->getRow('
				SELECT modid, display, selector
				FROM `'._DB_PREFIX_.'uhucolors`
				WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mbg);

			$css_bg = $result_bg['modid'];
			$csstitle = $result_bg['display'];
			$selectors = $result_bg['selector'];
			$bgcolor = Tools::getValue($css_bg);

			$mpattern = $m_pattern + 1;
			$css_pattern = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mpattern);
			$mbgpos = $m_bgpos + 1;
			$css_bgpos = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mbgpos);

			$bgpattern = Tools::getValue($css_pattern);
			$bgpos = Tools::getValue($css_bgpos);

			$cssvalue = '';
			if ($bgcolor <> '')
				$cssvalue .= $bgcolor;

			if ($bgpattern <> '0_noimage.gif' && $bgpattern <> '')
			{
				$cssvalue .= ' url(../..'.$imagefolder.$bgpattern.')';
				$cssvalue .= ' '.$bgpos;
			}

			if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '')
				$code = $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";
		}
		return $code;
	}

	private function loadConfigFile()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$mod_total = fgets($fp);
			for ($i = 0; $i < $mod_total; $i++)
			{
				$temp1 = trim(fgets($fp));
				fgets($fp);
				$temp3 = trim(fgets($fp));
				fgets($fp);
				if ($temp1 == 'color')
				{
					$item_total = $temp3;
					Configuration::updateValue('uhu_colorsetting', $item_total);

					$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_color.txt', 'rb');
					if ($fp2)
					for ($j = 0; $j < $item_total; $j++)
					{
						$modorder = trim(fgets($fp2));
						$colors = trim(fgets($fp2));
						$modid = trim(fgets($fp2));

						$modtitle = trim(fgets($fp2));
						$modinfo = trim(fgets($fp2));
						$type = trim(fgets($fp2));

						$display = trim(fgets($fp2));
						$title = trim(fgets($fp2));
						$selector = trim(fgets($fp2));

						$result = $this->installColorsetting(
										$this->context->shop->id,
										$title,
										$display,
										$selector,
										$colors,
										$type,
										$modid,
										$modinfo,
										$modtitle,
										$modorder
									);
						if (!$result)
						echo 'false<br>';
					}
					fclose($fp2);
				}
			}
			fclose($fp);
		}

		$active = '';
		$this->updateActive('type01', $active);
		$this->updateActive('type02', $active);
		$this->updateActive('type03', $active);
		$this->updateActive('type04', $active);
		$this->updateActive('type05', $active);
		$this->updateActive('type06', $active);
		$this->updateActive('type07', $active);
		$this->updateActive('type10', $active);
		$this->updateActive('type12', $active);
	}

	private function saveConfigFile()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$mod_total = fgets($fp);
			for ($i = 0; $i < $mod_total; $i++)
			{
				$temp1 = trim(fgets($fp));
				fgets($fp);
				$temp3 = trim(fgets($fp));
				fgets($fp);
				if ($temp1 == 'color')
				{
					$item_total = $temp3;
					$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_color.txt', 'wb');
					for ($j = 0; $j < $item_total; $j++)
					{
						$mid = $j + 1;
						$result = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);

						$colors = $result['colors'];
						$title = $result['title'];
						$display = $result['display'];
						$modinfo = $result['modinfo'];
						$selector = $result['selector'];
						$modid = $result['modid'];
						$modorder = $result['modorder'];
						$mycolor = '';

						fputs($fp2, $modorder);
						fputs($fp2, "\n");
						fputs($fp2, $colors);
						fputs($fp2, "\n");
						fputs($fp2, $modid);
						fputs($fp2, "\n");
						fputs($fp2, $title);
						fputs($fp2, "\n");
						fputs($fp2, $modinfo);
						fputs($fp2, "\n");
						fputs($fp2, $mycolor);
						fputs($fp2, "\n");
						fputs($fp2, $display);
						fputs($fp2, "\n");
						fputs($fp2, $title);
						fputs($fp2, "\n");
						fputs($fp2, $selector);
						fputs($fp2, "\n");
					}
					fclose($fp2);
				}
			}
			fclose($fp);
		}
	}

	protected function installColorsetting($id_shop, $title, $display, $selector, $colors, $type, $modid, $modinfo, $modtitle, $modorder)
	{
		$result = true;
		$hidden = 'hidden';

		$id_item = Db::getInstance()->getValue('SELECT id_item FROM `'._DB_PREFIX_.'uhucolors` WHERE `modid` = \''.pSQL($modid).'\'');
		if ($id_item > 0)
			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'uhucolors` SET
					title = \''.pSQL($title).'\',
					display = \''.pSQL($display).'\',
					selector = \''.pSQL($selector).'\',
					colors = \''.pSQL($colors).'\',
					modinfo = \''.pSQL($modinfo).'\',
					modorder = \''.pSQL($modorder).'\',
					type = \''.pSQL($type).'\',
					modtitle = \''.pSQL($modtitle).'\'
					WHERE id_item = '.(int)$id_item
				);
		else
			$result &= Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'uhucolors` ( 
						`id_shop`, `title`, `display`, `selector`, `colors`, `type`, `modid`, `modinfo`, `modtitle`, `modorder`, `active`
				) VALUES ( 
					\''.(int)$id_shop.'\',
					\''.pSQL($title).'\',
					\''.pSQL($display).'\',
					\''.pSQL($selector).'\',
					\''.pSQL($colors).'\',
					\''.pSQL($type).'\',
					\''.pSQL($modid).'\',
					\''.pSQL($modinfo).'\',
					\''.pSQL($modtitle).'\',
					\''.pSQL($modorder).'\',
					\''.pSQL($hidden).'\'
					)
				');

		return $result;
	}

	private function updateActive($type, $active)
	{
		$sql = 'UPDATE `'._DB_PREFIX_.'uhucolors` SET active = \''.pSQL($active).'\' WHERE type = \''.pSQL($type).'\'';
		Db::getInstance()->execute($sql);
	}

	private function getColorModid($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getColorTitle($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT title FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getColorType($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT display FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getColorValue($m_id)
	{
		$mid = $m_id + 1;

		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
		{
			$colors = Db::getInstance()->getValue('SELECT colors FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
			$color = explode('|', $colors);

			if (Tools::getValue('theme') && Tools::getValue('live_configurator') == 1)
				$themecolor = Tools::getValue('theme');
			else
				$themecolor = Configuration::get('PS_UHU_THEME');
			switch ($themecolor)
			{
			case 'theme1':
				$c_value = $color[0];
				break;
			case 'theme2':
				$c_value = $color[1];
				break;
			case 'theme3':
				$c_value = $color[2];
				break;
			}
		}
		else
			$c_value = Db::getInstance()->getValue('SELECT mycolor FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);

		return $c_value;
	}

	private function getColorSelector($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT selector FROM `'._DB_PREFIX_.'uhucolors` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function displayForm()
	{
		$this->_html .= '<form class="defaultForm form-horizontal" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'
								"method="post" enctype="multipart/form-data">';
		$this->_html .= '<div class="panel" id="fieldset_0">';
		$this->_html .= '<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Front Color Setting').'</div>';

		$this->_html .= '<div class="form-wrapper">';
		$this->displayFormTabConfigColor();
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitConfigColor">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</form>';
	}

	private function displayFormTabConfigColor()
	{
		$result = Tools::file_get_contents(_PS_MODULE_DIR_.'uhuthemesetting/config/colortype.txt');
		$results = explode(PHP_EOL, $result);

		$unique = array();
		for ($i = 0; $i < $results[0] * 7; $i = $i + 7)
			$unique[] = $results[$i + 5];
		$uniques = array_unique($unique);

		$first = 0;
		foreach ($uniques as $catname)
		{
			if ($first++ == 0)
			{
				$this->_html .= '<div class="alert alert-info">';
				$this->_html .= $this->l('Display a color control block on Front Color Editor when its button is set to YES.');
				$this->_html .= '</div>';
			}
			for ($i = 0; $i < $results[0] * 7; $i = $i + 7)
				if ($results[$i + 5] == $catname && $results[$i + 6] <> 'hidden')
					$this->displayFormTabConfigColorSingle($results[$i + 1]);
		}
	}

	private function displayFormTabConfigColorSingle($title)
	{
		$result = Db::getInstance()->getRow('SELECT modid, active, modtitle FROM `'._DB_PREFIX_.'uhucolors` WHERE type = \''.pSQL($title).'\'');

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$result['modtitle'].'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="'.$result['modid'].'" id="'.$result['modid'].'_on" value="1" '.
											(($result['active'] <> 'hidden') ? 'checked="checked"' : '').'>
										<label for="'.$result['modid'].'_on">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="'.$result['modid'].'" id="'.$result['modid'].'_off" value="0" '.
											(($result['active'] == 'hidden') ? 'checked="checked"' : '').'>
										<label for="'.$result['modid'].'_off">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>
						</div>';
		$this->_html .= '</div>';
	}
}