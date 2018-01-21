<?php

namespace DatabaseBackups\Interfaces;

interface ObjectInterface {

	public function getName();

	/**
	 * @param $name
	 *
	 * @return string $this
	 */
	public function setName( $name );

	/**
	 * @return string|null
	 */
	public function getUrl();

	/**
	 * @param string $url
	 *
	 * @return $this
	 */
	public function setUrl( $url );

	/**
	 * @return string|null
	 */
	public function getPath();

	/**
	 * @return string|null
	 */
	public function getBody();

	/**
	 * @param string $body
	 *
	 * @return $this
	 */
	public function setBody( $body );
}