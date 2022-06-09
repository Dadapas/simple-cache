<?php
namespace Dadapas\Cache;

use Psr\Cache\CacheItemInterface;
use DateTimeInterface;

class Cache implements CacheItemInterface
{
	public function getKey()
	{

	}

	public function get()
	{

	}

	public function isHit()
	{}

	public function set($value)
	{

	}

	/**
     * Sets the expiration time for this cache item.
     *
     * @param \DateTimeInterface|null $expiration
     *   The point in time after which the item MUST be considered expired.
     *   If null is passed explicitly, a default value MAY be used. If none is set,
     *   the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
	public function expiresAt($expiration)
	{

	}

	/**
	 * 
	*/
	public function expiresAfter($time)
	{

	}
}
