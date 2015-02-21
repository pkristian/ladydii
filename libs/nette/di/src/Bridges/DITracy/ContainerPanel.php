<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Bridges\DITracy;

use Nette,
	Nette\DI\Container,
	Tracy;


/**
 * Dependency injection container panel for Debugger Bar.
 *
 * @author     Patrik Votoček
 */
class ContainerPanel extends Nette\Object implements Tracy\IBarPanel
{
	/** @var Nette\DI\Container */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * Renders tab.
	 * @return string
	 */
	public function getTab()
	{
		ob_start();
		require __DIR__ . '/templates/ContainerPanel.tab.phtml';
		return ob_get_clean();
	}


	/**
	 * Renders panel.
	 * @return string
	 */
	public function getPanel()
	{
		$container = $this->container;
		$registry = $this->getContainerProperty('registry');
		$tags = array();
		$meta = $this->getContainerProperty('meta');
		$services = $meta[Container::SERVICES];
		ksort($services);
		if (isset($meta[Container::TAGS])) {
			foreach ($meta[Container::TAGS] as $tag => $tmp) {
				foreach ($tmp as $service => $val) {
					$tags[$service][$tag] = $val;
				}
			}
		}

		ob_start();
		require __DIR__ . '/templates/ContainerPanel.panel.phtml';
		return ob_get_clean();
	}


	private function getContainerProperty($name)
	{
		$rc = new \ReflectionClass('Nette\DI\Container');
		$prop = $rc->getProperty($name);
		$prop->setAccessible(TRUE);
		return $prop->getValue($this->container);
	}

}