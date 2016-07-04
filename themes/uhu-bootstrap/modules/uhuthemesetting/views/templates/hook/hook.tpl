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

{if isset($gfont_logo) AND $gfont_logo}
<link id="link_logo" rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family={$gfont_logo|escape:'htmlall':'UTF-8'}" type="text/css">
{/if}

{if isset($googlefont) AND $googlefont}
<link rel="stylesheet" href="http{if Tools::usingSecureMode()}s{/if}://fonts.googleapis.com/css?family={$googlefont|escape:'htmlall':'UTF-8'}" type="text/css">
{/if}

{if isset($shownewsletter) AND $shownewsletter}
<style type="text/css">
	#newsletter_block_left{
		background-image: url('{$modules_dir|escape:'html':'UTF-8'}uhuthemesetting/views/img/newsletter/{$newsletter_file|escape:'html':'UTF-8'}');
		max-width: {$newsletter_width|escape:'html':'UTF-8'}px;
		max-height: {$newsletter_height|escape:'html':'UTF-8'}px;
		margin: 0;
		padding: {$newsletter_padding|escape:'html':'UTF-8'};
		background-size: 100% 100%;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		if(getCookie('uhuNewsletter') != 1){
			$('html').append('<a class="fancybox" style="display:none" href="#newsletter_block_left"></a>');
			setTimeout(function(){
				$('.fancybox').fancybox({
					padding: 0,
					width: {$newsletter_width|escape:'htmlall':'UTF-8'},
					height: {$newsletter_height|escape:'htmlall':'UTF-8'},
					autoSize: true,
					afterClose: function () {
						$('a[href="#newsletter_block_left"]').remove();
					}
				});
				$('a[href="#newsletter_block_left"]').trigger('click');
			}, {$newsletter_time|escape:'htmlall':'UTF-8'});
		}

		$('#dont-show-again').click(function(){
			if($('#dont-show-again').is(":checked")){
				setCookie('uhuNewsletter', 1, {$newsletter_days|escape:'htmlall':'UTF-8'});
			} else {
				delCookie('uhuNewsletter');
			}				
		})

		function setCookie(NameOfCookie, value, expiredays)
		{   
			var ExpireDate = new Date ();
			ExpireDate.setTime(ExpireDate.getTime() + (expiredays * 24 * 3600 * 1000));
		  
			document.cookie = NameOfCookie + "=" + escape(value) + ((expiredays == null) ? "" : "; expires=" + ExpireDate.toGMTString());   
		} 

		function getCookie(NameOfCookie)
		{
			if (document.cookie.length > 0)
			{
				begin = document.cookie.indexOf(NameOfCookie+"=");
				if (begin != -1)      
				{
					begin += NameOfCookie.length+1;
					end = document.cookie.indexOf(";", begin);
					if (end == -1) end = document.cookie.length;
					return unescape(document.cookie.substring(begin, end)); 
				}
			}
			return null;
		}

		function delCookie(NameOfCookie)   
		{
			if(getCookie(NameOfCookie)){
				document.cookie = NameOfCookie + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";   
			}
		}			
	});
</script>
{/if}

{if isset($slider) AND $slider}
<script type="text/javascript">
	$(document).ready(function() {
		$('#homefeatured').owlCarousel({
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
		$('#blocknewproducts').owlCarousel({
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
		$('#blockbestsellers').owlCarousel({
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
		$('#blockspecials').owlCarousel({
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