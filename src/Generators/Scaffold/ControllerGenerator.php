<?php

namespace InfyOm\Generator\Generators\Scaffold;

use Illuminate\Support\Arr;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;


class ControllerGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $fileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathController;
        $this->templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');
        $this->fileName = $this->commandData->modelName . 'Controller.php';
    }

    public function generate()
    {
        if ($this->commandData->getAddOn('datatables')) {
            if ($this->commandData->getOption('repositoryPattern')) {
                $templateName = 'datatable_controller';
            } else {
                $templateName = 'model_datatable_controller';
            }

            if ($this->commandData->isLocalizedTemplates()) {
                $templateName .= '_locale';
            }

            $templateData = get_template("scaffold.controller.$templateName", 'laravel-generator');

            $this->generateDataTable();
        } elseif ($this->commandData->jqueryDT()) {
            $templateName = 'jquery_datatable_controller';
            $templateData = get_template("scaffold.controller.$templateName", 'laravel-generator');

            $this->generateDataTable();
        } else {
            if ($this->commandData->getOption('repositoryPattern')) {
                $templateName = 'controller';
            } else {
                $templateName = 'model_controller';
            }
            if ($this->commandData->isLocalizedTemplates()) {
                $templateName .= '_locale';
            }

            $templateData = get_template("scaffold.controller.$templateName", 'laravel-generator');

            $paginate = $this->commandData->getOption('paginate');

            if ($paginate) {
                $templateData = str_replace('$RENDER_TYPE$', 'paginate(' . $paginate . ')', $templateData);
            } else {
                $templateData = str_replace('$RENDER_TYPE$', 'all()', $templateData);
            }
        }

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateDataTable()
    {
        $templateName = ($this->commandData->jqueryDT()) ? 'jquery_datatable' : 'datatable';
        if ($this->commandData->isLocalizedTemplates()) {
            $templateName .= '_locale';
        }

        $templateFile = get_template('scaffold.' . $templateName, 'laravel-generator');
        $filteredFields = collect($this->commandData->fields)
            ->where('inIndex', '=', true)
            ->pluck('name');

        $this->commandData->dynamicVars['$DATATABLE_DEFAULT_SORTING$'] = "'" . $filteredFields->first() . "'";

        $filteredFieldsArrayToString =  $filteredFields->reduce(function ($carry, $value, $key) use ($filteredFields) {
            if (($key + 1) === $filteredFields->count()) {
                return $carry .= "'" . $value . "']";
            }
            return $carry .= "'" . $value . "', ";
        }, "[");

        $this->commandData->dynamicVars['$DATATABLE_COLUMNS$'] =  $filteredFieldsArrayToString;

        $templateData = fill_template($this->commandData->dynamicVars, $templateFile);
        $dataTableFilePath = $this->commandData->config->pathDataTables;

        $dataTableFileName = $this->commandData->modelName . 'DataTable.php';

        FileUtil::createFile($dataTableFilePath, $dataTableFileName, $templateData);

        $this->commandData->commandComment("\nDataTable created: ");
        $this->commandData->commandInfo($dataTableFileName);
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Controller file deleted: ' . $this->fileName);
        }

        if ($this->commandData->getAddOn('datatables')) {
            if ($this->rollbackFile(
                $this->commandData->config->pathDataTables,
                $this->commandData->modelName . 'DataTable.php'
            )) {
                $this->commandData->commandComment('DataTable file deleted: ' . $this->fileName);
            }
        }
    }
}
