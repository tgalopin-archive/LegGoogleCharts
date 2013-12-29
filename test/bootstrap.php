<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include __DIR__.'/TestCase.php';

spl_autoload_register(function($class) {
	if (0 === strpos($class, 'Leg\\GCharts\\')) {
		$path = __DIR__.'/../src/'. str_replace('\\', '/', $class) .'.php';

		if (! stream_resolve_include_path($path)) {
			return false;
		}

		require_once $path;
		return true;
	}

	return false;
});