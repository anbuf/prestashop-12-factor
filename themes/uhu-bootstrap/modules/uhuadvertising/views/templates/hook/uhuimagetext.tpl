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

{if $page_name == 'index' || $show_footer == 'yes'}
<div id="{$id_name|escape:'htmlall':'UTF-8'}" class="advertising col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}">
	<div class="block_content">
		<ul>
		{section name=loop loop=$adv_number}
			{if $adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
			<li class="col-xs-{$adv_mobile_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}} col-sm-{$adv_grid_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}} col-md-{$adv_grid_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}} ad{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} {$zoom|escape:'htmlall':'UTF-8'} wow {$animate_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}|escape:'htmlall':'UTF-8'}" data-wow-delay="{$delay_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
				<div class="image-container">
					<a href="{$adv_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
						<img class="img-responsive" src="{$adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}"  />
					{if $adv_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
						<h3 class="item-title">{$adv_title_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}|nl2br}</h3>
					{/if}
					{if $adv_text_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
						<div class="item-html">
							{$adv_text_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}|nl2br}</i>
						</div>
					{/if}
					</a>
				</div>
			</li>
			{/if}
		{/section}	
		</ul>
	</div>	
</div>
{/if}
