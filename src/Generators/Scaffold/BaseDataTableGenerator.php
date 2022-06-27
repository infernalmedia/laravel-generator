<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

class BaseDataTableGenerator extends BaseGenerator
{
    const CLASS_NAME = 'BaseDataTable';
    const STUB_FILE_NAME = 'base_data_table';

    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = config('infyom.laravel_generator.path.datatables', app_path('DataTables/'));
    }

    public function generate()
    {
        $templateData = get_template("scaffold.datatables." . self::STUB_FILE_NAME, 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        FileUtil::createFile($this->path, $this->getFileName(), $templateData);

        $this->commandData->commandObj->comment("\abstract class BaseDataTable created: ");
        $this->commandData->commandObj->info($this->getFileName());
    }

    private function fillTemplate($templateData)
    {
        return fill_template($this->commandData->dynamicVars, $templateData);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->getFileName())) {
            $this->commandData->commandComment('abstract class BaseDataTable file deleted: ' . $this->getFileName());
        }
    }

    private function getFileName()
    {
        return self::CLASS_NAME . ".php";
    }
}
