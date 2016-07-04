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

class Uhulogo extends Module
{
    public function __construct()
    {
        $this->name = 'uhulogo';
        $this->tab = 'others';
        $this->author = 'uhuPage';
        $this->version = '1.0.5';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('uhu Logo block');
        $this->description = $this->l('Allows you to add your logo to the shop.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        $this->init();
    }

    protected function init()
    {
        $this->mod_name = 'logo';
        $this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));

        $mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
        $themes_headers = explode('|', $mvalue[19]);
        $this->styleid = array_search(Configuration::get('PS_UHU_HEADER'), $themes_headers);
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayNav') ||
            !$this->registerHook('displayTop') ||
            !$this->registerHook('displayTopContent') ||
            !$this->registerHook('displayFooterNav') ||
            !$this->registerHook('displayFooterBanner') ||
            !$this->registerHook('footer')) {
            return false;
        }

        return true;
    }

    public function hookDisplayNav()
    {
        $html = '';
        $pos = 0;
        $enable_logo = $pos + 1;
        $enables = explode('|', $this->mod_value[$enable_logo]);
        if (isset($enables[$this->styleid])) {
            $enable_logo = $enables[$this->styleid];
        } else {
            $enable_logo = $enables[0];
        }
        if ($enable_logo == 'displayNav') {
            $html = $this->displayCode($pos);
        }
        return $html;
    }

    public function hookDisplayTop()
    {
        $html = '';
        $pos = 0;
        $enable_logo = $pos + 1;
        $enables = explode('|', $this->mod_value[$enable_logo]);
        if (isset($enables[$this->styleid])) {
            $enable_logo = $enables[$this->styleid];
        } else {
            $enable_logo = $enables[0];
        }
        if ($enable_logo == 'displayTop') {
            $html = $this->displayCode($pos);
        }
        return $html;
    }

    public function hookdisplayTopContent()
    {
        $html = '';
        $pos = 0;
        $enable_logo = $pos + 1;
        $enables = explode('|', $this->mod_value[$enable_logo]);
        if (isset($enables[$this->styleid])) {
            $enable_logo = $enables[$this->styleid];
        } else {
            $enable_logo = $enables[0];
        }
        if ($enable_logo == 'displayTopContent') {
            $html = $this->displayCode($pos);
        }
        return $html;
    }

    public function hookdisplayFooterNav()
    {
        $html = '';
        $pos = 14;
        $html = $this->displayCode($pos);
        return $html;
    }

    public function hookFooter()
    {
        $html = '';
        $pos = 21;
        $html = $this->displayCode($pos);
        return $html;
    }

    public function hookdisplayFooterBanner()
    {
        $html = '';
        $pos = 28;
        $html = $this->displayCode($pos);
        return $html;
    }

    public function displayCode($pos)
    {
        $html = '';
        $display = $this->mod_value[$pos];

        if ($display == 'yes') {

            $totalgrid = 2 + $pos;
            $total_grids = explode('|', $this->mod_value[$totalgrid]);
            if (isset($total_grids[$this->styleid])) {
                $totalgrid = $total_grids[$this->styleid];
            } else {
                $totalgrid = $total_grids[0];
            }
            $this->smarty->assign('totalgrid', $totalgrid);

            $logo_type = 3 + $pos;
            $this->smarty->assign('logo_type', $this->mod_value[$logo_type]);

            $logo_effect = 4 + $pos;
            $this->smarty->assign('logo_effect', $this->mod_value[$logo_effect]);

            $imgurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
            $imgurl = $imgurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';
            $logo_image = 5 + $pos;
            $this->smarty->assign('logo_image', $imgurl.$this->mod_value[$logo_image]);

            $logo_text = 6 + $pos;
            $this->smarty->assign('logo_text', $this->mod_value[$logo_text]);

            if ($pos == 0) {
                $logo_icon = 35;
                if ($this->mod_value[$logo_icon] == '') {
                    $this->smarty->assign('logo_icon', $this->mod_value[$logo_icon]);
                } else {
                    $this->smarty->assign('logo_icon', $imgurl.$this->mod_value[$logo_icon]);
                }

                $logo_subtitle = 36;
                $this->smarty->assign('logo_subtitle', $this->mod_value[$logo_subtitle]);
            }

            if ($pos == 7) {
                $logo_icon = 38;
                if ($this->mod_value[$logo_icon] == '') {
                    $this->smarty->assign('logo_icon', $this->mod_value[$logo_icon]);
                } else {
                    $this->smarty->assign('logo_icon', $imgurl.$this->mod_value[$logo_icon]);
                }

                $logo_subtitle = 39;
                $this->smarty->assign('logo_subtitle', $this->mod_value[$logo_subtitle]);
            }

            if (Configuration::get('uhu_top_banner') == 1) {
                $this->smarty->assign('show_topbanner', 'yes');
            } else {
                $this->smarty->assign('show_topbanner', 'no');
            }

            $mvalue = Tools::unserialize(Configuration::get('uhu_value_setting'));
            if ($mvalue[14] == 'content') {
                $this->smarty->assign('show_close_header', 'yes');
            } else {
                $this->smarty->assign('show_close_header', 'no');
            }

            $html .= $this->display(__FILE__, $this->name.'.tpl');
        }

        return $html;
    }
}
