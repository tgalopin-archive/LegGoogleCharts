Usage
=====

## Create a chart

Before all, you must choose a chart type: see [the core types](03 - Chart types.md).

You need only to create an instance of a basic chart type, and customize it:

``` php
<?php

use Leg\GoogleChartsBundle\Charts\Gallery\BarChart;

$chart = new BarChart();

$chart	->setWidth(200)
        ->setHeight(200)
        ->setDatas(array(200, 100, 50));

$url = $this->get('leg_google_charts')->build($chart);
?>

<img src="<?php echo $url; ?>" alt="Chart" title="Chart" />
```

See [the core types](03 - Chart types.md) to know more about chart types.

### Externalize charts

For more flexibility, you can externalize a chart as a reusable class :

``` php
<?php

use Leg\GoogleChartsBundle\Charts\Gallery\BarChart;

class ExampleChart extends BarChart
{
	public function getDefaultOptions()
	{
		return array(
			'width' => 200,
			'height' => 200,
			'datas' => array(100, 75, 45)
		);
	}
}


$chart = new ExampleChart();
// ...
```

### Use multiple data sets

To use multiple data sets (for instance to compare two line charts on the same chart),
add as many arguments in `setDatas()` as you want :

``` php
<?php

use Leg\GoogleChartsBundle\Charts\Gallery\BarChart;

$chart = new BarChart();

$chart	->setWidth(200)
        ->setHeight(200)
        ->setDatas(array(200, 100, 50), array(10, 150, 70), array(400, 850, 300) /* ... */);

$url = $this->get('leg_google_charts')->build($chart);
?>

<img src="<?php echo $url; ?>" alt="Chart" title="Chart" />
```

### Next Steps

The following documents are available:

- [03 - Chart types and their properties](03 - Chart types.md)
- [04 - Using cache with the CacheEngine](04 - The CacheEngine.md)