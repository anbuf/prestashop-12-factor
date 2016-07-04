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

if (!defined('_CAN_LOAD_FILES_'))
	exit;

class Uhucopyright extends Module
{
	private $pattern = '/^([A-Z_]*)[0-9]+/';

	public function __construct()
	{
		$this->name = 'uhucopyright';
		$this->tab = 'others';
		$this->author = 'uhuPage';
		$this->version = '1.0.3';

		parent::__construct();

		$this->displayName = 'uhu Copyright block';
		$this->description = $this->l('Add a block to add coprright information.');
		$this->init();
	}

	protected function init()
	{
		$this->mod_name = 'copyright';
		$this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));
	}

	public function install()
	{
		$hook = $this->mod_value[5];

		if ($hook == 'FooterBanner')
			return (parent::install() && $this->registerHook('displayFooterBanner'));
		else
			return (parent::install() && $this->registerHook('footer'));
	}

	public function uninstall()
	{
		return (parent::uninstall());
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

	public function hookFooter($params)
	{
		$totalgrid = 0;
		$company = 1;
		$copyright = 2;
		$logo = 3;
		$footall = 4;

		$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
		$this->smarty->assign(array(
			'totalgrid' => $this->mod_value[$totalgrid],
			'company' => $this->language($params, $company),
			'copyright' => $this->language($params, $copyright),
			'imgurl' => $imgurl.'uhuthemesetting/views/img/'.$this->mod_name.'/',
			'logo' => $this->mod_value[$logo],
			'footall' => $this->mod_value[$footall]
		));

		$id_lang = (int)$this->context->language->id;
		$cms_titles = array();
		$cms_items = explode(',', $this->mod_value[6]);
		foreach ($cms_items as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $value);
			$id = (int)Tools::substr($item, Tools::strlen($value[1]), Tools::strlen($item));
			$cms = CMS::getLinks((int)$id_lang, array($id));
			if (count($cms))
			{
				$cms_titles[$item]['link'] = $cms[0]['link'];
				$cms_titles[$item]['meta_title'] = $cms[0]['meta_title'];
			}
		}

		$display_footer = $this->mod_value[7] == 'yes' ? 1:0;
		$display_special = $this->mod_value[8] == 'yes' ? 1:0;
		$display_new = $this->mod_value[9] == 'yes' ? 1:0;
		$display_best = $this->mod_value[10] == 'yes' ? 1:0;
		$display_contact = $this->mod_value[11] == 'yes' ? 1:0;
		$display_sitemap = $this->mod_value[12] == 'yes' ? 1:0;

		$this->smarty->assign(
			array(
				'contact_url' => 'contact',
				'cmslinks' => $cms_titles,
				'display_stores_footer' => $display_footer,
				'display_special_footer' => $display_special,
				'display_new_footer' => $display_new,
				'display_best_footer' => $display_best,
				'display_contact_footer' => $display_contact,
				'display_sitemap_footer' => $display_sitemap
			)
		);

		return $this->display(__FILE__, $this->name.'.tpl');
	}

	public function hookdisplayFooterBanner($params)
	{
		return $this->hookFooter($params);
	}
}
?>
