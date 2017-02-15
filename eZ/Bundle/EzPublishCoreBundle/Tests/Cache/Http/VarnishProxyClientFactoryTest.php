<?php

/**
 * File containing the VarnishProxyClientFactoryTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace eZ\Bundle\EzPublishCoreBundle\Tests\Cache\Http;

use eZ\Bundle\EzPublishCoreBundle\Cache\Http\VarnishProxyClientFactory;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\DynamicSettingParser;
use PHPUnit_Framework_TestCase;
use ReflectionObject;

class VarnishProxyClientFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $configResolver;

    /**
     * @var string
     */
    private $proxyClientClass;

    /**
     * @var string
     */
    private $httpDispatcherClass;

    /**
     * @var VarnishProxyClientFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->configResolver = $this->getMock('\eZ\Publish\Core\MVC\ConfigResolverInterface');
        $this->proxyClientClass = '\FOS\HttpCache\ProxyClient\Varnish';
        $this->httpDispatcherClass = 'FOS\HttpCache\ProxyClient\HttpDispatcher';
        $this->factory = new VarnishProxyClientFactory($this->configResolver, new DynamicSettingParser(), $this->proxyClientClass);
    }

    private static function filterUri($uri) {
        if (is_array($uri)) {
            $result = [];
            foreach ($uri as $value) {
                $result[] = $value->__toString();
            }
            return $result;
        }
	else if (is_object($uri)) {
            return $uri->__toString();
	}
	return $uri;
    }

    public function testBuildProxyClientNoDynamicSettings()
    {
        $servers = array('http://varnish1', 'http://varnish2');
        $baseUrl = 'http://phoenix-rises.fm/rapmm';
        $this->configResolver
            ->expects($this->never())
            ->method('getParameter');

        $proxyClient = $this->factory->buildProxyClient($servers, $baseUrl);
        $this->assertInstanceOf($this->proxyClientClass, $proxyClient);

        $refProxy = new ReflectionObject($proxyClient);
        $propHttpDispatcher = $refProxy->getParentClass()->getProperty('httpDispatcher');
        $propHttpDispatcher->setAccessible(true);

        $httpDispatcher = $propHttpDispatcher->getValue($proxyClient);
        $this->assertInstanceOf($this->httpDispatcherClass, $httpDispatcher);

        $refHttpDispatcher = new ReflectionObject($httpDispatcher);
        $refServers = $refHttpDispatcher->getProperty('servers');
        $refServers->setAccessible(true);
        $this->assertSame($servers, self::filterUri($refServers->getValue($httpDispatcher)));

        $refBaseUri = $refHttpDispatcher->getProperty('baseUri');
        $refBaseUri->setAccessible(true);
        $this->assertSame($baseUrl, self::filterUri($refBaseUri->getValue($httpDispatcher)));
    }

    public function testBuildProxyClientWithDynamicSettings()
    {
        $servers = array('$http_cache.purge_servers$', 'http://varnish2');
        $configuredServers = array('http://varnishconfigured1', 'http://varnishconfigured2');
        $expectedServers = array('http://varnishconfigured1', 'http://varnishconfigured2', 'http://varnish2');
        $baseUrl = 'http://phoenix-rises.fm/rapmm';
        $this->configResolver
            ->expects($this->once())
            ->method('getParameter')
            ->with('http_cache.purge_servers')
            ->will($this->returnValue($configuredServers));

        $proxyClient = $this->factory->buildProxyClient($servers, $baseUrl);
        $this->assertInstanceOf($this->proxyClientClass, $proxyClient);

        $refProxy = new ReflectionObject($proxyClient);
        $propHttpDispatcher = $refProxy->getParentClass()->getProperty('httpDispatcher');
        $propHttpDispatcher->setAccessible(true);

        $httpDispatcher = $propHttpDispatcher->getValue($proxyClient);
        $this->assertInstanceOf($this->httpDispatcherClass, $httpDispatcher);

        $refHttpDispatcher = new ReflectionObject($httpDispatcher);
        $refServers = $refHttpDispatcher->getProperty('servers');
        $refServers->setAccessible(true);
        $this->assertSame($expectedServers, self::filterUri($refServers->getValue($httpDispatcher)));

        $refBaseUri = $refHttpDispatcher->getProperty('baseUri');
        $refBaseUri->setAccessible(true);
        $this->assertSame($baseUrl, self::filterUri($refBaseUri->getValue($httpDispatcher)));

    }
}
