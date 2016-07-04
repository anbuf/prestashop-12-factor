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

<div id="font-right" class="config_icons">
	<i id="font-cogs" class="icon-font icon-2x"></i>
</div>
<form action="" method="post">
	<div id="font_customization" class="custom_style">
		<div id="block-header">
			Font Editor
		</div>
		{foreach $changelist['name'] as $key => $listid}
		<div class="list-title {$changelist['active'][$key]|escape:'htmlall':'UTF-8'}">
			<p id="{$listid|escape:'htmlall':'UTF-8'}-title" class="caret-down">
				{$changelist['title'][$key]|escape:'htmlall':'UTF-8'} 
			</p>
		</div>
		<div id="{$listid|escape:'htmlall':'UTF-8'}-box" class="listbox row" style="display: none;">
			{assign var='nbItems' value=0}
			{foreach $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['item'] as $itemid}
			<div class="item{if $nbItems++%4 == 0}-first{/if} col-xs-12 col-sm-3 col-md-3">
				<label class="subtle">{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'|replace:"font-family":"Family"|replace:"font-size":"Size"|replace:"font-style":"Style"|replace:"font-weight":"Weight"|replace:"text-align":"Align"|replace:"text-transform":"Transform"|replace:"text-indent":"Indent"|replace:"line-height":"Height"}</label>
				<select name="{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}" id="{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}">
				{if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'font-family'}
					{foreach $fontfamily_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'font-size'}
					{foreach $fontsize_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'font-weight'}
					{foreach $fontweight_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'font-style'}
					{foreach $fontstyle_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'text-align'}
					{foreach $textalign_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'text-transform'}
					{foreach $texttransform_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'text-indent'}
					{foreach $textindent_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{elseif $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}] == 'line-height'}
					{foreach $lineheight_list as $item}
					<option value="{$item|escape:'htmlall':'UTF-8'}" {if $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['value'][{$itemid|escape:'htmlall':'UTF-8'}] == $item}selected="selected"{/if}>{$item|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				{/if}
				</select>
			</div>
			{/foreach}
		</div>
		{/foreach}

		<div class="btn-tools">
			{if $livedemo <> 1}
			<button type="submit" class="btn btn-2" id="savefont" name="submitFontConfigurator">Save Font</button>
			{else}
			<button type="submit" class="btn btn-2" id="savefont" name="">You can't Save at this site</button>
			{/if}
		</div>
		<div id="block-version">
			© uhuPage. All rights reserved.		
		</div>
	</div>

</form>

<script type="text/javascript">
jQuery(document).ready(
	function($)
	{
		{foreach $changelist['name'] as $key => $listid}
		Slide_Block('{$listid|escape:'htmlall':'UTF-8'}', '#font_customization');		
		{/foreach}
	});
</script>

<script type="text/javascript">
jQuery(document).ready(
	function($)
	{
		{foreach $changelist['name'] as $listid}
			{foreach $fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['item'] as $itemid}
				change_fontstyle('{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['modid'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}', '{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['selector'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}', '{$fontstyles[{$listid|escape:'htmlall':'UTF-8'}]['type'][{$itemid|escape:'htmlall':'UTF-8'}]|escape:'htmlall':'UTF-8'}');
			{/foreach}
		{/foreach}
	});
</script>

{literal}
<script type="text/javascript">
	function change_fontstyle(cssid, selectors, css){
		$('#'+cssid).change(function(){
			var value=$("option:selected",this).val();
			$(selectors).css(css, value);
			if(css=='font-family'){
				if($('head').find('link#'+cssid).length<1){
					$('head').append('<link id="'+cssid+'" href="" rel="stylesheet" type="text/css"/>');
				}
			$('link#'+cssid).attr({href:'http://fonts.googleapis.com/css?family='+value});
			}
		});
	}
</script>
{/literal}