<?php

namespace InfyOm\Generator;

use Illuminate\Support\ServiceProvider;
use InfyOm\Generator\Commands\API\APIControllerGeneratorCommand;
use InfyOm\Generator\Commands\API\APIGeneratorCommand;
use InfyOm\Generator\Commands\API\APIRequestsGeneratorCommand;
use InfyOm\Generator\Commands\API\TestsGeneratorCommand;
use InfyOm\Generator\Commands\APIScaffoldGeneratorCommand;
use InfyOm\Generator\Commands\Common\MigrationGeneratorCommand;
use InfyOm\Generator\Commands\Common\ModelGeneratorCommand;
use InfyOm\Generator\Commands\Common\RepositoryGeneratorCommand;
use InfyOm\Generator\Commands\Publish\GeneratorPublishCommand;
use InfyOm\Generator\Commands\Publish\LayoutPublishCommand;
use InfyOm\Generator\Commands\Publish\PublishTemplateCommand;
use InfyOm\Generator\Commands\Publish\PublishUserCommand;
use InfyOm\Generator\Commands\RollbackGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use InfyOm\Generator\Commands\Scaffold\ViewsGeneratorCommand;

class InfyOmGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $configPath = __DIR__ . '/../config/laravel_generator.php';

        $this->publishes([
            $configPath => config_path('infyom/laravel_generator.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->singleton('infyom.publish', fn($app) => new GeneratorPublishCommand());

        $this->app->singleton('infyom.api', fn($app) => new APIGeneratorCommand());

        $this->app->singleton('infyom.scaffold', fn($app) => new ScaffoldGeneratorCommand());

        $this->app->singleton('infyom.publish.layout', fn($app) => new LayoutPublishCommand());

        $this->app->singleton('infyom.publish.templates', fn($app) => new PublishTemplateCommand());

        $this->app->singleton('infyom.api_scaffold', fn($app) => new APIScaffoldGeneratorCommand());

        $this->app->singleton('infyom.migration', fn($app) => new MigrationGeneratorCommand());

        $this->app->singleton('infyom.model', fn($app) => new ModelGeneratorCommand());

        $this->app->singleton('infyom.repository', fn($app) => new RepositoryGeneratorCommand());

        $this->app->singleton('infyom.api.controller', fn($app) => new APIControllerGeneratorCommand());

        $this->app->singleton('infyom.api.requests', fn($app) => new APIRequestsGeneratorCommand());

        $this->app->singleton('infyom.api.tests', fn($app) => new TestsGeneratorCommand());

        $this->app->singleton('infyom.scaffold.controller', fn($app) => new ControllerGeneratorCommand());

        $this->app->singleton('infyom.scaffold.requests', fn($app) => new RequestsGeneratorCommand());

        $this->app->singleton('infyom.scaffold.views', fn($app) => new ViewsGeneratorCommand());

        $this->app->singleton('infyom.rollback', fn($app) => new RollbackGeneratorCommand());

        $this->app->singleton('infyom.publish.user', fn($app) => new PublishUserCommand());

        $this->commands([
            'infyom.publish',
            'infyom.api',
            'infyom.scaffold',
            'infyom.api_scaffold',
            'infyom.publish.layout',
            'infyom.publish.templates',
            'infyom.migration',
            'infyom.model',
            'infyom.repository',
            'infyom.api.controller',
            'infyom.api.requests',
            'infyom.api.tests',
            'infyom.scaffold.controller',
            'infyom.scaffold.requests',
            'infyom.scaffold.views',
            'infyom.rollback',
            'infyom.publish.user',
        ]);
    }
}
