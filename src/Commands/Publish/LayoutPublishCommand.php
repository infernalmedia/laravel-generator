<?php

namespace InfyOm\Generator\Commands\Publish;

use Illuminate\Support\Str;
use InfyOm\Generator\Utils\FileUtil;
use Symfony\Component\Console\Input\InputOption;

class LayoutPublishCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom.publish:layout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes auth files';

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->copyView();
        $this->publishHomeController();
    }

    private function copyView(): void
    {
        $viewsPath = config('infyom.laravel_generator.path.views', resource_path('views/'));
        $templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');

        $this->createDirectories($viewsPath);

        $files = $this->option('localized') ? $this->getLocaleViews() : $this->getViews();

        foreach ($files as $stub => $blade) {
            $sourceFile = get_template_file_path('scaffold/' . $stub, $templateType);
            $destinationFile = $viewsPath . $blade;
            $this->publishFile($sourceFile, $destinationFile, $blade);
        }
    }

    private function createDirectories($viewsPath): void
    {
        FileUtil::createDirectoryIfNotExist($viewsPath . 'layouts');
        FileUtil::createDirectoryIfNotExist($viewsPath . 'auth');

        FileUtil::createDirectoryIfNotExist($viewsPath . 'auth/passwords');
        FileUtil::createDirectoryIfNotExist($viewsPath . 'auth/emails');
    }

    /**
     * @return mixed[]
     */
    private function getViews(): array
    {
        $views = [
            'layouts/app'               => 'layouts/app.blade.php',
            'layouts/sidebar'           => 'layouts/sidebar.blade.php',
            'layouts/datatables_css'    => 'layouts/datatables_css.blade.php',
            'layouts/datatables_js'     => 'layouts/datatables_js.blade.php',
            'layouts/menu'              => 'layouts/menu.blade.php',
            'layouts/home'              => 'home.blade.php',
            'auth/login'                => 'auth/login.blade.php',
            'auth/register'             => 'auth/register.blade.php',
            'auth/passwords/confirm'    => 'auth/passwords/confirm.blade.php',
            'auth/passwords/email'      => 'auth/passwords/email.blade.php',
            'auth/passwords/reset'      => 'auth/passwords/reset.blade.php',
            'auth/emails/password'      => 'auth/emails/password.blade.php',
        ];

        $version = $this->getApplication()->getVersion();
        if (Str::contains($version, '6.')) {
            $verifyView = [
                'auth/verify_6' => 'auth/verify.blade.php',
            ];
        } else {
            $verifyView = [
                'auth/verify' => 'auth/verify.blade.php',
            ];
        }

        return array_merge($views, $verifyView);
    }

    private function getLocaleViews(): array
    {
        return [
            'layouts/app_locale'           => 'layouts/app.blade.php',
            'layouts/sidebar_locale'       => 'layouts/sidebar.blade.php',
            'layouts/datatables_css'       => 'layouts/datatables_css.blade.php',
            'layouts/datatables_js'        => 'layouts/datatables_js.blade.php',
            'layouts/menu'                 => 'layouts/menu.blade.php',
            'layouts/home'                 => 'home.blade.php',
            'auth/login_locale'            => 'auth/login.blade.php',
            'auth/register_locale'         => 'auth/register.blade.php',
            'auth/passwords/email_locale'  => 'auth/passwords/email.blade.php',
            'auth/passwords/reset_locale'  => 'auth/passwords/reset.blade.php',
            'auth/emails/password_locale'  => 'auth/emails/password.blade.php',
        ];
    }

    private function publishHomeController(): void
    {
        $templateData = get_template('home_controller', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $controllerPath = config('infyom.laravel_generator.path.controller', app_path('Http/Controllers/'));

        $fileName = 'HomeController.php';

        if (file_exists($controllerPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($controllerPath, $fileName, $templateData);

        $this->info('HomeController created');
    }

    /**
     * Replaces dynamic variables of template.
     *
     * @param string $templateData
     */
    private function fillTemplate($templateData): string
    {
        $templateData = str_replace(
            '$NAMESPACE_CONTROLLER$',
            config('infyom.laravel_generator.namespace.controller'),
            $templateData
        );

        return str_replace(
            '$NAMESPACE_REQUEST$',
            config('infyom.laravel_generator.namespace.request'),
            $templateData
        );
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
