<?php
// source: array
// source: array

/** @noinspection PhpParamsInspection,PhpMethodMayBeStaticInspection */

declare(strict_types=1);

class Container_7d5f8dd1f4 extends Nette\DI\Container
{
	protected array $tags = ['nette.inject' => ['application.1' => true, 'application.2' => true, 'application.3' => true]];

	protected array $aliases = [
		'application' => 'application.application',
		'cacheStorage' => 'cache.storage',
		'httpRequest' => 'http.request',
		'httpResponse' => 'http.response',
		'nette.httpRequestFactory' => 'http.requestFactory',
		'nette.presenterFactory' => 'application.presenterFactory',
		'router' => 'routing.router',
		'session' => 'session.session',
	];

	protected array $wiring = [
		'Nette\DI\Container' => [['container']],
		'Nette\Application\Application' => [['application.application']],
		'Nette\Application\IPresenterFactory' => [['application.presenterFactory']],
		'Nette\Application\LinkGenerator' => [['application.linkGenerator']],
		'Nette\Caching\Storage' => [['cache.storage']],
		'Nette\Http\RequestFactory' => [['http.requestFactory']],
		'Nette\Http\IRequest' => [['http.request']],
		'Nette\Http\Request' => [['http.request']],
		'Nette\Http\IResponse' => [['http.response']],
		'Nette\Http\Response' => [['http.response']],
		'Nette\Http\Session' => [['session.session']],
		'Tracy\ILogger' => [['tracy.logger']],
		'Tracy\BlueScreen' => [['tracy.blueScreen']],
		'Tracy\Bar' => [['tracy.bar']],
		'Nette\Application\UI\Presenter' => [2 => ['application.1']],
		'Nette\Application\UI\Control' => [2 => ['application.1']],
		'Nette\Application\UI\Component' => [2 => ['application.1']],
		'Nette\ComponentModel\Container' => [2 => ['application.1']],
		'Nette\ComponentModel\Component' => [2 => ['application.1']],
		'Nette\Application\IPresenter' => [2 => ['application.1', 'application.2', 'application.3']],
		'Nette\Application\UI\Renderable' => [2 => ['application.1']],
		'ArrayAccess' => [2 => ['application.1']],
		'Nette\Application\UI\StatePersistent' => [2 => ['application.1']],
		'Nette\Application\UI\SignalReceiver' => [2 => ['application.1']],
		'Nette\ComponentModel\IContainer' => [2 => ['application.1']],
		'Nette\ComponentModel\IComponent' => [2 => ['application.1']],
		'TestPresenter' => [2 => ['application.1']],
		'NetteModule\ErrorPresenter' => [2 => ['application.2']],
		'NetteModule\MicroPresenter' => [2 => ['application.3']],
		'Nette\Routing\Router' => [['routing.router']],
	];


	/**
	 * @param  mixed[]  $params
	 */
	public function __construct(array $params = [])
	{
		parent::__construct($params);
	}


	public function createServiceApplication__1(): TestPresenter
	{
		$service = new TestPresenter;
		$service->injectPrimary(
			$this->getService('http.request'),
			$this->getService('http.response'),
			$this->getService('application.presenterFactory'),
			$this->getService('routing.router'),
			$this->getService('session.session'),
		);
		$service->invalidLinkMode = 1;
		return $service;
	}


	public function createServiceApplication__2(): NetteModule\ErrorPresenter
	{
		return new NetteModule\ErrorPresenter($this->getService('tracy.logger'));
	}


	public function createServiceApplication__3(): NetteModule\MicroPresenter
	{
		return new NetteModule\MicroPresenter($this, $this->getService('http.request'), $this->getService('routing.router'));
	}


	public function createServiceApplication__application(): Nette\Application\Application
	{
		$service = new Nette\Application\Application(
			$this->getService('application.presenterFactory'),
			$this->getService('routing.router'),
			$this->getService('http.request'),
			$this->getService('http.response'),
		);
		$service->error4xxPresenter = 'Nette:Error';
		$service->errorPresenter = 'Nette:Error';
		Nette\Bridges\ApplicationDI\ApplicationExtension::initializeBlueScreenPanel(
			$this->getService('tracy.blueScreen'),
			$service,
		);
		return $service;
	}


