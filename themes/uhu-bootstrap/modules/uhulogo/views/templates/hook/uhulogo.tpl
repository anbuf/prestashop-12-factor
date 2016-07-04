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

<div id="logo" class="col-xs-12 col-sm-{$totalgrid|escape:'htmlall':'UTF-8'}">
	<a href="{if $force_ssl|escape:'htmlall':'UTF-8'}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{else}{$base_dir|escape:'htmlall':'UTF-8'}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
	{if $logo_type == 'text'}
		{if $logo_icon <> ''}
		<div class="icon"><img class="img-responsive" src="{$logo_icon|escape:'htmlall':'UTF-8'}" alt="" /></div>
		{/if}
		<div class="text">
			<span class="title">{$logo_text|escape:'htmlall':'UTF-8'}</span>
		{if $logo_subtitle <> ''}
			<span class="info">{$logo_subtitle|escape:'htmlall':'UTF-8'}</span>
		{/if}
		</div>
	{else}
		<img class="logo img-responsive" src="{$logo_image|escape:'htmlall':'UTF-8'}" />
	{/if}
	</a>
</div>
{if $show_close_header == 'yes'}
<div class="close_header{if $show_topbanner == 'no'} active{/if}"></div>
<script type="text/javascript">	
//<![CDATA[
 	jQuery(document).ready(function($) {
		$('.close_header').on('click', function(){
			if($(this).hasClass('active')) {
				jQuery('header .top_header').css('height', '');
				jQuery('header .top_header').css('display', 'block');
			} else {
				jQuery('header .top_header').css('height', '0');
				jQuery('header .top_header').css('display', 'block');
			}
			jQuery(this).toggleClass('active');
		});
	});
//]]>
</script>
{/if}