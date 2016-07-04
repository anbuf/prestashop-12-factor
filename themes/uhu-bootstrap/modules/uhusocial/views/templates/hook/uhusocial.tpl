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

<div id="uhu_qt_social" class="col-xs-12 col-sm-{$totalgrid|escape:'htmlall':'UTF-8'}">
	<h4 class="title_block">{$social_title|escape:'htmlall':'UTF-8'}</h4>
	<ul class="{$social_type|escape:'htmlall':'UTF-8'}">
		{section name=loop loop=$social_number} 
		<li>
			<a href="{$social_links_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
				<span class="s{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}" style="background-image:url({$modules_dir|escape:'htmlall':'UTF-8'}uhuthemesetting/views/img/social/{$social_icons_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}})"></span>
			</a>
		</li>
		{/section}
	</ul>
</div>
