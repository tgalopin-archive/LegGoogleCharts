<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts;

use Leg\GCharts\DataSet\DataSet;
use Leg\GCharts\DataSet\DataSetCollection;

interface ChartInterface
{
	/**
	 * Set options
	 * @param array $options
	 */
	public function setOptions(array $options);

	/**
	 * @return array
	 */
	public function getDefaultOptions();

	/**
	 * Build and return URI
	 * @return string
	 */
	public function build();

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @param string $type
	 */
	public function setType($type);

	/**
	 * @return integer
	 */
	public function getWidth();

	/**
	 * @param integer $width
	 */
	public function setWidth($width);

	/**
	 * @return integer
	 */
	public function getHeight();

	/**
	 * @param integer $height
	 */
	public function setHeight($height);

	/**
	 * Register data sets
	 *
	 * @param array $1
	 * @param array $2
	 * @param array $...
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setDatas();

	/**
	 * @return DataSetCollection
	 */
	public function getDatas();

	/**
	 * @param array $labels
	 */
	public function setLabels(array $labels);

	/**
	 * @return DataSet
	 */
	public function getLabels();

	/**
	 * @param array $labels_options
	 */
	public function setLabelsOptions(array $labels_options);

	/**
	 * @return DataSet
	 */
	public function getLabelsOptions();

	/**
	 * @param array $colors
	 */
	public function setColors(array $colors);

	/**
	 * @return DataSet
	 */
	public function getColors();

	/**
	 * @param string $title
	 */
	public function setTitle($title);

	/**
	 * @return string
	 */
	public function getTitle();

	/**
	 * @param array $title_options
	 */
	public function setTitleOptions(array $title_options);

	/**
	 * @return DataSet
	 */
	public function getTitleOptions();

	/**
	 * @param boolean $transparency
	 */
	public function setTransparency($transparency);

	/**
	 * @return boolean
	 */
	public function isTransparent();
}