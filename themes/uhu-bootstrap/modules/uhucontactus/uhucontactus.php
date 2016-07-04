<?php
/**
* 2007-2015 uhuPage
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. 
*
*  @author    uhuPage <support@uhupage.com>
*  @copyright 2007-2015 uhuPage
*  @license   GNU General Public License version 2
*/

class Uhucontactus extends Module
{
    public function __construct()
    {
        $this->name = 'uhucontactus';
        $this->tab = 'others';
        $this->author = 'uhuPage';
        $this->version = '1.1.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = 'uhu Contact info block';
        $this->description = $this->l('Display your contact information in a customizable block.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->init();
    }

    protected function init()
    {
        $this->mod_name = 'contactus';
        $this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));

        $themes_styles = explode('|', Configuration::get('uhu_Theme_Styles'));
        if (Configuration::get('PS_UHU_LIVE_DEMO') == 1 || Configuration::get('PS_UHU_DEVELOPER_MODE') == 1) {

            if (Tools::getValue('theme_style') && Tools::getValue('live_configurator') == 1) {
                $themestyle = Tools::getValue('theme_style');
            } else {
                $themestyle = Configuration::get('PS_UHU_STYLE');
            }
            $this->styleid = array_search($themestyle, $themes_styles);
        } else {
            $this->styleid = array_search(Configuration::get('PS_UHU_STYLE'), $themes_styles);
        }
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayNav') ||
            !$this->registerHook('top') ||
            !$this->registerHook('displayTopContent') ||
            !$this->registerHook('displayTopColumn') ||
            !$this->registerHook('home') ||
            !$this->registerHook('displayBottomBanner') ||
            !$this->registerHook('displayFooterNav') ||
            !$this->registerHook('displayFooterBanner') ||
            !$this->registerHook('footer')) {
            return false;
        }

        return true;
    }

    public function displayHook($params, $pos, $did, $subtitle)
    {
        $html = '';

        $enable_contactus = $pos + 10;
        $enables = explode('|', $this->mod_value[$enable_contactus]);
        if (isset($enables[$this->styleid])) {
            $enable_contactus = $enables[$this->styleid];
        } else {
            $enable_contactus = $enables[0];
        }

        if ($enable_contactus == 'yes') {

            $totalgrid = 0 + $pos;
            $total_grids = explode(':', $this->mod_value[$totalgrid]);

            if (isset($total_grids[$this->styleid])) {
                $totalgrid = $total_grids[$this->styleid];
            } else {
                $totalgrid = $total_grids[0];
            }

            $module_grids = explode('|', $totalgrid);
            if (isset($module_grids[2])) {
                $mgrid3 = $module_grids[2];
            } else {
                $mgrid3 = $module_grids[0];
            }

            if (isset($module_grids[1])) {
                $mgrid2 = $module_grids[1];
            } else {
                $mgrid2 = $module_grids[0];
            }

            $mgrid1 = $module_grids[0];
            $this->smarty->assign('mgrid1', $mgrid1);
            $this->smarty->assign('mgrid2', $mgrid2);
            $this->smarty->assign('mgrid3', $mgrid3);

            $owl = 7 + $pos;
            if (strstr($this->mod_value[$owl], 'yes') <> '') {

                $owls = explode(':', $this->mod_value[$owl]); //yes|responsive

                $this->smarty->assign('owlslider', $owls[0]);
                $responsive = explode('|', $owls[1]);
                $did = $owls[2];
                $this->smarty->assign('responsive1', $responsive[0]);
                $this->smarty->assign('responsive2', $responsive[1]);
                $this->smarty->assign('responsive3', $responsive[2]);

                $base_url = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
                $imgurl = $base_url.'uhuthemesetting/views/img/'.$this->mod_name.'/';

                $logo = 6 + $pos;
                $link = 7 + $pos;
                $title = 8 + $pos;
                $text = 9 + $pos;

                $all_title = $this->language($params, $title);
                $titles = explode('^', $all_title);

                $all_subtitle = $this->language($params, $subtitle);
                $subtitles = explode('^', $all_subtitle);

                $all_text = $this->language($params, $text);
                $texts = explode('^', $all_text);

                if ($this->mod_value[$logo] <> '') {
                    $logos = explode('^', $this->mod_value[$logo]);
                    $slider_number = count($logos);
                } else {
                    $slider_number = count($titles);
                }
                $links = explode('^', $this->mod_value[$link]);

                $this->smarty->assign('slider_number', $slider_number);
                for ($i = 0; $i < $slider_number; $i++) {

                    $rimg = $logos[$i];
                    if ($rimg <> '') {
                        if (strstr($rimg, 'http://') <> '') {
                            $this->smarty->assign('logo_'.$i, $rimg);
                        } else {
                            $this->smarty->assign('logo_'.$i, $imgurl.$rimg);
                        }
                    } else {
                        $this->smarty->assign('logo_'.$i, $rimg);
                    }

                    $this->smarty->assign(array(
                        'did' => $did,
                        'logolink_'.$i => $links[$i],
                        'title_'.$i => $titles[$i],
                        'subtitle_'.$i => $subtitles[$i],
                        'texts_'.$i =>  explode('/n', $texts[$i]),
                        ));
                }

            } else {

                $this->smarty->assign('owlslider', '');
                $owls = explode(':', $this->mod_value[$owl]); //no|responsive|did
                if (isset($owls[2]) && count($owls) > 2) {
                    $did = $owls[2];
                }

                $company = 1 + $pos;
                $address = 2 + $pos;
                $phone = 3 + $pos;
                $email = 4 + $pos;
                $logo = 6 + $pos;
                $link = 7 + $pos;
                $title = 8 + $pos;
                $text = 9 + $pos;

                $this->smarty->assign(array(
                    'did' => $did,
                    'company' => $this->language($params, $company),
                    'address' => $this->language($params, $address),
                    'title' => $this->language($params, $title),
                    'subtitle' => $this->language($params, $subtitle),
                    'texts' =>  explode('/n', $this->language($params, $text)),
                    'logolink' => $this->mod_value[$link],
                    'phone' => $this->mod_value[$phone],
                    'email' => $this->mod_value[$email]
                    ));

                $base_url = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
                $imgurl = $base_url.'uhuthemesetting/views/img/'.$this->mod_name.'/';
                $rimg = $this->mod_value[$logo];
                if ($rimg <> '') {

                    if (strstr($rimg, 'http://') <> '') {
                        $this->smarty->assign('logo', $rimg);
                    } else {
                        $this->smarty->assign('logo', $imgurl.$rimg);
                    }

                } else {
                    $this->smarty->assign('logo', $rimg);
                }
            }
            $html .= $this->display(__FILE__, $this->name.'.tpl');
        }

        return $html;
    }

    public function language($params, $id)
    {
        $lang = array();
        if ($this->mod_value[$id] == '') {
            return;
        }
        $lang_iso = Language::getIsoById($params['cookie']->id_lang);
        $values = explode('|', $this->mod_value[$id]);
        foreach ($values as $value) {
            $langs = explode('¤', $value);
            if (isset($langs[1]) && $langs[1]) {
                $lang[$langs[1]] = $langs[0];
            }
        }
        if ($lang[$lang_iso] <> '') {
            return $lang[$lang_iso];
        } else {
            return $lang['en'];
        }
    }

    public function hookDisplayNav($params)
    {
        $html = '';
        $pos = 11;
        $subtitle = 56;
        $displays = explode('|', $this->mod_value[16]);
        if ($displays[0] == 'yes') {
            $html = $this->displayHook($params, $pos, 'top', $subtitle);
        }
        return $html;
    }

    public function hookdisplayTop($params)
    {
        $html = '';
        $pos = 11;
        $subtitle = 56;
        $displays = explode('|', $this->mod_value[16]);
        if (isset($displays[1]) && $displays[1] == 'yes') {
            $html = $this->displayHook($params, $pos, 'top', $subtitle);
        }
        return $html;
    }

    public function hookdisplayTopContent($params)
    {
        $html = '';
        $pos = 11;
        $subtitle = 56;
        $displays = explode('|', $this->mod_value[16]);
        if (isset($displays[2]) && $displays[2] == 'yes') {
            $html = $this->displayHook($params, $pos, 'top', $subtitle);
        }
        return $html;
    }

    public function hookdisplayTopColumn($params)
    {
        $html = '';
        $pos = 44;
        $subtitle = 59;
        $displays = explode('|', $this->mod_value[49]);
        if ($displays[0] == 'yes') {
            $html = $this->displayHook($params, $pos, 'slider', $subtitle);
        }
        return $html;
    }

    public function hookhome($params)
    {
        $html = '';
        $pos = 33;
        $subtitle = 58;
        $displays = explode('|', $this->mod_value[38]);
        if ($displays[0] == 'yes') {
            $html = $this->displayHook($params, $pos, 'home', $subtitle);
        }
        return $html;
    }

    public function hookdisplayBottomBanner($params)
    {
        $html = '';
        $pos = 22;
        $subtitle = 57;
        $displays = explode('|', $this->mod_value[27]);
        if (isset($displays[2]) && $displays[2] == 'yes') {
            $html = $this->displayHook($params, $pos, 'bottom', $subtitle);
        }
        return $html;
    }

    public function hookdisplayFooterNav($params)
    {
        $html = '';
        $pos = 0;
        $subtitle = 55;
        $displays = explode('|', $this->mod_value[5]);
        if ($displays[0] == 'yes') {
            $html = $this->displayHook($params, $pos, 'foot', $subtitle);
        }
        return $html;
    }

    public function hookFooter($params)
    {
        $html = '';
        $pos = 0;
        $subtitle = 55;
        $displays = explode('|', $this->mod_value[5]);
        if (isset($displays[1]) && $displays[1] == 'yes') {
            $html = $this->displayHook($params, $pos, 'foot', $subtitle);
        }
        return $html;
    }

    public function hookdisplayFooterBanner($params)
    {
        $html = '';
        $pos = 0;
        $subtitle = 55;
        $displays = explode('|', $this->mod_value[5]);
        if (isset($displays[2]) && $displays[2] == 'yes') {
            $html = $this->displayHook($params, $pos, 'foot', $subtitle);
        }
        return $html;
    }
}
