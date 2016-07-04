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
<!-- MODULE Block uhubxslider -->
<div id="uhuslider" class="col-xs-{$totalgrid|escape:'htmlall':'UTF-8'} col-md-{$totalgrid|escape:'htmlall':'UTF-8'}">
	<div class="block_content">
		<ul id="uhu_slider">
		{section name=loop loop=$slider_number} 
		
			{if isset($slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_show) AND $slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_show == 'yes'}
			<li class="slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} {$slider|escape:'htmlall':'UTF-8'}">

				<img class="img-responsive slider" src="{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image}" />

				<div class="slide_content" style="{if {$slidecontent_top|escape:'htmlall':'UTF-8'}}top: {$slidecontent_top|escape:'htmlall':'UTF-8'};{/if} {if {$slidecontent_left|escape:'htmlall':'UTF-8'}}left: {$slidecontent_left|escape:'htmlall':'UTF-8'};{/if}">
					<h2 class="slide-h2">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h2}</h2>
					<h3 class="slide-h3">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h3}</h3>
					<h4 class="slide-h4">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h4}</h4>
					<h5 class="slide-h5">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h5}</h5>
					<h6 class="slide-link"><span>
						{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}}<a href="{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}">{/if}
						{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link|stripslashes}
						{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}}</a>{/if}
					</span></h6>
					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}}
					<img class="img-responsive logo slide-logo" src="{$imgurl|escape:'htmlall':'UTF-8'}{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}"  />
					{/if}
				</div>
			</li>
			{/if}
			
		{/section}
		</ul>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#uhu_slider').bxSlider({
		auto: true,
		autoHover: true,
		pause: {$slider_delay|escape:'htmlall':'UTF-8'},
		speed: {$slider_duration|escape:'htmlall':'UTF-8'},
		useCSS: false,
		mode: 'horizontal',
		easing: '{$easing|escape:'htmlall':'UTF-8'}',
		prevText: '<i class="{$awesome_prev|escape:'htmlall':'UTF-8'}"></i>', 
		nextText: '<i class="{$awesome_next|escape:'htmlall':'UTF-8'}"></i>',

		onSliderLoad: function(){
			body = $('#index').width();
			if (body < 768)
			{
				$('#uhuslider').css('height', {$mobile_height|escape:'htmlall':'UTF-8'});
				$('.bx-viewport').css('height', {$mobile_height|escape:'htmlall':'UTF-8'});
				$('#uhu_slider').css('display', 'block');
				{section name=loop loop=$slider_number} 
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_s}');
				{/section}
			}
			else if (body < 960)
			{
				$('#uhuslider').css('height', {$tablet_height|escape:'htmlall':'UTF-8'});
				$('.bx-viewport').css('height', {$tablet_height|escape:'htmlall':'UTF-8'});
				$('#uhu_slider').css('display', 'block');
				{section name=loop loop=$slider_number} 
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_m}');
				{/section}
			}
			else
			{
				$('#uhuslider').css('height', {$pc_height|escape:'htmlall':'UTF-8'});
				$('.bx-viewport').css('height', {$pc_height|escape:'htmlall':'UTF-8'});
				$('#uhu_slider').css('display', 'block');
				{section name=loop loop=$slider_number} 
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image}');
				{/section}
			};
			$('.slide-h2').addClass('animated {$h2_animate_in|escape:'htmlall':'UTF-8'}').delay({$h2_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h3').addClass('animated {$h3_animate_in|escape:'htmlall':'UTF-8'}').delay({$h3_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h4').addClass('animated {$h4_animate_in|escape:'htmlall':'UTF-8'}').delay({$h4_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h5').addClass('animated {$h5_animate_in|escape:'htmlall':'UTF-8'}').delay({$h5_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-link').addClass('animated {$link_animate_in|escape:'htmlall':'UTF-8'}').delay({$link_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-logo').addClass('animated {$logo_animate_in|escape:'htmlall':'UTF-8'}').delay({$logo_time_in|escape:'htmlall':'UTF-8'}).show(10);
		},
		onSlideAfter: function(){
			$('.slide-h2').addClass('animated {$h2_animate_in|escape:'htmlall':'UTF-8'}').delay({$h2_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h3').addClass('animated {$h3_animate_in|escape:'htmlall':'UTF-8'}').delay({$h3_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h4').addClass('animated {$h4_animate_in|escape:'htmlall':'UTF-8'}').delay({$h4_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-h5').addClass('animated {$h5_animate_in|escape:'htmlall':'UTF-8'}').delay({$h5_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-link').addClass('animated {$link_animate_in|escape:'htmlall':'UTF-8'}').delay({$link_time_in|escape:'htmlall':'UTF-8'}).show(10);
			$('.slide-logo').addClass('animated {$logo_animate_in|escape:'htmlall':'UTF-8'}').delay({$logo_time_in|escape:'htmlall':'UTF-8'}).show(10);
		},
		onSlideBefore: function(){
			$('.slide-h2').hide();
			$('.slide-h3').hide();
			$('.slide-h4').hide();
			$('.slide-h5').hide();
			$('.slide-link').hide();
			$('.slide-logo').hide();
		},
	});
});
</script>
<!-- /MODULE Block uhubxslider -->
{/if}