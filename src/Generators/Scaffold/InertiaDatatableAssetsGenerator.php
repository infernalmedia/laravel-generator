<?php

namespace InfyOm\Generator\Generators\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;

/**
 * Class InertiaDatatableAssetsGenerator.
 */
class InertiaDatatableAssetsGenerator extends BaseGenerator
{
    /** @var CommandData */
    private $commandData;

    private $config;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->config = $this->commandData->config;
    }

    public function generate()
    {
        $this->generateInertiaTable();
    }

    public function generateInertiaTable()
    {

        // Publish Inertia JS file
        $createAppTemplate = 'inertia_data_table';
        $templateData = get_template('scaffold.' . $createAppTemplate, 'laravel-generator');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $path =  $this->config->jsPath . '/';
        if (!file_exists($path)) {
            FileUtil::createDirectoryIfNotExist($path);
        }
        file_put_contents($path . $this->convertStubToFileName($createAppTemplate, 'js'), $templateData);
        $this->commandData->commandComment("\n" . "InertiaDataTable" . ' assets added.');

        // Publish DefaultTable Template
        $defaultTableTemplate = 'default_table';
        $templateData = get_template('scaffold.' . $defaultTableTemplate, 'laravel-generator');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        $path = $this->config->jsPath . 'dataTables/';
        if (!file_exists($path)) {
            FileUtil::createDirectoryIfNotExist($path);
        }

        file_put_contents($path . $this->convertStubToFileName($defaultTableTemplate, 'vue'), $templateData);
        $this->commandData->commandComment("\n" . 'DefaultTable Template added.');

        // Publish Webpack mix lines
        $webpackMixContents = file_get_contents(base_path('webpack.mix.js'));
        $templateName = 'webpack_mix_js';
        $templateData = get_template('scaffold.' . $templateName, 'laravel-generator');
        $templateData = fill_template($this->commandData->dynamicVars, $templateData);
        $webpackMixContents .= "\n\n" . $templateData;

        file_put_contents(base_path('webpack.mix.js'), $webpackMixContents);
        $this->commandData->commandComment("\n" . $this->commandData->config->mCamelPlural . ' webpack.mix.js updated.');
    }

    private function convertStubToFileName($fileName, string $extension): string
    {
        $fileName = Str::replaceLast(".stub", '', $fileName);
        $fileName = Str::camel($fileName);
        return Str::ucfirst($fileName) . ".{$extension}";
    }
}
