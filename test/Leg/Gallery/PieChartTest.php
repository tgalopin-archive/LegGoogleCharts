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

use Leg\GCharts\Gallery\PieChart;
use Leg\GCharts\Tests\TestCase;

class PieChartTest extends TestCase
{
	public function testSetOptions()
	{
		$chart = new PieChart();
		$chart->setOptions(array( 'rotation' => 0.628 ));
		$this->assertEquals(0.628, $chart->getRotation());
	}

	public function testRotation()
	{
		$chart = new PieChart();
		$chart->setRotation(1.524);
		$this->assertEquals(1.524, $chart->getRotation());
	}

	public function testBuild()
	{
		$chart = new PieChart();

		$chart
			->setWidth(200)
			->setHeight(200)
			->setDatas(array(32, 15, 17))
			->setRotation(0.628);

		parse_str(parse_url($chart->build(), PHP_URL_QUERY), $options);

		// Type
		$this->assertArrayHasKey('cht', $options);
		$this->assertEquals('p', $options['cht']);

		// Rotation
		$this->assertArrayHasKey('chp', $options);
		$this->assertEquals('0.628', $options['chp']);
	}
}