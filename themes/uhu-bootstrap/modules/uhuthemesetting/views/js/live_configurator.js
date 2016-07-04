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


$(document).ready(
	function ()
	{
		Push_Block('switch');
		Push_Block('setting');
		Push_Block('color');
		Push_Block('font');
		Push_Block('border');
	}
);

function Push_Block(did)
{
	$('#'+did+'-cogs').click(
		function()
		{
			if ($('#switch-cogs').hasClass('active'))
				$('#switch-cogs').removeClass('active');
			if ($('#setting-cogs').hasClass('active'))
				$('#setting-cogs').removeClass('active');
			if ($('#color-cogs').hasClass('active'))
				$('#color-cogs').removeClass('active');
			if ($('#font-cogs').hasClass('active'))
				$('#font-cogs').removeClass('active');
			if ($('#border-cogs').hasClass('active'))
				$('#border-cogs').removeClass('active');
			$('#'+did+'-cogs').addClass('active');

			if ($('body').hasClass('body_pushed'))
			{
				if ($('#'+did+'_customization').hasClass('custom_active'))
				{
					$('body').removeClass('body_pushed');
					$('#'+did+'_customization').removeClass('custom_active');
					$('#'+did+'-cogs').removeClass('active');
				}
				else
				{
					if ($('#switch_customization').hasClass('custom_active'))
						$('#switch_customization').removeClass('custom_active');
					if ($('#setting_customization').hasClass('custom_active'))
						$('#setting_customization').removeClass('custom_active');
					if ($('#color_customization').hasClass('custom_active'))
						$('#color_customization').removeClass('custom_active');
					if ($('#font_customization').hasClass('custom_active'))
						$('#font_customization').removeClass('custom_active');
					if ($('#border_customization').hasClass('custom_active'))
						$('#border_customization').removeClass('custom_active');

					$('#'+did+'_customization').addClass('custom_active');
				}
			}
			else
			{
				$('body').addClass('body_pushed');
				$('#'+did+'_customization').addClass('custom_active');
			}
		}
	);
}

function Slide_Block(did, fid)
{
	$('#'+did+'-title').click(
		function()
		{
			if ($('#'+did+'-title').hasClass('active'))
			{
				$('#'+did+'-title').removeClass('active');
				$('#'+did+'-box').slideUp();
			}
			else
			{
				$(fid).find('.list-title p').removeClass('active');
				$(fid).children('.listbox').slideUp();
				$('#'+did+'-title').addClass('active');
				$('#'+did+'-box').slideDown();
			}
		}
	);
}

$(document).ready(
	function ()
	{ 
		$('#gear-right').click(
			function()
			{
				if ($(this).css('left') == '215px')
				{
					$('#tool_customization').animate({left : '-215px'}, 500);
					$(this).animate({left : '0px'}, 500);
				}
				else
				{
					$('#tool_customization').animate({left : '0px'}, 500);
					$(this).animate({left : '215px'}, 500);
				}
			}
		);

		$('#theme-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#color-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#color-box').slideDown();
				}
			}
		);

		$('#slider-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#slider-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#slider-box').slideDown();
				}
			}
		);

		$('#adver-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#adver-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#adver-box').slideDown();
				}
			}
		);

		$('#logo-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#logo-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#logo-box').slideDown();
				}
			}
		);

		$('#background-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#background-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#background-box').slideDown();
				}
			}
		);

		$('#text-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#text-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#text-box').slideDown();
				}
			}
		);

		$('#link-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#link-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#link-box').slideDown();
				}
			}
		);

		$('#icon-title').click(
			function()
			{
				if ($(this).children('i').hasClass('icon-caret-down'))
				{
					$(this).children('i').removeClass('icon-caret-down').addClass('icon-caret-up');
					$('#icon-box').slideUp();
				}
				else
				{
					$(this).children('i').removeClass('icon-caret-up').addClass('icon-caret-down');
					$('#icon-box').slideDown();
				}
			}
		);
	}		
);
