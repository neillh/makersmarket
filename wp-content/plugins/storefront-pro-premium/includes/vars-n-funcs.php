<?php
/**
 * Created by PhpStorm.
 * User: Shramee Srivastav <shramee.srivastav@gmail.com>
 * Date: 3/5/15
 * Time: 7:53 PM
 */

/**
 * Supported control types
 * * text
 * * checkbox
 * * radio (requires choices array in $args)
 * * select (requires choices array in $args)
 * * dropdown-pages
 * * textarea
 * * color
 * * image
 * * sf-text
 * * sf-heading
 * * sf-divider
 *
 * sf- prefixed controls are arbitrary storefront controls
 *
 * NOTE : sf-text control doesn't show anything if description is not set but
 * in Storefront_Pro_Customizer_Fields class we assign it to label
 * if not set ;)
 *
 */
$sf_pro_fonts = array(
	'Arial, sans-serif'                                                 => 'Arial',
	'Verdana, Geneva, sans-serif'                                       => 'Verdana',
	'"Trebuchet MS", Tahoma, sans-serif'                                => 'Trebuchet',
	'Georgia, serif'                                                    => 'Georgia',
	'"Times New Roman", serif'                                          => 'Times New Roman',
	'Tahoma, Geneva, Verdana, sans-serif'                               => 'Tahoma',
	'Palatino, "Palatino Linotype", serif'                              => 'Palatino',
	'"Helvetica Neue", Helvetica, sans-serif'                           => 'Helvetica',
	'Calibri, Candara, Segoe, Optima, sans-serif'                       => 'Calibri',
	'"Myriad Pro", Myriad, sans-serif'                                  => 'Myriad Pro',
	'"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif' => 'Lucida',
	'"Arial Black", sans-serif'                                         => 'Arial Black',
	'"Gill Sans", "Gill Sans MT", Calibri, sans-serif'                  => 'Gill Sans *',
	'Geneva, Tahoma, Verdana, sans-serif'                               => 'Geneva *',
	'Impact, Charcoal, sans-serif'                                      => 'Impact',
	'Courier, "Courier New", monospace'                                 => 'Courier',
	'"Century Gothic", sans-serif'                                      => 'Century Gothic',
	''                                                                  => '-- Google WebFonts --',
	'ABeeZee'                                                           => 'ABeeZee',
	'Abel'                                                              => 'Abel',
	'Abril Fatface'                                                     => 'Abril Fatface',
	'Aclonica'                                                          => 'Aclonica',
	'Acme'                                                              => 'Acme',
	'Actor'                                                             => 'Actor',
	'Adamina'                                                           => 'Adamina',
	'Advent Pro'                                                        => 'Advent Pro',
	'Aguafina Script'                                                   => 'Aguafina Script',
	'Akronim'                                                           => 'Akronim',
	'Aladin'                                                            => 'Aladin',
	'Aldrich'                                                           => 'Aldrich',
	'Alef'                                                              => 'Alef',
	'Alegreya'                                                          => 'Alegreya',
	'Alegreya SC'                                                       => 'Alegreya SC',
	'Alegreya Sans'                                                     => 'Alegreya Sans',
	'Alegreya Sans SC'                                                  => 'Alegreya Sans SC',
	'Alex Brush'                                                        => 'Alex Brush',
	'Alfa Slab One'                                                     => 'Alfa Slab One',
	'Alice'                                                             => 'Alice',
	'Alike'                                                             => 'Alike',
	'Alike Angular'                                                     => 'Alike Angular',
	'Allan'                                                             => 'Allan',
	'Allerta'                                                           => 'Allerta',
	'Allerta Stencil'                                                   => 'Allerta Stencil',
	'Allura'                                                            => 'Allura',
	'Almendra'                                                          => 'Almendra',
	'Almendra Display'                                                  => 'Almendra Display',
	'Almendra SC'                                                       => 'Almendra SC',
	'Amarante'                                                          => 'Amarante',
	'Amaranth'                                                          => 'Amaranth',
	'Amatic SC'                                                         => 'Amatic SC',
	'Amethysta'                                                         => 'Amethysta',
	'Anaheim'                                                           => 'Anaheim',
	'Andada'                                                            => 'Andada',
	'Andika'                                                            => 'Andika',
	'Angkor'                                                            => 'Angkor',
	'Annie Use Your Telescope'                                          => 'Annie Use Your Telescope',
	'Anonymous Pro'                                                     => 'Anonymous Pro',
	'Antic'                                                             => 'Antic',
	'Antic Didone'                                                      => 'Antic Didone',
	'Antic Slab'                                                        => 'Antic Slab',
	'Anton'                                                             => 'Anton',
	'Arapey'                                                            => 'Arapey',
	'Arbutus'                                                           => 'Arbutus',
	'Arbutus Slab'                                                      => 'Arbutus Slab',
	'Architects Daughter'                                               => 'Architects Daughter',
	'Archivo Black'                                                     => 'Archivo Black',
	'Archivo Narrow'                                                    => 'Archivo Narrow',
	'Arimo'                                                             => 'Arimo',
	'Arizonia'                                                          => 'Arizonia',
	'Armata'                                                            => 'Armata',
	'Artifika'                                                          => 'Artifika',
	'Arvo'                                                              => 'Arvo',
	'Asap'                                                              => 'Asap',
	'Asset'                                                             => 'Asset',
	'Astloch'                                                           => 'Astloch',
	'Asul'                                                              => 'Asul',
	'Atomic Age'                                                        => 'Atomic Age',
	'Aubrey'                                                            => 'Aubrey',
	'Audiowide'                                                         => 'Audiowide',
	'Autour One'                                                        => 'Autour One',
	'Average'                                                           => 'Average',
	'Average Sans'                                                      => 'Average Sans',
	'Averia Gruesa Libre'                                               => 'Averia Gruesa Libre',
	'Averia Libre'                                                      => 'Averia Libre',
	'Averia Sans Libre'                                                 => 'Averia Sans Libre',
	'Averia Serif Libre'                                                => 'Averia Serif Libre',
	'Bad Script'                                                        => 'Bad Script',
	'Balthazar'                                                         => 'Balthazar',
	'Bangers'                                                           => 'Bangers',
	'Basic'                                                             => 'Basic',
	'Battambang'                                                        => 'Battambang',
	'Baumans'                                                           => 'Baumans',
	'Bayon'                                                             => 'Bayon',
	'Belgrano'                                                          => 'Belgrano',
	'Belleza'                                                           => 'Belleza',
	'BenchNine'                                                         => 'BenchNine',
	'Bentham'                                                           => 'Bentham',
	'Berkshire Swash'                                                   => 'Berkshire Swash',
	'Bevan'                                                             => 'Bevan',
	'Bigelow Rules'                                                     => 'Bigelow Rules',
	'Bigshot One'                                                       => 'Bigshot One',
	'Bilbo'                                                             => 'Bilbo',
	'Bilbo Swash Caps'                                                  => 'Bilbo Swash Caps',
	'Bitter'                                                            => 'Bitter',
	'Black Ops One'                                                     => 'Black Ops One',
	'Bokor'                                                             => 'Bokor',
	'Bonbon'                                                            => 'Bonbon',
	'Boogaloo'                                                          => 'Boogaloo',
	'Bowlby One'                                                        => 'Bowlby One',
	'Bowlby One SC'                                                     => 'Bowlby One SC',
	'Brawler'                                                           => 'Brawler',
	'Bree Serif'                                                        => 'Bree Serif',
	'Bubblegum Sans'                                                    => 'Bubblegum Sans',
	'Bubbler One'                                                       => 'Bubbler One',
	'Buda'                                                              => 'Buda',
	'Buenard'                                                           => 'Buenard',
	'Butcherman'                                                        => 'Butcherman',
	'Butterfly Kids'                                                    => 'Butterfly Kids',
	'Cabin'                                                             => 'Cabin',
	'Cabin Condensed'                                                   => 'Cabin Condensed',
	'Cabin Sketch'                                                      => 'Cabin Sketch',
	'Caesar Dressing'                                                   => 'Caesar Dressing',
	'Cagliostro'                                                        => 'Cagliostro',
	'Calligraffitti'                                                    => 'Calligraffitti',
	'Cambo'                                                             => 'Cambo',
	'Candal'                                                            => 'Candal',
	'Cantarell'                                                         => 'Cantarell',
	'Cantata One'                                                       => 'Cantata One',
	'Cantora One'                                                       => 'Cantora One',
	'Capriola'                                                          => 'Capriola',
	'Cardo'                                                             => 'Cardo',
	'Carme'                                                             => 'Carme',
	'Carrois Gothic'                                                    => 'Carrois Gothic',
	'Carrois Gothic SC'                                                 => 'Carrois Gothic SC',
	'Carter One'                                                        => 'Carter One',
	'Caudex'                                                            => 'Caudex',
	'Cedarville Cursive'                                                => 'Cedarville Cursive',
	'Ceviche One'                                                       => 'Ceviche One',
	'Changa One'                                                        => 'Changa One',
	'Chango'                                                            => 'Chango',
	'Chau Philomene One'                                                => 'Chau Philomene One',
	'Chela One'                                                         => 'Chela One',
	'Chelsea Market'                                                    => 'Chelsea Market',
	'Chenla'                                                            => 'Chenla',
	'Cherry Cream Soda'                                                 => 'Cherry Cream Soda',
	'Cherry Swash'                                                      => 'Cherry Swash',
	'Chewy'                                                             => 'Chewy',
	'Chicle'                                                            => 'Chicle',
	'Chivo'                                                             => 'Chivo',
	'Cinzel'                                                            => 'Cinzel',
	'Cinzel Decorative'                                                 => 'Cinzel Decorative',
	'Clicker Script'                                                    => 'Clicker Script',
	'Coda'                                                              => 'Coda',
	'Coda Caption'                                                      => 'Coda Caption',
	'Codystar'                                                          => 'Codystar',
	'Combo'                                                             => 'Combo',
	'Comfortaa'                                                         => 'Comfortaa',
	'Coming Soon'                                                       => 'Coming Soon',
	'Concert One'                                                       => 'Concert One',
	'Condiment'                                                         => 'Condiment',
	'Content'                                                           => 'Content',
	'Contrail One'                                                      => 'Contrail One',
	'Convergence'                                                       => 'Convergence',
	'Cookie'                                                            => 'Cookie',
	'Copse'                                                             => 'Copse',
	'Corben'                                                            => 'Corben',
	'Courgette'                                                         => 'Courgette',
	'Cousine'                                                           => 'Cousine',
	'Coustard'                                                          => 'Coustard',
	'Covered By Your Grace'                                             => 'Covered By Your Grace',
	'Crafty Girls'                                                      => 'Crafty Girls',
	'Creepster'                                                         => 'Creepster',
	'Crete Round'                                                       => 'Crete Round',
	'Crimson Text'                                                      => 'Crimson Text',
	'Croissant One'                                                     => 'Croissant One',
	'Crushed'                                                           => 'Crushed',
	'Cuprum'                                                            => 'Cuprum',
	'Cutive'                                                            => 'Cutive',
	'Cutive Mono'                                                       => 'Cutive Mono',
	'Damion'                                                            => 'Damion',
	'Dancing Script'                                                    => 'Dancing Script',
	'Dangrek'                                                           => 'Dangrek',
	'Dawning of a New Day'                                              => 'Dawning of a New Day',
	'Days One'                                                          => 'Days One',
	'Delius'                                                            => 'Delius',
	'Delius Swash Caps'                                                 => 'Delius Swash Caps',
	'Delius Unicase'                                                    => 'Delius Unicase',
	'Della Respira'                                                     => 'Della Respira',
	'Denk One'                                                          => 'Denk One',
	'Devonshire'                                                        => 'Devonshire',
	'Didact Gothic'                                                     => 'Didact Gothic',
	'Diplomata'                                                         => 'Diplomata',
	'Diplomata SC'                                                      => 'Diplomata SC',
	'Domine'                                                            => 'Domine',
	'Donegal One'                                                       => 'Donegal One',
	'Doppio One'                                                        => 'Doppio One',
	'Dorsa'                                                             => 'Dorsa',
	'Dosis'                                                             => 'Dosis',
	'Dr Sugiyama'                                                       => 'Dr Sugiyama',
	'Droid Sans'                                                        => 'Droid Sans',
	'Droid Sans Mono'                                                   => 'Droid Sans Mono',
	'Droid Serif'                                                       => 'Droid Serif',
	'Duru Sans'                                                         => 'Duru Sans',
	'Dynalight'                                                         => 'Dynalight',
	'EB Garamond'                                                       => 'EB Garamond',
	'Eagle Lake'                                                        => 'Eagle Lake',
	'Eater'                                                             => 'Eater',
	'Economica'                                                         => 'Economica',
	'Electrolize'                                                       => 'Electrolize',
	'Elsie'                                                             => 'Elsie',
	'Elsie Swash Caps'                                                  => 'Elsie Swash Caps',
	'Emblema One'                                                       => 'Emblema One',
	'Emilys Candy'                                                      => 'Emilys Candy',
	'Engagement'                                                        => 'Engagement',
	'Englebert'                                                         => 'Englebert',
	'Enriqueta'                                                         => 'Enriqueta',
	'Erica One'                                                         => 'Erica One',
	'Esteban'                                                           => 'Esteban',
	'Euphoria Script'                                                   => 'Euphoria Script',
	'Ewert'                                                             => 'Ewert',
	'Exo'                                                               => 'Exo',
	'Exo 2'                                                             => 'Exo 2',
	'Expletus Sans'                                                     => 'Expletus Sans',
	'Fanwood Text'                                                      => 'Fanwood Text',
	'Fascinate'                                                         => 'Fascinate',
	'Fascinate Inline'                                                  => 'Fascinate Inline',
	'Faster One'                                                        => 'Faster One',
	'Fasthand'                                                          => 'Fasthand',
	'Fauna One'                                                         => 'Fauna One',
	'Federant'                                                          => 'Federant',
	'Federo'                                                            => 'Federo',
	'Felipa'                                                            => 'Felipa',
	'Fenix'                                                             => 'Fenix',
	'Finger Paint'                                                      => 'Finger Paint',
	'Fjalla One'                                                        => 'Fjalla One',
	'Fjord One'                                                         => 'Fjord One',
	'Flamenco'                                                          => 'Flamenco',
	'Flavors'                                                           => 'Flavors',
	'Fondamento'                                                        => 'Fondamento',
	'Fontdiner Swanky'                                                  => 'Fontdiner Swanky',
	'Forum'                                                             => 'Forum',
	'Francois One'                                                      => 'Francois One',
	'Freckle Face'                                                      => 'Freckle Face',
	'Fredericka the Great'                                              => 'Fredericka the Great',
	'Fredoka One'                                                       => 'Fredoka One',
	'Freehand'                                                          => 'Freehand',
	'Fresca'                                                            => 'Fresca',
	'Frijole'                                                           => 'Frijole',
	'Fruktur'                                                           => 'Fruktur',
	'Fugaz One'                                                         => 'Fugaz One',
	'GFS Didot'                                                         => 'GFS Didot',
	'GFS Neohellenic'                                                   => 'GFS Neohellenic',
	'Gabriela'                                                          => 'Gabriela',
	'Gafata'                                                            => 'Gafata',
	'Galdeano'                                                          => 'Galdeano',
	'Galindo'                                                           => 'Galindo',
	'Gentium Basic'                                                     => 'Gentium Basic',
	'Gentium Book Basic'                                                => 'Gentium Book Basic',
	'Geo'                                                               => 'Geo',
	'Geostar'                                                           => 'Geostar',
	'Geostar Fill'                                                      => 'Geostar Fill',
	'Germania One'                                                      => 'Germania One',
	'Gilda Display'                                                     => 'Gilda Display',
	'Give You Glory'                                                    => 'Give You Glory',
	'Glass Antiqua'                                                     => 'Glass Antiqua',
	'Glegoo'                                                            => 'Glegoo',
	'Gloria Hallelujah'                                                 => 'Gloria Hallelujah',
	'Goblin One'                                                        => 'Goblin One',
	'Gochi Hand'                                                        => 'Gochi Hand',
	'Gorditas'                                                          => 'Gorditas',
	'Goudy Bookletter 1911'                                             => 'Goudy Bookletter 1911',
	'Graduate'                                                          => 'Graduate',
	'Grand Hotel'                                                       => 'Grand Hotel',
	'Gravitas One'                                                      => 'Gravitas One',
	'Great Vibes'                                                       => 'Great Vibes',
	'Griffy'                                                            => 'Griffy',
	'Gruppo'                                                            => 'Gruppo',
	'Gudea'                                                             => 'Gudea',
	'Habibi'                                                            => 'Habibi',
	'Hammersmith One'                                                   => 'Hammersmith One',
	'Hanalei'                                                           => 'Hanalei',
	'Hanalei Fill'                                                      => 'Hanalei Fill',
	'Handlee'                                                           => 'Handlee',
	'Hanuman'                                                           => 'Hanuman',
	'Happy Monkey'                                                      => 'Happy Monkey',
	'Headland One'                                                      => 'Headland One',
	'Henny Penny'                                                       => 'Henny Penny',
	'Herr Von Muellerhoff'                                              => 'Herr Von Muellerhoff',
	'Holtwood One SC'                                                   => 'Holtwood One SC',
	'Homemade Apple'                                                    => 'Homemade Apple',
	'Homenaje'                                                          => 'Homenaje',
	'IM Fell DW Pica'                                                   => 'IM Fell DW Pica',
	'IM Fell DW Pica SC'                                                => 'IM Fell DW Pica SC',
	'IM Fell Double Pica'                                               => 'IM Fell Double Pica',
	'IM Fell Double Pica SC'                                            => 'IM Fell Double Pica SC',
	'IM Fell English'                                                   => 'IM Fell English',
	'IM Fell English SC'                                                => 'IM Fell English SC',
	'IM Fell French Canon'                                              => 'IM Fell French Canon',
	'IM Fell French Canon SC'                                           => 'IM Fell French Canon SC',
	'IM Fell Great Primer'                                              => 'IM Fell Great Primer',
	'IM Fell Great Primer SC'                                           => 'IM Fell Great Primer SC',
	'Iceberg'                                                           => 'Iceberg',
	'Iceland'                                                           => 'Iceland',
	'Imprima'                                                           => 'Imprima',
	'Inconsolata'                                                       => 'Inconsolata',
	'Inder'                                                             => 'Inder',
	'Indie Flower'                                                      => 'Indie Flower',
	'Inika'                                                             => 'Inika',
	'Irish Grover'                                                      => 'Irish Grover',
	'Istok Web'                                                         => 'Istok Web',
	'Italiana'                                                          => 'Italiana',
	'Italianno'                                                         => 'Italianno',
	'Jacques Francois'                                                  => 'Jacques Francois',
	'Jacques Francois Shadow'                                           => 'Jacques Francois Shadow',
	'Jim Nightshade'                                                    => 'Jim Nightshade',
	'Jockey One'                                                        => 'Jockey One',
	'Jolly Lodger'                                                      => 'Jolly Lodger',
	'Josefin Sans'                                                      => 'Josefin Sans',
	'Josefin Slab'                                                      => 'Josefin Slab',
	'Joti One'                                                          => 'Joti One',
	'Judson'                                                            => 'Judson',
	'Julee'                                                             => 'Julee',
	'Julius Sans One'                                                   => 'Julius Sans One',
	'Junge'                                                             => 'Junge',
	'Jura'                                                              => 'Jura',
	'Just Another Hand'                                                 => 'Just Another Hand',
	'Just Me Again Down Here'                                           => 'Just Me Again Down Here',
	'Kameron'                                                           => 'Kameron',
	'Kantumruy'                                                         => 'Kantumruy',
	'Karla'                                                             => 'Karla',
	'Kaushan Script'                                                    => 'Kaushan Script',
	'Kavoon'                                                            => 'Kavoon',
	'Kdam Thmor'                                                        => 'Kdam Thmor',
	'Keania One'                                                        => 'Keania One',
	'Kelly Slab'                                                        => 'Kelly Slab',
	'Kenia'                                                             => 'Kenia',
	'Khmer'                                                             => 'Khmer',
	'Kite One'                                                          => 'Kite One',
	'Knewave'                                                           => 'Knewave',
	'Kotta One'                                                         => 'Kotta One',
	'Koulen'                                                            => 'Koulen',
	'Kranky'                                                            => 'Kranky',
	'Kreon'                                                             => 'Kreon',
	'Kristi'                                                            => 'Kristi',
	'Krona One'                                                         => 'Krona One',
	'La Belle Aurore'                                                   => 'La Belle Aurore',
	'Lancelot'                                                          => 'Lancelot',
	'Lato'                                                              => 'Lato',
	'League Script'                                                     => 'League Script',
	'Leckerli One'                                                      => 'Leckerli One',
	'Ledger'                                                            => 'Ledger',
	'Lekton'                                                            => 'Lekton',
	'Lemon'                                                             => 'Lemon',
	'Libre Baskerville'                                                 => 'Libre Baskerville',
	'Life Savers'                                                       => 'Life Savers',
	'Lilita One'                                                        => 'Lilita One',
	'Lily Script One'                                                   => 'Lily Script One',
	'Limelight'                                                         => 'Limelight',
	'Linden Hill'                                                       => 'Linden Hill',
	'Lobster'                                                           => 'Lobster',
	'Lobster Two'                                                       => 'Lobster Two',
	'Londrina Outline'                                                  => 'Londrina Outline',
	'Londrina Shadow'                                                   => 'Londrina Shadow',
	'Londrina Sketch'                                                   => 'Londrina Sketch',
	'Londrina Solid'                                                    => 'Londrina Solid',
	'Lora'                                                              => 'Lora',
	'Love Ya Like A Sister'                                             => 'Love Ya Like A Sister',
	'Loved by the King'                                                 => 'Loved by the King',
	'Lovers Quarrel'                                                    => 'Lovers Quarrel',
	'Luckiest Guy'                                                      => 'Luckiest Guy',
	'Lusitana'                                                          => 'Lusitana',
	'Lustria'                                                           => 'Lustria',
	'Macondo'                                                           => 'Macondo',
	'Macondo Swash Caps'                                                => 'Macondo Swash Caps',
	'Magra'                                                             => 'Magra',
	'Maiden Orange'                                                     => 'Maiden Orange',
	'Mako'                                                              => 'Mako',
	'Marcellus'                                                         => 'Marcellus',
	'Marcellus SC'                                                      => 'Marcellus SC',
	'Marck Script'                                                      => 'Marck Script',
	'Margarine'                                                         => 'Margarine',
	'Marko One'                                                         => 'Marko One',
	'Marmelad'                                                          => 'Marmelad',
	'Marvel'                                                            => 'Marvel',
	'Mate'                                                              => 'Mate',
	'Mate SC'                                                           => 'Mate SC',
	'Maven Pro'                                                         => 'Maven Pro',
	'McLaren'                                                           => 'McLaren',
	'Meddon'                                                            => 'Meddon',
	'MedievalSharp'                                                     => 'MedievalSharp',
	'Medula One'                                                        => 'Medula One',
	'Megrim'                                                            => 'Megrim',
	'Meie Script'                                                       => 'Meie Script',
	'Merienda'                                                          => 'Merienda',
	'Merienda One'                                                      => 'Merienda One',
	'Merriweather'                                                      => 'Merriweather',
	'Merriweather Sans'                                                 => 'Merriweather Sans',
	'Metal'                                                             => 'Metal',
	'Metal Mania'                                                       => 'Metal Mania',
	'Metamorphous'                                                      => 'Metamorphous',
	'Metrophobic'                                                       => 'Metrophobic',
	'Michroma'                                                          => 'Michroma',
	'Milonga'                                                           => 'Milonga',
	'Miltonian'                                                         => 'Miltonian',
	'Miltonian Tattoo'                                                  => 'Miltonian Tattoo',
	'Miniver'                                                           => 'Miniver',
	'Miss Fajardose'                                                    => 'Miss Fajardose',
	'Modern Antiqua'                                                    => 'Modern Antiqua',
	'Molengo'                                                           => 'Molengo',
	'Molle'                                                             => 'Molle',
	'Monda'                                                             => 'Monda',
	'Monofett'                                                          => 'Monofett',
	'Monoton'                                                           => 'Monoton',
	'Monsieur La Doulaise'                                              => 'Monsieur La Doulaise',
	'Montaga'                                                           => 'Montaga',
	'Montez'                                                            => 'Montez',
	'Montserrat'                                                        => 'Montserrat',
	'Montserrat Alternates'                                             => 'Montserrat Alternates',
	'Montserrat Subrayada'                                              => 'Montserrat Subrayada',
	'Moul'                                                              => 'Moul',
	'Moulpali'                                                          => 'Moulpali',
	'Mountains of Christmas'                                            => 'Mountains of Christmas',
	'Mouse Memoirs'                                                     => 'Mouse Memoirs',
	'Mr Bedfort'                                                        => 'Mr Bedfort',
	'Mr Dafoe'                                                          => 'Mr Dafoe',
	'Mr De Haviland'                                                    => 'Mr De Haviland',
	'Mrs Saint Delafield'                                               => 'Mrs Saint Delafield',
	'Mrs Sheppards'                                                     => 'Mrs Sheppards',
	'Muli'                                                              => 'Muli',
	'Mystery Quest'                                                     => 'Mystery Quest',
	'Neucha'                                                            => 'Neucha',
	'Neuton'                                                            => 'Neuton',
	'New Rocker'                                                        => 'New Rocker',
	'News Cycle'                                                        => 'News Cycle',
	'Niconne'                                                           => 'Niconne',
	'Nixie One'                                                         => 'Nixie One',
	'Nobile'                                                            => 'Nobile',
	'Nokora'                                                            => 'Nokora',
	'Norican'                                                           => 'Norican',
	'Nosifer'                                                           => 'Nosifer',
	'Nothing You Could Do'                                              => 'Nothing You Could Do',
	'Noticia Text'                                                      => 'Noticia Text',
	'Noto Sans'                                                         => 'Noto Sans',
	'Noto Serif'                                                        => 'Noto Serif',
	'Nova Cut'                                                          => 'Nova Cut',
	'Nova Flat'                                                         => 'Nova Flat',
	'Nova Mono'                                                         => 'Nova Mono',
	'Nova Oval'                                                         => 'Nova Oval',
	'Nova Round'                                                        => 'Nova Round',
	'Nova Script'                                                       => 'Nova Script',
	'Nova Slim'                                                         => 'Nova Slim',
	'Nova Square'                                                       => 'Nova Square',
	'Numans'                                                            => 'Numans',
	'Nunito'                                                            => 'Nunito',
	'Odor Mean Chey'                                                    => 'Odor Mean Chey',
	'Offside'                                                           => 'Offside',
	'Old Standard TT'                                                   => 'Old Standard TT',
	'Oldenburg'                                                         => 'Oldenburg',
	'Oleo Script'                                                       => 'Oleo Script',
	'Oleo Script Swash Caps'                                            => 'Oleo Script Swash Caps',
	'Open Sans'                                                         => 'Open Sans',
	'Open Sans Condensed'                                               => 'Open Sans Condensed',
	'Oranienbaum'                                                       => 'Oranienbaum',
	'Orbitron'                                                          => 'Orbitron',
	'Oregano'                                                           => 'Oregano',
	'Orienta'                                                           => 'Orienta',
	'Original Surfer'                                                   => 'Original Surfer',
	'Oswald'                                                            => 'Oswald',
	'Over the Rainbow'                                                  => 'Over the Rainbow',
	'Overlock'                                                          => 'Overlock',
	'Overlock SC'                                                       => 'Overlock SC',
	'Ovo'                                                               => 'Ovo',
	'Oxygen'                                                            => 'Oxygen',
	'Oxygen Mono'                                                       => 'Oxygen Mono',
	'PT Mono'                                                           => 'PT Mono',
	'PT Sans'                                                           => 'PT Sans',
	'PT Sans Caption'                                                   => 'PT Sans Caption',
	'PT Sans Narrow'                                                    => 'PT Sans Narrow',
	'PT Serif'                                                          => 'PT Serif',
	'PT Serif Caption'                                                  => 'PT Serif Caption',
	'Pacifico'                                                          => 'Pacifico',
	'Paprika'                                                           => 'Paprika',
	'Parisienne'                                                        => 'Parisienne',
	'Passero One'                                                       => 'Passero One',
	'Passion One'                                                       => 'Passion One',
	'Pathway Gothic One'                                                => 'Pathway Gothic One',
	'Patrick Hand'                                                      => 'Patrick Hand',
	'Patrick Hand SC'                                                   => 'Patrick Hand SC',
	'Patua One'                                                         => 'Patua One',
	'Paytone One'                                                       => 'Paytone One',
	'Peralta'                                                           => 'Peralta',
	'Permanent Marker'                                                  => 'Permanent Marker',
	'Petit Formal Script'                                               => 'Petit Formal Script',
	'Petrona'                                                           => 'Petrona',
	'Philosopher'                                                       => 'Philosopher',
	'Piedra'                                                            => 'Piedra',
	'Pinyon Script'                                                     => 'Pinyon Script',
	'Pirata One'                                                        => 'Pirata One',
	'Plaster'                                                           => 'Plaster',
	'Play'                                                              => 'Play',
	'Playball'                                                          => 'Playball',
	'Playfair Display'                                                  => 'Playfair Display',
	'Playfair Display SC'                                               => 'Playfair Display SC',
	'Podkova'                                                           => 'Podkova',
	'Poiret One'                                                        => 'Poiret One',
	'Poller One'                                                        => 'Poller One',
	'Poly'                                                              => 'Poly',
	'Pompiere'                                                          => 'Pompiere',
	'Pontano Sans'                                                      => 'Pontano Sans',
	'Port Lligat Sans'                                                  => 'Port Lligat Sans',
	'Port Lligat Slab'                                                  => 'Port Lligat Slab',
	'Prata'                                                             => 'Prata',
	'Preahvihear'                                                       => 'Preahvihear',
	'Press Start 2P'                                                    => 'Press Start 2P',
	'Princess Sofia'                                                    => 'Princess Sofia',
	'Prociono'                                                          => 'Prociono',
	'Prosto One'                                                        => 'Prosto One',
	'Puritan'                                                           => 'Puritan',
	'Purple Purse'                                                      => 'Purple Purse',
	'Quando'                                                            => 'Quando',
	'Quantico'                                                          => 'Quantico',
	'Quattrocento'                                                      => 'Quattrocento',
	'Quattrocento Sans'                                                 => 'Quattrocento Sans',
	'Questrial'                                                         => 'Questrial',
	'Quicksand'                                                         => 'Quicksand',
	'Quintessential'                                                    => 'Quintessential',
	'Qwigley'                                                           => 'Qwigley',
	'Racing Sans One'                                                   => 'Racing Sans One',
	'Radley'                                                            => 'Radley',
	'Raleway'                                                           => 'Raleway',
	'Raleway Dots'                                                      => 'Raleway Dots',
	'Rambla'                                                            => 'Rambla',
	'Rammetto One'                                                      => 'Rammetto One',
	'Ranchers'                                                          => 'Ranchers',
	'Rancho'                                                            => 'Rancho',
	'Rationale'                                                         => 'Rationale',
	'Redressed'                                                         => 'Redressed',
	'Reenie Beanie'                                                     => 'Reenie Beanie',
	'Revalia'                                                           => 'Revalia',
	'Ribeye'                                                            => 'Ribeye',
	'Ribeye Marrow'                                                     => 'Ribeye Marrow',
	'Righteous'                                                         => 'Righteous',
	'Risque'                                                            => 'Risque',
	'Roboto'                                                            => 'Roboto',
	'Roboto Condensed'                                                  => 'Roboto Condensed',
	'Roboto Slab'                                                       => 'Roboto Slab',
	'Rochester'                                                         => 'Rochester',
	'Rock Salt'                                                         => 'Rock Salt',
	'Rokkitt'                                                           => 'Rokkitt',
	'Romanesco'                                                         => 'Romanesco',
	'Ropa Sans'                                                         => 'Ropa Sans',
	'Rosario'                                                           => 'Rosario',
	'Rosarivo'                                                          => 'Rosarivo',
	'Rouge Script'                                                      => 'Rouge Script',
	'Ruda'                                                              => 'Ruda',
	'Rufina'                                                            => 'Rufina',
	'Ruge Boogie'                                                       => 'Ruge Boogie',
	'Ruluko'                                                            => 'Ruluko',
	'Rum Raisin'                                                        => 'Rum Raisin',
	'Ruslan Display'                                                    => 'Ruslan Display',
	'Russo One'                                                         => 'Russo One',
	'Ruthie'                                                            => 'Ruthie',
	'Rye'                                                               => 'Rye',
	'Sacramento'                                                        => 'Sacramento',
	'Sail'                                                              => 'Sail',
	'Salsa'                                                             => 'Salsa',
	'Sanchez'                                                           => 'Sanchez',
	'Sancreek'                                                          => 'Sancreek',
	'Sansita One'                                                       => 'Sansita One',
	'Sarina'                                                            => 'Sarina',
	'Satisfy'                                                           => 'Satisfy',
	'Scada'                                                             => 'Scada',
	'Schoolbell'                                                        => 'Schoolbell',
	'Seaweed Script'                                                    => 'Seaweed Script',
	'Sevillana'                                                         => 'Sevillana',
	'Seymour One'                                                       => 'Seymour One',
	'Shadows Into Light'                                                => 'Shadows Into Light',
	'Shadows Into Light Two'                                            => 'Shadows Into Light Two',
	'Shanti'                                                            => 'Shanti',
	'Share'                                                             => 'Share',
	'Share Tech'                                                        => 'Share Tech',
	'Share Tech Mono'                                                   => 'Share Tech Mono',
	'Shojumaru'                                                         => 'Shojumaru',
	'Short Stack'                                                       => 'Short Stack',
	'Siemreap'                                                          => 'Siemreap',
	'Sigmar One'                                                        => 'Sigmar One',
	'Signika'                                                           => 'Signika',
	'Signika Negative'                                                  => 'Signika Negative',
	'Simonetta'                                                         => 'Simonetta',
	'Sintony'                                                           => 'Sintony',
	'Sirin Stencil'                                                     => 'Sirin Stencil',
	'Six Caps'                                                          => 'Six Caps',
	'Skranji'                                                           => 'Skranji',
	'Slackey'                                                           => 'Slackey',
	'Smokum'                                                            => 'Smokum',
	'Smythe'                                                            => 'Smythe',
	'Sniglet'                                                           => 'Sniglet',
	'Snippet'                                                           => 'Snippet',
	'Snowburst One'                                                     => 'Snowburst One',
	'Sofadi One'                                                        => 'Sofadi One',
	'Sofia'                                                             => 'Sofia',
	'Sonsie One'                                                        => 'Sonsie One',
	'Sorts Mill Goudy'                                                  => 'Sorts Mill Goudy',
	'Source Code Pro'                                                   => 'Source Code Pro',
	'Source Sans Pro'                                                   => 'Source Sans Pro',
	'Special Elite'                                                     => 'Special Elite',
	'Spicy Rice'                                                        => 'Spicy Rice',
	'Spinnaker'                                                         => 'Spinnaker',
	'Spirax'                                                            => 'Spirax',
	'Squada One'                                                        => 'Squada One',
	'Stalemate'                                                         => 'Stalemate',
	'Stalinist One'                                                     => 'Stalinist One',
	'Stardos Stencil'                                                   => 'Stardos Stencil',
	'Stint Ultra Condensed'                                             => 'Stint Ultra Condensed',
	'Stint Ultra Expanded'                                              => 'Stint Ultra Expanded',
	'Stoke'                                                             => 'Stoke',
	'Strait'                                                            => 'Strait',
	'Sue Ellen Francisco'                                               => 'Sue Ellen Francisco',
	'Sunshiney'                                                         => 'Sunshiney',
	'Supermercado One'                                                  => 'Supermercado One',
	'Suwannaphum'                                                       => 'Suwannaphum',
	'Swanky and Moo Moo'                                                => 'Swanky and Moo Moo',
	'Syncopate'                                                         => 'Syncopate',
	'Tangerine'                                                         => 'Tangerine',
	'Taprom'                                                            => 'Taprom',
	'Tauri'                                                             => 'Tauri',
	'Telex'                                                             => 'Telex',
	'Tenor Sans'                                                        => 'Tenor Sans',
	'Text Me One'                                                       => 'Text Me One',
	'The Girl Next Door'                                                => 'The Girl Next Door',
	'Tienne'                                                            => 'Tienne',
	'Tinos'                                                             => 'Tinos',
	'Titan One'                                                         => 'Titan One',
	'Titillium Web'                                                     => 'Titillium Web',
	'Trade Winds'                                                       => 'Trade Winds',
	'Trocchi'                                                           => 'Trocchi',
	'Trochut'                                                           => 'Trochut',
	'Trykker'                                                           => 'Trykker',
	'Tulpen One'                                                        => 'Tulpen One',
	'Ubuntu'                                                            => 'Ubuntu',
	'Ubuntu Condensed'                                                  => 'Ubuntu Condensed',
	'Ubuntu Mono'                                                       => 'Ubuntu Mono',
	'Ultra'                                                             => 'Ultra',
	'Uncial Antiqua'                                                    => 'Uncial Antiqua',
	'Underdog'                                                          => 'Underdog',
	'Unica One'                                                         => 'Unica One',
	'UnifrakturCook'                                                    => 'UnifrakturCook',
	'UnifrakturMaguntia'                                                => 'UnifrakturMaguntia',
	'Unkempt'                                                           => 'Unkempt',
	'Unlock'                                                            => 'Unlock',
	'Unna'                                                              => 'Unna',
	'VT323'                                                             => 'VT323',
	'Vampiro One'                                                       => 'Vampiro One',
	'Varela'                                                            => 'Varela',
	'Varela Round'                                                      => 'Varela Round',
	'Vast Shadow'                                                       => 'Vast Shadow',
	'Vibur'                                                             => 'Vibur',
	'Vidaloka'                                                          => 'Vidaloka',
	'Viga'                                                              => 'Viga',
	'Voces'                                                             => 'Voces',
	'Volkhov'                                                           => 'Volkhov',
	'Vollkorn'                                                          => 'Vollkorn',
	'Voltaire'                                                          => 'Voltaire',
	'Waiting for the Sunrise'                                           => 'Waiting for the Sunrise',
	'Wallpoet'                                                          => 'Wallpoet',
	'Walter Turncoat'                                                   => 'Walter Turncoat',
	'Warnes'                                                            => 'Warnes',
	'Wellfleet'                                                         => 'Wellfleet',
	'Wendy One'                                                         => 'Wendy One',
	'Wire One'                                                          => 'Wire One',
	'Yanone Kaffeesatz'                                                 => 'Yanone Kaffeesatz',
	'Yellowtail'                                                        => 'Yellowtail',
	'Yeseva One'                                                        => 'Yeseva One',
	'Yesteryear'                                                        => 'Yesteryear',
	'Zeyada'                                                            => 'Zeyada',
);

