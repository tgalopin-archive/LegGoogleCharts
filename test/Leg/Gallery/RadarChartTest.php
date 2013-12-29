<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\Tests\Charts\Gallery;

use Leg\GCharts\Gallery\RadarChart;
use Leg\GCharts\Tests\TestCase;

class RadarChartTest extends TestCase
{
	public function testBuild()
	{
		$chart = new RadarChart();

		$chart
			->setWidth(200)
			->setHeight(200)
			->setDatas(array(32, 15, 17));

		parse_str(parse_url($chart->build(), PHP_URL_QUERY), $options);

		// Type
		$this->assertArrayHasKey('cht', $options);
		$this->assertEquals('r', $options['cht']);
	}
}