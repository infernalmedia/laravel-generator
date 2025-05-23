<?php

namespace InfyOm\Generator\Generators\Scaffold;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Generators\ModelGenerator;
use InfyOm\Generator\Utils\FileUtil;

class RequestGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $commonFileName;

    /** @var string */
    private $createFileName;

    /** @var string */
    private $updateFileName;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathRequest . $commandData->config->mName . '/';
        if (!file_exists($this->path)) {
            mkdir($this->path, 0755, true);
        }
        $this->commonFileName = $this->commandData->modelName . 'Request.php';
        $this->createFileName = 'Create' . $this->commandData->modelName . 'Request.php';
        $this->updateFileName = 'Update' . $this->commandData->modelName . 'Request.php';
    }

    public function generate(): void
    {
        $this->generateCommonRequest();
        $this->generateCreateRequest();
        $this->generateUpdateRequest();
    }

    private function generateCommonRequest(): void
    {
        $templateData = get_template('scaffold.request.common_request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile(
            $this->path,
            $this->commonFileName,
            $templateData
        );

        $this->commandData->commandComment("\nCommon Request created: ");
        $this->commandData->commandInfo($this->commonFileName);
    }

    private function generateCreateRequest(): void
    {
        $templateData = get_template('scaffold.request.create_request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile(
            $this->path,
            $this->createFileName,
            $templateData
        );

        $this->commandData->commandComment("\nCreate Request created: ");
        $this->commandData->commandInfo($this->createFileName);
    }

    private function generateUpdateRequest(): void
    {
        $modelGenerator = new ModelGenerator($this->commandData);
        $rules = $modelGenerator->generateUniqueRules();
        $this->commandData->addDynamicVariable('$UNIQUE_RULES$', $rules);

        $templateData = get_template('scaffold.request.update_request', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile(
            $this->path,
            $this->updateFileName,
            $templateData
        );

        $this->commandData->commandComment("\nUpdate Request created: ");
        $this->commandData->commandInfo($this->updateFileName);
    }

    public function rollback(): void
    {
        if ($this->rollbackFile($this->path, $this->createFileName)) {
            $this->commandData->commandComment('Create API Request file deleted: ' . $this->createFileName);
        }

        if ($this->rollbackFile($this->path, $this->updateFileName)) {
            $this->commandData->commandComment('Update API Request file deleted: ' . $this->updateFileName);
        }
    }
}
