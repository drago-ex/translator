<?php

declare(strict_types=1);

use Nette\DI\Container;
use Tester\TestCase;


abstract class TestContainer extends TestCase
{
	protected Container $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}
}
