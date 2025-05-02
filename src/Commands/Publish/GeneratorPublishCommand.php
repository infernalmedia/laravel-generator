<?php

namespace InfyOm\Generator\Commands\Publish;

use InfyOm\Generator\Utils\FileUtil;
use Symfony\Component\Console\Input\InputOption;

class GeneratorPublishCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes & init api routes, base controller, base test cases traits.';

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
     * Execute the command.
     */
    public function handle(): void
    {
        $this->publishTestCases();
        $this->publishBaseController();
        $repositoryPattern = config('infyom.laravel_generator.options.repository_pattern', true);
        if ($repositoryPattern) {
            $this->publishBaseRepository();
        }

        if ($this->option('localized')) {
            $this->publishLocaleFiles();
        }

        $this->publishTraitsFiles();
    }

    /**
     * Replaces dynamic variables of template.
     *
     * @param string $templateData
     */
    private function fillTemplate($templateData): string
    {
        $apiVersion = config('infyom.laravel_generator.api_version', 'v1');
        $apiPrefix = config('infyom.laravel_generator.api_prefix', 'api');

        $templateData = str_replace('$API_VERSION$', $apiVersion, $templateData);
        $templateData = str_replace('$API_PREFIX$', $apiPrefix, $templateData);

        $appNamespace = $this->getLaravel()->getNamespace();
        $appNamespace = substr($appNamespace, 0, strlen($appNamespace) - 1);

        return str_replace('$NAMESPACE_APP$', $appNamespace, $templateData);
    }

    private function publishTestCases(): void
    {
        $this->publishTestTraits();

        $testAPIsPath = config('infyom.laravel_generator.path.api_test', base_path('tests/APIs/'));
        if (!file_exists($testAPIsPath)) {
            FileUtil::createDirectoryIfNotExist($testAPIsPath);
            $this->info('APIs Tests directory created');
        }

        $testRepositoriesPath = config('infyom.laravel_generator.path.repository_test', base_path('tests/Repositories/'));
        if (!file_exists($testRepositoriesPath)) {
            FileUtil::createDirectoryIfNotExist($testRepositoriesPath);
            $this->info('Repositories Tests directory created');
        }

        $testControllersPath = config('infyom.laravel_generator.path.controller_test', base_path('tests/Controllers/'));
        if (!file_exists($testControllersPath)) {
            FileUtil::createDirectoryIfNotExist($testRepositoriesPath);
            $this->info('Controllers Tests directory created');
        }
    }

    private function publishTestTraits(): void
    {
        $testsPath = config('infyom.laravel_generator.path.tests', base_path('tests/'));
        $testsNameSpace = config('infyom.laravel_generator.namespace.tests', 'Tests');
        $createdAtField = config('infyom.laravel_generator.timestamps.created_at', 'created_at');
        $updatedAtField = config('infyom.laravel_generator.timestamps.updated_at', 'updated_at');

        foreach ($this->testTraits as $stubFileName => $className) {
            $templateData = get_template('test.' . $stubFileName, 'laravel-generator');
            $templateData = str_replace('$NAMESPACE_TESTS$', $testsNameSpace, $templateData);
            $templateData = str_replace('$TIMESTAMPS$', sprintf("['%s', '%s']", $createdAtField, $updatedAtField), $templateData);

            $fileName = $className . '.php';

            if (file_exists(sprintf('%s.%s', $testsPath, $fileName)) && !$this->confirmOverwrite($fileName)) {
                return;
            }

            FileUtil::createFile($testsPath, $fileName, $templateData);
            $this->info($className . ' test trait created');
        }
    }

    private function publishBaseController(): void
    {
        $templateData = get_template('app_base_controller', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $controllerPath = app_path('Http/Controllers/');

        $fileName = 'AppBaseController.php';

        if (file_exists($controllerPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($controllerPath, $fileName, $templateData);

        $this->info('AppBaseController created');
    }

    private function publishBaseRepository(): void
    {
        $templateData = get_template('base_repository', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $repositoryPath = app_path('Repositories/');

        FileUtil::createDirectoryIfNotExist($repositoryPath);

        $fileName = 'BaseRepository.php';

        if (file_exists($repositoryPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($repositoryPath, $fileName, $templateData);

        $this->info('BaseRepository created');
    }

    private function publishLocaleFiles(): void
    {
        $localesDir = __DIR__ . '/../../../locale/';

        $this->publishDirectory($localesDir, resource_path('lang'), 'lang', true);

        $this->comment('Locale files published');
    }

    private function publishTraitsFiles(): void
    {
        $traitsDir = __DIR__ . '/../../../traits/';

        $this->publishDirectory($traitsDir, app_path('Traits/'), 'Traits', true);

        $this->comment('Locale files published');
    }

    /**
     * Get the console command options.
     */
    public function getOptions(): array
    {
        return [
            ['localized', null, InputOption::VALUE_NONE, 'Localize files.'],
        ];
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [];
    }
}
