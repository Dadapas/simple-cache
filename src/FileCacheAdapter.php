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

class FileCacheAdapter implements CacheAdapterInterface
{
	protected $file = "dadapas.cache";

	protected $path;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function getContents()
	{
		if ( ! file_exists($this->path."/{$this->file}") )
			file_put_contents($this->path."/{$this->file}", "");

		return file_get_contents($this->path . "/{$this->file}");
	}

	public function writeContent(string $data)
	{
		file_put_contents($this->path."/{$this->file}", $data);
	}

	/**
	 * Get key value of all element get by adapter
	 * an empty array instead
	 * 
	 * @return array
	*/
	public function charge()
	{
		$items = unserialize($this->getContents());

		return $items;
	}

	/**
	 * Update to file the contents
	 * 
	 * @param array $items key value of elements
	*/ 
	public function update(array $items)
	{
		$serial = serialize($items);
		$this->writeContent($serial);
	}
}