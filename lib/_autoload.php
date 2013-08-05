<?php

/**
 * Autoloader routine
 *
 * @param string Class name
 */
function RecipeParser_Autoload($class_name) {
	if (!class_exists($class_name, false)) {
        $class_file_path = str_replace('_', '/', $class_name) . '.php';
		require($class_file_path);
	}
}

spl_autoload_register('RecipeParser_Autoload');

?>
