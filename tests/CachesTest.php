<?php declare(strict_types=1);

namespace Dadapas\CacheTests;

use Dadapas\Cache\Cache;

use PHPUnit\Framework\TestCase;

final class CachesTest extends TestCase
{
	public function laravel_style_test()
	{

		Cache::put('mykey', 'myvalue', 10);

		$this->assertSame('myvalue', Cache::get('mykey'));
	}
}