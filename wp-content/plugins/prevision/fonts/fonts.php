<?php
global $WP3S_Prevision;

// Return array of system fonts
function wp3s_prevision_typography_get_os_fonts() {
	$os_fonts = array(
		'Georgia, Times, "Times New Roman", serif' => 'Georgia',
		'"Helvetica Neue", Helvetica, Arial, sans-serif' => 'Helvetica Neue',
		'Arial, Helvetica, sans-serif' => 'Arial', 
		'"Arial Black", Gadget, sans-serif' => 'Arial Black',
		'"Comic Sans MS", cursive' => 'Comic Sans MS',
		'"Courier New", monospace' => 'Courier New',
		'Impact, Charcoal, sans-serif' => 'Impact',
		'"Lucida Console", Monaco, monospace' => 'Lucida Console',
		'"Lucida Sans Unicode", "Lucida Grande", sans-serif' => 'Lucida Sans Unicode',
		'"Palatino Linotype", "Book Antiqua", Palantino, serif' => 'Palatino Linotype',
		'"Times New Roman", Times, serif' => 'Times New Roman',
		'"Trebuchet MS", sans-serif' => 'Trebuchet MS',
		'Verdana, Geneva, sans-serif' => 'Verdana',
		'"Avant Garde", sans-serif' => 'Avant-Garde',
		'Cambria, Georgia, serif' => 'Cambria',  
		'Garamond, "Hoefler Text", "Times New Roman", Times, serif' => 'Garamond',
		'Tahoma, Geneva, sans-serif' => 'Tahoma'
	);
	
	return $os_fonts;
}