	public function createServiceApplication__linkGenerator(): Nette\Application\LinkGenerator
	{
		return new Nette\Application\LinkGenerator(
			$this->getService('routing.router'),
			$this->getService('http.request')->getUrl()->withoutUserInfo(),
			$this->getService('application.presenterFactory'),
		);
	}


	public function createServiceApplication__presenterFactory(): Nette\Application\IPresenterFactory
	{
		return new Nette\Application\PresenterFactory(new Nette\Bridges\ApplicationDI\PresenterFactoryCallback($this, 1, null));
	}


	public function createServiceCache__storage(): Nette\Caching\Storage
	{
		return new Nette\Caching\Storages\FileStorage('C:\Users\zdene\PhpstormProjects\translator2\tests/tmp/cache');
	}


	public function createServiceContainer(): Nette\DI\Container
	{
		return $this;
	}


	public function createServiceHttp__request(): Nette\Http\Request
	{
		return $this->getService('http.requestFactory')->fromGlobals();
	}


	public function createServiceHttp__requestFactory(): Nette\Http\RequestFactory
	{
		$service = new Nette\Http\RequestFactory;
		$service->setProxy([]);
		return $service;
	}


	public function createServiceHttp__response(): Nette\Http\Response
	{
		$service = new Nette\Http\Response;
		$service->cookieSecure = $this->getService('http.request')->isSecured();
		return $service;
	}


	public function createServiceRouting__router(): Nette\Routing\Router
	{
		return new Nette\Routing\SimpleRouter;
	}


	public function createServiceSession__session(): Nette\Http\Session
	{
		$service = new Nette\Http\Session($this->getService('http.request'), $this->getService('http.response'));
		$service->setOptions(['cookieSamesite' => 'Lax']);
		return $service;
	}


	public function createServiceTracy__bar(): Tracy\Bar
	{
		return Tracy\Debugger::getBar();
	}


	public function createServiceTracy__blueScreen(): Tracy\BlueScreen
	{
		return Tracy\Debugger::getBlueScreen();
	}


	public function createServiceTracy__logger(): Tracy\ILogger
	{
		return Tracy\Debugger::getLogger();
	}


	public function initialize(): void
	{
		// tracy.
		(function () {
			if (!Tracy\Debugger::isEnabled()) { return; }
			$logger = $this->getService('tracy.logger');
			$keysToHide = [];
			array_push(Tracy\Debugger::$keysToHide, ...$keysToHide);
			array_push(Tracy\Debugger::getBlueScreen()->keysToHide, ...$keysToHide);;
		})();
	}


	/**
	 * @return mixed[]
	 */
	protected function getStaticParameters(): array
	{
		return [
			'appDir' => 'C:\Users\zdene\PhpstormProjects\translator2\tests',
			'wwwDir' => 'C:\Users\zdene\PhpstormProjects\translator2\tests\Translator',
			'vendorDir' => 'C:\Users\zdene\PhpstormProjects\translator2\vendor',
			'rootDir' => 'C:\Users\zdene\PhpstormProjects\translator2',
			'debugMode' => false,
			'productionMode' => true,
			'consoleMode' => true,
			'tempDir' => 'C:\Users\zdene\PhpstormProjects\translator2\tests/tmp',
		];
	}


	protected function getDynamicParameter(string|int $key): mixed
	{
		return match($key) {
			'baseUrl' => trim($this->getByType('Nette\Http\IRequest')->getUrl()->getBaseUrl(), "/"),
			default => parent::getDynamicParameter($key),
		};
	}


	/**
	 * @return mixed[]
	 */
	public function getParameters(): array
	{
		array_map($this->getParameter(...), ['baseUrl']);
		return parent::getParameters();
	}
}
