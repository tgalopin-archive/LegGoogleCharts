<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\Gallery;

use Leg\GCharts\BaseChart;

class PieChart extends BaseChart
{
	protected $type = 'p';

	/**
	 * @var float
	 */
	protected $rotation;

	public function build()
	{
		$url = parent::build();

		if (! empty($this->rotation)) {
			$url .= '&chp='.$this->rotation;
		}

		return $url;
	}

	/**
	 * Get rotation
	 * @return float
	 */
	public function getRotation()
	{
		return $this->rotation;
	}

	/**
	 * Set rotation
	 * @param float $rotation
	 * @return float
	 */
	public function setRotation($rotation)
	{
		$this->rotation = (float) $rotation;

		return $this;
	}
}