function wp3s_prevision_typography_get_google_fonts() {
	$google_fonts = array(
		// sans-serif (162)
		'ABeeZee, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'ABeeZee',
		'Abel, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Abel',
		'Aclonica, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Aclonica',
		'Acme, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Acme',
		'Actor, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Actor',
		'"Advent Pro", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Advent Pro',
		'Aldrich, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Aldrich',
		'Alef, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Alef',
		'Allerta, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Allerta',
		'"Allerta Stencil", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Allerta Stencil',
		'Amaranth, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Amaranth',
		'Anaheim, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Anaheim',
		'Andika, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Andika',
		'"Anonymous Pro, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Anonymous Pro',
		'Antic, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Antic',
		'Anton, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Anton',
		'"Archivo Black", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Archivo Black',
		'"Archivo Narrow", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Archivo Narrow',
		'Arimo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Arimo',
		'Armata, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Armata',
		'Asap, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Asap',
		'Asul, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Asul',
		'"Average Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Average Sans',
		'Basic, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Basic',
		'Belleza, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Belleza',
		'BenchNine, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'BenchNine',
		'"Bubbler One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Bubbler One',
		'Cabin, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cabin',
		'"Cabin Condensed", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cabin Condensed',
		'Cagliostro, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cagliostro',
		'Candal, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Candal',
		'Cantarell, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cantarell',
		'"Cantora One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cantora One',
		'Capriola, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Capriola',
		'Carme, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Carme',
		'"Carrois Gothic", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Carrois Gothic',
		'"Carrois Gothic SC", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Carrois Gothic',
		'"Chau Philomene One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Chau Philomene One',
		'Chivo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Chivo',
		'"Coda Caption", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Coda Caption',
		'Convergence, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Convergence',
		'Cousine, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cousine',
		'Cuprum, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Cuprum',
		'"Days One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Days One',
		'"Denk One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Denk One',
		'"Didact Gothic", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Didact Gothic',
		'"Doppio One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Doppio One',
		'Dorsa, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Dorsa',
		'Dosis, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Dosis',
		'"Droid Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Droid Sans',
		'"Droid Sans Mono", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Droid Sans Mono',
		'"Duru Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Duru Sans',
		'Economica, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Economica',
		'Electrolize, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Electrolize',
		'Englebert, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Englebert',
		'Exo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Exo',
		'Federo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Federo',
		'"Fjalla One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Fjalla One',
		'"Francois One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Francois One',
		'Fresca, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Frasca',
		'Gafata, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Gafata',
		'Galdeano, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Galdeano',
		'Geo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Geo',
		'Gudea, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Gudea',
		'"Hammersmith One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Hammersmith One',
		'Homenaje, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Homenaje',
		'Imprima, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Imprima',
		'Inconsolata, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Inconsolata',
		'Inder, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Inder',
		'"Istok Web", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Istok Web',
		'"Jockey One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Jockey One',
		'"Josefin Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Josefin Sans',
		'"Julius Sans One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Julius Sans One',
		'Jura, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Jura',
		'Karla, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Karla',
		'"Kite One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Kite One',
		'"Krona One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Krona One',
		'Lato, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Lato',
		'Lekton, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Lekton',
		'Magra, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Magra',
		'Mako, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Mako',
		'Marmelad, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Marmelad',
		'Marvel, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Marvel',
		'"Maven Pro", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Maven Pro',
		'"Merriweather Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Merriweather Sans',
		'Metrophobic, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Metrophobic',
		'Michroma, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Michroma',
		'Molengo, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Molengo',
		'Monda, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Monda',
		'Montserrat, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Montserrat',
		'"Montserrat Alternates", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Montserrat Alternates',
		'"Montserrat Subrayada", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Montserrat Subrayada',
		'"Mouse Memoirs", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Mouse Memoirs',
		'Muli, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Muli',
		'"News Cycle", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'News Cycle',
		'Nobile, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Nobile',
		'"Noto Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Noto Sans',
		'Numans, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Numans',
		'Nunito, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Nunito',
		
		'"Open Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Open Sans',
		
		'"Open Sans Condensed", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Open Sans Condenses',
		'Orbitron, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Orbitron',
		'Orienta, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Orienta',
		'Oswald, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Oswald',
		'Oxygen, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Oxygen',
		'"Oxygen Mono", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Oxygen Mono',
		'"PT Mono", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'PT Mono',
		'"PT Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'PT Sans',
		'"PT Sans Caption", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'PT Sans Caption',
		'"PT Sans Narrow", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'PT Sans Narrow',
		'"Pathway Gothic One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Pathway Gothic One',
		'"Paytone One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Paytone One',
		'Philosopher, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Philosopher',
		'Play, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Play',
		'"Pontano Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Pontano Sans',
		'"Port Lligat Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Port Lligat Sans',
		'Puritan, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Puritan',
		'Quantico, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Quantica',
		'"Quattrocento Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Quattrocento Sans',
		'Questrial, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Questrial',
		'Quicksand, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Quicksand',
		'Raleway, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Raleway',
		'Rambla, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Rambla',
		'Rationale, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Rationale',
		'Roboto, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Roboto',
		'"Roboto Condensed", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Roboto Condensed',
		'"Ropa Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ropa Sans',
		'Rosario, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Rosario',
		'Ruda, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ruda',
		'Ruluko, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ruluko',
		'"Rum Raisin", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Rum Raisin',
		'"Russo One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Russo One',
		'Scada, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Scada',
		'"Seymour One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Seymour One',
		'Shanti, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Shanti',
		'"Share Tech", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Share Tech',
		'"Share Tech Mono", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Share Tech Mono',
		'Signika, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Signika',
		'"Signika Negative", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Signika Negative',
		'Sintony, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Sintony',
		'"Six Caps", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Six Caps',
		'Snippet, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Snippet',
		'"Source Code Pro", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Source Code Pro',
		'"Source Sans Pro", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Source Sans Pro',
		'Spinnaker, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Spinnaker',
		'Strait, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Strait',
		'Syncopate, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Syncopate',
		'Tauri, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Tauri',
		'Telex, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Telex',
		'"Tenor Sans", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Tenor Sans',
		'"Text Me One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Text Me One',
		'"Titillium Web", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Titillium Web',
		'Ubuntu, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ubuntu',
		'"Ubuntu Condensed", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ubuntu Condensed',
		'"Ubuntu Mono", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Ubuntu Mono',
		'Varela, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Varela',
		'"Varela Round", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Varela Round',
		'Viga, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Viga',
		'Voltaire, "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Voltaire',
		'"Wendy One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Wendy One',
		'"Wire One", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Wire One',
		'"Yanone Kaffeesatz", "Helvetica Neue", Hevetica, Arial, sans-serif' => 'Yanone Kaffeesatz',
		
		// Serif (122)
		'Adamina, Georgia, Times, "Times New Roman", serif' => 'Adamina',
		'Alegreya, Georgia, Times, "Times New Roman", serif' => 'Alegreya',
		'"Alegreya SC", Georgia, Times, "Times New Roman", serif' => 'Alegreya SC',
		'Alice, Georgia, Times, "Times New Roman", serif' => 'Alice',
		'Alike, Georgia, Times, "Times New Roman", serif' => 'Alike',
		'"Alike Angular", Georgia, Times, "Times New Roman", serif' => 'Alike Angular',
		'Almendra, Georgia, Times, "Times New Roman", serif' => 'Almendra',
		'"Almendra SC", Georgia, Times, "Times New Roman", serif' => 'Almendra SC',
		'Amethysta, Georgia, Times, "Times New Roman", serif' => 'Amethysta',
		'Andada, Georgia, Times, "Times New Roman", serif' => 'Andada',
		'"Antic Didone", Georgia, Times, "Times New Roman", serif' => 'Antic Didone',
		'"Antic Slab", Georgia, Times, "Times New Roman", serif' => 'Antic Slab',
		'Arapey, Georgia, Times, "Times New Roman", serif' => 'Arapey',
		'"Arbutus Slab", Georgia, Times, "Times New Roman", serif' => 'Arbutus Slab',
		'Artifika, Georgia, Times, "Times New Roman", serif' => 'Artifika',
		'Arvo, Georgia, Times, "Times New Roman", serif' => 'Arvo',
		'Average, Georgia, Times, "Times New Roman", serif' => 'Average',
		'Balthazar, Georgia, Times, "Times New Roman", serif' => 'Balthazar',
		'Belgrano, Georgia, Times, "Times New Roman", serif' => 'Belgrano',
		'Bentham, Georgia, Times, "Times New Roman", serif' => 'Bentham',
		'Bitter, Georgia, Times, "Times New Roman", serif' => 'Bitter',
		'Brawler, Georgia, Times, "Times New Roman", serif' => 'Brawler',
		'"Bree Serif", Georgia, Times, "Times New Roman", serif' => 'Bree Serif',
		'Buenard, Georgia, Times, "Times New Roman", serif' => 'Buenard',
		'Cambo, Georgia, Times, "Times New Roman", serif' => 'Cambo',
		'"Cantata One", Georgia, Times, "Times New Roman", serif' => 'Cantata One',
		'Cardo, Georgia, Times, "Times New Roman", serif' => 'Cardo',
		'Caudex, Georgia, Times, "Times New Roman", serif' => 'Caudex',
		'Cinzel, Georgia, Times, "Times New Roman", serif' => 'Cinzel',
		'Copse, Georgia, Times, "Times New Roman", serif' => 'Copse',
		'Coustard, Georgia, Times, "Times New Roman", serif' => 'Coustard',
		'"Crete Round", Georgia, Times, "Times New Roman", serif' => 'Crete Round',
		'"Crimson Text", Georgia, Times, "Times New Roman", serif' => 'Crimson Text',
		'Cutive, Georgia, Times, "Times New Roman", serif' => 'Cutive',
		'"Cutive Mono", Georgia, Times, "Times New Roman", serif' => 'Cutive Mono',
		'"Della Respira", Georgia, Times, "Times New Roman", serif' => 'Della Respira',
		'Domine, Georgia, Times, "Times New Roman", serif' => 'Domine',
		'"Donegal One", Georgia, Times, "Times New Roman", serif' => 'Donegal One',
		'"Droid Serif", Georgia, Times, "Times New Roman", serif' => 'Droid Serif',
		'"EB Garamond", Georgia, Times, "Times New Roman", serif' => 'EB Garamond',
		'Enriqueta, Georgia, Times, "Times New Roman", serif' => 'Enriqueta',
		'Esteban, Georgia, Times, "Times New Roman", serif' => 'Esteban',
		'"Fanwood Text", Georgia, Times, "Times New Roman", serif' => 'Fanwood Text',
		'"Fauna One", Georgia, Times, "Times New Roman", serif' => 'Fauna One',
		'Fenix, Georgia, Times, "Times New Roman", serif' => 'Fenix',
		'"Fjord One", Georgia, Times, "Times New Roman", serif' => 'Fjord One',
		'Gabriela, Georgia, Times, "Times New Roman", serif' => 'Gabriela',
		'"Gentium Basic", Georgia, Times, "Times New Roman", serif' => 'Gentium Basic',
		'"Gentium Book Basic", Georgia, Times, "Times New Roman", serif' => 'Gentium Book Basic',
		'"Gilda Display", Georgia, Times, "Times New Roman", serif' => 'Gilda Display',
		'Glegoo, Georgia, Times, "Times New Roman", serif' => 'Glegoo',
		'"Goudy Bookletter 1911", Georgia, Times, "Times New Roman", serif' => 'Goudy Bookletter 1911',
		'Habibi, Georgia, Times, "Times New Roman", serif' => 'Habibi',
		'"Headland One", Georgia, Times, "Times New Roman", serif' => 'Headland One',
		'"Holtwood One SC", Georgia, Times, "Times New Roman", serif' => 'Holtwood One SC',
		'"IM Fell DW Pica", Georgia, Times, "Times New Roman", serif' => 'IM Fell DW Pica',
		'"IM Fell DW Pica SC", Georgia, Times, "Times New Roman", serif' => 'IM Fell DW Pica SC',
		'"IM Fell Double Pica", Georgia, Times, "Times New Roman", serif' => 'IM Fell Double Pica',
		'"IM Fell Double Pica SC", Georgia, Times, "Times New Roman", serif' => 'IM Fell Double Pica SC',
		'"IM Fell English", Georgia, Times, "Times New Roman", serif' => 'IM Fell English',
		'"IM Fell English SC", Georgia, Times, "Times New Roman", serif' => 'IM Fell English SC',
		'"IM Fell French Canon", Georgia, Times, "Times New Roman", serif' => 'IM Fell French Canon',
		'"IM Fell French Canon SC", Georgia, Times, "Times New Roman", serif' => 'IM Fell French Canon SC',
		'"IM Fell Great Primer", Georgia, Times, "Times New Roman", serif' => 'IM Fell Great Primer',
		'"IM Fell Great Primer SC", Georgia, Times, "Times New Roman", serif' => 'IM Fell Great Primer SC',
		'Inika, Georgia, Times, "Times New Roman", serif' => 'Inika',
		'Italiana, Georgia, Times, "Times New Roman", serif' => 'Italiana',
		'"Jacques Francois", Georgia, Times, "Times New Roman", serif' => 'Jacques Fancois',
		'"Josefin Slab", Georgia, Times, "Times New Roman", serif' => 'Josefin Slab',
		'Judson, Georgia, Times, "Times New Roman", serif' => 'Judson',
		'Junge, Georgia, Times, "Times New Roman", serif' => 'Junge',
		'Kameron, Georgia, Times, "Times New Roman", serif' => 'Kameron',
		'"Kotta One", Georgia, Times, "Times New Roman", serif' => 'Kotta One',
		'Kreon, Georgia, Times, "Times New Roman", serif' => 'Kreon',
		'Ledger, Georgia, Times, "Times New Roman", serif' => 'Ledger',
		'"Libre Baskerville", Georgia, Times, "Times New Roman", serif' => 'Libre Baskerville',
		'"Linden Hill", Georgia, Times, "Times New Roman", serif' => 'Linden Hill',
		'Lora, Georgia, Times, "Times New Roman", serif' => 'Lora',
		'Lusitana, Georgia, Times, "Times New Roman", serif' => 'Lusitana',
		'Lustria, Georgia, Times, "Times New Roman", serif' => 'Lustria',
		'Marcellus, Georgia, Times, "Times New Roman", serif' => 'Marcellus',
		'"Marcellus SC", Georgia, Times, "Times New Roman", serif' => 'Marcellus SC',
		'"Marko One", Georgia, Times, "Times New Roman", serif' => 'Marko One',
		'Mate, Georgia, Times, "Times New Roman", serif' => 'Mate',
		'"Mate SC", Georgia, Times, "Times New Roman", serif' => 'Mate SC',
		'Merriweather, Georgia, Times, "Times New Roman", serif' => 'Merriweather',
		'Montaga, Georgia, Times, "Times New Roman", serif' => 'Montaga',
		'Neuton, Georgia, Times, "Times New Roman", serif' => 'Neuton',
		'"Noticia Text", Georgia, Times, "Times New Roman", serif' => 'Noticia Text',
		'"Noto Serif", Georgia, Times, "Times New Roman", serif' => 'Noto Serif',
		'"Old Standard TT", Georgia, Times, "Times New Roman", serif' => 'Old Standard TT',
		'Oranienbaum, Georgia, Times, "Times New Roman", serif' => 'Oranienbaum',
		'Ovo, Georgia, Times, "Times New Roman", serif' => 'Ovo',
		'"PT Serif", Georgia, Times, "Times New Roman", serif' => 'PT Serif',
		'"PT Serif Caption", Georgia, Times, "Times New Roman", serif' => 'PT Serif Caption',
		'Petrona, Georgia, Times, "Times New Roman", serif' => 'Petrona',
		'"Playfair Display", Georgia, Times, "Times New Roman", serif' => 'Playfair Display',
		'"Playfair Display SC", Georgia, Times, "Times New Roman", serif' => 'Playfair Display SC',
		'Podkova, Georgia, Times, "Times New Roman", serif' => 'Podkova',
		'Poly, Georgia, Times, "Times New Roman", serif' => 'Poly',
		'"Port Lligat Slab", Georgia, Times, "Times New Roman", serif' => 'Port Lligat Slab',
		'Prata, Georgia, Times, "Times New Roman", serif' => 'Prata',
		'Prociono, Georgia, Times, "Times New Roman", serif' => 'Prociono',
		'Quando, Georgia, Times, "Times New Roman", serif' => 'Quando',
		'Quattrocento, Georgia, Times, "Times New Roman", serif' => 'Quattrocento',
		'Radley, Georgia, Times, "Times New Roman", serif' => 'Radley',
		'"Roboto Slab", Georgia, Times, "Times New Roman", serif' => 'Roboto Slab',
		'Rokkitt, Georgia, Times, "Times New Roman", serif' => 'Rokkitt',
		'Rosarivo, Georgia, Times, "Times New Roman", serif' => 'Rosarivo',
		'Rufina, Georgia, Times, "Times New Roman", serif' => 'Rufina',
		'Sanchez, Georgia, Times, "Times New Roman", serif' => 'Sanchez',
		'"Sorts Mill Goudy", Georgia, Times, "Times New Roman", serif' => 'Sorts Mill Goudy',
		'Stoke, Georgia, Times, "Times New Roman", serif' => 'Stoke',
		'Tienne, Georgia, Times, "Times New Roman", serif' => 'Tienne',
		'Tinos, Georgia, Times, "Times New Roman", serif' => 'Tinos',
		'Trocchi, Georgia, Times, "Times New Roman", serif' => 'Trocchi',
		'Trykker, Georgia, Times, "Times New Roman", serif' => 'Trykker',
		'Ultra, Georgia, Times, "Times New Roman", serif' => 'Ultra',
		'Unna, Georgia, Times, "Times New Roman", serif' => 'Unna',
		'Vidaloka, Georgia, Times, "Times New Roman", serif' => 'Vidaloka',
		'Volkhov, Georgia, Times, "Times New Roman", serif' => 'Volkhov',
		'Vollkorn, Georgia, Times, "Times New Roman", serif' => 'Vollkorn'
	);
	
	return $google_fonts;
}

