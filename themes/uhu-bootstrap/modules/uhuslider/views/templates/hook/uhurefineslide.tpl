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

{if $page_name == "index"}
<!-- MODULE Block uhuslider -->
<div id="uhuslider" class="col-xs-{$totalgrid|escape:'htmlall':'UTF-8'} col-md-{$totalgrid|escape:'htmlall':'UTF-8'}">
	<div class="block_content">
		<div class="loading"></div>
		<ul id="uhu_slider" style="visibility: hidden;" class="cycle-slideshow">
		{section name=loop loop=$slider_number} 
		
			{if isset($slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_show) AND $slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_show == 'yes'}

			<li class="slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} group {$slider|escape:'htmlall':'UTF-8'}">
				{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link}}<a href="{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}">{/if}
				<img class="slider" src="" />
				{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link}}</a>{/if}

				{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_texteffect == 'true'}}
				<div class="slide_content" style="display: none; {if {$slidecontent_top_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}}top: {$slidecontent_top_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}};{/if} {if {$slidecontent_left_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}}left: {$slidecontent_left_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}};{/if}">
					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h2}}
					<h2 class="sd2 animated slide-h2">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h2}</h2>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h3}}
					<h3 class="sd3 animated slide-h3">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h3}</h3>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h4}}
					<h4 class="sd4 animated slide-h4">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h4}</h4>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h5}}
					<h5 class="sd5 animated slide-h5">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h5}</h5>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link}}
					<h6 class="sd6 animated slide-link slidelink">
						<a class="btn lnk_view" href="{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}">
						<span>{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link|stripslashes}</span>
						</a>
					</h6>
					{/if}
				</div>

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}}
					<img class="{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_texteffect == 'true'}}animated{/if} logo slide-logo" src="{$imgurl|escape:'htmlall':'UTF-8'}{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}"  />
					{/if}
				{else}
				<div class="slide_content" style="display: none; {if {$slidecontent_top_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}}top: {$slidecontent_top_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}};{/if} {if {$slidecontent_left_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}}}left: {$slidecontent_left_{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}};{/if}">
					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h2}}
					<h2 class="sd2">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h2}</h2>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h3}}
					<h3 class="sd3">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h3}</h3>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h4}}
					<h4 class="sd4">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h4}</h4>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h5}}
					<h5 class="sd5">{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_h5}</h5>
					{/if}

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link}}
					<h6 class="sd6 slidelink">
						<a class="btn lnk_view" href="{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_url}">
						<span>{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_link|stripslashes}</span>
						</a>
					</h6>
					{/if}
					</div>

					{if {$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}}
					<img class="slidelogo logo" src="{$imgurl|escape:'htmlall':'UTF-8'}{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_logo}"  />
					{/if}
				{/if}
			</li>
			{/if}
			
		{/section}
		</ul>
	</div>
</div>
<script type="text/javascript">
function loadImage(url, callback) {
    var img = new Image();
     img.src = url;

    if (img.complete) {
		callback.call(img);
    } else {
        img.onload = function () {
			callback.call(img);
            img.onload = null;
        };
    };
};

function imgLoaded(){
	//alert(this.width);
    $('.loading').css('display', 'none');
    $('#uhu_slider').css('display', 'block');
    $('.slide_content').css('display', 'block');
}

