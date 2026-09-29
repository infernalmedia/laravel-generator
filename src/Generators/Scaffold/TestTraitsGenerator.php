<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class TestTraitsGenerator extends BaseGenerator
{
    /** @var string */
    private $path;

    /**
     * Test traits to publish
     *
     * @var array
     */
    private $testTraits = [
        'api_test_trait' => 'ApiTestTrait',
        'model_test_trait' => 'ModelTestTrait',
    ];

    /**
     * @param CommandData $commandData
     */
    public function __construct(private $commandData)
    {
        $this->path = config('infyom.laravel_generator.path.test_traits', base_path('tests/'));
    }

    public function generate(): void
    {
        foreach ($this->testTraits as $stubFileName => $className) {
            $templateData = get_template('test.' . $stubFileName, 'laravel-generator');

            $templateData = $this->fillTemplate($templateData);

            FileUtil::createFile($this->path, $this->getFileName($className), $templateData);

            $this->commandData->commandObj->comment("\nTest Traits created: ");
            $this->commandData->commandObj->info($this->getFileName($className));
        }
    }

    private function fillTemplate($templateData)
    {
        return fill_template($this->commandData->dynamicVars, $templateData);
    }

    public function rollback(): void
    {
        foreach ($this->testTraits as $className) {
            if ($this->rollbackFile($this->path, $this->getFileName($className))) {
                $this->commandData->commandComment('Test trait file deleted: ' . $this->getFileName($className));
            }
        }
    }

    private function getFileName(string $className): string
    {
        return $className . '.php';
    }
}
