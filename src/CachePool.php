<?php
namespace Dadapas\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Cache\CacheItemInterface;
use DateTimeInterface;

class CachePool implements CacheItemPoolInterface
{
	public function put($key, $value, DateTimeInterface $date = null)
	{
		$cacheItem = $this->getItem($key);

		if ($cacheItem == null){
			$cacheItem = new Cache;
		}
		
		$cacheItem->set($value);
		$cacheItem->expiresAt($date);
		$cacheItem->exipresAfter(000);

		$this->save($cacheItem);
	}

	public function getItem($key)
	{

	}

	public function clear()
	{
		
	}

	public function getItems(array $keys = array())
	{

	}

	public function deleteItems(array $keys)
	{

	}

	public function deleteItem($key)
	{

	}

	public function hasItem($key)
	{
		
	}

	public function save(CacheItemInterface $item)
	{

	}

	public function saveDeferred(CacheItemInterface $item)
	{

	}

	public function commit()
	{
		return true;
	}

	public static function __callStatic($method, $arguments)
	{
		call_user_func($this, $method, $arguments);
		echo "Call method $method";
	}
}