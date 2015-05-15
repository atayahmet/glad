<?php

namespace Glad\Interfaces;

/**
 * Cookie setter class implementation
 *
 * @author Ahmet ATAY
 * @category CookerInterface
 * @package Glad
 * @copyright 2015
 * @license http://opensource.org/licenses/MIT MIT license
 * @link https://github.com/atayahmet/glad
 */
interface CookerInterface
{
	/**
     * Creates new cookie
     *
     * @param $name string
     * @param $value mixed
     * @param $lifetime integer
     * @param $path string
     * @param $domain string
     * @param $secure boolean
     * @param $httpOnly boolean
     * @return boolean
     */
	public function set($name = false, $value = false, $lifeTime = '', $path = '/', $domain = '', $secure = false, $httpOnly = false);

	/**
     * Delete cookie
     *
     * @param $name string
     * @return boolean
     */
	public function remove($name);

	/**
     * Get the value if exists
     *
     * @param $name string
     * @return string|null
     */
	public function get($name);

	/**
     * Check cookie exists
     *
     * @param $name string
     * @return boolean
     */
	public function has($name);
}