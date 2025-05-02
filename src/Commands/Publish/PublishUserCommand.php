<?php

namespace InfyOm\Generator\Commands\Publish;

use InfyOm\Generator\Utils\FileUtil;

class PublishUserCommand extends PublishBaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infyom.publish:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publishes Users CRUD file';

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->copyViews();
        $this->updateRoutes();
        $this->updateMenu();
        $this->publishUserController();
        if (config('infyom.laravel_generator.options.repository_pattern')) {
            $this->publishUserRepository();
        }

        $this->publishCreateUserRequest();
        $this->publishUpdateUserRequest();
    }

    private function copyViews(): void
    {
        $viewsPath = config('infyom.laravel_generator.path.views', resource_path('views/'));
        $templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');

        $this->createDirectories($viewsPath . 'users');

        $files = $this->getViews();

        foreach ($files as $stub => $blade) {
            $sourceFile = get_template_file_path('scaffold/' . $stub, $templateType);
            $destinationFile = $viewsPath . $blade;
            $this->publishFile($sourceFile, $destinationFile, $blade);
        }
    }

    private function createDirectories($dir): void
    {
        FileUtil::createDirectoryIfNotExist($dir);
    }

    private function getViews(): array
    {
        return [
            'users/create'      => 'users/create.blade.php',
            'users/edit'        => 'users/edit.blade.php',
            'users/fields'      => 'users/fields.blade.php',
            'users/index'       => 'users/index.blade.php',
            'users/show'        => 'users/show.blade.php',
            'users/show_fields' => 'users/show_fields.blade.php',
            'users/table'       => 'users/table.blade.php',
        ];
    }

    private function updateRoutes(): void
    {
        $path = config('infyom.laravel_generator.path.routes', base_path('routes/web.php'));

        $routeContents = file_get_contents($path);

        $routesTemplate = get_template('routes.user', 'laravel-generator');

        $routeContents .= "\n\n" . $routesTemplate;

        file_put_contents($path, $routeContents);
        $this->comment("\nUser route added");
    }

    private function updateMenu(): void
    {
        $viewsPath = config('infyom.laravel_generator.path.views', resource_path('views/'));
        $templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');
        $path = $viewsPath . 'layouts/menu.blade.php';
        $menuContents = file_get_contents($path);
        $sourceFile = file_get_contents(get_template_file_path('scaffold/users/menu', $templateType));
        $menuContents .= "\n" . $sourceFile;

        file_put_contents($path, $menuContents);
        $this->comment("\nUser Menu added");
    }

    private function publishUserController(): void
    {
        $templateData = get_template('user/user_controller', 'laravel-generator');
        if (!config('infyom.laravel_generator.options.repository_pattern')) {
            $templateData = get_template('user/user_controller_without_repository', 'laravel-generator');
            $templateData = $this->fillTemplate($templateData);
        }

        $templateData = $this->fillTemplate($templateData);

        $controllerPath = config('infyom.laravel_generator.path.controller', app_path('Http/Controllers/'));

        $fileName = 'UserController.php';

        if (file_exists($controllerPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($controllerPath, $fileName, $templateData);

        $this->info('UserController created');
    }

    private function publishUserRepository(): void
    {
        $templateData = get_template('user/user_repository', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $repositoryPath = config('infyom.laravel_generator.path.repository', app_path('Repositories/'));

        $fileName = 'UserRepository.php';

        FileUtil::createDirectoryIfNotExist($repositoryPath);

        if (file_exists($repositoryPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($repositoryPath, $fileName, $templateData);

        $this->info('UserRepository created');
    }

    private function publishCreateUserRequest(): void
    {
        $templateData = get_template('user/create_user_request', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $requestPath = config('infyom.laravel_generator.path.request', app_path('Http/Requests/'));

        $fileName = 'CreateUserRequest.php';

        FileUtil::createDirectoryIfNotExist($requestPath);

        if (file_exists($requestPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($requestPath, $fileName, $templateData);

        $this->info('CreateUserRequest created');
    }

    private function publishUpdateUserRequest(): void
    {
        $templateData = get_template('user/update_user_request', 'laravel-generator');

        $templateData = $this->fillTemplate($templateData);

        $requestPath = config('infyom.laravel_generator.path.request', app_path('Http/Requests/'));

        $fileName = 'UpdateUserRequest.php';
        if (file_exists($requestPath . $fileName) && !$this->confirmOverwrite($fileName)) {
            return;
        }

        FileUtil::createFile($requestPath, $fileName, $templateData);

        $this->info('UpdateUserRequest created');
    }

    /**
     * Replaces dynamic variables of template.
     *
     * @param string $templateData
     */
    private function fillTemplate($templateData): string
    {
        $templateData = str_replace('$NAMESPACE_CONTROLLER$', config('infyom.laravel_generator.namespace.controller'), $templateData);

        $templateData = str_replace('$NAMESPACE_REQUEST$', config('infyom.laravel_generator.namespace.request'), $templateData);

        $templateData = str_replace('$NAMESPACE_REPOSITORY$', config('infyom.laravel_generator.namespace.repository'), $templateData);

        return str_replace('$NAMESPACE_USER$', config('auth.providers.users.model'), $templateData);
    }

    /**
     * Get the console command options.
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [];
    }
}
