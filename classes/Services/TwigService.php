<?php

namespace Avado\MoodleAbstractionLibrary\Services;

use Monolog\Logger;
use Twig\Error\Error;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Class TwigService
 * @package Avado\MoodleAbstractionLibrary\Services
 */
class TwigService
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * TwigService constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment, Logger $logger)
    {
        $this->environment = $environment;
        $this->logger = $logger;
    }

    /**
     * @param string $templateName
     * @param array  $templateData Values to be switched out for template placeholders
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function process(string $templateName, array $templateData = [], bool $needHeaderFooter = true): string
    {
        try {
            $loader = $this->environment->getLoader();
            $loader->addPath(dirname($templateName));
            $emailBody = $this->environment->render(basename($templateName), $templateData);
            return $emailBody;
        } catch (Error $e) {
            $location = get_class($this) . ":process()";
            $trace = $e->getTraceAsString();
            $this->logger->debug("Error in $location. $trace");
        }
    }
}