function wp3s_prevision_typography_google_fonts() {
	global $WP3S_Prevision;
	
	$r_font = '';
	
	$all_google_fonts = array_keys(wp3s_prevision_typography_get_google_fonts());
	$primary_font = stripslashes($WP3S_Prevision->_settings['wp3s_prevision_primary_font']);
	
	//echo $primary_font . '<br/>';
	//$secondary_font =$WP3S_Prevision->_settings['wp3s_prevision_secondary_font'];
	$selected_fonts = array(
		$primary_font
		//$secondary_font
	);
	$selected_fonts = array_unique($selected_fonts);
	
	foreach ($selected_fonts as $font) {
		if (in_array($font, $all_google_fonts)) {
			$r_font .= wp3s_prevision_typography_enqueue_google_font($font);
		}
	}
	return $r_font;
}
	
function wp3s_prevision_typography_enqueue_google_font($font) {
	$font = explode(',', $font);
	$font = $font[0];
	//$font_types = $font[1];
	
	$font = str_replace(" ", "+", $font);
	$font = str_replace('"', '', $font);
	//echo $font . '<br/>';
	//wp_enqueue_style("wp3s_prevision_typography_$font", "http://fonts.googleapis.com/css?family=$font:300,400,400:italic,500,600,700,800", false, null, 'all');
	
	return $font;
}

