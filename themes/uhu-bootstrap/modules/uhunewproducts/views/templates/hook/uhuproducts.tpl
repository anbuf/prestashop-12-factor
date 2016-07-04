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
		$('#uhu_tj_9502').owlCarousel({
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
<div id="uhu_tj_9502" class="">
	<ul id="home-uhu-tabs" class="nav nav-tabs title_block">
		{section name=cloop loop=$category_number}		
			<li class="{if $smarty.section.cloop.index|escape:'htmlall':'UTF-8' == 0}active{/if}"><a data-toggle="tab" href="#uhuproducts_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}" class="uhuproducts_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}">{$title_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}</a></li>
		{/section}
	</ul>
	<div id="home-uhu-contents" class="tab-content">
	{section name=cloop loop=$category_number}
		{if isset($products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}) AND $products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}	
			{include file="$tpl_dir./product-list.tpl" products=$products_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} class='uhuproducts tab-pane' id="uhuproducts_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}"}
		{else}
			<ul id="blocknewproducts" class="blocknewproducts tab-pane">
				<li class="alert alert-info">{l s='No new products at this time.' mod='uhunewproducts'}</li>
			</ul>
		{/if}
	{/section}
	</div>
</div>
