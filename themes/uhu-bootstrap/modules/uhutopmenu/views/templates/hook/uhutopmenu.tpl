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
{if $MENU != ''}
{if $pos != 'top'}</div>{/if}<div class="menu">
		<ul class="nav_item umenu">
			{if $showhome == 'true'}
			<li class="home">
				<a href="{$base_dir|escape:'htmlall':'UTF-8'}" class="roll"><span data-title="{l s='Home' mod='uhutopmenu'}">{l s='Home' mod='uhutopmenu'}</span></a>
			</li>
			{/if}
			{$MENU|replace:"Categories":"{l s='Categories' mod='uhutopmenu'}"|replace:"Products":"{l s='Products' mod='uhutopmenu'}"|replace:"Brands":"{l s='Brands' mod='uhutopmenu'}"|replace:"News":"{l s='News' mod='uhutopmenu'}"|replace:"Links":"{l s='Links' mod='uhutopmenu'}|escape:'htmlall':'UTF-8'"}
			{if $showsearch == 'true'}
				<li class="sf-search noBack" style="float:right">
					<form id="searchbox" action="{$link->getPageLink('search')|escape:'html'}" method="get">
						<p>
							<input type="hidden" name="controller" value="search" />
							<input type="hidden" value="position" name="orderby"/>
							<input type="hidden" value="desc" name="orderway"/>
							<input type="text" name="search_query" value="{if isset($smarty.get.search_query)}{$smarty.get.search_query|escape:'html':'UTF-8'}{/if}" />
						</p>
					</form>
				</li>
			{/if}
		</ul>
		<div class="mobile mb-contener">
			{$MENU_MOBILE|replace:"Categories":"{l s='Categories' mod='uhutopmenu'}|escape:'htmlall':'UTF-8'"}
		</div>
{if $pos == 'top'}
	</div>
{/if}
		<script type="text/javascript">
			$('#header .menu ul.nav_item li.catall').click(function(){
				$('div.nav_pop').slideToggle();
			});
		
			$(document).ready(function(){
				$('.nav_item > li').mouseover(function(){
					$(this).addClass('active');
					$(this).children('div.nav_pop').css({
						'visibility':'visible',
						'height':'auto'
					});
				});				
				$('.nav_item > li').mouseleave(function(){
					$(this).removeClass('active');
					$('div.nav_pop').css({
						'visibility':'hidden',
						'height':'0px'
					});
				});
			});
		</script>
{/if}