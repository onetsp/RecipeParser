<?php

class RecipeSource {

    public static $sources_by_host = array(

		// Food Magazines and TV Shows
		'foodnetwork.com' => 'Food Network',
		'foodtv.ca' => 'Food Network Canada',
		'bbqu.net' => 'Barbecue University',
		'barbecuebible.com' => 'Barbecue Bible',

		// Food web sites
		'allrecipes.com' => 'Allrecipes.com',
		'bhg.com' => 'Better Homes & Gardens',
        'bonappetit.com' => 'Bon Appétit',
		'chow.com' => 'CHOW',
        'cooking.com' => 'Cooking.com',
        'countryliving.com' => 'Country Living',
        'eatingwell.com' => 'EatingWell',
		'epicurious.com' => 'Epicurious',
		'foodandwine.com' => 'Food & Wine Magazine',
		'fooddownunder.com' => 'Food Down Under',
        'geniuskitchen.com' => 'Genius Kitchen',
		'primalgrill.org' => 'Primal Grill',
		'recipezaar.com' => 'Recipezaar',
        'seriouseats.com' => 'Serious Eats',
        'smittenkitchen.com' => 'Smitten Kitchen',
        'tastykitchen.com' => 'Tasty Kitchen',
        'wholefoodsmarket.com' => 'Whole Foods Market',

		// Newspapers
		'bbc.co.uk' => 'BBC Food',
        'bbcgoodfood.com' => 'BBC Good Food',
		'theglobeandmail.com' => 'The Globe and Mail',
		'nytimes.com' => 'The New York Times',
		'bitten.blogs.nytimes.com' => 'Mark Bittman',
		'blog.nola.com' => 'The Times-Picayune / NOLA.com',

		// Food blogs
		'elise.com' => 'Simply Recipes',
        'elanaspantry.com' => 'Elana\'s Pantry',
		'food.realsimple.com' => 'Real Simple',
		'smittenkitchen.com' => 'Smitten Kitchen',
		'seitanismymotor.blogspot.com' => 'Seitan is My Motor',
		'eatmedelicious.com' => 'eat me, delicious',
		'latartinegourmande.com' => 'La Tartine Gourmande',
		'pinoycook.net' => 'Pinoy Cook',
		'veggiemealplans.com' => 'Veggie Meal Plans',
		'yourveganmom.com' => 'Your Vegan Mom',
		'steamykitchen.com' => 'Steamy Kitchen: Modern Asian Cooking',
		'weallcook.blogspot.com' => 'We All Cook',

		// Chefs and Personalities
		'roccodispirito.com' => 'Rocco DiSpirito',

		// Food brands
        'bettycrocker.com' => 'Betty Crocker',
		'pillsbury.com' => 'Pillsbury',

	);

    static public function getSourceNameByUrl($url) {
        $hostname = parse_url($url, PHP_URL_HOST);
        $hostname = preg_replace("/^www\./i", "", $hostname);
        if (isset(self::$sources_by_host[$hostname])) {
            return self::$sources_by_host[$hostname];
        } else {
            return null;
        }
    }

}

