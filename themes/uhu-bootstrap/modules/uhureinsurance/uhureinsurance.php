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

class Uhureinsurance extends Module
{
    protected $user_groups;

    public function __construct()
    {
        $this->name = 'uhureinsurance';
        $this->tab = 'others';
        $this->author = 'uhuPage';
        $this->version = '1.1.3';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = 'uhu Multi-function block';
        $this->description = $this->l('Adds an block to show reassure, categories or news.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->init();
    }

    protected function init()
    {
        $this->mod_name = 'reinsurance';
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
            !$this->registerHook('displayTopContent') ||
            !$this->registerHook('displayTopColumn') ||
            !$this->registerHook('home') ||
            !$this->registerHook('displayTopBanner') ||
            !$this->registerHook('displayBottomBanner') ||
            !$this->registerHook('displayFooterNav') ||
            !$this->registerHook('displayFooterBanner') ||
            !$this->registerHook('footer')) {
            return false;
        }

        return true;
    }

    public function hookdisplayTopContent($params)
    {
        $pos = 0;
        return $this->displayCode($params, $pos, 'top');
    }

    public function hookdisplayTopColumn($params)
    {
        $pos = 20;
        return $this->displayCode($params, $pos, 'column');
    }

    public function hookdisplayHome($params)
    {
        $pos = 40;
        return $this->displayCode($params, $pos, 'home');
    }

    public function hookdisplayTopBanner($params)
    {
        $pos = 60;
        return $this->displayCode($params, $pos, 'topbanner');
    }

    public function hookdisplayBottomBanner($params)
    {
        $pos = 60;
        return $this->displayCode($params, $pos, 'bottombanner');
    }

    public function hookdisplayFooterNav($params)
    {
        $pos = 60;
        return $this->displayCode($params, $pos, 'nav');
    }

    public function hookdisplayFooterBanner($params)
    {
        $pos = 80;
        return $this->displayCode($params, $pos, 'footerbanner');
    }