function wp3s_prevision_typography_styles() {
	global $WP3S_Prevision;
	
	$output = '';
	$input = '';
	
	if ($WP3S_Prevision->_settings['wp3s_prevision_primary_font']) {
		$input = $WP3S_Prevision->_settings['wp3s_prevision_primary_font'];
		$output .= wp3s_prevision_typography_font_styles($WP3S_Prevision->_settings['wp3s_prevision_primary_font'], 'html, body.wp3s_prevision, #countdown_container h1, #countdown_timer li em, #countdown_timer li');
	}
	
	/*if (wp3s_option('secondary_font')) {
		$input = wp3s_option('secondary_font');
		$output .= wp3s_typography_font_styles(wp3s_option('secondary_font'), 'h1, h2, h3, blockquote .quoteauthor, #top-bar, #top-bar .scroll-nav li, .hero-header .logo .name-tag, .hero-header .links ul li, .cta .title, .items-wrap .item .time, team-inner .name, .contact-form input, .contact-form textarea, .contact-form input.error, .contact-form textarea.error, #form-comment h3.reply-title, #form-comment input, #form-comment textarea, #form-comment input.error, #form-comment textarea.error, .search input, .pagination');
	}*/
	
	if ($output != '') {
		$output = "\n<style>\n". stripslashes($output) . "</style>\n";
		echo $output;
	}
}

function wp3s_prevision_typography_font_styles($option, $selectors) {
	$output = $selectors . ' {' . "\n";
	$output .= 'font-family: ' . $option . '!important; ';
	$output .= "\n" . '}';
	$output .= "\n";
	return $output;
}

/**
 * Font Stack based on Google Font's Type
 * Build a font stack based on font and its type - use in CSS
 */
function wp3s_prevision_font_stack( $font ) {

	// Get the default dont stack for each type
	$default_font_stacks = wp3s_prevision_default_font_stacks();

	// Build font stack with Google font as primary
	$available_fonts = wp3s_prevision_google_web_fonts();
	if (!empty( $available_fonts )) {
		$default_font_stack = $default_font_stacks[$available_fonts];
	} else { // if invalid, type use first in list (should be serif)
		$default_font_stack = current( $default_font_stacks );
	}
	$font_stack = "'" . $font . "', " . $default_font_stack;

	return $font_stack;

}