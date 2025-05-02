<?php

namespace InfyOm\Generator\Commands\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Generators\Scaffold\TestTraitsGenerator;
use InfyOm\Generator\Generators\Scaffold\ControllerTestGenerator;
use InfyOm\Generator\Generators\Scaffold\FeatureTestCaseGenerator;

class TestsGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom.scaffold:tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tests command';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_API);
    }

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        parent::handle();

        $featureTestCaseGenerator = new FeatureTestCaseGenerator($this->commandData);
        $featureTestCaseGenerator->generate();

        $testTraitsGenerator = new TestTraitsGenerator($this->commandData);
        $testTraitsGenerator->generate();

        $controllerTestGenerator = new ControllerTestGenerator($this->commandData);
        $controllerTestGenerator->generate();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }
}