    public function hookFooter($params)
    {
        $pos = 80;
        return $this->displayCode($params, $pos, 'footer');
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

    public function displayCode($params, $pos, $cid)
    {
        $this->user_groups = ($this->context->customer->isLogged() ?
            $this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));
        $html = '';
        $display = $this->mod_value[$pos];

        if ($pos == 60) {
            $displays = explode('|', $this->mod_value[$pos]);
            $display = $displays[0];
            if ($cid == 'topbanner') {
                $display = '';
                if (isset($displays[1])) {
                    $display = $displays[1];
                }
                $cid = 'nav';
            }
            if ($cid == 'bottombanner') {
                $display = '';
                if (isset($displays[2])) {
                    $display = $displays[2];
                }
                $cid = 'nav';
            }
        }

        if ($pos == 80) {
            $displays = explode('|', $this->mod_value[$pos]);
            $display = $displays[0];
            if ($cid == 'footerbanner') {
                $display = '';
                if (isset($displays[1])) {
                    $display = $displays[1];
                }
                $cid = 'footer';
            }
        }

        if ($display == 'yes') {
            $enable_rean = $pos + 1;
            $enables = explode('|', $this->mod_value[$enable_rean]);
            if (isset($enables[$this->styleid])) {
                $enable_rean = $enables[$this->styleid];
            } else {
                $enable_rean = $enables[0];
            }

            if ($enable_rean == 'yes') {
                $this->smarty->assign('pos', $cid);

                $totalgrid = 2 + $pos;
                $total_grids = explode('|', $this->mod_value[$totalgrid]);
                if (isset($total_grids[$this->styleid])) {
                    $totalgrid = $total_grids[$this->styleid];
                } else {
                    $totalgrid = $total_grids[0];
                }
                $module_grids = explode(':', $totalgrid);
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

                $itemgrid = 3 + $pos;
                $this->smarty->assign('itemgrid', $this->mod_value[$itemgrid]);

                $owl = 4 + $pos;
                if (strstr($this->mod_value[$owl], 'yes') <> '') {
                    $owls = explode(':', $this->mod_value[$owl]); //yes|responsive

                    $this->smarty->assign('owlslider', $owls[0]);
                    $responsive = explode('|', $owls[1]);
                    $this->smarty->assign('responsive1', $responsive[0]);
                    $this->smarty->assign('responsive2', $responsive[1]);
                    $this->smarty->assign('responsive3', $responsive[2]);
                    $this->smarty->assign('sliderid', $owls[2]);
                } else {
                    $this->smarty->assign('owlslider', 'no');
                }
                $base_url = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
                $imgurl = $base_url.'uhuthemesetting/views/img/'.$this->mod_name.'/';

                $totalitems = 5 + $pos;
                $totalitems = $this->mod_value[$totalitems];
                $this->smarty->assign('reassure_number', $totalitems);

                $reassureimgs = 6 + $pos;
                $reassure_image = explode('^', $this->mod_value[$reassureimgs]);

                $link = 7 + $pos;
                $all_link = $this->language($params, $link);
                $links = explode('^', $all_link);

                $title = 8 + $pos;
                $all_title = $this->language($params, $title);
                $titles = explode('^', $all_title);

                $subtitle = 9 + $pos;
                $all_subtitle = $this->language($params, $subtitle);
                $subtitles = explode('^', $all_subtitle);

                $text = 10 + $pos;
                $all_text = $this->language($params, $text);
                $texts = explode('^', $all_text);

                $ftitle = 11 + $pos;
                $all_ftitle = $this->language($params, $ftitle);
                $ftitles = explode('^', $all_ftitle);

                $fsubtitle = 12 + $pos;
                $all_fsubtitle = $this->language($params, $fsubtitle);
                $fsubtitles = explode('^', $all_fsubtitle);

                for ($i = 0; $i < $totalitems; $i++) {
                    $rimg = '';
                    if (isset($reassure_image[$i])) {
                        $rimg = $reassure_image[$i];
                    }

                    if (strstr($rimg, 'icon-') == '') {
                        $this->smarty->assign('icon_'.$i, 'false');
                        if (strstr($rimg, 'http://') <> '') {
                            $this->smarty->assign('reassure_image_'.$i, $rimg);
                        } else {
                            if ($rimg <> '') {
                                $this->smarty->assign('reassure_image_'.$i, $imgurl.$rimg);
                            } else {
                                $this->smarty->assign('reassure_image_'.$i, '');
                            }
                        }
                    } else {
                        $this->smarty->assign('icon_'.$i, 'true');
                        $this->smarty->assign('reassure_image_'.$i, $rimg);
                    }

                    $rlink = '';
                    if (isset($links[$i])) {
                        $rlink = $links[$i];
                    }
                    $this->smarty->assign('reassure_link_'.$i, $rlink);

                    $rtitle = '';
                    if (isset($titles[$i])) {
                        $rtitle = $titles[$i];
                    }
                    $rsubtitle = '';
                    if (isset($subtitles[$i])) {
                        $rsubtitle = $subtitles[$i];
                    }
                    $rftitle = '';
                    if (isset($ftitles[$i])) {
                        $rftitle = $ftitles[$i];
                    }
                    $rfsubtitle = '';
                    if (isset($fsubtitles[$i])) {
                        $rsubtitle = $fsubtitles[$i];
                    }
                    $rtext = '';
                    if (isset($texts[$i])) {
                        $rtext = $texts[$i];
                    }
                    $this->smarty->assign(array(
                        'reassure_title_'.$i => $rtitle,
                        'reassure_subtitle_'.$i => $rsubtitle,
                        'reassure_text_'.$i =>  explode('/n', $rtext),
                        'reassure_ftitle_'.$i => $rftitle,
                        'reassure_fsubtitle_'.$i => $rfsubtitle,
                        ));
                }

                $block_title = 16 + $pos;
                $this->smarty->assign('block_title', $this->language($params, $block_title));
                $block_info = 17 + $pos;
                $this->smarty->assign('block_info', $this->language($params, $block_info));

                $divid = 19 + $pos;
                $divid = $this->mod_value[$divid];
                $this->smarty->assign('divid', $divid);
                
                $html .= $this->display(__FILE__, $this->name.'.tpl');
            }
        }

        return $html;
    }
}
