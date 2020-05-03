<?php

namespace Avado\MoodleAbstractionLibrary\Tinker;

include_once __DIR__.'/../../vendor/autoload.php';

use Avado\MoodleAbstractionLibrary\DependencyInjection\Container;
use Psy\Configuration;
use Psy\Shell;
use Psy\VersionUpdater\Checker;

/**
 * Class Tinker
 * @package Avado\MoodleAbstractionLibrary\Tinker
 */
class Tinker {

    /**
     * @var
     */
    protected $currentDirectory;

    /**
     * @var null
     */
    protected $config;

    /**
     * @var array
     */
    protected $casters = [
        'Illuminate\Database\Eloquent\Collection' => 'Avado\MoodleAbstractionLibrary\Tinker\TinkerCaster::castCollection',
        'Illuminate\Database\Eloquent\Model'      => 'Avado\MoodleAbstractionLibrary\Tinker\TinkerCaster::castModel'
    ];

    /**
     * Tinker constructor.
     *
     * @param $currentDirectory
     * @param $config
     */
    public function __construct($currentDirectory, array $config = [])
    {
        $this->currentDirectory = $currentDirectory;
        $this->config = $config;
    }

    /**
     * Build a container to connect to the DB, add shell configurations for output, start shell session.
     */
    public function run()
    {
        $this->buildContainer();
        $config = $this->buildShellConfig();
        $shell = new Shell($config);
        $shell->run();
    }

    /**
     * @throws \Exception
     */
    protected function buildContainer()
    {
        (new Container($this->currentDirectory))->buildContainer();
    }

    /**
     * Build Configuration object and add casters - this is what renders specified objects as arrays.
     *
     * @return Configuration
     */
    protected function buildShellConfig()
    {
        $config = new Configuration($this->config);
        $config->setUpdateCheck(Checker::NEVER);
        $config->getPresenter()->addCasters($this->casters);

        return $config;
    }
}
