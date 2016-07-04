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

if (!defined('_PS_VERSION_'))
	exit;

class Uhuthemefont extends Module
{
	public function __construct()
	{
		$this->name = 'uhuthemefont';
		$this->tab = 'others';
		$this->version = '1.2.0';
		$this->bootstrap = true;
		$this->author = 'uhuPage';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = 'uhu Theme configurator - Font';
		$this->description = $this->l('Change the fonts of your theme.');
	}

	protected function init()
	{
		$this->datadir = '/home/www/design/data_v20/';
		$this->fontfamily_list = array('','Serif','Sans-serif','Monospace','Cursive','Fantasy','','Arial','Tahoma','Verdana','Trebuchet MS',
										'Lucida Sans Unicode','Georgia','Times New Roman','','Abel','Abril Fatface','Aclonica','Acme','Actor',
										'Adamina','Advent Pro','Aguafina Script','Akronim','Aladin','Aldrich','Alegreya','Alegreya SC','Alex Brush',
										'Alfa Slab One','Alice','Alike','Alike Angular','Allan','Allerta','Allerta Stencil','Allura','Almendra',
										'Almendra SC','Amarante','Amaranth','Amatic SC','Amethysta','Andada','Andika','Angkor',
										'Annie Use Your Telescope','Anonymous Pro','Antic','Antic Didone','Antic Slab','Anton','Arapey','Arbutus',
										'Arbutus Slab','Architects Daughter','Archivo Black','Archivo Narrow','Arimo','Arizonia','Armata','Artifika',
										'Arvo','Asap','Asset','Astloch','Asul','Atomic Age','Aubrey','Audiowide','Autour One','Average',
										'Averia Gruesa Libre','Averia Libre','Averia Sans Libre','Averia Serif Libre','Bad Script','Balthazar',
										'Bangers','Basic','Battambang','Baumans','Bayon','Belgrano','Belleza','BenchNine','Bentham',
										'Berkshire Swash','Bevan','Bigshot One','Bilbo','Bilbo Swash Caps','Bitter','Black Ops One','Bokor','Bonbon',
										'Boogaloo','Bowlby One','Bowlby One SC','Brawler','Bree Serif','Bubblegum Sans','Bubbler One','Buda',
										'Buenard','Butcherman','Butterfly Kids','Cabin','Cabin Condensed','Cabin Sketch','Caesar Dressing',
										'Cagliostro','Calligraffitti','Cambo','Candal','Cantarell','Cantata One','Cantora One','Capriola','Cardo',
										'Carme','Carrois Gothic','Carrois Gothic SC','Carter One','Caudex','Cedarville Cursive','Ceviche One',
										'Changa One','Chango','Chau Philomene One','Chela One','Chelsea Market','Chenla','Cherry Cream Soda',
										'Cherry Swash','Chewy','Chicle','Chivo','Cinzel','Cinzel Decorative','Coda','Coda Caption','Codystar',
										'Combo','Comfortaa','Coming Soon','Concert One','Condiment','Content','Contrail One','Convergence','Cookie',
										'Copse','Corben','Courgette','Cousine','Coustard','Covered By Your Grace','Crafty Girls','Creepster',
										'Crete Round','Crimson Text','Crushed','Cuprum','Cutive','Damion','Dancing Script','Dangrek',
										'Dawning of a New Day','Days One','Delius','Delius Swash Caps','Delius Unicase','Della Respira','Devonshire',
										'Didact Gothic','Diplomata','Diplomata SC','Doppio One','Dorsa','Dosis','Dr Sugiyama','Droid Sans',
										'Droid Sans Mono','Droid Serif','Duru Sans','Dynalight','EB Garamond','Eagle Lake','Eater','Economica',
										'Electrolize','Emblema One','Emilys Candy','Engagement','Enriqueta','Erica One','Esteban','Euphoria Script',
										'Ewert','Exo','Expletus Sans','Fanwood Text','Fascinate','Fascinate Inline','Fasthand','Federant','Federo',
										'Felipa','Fenix','Finger Paint','Fjord One','Flamenco','Flavors','Fondamento','Fontdiner Swanky','Forum',
										'Francois One','Fredericka the Great','Fredoka One','Freehand','Fresca','Frijole','Fugaz One','GFS Didot',
										'GFS Neohellenic','Galdeano','Galindo','Gentium Basic','Gentium Book Basic','Geo','Geostar','Geostar Fill',
										'Germania One','Give You Glory','Glass Antiqua','Glegoo','Gloria Hallelujah','Goblin One','Gochi Hand',
										'Gorditas','Goudy Bookletter 1911','Graduate','Gravitas One','Great Vibes','Griffy','Gruppo','Gudea',
										'Habibi','Hammersmith One','Handlee','Hanuman','Happy Monkey','Headland One','Henny Penny',
										'Herr Von Muellerhoff','Holtwood One SC','Homemade Apple','Homenaje','IM Fell DW Pica','IM Fell DW Pica SC',
										'IM Fell Double Pica','IM Fell Double Pica SC','IM Fell English','IM Fell English SC','IM Fell French Canon',
										'IM Fell French Canon SC','IM Fell Great Primer','IM Fell Great Primer SC','Iceberg','Iceland','Imprima',
										'Inconsolata','Inder','Indie Flower','Inika','Irish Grover','Istok Web','Italiana','Italianno',
										'Jacques Francois','Jacques Francois Shadow','Jim Nightshade','Jockey One','Jolly Lodger','Josefin Sans',
										'Josefin Slab','Judson','Julee','Julius Sans One','Junge','Jura','Just Another Hand',
										'Just Me Again Down Here','Kameron','Karla','Kaushan Script','Kelly Slab','Kenia','Khmer','Knewave',
										'Kotta One','Koulen','Kranky','Kreon','Kristi','Krona One','La Belle Aurore','Lancelot','Lato',
										'League Script','Leckerli One','Ledger','Lekton','Lemon','Life Savers','Lilita One','Limelight',
										'Linden Hill','Lobster','Lobster Two','Londrina Outline','Londrina Shadow','Londrina Sketch',
										'Londrina Solid','Lora','Love Ya Like A Sister','Loved by the King','Lovers Quarrel','Luckiest Guy',
										'Lusitana','Lustria','Macondo','Macondo Swash Caps','Magra','Maiden Orange','Mako','Marcellus',
										'Marcellus SC','Marck Script','Marko One','Marmelad','Marvel','Mate','Mate SC','Maven Pro','McLaren',
										'Meddon','MedievalSharp','Medula One','Megrim','Meie Script','Merienda One','Merriweather','Metal',
										'Metal Mania','Metamorphous','Metrophobic','Michroma','Miltonian','Miltonian Tattoo','Miniver',
										'Miss Fajardose','Modern Antiqua','Molengo','Molle','Monofett','Monoton','Monsieur La Doulaise','Montaga',
										'Montez','Montserrat','Montserrat Alternates','Montserrat Subrayada','Moul','Moulpali',
										'Mountains of Christmas','Mr Bedfort','Mr Dafoe','Mr De Haviland','Mrs Saint Delafield','Mrs Sheppards',
										'Muli','Mystery Quest','Neucha','Neuton','News Cycle','Niconne','Nixie One','Nobile','Nokora','Norican',
										'Nosifer','Nothing You Could Do','Noticia Text','Nova Cut','Nova Flat','Nova Mono','Nova Oval','Nova Round',
										'Nova Script','Nova Slim','Nova Square','Numans','Nunito','Odor Mean Chey','Old Standard TT','Oldenburg',
										'Oleo Script','Open Sans','Open Sans Condensed','Oranienbaum','Orbitron','Oregano','Orienta',
										'Original Surfer','Oswald','Over the Rainbow','Overlock','Overlock SC','Ovo','Oxygen','Oxygen Mono',
										'PT Mono','PT Sans','PT Sans Caption','PT Sans Narrow','PT Serif','PT Serif Caption','Pacifico','Parisienne',
										'Passero One','Passion One','Patrick Hand','Patua One','Paytone One','Peralta','Permanent Marker',
										'Petit Formal Script','Petrona','Philosopher','Piedra','Pinyon Script','Plaster','Play','Playball',
										'Playfair Display','Podkova','Poiret One','Poller One','Poly','Pompiere','Pontano Sans','Port Lligat Sans',
										'Port Lligat Slab','Prata','Preahvihear','Press Start 2P','Princess Sofia','Prociono','Prosto One','Puritan',
										'Quando','Quantico','Quattrocento','Quattrocento Sans','Questrial','Quicksand','Qwigley','Racing Sans One',
										'Radley','Raleway','Raleway Dots','Rammetto One','Ranchers','Rancho','Rationale','Redressed','Reenie Beanie',
										'Revalia','Ribeye','Ribeye Marrow','Righteous','Rochester','Rock Salt','Rokkitt','Romanesco','Ropa Sans',
										'Rosario','Rosarivo','Rouge Script','Ruda','Ruge Boogie','Ruluko','Ruslan Display','Russo One','Ruthie',
										'Rye','Sail','Salsa','Sancreek','Sansita One','Sarina','Satisfy','Scada','Schoolbell','Seaweed Script',
										'Sevillana','Seymour One','Shadows Into Light','Shadows Into Light Two','Shanti','Share','Shojumaru',
										'Short Stack','Siemreap','Sigmar One','Signika','Signika Negative','Simonetta','Sirin Stencil','Six Caps',
										'Skranji','Slackey','Smokum','Smythe','Sniglet','Snippet','Sofadi One','Sofia','Sonsie One',
										'Sorts Mill Goudy','Source Code Pro','Source Sans Pro','Special Elite','Spicy Rice','Spinnaker','Spirax',
										'Squada One','Stalinist One','Stardos Stencil','Stint Ultra Condensed','Stint Ultra Expanded','Stoke',
										'Sue Ellen Francisco','Sunshiney','Supermercado One','Suwannaphum','Swanky and Moo Moo','Syncopate',
										'Tangerine','Taprom','Telex','Tenor Sans','The Girl Next Door','Tienne','Tinos','Titan One','Titillium Web',
										'Trade Winds','Trocchi','Trochut','Trykker','Tulpen One','Ubuntu','Ubuntu Condensed','Ubuntu Mono','Ultra',
										'Uncial Antiqua','Underdog','UnifrakturCook','UnifrakturMaguntia','Unkempt','Unlock','Unna','VT323',
										'Varela','Varela Round','Vast Shadow','Vibur','Vidaloka','Viga','Voces','Volkhov','Vollkorn','Voltaire',
										'Waiting for the Sunrise','Wallpoet','Walter Turncoat','Warnes','Wellfleet','Wire One','Yanone Kaffeesatz',
										'Yellowtail','Yeseva One','Yesteryear','Zeyada');
		$this->fontsize_list = array('','1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','11px','12px','13px','14px','15px','16px',
									'17px','18px','19px','20px','21px','22px','23px','24px','25px','26px','27px','28px','29px','30px','31px','32px',
									'33px','34px','35px','36px','37px','38px','39px','40px','41px','42px','43px','44px','45px','46px','47px','48px',
									'49px','50px','51px','52px','53px','54px','55px','56px','57px','58px','59px','60px','61px','62px','63px','64px',
									'65px','66px','67px','68px','69px','70px','71px','72px','73px','74px','75px','76px','77px','78px','79px','80px',
									'81px','82px','83px','84px','85px','86px','87px','88px','89px','90px','91px','92px','93px','94px','95px','96px',
									'97px','98px','99px','100px','','0.1vw','0.2vw','0.3vw','0.4vw','0.5vw','0.6vw','0.7vw','0.8vw','0.9vw','1.0vw',
									'1.1vw','1.2vw','1.3vw','1.4vw','1.5vw','1.6vw','1.7vw','1.8vw','1.9vw','2.0vw','2.1vw','2.2vw','2.3vw','2.4vw',
									'2.5vw','2.6vw','2.7vw','2.8vw','2.9vw','3.0vw','3.1vw','3.2vw','3.3vw','3.4vw','3.5vw','3.6vw','3.7vw','3.8vw',
									'3.9vw','4.0vw');
		$this->fontweight_list = array('','normal','bold','bolder','lighter','100','200','300','400','500','600','700','800','900');
		$this->fontstyle_list = array('','normal','italic','oblique');
		$this->textalign_list = array('','left','right','center','justify');
		$this->texttransform_list = array('','none','uppercase','lowercase','capitalize');
		$this->textindent_list = array('','1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','11px','12px','13px','14px','15px','16px',
									'17px','18px','19px','20px','21px','22px','23px','24px','25px','26px','27px','28px','29px','30px','31px','32px',
									'33px','34px','35px','36px','37px','38px','39px','40px','41px','42px','43px','44px','45px','46px','47px','48px',
									'49px','50px');
		$this->lineheight_list = array('','1px','2px','3px','4px','5px','6px','7px','8px','9px','10px','11px','12px','13px','14px','15px','16px',
									'17px','18px','19px','20px','21px','22px','23px','24px','25px','26px','27px','28px','29px','30px','31px','32px',
									'33px','34px','35px','36px','37px','38px','39px','40px','41px','42px','43px','44px','45px','46px','47px','48px',
									'49px','50px','51px','52px','53px','54px','55px','56px','57px','58px','59px','60px','61px','62px','63px','64px',
									'65px','66px','67px','68px','69px','70px','71px','72px','73px','74px','75px','76px','77px','78px','79px','80px',
									'81px','82px','83px','84px','85px','86px','87px','88px','89px','90px','91px','92px','93px','94px','95px','96px',
									'97px','98px','99px','100px','110px','120px','130px','140px','150px','160px','170px','180px','190px','200px',
									'210px','220px','230px','240px','250px','260px','270px','280px','290px','300px','310px','320px','330px','340px',
									'350px','360px','370px','380px','390px','400px');

		$this->changelist = array();
		$this->fontstyles = array();

		//$this->list_total = $this->getFontTotal() / 8;
		$results = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'uhufonts` ORDER BY `ps_uhufonts`.`modorder` ASC');

