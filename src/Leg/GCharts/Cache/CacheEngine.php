<?php

/*
 * This file is part of the LegGoogleChartsBundle package.
 *
 * (c) Titouan Galopin <http://titouangalopin.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leg\GCharts\Cache;

use Leg\GCharts\ChartInterface;

/**
 * CacheEngine is an engine to cache charts images.
 */
class CacheEngine
{
	/**
	 * Public cache directory (accessible from users)
	 * @var string
	 */
	protected $publicCacheDir;

	/**
	 * Internal cache directory (hidden to users)
	 * @var string
	 */
	protected $internalCacheDir;

	/**
	 * Assets cache url (accessible from users), to generate a valid URL
	 * @var string
	 */
	protected $assetsCacheUrl = '';

	/**
	 * @param string $publicCacheDir
	 * @param string $assetsCacheUrl
	 * @param string $internalCacheDir
	 * @throws \InvalidArgumentException
	 */
	public function __construct($publicCacheDir, $assetsCacheUrl = '', $internalCacheDir = null)
	{
		$publicCacheDir = (string) $publicCacheDir;
		$internalCacheDir = (string) $internalCacheDir;
		$this->assetsCacheUrl = (string) $assetsCacheUrl;

		if (! file_exists($publicCacheDir)) {
			if (! mkdir($publicCacheDir, 0777, true)) {
				throw new \InvalidArgumentException(sprintf(
					'Public cache directory does not exists and can not be created ("%s")', $publicCacheDir
				));
			}
		} elseif (! is_dir($publicCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Given public cache directory is not a directory ("%s")', $publicCacheDir
			));
		} elseif (! is_readable($publicCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Public cache directory is not readable ("%s")', $publicCacheDir
			));
		} elseif (! is_writable($publicCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Public cache directory is not writable ("%s")', $publicCacheDir
			));
		}

		if (! $internalCacheDir) {
			$internalCacheDir = __DIR__.'/objects';
		}

		if (! file_exists($internalCacheDir)) {
			if (! mkdir($internalCacheDir, 0777, true)) {
				throw new \InvalidArgumentException(sprintf(
					'Internal cache directory does not exists and can not be created ("%s")', $internalCacheDir
				));
			}
		} elseif (! is_dir($internalCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Given internal cache directory is not a directory ("%s")', $internalCacheDir
			));
		} elseif (! is_readable($internalCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Internal cache directory is not readable ("%s")', $internalCacheDir
			));
		} elseif (! is_writable($internalCacheDir)) {
			throw new \InvalidArgumentException(sprintf(
				'Internal cache directory is not writable ("%s")', $internalCacheDir
			));
		}

		$this->publicCacheDir = $publicCacheDir;
		$this->internalCacheDir = $internalCacheDir;
	}

	/**
	 * Save a chart during a given time in the cache.
	 *
	 * @param ChartInterface $chart
	 * @param integer $keepTime
	 * @return $this
	 */
	public function put(ChartInterface $chart, $keepTime = 3600)
	{
		$filename = $this->hash($chart->build());

		$this->saveFile($this->internalCacheDir.'/'.$filename, time() + $keepTime);
		$this->saveFile($this->publicCacheDir.'/'.$filename.'.png', file_get_contents($chart->build()));

		return $this;
	}

	/**
	 * Check if the chart is in cache or not.
	 *
	 * @param ChartInterface $chart
	 * @return bool
	 */
	public function has(ChartInterface $chart)
	{
		$filename = $this->hash($chart->build());
		$internalCacheFile = $this->getInternalCacheDir().'/'.$filename;
		$hasFile = false;

		if (file_exists($internalCacheFile) && file_exists($this->publicCacheDir.'/'.$filename.'.png')) {
			$chartKeepTime = (int) file_get_contents($internalCacheFile);

			if ($chartKeepTime < time()) {
				unlink($internalCacheFile);
				unlink($this->publicCacheDir.'/'.$filename.'.png');
			} else {
				$hasFile = true;
			}
		}

		return $hasFile;
	}

	/**
	 * Get a chart in the cache if it's possible or directly on Google if it's not.
	 *
	 * @param ChartInterface $chart
	 * @param integer $keepTime
	 * @return string
	 */
	public function build(ChartInterface $chart, $keepTime = 3600)
	{
		if (! $this->has($chart)) {
			echo 'PUT';
			$this->put($chart, $keepTime);
		}

		return $this->assetsCacheUrl.$this->hash($chart->build()).'.png';
	}

	/**
	 * Clear the cache for the given chart
	 *
	 * @param ChartInterface $chart
	 * @return $this
	 */
	public function clear(ChartInterface $chart)
	{
		$filename = $this->hash($chart->build());

		$internalCacheFile = $this->internalCacheDir.'/'.$filename;
		$publicCacheFile = $this->publicCacheDir.'/'.$filename.'.png';

		if (file_exists($internalCacheFile)) {
			unlink($internalCacheFile);
		}

		if (file_exists($publicCacheFile)) {
			unlink($publicCacheFile);
		}

		return $this;
	}

	/**
	 * Clear all the cache
	 *
	 * @return $this
	 */
	public function clearAll()
	{
		// Images
		$iterator = new \DirectoryIterator($this->publicCacheDir);

		foreach ($iterator as $file) {
			if ($file->isFile()) {
				unlink($file->getPathname());
			}
		}

		// Metadatas
		$iterator = new \DirectoryIterator($this->internalCacheDir);

		foreach ($iterator as $file) {
			if ($file->isFile()) {
				unlink($file->getPathname());
			}
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getInternalCacheDir()
	{
		return $this->internalCacheDir;
	}

	/**
	 * @return string
	 */
	public function getPublicCacheDir()
	{
		return $this->publicCacheDir;
	}

	/**
	 * @param string $assetsCacheUrl
	 * @return CacheEngine
	 */
	public function setAssetsCacheUrl($assetsCacheUrl)
	{
		$this->assetsCacheUrl = (string) $assetsCacheUrl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAssetsCacheUrl()
	{
		return $this->assetsCacheUrl;
	}

	/**
	 * Hash the URL to return a unique file name
	 *
	 * @param string $url
	 * @return string
	 */
	private function hash($url)
	{
		return substr(md5($url), 0, 10);
	}

	/**
	 * @param string $name
	 * @param string $content
	 * @return int
	 * @throws \RuntimeException
	 */
	private function saveFile($name, $content)
	{
		$result = @file_put_contents($name, $content);

		if ($result === false) {
			throw new \RuntimeException(sprintf('Directory "%s" is not writable', dirname($name)));
		}

		return $result;
	}
}