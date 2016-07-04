{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. 
*
*  @author    uhuPage <support@uhupage.com>
*  @copyright 2007-2015 uhuPage
*}

{if $footall == 'yes'}
	</div>
{/if}
<!-- MODULE uhucopyright -->
	<div id="block_various_links_footer" class="footer-block col-xs-12 col-sm-12 ">
		<ul id="cms">
			{if !$PS_CATALOG_MODE && $display_special_footer}
				<li class="first_item">
					<a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}">{l s='Specials' mod='uhucopyright'}</a>
				</li>
			{/if}
			{if $display_new_footer}
				<li class="{if $PS_CATALOG_MODE}first_{/if}item">
					<a href="{$link->getPageLink('new-products')|escape:'html':'UTF-8'}">{l s='New products' mod='uhucopyright'}</a>
				</li>
			{/if}
			{if !$PS_CATALOG_MODE && $display_best_footer}
				<li class="item">
					<a href="{$link->getPageLink('best-sales')|escape:'html':'UTF-8'}">{l s='Top sellers' mod='uhucopyright'}</a>
				</li>
			{/if}
			{if $display_stores_footer}
				<li class="item">
					<a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}">{l s='Our stores' mod='uhucopyright'}</a>
				</li>
			{/if}
			{if $display_contact_footer}
				<li class="item">
					<a href="{$link->getPageLink($contact_url, true)|escape:'html':'UTF-8'}">{l s='Contact us' mod='uhucopyright'}</a>
				</li>
			{/if}
			{foreach from=$cmslinks item=cmslink}
				{if $cmslink.meta_title != ''}
					<li class="item">
						<a href="{$cmslink.link|addslashes|escape:'html':'UTF-8'}">{$cmslink.meta_title|escape:'html':'UTF-8'}</a>
					</li>
				{/if}
			{/foreach}
			{if $display_sitemap_footer}
				<li>
					<a href="{$link->getPageLink('sitemap')|escape:'html':'UTF-8'}">{l s='Sitemap' mod='uhucopyright'}</a>
				</li>
			{/if}
		</ul>
	</div>
	<div id="uhu_qt_copyright" class="footer-block col-xs-12 col-sm-{$totalgrid|escape:'htmlall':'UTF-8'}">
		{if $company <> ''}<span>{$company|escape:'htmlall':'UTF-8'}</span>{/if}
		<span>{$copyright|escape:'htmlall':'UTF-8'}</span>
		{if $logo <> ''}<span class="logo"><img src="{$imgurl|escape:'htmlall':'UTF-8'}{$logo|escape:'htmlall':'UTF-8'}" alt="" /></span>{/if}
		<p style="width: 0;height: 0;line-height: 0;padding: 0;margin: 0;overflow: hidden;">Designed by uhuPage</p>
{if $footall <> 'yes'}
	</div>
{/if}
<!-- /MODULE uhucopyright -->