		//for ($lid = 0; $lid < $this->list_total; $lid++)
		foreach ($results as $result)
		{
			if ($result['id_item'] % 8 == 1)
			{
				$temp = $result['id_item'] / 8;
				$lid = (int)$temp;
				$this->changelist['name'][$lid] = 'font'.$lid;
				$this->changelist['title'][$lid] = $this->getFontModTitle($lid * 8);
				$this->changelist['active'][$lid] = $this->getFontActive($lid * 8);

				$listname = $this->changelist['name'][$lid];
				$this->fontstyles[$listname]['item'] = array($lid * 8, $lid * 8 + 1, $lid * 8 + 2, $lid * 8 + 3,
															$lid * 8 + 4, $lid * 8 + 5, $lid * 8 + 6, $lid * 8 + 7);
				foreach ($this->fontstyles[$listname]['item'] as $item)
				{
					$this->fontstyles[$listname]['modid'][$item] = $this->getFontModId($item);
					$this->fontstyles[$listname]['title'][$item] = $this->getFontTitle($item);
					$this->fontstyles[$listname]['type'][$item] = $this->getFontType($item);
					$this->fontstyles[$listname]['value'][$item] = $this->getFontValue($item);
					$this->fontstyles[$listname]['selector'][$item] = $this->getFontSelector($item);
				}
			}
		}
	}

	public function install()
	{
		if (!parent::install() ||
			!$this->installDB() ||
			!$this->registerHook('displayFooter') ||
			!$this->registerHook('displayBackOfficeHeader'))
			return false;

		$this->loadConfigFile();
		return true;
	}

	private function installDB()
	{
		return (
			Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'uhufonts`') &&
			Db::getInstance()->Execute('
			CREATE TABLE `'._DB_PREFIX_.'uhufonts` (
					`id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`title` VARCHAR(50),
					`display` VARCHAR(30),
					`selector` TEXT,
					`fonts` VARCHAR(100),
					`myfont` VARCHAR(100),
					`modid` VARCHAR(20),
					`modinfo` VARCHAR(200),
					`modorder` VARCHAR(10),
					`type` VARCHAR(10),
					`modtitle` VARCHAR(50),
					`active` VARCHAR(10),
					PRIMARY KEY (`id_item`)
			) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;')
		);

	}

	public function uninstall()
	{
		Configuration::deleteByName('uhu_fontsetting');

		if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'uhufonts`') || !parent::uninstall())
			return false;
		return true;
	}

	public function hookDisplayFooter()
	{
		$html = '';

		if (Tools::isSubmit('submitFontConfigurator'))
		{
			$this->postSave();
			$url = Tools::safeOutput(preg_replace('/&deleteFilterTemplate=[0-9]*&id_layered_filter=[0-9]*/', '', $_SERVER['REQUEST_URI']));
			Tools::redirect($url);
		}

		if (Configuration::get('uhu_font_front_panel') == 1 || Configuration::get('PS_UHU_LIVE_DEMO') == 1)
		{
			$this->init();
			$this->smarty->assign('theme_version', $this->version);

			$this->smarty->assign('fontfamily_list', $this->fontfamily_list);
			$this->smarty->assign('fontsize_list', $this->fontsize_list);
			$this->smarty->assign('fontweight_list', $this->fontweight_list);
			$this->smarty->assign('fontstyle_list', $this->fontstyle_list);
			$this->smarty->assign('textalign_list', $this->textalign_list);
			$this->smarty->assign('texttransform_list', $this->texttransform_list);
			$this->smarty->assign('textindent_list', $this->textindent_list);
			$this->smarty->assign('lineheight_list', $this->lineheight_list);

			$this->smarty->assign('changelist', $this->changelist);
			$this->smarty->assign('fontstyles', $this->fontstyles);
			$this->smarty->assign('livedemo', Configuration::get('PS_UHU_LIVE_DEMO'));

			$html .= $this->display(__FILE__, 'live_fonts.tpl');
		}

		return $html;
	}

	public function getContent()
	{
		$this->_html = '';

		$this->loadConfigFile();

		$this->postProcess();
		$this->displayForm();

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';

		if (Tools::isSubmit('submitCustomFont'))
			$this->postSave();

		if (Tools::isSubmit('submitConfigFont'))
		{
			$results = Db::getInstance()->executeS('SELECT DISTINCT type FROM `'._DB_PREFIX_.'uhufonts`');
			foreach ($results as $result)
			{
				if ($result['type'] <> '')
				{
					$type = $result['type'];
					$modid = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhufonts` WHERE type = \''.pSQL($type).'\'');
					if (Tools::getValue($modid) == 0)
						$active = 'hidden';
					else
						$active = '';
					$sql = 'UPDATE `'._DB_PREFIX_.'uhufonts` SET active = \''.pSQL($active).'\' WHERE type = \''.pSQL($type).'\'';
					Db::getInstance()->execute($sql);
				}
			}
		}

		if ($errors)
			echo $this->displayError($errors);
	}

	public function postSave()
	{
		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/theme_font.css', 'wb');
		else
			$fp = fopen(_PS_ROOT_DIR_.'/modules/uhuthemesetting/views/css/myfont.css', 'wb');

		$total = (int)Configuration::get('uhu_fontsetting');
		for ($i = 0; $i < $total; $i++)
		{
			$code = $this->postProcessStyleFont($i);
			fputs($fp, $code);
		}
		fclose($fp);

		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			$this->saveConfigFile();

		$this->updateGooglefont();
	}

	public function updateGooglefont()
	{
		$googlefont = '';
		$webfont_list = array('Serif','Sans-serif','Monospace','Cursive','Fantasy','','Arial','Tahoma','Verdana',
							'Trebuchet MS','Lucida Sans Unicode','Georgia','Times New Roman');

		//if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
		//{
			$fonts = Db::getInstance()->executeS('SELECT fonts FROM `'._DB_PREFIX_.'uhufonts` WHERE display = \'font-family\'');
			foreach ($fonts as $font)
				if ($font['fonts'] <> '' && !in_array($font['fonts'], $webfont_list) && strstr($googlefont, $font['fonts']) == '')
					$googlefont .= $font['fonts'].'|';
		//}
		//else
		//{
			$fonts = Db::getInstance()->executeS('SELECT myfont FROM `'._DB_PREFIX_.'uhufonts` WHERE display = \'font-family\'');
			foreach ($fonts as $font)
				if ($font['myfont'] <> '' && !in_array($font['myfont'], $webfont_list) && strstr($googlefont, $font['fonts']) == '')
					$googlefont .= $font['myfont'].'|';
		//}

		$googlefont = str_replace(' ', '+', $googlefont);
		Configuration::updateValue('uhu_googlefonts', trim($googlefont, '|'));
	}

	private function postProcessStyleFont($m_id)
	{
		$mid = $m_id + 1;
		$result = Db::getInstance()->getRow('
			SELECT modid, display, selector
			FROM `'._DB_PREFIX_.'uhufonts`
			WHERE id_shop = '.(int)$this->context->shop->id.' AND id_item = '.(int)$mid);

		$css_id = $result['modid'];
		$csstitle = $result['display'];
		$selectors = $result['selector'];
		$cssvalue = Tools::getValue($css_id);

		$code = '';
		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'uhufonts` SET fonts = \''.pSQL($cssvalue).'\' WHERE id_item = '.(int)$mid);
		else
			Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'uhufonts` SET myfont = \''.pSQL($cssvalue).'\' WHERE id_item = '.(int)$mid);

		if ($cssvalue <> '' && $csstitle <> '' && $selectors <> '')
			$code .= $selectors.' {'.$csstitle.':'.$cssvalue.";}\n";

		return $code;
	}

	private function getFontTotal()
	{
		$c_value = (int)Db::getInstance()->getValue('SELECT count(*) FROM '._DB_PREFIX_.'uhufonts');
		return $c_value;
	}

	private function getFontModOrder($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT id_item FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontModTitle($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT modtitle FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontModId($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT modid FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontTitle($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT title FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontType($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT display FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontValue($m_id)
	{
		$mid = $m_id + 1;
		if (Configuration::get('PS_UHU_DEVELOPER_MODE') == '1')
			$c_value = Db::getInstance()->getValue('SELECT fonts FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		else
			$c_value = Db::getInstance()->getValue('SELECT myfont FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontSelector($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT selector FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function getFontActive($m_id)
	{
		$mid = $m_id + 1;
		$c_value = Db::getInstance()->getValue('SELECT active FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);
		return $c_value;
	}

	private function updateActive($type, $active)
	{
		$sql = 'UPDATE `'._DB_PREFIX_.'uhufonts` SET active = \''.pSQL($active).'\' WHERE type = \''.pSQL($type).'\'';
		Db::getInstance()->execute($sql);
	}

	private function loadConfigFile()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$mod_total = fgets($fp);
			for ($i = 0; $i < $mod_total; $i++)
			{
				$temp1 = trim(fgets($fp));
				fgets($fp);
				$temp3 = trim(fgets($fp));
				fgets($fp);
				if ($temp1 == 'font')
				{
					$item_total = $temp3;
					Configuration::updateValue('uhu_fontsetting', $item_total);

					$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_font.txt', 'rb');
					if ($fp2)
					for ($j = 0; $j < $item_total; $j++)
					{
						$modorder = trim(fgets($fp2));
						$fonts = trim(fgets($fp2));
						$modid = trim(fgets($fp2));
						$modtitle = trim(fgets($fp2));
						$modinfo = trim(fgets($fp2));
						$hidden = trim(fgets($fp2));
						$display = trim(fgets($fp2));
						$title = trim(fgets($fp2));
						$selector = trim(fgets($fp2));

						$result = $this->installFontsetting($this->context->shop->id, $modorder, $modtitle, $title,
															$display, $selector, $fonts, $hidden, $modid, $modinfo);
						if (!$result)
						echo 'false<br>';
					}
					fclose($fp2);
				}
			}
			fclose($fp);
		}

		$active = '';
		$this->updateActive('type01', $active);
		$this->updateActive('type02', $active);
		$this->updateActive('type03', $active);
		$this->updateActive('type04', $active);
		$this->updateActive('type05', $active);
		$this->updateActive('type06', $active);
		$this->updateActive('type07', $active);
		$this->updateActive('type08', $active);
		$this->updateActive('type09', $active);
		$this->updateActive('type10', $active);
		$this->updateActive('type11', $active);

		$this->updateGooglefont();
	}

	private function saveConfigFile()
	{
		$fp = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/modlist.txt', 'rb');
		if ($fp)
		{
			$mod_total = fgets($fp);
			for ($i = 0; $i < $mod_total; $i++)
			{
				$temp1 = trim(fgets($fp));
				fgets($fp);
				$temp3 = trim(fgets($fp));
				fgets($fp);
				if ($temp1 == 'font')
				{
					$item_total = $temp3;
					//$fp2 = fopen($this->datadir.'modlist/config_mod_font.txt', 'wb');
					$fp2 = fopen(_PS_MODULE_DIR_.'uhuthemesetting/config/mod_font.txt', 'wb');
					for ($j = 0; $j < $item_total; $j++)
					{
						$mid = $j + 1;
						$result = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'uhufonts` WHERE id_item = '.(int)$mid);

						//$fonts = '';
						//$myfont = $result['myfont'];
						$fonts = $result['fonts'];
						$myfont = '';

						$modorder = $result['modorder'];
						$modid = $result['modid'];
						$modtitle = $result['modtitle'];
						$title = $result['title'];
						$display = $result['display'];
						$modinfo = $result['modinfo'];
						$selector = $result['selector'];

						fputs($fp2, $modorder);
						fputs($fp2, "\n");
						fputs($fp2, $fonts);
						fputs($fp2, "\n");
						fputs($fp2, $modid);
						fputs($fp2, "\n");
						fputs($fp2, $modtitle);
						fputs($fp2, "\n");
						fputs($fp2, $modinfo);
						fputs($fp2, "\n");
						fputs($fp2, $myfont);
						fputs($fp2, "\n");
						fputs($fp2, $display);
						fputs($fp2, "\n");
						fputs($fp2, $title);
						fputs($fp2, "\n");
						fputs($fp2, $selector);
						fputs($fp2, "\n");
					}
					fclose($fp2);
				}
			}
			fclose($fp);
		}
	}

	protected function installFontsetting($id_shop, $modorder, $modtitle, $title, $display, $selector, $fonts, $type, $modid, $modinfo)
	{
		$result = true;
		$hidden = 'hidden';

		$id_item = Db::getInstance()->getValue('SELECT id_item FROM `'._DB_PREFIX_.'uhufonts` WHERE `modid` = \''.pSQL($modid).'\'');
		if ($id_item > 0)
			Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'uhufonts` SET
					modorder = \''.pSQL($modorder).'\',
					modtitle = \''.pSQL($modtitle).'\',
					title = \''.pSQL($title).'\',
					display = \''.pSQL($display).'\',
					selector = \''.pSQL($selector).'\',
					fonts = \''.pSQL($fonts).'\',
					type = \''.pSQL($type).'\',
					modinfo = \''.pSQL($modinfo).'\'
					WHERE id_item = '.(int)$id_item
				);
		else
			if ($modid <> '')
			$result &= Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'uhufonts` ( 
					`id_shop`, `modorder`, `modtitle`, `title`, `display`, `selector`, `fonts`, `type`, `modid`, `modinfo`, `active`
			) VALUES ( 
				\''.(int)$id_shop.'\',
				\''.pSQL($modorder).'\',
				\''.pSQL($modtitle).'\',
				\''.pSQL($title).'\',
				\''.pSQL($display).'\',
				\''.pSQL($selector).'\',
				\''.pSQL($fonts).'\',
				\''.pSQL($type).'\',
				\''.pSQL($modid).'\',
				\''.pSQL($modinfo).'\',
				\''.pSQL($hidden).'\'
				)
			');

		return $result;
	}

	private function displayForm()
	{
		$this->_html .= '<form class="defaultForm form-horizontal" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'
								"method="post" enctype="multipart/form-data">';
		$this->_html .= '<div class="panel" id="fieldset_0">';
		$this->_html .= '<div class="panel-heading"><i class="icon-cogs"></i> '.$this->l('Front Font Editor').'</div>';

		$this->_html .= '<div class="form-wrapper">';
		$this->displayFormTabConfigFont();
		$this->_html .= '</div>';

		$this->_html .= '<div class="panel-footer">';
		$this->_html .= '<button type="submit" class="btn btn-default pull-right" name="submitConfigFont">
							<i class="process-icon-save"></i> '.$this->l('Save').'</button>';
		$this->_html .= '</div>';

		$this->_html .= '</div>';
		$this->_html .= '</form>';
	}

	private function displayFormTabConfigFont()
	{
		$result = Tools::file_get_contents(_PS_MODULE_DIR_.'uhuthemesetting/config/fonttype.txt');
		$results = explode(PHP_EOL, $result);

		$unique = array();
		for ($i = 0; $i < $results[0] * 7; $i = $i + 7)
			$unique[] = $results[$i + 5];
		$uniques = array_unique($unique);

		$first = 0;
		foreach ($uniques as $catname)
		{
			if ($first++ == 0)
			{
				$this->_html .= '<div class="alert alert-info">';
				$this->_html .= $this->l('Display a font control block on Front Font Editor when its button is set to YES.');
				$this->_html .= '</div>';
			}
			for ($i = 0; $i < $results[0] * 7; $i = $i + 7)
				if ($results[$i + 5] == $catname && $results[$i + 6] <> 'hidden')
					$this->displayFormTabConfigFontSingle(trim($results[$i + 1]));
		}
	}

	private function displayFormTabConfigFontSingle($title)
	{
		$result = Db::getInstance()->getRow('SELECT modid, active, modtitle FROM `'._DB_PREFIX_.'uhufonts` WHERE type = \''.pSQL($title).'\'');

		$this->_html .= '<div class="form-group">';
		$this->_html .= '<label class="control-label col-lg-3">'.$result['modtitle'].'</label>';
		$this->_html .= '<div class="col-lg-9">
							<div class="row">
								<div class="input-group col-lg-2">
									<span class="switch prestashop-switch">
										<input type="radio" name="'.$result['modid'].'" id="'.$result['modid'].'_on" value="1" '.
											(($result['active'] <> 'hidden') ? 'checked="checked"' : '').'>
										<label for="'.$result['modid'].'_on">
											<i class="icon-check-sign color_success"></i> Yes
										</label>
										<input type="radio" name="'.$result['modid'].'" id="'.$result['modid'].'_off" value="0" '.
											(($result['active'] == 'hidden') ? 'checked="checked"' : '').'>
										<label for="'.$result['modid'].'_off">
											<i class="icon-ban-circle color_danger"></i> No
										</label>
										<a class="slide-button btn btn-default"></a>
									</span>
								</div>
							</div>
						</div>';
		$this->_html .= '</div>';
	}
}