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
{if ($pos == 'column' && $page_name == 'index') || $pos <> 'column'}
{if $owlslider == 'yes' && $reassure_number > 1}
<script type="text/javascript">
	$(document).ready(function() {
		$('.{$sliderid|escape:'htmlall':'UTF-8'}').owlCarousel({
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
{/if}
<div {if $divid <> ''}id="{$divid|escape:'htmlall':'UTF-8'}"{else}id="uhu_reassure_{$pos|escape:'htmlall':'UTF-8'}"{/if} class="col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}">
	{if isset($block_title) && $block_title}
	<h2 class="title_block">{$block_title|escape:'htmlall':'UTF-8'}</h2>
	{/if}
	{if isset($block_info) && $block_info}
	<p class="title_info">{$block_info|escape:'htmlall':'UTF-8'}</p>
	{/if}
	<div class="block_content">
		<ul class="{$sliderid|escape:'htmlall':'UTF-8'} scale">
		{section name=loop loop=$reassure_number}
		{if $reassure_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> '' || $reassure_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
			<li class="col-xs-{$itemgrid|escape:'htmlall':'UTF-8'} col-sm-{$itemgrid|escape:'htmlall':'UTF-8'} wow slideInUp {if $smarty.section.loop.first}first_item{/if} {if $smarty.section.loop.last}last_item{/if}" data-wow-delay="">
				<div class="list-container">
						{if $icon_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> 'false'}
							{if isset($reassure_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<i class="{$reassure_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}"></i>
							{/if}
						{else}
							{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<a href="{$reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
							{/if}
								<img class="img-responsive" src="{$reassure_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}" alt="" />
							{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							</a>
							{/if}
						{/if}
						<div class="type-text">
							{if isset($reassure_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<h3>
								{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
								<a href="{$reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
								{/if}
								{$reassure_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}
								{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
								</a>
								{/if}
							</h3>
							{/if}

							{if isset($reassure_subtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_subtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<h4>{$reassure_subtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}</h4>
							{/if}

							{if isset($reassure_text_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_text_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							{foreach from=$reassure_text_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} item=text name=myLoop}
							{if $text <> ''}<p>{$text|escape:'html':'UTF-8'|nl2br}</p>{/if}
							{/foreach}
							{/if}

							{if isset($reassure_ftitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_ftitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<h4 class="foot_title">
								{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
								<a href="{$reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
								{/if}
									{$reassure_ftitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}
								{if isset($reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
								</a>
								{/if}
							</h4>
							{/if}

							{if isset($reassure_fsubtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}) && $reassure_fsubtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
							<h4 class="foot_subtitle">{$reassure_fsubtitle_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}</h4>
							{/if}
						</div>
				</div>
			</li>
		{/if}
		{/section}
		</ul>
	</div>
</div>
{/if}
