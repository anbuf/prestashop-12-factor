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

{if ($did <> 'home' && $did <> 'slider' && $page_name <> 'index') || $page_name == 'index'}
{if $owlslider == 'yes'}
{if $slider_number > 1}
<script type="text/javascript">
	$(document).ready(function() {
		$('.contactslider').owlCarousel({
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
<div id="uhu_contactus_{$did|escape:'htmlall':'UTF-8'}" class="contactus col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}" data-stellar-background-ratio="0.1">
	<div class="contactslider">
	{section name=cloop loop=$slider_number}
		<div class="block_content">
		{if $logo_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} <> ''}
			<div class="logo">
			{if $logolink_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} <> ''}<a href="{$logolink_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}">{/if}
				<img class="img-responsive wow animated slideInUp" src="{$logo_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}" alt="" />
			{if $logolink_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} <> ''}</a>{/if}
			</div>
		{/if}
			<div class="info">
			{if $subtitle_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} <> ''}<h5 class="sub_title wow animated fadeInDown">{$subtitle_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}</h5>{/if}
			{if $title_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} <> ''}<h4 class="title_block wow animated fadeInDown">{$title_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'}}</h4>{/if}
			{foreach from=$texts_{$smarty.section.cloop.index|escape:'htmlall':'UTF-8'} item=text name=myLoop}
			{if $text <> ''}<p class="wow animated lightSpeedIn">{$text|escape:'html':'UTF-8'|nl2br}</p>{/if}
			{/foreach}
			</div>
		</div>
	{/section}
	</div>
</div>
{else}
<div id="uhu_contactus_{$did|escape:'htmlall':'UTF-8'}" class="contactus col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}" data-stellar-background-ratio="0.1">
	<div class="block_content">
	{if $logo <> ''}
		<div class="logo">
			{if $logolink <> ''}<a href="{$logolink|escape:'htmlall':'UTF-8'}">{/if}
			<img class="img-responsive wow animated slideInUp" src="{$logo|escape:'htmlall':'UTF-8'}" alt="" />
			{if $logolink <> ''}</a>{/if}
		</div>
	{/if}
		<div class="info">
		{if $subtitle <> ''}<h5 class="sub_title wow animated fadeInDown">{$subtitle|escape:'htmlall':'UTF-8'}</h5>{/if}
		{if $title <> ''}<h4 class="title_block wow animated fadeInDown">{$title|escape:'htmlall':'UTF-8'}</h4>{/if}
		{foreach from=$texts item=text name=myLoop}
		{if $text <> ''}<p class="wow animated lightSpeedIn">{$text|escape:'html':'UTF-8'|nl2br}</p>{/if}
		{/foreach}
		</div>
		<ul>
		{if $phone != ''}<li class="phone wow animated lightSpeedIn"><i class="icon-phone-sign"></i>{$phone|escape:'htmlall':'UTF-8'}</li>{/if}
		{if $company != ''}<li class="company wow animated lightSpeedIn"><i class="icon-building"></i>{$company|escape:'htmlall':'UTF-8'}</li>{/if}
		{if $address != ''}<li class="address wow animated lightSpeedIn"><i class="icon-map-marker"></i>{$address|escape:'htmlall':'UTF-8'}</li>{/if}
		{if $email != ''}<li class="email wow animated lightSpeedIn"><i class="icon-envelope-alt"></i>{mailto address=$email|escape:'htmlall':'UTF-8' encode="hex"}</li>{/if}
		</ul>
	</div>
</div>
{/if}
{/if}