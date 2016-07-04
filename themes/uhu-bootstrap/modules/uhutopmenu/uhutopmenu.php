<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. 
*
*  @author    uhuPage <support@uhupage.com>
*  @copyright 2007-2015 uhuPage
*  @license   GNU General Public License version 2
*/

class Uhutopmenu extends Module
{
	private $menu = '';
	private $menuroll = '';
	private $html = '';
	private $user_groups;

	private $menumobile = '';

	/*
	 * Pattern for matching config values
	 */
	private $pattern = '/^([A-Z_]*)[0-9]+/';

	/*
	 * Name of the controller
	 * Used to set item selected or not in top menu
	 */
	private $page_name = '';

	/*
	 * Spaces per depth in BO
	 */
	private $spacer_size = '5';

	public function __construct()
	{
		$this->name = 'uhutopmenu';
		$this->tab = 'others';
		$this->version = '6.0.8';
		$this->author = 'uhuPage';

		parent::__construct();

		$this->displayName = 'uhu Popup menu';
		$this->description = $this->l('Add a popup menu on top of your shop.');

		$this->mod_name = 'topmenu';
		$this->mod_value = Tools::unserialize(Configuration::get('uhu_value_'.$this->mod_name));
	}

	public function install()
	{
		if (!parent::install() ||
			!$this->registerHook('displayTop') ||
			!$this->registerHook('displayTopContent') ||
			!$this->registerHook('header'))
			return false;

		return true;
	}

	public function uninstall()
	{
		return (parent::uninstall());
	}

	private function getMenuItems()
	{
		return explode(',', $this->mod_value[12]);
	}

	private function makeMenu()
	{
		$menu_items = $this->getMenuItems();
		$id_lang = (int)$this->context->language->id;
		//$id_shop = (int)Shop::getContextShopID();

		$cat_main = 'A';

		//$default_language = (int)Configuration::get('PS_LANG_DEFAULT');
		//$languages = Language::getLanguages(false);

		foreach ($menu_items as $item)
		{
			if (!$item || $item == 'CAT')
				continue;

			preg_match($this->pattern, $item, $value);
			$id = (int)Tools::substr($item, Tools::strlen($value[1]), Tools::strlen($item));

			switch (Tools::substr($item, 0, Tools::strlen($value[1])))
			{
				case 'CAT':
					$catetitle = Tools::substr($item, 0, Tools::strlen($value[1]));
					$con_grid = $this->mod_value[0];
					$adv_grid = $this->mod_value[1];

					$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
					$category = new Category((int)$id, (int)$id_lang);

					if ($category->level_depth > 1)
						$category_link = $category->getLink();
					else
						$category_link = $this->context->link->getPageLink('index');

					if (is_null($category->id))
						continue;

					$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
					// filter the categories that the user is allowed to see and browse
					if (!empty($is_intersected))
					{
						$this->menu .= '<li class="nav_li cat">';
						$this->menu .= '<a class="nav_a roll" href="'.Tools::HtmlEntitiesUTF8($category_link).'" title="">
											<span data-title="'.$category->name.'">'.$category->name.'</span></a>';
						$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';
						$this->menu .= '<dl class="pop_adver col-sm-'.$adv_grid.' col-md-'.$adv_grid.'">';

						//$con_img = Configuration::get('uhu_modvalue_'.$this->mod_name.'_28');
						$labels = explode('|', $this->mod_value[28]);
						$links = explode('|', $this->mod_value[29]);
						$label_num = count($labels);
						for ($i = 0; $i < $label_num; $i++)
						{
							$label = $labels[$i];
							if (isset($links[$i]))
								$link = $links[$i];
							else
								$link = '';

							if (strstr($label, $catetitle.$cat_main))
							{
								$imgs = explode(',', str_replace($catetitle.$cat_main.':', '', $label));
								$baseurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
								$imgurl = $baseurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';
								$count_imgs = count($imgs);
								for ($j = 0; $j < $count_imgs; $j++)
								{
									$this->menu .= '<dd class="col-sm-'.(12 / $count_imgs).' col-md-'.(12 / $count_imgs).'">';

									if (strstr($link, $catetitle.$cat_main))
									{
										$lnk = explode(',', str_replace($catetitle.$cat_main.':', '', $link));
										if (isset($lnk[$j]))
											$this->menu .= '<a href="'.$lnk[$j].'">';
									}
									if (strstr($imgs[$j], 'http://') <> '')
										$this->menu .= '<img class="img-responsive" src="'.$imgs[$j].'" />';
									else
										$this->menu .= '<img class="img-responsive" src="'.$imgurl.$imgs[$j].'" />';
									if (strstr($link, $catetitle.$cat_main))
									{
										$lnk = explode(',', str_replace($catetitle.$cat_main.':', '', $link));
										if (isset($lnk[$j]))
											$this->menu .= '</a>';
									}
									$this->menu .= '</dd>';
								}
								$this->menu .= PHP_EOL;
							}
						}
						//$this->getCategory((int)$id);
						$this->menu .= '</dl>';
					}

					$this->menu .= '<dl class="pop_content products_block col-sm-'.$con_grid.' col-md-'.$con_grid.'">';
					foreach ($menu_items as $item_man)
					{
						if (!$item_man || $item_man == 'CAT')
							continue;

						preg_match($this->pattern, $item_man, $value);
						$id = (int)Tools::substr($item_man, Tools::strlen($value[1]), Tools::strlen($item_man));

						if (Tools::substr($item_man, 0, Tools::strlen($value[1])) == $catetitle.$cat_main)
							$this->getSingleCategory((int)$id);
					}
					$this->menu .= '</dl>';
					$this->menu .= '</div>';
					$this->menu .= '</li>'.PHP_EOL;
					$cat_main = chr(ord($cat_main) + 1);
					break;
			}
		}
	}

