<?php

namespace InfyOm\Generator\Generators;

use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Utils\FileUtil;

/**
 * Class SeederGenerator.
 */
class SeederGenerator extends BaseGenerator
{
    /** @var string */
    private $path;

    private $fileName;

    /**
     * ModelGenerator constructor.
     */
    public function __construct(private readonly CommandData $commandData)
    {
        $this->path = $this->commandData->config->pathSeeder;
        $this->fileName = $this->commandData->config->mPlural . 'TableSeeder.php';
    }

    public function generate(): void
    {
        $templateData = get_template('seeds.model_seeder', 'laravel-generator');

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nSeeder created: ");
        $this->commandData->commandInfo($this->fileName);
    }
}
