<?php

namespace DatabaseBackups\Entity;

use DatabaseBackups\Interfaces\ObjectInterface;

class Object implements ObjectInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $body;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName( $name ) {
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 *
	 * @return $this
	 */
	public function setUrl( $url ) {
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * @return string|null
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @param string $body
	 *
	 * @return $this
	 */
	public function setBody( $body ) {
		$this->body = $body;

		return $this;
	}
}