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

use Leg\GCharts\Gallery\BarChart;
use Leg\GCharts\Tests\TestCase;

class BarChartTest extends TestCase
{
	public function testSetOptions()
	{
		$chart = new BarChart();

		$chart->setOptions(array(
			'axis' => array('x', 'y'),
			'bar_width' => 150,
			'bar_spacing' => 10,
			'zero_line' => 0.1,
		));
	}

	public function testAxis()
	{
		$chart = new BarChart();
		$chart->setAxis(array('x', 'y'));
		$this->assertEquals(array('x', 'y'), $chart->getAxis()->toArray());
	}

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionCode    500
	 * @expectedExceptionMessage Unknown visible axe "invalid". Valid axis are x, y, r, t.
	 */
	public function testAxisInvalidString()
	{
		$chart = new BarChart();
		$chart->setAxis(array('invalid'));
	}

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionCode    500
	 * @expectedExceptionMessage A visible axe must be a string (object given)
	 */
	public function testAxisInvalidObject()
	{
		$chart = new BarChart();
		$chart->setAxis(array(new \stdClass()));
	}

	public function testBarWidth()
	{
		$chart = new BarChart();
		$chart->setBarWidth(500);
		$this->assertEquals(500, $chart->getBarWidth());
	}

	public function testBarSpacing()
	{
		$chart = new BarChart();
		$chart->setBarSpacing(500);
		$this->assertEquals(500, $chart->getBarSpacing());
	}

	public function testZeroLine()
	{
		$chart = new BarChart();
		$chart->setZeroLine(0.2);
		$this->assertEquals(0.2, $chart->getZeroLine());
	}

	public function testBuild()
	{
		$chart = new BarChart();

		$chart
			->setWidth(200)
			->setHeight(200)
			->setDatas(array(32, 15, 17))
			->setAxis(array('x', 'y'))
			->setZeroLine(0.2);

		parse_str(parse_url($chart->build(), PHP_URL_QUERY), $options);

		// Type
		$this->assertArrayHasKey('cht', $options);
		$this->assertEquals('bhs', $options['cht']);

		// Axis
		$this->assertArrayHasKey('chxt', $options);
		$this->assertEquals('x,y', $options['chxt']);

		// Zero line
		$this->assertArrayHasKey('chp', $options);
		$this->assertEquals('0.2', $options['chp']);
	}

	/**
	 * @expectedException        \InvalidArgumentException
	 * @expectedExceptionCode    500
	 * @expectedExceptionMessage A bar chart must have axis.
	 */
	public function testBuildInvalidType()
	{
		$chart = new BarChart();

		$chart	->setWidth(200)
				->setHeight(200)
				->setDatas(array(32, 15, 17))
				->setAxis(array());

		$chart->build();
	}
}