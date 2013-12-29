Using cache with the CacheEngine
================================

The Google ChartsImage tool is very cool but it's not always free. If you make too many requests
to the Google servers, you will need to pay. To resolve that problem, you can cache your charts.
Fortunately, the LegGCharts provides a useful CacheEngine to do that for you.

## Create the CacheEngine instance

The cache engine will require two directories to store the cache :

- the public one, accessible from users, to store charts images ;
- the internal one, inaccessible from users, to store metadata about charts ;

You can also provide an asset prefix that will be used to generate charts URL.
By default, the internal directory is directly in the library directory.

For instance:

```php
// Here we specify only the public cache directory
$cacheEngine = new CacheEngine('public/charts');

// Here we specify the public cache and the asset prefix
$cacheEngine = new CacheEngine('web/bundles/gcharts', 'bundles/gcharts/');

// Here we specify the public cache, the asset prefix and the internal cache
$cacheEngine = new CacheEngine('web/bundles/gcharts', 'bundles/gcharts/', 'app/cache/charts');
```

## Usage

The usage is very simple:

```php
$cacheEngine = new CacheEngine('web/gcharts', 'gcharts/');

echo '<img src="'. $cacheEngine->build($chart) .'" />';
```

What's happened here ?

- The cache engine is created with `web/bundles/gcharts` as public directory
- `CacheEngine::build(ChartInterface $chart, $keepTime = 3600)` is called :
	- if the chart already exists in the cache (and is fresher than $keepTime), this version is returned ;
	- if not, the Google servers are requested, and the result is stored in cache ;
- We no have a local URL, pointing to your assets directory (for instance, something like `gcharts/55852c88e2.png`)

## CacheEngine

The CacheEngine has five main methods :

- `put(ChartInterface $chart, $keepTime)` request the chart from Google servers even if it's in cache and store it ;
- `build(ChartInterface $chart, $keepTime)` call `put` if the chart is not in the cache and return a local URL for the chart
- `has(ChartInterface $chart)` check if the cache contains the chart
- `clear(ChartInterface $chart)` clear the cache for a given chart
- `clearAll()` clear the cache for all charts
