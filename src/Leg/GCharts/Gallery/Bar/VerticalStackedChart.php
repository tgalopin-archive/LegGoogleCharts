<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\Gallery\Bar;

use Leg\GCharts\Gallery\BarChart;

class VerticalStackedChart extends BarChart
{
	protected $type = 'bvs';

	/**
	 * Set the stacked mode for the bars
	 *
	 * 		atop : 	Vertical bar chart in which bars
	 * 				are stacked atop of one another.
	 * 		front : Vertical bar chart in which bars
	 * 				are stacked front of one another.
	 *
	 * @param $mode
	 * @throws \InvalidArgumentException
	 */
	public function setStackedMode($mode)
	{
		if (! is_string($mode)) {
			throw new \InvalidArgumentException(sprintf(
				'A stacked mode must be a string (%s given).',
				gettype($mode)
			), 500);
		}

		if ($mode == 'front') {
			$this->type = 'bvo';
		} elseif ($mode == 'atop') {
			$this->type = 'bvs';
		} else {
			throw new \InvalidArgumentException(sprintf(
				'Unknown chart stacked mode "%s". Valid stacked mode are "front" and "atop".',
				$mode
			), 500);
		}
	}

	/**
	 * Get the stacked mode for the bars
	 *
	 * @return string
	 */
	public function getStackedMode()
	{
		if ($this->type == 'bvo') {
			return 'front';
		} else {
			return 'atop';
		}
	}
}