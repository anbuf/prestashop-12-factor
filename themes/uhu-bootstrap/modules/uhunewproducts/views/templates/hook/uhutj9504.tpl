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

<script type="text/javascript">
	$(document).ready(function() {
		$('#uhu_tj_9502 .block_content').owlCarousel({
			loop: true,
			autoplay: false,
			margin: 30,
			responsiveClass: true,
			nav: false,
			dots: true,
			responsive: {
			0: 	{
					items: {$responsive3|escape:'html':'UTF-8'},
				},
			600: {
					items: {$responsive2|escape:'html':'UTF-8'},
				},
			1000: {
					items: {$responsive1|escape:'html':'UTF-8'},
				}
			}
		})
	});
</script>
<div id="uhu_tj_9502" class="col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}">
	<div class="block_content">
		{section name=cloop loop=$category_number}
		<div class="products_block">
			<h4 class="title_block list wow fadeInUp">{$title_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}</h4>
			<div id="more_info_sheets" class="tab-content">
				{if isset($products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}) AND $products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}	
					{include file="$tpl_dir./product-list.tpl" items="6" products=$products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} class='pd tab-pane' id="products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}" pg="{$productgrid|escape:'htmlall':'UTF-8'}"}
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