<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\Tests\Charts\Gallery\Bar;

use Leg\GCharts\Gallery\Bar\VerticalStackedChart;
use Leg\GCharts\Tests\TestCase;

class VerticalStackedChartTest extends TestCase
{
	public function testSetOptions()
	{
		$chart = new VerticalStackedChart();

		$chart->setOptions(array(
			'stacked_mode' => 'atop',
		));
	}

	public function testStackedMode()
	{
		$chart = new VerticalStackedChart();
		$chart->setStackedMode('atop');
		$this->assertEquals('atop', $chart->getStackedMode());
	}

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionCode    500
	 * @expectedExceptionMessage Unknown chart stacked mode "invalid". Valid stacked mode are "front" and "atop".
	 */
	public function testStackedModeInvalidString()
	{
		$chart = new VerticalStackedChart();
		$chart->setStackedMode('invalid');
	}

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionCode    500
	 * @expectedExceptionMessage A stacked mode must be a string (object given).
	 */
	public function testStackedModeInvalidObject()
	{
		$chart = new VerticalStackedChart();
		$chart->setStackedMode(new \stdClass());
	}

	public function testBuildAtop()
	{
		$chart = new VerticalStackedChart();

		$chart
			->setWidth(200)
			->setHeight(200)
			->setDatas(array(32, 15, 17))
			->setStackedMode('atop');

		parse_str(parse_url($chart->build(), PHP_URL_QUERY), $options);

		// Stacked mode
		$this->assertArrayHasKey('cht', $options);
		$this->assertEquals('bvs', $options['cht']);
	}

	public function testBuildFront()
	{
		$chart = new VerticalStackedChart();

		$chart
			->setWidth(200)
			->setHeight(200)
			->setDatas(array(32, 15, 17))
			->setStackedMode('front');

		parse_str(parse_url($chart->build(), PHP_URL_QUERY), $options);

		// Stacked mode
		$this->assertArrayHasKey('cht', $options);
		$this->assertEquals('bvo', $options['cht']);
	}
}