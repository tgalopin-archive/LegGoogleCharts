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

class BaseChart implements ChartInterface
{

	/**
	 * Currently support charts by Image Charts
	 * @link https://image-charts.com/swagger.json
	 * @var array
	 */
	const IMAGECHARTS_SUPPORTED = array('bvs', 'bhs', 'bvg', 'bhg', 'bvo', 'p', 'p3', 'pc', 'pd', 'ls', 'lc', 'lxy');

	/**
	 * @var string
	 */
	const IMAGECHART_BASE_URL = 'http://image-charts.com/chart';

	/**
	 * Fallback on Google Image Charts for currently unsupported charts by Image Charts
	 * @var string
	 */
	const BASE_URL = 'http://chart.googleapis.com/chart';

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var integer
	 */
	protected $width;

	/**
	 * @var integer
	 */
	protected $height;

	/**
	 * @var DataSetCollection
	 */
	protected $datas;

	/**
	 * @var DataSet
	 */
	protected $labels;

	/**
	 * @var DataSet
	 */
	protected $labels_options;

	/**
	 * @var DataSet
	 */
	protected $colors;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var DataSet
	 */
	protected $title_options;

	/**
	 * @var boolean
	 */
	protected $transparency;

	/**
	 * @var DataSet
	 */
	protected $margins;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->datas = new DataSetCollection();
		$this->labels = new DataSet();
		$this->labels_options = new DataSet();
		$this->colors = new DataSet();
		$this->title_options = new DataSet();

		$this->margins = new DataSet(array(
			'top' => null,
			'bottom' => null,
			'left' => null,
			'right' => null,
			'legend-width' => null,
			'legend-height' => null
		));