	private function getCategory($id_category, $id_lang = false, $id_shop = false)
	{
		$id_shop = $id_shop;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category->level_depth > 1)
			$category_link = $category->getLink();
		else
			$category_link = $this->context->link->getPageLink('index');

		if (is_null($category->id))
			return;

		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);

		$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
		// filter the categories that the user is allowed to see and browse
		if (!empty($is_intersected))
		{
			if (count($children))
			{
				$grid = $this->mod_value[13];

				foreach ($children as $child)
				{
					//$this->getCategory((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);
					$category_child = new Category((int)$child['id_category'], (int)$id_lang);

					if ($category_child->level_depth > 1)
						$category_link = $category_child->getLink();
					else
						$category_link = $this->context->link->getPageLink('index');

					if (is_null($category_child->id))
						return;

					$children_two = Category::getChildren((int)$child['id_category'], (int)$id_lang, true, (int)$id_shop);

					$is_intersected = array_intersect($category_child->getGroups(), $this->user_groups);
					// filter the categories that the user is allowed to see and browse
					if (!empty($is_intersected))
					{
						$this->menu .= '<dd class="col-sm-'.$grid.' col-md-'.$grid.'">';
						$this->menu .= '<span class="s_title_block"><a href="'.$category_link.'">'.$category_child->name.'</a></span>';

						if (count($children_two))
						{
							foreach ($children_two as $childtwo)
							{
								$category_childtwo = new Category((int)$childtwo['id_category'], (int)$id_lang);

								if ($category_childtwo->level_depth > 1)
									$category_linktwo = $category_childtwo->getLink();
								else
									$category_linktwo = $this->context->link->getPageLink('index');

								if (is_null($category_childtwo->id))
									return;

								$is_intersected = array_intersect($category_childtwo->getGroups(), $this->user_groups);
								// filter the categories that the user is allowed to see and browse
								if (!empty($is_intersected))
								{
									$this->menu .= '<p>';
									$this->menu .= '<a href="'.$category_linktwo.'">'.$category_childtwo->name.'</a>';
									$this->menu .= '</p>';
								}
							}
						}

						$this->menu .= '</dd>';
					}
				}
			}
		}
	}

	private function getSingleCategory($id_category, $id_lang = false, $id_shop = false)
	{
		$id_shop = $id_shop;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category->level_depth > 1)
			$category_link = $category->getLink();
		else
			$category_link = $this->context->link->getPageLink('index');

		if (is_null($category->id))
			return;

		$grid = $this->mod_value[11];

		$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
		// filter the categories that the user is allowed to see and browse
		if (!empty($is_intersected))
		{
			$type = 'medium';
			$this->menu .= '<dd class="col-sm-'.$grid.' col-md-'.$grid.'">';
			$this->menu .= '<a href="'.htmlentities($category_link).'" class="product_image">
				<img class="img-responsive" src="'._THEME_CAT_DIR_.$id_category.'-'.$type.'_default.jpg" /></a>';
			$this->menu .= '<h5 class="s_title_block"><a href="'.htmlentities($category_link).'">'.$category->name.'</a></h5>';
			$this->menu .= '<div class="product_desc">'.strip_tags($category->description).'</div>';
			$this->menu .= '</dd>';
		}
	}

	private function makeCategoryMenuItems($enable_menu)
	{
		$id_lang = (int)$this->context->language->id;
		//$id_shop = (int)Shop::getContextShopID();
		$menu_items = explode(',', $this->mod_value[26]);

		if (count($menu_items) > 0)
		{
			foreach ($menu_items as $item)
			{
				$con_grid = $this->mod_value[0];
				$adv_grid = $this->mod_value[1];

				$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
				$category = new Category((int)$item, (int)$id_lang);

				if ($category->level_depth > 1)
					$category_link = $category->getLink();
				else
					$category_link = $this->context->link->getPageLink('index');

				if (is_null($category->id))
					continue;

				$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
				if (!empty($is_intersected))
				{
					$this->menu .= '<li class="nav_li cat">';
					$this->menu .= '<a class="nav_a roll" href="'.Tools::HtmlEntitiesUTF8($category_link).'" title="">
										<span data-title="'.$category->name.'">'.$category->name.'</span></a>';

					if ($enable_menu == 'yes')
					{
						$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';
						$this->menu .= '<dl class="pop_adver col-sm-'.$adv_grid.' col-md-'.$adv_grid.'">';
						$this->getCategory((int)$item);
						$this->menu .= '</dl>';

						$this->menu .= '<dl class="pop_content products_block col-sm-'.$con_grid.' col-md-'.$con_grid.'">';

						if ((int)$category->level_depth == 2)
						{
							$files = scandir(_PS_CAT_IMG_DIR_);
							if (count($files) > 0)
							{
								foreach ($files as $file)
								{
									$cats = explode('-', $file);
									if ($cats[0] == $category->id && preg_match('/'.$category->id.'-([0-9])?_thumb.jpg/i', $file) === 1)
									//if (preg_match('/'.$category->id.'-([0-9])?_thumb.jpg/i', $file) === 1)
										$this->menu .= '<dd class="col-sm-6 col-md-6"><img src="'.$this->context->link->getMediaLink(_THEME_CAT_DIR_.$file)
										.'" alt="'.Tools::SafeOutput($category->name).'" title="'
										.Tools::SafeOutput($category->name).'" class="img-responsive" /></dd>';
								}
							}
						}

						$this->menu .= '</dl>';
						$this->menu .= '</div>';
					}

					$this->menu .= '</li>'.PHP_EOL;
				}
			}
		}
	}

	private function makeCategoryMenu()
	{
		if (($this->mod_value[41] == 'no' || $this->mod_value[41] == '') && $this->mod_value[8] == '')
			$this->menu .= PHP_EOL;
		else
		{
			$this->menu .= '<li class="nav_li cat catall">';
			$this->menu .= '<a class="nav_a roll" href="javascript:void(0)"><span data-title="Categories">Categories</span></a>';
			$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';

			if ($this->mod_value[41] <> 'no')
			{
				$adv_grid = $this->mod_value[1];
				$this->menu .= '<dl class="pop_adver col-sm-'.$adv_grid.' col-md-'.$adv_grid.'">';
				$this->getCategory((int)Configuration::get('PS_HOME_CATEGORY'));
				$this->menu .= '</dl>';
			}

			if ($this->mod_value[8] <> '')
			{
				$menu_items = explode(',', $this->mod_value[8]);
				$con_grid = $this->mod_value[0];
				$this->menu .= '<dl class="pop_content products_block col-sm-'.$con_grid.' col-md-'.$con_grid.'">';
				foreach ($menu_items as $item_man)
					$this->getSingleCategory((int)$item_man);
				$this->menu .= '</dl>';
			}

			$this->menu .= '</div>';
			$this->menu .= '</li>'.PHP_EOL;
		}
	}

	private function makeProductMenu()
	{
		if ($this->mod_value[6] <> '' || $this->mod_value[14] <> '')
		{
			$this->menu .= '<li class="nav_li prd">';
			$this->menu .= '<a class="nav_a roll" href="javascript:void(0)"><span data-title="Products">Products</span></a>';
			$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';

			if ($this->mod_value[6] <> '')
			{
				$id_lang = (int)$this->context->language->id;
				$i = 0;
				$con_grid = $this->mod_value[2];
				$pitem = $this->mod_value[16];
				$grid = $this->mod_value[17];

				$this->menu .= '<dl class="pop_content products_block col-sm-'.$con_grid.' col-md-'.$con_grid.'">';

				$menu_items = explode(',', $this->mod_value[6]);
				foreach ($menu_items as $item_prd)
				{
					$product = new Product((int)$item_prd, true, (int)$id_lang);
					if (!is_null($product->id))
					{
						$selected = ($i % $pitem == 0) ? 'first_item' : '';
						$i++;
						$this->menu .= '<dd class="col-sm-'.$grid.' col-md-'.$grid.' '.$selected.'">';
						$image = Image::getImages($id_lang, $product->id);
						$product->id_image = $image[0]['id_image'];
						$typea = 'large';
						$typeb = 'default';
						$type = $typea.'_'.$typeb;
						$imgurl = str_replace('http://', Tools::getShopProtocol(),
									Context::getContext()->link->getImageLink($product->link_rewrite, $product->id_image, $type));
						$this->menu .= '<a href="'.$product->getLink().'" class="product_image">';
						$this->menu .= '<img class="img-responsive" src="'.$imgurl.'" /></a>';
						$this->menu .= '<h5 class="s_title_block"><a href="'.$product->getLink().'">'.$product->name.'</a></h5>';
						$this->menu .= '<div class="product_desc">'.strip_tags($product->description_short).'</div>';
						$this->menu .= '</dd>'.PHP_EOL;
					}
				}
				$this->menu .= '</dl>';
			}

			if ($this->mod_value[14] <> '')
			{
				$adv_grid = $this->mod_value[3];
				$this->menu .= '<dl class="pop_adver col-sm-'.$adv_grid.' col-md-'.$adv_grid.'">';

				//$this->displayFront('prd');

				$imgs = explode(',', $this->mod_value[14]);
				$baseurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
				$imgurl = $baseurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';
				$count_imgs = count($imgs);
				for ($j = 0; $j < $count_imgs; $j++)
				{
					$this->menu .= '<dd class="col-sm-'.(12 / $count_imgs).' col-md-'.(12 / $count_imgs).'">';

					$lnk = explode(',', $this->mod_value[15]);
					if (isset($lnk[$j]))
						$this->menu .= '<a href="'.$lnk[$j].'">';
					if (strstr($imgs[$j], 'http://') <> '')
						$this->menu .= '<img class="img-responsive" src="'.$imgs[$j].'" />';
					else
						$this->menu .= '<img class="img-responsive" src="'.$imgurl.$imgs[$j].'" />';
					if (isset($lnk[$j]))
						$this->menu .= '</a>';

					$this->menu .= '</dd>';
				}

				$this->menu .= '</dl>';
			}

			$this->menu .= '</div>';
			$this->menu .= '</li>'.PHP_EOL;
		}
	}

	private function makeBrandMenu()
	{
		if (($this->mod_value[42] == 'no' || $this->mod_value[42] == '') && $this->mod_value[7] == '')
			$this->menu .= PHP_EOL;
		else
		{
			$this->menu .= '<li class="nav_li man">';
			$this->menu .= '<a class="nav_a roll" href="javascript:void(0)"><span data-title="Brands">Brands</span></a>';
			$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';

			if ($this->mod_value[7] <> '')
			{
				$con_grid = $this->mod_value[4];
				$pitem = $this->mod_value[18];
				$grid = $this->mod_value[19];

				$this->menu .= '<dl class="pop_content products_block col-sm-'.$con_grid.' col-md-'.$con_grid.'">';

				$i = 0;
				$id_lang = (int)$this->context->language->id;
				$menu_items = explode(',', $this->mod_value[7]);

				foreach ($menu_items as $item_man)
				{
					$manufacturer = new Manufacturer((int)$item_man, (int)$id_lang);
					if (!is_null($manufacturer->id))
					{
						if ((int)Configuration::get('PS_REWRITING_SETTINGS'))
							$manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
						else
							$manufacturer->link_rewrite = 0;
						$link = new Link;

						$selected = ($i % $pitem == 0) ? 'first_item' : '';
						$i++;
						$this->menu .= '<dd class="col-sm-'.$grid.' col-md-'.$grid.' '.$selected.'">';
						$this->menu .= '<p><a href="'.$link->getManufacturerLink((int)$item_man, $manufacturer->link_rewrite).'" class="product_image">
											<img class="img-responsive" src="'._THEME_MANU_DIR_.$manufacturer->id_manufacturer.'.jpg" /></a></p>';
						$this->menu .= '<h5 class="s_title_block"><a href="'.$link->getManufacturerLink((int)$item_man, $manufacturer->link_rewrite).'">'.
											$manufacturer->name.'</a></h5>';
						$this->menu .= '<div class="product_desc">'.$manufacturer->short_description.'</div>';
						$this->menu .= '</dd>'.PHP_EOL;
					}
				}

				$this->menu .= '</dl>';
			}

			if ($this->mod_value[42] <> 'no')
			{
				$adv_grid = $this->mod_value[5];
				$pitem = $this->mod_value[20];
				$grid = $this->mod_value[21];

				$this->menu .= '<dl class="pop_adver col-sm-'.$adv_grid.' col-md-'.$adv_grid.'"><dd>';

				$i = 0;
				$manufacturers = Manufacturer::getManufacturers();
				foreach ($manufacturers as $manufacturer)
				{
					$link = new Link;
					$selected = ($i % $pitem == 0) ? 'first_item' : '';
					$i++;
					$this->menu .= '<p class="col-md-'.$grid.' '.$selected.'"><a href="'.
									htmlentities($link->getManufacturerLink((int)$manufacturer['id_manufacturer'], $manufacturer['link_rewrite'])).
									'">'.$manufacturer['name'].'</a></p>';
				}

				$this->menu .= '</dd></dl>';
			}

			$this->menu .= '</div>';
			$this->menu .= '</li>'.PHP_EOL;
		}
	}

	private function makeNewsMenu($params)
	{
		if (($this->mod_value[49] == '' || $this->mod_value[49] == '0') && ($this->mod_value[50] == '' || $this->mod_value[50] == '0'))
			$this->menu .= PHP_EOL;
		else
		{
			$this->menu .= '<li class="nav_li news">';
			$this->menu .= '<a class="nav_a roll" href="javascript:void(0)"><span data-title="News">News</span></a>';
			$this->menu .= '<div class="nav_pop col-md-12" style="visibility: hidden; height: 0px;">';

			if ($this->mod_value[49] <> '' && $this->mod_value[49] <> '0')
			{
				$news_grid = $this->mod_value[49];
				$this->menu .= '<dl class="pop_content products_block col-sm-'.$news_grid.' col-md-'.$news_grid.'"><dd>';

				if ($this->mod_value[51] <> '')
				{
					$baseurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
					$imgurl = $baseurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';
					$image = $this->mod_value[51];
					$this->menu .= '<div class="image">';
					if (strstr($image, 'http://') <> '')
						$this->menu .= '<img class="img-responsive" src="'.$image.'" />';
					else
						$this->menu .= '<img class="img-responsive" src="'.$imgurl.$image.'" />';
					$this->menu .= '</div>';
				}

				$this->menu .= '<div class="content">';

				$this->menu .= '<h5 class="s_title_block">'.$this->language($params, 52).'</h5>';

				for ($i = 0; $i < 3; $i++)
				{
					$cid = 53 + $i * 2;
					$subtitle = $this->language($params, $cid);
					$this->menu .= '<p>'.$subtitle.'</p>';

					$text = $this->language($params, $cid + 1);
					$texts = explode("\n", $text);
					foreach ($texts as $text)
						$this->menu .= '<div class="product_desc">'.$text.'</div>';
				}

				$this->menu .= '</div>';

				$this->menu .= '</dd></dl>';
			}

			if ($this->mod_value[50] <> '' && $this->mod_value[50] <> '0')
			{
				$cms_grid = $this->mod_value[50];
				$this->menu .= '<dl class="pop_adver col-sm-'.$cms_grid.' col-md-'.$cms_grid.'"><dd>';

				$this->menu .= '<h5 class="s_title_block">'.$this->language($params, 59).'</h5></dd><dd>';

				$i = 0;
				$id_lang = (int)$this->context->language->id;
				$menu_items = explode(',', $this->mod_value[9]); // cms id

				foreach ($menu_items as $item)
				{
					$cms = CMS::getLinks((int)$id_lang, array($item));
					if (count($cms))
						$this->menu .= '<p><a href="'.htmlentities($cms[0]['link']).'">'.$cms[0]['meta_title'].'</a></p>';
				}

				$this->menu .= '</dd></dl>';
			}

			$this->menu .= '</div>';
			$this->menu .= '</li>'.PHP_EOL;
		}
	}

	private function makeCMSMenuItems()
	{
		$id_lang = (int)$this->context->language->id;
		$menu_items = explode(',', $this->mod_value[9]);

		if (count($menu_items) > 0)
		{
			foreach ($menu_items as $item)
			{
				$cms = CMS::getLinks((int)$id_lang, array($item));
				if (count($cms))
					$this->menu .= '<li><a href="'.htmlentities($cms[0]['link']).'" class="roll">
						<span data-title="'.$cms[0]['meta_title'].'">'.$cms[0]['meta_title'].'</span></a></li>'.PHP_EOL;
			}
		}
	}

	private function makeCustomMenuItems($params)
	{
		if ($this->mod_value[23] <> '')
		{
			$custom = 23;
			$all_custom = $this->language($params, $custom);
			$menu_items = explode('^', $all_custom);

			if (count($menu_items) > 0)
			{
				$links = explode('|', $this->mod_value[24]);
				$lid = 0;
				foreach ($menu_items as $item)
					$this->menu .= '<li><a href="'.htmlentities($links[$lid++]).'" class=" roll"><span data-title="'.$item.'">'.$item.'</span></a></li>'.PHP_EOL;
			}
		}
	}

	public function hookDisplayTop($params)
	{
		if ($this->mod_value[47] == 'top')
		{
			$this->hookDisplay($params);
			$this->smarty->assign('pos', 'top');
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}

	public function hookDisplayTopContent($params)
	{
		if ($this->mod_value[47] <> 'top')
		{
			$this->hookDisplay($params);
			$this->smarty->assign('pos', 'topcontent');
			return $this->display(__FILE__, $this->name.'.tpl');
		}
	}

	public function hookDisplay($params)
	{
		$this->user_groups = ($this->context->customer->isLogged() ?
			$this->context->customer->getGroups() : array(Configuration::get('PS_UNIDENTIFIED_GROUP')));

		$enable_menu = $this->mod_value[22]; //test roll

		if ($enable_menu == 'yes')
			$this->makeCategoryMenu();

		$this->makeCategoryMenuItems($enable_menu);

		if ($enable_menu == 'yes')
		{
			$this->makeMenu();
			$this->makeProductMenu();
			$this->makeBrandMenu();
			$this->makeNewsMenu($params);
		}
		//$this->makeCMSMenuItems();
		$this->makeCustomMenuItems($params);

		$this->smarty->assign('MENU', $this->menu);

		$showhome = $this->mod_value[27];
		$this->smarty->assign('showhome', $showhome);

		$showsearch = $this->mod_value[63];
		$this->smarty->assign('showsearch', $showsearch);

		$this->menumobile .= '<div class="mobile-title">';
		$this->menumobile .= '<div class="cat-title title-item"><i class="icon-reorder"></i></div>';
		$id_lang = (int)$this->context->language->id;
		$menu_items = explode(',', $this->mod_value[60]);
		if (count($menu_items) > 0)
		{
			foreach ($menu_items as $item)
			{
				$cms = CMS::getLinks((int)$id_lang, array($item));
				if (count($cms))
					$this->menumobile .= '<div class="title-item"><a href="'.htmlentities($cms[0]['link']).'">'.$cms[0]['meta_title'].'</a></div>';
			}
		}

		$custom = 61;
		$all_custom = $this->language($params, $custom);
		$menu_items = explode('^', $all_custom);

		if (count($menu_items) > 0)
		{
			$links = explode('|', $this->mod_value[62]);
			$lid = 0;
			foreach ($menu_items as $item)
				$this->menumobile .= '<div class="title-item"><a href="'.htmlentities($links[$lid++]).'">'.$item.'</a></div>';
		}
		$this->menumobile .= '</div>';

		$this->menumobile .= '<ul class="mb-menu clearfix menu-content" style="display: none;">';
		$this->getCategoryMobile((int)Configuration::get('PS_HOME_CATEGORY'));
		$this->menumobile .= '</ul>';

		$this->smarty->assign('MENU_MOBILE', $this->menumobile );
	}

	public function hookHeader()
	{
		$this->context->controller->addJS(($this->_path).'views/js/uhutopmenu.js');
		//$this->context->controller->addJS(_THEME_JS_DIR_.'tools/treeManagement.js');
	}

	public function displayFront($type)
	{
		$num = 0;
		//$grid = 12;
		$adv = array();
		$lnk = array();
		if ($type == 'prd')
		{
			$num = 1;
			$adv[0] = $this->mod_value[14];
			$lnk[0] = $this->mod_value[15];
		}

		//if ($type == 'cms')
		//	$grid = 12;
		//if ($type == 'lnk')
		//	$grid = 4;

		$baseurl = $this->context->link->protocol_content.Tools::getMediaServer($this->name)._MODULE_DIR_;
		$imgurl = $baseurl.'uhuthemesetting/views/img/'.$this->mod_name.'/';

		for ($i = 0; $i < $num; $i++)
		{
			$this->menu .= '<dd>';
			if ($lnk[$i])
				$this->menu .= '<a href="'.$lnk[$i].'">';
			if (strstr($adv[$i], 'http://') <> '')
				$this->menu .= '<img class="img-responsive" src="'.$adv[$i].'" />';
			else
				$this->menu .= '<img class="img-responsive" src="'.$imgurl.$adv[$i].'" />';
			if ($lnk[$i])
				$this->menu .= '</a>';
			$this->menu .= '</dd>';
		}
	}

	private function getCategoryMobile($id_category, $id_lang = false, $id_shop = false)
	{
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category->level_depth > 1)
			$category_link = $category->getLink();
		else
			$category_link = $this->context->link->getPageLink('index');

		if (is_null($category->id))
			return;

		$children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);

		$is_intersected = array_intersect($category->getGroups(), $this->user_groups);
		// filter the categories that the user is allowed to see and browse
		if (!empty($is_intersected))
		{
			if ($category->level_depth > 1)
			{
			$this->menumobile .= '<li class="s_title_'.$category->level_depth.'">';
			$this->menumobile .= '<a href="'.$category_link.'">'.$category->name.'</a>';
			}

			if (count($children))
			{
			if ($category->level_depth > 1)
			{
				$this->menumobile .= '<ul';
				if ($category->level_depth < 3)
					$this->menumobile .= ' style="display: none;"';
				$this->menumobile .= '>';
			}

				foreach ($children as $child)
					$this->getCategoryMobile((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop']);

			if ($category->level_depth > 1)
				$this->menumobile .= '</ul>';
			}
			if ($category->level_depth > 1)
				$this->menumobile .= '</li>';
		}
	}

	private function getCategoryRoll($id_category, $roll, $id_lang = false, $id_shop = false)
	{
		$id_shop = $id_shop;
		$id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
		$category = new Category((int)$id_category, (int)$id_lang);

		if ($category->level_depth > 1)
			$category_link = $category->getLink();
		else
			$category_link = $this->context->link->getPageLink('index');

		if (is_null($category->id))
			return;

		$this->menuroll .= '<li>';
		$this->menuroll .= '<a href="'.$category_link.'" class=" '.$roll.'"><span data-title="'.$category->name.'">'.$category->name.'</span></a>';
		$this->menuroll .= '</li>';
	}

	public function language($params, $id)
	{
		$lang = array();
		if ($this->mod_value[$id] == '')
			return;
		$lang_iso = Language::getIsoById($params['cookie']->id_lang);
		$values = explode('|', $this->mod_value[$id]);
		foreach ($values as $value)
		{
			$langs = explode('¤', $value);
			if (isset($langs[1]) && $langs[1])
				$lang[$langs[1]] = $langs[0];
		}
		if ($lang[$lang_iso] <> '')
			return $lang[$lang_iso];
		else
			return $lang['en'];
	}
}
