<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class CrudControllerTraitGenerator extends BaseGenerator
{
    const CLASS_NAME = 'CrudControllerTrait';
    const STUB_FILE_NAME = 'crud_controller_trait';

    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = config('infyom.laravel_generator.path.traits', (app_path('Traits/')));
    }

    public function generate()
    {
        $templateData = get_template("scaffold.traits." . self::STUB_FILE_NAME, 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        FileUtil::createFile($this->path, $this->getFileName(), $templateData);

        $this->commandData->commandObj->comment("\Trait created: ");
        $this->commandData->commandObj->info($this->getFileName());
    }

    private function fillTemplate($templateData)
    {
        return fill_template($this->commandData->dynamicVars, $templateData);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->getFileName())) {
            $this->commandData->commandComment('Trait file deleted: ' . $this->getFileName());
        }
    }

    private function getFileName()
    {
        return self::CLASS_NAME . ".php";
    }
}
