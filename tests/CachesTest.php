<?php declare(strict_types=1);
namespace Dadapas\CacheTests;

use Dadapas\Cache\{CacheItemPool, CacheItem, FileCacheAdapter, InvalidArgumentException};
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use DateTime;
use DateTimeInterface;

final class CachesTest extends TestCase
{
	protected $pool;

	protected $path;

	/**
	 * @before
	*/
	protected function setFirst()
	{
		$this->path = dirname(__DIR__)."/cache";
		$adapter = new FileCacheAdapter($this->path);
		$this->pool = new CacheItemPool($adapter);
	}

	public function test_file_isreadabble()
	{
		$this->assertTrue(is_writable($this->path."/dadapas.cache"), "Cache folder to path writabble");
	}

	public function test_laravel_style_test()
	{
		$key = "token";
		$cache = new CacheItem($key);
		$cache->set("akoriaby");
		$this->pool->save($cache);

		$cacheItem = $this->pool->getItem($key);

		$this->assertFalse($cacheItem->isHit(), "Expires not reach yet");
		$this->assertEquals('akoriaby', $cacheItem->get());
	}

	public function test_throw_invalid_key()
	{
		try {
			$this->pool->getItem([654]);
		} catch (InvalidArgumentException $e) {

			$this->assertEquals( "key must be a string.", $e->getMessage());
		}
	}

	public function test_if_hasKey_work()
	{
		$key = "mynewkey";
		$cacheItem = new CacheItem($key);
		$cacheItem->set("hello_world");

		$this->pool->save($cacheItem);

		$this->assertTrue($this->pool->hasItem($key), "Element exist");

		// Expires this item
		$cacheItem->expiresAt(new DateTime("2020-04-10"));
		$this->pool->save($cacheItem);

		$this->assertFalse($this->pool->hasItem($key), "Element doesnt exist");
	}

	public function test_expired_cache()
	{
		$key = "anotherToken";
		$cache = new CacheItem($key);
		$md5 = md5("mymdfive");
		$cache->set($md5);
		$date = new \DateTime("2020-04-10 10:00:00");
		$cache->expiresAt($date);
		$this->pool->save($cache);

		$cacheItem = $this->pool->getItem($key);

		$this->assertTrue($cacheItem->isHit(), "this is an expired cache");

		$this->assertEquals(null, $cacheItem->get());

	}

	public function test_item()
	{
		$this->assertEquals(null, $this->pool->getItem('innexistanceKey'));

		$keys = ['token', 'another'];

		$caches = $this->pool->getItems($keys);

		$this->assertEquals('array', gettype($caches), '$caches is an array');

		$this->assertArrayHasKey('token', $caches);
		$this->assertArrayHasKey('another', $caches);

		// Delete item
		$this->pool->deleteItem('token');
		$this->assertFalse($this->pool->hasItem('token'), "token cache is not exists anymore");
	}

	public function test_using_expireAfter()
	{
		$key = "mynewtoken";
		$cache = new CacheItem($key);
		$md5 = sha1("mymdfive");
		$cache->set($md5);
		$date = new \DateTime("2020-04-10 10:00:00");
		$cache->expiresAt($date);
		$this->pool->save($cache);

		$cacheItem = $this->pool->getItem($key);

		$this->assertTrue($cacheItem->isHit(), "this is an expired cache");

		// Expires after one hours
		$cacheItem->expiresAfter(3600);
		$this->pool->save($cacheItem);

		$cacheItem = $this->pool->getItem($key);
		$this->assertFalse($cacheItem->isHit(), "item exist");
		$this->assertEquals($md5, $cacheItem->get(), "the item will remind the same value");
	}

	public function test_with_callback()
	{
		$accessToken = $this->pool->get('AccessToken', function($cacheItem){

			// Expires after 5 second
			$cacheItem->expiresAfter(5);
			echo "Renew the cache item";

			$cacheItem->set("mytoken");

			return $cacheItem;
		});

		$this->assertEquals("mytoken", $accessToken);
	}

	/*public function test_clear_cache()
	{
		// clear all item
		$this->pool->clear();
		$this->assertEquals(null, $this->pool->getItem('anotherToken'), 'no cache in cache store');
	}*/
}
