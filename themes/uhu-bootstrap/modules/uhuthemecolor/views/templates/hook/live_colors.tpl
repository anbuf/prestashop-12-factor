{*
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
*  @license    http://opensource.org/#licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<form action="" method="post">
	<div id="color-right" class="config_icons">
		<i id="color-cogs" class="icon-barcode icon-2x"></i>
	</div>
	<div id="color_customization" class="custom_style">
		<div id="block-header">
			Color Editor
		</div>
		{foreach $changelist['name'] as $key => $listid}
		<div class="list-title {$changelist['active'][$key]|escape:'htmlall':'UTF-8'}">
			<p id="{$listid|escape:'htmlall':'UTF-8'}-title" class="{if $listid == 'global' || $listid == 'cpage' || $listid == 'ppage' || $listid == 'opage'}active{/if}">
				{$changelist['title'][$key]|escape:'htmlall':'UTF-8'} 
			</p>
		</div>
		<div id="{$listid|escape:'htmlall':'UTF-8'}-box" class="listbox row" style="display: none;">
			{assign var='nbItems' value=0}
			{foreach $colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['item'] as $itemid}
			<div class="item{if $nbItems++%3 == 0}-first{/if} col-xs-12 col-sm-4 col-md-4">
				<input type="hidden" name="{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}" data-text="hidden" data-hex="true" class="colorpicker mColorPicker" id="{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}" value="{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}" style="background-color: transparent; color: white;">
				<span style="cursor: pointer; background-color:{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'};" id="icp_{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}" class="colorpicker mColorPickerTrigger" data-mcolorpicker="true">&nbsp;</span>
				{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['title'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'|replace:"border-color":"Border"|replace:"background-color":"Backgd"|replace:"color":"Color"}
			</div>
			{/foreach}
		</div>
		{/foreach}
		<div class="btn-tools">
			{if $livedemo == 1}
			<button type="submit" class="btn btn-2" id="savecolor" name="">You can't Save at this site</button>
			{else}
			<button type="submit" class="btn btn-2" id="savecolor" name="submitColorConfigurator">Save Color</button>
			{/if}
		</div>
		<div id="block-version">
			Theme Configurator v2.0
			<p>© uhuPage. All rights reserved.</p>
		</div>
	</div>
</form>

<script type="text/javascript">
$.fn.mColorPicker.defaults = {
	imageFolder: '{$module_dir|escape:'htmlall':'UTF-8'}views/img/',
	swatches: ["#ffffff","#ffff00","#00ff00","#00ffff","#0000ff","#ff00ff","#ff0000","#4c2b11","#3b3b3b","#000000"]
	};
jQuery(document).ready(
	function($)
	{
		{foreach $changelist['name'] as $listid}
		{foreach $colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['item'] as $itemid}
		change_colorstyle('{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}', '{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['selector'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}', '{$colorstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}');
		{/foreach}
		{/foreach}
	});
</script>

<script type="text/javascript">
jQuery(document).ready(
	function($)
	{
		{foreach $changelist['name'] as $key => $listid}
		Slide_Block('{$listid|escape:'htmlall':'UTF-8'}', '#color_customization');		
		{/foreach}
	});
	$('#savecolor').click(
	function()
	{
		location.reload(true);
	}
);	
</script>

{literal}
<script type="text/javascript">
	function change_colorstyle(cssid, selectors, css){
		$('#'+cssid).bind('change',function(){$(selectors).css(css,$(this).val());});
	}
</script>
{/literal}

