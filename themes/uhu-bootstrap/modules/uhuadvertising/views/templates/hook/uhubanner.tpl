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

{if $page_name == 'index'}
<div id="{$id_name|escape:'htmlall':'UTF-8'}" class="advertising col-xs-{$mgrid3|escape:'htmlall':'UTF-8'} col-sm-{$mgrid2|escape:'htmlall':'UTF-8'} col-md-{$mgrid1|escape:'htmlall':'UTF-8'}">
	<div class="image-container"{if $show_close_header == 'yes'}{if $show_topbanner == 'no'} style="display: none;"{/if}{/if}>
	{section name=loop loop=$adv_number}
	{if $adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
		<a href="{$adv_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}">
			<img class="img-responsive" src="{$adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}"  />
		</a>
		{if $zoom == 'rollimg' || $zoom == 'circleimg' || $zoom == 'upimg' || $zoom == 'downimg' || $zoom == 'rightimg' || $zoom == 'leftimg'}
		<a class="img_roll" href="{$adv_link_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}" style="background-image:url({$adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}})"></a>
		{/if}
	{/if}
	{/section}	
	</div>
	{if $show_close_header == 'yes'}
	<div class="close_header{if $show_topbanner == 'no'} active{/if}"></div>
	{/if}
</div>
{if $show_close_header == 'yes'}
<script type="text/javascript">	
//<![CDATA[
 	jQuery(document).ready(function($) {
		$('.close_header').on('click', function(){
			if($(this).hasClass('active')) {
				$('#uhubanner .image-container').stop(true, true).slideDown("400");
			} else {
				$('#uhubanner .image-container').stop(true, true).slideUp("400");
			}
			jQuery(this).toggleClass('active');
		});
	});
//]]>
</script>
{/if}
{/if}
