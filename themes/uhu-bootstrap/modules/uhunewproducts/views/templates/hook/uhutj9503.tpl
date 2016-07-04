{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. 
*
*  @author    uhuPage <support@uhupage.com>
*  @copyright 2007-2015 uhuPage
*  @license   GNU General Public License version 2
*}

<div id="uhu_tj_9502" class="col-xs-12 col-sm-{$totalgrid|escape:'htmlall':'UTF-8'} col-md-{$totalgrid|escape:'htmlall':'UTF-8'}">
	<div class="block_content">
		{section name=cloop loop=$category_number}
		<div class="products_block {if isset($blockgrid) && $blockgrid <> 0}col-xs-12 col-sm-{$blockgridtablet|escape:'htmlall':'UTF-8'} col-md-{$blockgrid|escape:'htmlall':'UTF-8'}{/if}">
			<h4 class="title_block list wow fadeInUp">{$title_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}</h4>
			<div id="more_info_sheets" class="tab-content">
				{if isset($products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}) AND $products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}	
					{include file="$tpl_dir./product-list.tpl" products=$products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} class='pd tab-pane' id="products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}"}
				{else}
					<ul id="blocknewproducts" class="blocknewproducts tab-pane">
						<li class="alert alert-info">{l s='No new products at this time.' mod='uhunewproducts'}</li>
					</ul>
				{/if}
			</div>
		</div>
		{/section}
	</div>
</div>