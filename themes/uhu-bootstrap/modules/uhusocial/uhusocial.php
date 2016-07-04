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

class Uhusocial extends Module
{

	public function __construct()
	{
		$this->name = 'uhusocial';
		$this->tab = 'others';
		$this->version = '1.0.3';
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Social networking block';
		$this->description = $this->l('Allows you to add extra information about social networks.');

		$this->init();
	}

	protected function init()
	{
		$this->mod_name = 'social';
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
		if (!parent::install()
			|| !$this->registerHook('displayBanner')
			|| !$this->registerHook('displayTopContent')
			|| !$this->registerHook('displayFooterNav')
			|| !$this->registerHook('displayFooterBanner')
			|| !$this->registerHook('footer'))
			return false;
		return true;
	}

	public function hookDisplayBanner($params)
	{
		$pos = 0;
		return $this->displayCode($params, $pos);
	}

	public function hookdisplayTopContent($params)
	{
		$pos = 10;
		return $this->displayCode($params, $pos);
	}

	public function hookdisplayFooterNav($params)
	{
		$pos = 20;
		return $this->displayCode($params, $pos);
	}

	public function hookFooter($params)
	{
		$pos = 30;
		return $this->displayCode($params, $pos);
	}

	public function hookdisplayFooterBanner($params)
	{
		$pos = 40;
		return $this->displayCode($params, $pos);
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

	public function displayCode($params, $pos)
	{
		$html = '';
		$display = $this->mod_value[$pos];
		if ($display == 'yes')
		{
			$enable_social = $pos + 1;
			$enables = explode('|', $this->mod_value[$enable_social]);
			if (isset($enables[$this->styleid]))
				$enable_social = $enables[$this->styleid];
			else
				$enable_social = $enables[0];
			if ($enable_social == 'yes')
			{
				$totalgrid = 2 + $pos;
				$total_grids = explode('|', $this->mod_value[$totalgrid]);
				if (isset($total_grids[$this->styleid]))
					$totalgrid = $total_grids[$this->styleid];
				else
					$totalgrid = $total_grids[0];
				$this->smarty->assign('totalgrid', $totalgrid);

				$social_number = 3 + $pos;
				$social_number = $this->mod_value[$social_number];
				$this->smarty->assign('social_number', $social_number);

				$social_type = 4 + $pos;
				$this->smarty->assign('social_type', $this->mod_value[$social_type]);

				$icons = 6 + $pos;
				$social_icons = explode('|', $this->mod_value[$icons]);
				$links = 7 + $pos;
				$social_links = explode('|', $this->mod_value[$links]);
				for ($i = 0; $i < $social_number; $i++)
				{
					if (isset($social_icons[$i]))
						$sicon = $social_icons[$i];
					else
						$sicon = '';
					$this->smarty->assign('social_icons_'.$i, $sicon);

					if (isset($social_links[$i]))
						$slink = $social_links[$i];
					else
						$slink = '';
					$this->smarty->assign('social_links_'.$i, $slink);
				}

				$social_title = 9 + $pos;
				$this->smarty->assign('social_title', $this->language($params, $social_title));
			}

			$html .= $this->display(__FILE__, $this->name.'.tpl');
		}

		return $html;
	}
}