<?php

declare(strict_types = 1);

use Nette\DI\Container;


abstract class TestContainer extends Tester\TestCase
{
	/** @var Container */
	protected $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}
}
