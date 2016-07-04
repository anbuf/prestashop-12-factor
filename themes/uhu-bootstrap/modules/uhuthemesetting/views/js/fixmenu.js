/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

var initPos=0;
window.onload=function(){
	if($('header .nav').height()!==null){
		initPos=$('header .nav').height();
		if($('header .banner').height()!==null){
			initPos += $('header .banner').height();
		}
	}
}

var scrollToTop=false;
window.onscroll=function(event)
{
	currentPos=jQuery(window).scrollTop()<0?0:jQuery(window).scrollTop();
	if(parseInt(currentPos)-parseInt(initPos)>0){
		scrollToTop=false;
	}else{
		scrollToTop=true;
	}
	if(scrollToTop){
		jQuery('#header .menu ul.nav_item li').css('line-height','');
		jQuery('header .banner').css('height', '');
		jQuery('header .nav').css('height', '');
		jQuery('header .nav').css('line-height', '');
		jQuery('header .content').removeClass('head-reduced');
	}else{
		jQuery('#header .menu ul.nav_item li').css('line-height','45px');
		jQuery('header .banner').css('height', '0');
		jQuery('header .nav').css('height', '0');
		jQuery('header .nav').css('line-height', '0');
		jQuery('header .content').addClass('head-reduced');
	}
};

$('.box-info-product .exclusive, .button.ajax_add_to_cart_button').hover( function (e) {
    $(this).addClass('animated pulse');
 }, function(e){
    $(this).removeClass('animated pulse');
 });
