LegGCharts
===========

[![Build Status](https://travis-ci.org/tgalopin/LegGoogleCharts.png?branch=master)](https://travis-ci.org/tgalopin/LegGoogleCharts)

What is LegGCharts ?
---------------------

LegGCharts is a library for the PHP 5.3.2+. It provides a set of classes to generate charts using
~~Google Charts Image~~ [Image-Charts](https://image-charts.com) (a drop-in-replacement for [the deprecated](https://developers.googleblog.com/2012/04/changes-to-deprecation-policies-and-api.html) Google Image Chart service).

> **Note:** This library does *not* create images alone : it use Image-Charts service.
> Therefore you can not display charts without an active internet connection.

Documentation
-------------

The documentation is stored in the `doc` directory of this library:

[Read the Documentation](doc)

Installation
------------

All the installation instructions are located in [documentation](doc).

Tests
------------

```shell
phpunit --configuration phpunit.xml.dist
```

License
-------

See the license in the bundle: [LICENSE](LICENSE.md)

About
-----

The LegGCharts library is developped mainly by Titouan Galopin.

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](issues).
