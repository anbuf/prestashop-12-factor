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

class Uhuslider extends Module
{
	public function __construct()
	{
		$this->name = 'uhuslider';
		$this->tab = 'others';
		$this->version = '1.2.4';
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Image Slider';
		$this->description = $this->l('Adds an image slider to your homepage.');

		$this->init();
	}

	protected function init()
	{
		$this->mod_name = 'slider';
		$this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));

		$themes_styles = explode('|', Configuration::get('uhu_Theme_Styles'));
		if (Tools::getValue('theme_style') && Tools::getValue('live_configurator') == 1)
			$themestyle = Tools::getValue('theme_style');
		else
			$themestyle = Configuration::get('PS_UHU_STYLE');
		$this->styleid = array_search($themestyle, $themes_styles);

		$enables = explode('|', $this->mod_value[0]);
		if (isset($enables[$this->styleid]) && $enables[$this->styleid] > 0 && $this->mod_value[4] <> 'no')
			$this->enable_slider = 'yes';
		else
			$this->enable_slider = 'no';

		$hooks = explode('|', $this->mod_value[1]);
		if (isset($hooks[$this->styleid]))
			$this->hook = $hooks[$this->styleid];
		else
			$this->hook = $hooks[0];
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('displayTopColumn') && $this->registerHook('home'));
	}

	public function uninstall()
	{
		return (parent::uninstall());
	}

	public function hookHome($params)
	{
		$html = '';

		if ($this->enable_slider == 'yes' && $this->hook == 'home')
			$html .= $this->hookSlider($params);

		return $html;
	}

	public function hookdisplayTopColumn($params)
	{
		$html = '';

		if ($this->enable_slider == 'yes' && $this->hook <> 'home')
			$html .= $this->hookSlider($params);

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

	public function hookSlider($params)
	{
		$total_grids = explode('|', $this->mod_value[0]);
		if (isset($total_grids[$this->styleid]))
			$totalgrid = $total_grids[$this->styleid];
		else
			$totalgrid = $total_grids[0];

		$slider_number = $this->mod_value[2];
		if ($slider_number == '')
			$slider_number = 5;

		$slider_speed = 3;
		$delays = explode('|', $this->mod_value[$slider_speed]);
		if (isset($delays[0]))
			$slider_delay = $delays[0];
		else
			$slider_delay = 5000;
		if (isset($delays[1]))
			$slider_duration = $delays[1];
		else
			$slider_duration = 800;

		$slider_timeout = 13;
		$easing = 14;

		$img_height = 15;
		$highs = explode('|', $this->mod_value[$img_height]);
		if (isset($highs[0]))
		{
			$styleheight = explode('^', $highs[0]);
			if (isset($styleheight[$this->styleid]))
				$this->smarty->assign('pc_height', $styleheight[$this->styleid]);
			else
				$this->smarty->assign('pc_height', $styleheight[0]);
		}
		else
			$this->smarty->assign('pc_height', '800');
		if (isset($highs[1]))
			$this->smarty->assign('tablet_height', $highs[1]);
		else
			$this->smarty->assign('tablet_height', '460');
		if (isset($highs[2]))
			$this->smarty->assign('mobile_height', $highs[2]);
		else
			$this->smarty->assign('mobile_height', '360');

		$max_width = 16;
		$this->smarty->assign(array(
			'totalgrid' => $totalgrid,
			'slider_number' => $slider_number,
			'slider_delay' => $slider_delay,
			'slider_duration' => $slider_duration,
			'max_width' => $this->mod_value[$max_width],
			'easing' => trim($this->mod_value[$easing])
		));

		$controls = explode('|', $this->mod_value[6]);
		if (isset($controls[0]))
			$awesome_prev = $controls[0];
		else
			$awesome_prev = 'icon-angle-left';
		if (isset($controls[1]))
			$awesome_next = $controls[1];
		else
			$awesome_next = 'icon-angle-right';
		$this->smarty->assign(array(
			'awesome_prev' => $awesome_prev,
			'awesome_next' => $awesome_next
		));

		$delays = explode('|', $this->mod_value[$slider_timeout]);
		if (isset($delays[0]))
			$delay = $delays[0];
		else
			$delay = 1000;
		if (isset($delays[1]))
			$disappear = $delays[1];
		else
			$disappear = 20;
		$slider_timeout = $delay * ($disappear + 6);
		$this->smarty->assign('slider_timeout', $slider_timeout);

		$h2s = explode('|', $this->mod_value[7]);
		if (isset($h2s[0]) && $h2s[0] <> '')
			$animate_in = $h2s[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($h2s[1]))
			$animate_out = $h2s[1];
		else
			$animate_out = 'zoomOut';
		if (isset($h2s[2]))
			$time_in = $h2s[2];
		else
			$time_in = 500;
		if (isset($h2s[3]))
			$time_out = $h2s[3];
		else
			$time_out = 1000 + $delay * ($disappear + 0);
		$this->smarty->assign(array(
			'h2_animate_in' => $animate_in,
			'h2_animate_out' => $animate_out,
			'h2_time_in' => $time_in,
			'h2_time_out' => $time_out
		));

		$h3s = explode('|', $this->mod_value[8]);
		if (isset($h3s[0]) && $h3s[0] <> '')
			$animate_in = $h3s[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($h3s[1]))
			$animate_out = $h3s[1];
		else
			$animate_out = 'zoomOut';
		if (isset($h3s[2]))
			$time_in = $h3s[2];
		else
			$time_in = 500 + $delay;
		if (isset($h3s[3]))
			$time_out = $h3s[3];
		else
			$time_out = 500 + $delay * ($disappear + 1);
		$this->smarty->assign(array(
			'h3_animate_in' => $animate_in,
			'h3_animate_out' => $animate_out,
			'h3_time_in' => $time_in,
			'h3_time_out' => $time_out
		));

		$h4s = explode('|', $this->mod_value[11]);
		if (isset($h4s[0]) && $h4s[0] <> '')
			$animate_in = $h4s[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($h4s[1]))
			$animate_out = $h4s[1];
		else
			$animate_out = 'zoomOut';
		if (isset($h4s[2]))
			$time_in = $h4s[2];
		else
			$time_in = 500 + $delay * 2;
		if (isset($h4s[3]))
			$time_out = $h4s[3];
		else
			$time_out = 500 + $delay * ($disappear + 2);
		$this->smarty->assign(array(
			'h4_animate_in' => $animate_in,
			'h4_animate_out' => $animate_out,
			'h4_time_in' => $time_in,
			'h4_time_out' => $time_out
		));

		$h5s = explode('|', $this->mod_value[12]);
		if (isset($h5s[0]) && $h5s[0] <> '')
			$animate_in = $h5s[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($h5s[1]))
			$animate_out = $h5s[1];
		else
			$animate_out = 'zoomOut';
		if (isset($h5s[2]))
			$time_in = $h5s[2];
		else
			$time_in = 500 + $delay * 3;
		if (isset($h5s[3]))
			$time_out = $h5s[3];
		else
			$time_out = 500 + $delay * ($disappear + 3);
		$this->smarty->assign(array(
			'h5_animate_in' => $animate_in,
			'h5_animate_out' => $animate_out,
			'h5_time_in' => $time_in,
			'h5_time_out' => $time_out
		));

		$links = explode('|', $this->mod_value[17]);
		if (isset($links[0]) && $links[0] <> '')
			$animate_in = $links[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($links[1]))
			$animate_out = $links[1];
		else
			$animate_out = 'zoomOut';
		if (isset($links[2]))
			$time_in = $links[2];
		else
			$time_in = 500 + $delay * 4;
		if (isset($links[3]))
			$time_out = $links[3];
		else
			$time_out = 500 + $delay * ($disappear + 4);
		$this->smarty->assign(array(
			'link_animate_in' => $animate_in,
			'link_animate_out' => $animate_out,
			'link_time_in' => $time_in,
			'link_time_out' => $time_out
		));

		$logos = explode('|', $this->mod_value[18]);
		if (isset($logos[0]))
			$animate_in = $logos[0];
		else
			$animate_in = 'bounceInDown';
		if (isset($logos[1]))
			$animate_out = $logos[1];
		else
			$animate_out = 'zoomOut';
		if (isset($logos[2]))
			$time_in = $logos[2];
		else
			$time_in = 500 + $delay * 5;
		if (isset($logos[3]))
			$time_out = $logos[3];
		else
			$time_out = 500 + $delay * ($disappear + 5);
		$this->smarty->assign(array(
			'logo_animate_in' => $animate_in,
			'logo_animate_out' => $animate_out,
			'logo_time_in' => $time_in,
			'logo_time_out' => $time_out
		));

		$transitions = explode('|', $this->mod_value[9]);
		if (!isset($transitions[1]))
			$transitions[1] = 'sliceV';
		$this->smarty->assign(array(
			'slider_transition' =>  $transitions[0],
			'slider_easing' =>  $transitions[1]
		));

		$responsive = 19;
		if ($this->mod_value[$responsive] == '')
			$this->mod_value[$responsive] = '4|4|2';
		$responsive = explode('|', $this->mod_value[$responsive]);
		$this->smarty->assign('responsive1', $responsive[0]);
		$this->smarty->assign('responsive2', $responsive[1]);
		$this->smarty->assign('responsive3', $responsive[2]);
		//$pos = explode('|', $this->mod_value[10]);
		//$this->smarty->assign(array(
		//	'slidecontent_top' => $pos[0],
		//	'slidecontent_left' => $pos[1]
		//));

		$imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_.'uhuthemesetting/views/img/'.$this->mod_name.'/';
		$this->smarty->assign('imgurl', $imgurl);

		$text_effect = 5;
		$texteffects = explode('|', $this->mod_value[$text_effect]);

		for ($i = 0; $i < $slider_number; $i++)
		{
			$this->smarty->assign('slider'.$i.'_texteffect', 'false');
			if (isset($texteffects[$i]) && $texteffects[$i])
				$this->smarty->assign('slider'.$i.'_texteffect', $texteffects[$i]);

			$slider_show = 20 + 10 * $i;
			$this->smarty->assign('slider'.$i.'_show', $this->mod_value[$slider_show]);

			$posall = explode('^', $this->mod_value[10]);
			if (isset($posall[$i]))
				$posone = $posall[$i];
			else
				$posone = $posall[0];
			$pos = explode('|', $posone);
			$this->smarty->assign(array(
				'slidecontent_top_'.$i => $pos[0],
				'slidecontent_left_'.$i => $pos[1]
			));

			$slider_img = 21 + 10 * $i;
			$slider_images = explode('|', $this->mod_value[$slider_img]);

			$slider_image_l = explode('^', $slider_images[0]);
			if (isset($slider_image_l[$this->styleid]))
				$slider_image = $slider_image_l[$this->styleid];
			else
				$slider_image = $slider_image_l[0];

			$slider_image_ms = explode('^', $slider_images[1]);
			if (isset($slider_image_ms[$this->styleid]))
				$slider_image_m = $slider_image_ms[$this->styleid];
			else
			{
				if (isset($slider_image_ms[0]))
					$slider_image_m = $slider_image_ms[0];
				else
					$slider_image_m = $slider_image_l[0];
			}

			if (isset($slider_images[2]))
				$slider_image_s = $slider_images[2];
			else
				$slider_image_s = $slider_images[0];

			if (Configuration::get('PS_UHU_DEVELOPER_MODE'))
			{
				$this->smarty->assign('slider'.$i.'_image', $imgurl.basename($slider_image));
				$this->smarty->assign('slider'.$i.'_image_m', $imgurl.basename($slider_image_m));
				$this->smarty->assign('slider'.$i.'_image_s', $imgurl.basename($slider_image_s));
			}
			else
			{
				if (strstr($slider_image, 'http://') <> '')
				{
					$this->smarty->assign('slider'.$i.'_image', $slider_image);
					$this->smarty->assign('slider'.$i.'_image_m', $slider_image_m);
					$this->smarty->assign('slider'.$i.'_image_s', $slider_image_s);
				}
				else
				{
					$this->smarty->assign('slider'.$i.'_image', $imgurl.$slider_image);
					$this->smarty->assign('slider'.$i.'_image_m', $imgurl.$slider_image_m);
					$this->smarty->assign('slider'.$i.'_image_s', $imgurl.$slider_image_s);
				}
			}

			$slider_h2 = 22 + 10 * $i;
			$sh2 = $this->language($params, $slider_h2);
			$this->smarty->assign('slider'.$i.'_h2', $sh2);

			$slider_h3 = 23 + 10 * $i;
			$sh3 = $this->language($params, $slider_h3);
			$this->smarty->assign('slider'.$i.'_h3', $sh3);

			$slider_h4 = 24 + 10 * $i;
			$sh4 = $this->language($params, $slider_h4);
			$this->smarty->assign('slider'.$i.'_h4', $sh4);

			$slider_h5 = 25 + 10 * $i;
			$sh5 = $this->language($params, $slider_h5);
			$this->smarty->assign('slider'.$i.'_h5', $sh5);

			$slider_link = 26 + 10 * $i;
			$slink = $this->language($params, $slider_link);
			$this->smarty->assign('slider'.$i.'_link', $slink);

			$slider_logo = 27 + 10 * $i;
			//$slogo = $this->language($params, $slider_logo);
			$this->smarty->assign('slider'.$i.'_logo', $this->mod_value[$slider_logo]);

			$slider_url = 28 + 10 * $i;
			$this->smarty->assign('slider'.$i.'_url', $this->mod_value[$slider_url]);
		}

		$slider = $this->mod_value[4];
		switch ($slider)
		{
		case 'Bxslider':
			$this->smarty->assign('slider', 'bx');
			$tpl = 'uhubxslider.tpl';
			break;
		case 'RefineSlide':
			$this->smarty->assign('slider', 're');
			$tpl = 'uhurefineslide.tpl';
			break;
		case 'Owlslider':
			$this->smarty->assign('slider', 'ow');
			$tpl = 'uhuowlslider.tpl';
			break;
		case 'Fluxslider':
			$this->smarty->assign('slider', 'cy');
			$tpl = 'uhufluxslider.tpl';
			break;
		case 'no':
		default:
			$tpl = '';
			break;
		}

		return $this->display(__FILE__, $tpl);
	}
}