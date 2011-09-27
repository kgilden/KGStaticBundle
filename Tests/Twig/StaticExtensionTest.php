<?php

namespace KG\StaticBundle\Tests\Twig;

use KG\StaticBundle\Twig\StaticExtension;

class StaticExtensionTest extends \PHPUnit_Framework_TestCase
{
    static protected $templates;

    public function setUp()
    {
        self::$templates = array(
            'basic' => "{{ file('KGStaticBundle/Tests/Twig/test.txt') }}"
        );
    }

    public function testStaticFileLoad()
    {
        $twig = $this->getEnvironment(true, array(), self::$templates, array(), array(), array(), array(), array('file'));
        $twig->addExtension(new StaticExtension($this->getMockKernel(__DIR__.'/test.txt')));

        $this->assertEquals('Hello, world!', $twig->loadTemplate('basic')->render(array()));
    }

    protected function getEnvironment($sandboxed, $options, $templates, $tags = array(), $filters = array(), $methods = array(), $properties = array(), $functions = array())
    {
        $loader = new \Twig_Loader_Array($templates);
        $twig = new \Twig_Environment($loader, array_merge(array('debug' => true, 'cache' => false, 'autoescape' => false), $options));
        $policy = new \Twig_Sandbox_SecurityPolicy($tags, $filters, $methods, $properties, $functions);
        $twig->addExtension(new \Twig_Extension_Sandbox($policy, $sandboxed));

        return $twig;
    }

    protected function getMockKernel($path)
    {
        $kernel = $this->getMock('\Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->any())
            ->method('locateResource')
            ->will($this->returnValue($path));

        return $kernel;
    }
}