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

use Psr\Cache\CacheItemInterface;
use DateTimeInterface;
use DateTime;
use DateInterval;
use Serializable;

class CacheItem implements CacheItemInterface, Serializable
{
	protected $key;

	protected $value;

	protected $expiration;

	protected $format = "Y-m-d H:i:s";

	public function __construct($key)
	{
		$this->key = $key;
		$date = new DateTime();
		$date->add(new DateInterval("P1Y"));

		$this->setExpirationAt($date);
	}

	protected function setExpirationAt(DateTimeInterface $date)
	{
		$this->expiration = $date->format($this->format);
	}

	public function getKey()
	{
		return $this->key;
	}

	public function get()
	{
		if ($this->isHit())
			return;

		return $this->value;
	}

	public function isHit()
	{
		$date = new DateTime($this->expiration);

		return $date->getTimestamp() < time();
	}

	public function set($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
     * Sets the expiration time for this cache item.
     *
     * 
     * @param int $expiration number of seconds Ex: 60 will expire after one minute
     * 
     * @return static
     *   The called object.
     */
	public function expiresAt($expiration)
	{
		if ( ! ($expiration instanceof DateTimeInterface) )
			throw new InvalidArgumentException("expiration must be a \"DateTimeInterface\" instance");

		$this->setExpirationAt($expiration);

		return $this;
	}

	/**
	 * Set the expiration date expire after a seconds
	 * 
	 * @param int $time in second
	*/
	public function expiresAfter($time)
	{
		$date = new DateTime();
		$date->add(new DateInterval("PT{$time}S"));

		$this->setExpirationAt($date);

		return $this;
	}

	public function serialize()
	{
		return serialize([
			'key'			=> $this->key,
			'value'			=> $this->value,
			'expiration'	=> $this->expiration
		]);
	}

	public function unserialize($serialized)
	{
		$arrayData = unserialize($serialized);

		$this->key = $arrayData['key'];
		$this->value = $arrayData['value'];
		$this->expiration = $arrayData['expiration'];
	}
}
