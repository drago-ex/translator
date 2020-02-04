<?php

declare(strict_types = 1);

namespace Test;

use Nette\DI\Container;
use Tester\TestCase;


abstract class TestContainer extends TestCase
{
	/** @var Container */
	protected $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}
}
