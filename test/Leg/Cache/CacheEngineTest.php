<?php

/*
 * This file is part of the LegGoogleChartsBundle package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GoogleChartsBundle\Tests\Cache;

use Leg\GCharts\Cache\CacheEngine;
use Leg\GCharts\Gallery\PieChart;
use Leg\GCharts\Gallery\BarChart;
use Leg\GCharts\Tests\TestCase;

/**
 * CacheEngine is an engine to cache the charts.
 */
class CacheEngineTest extends TestCase
{
	public function testCreate()
	{
		$cacheEngine = new CacheEngine(__DIR__.'/../objects/public', '/assets/', __DIR__.'/../objects/internal');

		$this->assertGreaterThan(0, strlen($cacheEngine->getInternalCacheDir()));
		$this->assertGreaterThan(0, strlen($cacheEngine->getPublicCacheDir()));
		$this->assertGreaterThan(0, strlen($cacheEngine->getAssetsCacheUrl()));
		$this->assertTrue(file_exists($cacheEngine->getPublicCacheDir()));
		$this->assertTrue(file_exists($cacheEngine->getInternalCacheDir()));

		return $cacheEngine;
	}

	/**
	 * @depends testCreate
	 */
	public function testPut(CacheEngine $cacheEngine)
	{
		$chart = new BarChart();

		$chart->setDatas(array(152, 142, 12));
		$chart->setWidth(200);
		$chart->setHeight(200);
		$chart->setAxis(array('x', 'y'));

		@$cacheEngine->put($chart, 1200);

		$this->assertTrue(file_exists($cacheEngine->getInternalCacheDir().'/3f418487f2'));

		return $cacheEngine;
	}

	/**
	 * @depends testPut
	 */
	public function testHasAndGet(CacheEngine $cacheEngine)
	{
		$chart = new BarChart();

		$chart->setDatas(array(152, 142, 12));
		$chart->setWidth(200);
		$chart->setHeight(200);
		$chart->setAxis(array('x', 'y'));

		$this->assertTrue($cacheEngine->has($chart));
		$this->assertTrue(is_string($cacheEngine->build($chart)));
		$this->assertGreaterThan(0, strlen($cacheEngine->build($chart)));
	}

	/**
	 * @depends testCreate
	 */
	public function testClear(CacheEngine $cacheEngine)
	{
		$chart = new BarChart();

		$chart->setDatas(array(152, 142, 12));
		$chart->setWidth(200);
		$chart->setHeight(200);
		$chart->setAxis(array('x', 'y'));

		@$cacheEngine->put($chart, 1200);
		$cacheEngine->clear($chart);

		$this->assertTrue(! $cacheEngine->has($chart));

		$chart = new PieChart();

		$chart->setDatas(array(152, 142, 12));
		$chart->setWidth(200);
		$chart->setHeight(200);

		@$cacheEngine->put($chart, 1200);
		$cacheEngine->clearAll();

		$this->assertTrue(! $cacheEngine->has($chart));
	}
}