		$this->setOptions($this->getDefaultOptions());
	}

	/**
	 * Set options
	 *
	 * @param array $options
	 * @throws \InvalidArgumentException
	 */
	public function setOptions(array $options)
	{
		foreach ($options as $option => $value) {
			$funcName = array_map('ucfirst', explode('_', $option));
			$funcName = 'set'.implode('', $funcName);

			if (method_exists($this, $funcName)) {
				$this->$funcName($value);
			} else {
				throw new \InvalidArgumentException(sprintf(
					'Unknown chart option "%s" or chart method "%s()"',
					$option, $funcName
				), 500);
			}
		}
	}

	/**
	 * @return array
	 */
	public function getDefaultOptions()
	{
		return array();
	}

	/**
	 * Build and return URI
	 *
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function build()
	{
		if (empty($this->type)) {
			throw new \InvalidArgumentException('A chart must have a type.', 500);
		}

		if ($this->datas->isEmpty()) {
			throw new \InvalidArgumentException('A chart must have datas.', 500);
		}

		if (empty($this->width)) {
			throw new \InvalidArgumentException('A chart must have a width.', 500);
		}

		if (empty($this->height)) {
			throw new \InvalidArgumentException('A chart must have a height.', 500);
		}

		$url = (in_array($this->type, self::IMAGECHARTS_SUPPORTED) ? self::IMAGECHART_BASE_URL : self::BASE_URL).'?cht='.$this->type;
		$url .= '&chs='.$this->width.'x'.$this->height;

		$dataSets = array();

		foreach ($this->datas->toArray() as $dataSet) {
			$dataSets[] = implode(',', $dataSet->toArray());
		}

		$url .= '&chd=t:'.implode('|', $dataSets);

		if (! $this->colors->isEmpty()) {
			$url .= '&chco='.implode('|', $this->colors->toArray());
		}

		if ($this->isTransparent()) {
			$url .= '&chf=bg,s,65432100';
		}

		if (! $this->labels->isEmpty()) {
			$url .= '&chl='.implode('|', $this->labels->toArray());

			if ($this->labels_options->get('position')) {
				$url .= '&chdlp='.$this->labels_options->get('position');
			}

			if ($this->labels_options->get('color')) {
				$url .= '&chdls='.$this->labels_options->get('color');
			}

			if ($this->labels_options->get('font-size')) {
				if ($this->labels_options->get('color')) {
					$url .= ','.$this->labels_options->get('font-size');
				} else {
					$url .= '&chdls=,'.$this->labels_options->get('font-size');
				}
			}
		}

		if (! empty($this->title)) {
			$url .= '&chtt='.urlencode($this->title);

			// Check color
			if ($this->title_options->get('color')) {
				$url .= '&chts='.$this->title_options->get('color');
			}

			// Check font size
			if ($this->title_options->get('font-size')) {
				if ($this->title_options->get('color')) {
					$url .= ','.$this->title_options->get('font-size');
				} else {
					$url .= '&chts=,'.$this->title_options->get('font-size');
				}
			}

			// Check alignement
			if ($this->title_options->get('text-align')) {
				if ($this->title_options->get('color') OR $this->title_options->get('font-size')) {
					$url .= ','.$this->title_options->get('text-align');
				} else {
					$url .= '&chts=,,'.$this->title_options->get('text-align');
				}
			}
		}

		// Margins
		$margins = $this->margins;

		if ($margins->get('top') || $margins->get('bottom') || $margins->get('left')
			|| $margins->get('right') || $margins->get('legend-width') || $margins->get('legend-height')) {

			$url .= '&chma='.((float) $margins->get('top')).',';
			$url .= ((float) $margins->get('bottom')).',';
			$url .= ((float) $margins->get('left')).',';
			$url .= ((float) $margins->get('right')).',';
			$url .= ((float) $margins->get('legend-width')).',';
			$url .= ((float) $margins->get('legend-height'));
		}

		return $url;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->type = (string) $type;

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @param int $width
	 * @return $this
	 */
	public function setWidth($width)
	{
		$this->width = (int) $width;

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @param int $height
	 * @return $this
	 */
	public function setHeight($height)
	{
		$this->height = (int) $height;

		return $this;
	}

	/**
	 * Register data sets
	 *
	 * @param array $1
	 * @param array $2
	 * @param array $...
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setDatas()
	{
		foreach (func_get_args() as $datas) {
			foreach ($datas as $data) {
				if (! is_numeric($data)) {
					throw new \InvalidArgumentException(sprintf(
						'Datas must be numbers (%s given)', gettype($data)
					), 500);
				}
			}

			$this->datas->add(new DataSet($datas));
		}

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getDatas()
	{
		return $this->datas;
	}

	/**
	 * @param array $labels
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setLabels(array $labels)
	{
		foreach ($labels as $label) {
			if (! is_numeric($label) && ! is_string($label)) {
				throw new \InvalidArgumentException(sprintf(
					'Labels must be numbers or strings (%s given)', gettype($label)
				), 500);
			}
		}

		$this->labels = new DataSet($labels);

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getLabels()
	{
		return $this->labels;
	}

	/**
	 * @param array $labels_options
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setLabelsOptions(array $labels_options)
	{
		foreach($labels_options as $option => $value) {
			switch($option) {
				case 'position':
					if (! is_string($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The label position must be a string (%s given)', gettype($value)
						), 500);
					}

					if (! in_array($value, array('b', 'bv', 't', 'tv', 'r', 'l'))) {
						throw new \InvalidArgumentException(sprintf(
							'Unknown label position "%s". Valid positions are : b, bv, t, tv, r, l.',
							$value
						), 500);
					}
				break;

				case 'color':
					if (! is_string($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The label color must be a string (%s given)', gettype($value)
						), 500);
					}

					if (! preg_match('#^[a-z0-9]{6,8}$#i', $value)) {
						throw new \InvalidArgumentException(sprintf(
							'The label color must be a hexadecimal value ("%s" given).',
							$value
						), 500);
					}
				break;

				case 'font-size':
					if (! is_numeric($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The label font size must be numeric (%s given).',
							gettype($value)
						), 500);
					}
				break;

				default:
					throw new \InvalidArgumentException(sprintf(
						'Unknown label option "%s". Valid options are : position, color, font-size.',
						$option
					), 500);
			}
		}

		$this->labels_options = new DataSet($labels_options);

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getLabelsOptions()
	{
		return $this->labels_options;
	}

	/**
	 * @param array $colors
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setColors(array $colors)
	{
		foreach ($colors as $color) {
			if (! is_string($color)) {
				throw new \InvalidArgumentException(sprintf(
					'A color must be a string (%s given).',
					gettype($color)
				), 500);
			}

			if (! preg_match('#^[a-z0-9]{6}$#i', $color)) {
				throw new \InvalidArgumentException(sprintf(
					'A color must be a hexadecimal string ("%s" given).',
					$color
				), 500);
			}
		}

		$this->colors = new DataSet($colors);

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getColors()
	{
		return $this->colors;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = (string) $title;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param array $title_options
	 * @return $this
	 * @throws \InvalidArgumentException
	 */
	public function setTitleOptions(array $title_options)
	{
		foreach ($title_options as $option => $value) {
			switch ($option) {
				case 'text-align':
					if (! is_string($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The title position must be a string (%s given)', gettype($value)
						), 500);
					}

					if (! in_array($value, array('left', 'center', 'right'))) {
						throw new \InvalidArgumentException(sprintf(
							'Unknown title position "%s". Valid positions are : left, center, right.',
							$value
						), 500);
					}
				break;

				case 'color':
					if (! is_string($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The title color must be a string (%s given)', gettype($value)
						), 500);
					}

					if (! preg_match('#^[a-z0-9]{6,8}$#i', $value)) {
						throw new \InvalidArgumentException(sprintf(
							'The title color must be a hexadecimal value ("%s" given).',
							$value
						), 500);
					}

					break;

				case 'font-size':
					if (! is_numeric($value)) {
						throw new \InvalidArgumentException(sprintf(
							'The title font size must be numeric (%s given).',
							gettype($value)
						), 500);
					}
				break;

				default:
					throw new \InvalidArgumentException(sprintf(
						'Unknown title option "%s". Valid options are : text-align, color, font-size.',
						$option
					), 500);
			}
		}

		$this->title_options = new DataSet($title_options);

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getTitleOptions()
	{
		return $this->title_options;
	}

	/**
	 * @param bool $transparency
	 * @return $this
	 */
	public function setTransparency($transparency)
	{
		$this->transparency = (boolean) $transparency;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isTransparent()
	{
		return $this->transparency;
	}

	/**
	 * @param array $margins
	 * @return BaseChart
	 */
	public function setMargins(array $margins)
	{
		$this->margins = new DataSet($margins);

		return $this;
	}

	/**
	 * @return DataSet
	 */
	public function getMargins()
	{
		return $this->margins;
	}
}