<?php

/*
 * This file is part of the LegGCharts package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\DataSet;

/**
 * Array object for data and configuration
 */
class DataSet implements \Countable, \ArrayAccess
{
	/**
	 * An array containing the entries of this collection.
	 *
	 * @var array
	 */
	protected $_elements;

	/**
	 * Initializes a new DataSet.
	 *
	 * @param array $elements
	 */
	public function __construct(array $elements = array())
	{
		$this->_elements = $elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray()
	{
		return $this->_elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function first()
	{
		return reset($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function last()
	{
		return end($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function key()
	{
		return key($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function next()
	{
		return next($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function current()
	{
		return current($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove($key)
	{
		if (isset($this->_elements[$key]) || array_key_exists($key, $this->_elements)) {
			$removed = $this->_elements[$key];
			unset($this->_elements[$key]);

			return $removed;
		}

		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeElement($element)
	{
		$key = array_search($element, $this->_elements, true);

		if ($key !== false) {
			unset($this->_elements[$key]);
			return true;
		}

		return false;
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetExists($offset)
	{
		return $this->containsKey($offset);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetSet($offset, $value)
	{
		if ( ! isset($offset)) {
			return $this->add($value);
		}
		return $this->set($offset, $value);
	}

	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetUnset($offset)
	{
		return $this->remove($offset);
	}

	/**
	 * {@inheritDoc}
	 */
	public function containsKey($key)
	{
		return isset($this->_elements[$key]) || array_key_exists($key, $this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function contains($element)
	{
		return in_array($element, $this->_elements, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function indexOf($element)
	{
		return array_search($element, $this->_elements, true);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($key)
	{
		if (isset($this->_elements[$key])) {
			return $this->_elements[$key];
		}
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getKeys()
	{
		return array_keys($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValues()
	{
		return array_values($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function count()
	{
		return count($this->_elements);
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value)
	{
		$this->_elements[$key] = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add($value)
	{
		$this->_elements[] = $value;
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isEmpty()
	{
		return ! $this->_elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function clear()
	{
		$this->_elements = array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function slice($offset, $length = null)
	{
		return array_slice($this->_elements, $offset, $length, true);
	}
}
