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
{section name=loop loop=$adv_number}
	{if $adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} <> ''}
	<div class="{$holder|escape:'htmlall':'UTF-8'}" data-image="{$adv_image_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}"></div>
	{/if}
{/section}
	<script type="text/javascript">
		$(document).ready(function() {
			$('.{$holder|escape:'htmlall':'UTF-8'}').imageScroll({
//          	image: null,
//            	imageAttribute: 'image',
            	container: $('{$container|escape:'htmlall':'UTF-8'}'),
            	speed: {$speed|escape:'htmlall':'UTF-8'},
//            	coverRatio: 0.75,
//            	holderClass: 'imageHolder',
//            	holderMinHeight: 200,
				holderMaxHeight: {$maxheight|escape:'htmlall':'UTF-8'},
//            	extraHeight: 0,
//            	mediaWidth: 1600,
//            	mediaHeight: 900,
//            	parallax: true,
//            	touch: false
			});
		});
	</script>
{/if}
