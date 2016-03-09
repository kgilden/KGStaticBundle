<?php

namespace KG\StaticBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

class StaticExtension extends \Twig_Extension
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel) {
        $this->kernel = $kernel;
    }

    /**
     * {@inherit-Doc}
     */
    public function getFunctions()
    {
        return array(
            'file' => new \Twig_SimpleFunction('file', [$this, 'file'])
        );
    }

    /**
     * Returns the contents of a file to the template.
     *
     * @param string $path    A logical path to the file (e.g '@AcmeFooBundle:Foo:resource.txt').
     *
     * @return string         The contents of a file.
     */
    public function file($path)
    {
        $path = $this->kernel->locateResource($path, null, true);

        return file_get_contents($path);
    }

    public function getName()
    {
        return 'StaticExtension';
    }
}