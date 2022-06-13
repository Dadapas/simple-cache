### PHP Cache

This is an implementation of PSR-6 and PSR-16 cache interface,

Installation
============

Install composer if not and enter the command

```bash
composer require dadapas/cache
```

Basic usage
===========

A basic usage of cache

```php
use Dadapas\Cache\CacheItemPool;
use Dadapas\Cache\CacheItem;
use Dadapas\Cache\FileCacheAdapter;

// Declarations
$adapter   = new FileCacheAdapter(__DIR__."/cache"); // cache adapter
$pool      = new CacheItemPool($adapter);            // pool

$key       = "mykey";
$cacheItem = new CacheItem($key);                    // cache item
// Set the cache value
$cacheItem->set("cache value");
$cacheItem->expiresAfter(3600);                      // cache will live in 1 hour
$pool->save($cache);                                 // Save the cache

// ...
```
To get a cache from the pool

```php
// Get cache by key null if key not exist
$cacheItem = $pool->getItem($key);

// get the cache value null if expires or non exist key
$cacheItem->get();

// To get the key
$cacheItem->getKey();

// Set the exact expiration date
// This data will expire in 1st january 2023
$cacheItem->expiresAt(new DateTime("2023-01-01"));

// 
```
You can then pick one of the implementations of the interface to get a logger.

If you want to implement the interface, you can require this package and
implement `Psr\Cache\CacheItemPoolInterface` in the code code. Please read the
[specification text](https://packagist.org/packages/psr/log)
for details.

Test
=====

For making a test just

```bash
composer test
```
License
=======

The licence is MIT for more details click [here](LICENSE)