$(function () {
	var st = {$slider_number|escape:'htmlall':'UTF-8'};
	st = st - 1;
	$('#uhu_slider').refineSlide({
		maxWidth: {$max_width|escape:'htmlall':'UTF-8'},
		delay: {$slider_delay|escape:'htmlall':'UTF-8'},
		transitionDuration: {$slider_duration|escape:'htmlall':'UTF-8'}, 
		autoPlay: true,
		transition: '{$slider_transition|escape:'htmlall':'UTF-8'}',
		fallback3d: '{$slider_easing|escape:'htmlall':'UTF-8'}',
		useThumbs: false,
		useArrows: true,
		startSlide: st,
		arrowTemplate: '<div class="rs-arrows bx-control"><span class="rs-prev cycle-prev"><i class="{$awesome_prev|escape:'htmlall':'UTF-8'}"></i></span><span class="rs-next cycle-next"><i class="{$awesome_next|escape:'htmlall':'UTF-8'}"></i></span></div>',
		onInit: function(){
			var slider = this.slider,
			body = $('#index').width();
			if (body < 768)
			{
				$('.loading').css('height', {$mobile_height|escape:'htmlall':'UTF-8'});
				{section name=loop loop=$slider_number} 
				loadImage('{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_s}',imgLoaded);
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_s}');
				{/section}
			}
			else if (body < 960)
			{
				$('.loading').css('height', {$tablet_height|escape:'htmlall':'UTF-8'});
				{section name=loop loop=$slider_number} 
				loadImage('{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_m}',imgLoaded);
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image_m}');
				{/section}
			}
			else
			{
				$('.loading').css('height', {$pc_height|escape:'htmlall':'UTF-8'});
				{section name=loop loop=$slider_number} 
				loadImage('{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image}',imgLoaded);
				$('#uhu_slider li.slide{$smarty.section.loop.index|escape:'htmlall':'UTF-8'} img.slider').attr('src', '{$slider{$smarty.section.loop.index|escape:'htmlall':'UTF-8'}_image}');
				{/section}
			}
			//$('.slide_content').show();
			//$('.slide-h2').addClass('{$h2_animate_in|escape:'htmlall':'UTF-8'}').delay({$h2_time_in|escape:'htmlall':'UTF-8'}).show(10);
			//$('.slide-h3').addClass('{$h3_animate_in|escape:'htmlall':'UTF-8'}').delay({$h3_time_in|escape:'htmlall':'UTF-8'}).show(10);
			//$('.slide-h4').addClass('{$h4_animate_in|escape:'htmlall':'UTF-8'}').delay({$h4_time_in|escape:'htmlall':'UTF-8'}).show(10);
			//$('.slide-h5').addClass('{$h5_animate_in|escape:'htmlall':'UTF-8'}').delay({$h5_time_in|escape:'htmlall':'UTF-8'}).show(10);
			//$('.slide-link').addClass('{$link_animate_in|escape:'htmlall':'UTF-8'}').delay({$link_time_in|escape:'htmlall':'UTF-8'}).show(10);
			//$('.slide-logo').addClass('{$logo_animate_in|escape:'htmlall':'UTF-8'}').delay({$logo_time_in|escape:'htmlall':'UTF-8'}).show(10);
		},
		afterChange: function(){
			//$('.slide_content').show();
			$('.slide-h2').addClass('{$h2_animate_in|escape:'htmlall':'UTF-8'}').delay({$h2_time_in|escape:'htmlall':'UTF-8'});
			$('.slide-h3').addClass('{$h3_animate_in|escape:'htmlall':'UTF-8'}').delay({$h3_time_in|escape:'htmlall':'UTF-8'});
			$('.slide-h4').addClass('{$h4_animate_in|escape:'htmlall':'UTF-8'}').delay({$h4_time_in|escape:'htmlall':'UTF-8'});
			$('.slide-h5').addClass('{$h5_animate_in|escape:'htmlall':'UTF-8'}').delay({$h5_time_in|escape:'htmlall':'UTF-8'});
			$('.slide-link').addClass('{$link_animate_in|escape:'htmlall':'UTF-8'}').delay({$link_time_in|escape:'htmlall':'UTF-8'});
			$('.slide-logo').addClass('{$logo_animate_in|escape:'htmlall':'UTF-8'}').delay({$logo_time_in|escape:'htmlall':'UTF-8'});
			$('.sd2').show(20);
			$('.sd3').show(20);
			$('.sd4').show(20);
			$('.sd5').show(20);
			$('.sd6').show(20);
			$('.slide-logo').show(20);
		},
		onChange: function(){
			$('.sd2').hide();
			$('.sd3').hide();
			$('.sd4').hide();
			$('.sd5').hide();
			$('.sd6').hide();
			$('.slide-logo').hide();
		},
	});
});
</script>
<!-- /MODULE Block uhuslider -->
{/if}