function storefront_pro_fields() {
	$fields = array(
		//Primary Nav
		array(
			'id'      => 'nav-style',
			'label'   => __( 'Navigation Style', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'select',
			'choices' => array(
				''                                      => 'Default',
				'right'                                 => 'Align right items left',
				'right nav-items-right'                 => 'Align right items right',
				'center'                                => 'Centered',
				'center-inline'                         => 'Centred inline logo',
				'left-vertical'                         => 'Left vertical',
				'left-vertical hamburger'               => 'Hamburger',
				'left-vertical hamburger lv-full-width' => 'Full width Hamburger',
			),
		),
		array(
			'id'      => 'pri-nav-label',
			'label'   => __( 'Hamburger Label', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'text',
		),
		array(
			'id'      => 'show-search-box',
			'label'   => __( 'Show search box in Header', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'search-box-size',
			'label'   => __( 'Search box size', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'select',
			'choices' => array(
				''    => __( 'Default', SFP_TKN ),
				'34%' => __( 'Large', SFP_TKN ),
				'43%' => __( 'Extra Large', SFP_TKN ),
			),
		),
		array(
			'id'      => 'search-box-bo-rad',
			'label'   => __( 'Search box rounded corners', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'select',
			'choices' => array(
				''     => __( 'Boxy', SFP_TKN ),
				'7px'  => __( 'Curvaceous', SFP_TKN ),
				'16px' => __( 'Really Curvaceous', SFP_TKN ),
			),
		),
		array(
			'id'      => 'search-box-bg-clr',
			'label'   => __( 'Search box background color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'      => 'search-box-text-clr',
			'label'   => __( 'Search box text color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'      => 'remove-search-icon',
			'label'   => __( 'Remove search icon from nav', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'pri-nav-font',
			'label'   => __( 'Font', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'font',
		),
		array(
			'id'          => 'pri-nav-text-size',
			'label'       => __( 'Text size', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'      => 'pri-nav-text-color',
			'label'   => __( 'Text color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'          => 'pri-nav-letter-spacing',
			'label'       => __( 'Letter spacing', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => - 2,
				'max'  => 10,
				'step' => 1,
			),
		),
		array(
			'id'      => 'pri-nav-font-style',
			'label'   => __( 'Font style', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'multi-checkbox',
			'choices' => array(
				'bold'      => __( 'Bold', SFP_TKN ),
				'italic'    => __( 'Italic', SFP_TKN ),
				'underline' => __( 'Underline', SFP_TKN ),
				'uppercase' => __( 'Uppercase', SFP_TKN ),
			),
		),
		array(
			'id'      => 'pri-nav-active-link-color',
			'label'   => __( 'Active link color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'      => 'pri-nav-bg-color',
			'label'   => __( 'Background color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'alpha-color',
		),
		array(
			'id'      => 'pri-nav-dd-bg-color',
			'label'   => __( 'Drop down menu background color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'alpha-color',
		),
		array(
			'id'      => 'pri-nav-dd-text-color',
			'label'   => __( 'Drop down menu text color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'      => 'pri-nav-dd-animation',
			'label'   => __( 'Drop down menu animation', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'select',
			'choices' => array(
				''       => __( 'Default', SFP_TKN ),
				'fade'   => __( 'Fade', SFP_TKN ),
				'expand' => __( 'Expand', SFP_TKN ),
				'slide'  => __( 'Slide', SFP_TKN ),
				'flip'   => __( 'Flip', SFP_TKN ),
			),
		),
		array(
			'id'          => 'pri-nav-icon-size',
			'label'       => __( 'Icon size', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 16,
				'max'  => 52,
				'step' => 1,
			),
		),
		array(
			'id'      => 'pri-nav-icon-color',
			'label'   => __( 'Icon color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'          => 'pri-nav-dd-icon-size',
			'label'       => __( 'Drop down menu Icon size', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'          => 'pri-nav-dd-text-size',
			'label'       => __( 'Drop down menu text size', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'      => 'pri-nav-dd-icon-color',
			'label'   => __( 'Drop down menu Icon color', SFP_TKN ),
			'section' => 'Primary Navigation',
			'type'    => 'color',
		),
		array(
			'id'          => 'pri-nav-height',
			'label'       => __( 'Menu height', SFP_TKN ),
			'section'     => 'Primary Navigation',
			'type'        => 'range',
			'default'     => 1,
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 2.5,
				'step' => 0.1,
			),
		),

		//Secondary Nav
		array(
			'id'      => 'sec-nav-full',
			'label'   => __( 'Make full width', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'sec-nav-font',
			'label'   => __( 'Font', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'font',
		),
		array(
			'id'          => 'sec-nav-text-size',
			'label'       => __( 'Text size', SFP_TKN ),
			'section'     => 'Secondary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
			'default'     => '',
		),
		array(
			'id'          => 'sec-nav-letter-spacing',
			'label'       => __( 'Letter spacing', SFP_TKN ),
			'section'     => 'Secondary Navigation',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => - 2,
				'max'  => 10,
				'step' => 1,
			),
		),
		array(
			'id'      => 'sec-nav-font-style',
			'label'   => __( 'Font style', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'multi-checkbox',
			'choices' => array(
				'bold'      => __( 'Bold', SFP_TKN ),
				'italic'    => __( 'Italic', SFP_TKN ),
				'underline' => __( 'Underline', SFP_TKN ),
				'uppercase' => __( 'Uppercase', SFP_TKN ),
			),
		),
		array(
			'id'      => 'sec-nav-bg-color',
			'label'   => __( 'Background color', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'alpha-color',
		),
		array(
			'id'      => 'sec-nav-text-color',
			'label'   => __( 'Text color', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'color',
			'default' => '#fff',
		),
		array(
			'id'      => 'sec-nav-active-link-color',
			'label'   => __( 'Active link color', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'color',
		),
		array(
			'id'      => 'sec-nav-dd-bg-color',
			'label'   => __( 'Drop down menu background color', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'alpha-color',
		),
		array(
			'id'      => 'sec-nav-dd-text-color',
			'label'   => __( 'Drop down menu text color', SFP_TKN ),
			'section' => 'Secondary Navigation',
			'type'    => 'color',
		),

		//Header Elements
		array(
			'id'       => 'logo',
			'label'    => __( 'Logo', SFP_TKN ),
			'section'  => 'existing_header_image',
			'type'     => 'image',
			'priority' => 1,
		),
		array(
			'id'       => 'header-bg-color',
			'label'    => __( 'Header Background Color', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 16,
			'type'     => 'alpha-color',
		),
		array(
			'id'          => 'logo-max-height',
			'label'       => __( 'Logo max height', SFP_TKN ),
			'section'     => 'existing_header_image',
			'priority'    => 25,
			'type'        => 'range',
			'default'     => 75,
			'input_attrs' => array(
				'min'  => 50,
				'max'  => 250,
				'step' => 1,
			),
		),
		array(
			'id'       => 'phone-num',
			'label'    => __( 'Phone Number', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'email',
			'label'    => __( 'Email', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'facebook',
			'label'    => __( 'Facebook profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'twitter',
			'label'    => __( 'Twitter profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'whatsapp',
			'label'    => __( 'WhatsApp number (with country-code)', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'googleplus',
			'label'    => __( 'Google+ profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'linkedin',
			'label'    => __( 'Linked in profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'instagram',
			'label'    => __( 'Instagram profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'pinterest',
			'label'    => __( 'Pinterest profile URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'youtube',
			'label'    => __( 'Youtube channel URL', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'text',
		),
		array(
			'id'       => 'align-social-info',
			'label'    => __( 'Align social icons and contact info', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'select',
			'choices'  => array(
				''       => __( 'Left', SFP_TKN ),
				'right'  => __( 'Right', SFP_TKN ),
				'center' => __( 'Center', SFP_TKN ),
			),
		),
		array(
			'id'       => 'search-post_type',
			'label'    => __( 'Post types to search', SFP_TKN ),
			'priority' => 40,
			'section'  => 'existing_header_image',
			'type'     => 'select',
			'default'  => 'post,page',
			'choices'  => array(
				'post,page' => __( 'Posts and Pages', SFP_TKN ),
				'product'   => __( 'Products', SFP_TKN ),
			),
		),
		array(
			'id'       => 'header-wc-cart',
			'label'    => __( 'Cart location', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'select',
			'choices'  => array(
				''     => __( 'Primary nav', SFP_TKN ),
				'_sec' => __( 'Secondary nav', SFP_TKN ),
				'hide' => __( 'Hide', SFP_TKN ),
			),
		),
		array(
			'id'       => 'header-wc-cart-color',
			'label'    => __( 'Cart text color', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'color',
		),
		array(
			'id'       => 'header-wc-cart-dd-color',
			'label'    => __( 'Cart drop down text color', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'color',
			'default'  => '#ffffff',
		),
		array(
			'id'       => 'header-sticky',
			'label'    => __( 'Make header sticky', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'checkbox',
		),
		array(
			'id'       => 'header-hide-until-scroll',
			'label'    => __( 'Hide header until scroll', SFP_TKN ),
			'section'  => 'existing_header_image',
			'priority' => 25,
			'type'     => 'checkbox',
		),
		/*
		array(
			'id'      => 'header-sticky-compress',
			'label'   => __( 'Reduce sticky header height', SFP_TKN ),
			'section' => 'existing_header_image',
			'priority' => 25,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'header-over-content',
			'label'   => __( 'Make header come over first row in page builder', SFP_TKN ),
			'section' => 'existing_header_image',
			'priority' => 25,
			'type'    => 'checkbox',
		),
		*/
		//Content
		array(
			'id'      => 'hide-link-focus-outline',
			'label'   => __( 'Hide accessibility box around active links', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'hide-wc-breadcrumbs-pages',
			'label'   => __( 'Hide breadcrumbs on pages', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'hide-wc-breadcrumbs-posts',
			'label'   => __( 'Hide breadcrumbs on posts', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
			'default' => true,
		),
		array(
			'id'      => 'hide-wc-breadcrumbs-archives',
			'label'   => __( 'Hide breadcrumbs on archives', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'hide-page-title',
			'label'   => __( 'Hide page title', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'hide-hr',
			'label'   => __( 'Hide horizontal lines', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'content-hr-color',
			'label'   => __( 'Change horizontal lines color', SFP_TKN ),
			'section' => 'Content Elements',
			'type'    => 'color',
		),

		//Single
		array(
			'id'          => 'single-header-size',
			'label'       => __( 'Heading size', SFP_TKN ),
			'section'     => 'existing_storefront_single_post',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 8,
				'max'  => 80,
				'step' => 1,
			),
		),
		array(
			'id'      => 'single-fixed-featured-image',
			'label'   => __( 'Featured image fixed parallax', SFP_TKN ),
			'section' => 'existing_storefront_single_post',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'single-header-color',
			'label'   => __( 'Heading color', SFP_TKN ),
			'section' => 'existing_storefront_single_post',
			'type'    => 'color',
		),
		array(
			'id'      => 'single-post-meta',
			'label'   => __( 'Hide post meta', SFP_TKN ),
			'section' => 'existing_storefront_single_post',
			'type'    => 'checkbox',
		),

		//Blog
		array(
			'id'          => 'blog-header-size',
			'label'       => __( 'Heading size', SFP_TKN ),
			'section'     => 'existing_storefront_archive',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 8,
				'max'  => 80,
				'step' => 1,
			),
		),
		array(
			'id'      => 'blog-header-color',
			'label'   => __( 'Heading color', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'color',
		),
		'blog-layout'       => array(
			'id'      => 'blog-layout',
			'label'   => __( 'Layout', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'sf-radio-image',
			'choices' => array(
				''            => SFP_URL . '/assets/img/admin/layout-default.png',
				'left-image'  => SFP_URL . '/assets/img/admin/layout-left-image.png',
				'full-image'  => SFP_URL . '/assets/img/admin/layout-full-image.png',
				'right-image' => SFP_URL . '/assets/img/admin/layout-right-image.png',
				'tiles'       => SFP_URL . '/assets/img/admin/layout-tiles.png',

			),
		),
		array(
			'id'      => 'blog-grid',
			'label'   => __( 'Show posts', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'grid',
			'default' => '3,4',
		),
		array(
			'id'      => 'blog-content',
			'label'   => __( 'Full content or Excerpt', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'select',
			'choices' => array(
				'full' => __( 'Full post', SFP_TKN ),
				''     => __( 'Excerpt', SFP_TKN ),
			),
		),
		array(
			'id'      => 'blog-excerpt-count',
			'label'   => __( 'Excerpt word count', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'number',
			'default' => 55,
		),
		array(
			'id'      => 'blog-excerpt-end',
			'label'   => __( 'Excerpt word end', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'text',
			'default' => '[...]',
		),
		array(
			'id'      => 'blog-rm-butt-text',
			'label'   => __( 'Read more button text', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'text',
		),
		array(
			'id'      => 'blog-post-meta',
			'label'   => __( 'Hide post meta', SFP_TKN ),
			'section' => 'existing_storefront_archive',
			'type'    => 'checkbox',
		),

		//Typography
		array(
			'id'      => 'typo-body-font',
			'label'   => __( 'Body font', SFP_TKN ),
			'section' => 'existing_storefront_typography',
			'type'    => 'font',
		),
		array(
			'id'          => 'typo-body-font-size',
			'label'       => __( 'Body text size', SFP_TKN ),
			'section'     => 'existing_storefront_typography',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'          => 'typo-body-line-height',
			'label'       => __( 'Body line height', SFP_TKN ),
			'section'     => 'existing_storefront_typography',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0.5,
				'max'  => 2.5,
				'step' => 0.1,
			),
		),
		array(
			'id'      => 'typo-header-font',
			'label'   => __( 'Heading font', SFP_TKN ),
			'section' => 'existing_storefront_typography',
			'type'    => 'font',
		),
		array(
			'id'          => 'typo-header-font-size',
			'label'       => __( 'Heading text size', SFP_TKN ),
			'section'     => 'existing_storefront_typography',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'          => 'typo-header-letter-spacing',
			'label'       => __( 'Heading letter spacing', SFP_TKN ),
			'section'     => 'existing_storefront_typography',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => - 2,
				'max'  => 10,
				'step' => 1,
			),
		),
		array(
			'id'          => 'typo-header-line-height',
			'label'       => __( 'Heading line height', SFP_TKN ),
			'section'     => 'existing_storefront_typography',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 0.5,
				'max'  => 2.5,
				'step' => 0.1,
			),
		),
		array(
			'id'      => 'typo-header-font-style',
			'label'   => __( 'Heading font style', SFP_TKN ),
			'section' => 'existing_storefront_typography',
			'type'    => 'multi-checkbox',
			'choices' => array(
				'bold'      => __( 'Bold', SFP_TKN ),
				'italic'    => __( 'Italic', SFP_TKN ),
				'underline' => __( 'Underline', SFP_TKN ),
				'uppercase' => __( 'Uppercase', SFP_TKN ),
			),
		),

		//Footer - Layout
		array(
			'id'       => 'typo-footer-layout',
			'label'    => __( 'Footer layout', SFP_TKN ),
			'section'  => 'existing_storefront_footer',
			'type'     => 'select',
			'choices'  => array(
				'4'           => __( '4 Columns', SFP_TKN ),
				'3'           => __( '3 Columns', SFP_TKN ),
				'2'           => __( '2 Columns', SFP_TKN ),
				'1'           => __( '1 Column', SFP_TKN ),
				'1_4-3_4'     => __( '1/4 + 3/4 Columns', SFP_TKN ),
				'3_4-1_4'     => __( '3/4 + 1/4 Columns', SFP_TKN ),
				'1_3-2_3'     => __( '1/3 + 2/3 Columns', SFP_TKN ),
				'2_3-1_3'     => __( '2/3 + 1/3 Columns', SFP_TKN ),
				'1_4-1_4-1_2' => __( '1/4 + 1/4 + 1/2 Columns', SFP_TKN ),
				'1_2-1_4-1_4' => __( '1/2 + 1/4 + 1/4 Columns', SFP_TKN ),
			),
			'priority' => 5,
		),
		array(
			'id'       => 'footer-bg-image',
			'label'    => __( 'Background Image', SFP_TKN ),
			'section'  => 'existing_storefront_footer',
			'type'     => 'image',
			'priority' => 1,
		),
		array(
			'id'          => 'footer-custom-text',
			'label'       => __( 'Custom footer text', SFP_TKN ),
			'section'     => 'existing_storefront_footer',
			'type'        => 'textarea',
			'description' => 'Type here some text to replace footer text.',
		),
		//Footer - Widgets
		array(
			'id'          => 'footer-wid-header-font-size',
			'label'       => __( 'Header text size', SFP_TKN ),
			'section'     => 'Widgets',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 12,
				'max'  => 32,
				'step' => 1,
			),
		),
		array(
			'id'      => 'footer-wid-header-font-style',
			'label'   => __( 'Header font style', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'multi-checkbox',
			'choices' => array(
				'bold'      => __( 'Bold', SFP_TKN ),
				'italic'    => __( 'Italic', SFP_TKN ),
				'underline' => __( 'Underline', SFP_TKN ),
				'uppercase' => __( 'Uppercase', SFP_TKN ),
			),
		),
		array(
			'id'      => 'footer-wid-header-color',
			'label'   => __( 'Widget header color', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'color',
		),
		array(
			'id'          => 'footer-wid-font-size',
			'label'       => __( 'Text size', SFP_TKN ),
			'section'     => 'Widgets',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 25,
				'step' => 1,
			),
		),
		array(
			'id'      => 'footer-wid-font-style',
			'label'   => __( 'Font style', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'multi-checkbox',
			'choices' => array(
				'bold'      => __( 'Bold', SFP_TKN ),
				'italic'    => __( 'Italic', SFP_TKN ),
				'underline' => __( 'Underline', SFP_TKN ),
				'uppercase' => __( 'Uppercase', SFP_TKN ),
			),
		),
		array(
			'id'      => 'footer-wid-color',
			'label'   => __( 'Widget text color', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'color',
		),
		array(
			'id'      => 'footer-wid-link-color',
			'label'   => __( 'Widget link color', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'color',
		),
		array(
			'id'      => 'footer-wid-bullet-color',
			'label'   => __( 'Widget bullet color', SFP_TKN ),
			'section' => 'Widgets',
			'type'    => 'color',
		),

		//Shop
		'wc-shop-layout'    => array(
			'id'      => 'wc-shop-layout',
			'label'   => __( 'Shop layout', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'radio',
			'choices' => array(
				''        => __( 'Default', SFP_TKN ),
				'full'    => __( 'Full width', SFP_TKN ),
				'masonry' => __( 'Masonry', SFP_TKN ),
				'list'    => __( 'List', SFP_TKN ),
				'grid'    => __( 'Tiles', SFP_TKN ),
			),
		),
		'wc-shop-sidebar'   => array(
			'id'      => 'wc-shop-sidebar',
			'label'   => __( 'Display sidebar', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-shop-columns',
			'label'   => __( 'Product columns', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'select',
			'default' => 3,
			'choices' => array_combine( range( 1, 5 ), range( 1, 5 ) ),
		),
		array(
			'id'      => 'wc-shop-products',
			'label'   => __( 'Products per page', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'select',
			'default' => 12,
			'choices' => array_combine( range( 1, 50 ), range( 1, 50 ) ),
		),
		array(
			'id'      => 'wc-shop-alignment',
			'label'   => __( 'Product alignment', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'select',
			'choices' => array(
				'center' => __( 'Center', SFP_TKN ),
				'left'   => __( 'Left', SFP_TKN ),
				'right'  => __( 'Right', SFP_TKN ),
			),
		),

		array(
			'id'      => 'shop-header-start-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),

		array(
			'id'      => 'shop-header',
			'label'   => __( 'Shop Hero Type', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'radio',
			'choices' => array(
				''                 => __( 'Default (none)', SFP_TKN ),
				'cat_img'          => __( 'Featured image', SFP_TKN ),
//				'cat_img_parallax' => __( 'Category image - parallax', SFP_TKN ),
				'feat_prods'       => __( 'Featured products slider', SFP_TKN ),
			),
		),

		array(
			'id'      => 'shop-cat-header',
			'label'   => __( 'Product Category Hero Type', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'radio',
			'choices' => array(
				''                 => __( 'Default (none)', SFP_TKN ),
				'cat_img'          => __( 'Category image', SFP_TKN ),
//				'cat_img_parallax' => __( 'Category image - parallax', SFP_TKN ),
				'feat_prods'       => __( 'Featured products slider', SFP_TKN ),
			),
		),

		array(
			'id'          => 'shop-header-height',
			'label'       => __( 'Shop and Category Hero height', SFP_TKN ),
			'section' => 'Shop',
			'type'        => 'range',
			'input_attrs' => array(
				'min'  => 160,
				'max'  => 1000,
				'step' => 5,
			),
		),

		array(
			'id'      => 'shop-header-end-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),

		array(
			'id'      => 'wc-quick-view',
			'label'   => __( 'Quick view product', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-flip-prod-img',
			'label'   => __( 'Flip product images', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-hide-cat-prod-count',
			'label'   => __( 'Hide product count on categories', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-product-results-count',
			'label'   => __( 'Display product results count', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-product-sorting',
			'label'   => __( 'Display product sorting', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-product-image',
			'label'   => __( 'Display product image', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-product-title',
			'label'   => __( 'Display product title', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-sale-flash',
			'label'   => __( 'Display sale flash', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-rating',
			'label'   => __( 'Display rating', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-price',
			'label'   => __( 'Display price', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-display-add-to-cart',
			'label'   => __( 'Display add to cart button', SFP_TKN ),
			'section' => 'Shop',
			'default' => 1,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'hide-wc-breadcrumbs-wc',
			'label'   => __( 'Hide breadcrumbs on WooCommerce pages', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-mob-store-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),
		array(
			'id'      => 'wc-mob-store',
			'label'   => __( 'Enable sweet mobile store styling', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-mob-store-layout',
			'label'   => __( 'Default sweet mobile layout', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'radio',
			'choices' => array(
				''            => __( 'Grid layout', SFP_TKN ),
				'layout-list' => __( 'List layout', SFP_TKN ),
			),
		),
		array(
			'id'      => 'wc-mob-dont-hide-breadcrumbs',
			'label'   => __( "Don't hide breadcrumbs on sweet mobile", SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-infinite-scroll-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),
		array(
			'id'      => 'wc-infinite-scroll',
			'label'   => __( 'Infinite scroll', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-success-color-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),
		array(
			'id'      => 'wc-success-bg-color',
			'label'   => __( 'Success message background color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-success-text-color',
			'label'   => __( 'Success message text color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-info-color-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),
		array(
			'id'      => 'wc-info-bg-color',
			'label'   => __( 'Info message background color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-info-text-color',
			'label'   => __( 'Info message text color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-error-color-sep',
			'section' => 'Shop',
			'type'    => 'sf-divider',
		),
		array(
			'id'      => 'wc-error-bg-color',
			'label'   => __( 'Error message background color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-error-text-color',
			'label'   => __( 'Error message text color', SFP_TKN ),
			'section' => 'Shop',
			'type'    => 'color',
		),

		//Product details
		'wc-product-layout' => array(
			'id'      => 'wc-product-layout',
			'label'   => __( 'Product layout', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'radio',
			'choices' => array(
				''     => __( 'Default', SFP_TKN ),
				'full' => __( 'Full width', SFP_TKN ),
			),
		),

		'wc-product-tabs-layout' => array(
			'id'      => 'wc-product-tabs-layout',
			'label'   => __( 'Product tabs', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'radio',
			'choices' => array(
				''            => __( 'Default', SFP_TKN ),
				'hrzntl-tabs' => __( 'Horizontal tabs', SFP_TKN ),
				'accordion'   => __( 'Accordion', SFP_TKN ),
			),
		),

		array(
			'id'      => 'wc-product-style',
			'label'   => __( 'Product Style', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'radio',
			'choices' => array(
				''                    => __( 'Default', SFP_TKN ),
				'full-image'          => __( 'Full width Product image', SFP_TKN ),
				'full-gallery'        => __( 'Full width Product Gallery', SFP_TKN ),
				'full-gallery-slider' => __( 'Full width Product Images Slider', SFP_TKN ),
				'hero'                => __( 'Hero Product', SFP_TKN ),
			),
		),

		array(
			'id'      => 'wc-prod-sale-style',
			'label'   => __( 'Sale badge style', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'select',
			'choices' => array(
				''        => __( 'Rectangle', SFP_TKN ),
				'circle'  => __( 'Circle', SFP_TKN ),
				'slant-l' => __( 'Slant left', SFP_TKN ),
				'slant-r' => __( 'Slant right', SFP_TKN ),
			),
		),
		array(
			'id'      => 'wc-prod-sale-alignment',
			'label'   => __( 'Sale badge alignment', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'select',
			'choices' => array(
				''      => __( 'Default', SFP_TKN ),
				'left'  => __( 'Left', SFP_TKN ),
				'right' => __( 'Right', SFP_TKN ),
			),
		),
		array(
			'id'      => 'wc-prod-sale-text-color',
			'label'   => __( 'Sale badge text color', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-prod-sale-bg-color',
			'label'   => __( 'Sale badge background color', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'alpha-color',
		),
		array(
			'id'      => 'wc-prod-sale-border-color',
			'label'   => __( 'Sale badge border color', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'color',
		),
		array(
			'id'      => 'hide-wc-breadcrumbs-product',
			'label'   => __( 'Hide breadcrumbs', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),

		array(
			'id'      => 'wc-prod-share-icons',
			'label'   => __( 'Show product sharing icons', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-prod-share-icons-labels',
			'label'   => __( 'Hide product sharing icons labels', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-prod-share-icons-color',
			'label'   => __( 'Product sharing icons color', SFP_TKN ),
			'section' => 'Product Page',
			'type'    => 'color',
		),
		array(
			'id'      => 'wc-product-tabs',
			'label'   => __( 'Display product tabs', SFP_TKN ),
			'default' => true,
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-rel-product',
			'label'   => __( 'Display related products', SFP_TKN ),
			'default' => true,
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-product-meta',
			'label'   => __( 'Display product meta', SFP_TKN ),
			'default' => true,
			'section' => 'Product Page',
			'type'    => 'checkbox',
		),

		// Checkout
		array(
			'id'      => 'hide-wc-breadcrumbs-checkout',
			'label'   => __( 'Hide breadcrumbs on Cart and Checkout pages', SFP_TKN ),
			'section' => 'existing_woocommerce_checkout',
			'priority' => 5,
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'wc-co-distraction-free',
			'label'   => __( 'Enable distraction free Cart and Checkout', SFP_TKN ),
			'section' => 'existing_woocommerce_checkout',
			'type'    => 'checkbox',
			'priority' => 5,
		),

		// Mobile
		array(
			'id'       => 'mob-logo',
			'label'    => __( 'Mobile Logo', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'image',
			'priority' => 5,
		),
		array(
			'id'       => 'mob-menu-label',
			'label'    => __( 'Mobile menu icon label', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'text',
			'default' => 'Menu',
			'priority' => 10,
		),
		array(
			'id'       => 'mob-search',
			'label'    => __( 'Display search on mobile menu', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'checkbox',
			'priority' => 20,
		),
		array(
			'id'       => 'mob-menu-icon-color',
			'label'    => __( 'Menu icon color', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'color',
			'default'  => '#000',
			'priority' => 25,
		),
		array(
			'id'       => 'mob-menu-bg-color',
			'label'    => __( 'Background color', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'color',
			'default'  => '#fff',
			'priority' => 30,
		),
		array(
			'id'       => 'mob-menu-font-color',
			'label'    => __( 'Font color', SFP_TKN ),
			'section'  => 'Mobile menu',
			'type'     => 'color',
			'priority' => 35,
		),
		array(
			'id'      => 'mob-footer-bg-color',
			'label'   => __( 'Background color', SFP_TKN ),
			'section' => 'Mobile Fixed Footer',
			'type'    => 'color',
			'default' => '#fff',
		),
		array(
			'id'      => 'mob-footer-font-color',
			'label'   => __( 'Icons/Font color', SFP_TKN ),
			'section' => 'Mobile Fixed Footer',
			'type'    => 'color',
		),
		array(
			'id'      => 'mob-footer-hide-myac',
			'label'   => __( 'Hide my account icon', SFP_TKN ),
			'section' => 'Mobile Fixed Footer',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'mob-footer-hide-search',
			'label'   => __( 'Hide search icon', SFP_TKN ),
			'section' => 'Mobile Fixed Footer',
			'type'    => 'checkbox',
		),
		array(
			'id'      => 'mob-footer-hide-cart',
			'label'   => __( 'Hide cart icon', SFP_TKN ),
			'section' => 'Mobile Fixed Footer',
			'type'    => 'checkbox',
		),
	);

	return apply_filters( 'storefront_pro_fields', $fields );
}

add_filter( 'storefront_pro_fields', 'storefront_pro_fancy_header' );

function storefront_pro_fancy_header( $fields ) {
	// Header type
	$fields[] = array(
		'id'       => 'home-header',
		'label'    => __( 'Homepage hero', SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'radio',
		'choices'  => array(
			''         => __( 'Default', SFP_TKN ),
			'image'    => __( 'Featured Image', SFP_TKN ),
			'slider'   => __( 'Slider', SFP_TKN ),
			'video'    => __( 'Video', SFP_TKN ),
			'products' => __( 'Featured products', SFP_TKN ),
		),
		'priority' => 5,
	);
	$fields[] = array(
		'id'          => 'home-header-height',
		'label'       => __( 'Home header height', SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => [
			'min'  => 160,
			'max'  => 1000,
			'step' => 5,
		],
		'priority'    => 7,
	);

	$fields[] = array(
		'id'          => 'home-header-mobile-height',
		'label'       => __( 'Home header mobile height', SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => [
			'min'  => 160,
			'max'  => 1000,
			'step' => 5,
		],
		'priority'    => 8,
	);


	$fields[] = array(
		'id'       => 'header-hero-title-font',
		'label'    => __( 'Header hero title font', SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'font',
		'priority' => 14,
	);

	$fields[] = array(
		'id'          => 'header-hero-title-size',
		'label'       => __( 'Header hero title size', SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 20,
			'max'  => 160,
			'step' => 5,
		),
		'priority'    => 16,
	);

	$fields[] = array(
		'id'          => 'header-hero-text-size',
		'label'       => __( 'Header hero text size', SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 70,
			'step' => 2,
		),
		'priority'    => 18,
	);

	$fields[] = array(
		'id'       => 'home-header-position',
		'label'    => __( 'Home hero position', SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'radio',
		'choices'  => array(
			''             => __( 'Behind header', SFP_TKN ),
			'below-header' => __( 'Below header', SFP_TKN ),
		),
		'priority' => 8,
	);

	$fields[] = array(
		'id'       => 'sitewide-header',
		'label'    => __( 'Sitewide hero', SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'radio',
		'choices'  => array(
			''      => __( 'Default', SFP_TKN ),
			'image' => __( 'Featured Image', SFP_TKN ),
		),
		'priority' => 10,
	);

	// region Header media
	$fields[] = array(
		'id'          => "sitewide-header-height",
		'label'       => __( "Featured image height", SFP_TKN ),
		'description' => __( "Sitewide header Featured image height.", SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => [
			'min'  => 160,
			'max'  => 1000,
			'step' => 5,
		],
		'priority'    => 25,
	);
	$fields[] = array(
		'id'          => "sitewide-header-mobile-height",
		'label'       => __( "Featured image mobile height", SFP_TKN ),
		'description' => __( "Sitewide header Featured image height for mobile.", SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'range',
		'input_attrs' => [
			'min'  => 160,
			'max'  => 1000,
			'step' => 5,
		],
		'priority'    => 25,
	);

	$fields[] = array(
		'id'          => "home-video-url",
		'label'       => __( "Video (full screen) URL", SFP_TKN ),
		'description' => __( "Put in Youtube/Vimeo video URL here.", SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'url',
		'priority'    => 25,
	);

	$fields[] = array(
		'id'       => "home-video-divider",
		'section'  => 'Header type',
		'type'     => 'sf-divider',
		'priority' => 30,
	);

	$fields[] = array(
		'id'       => "header-slides-heading",
		'label'    => __( "Slider (full screen)", SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'sf-heading',
		'priority' => 35,
	);

	$fields[] = array(
		'id'          => "header-slider-content-overlay",
		'label'       => __( "Slide content black overlay", SFP_TKN ),
		'description' => __( "Transparent black background to improve readability.", SFP_TKN ),
		'section'     => 'Header type',
		'type'        => 'checkbox',
		'priority'    => 43,
	);

	$fields[] = array(
		'id'       => "header-slides-divider",
		'section'  => 'Header type',
		'type'     => 'sf-divider',
		'priority' => 40,
	);

	$fields[] = array(
		'id'       => "header-slider-cta-btn-link",
		'label'    => __( "Call to action button link", SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'url',
		'priority' => 40,
	);

	$fields[] = array(
		'id'       => "header-slider-cta-btn-text",
		'label'    => __( "Call to action button text", SFP_TKN ),
		'section'  => 'Header type',
		'type'     => 'url',
		'priority' => 45,
	);

	$fields[] = array(
		'id'       => "header-slides-cta-divider",
		'section'  => 'Header type',
		'type'     => 'sf-divider',
		'priority' => 50,
	);

	// Slides
	for ( $i = 1; $i <= 5; $i ++ ) {
		$fields[] = array(
			'id'       => "header-slide-$i-image",
			'label'    => __( "Slide $i image", SFP_TKN ),
			'section'  => 'Header type',
			'type'     => 'image',
			'priority' => 35 + 35 * $i,
		);

		$fields[] = array(
			'id'       => "header-slide-$i-title",
			'label'    => __( "Slide $i title", SFP_TKN ),
			'section'  => 'Header type',
			'type'     => 'text',
			'priority' => 35 + 35 * $i + 5,
		);

		$fields[] = array(
			'id'       => "header-slide-$i-text",
			'label'    => __( "Slide $i text", SFP_TKN ),
			'section'  => 'Header type',
			'type'     => 'textarea',
			'priority' => 35 + 35 * $i + 10,
		);

		$fields[] = array(
			'id'       => "header-slide-$i-cta-text",
			'label'    => __( "Slide $i button text", SFP_TKN ),
			'section'  => 'Header type',
			'type'     => 'text',
			'priority' => 35 + 35 * $i + 15,
		);

		$fields[] = array(
			'id'       => "header-slide-$i-cta-link",
			'label'    => __( "Slide $i button link", SFP_TKN ),
			'section'  => 'Header type',
			'type'     => 'url',
			'priority' => 35 + 35 * $i + 20,
		);

		$fields[] = array(
			'id'       => "header-slide-$i-divider",
			'section'  => 'Header type',
			'type'     => 'sf-divider',
			'priority' => 35 + 35 * $i + 25,
		);

		$fields[] = array(
			'id'          => "lazy-load-fa5",
			'label'       => __( "Lazy load Font Awesome 5", SFP_TKN ),
			'description' => __( "This will load icons after page is loaded to speed up loading page.", SFP_TKN ),
			'section'     => 'Performance',
			'type'        => 'checkbox',
			'priority'    => 43,
		);
	}

	// endregion

	return $fields;
}

add_filter( 'storefront-pro-section-header-type-filter-args', 'storefront_pro_fancy_header_sections' );
add_filter( 'storefront-pro-section-header-media-filter-args', 'storefront_pro_fancy_header_sections' );

function storefront_pro_fancy_header_sections( $args ) {
	$args['panel']    = 'sf-pro-header';
	$args['priority'] = 8;

	return $args;
}

function storefront_pro_google_fonts( $value ) {
	global $pootlepb_font;

	$font_faces = $pootlepb_font;
	$test_cases = array();

	if ( function_exists( 'wf_get_system_fonts_test_cases' ) ) {
		$test_cases = wf_get_system_fonts_test_cases();
	}

	$html = '';
	foreach ( $font_faces as $k => $v ) {

		$selected = '';

		// If one of the fonts requires a test case, use that value. Otherwise, use the key as the test case.
		if ( in_array( $k, array_keys( $test_cases ) ) ) {
			$value_to_test = $test_cases[ $k ];
		} else {
			$value_to_test = $k;
		}

		if ( pootlepb_test_typeface_against_test_case( $value, $value_to_test ) ) {
			$selected = ' selected="selected"';
		}
		$html .= '<option value="' . esc_attr( $k ) . '" ' . $selected . '>' . esc_html( $v ) . '</option>' . "\n";
	}

	return $html;

}

if ( ! function_exists( 'storefront_site_branding' ) ) {
	/**
	 * Display Site Branding
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_site_branding() {
		$desktop_logo = get_sfp_mod( 'logo' );
		if ( $desktop_logo ) {
			$mob_logo = get_sfp_mod( 'mob-logo' );
			if ( ! $mob_logo ) $mob_logo = $desktop_logo;
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-desktop site-logo-link" rel="home">
				<img src="<?php echo $desktop_logo; ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"/>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-mobile site-logo-link" rel="home">
				<img src="<?php echo $mob_logo; ?>" alt="<?php echo get_bloginfo( 'name' ); ?>"/>

			</a>
			<?php

		} else if ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			jetpack_the_site_logo();
		} else { ?>
			<div class="site-branding">
				<h1 class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php if ( '' != get_bloginfo( 'description' ) ) { ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				<?php } ?>
			</div>
		<?php }
		?>
		<a class="menu-toggle"
			 aria-controls="primary-navigation" aria-expanded="false">
			<span><?php echo esc_attr( apply_filters( 'storefront_menu_toggle_text', get_sfp_mod( 'mob-menu-label', 'Menu' ) ) ); ?></span>
		</a>
		<?php
	}
}

function storefront_pro_menu_hamburger() {
	$nav_style = get_theme_mod( 'storefront-pro-nav-style' );
	if ( strpos( $nav_style, 'hamburger' ) ) {
		$label = get_theme_mod( 'storefront-pro-pri-nav-label' );
		$class = $label ? 'header-toggle has-label' : 'header-toggle';
		?>
		<div class="overlay hamburger-overlay"></div>
		<a class="<?php echo $class; ?>" aria-controls="header" aria-expanded="false"><span><?php echo $label; ?></span></a>
		<?php
		if ( strpos( $nav_style, 'lv-full-width' ) ) {
			echo '<div class="full-width-hamburger-wrap" style="display: none">';
		}
	}
}

function storefront_pro_menu_hamburger_close_full_width() {
	if ( strpos( get_theme_mod( 'storefront-pro-nav-style' ), 'lv-full-width' ) ) {
		echo '</div>';
	}
}

if ( ! function_exists( 'storefront_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation"
				 aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
			<div class="sfp-nav-search" style="display: none;">
				<?php echo sfp_search_form(); ?>
				<a class='sfp-nav-search-close'><i class='fas fa-times'></i></a>
			</div><!-- .sfp-nav-search -->
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container_class' => 'primary-navigation',
				)
			);

			?>
			<div class="handheld-navigation-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'handheld',
						'container_class' => 'handheld-navigation',
					)
				) ?>
			</div>
			<?php
			do_action( 'storefront_pro_in_nav' );
			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'storefront_sanitize_layout' ) ) {
	/**
	 * Sanitizes the layout setting
	 *
	 * Ensures only array keys matching the original settings specified in add_control() are valid
	 *
	 * @since 1.0.3
	 */
	function sf_pro_sanitize_layout( $input ) {
		$valid = array(
			'right',
			'left',
			'full',
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'woocommerce_template_loop_rating' ) ) {

	/**
	 * Display the average rating in the loop
	 *
	 * @subpackage    Loop
	 */
	function woocommerce_template_loop_rating() {
		echo '<div>';
		wc_get_template( 'loop/rating.php' );
		echo '</div>';
	}
}

if ( ! function_exists( 'storefront_post_thumbnail' ) ) {
	/**
	 * Display post thumbnail
	 * @var $size string Thumbnail size. thumbnail|medium|large|full|$custom
	 * @var $args array Arguments passed to the_post_thumbnail()
	 * @uses has_post_thumbnail()
	 *
	 * @param string $size
	 *
	 * @since 1.5.0
	 */
	function storefront_post_thumbnail( $size, $args = array( 'itemprop' => 'image' ) ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $size, $args );
		}
	}
}

function sfp_search_form() {
	$post_type_fields = '';
	$search_pt        = explode( ',', get_theme_mod( SFP_TKN . '-search-post_type', 'post,page' ) );
	if ( 1 < count( $search_pt ) ) {
		foreach ( $search_pt as $pt ) {
			$post_type_fields .= "<input type='hidden' name='post_type[]' value='{$pt}' />";
		}
	} else {
		$post_type_fields = "<input type='hidden' name='post_type' value='{$search_pt[0]}' />";
	}

	$home_url    = esc_url( home_url( '/' ) );
	$placeholder = esc_attr_x( 'Search&hellip;', 'placeholder', SFP_TKN );
	$search_for  = esc_attr_x( 'Search for:', 'label', SFP_TKN );
	$submit      = esc_attr_x( 'Search', 'submit button', SFP_TKN );
	$qry         = get_search_query();

	/**
	 * Filter storefront pro search form html
	 *
	 * @param string $html Search form HTML
	 * @param array $args
	 *    * @param string $home_url
	 *    * @param string $search_for
	 *    * @param string $placeholder
	 *    * @param string $qry
	 *    * @param string $submit
	 *    * @param string $post_type_fields
	 */
	return apply_filters( 'sfp_search_form_html', "
	<form role='search' class='search-form' action='$home_url'>
		<label class='screen-reader-text' for='s'>$search_for</label>
		<input type='search' class='search-field' placeholder='$placeholder' value='$qry' name='s' title='$search_for' />
		<input type='submit' value='$submit' />
		$post_type_fields
	</form>
", [
		'home_url'         => $home_url,
		'search_for'       => $search_for,
		'placeholder'      => $placeholder,
		'qry'              => $qry,
		'submit'           => $submit,
		'post_type_fields' => $post_type_fields,
	] );
}

add_filter( 'storefront_page_customizer', function ( $f ) {

	if ( filter_input( INPUT_GET, 'post_id' ) ) {
		$post_id = filter_input( INPUT_GET, 'post_id' );
	} elseif ( ! empty( $_COOKIE['shramee_post_meta_customize_setting_post_id'] ) ) {
		$post_id = $_COOKIE['shramee_post_meta_customize_setting_post_id'];
	} else {
		return $f;
	}

	$f['flush-content-with-header'] = array(
		'id'      => 'flush-content-with-header',
		'label'   => __( 'Flush content with header', SFP_TKN ),
		'section' => 'Content',
		'type'    => 'checkbox',
		'default' => '',
	);
	$f['header-over-content']       = array(
		'id'      => 'header-over-content',
		'label'   => __( 'Make header come over the content', SFP_TKN ),
		'section' => 'Content',
		'type'    => 'checkbox',
		'default' => '',
	);
	$f['header-over-content-color'] = array(
		'id'      => 'header-over-content-color',
		'label'   => __( 'Color of header when it is over the content', SFP_TKN ),
		'section' => 'Content',
		'type'    => 'lib_color',
		'default' => '',
	);

	if ( get_post_type( $post_id ) == 'product' ) {
		$f['wc-product-style'] = array(
			'id'      => 'wc-product-style',
			'label'   => __( 'Product Style', SFP_TKN ),
			'section' => 'Content',
			'type'    => 'radio',
			'choices' => array(
				''                    => __( 'Default', SFP_TKN ),
				'full-image'          => __( 'Full width Product image', SFP_TKN ),
				'full-gallery'        => __( 'Full width Product Gallery', SFP_TKN ),
				'full-gallery-slider' => __( 'Full width Product Images Slider', SFP_TKN ),
				'hero'                => __( 'Hero Product', SFP_TKN ),
			),
			'default' => '',
		);
	}

	return $f;
} );

function get_sfp_mod( $id, $default = null ) {
	if ( Storefront_Pro::instance()->public ) {
		return Storefront_Pro::instance()->public->get( $id, $default );
	} else {
		return $default;
	}
}

add_filter( 'storefront_setting_default_values', 'sfp_storefront_defaults' );

function sfp_storefront_defaults( $args ) {
	$fields = storefront_pro_fields();

	$args['storefront_header_background_color'] = '#fff';
//	$args['storefront_heading_color']               = '';
//	$args['storefront_text_color']                  = '';
//	$args['storefront_accent_color']                = '';
//	$args['storefront_header_text_color']           = '';
//	$args['storefront_header_link_color']           = '';
	return $args;
}
