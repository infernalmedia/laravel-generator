<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class FeatureTestCaseGenerator extends BaseGenerator
{
    const CLASS_NAME = 'FeatureTestCase';

    const STUB_FILE_NAME = 'feature_test_case';

    /** @var string */
    private $path;

    /**
     * @param CommandData $commandData
     */
    public function __construct(private $commandData)
    {
        $this->path = config('infyom.laravel_generator.path.tests', base_path('tests/'));
    }

    public function generate(): void
    {
        $templateData = get_template("test." . self::STUB_FILE_NAME, 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        FileUtil::createFile($this->path, $this->getFileName(), $templateData);

        $this->commandData->commandObj->comment("\Abstract class for Test classes created: ");
        $this->commandData->commandObj->info($this->getFileName());
    }

    private function fillTemplate($templateData)
    {
        return fill_template($this->commandData->dynamicVars, $templateData);
    }

    public function rollback(): void
    {
        if ($this->rollbackFile($this->path, $this->getFileName())) {
            $this->commandData->commandComment('Abstract class for Test classes file deleted: ' . $this->getFileName());
        }
    }

    private function getFileName(): string
    {
        return self::CLASS_NAME . ".php";
    }
}
