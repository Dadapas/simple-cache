<?php
namespace Dadapas\Cache;

/**
 * This file is part of the dadapas/cache library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) TOVOHERY Z. Pascal <tovoherypascal@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionIterface;
use Psr\Cache\CacheItemInterface;
use DateTimeInterface;
use DateTime;


class CacheItemPool implements CacheItemPoolInterface
{
	protected $items;

	protected $adapter;

	public function __construct(CacheAdapterInterface $adapter)
	{
		// File cache adapter is the default adapter
		$this->adapter = $adapter;
		$this->items = $adapter->charge();
	}

	protected function checkExistanceOfAdapter()
	{
		if ( is_null($this->adapter) )
			throw new InvalidArgumentException("not adapter has been found.");
	}

	protected function updateStore()
	{
		$this->adapter->update($this->items);
	}

	public function put($key, $value, DateTimeInterface $date = null)
	{
		$this->checkExistanceOfAdapter();

		$cacheItem = $this->getItem($key);

		if ($cacheItem == null){
			$cacheItem = new CacheItem;
		}

		$cacheItem->set($value);
		$cacheItem->expiresAt($date);

		$this->save($cacheItem);
	}

	public function getItem($key)
	{
		$this->checkExistanceOfAdapter();

		if ( ! is_string($key) )
			throw new InvalidArgumentException("key must be a string.");

		if ( ! isset($this->items[$key]) )
			return;

		return $this->items[$key];
	}

	public function clear()
	{
		$this->items = [];

		return $this->updateStore();
	}

	public function getItems(array $keys = array())
	{
		$this->checkExistanceOfAdapter();
		$newarray = [];

		foreach($keys as $key) {
			$newarray[$key] = $this->hasItem($key) ? $this->items[$key] : null;
		}
		return $newarray;
	}

	public function deleteItems(array $keys)
	{
		$this->checkExistanceOfAdapter();

		foreach($keys as $key => $keyItem)
		{
			$this->deleteItem($keyItem);
		}
		return true;
	}

	public function deleteItem($key)
	{
		$this->checkExistanceOfAdapter();

		if ( $this->hasItem($key) ){
			unset($this->items[$key]);
			$this->updateStore();
			return true;
		}
		return false;
	}

	public function hasItem($key)
	{
		$this->checkExistanceOfAdapter();

		if (!is_string($key) )
			throw new InvalidArgumentException('Invalid parameter, $key must be a string.');

		if (array_key_exists($key, $this->items)){
			$cacheItem = $this->items[$key];
			return $this->isValid();
		}
		return false;
	}

	public function save(CacheItemInterface $item)
	{
		$this->checkExistanceOfAdapter();
		$this->items = $this->adapter->charge();
		$this->items[ $item->getKey() ] = $item;
		$this->updateStore();
		return true;
	}

	public function saveDeferred(CacheItemInterface $item)
	{
		$this->checkExistanceOfAdapter();
		return $this->save($item);
	}

	public function commit()
	{
		$this->checkExistanceOfAdapter();
		return true;
	}
}