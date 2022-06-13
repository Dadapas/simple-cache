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
interface CacheAdapterInterface
{
	/**
	 * Get key value of all element get by adapter
	 * an empty array instead
	 * 
	 * @return array
	*/
	public function charge();

	/**
	 * Update the element to the store
	 * 
	 * @param array $items key value of elements
	*/ 
	public function update(array $items);
}