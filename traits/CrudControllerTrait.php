<?php

namespace App\Traits;

use Flash;
use Illuminate\View\View;

trait CrudControllerTrait
{
    /**
     * The blade view base path
     *
     * @return string
     */
    abstract protected function getBaseViewPath();
    /**
     * The Route base path
     *
     * @return string
     */
    abstract protected function getBaseRoutePath();
    /**
     * The localization file path
     *
     * @return string
     */
    abstract protected function getModelLangPath();

    /**
     * Return view from current section
     *
     * @param string $view
     * @return View
     */
    protected function returnView(string $view): View
    {
        return view("{$this->getBaseViewPath()}.{$view}");
    }

    /**
     * Return route from current section
     *
     * @param string $route
     * @return string
     */
    protected function getRoute(string $route): string
    {
        return route("{$this->getBaseRoutePath()}.{$route}");
    }

    /**
     * Redirect if user model is not found
     *
     * @return void
     */
    protected function notFoundRedirect()
    {
        $this->getModelFlashMessage('error', 'not_found');

        return redirect($this->getRoute('index'));
    }

    /**
     * Show the status flash message from messages file
     *
     * @param string $status
     * @param string $message
     * @return void
     */
    protected function getModelFlashMessage(string $status, string $message)
    {
        return Flash::$status(
            __(
                "messages.{$message}",
                ['model' => __("{$this->getModelLangPath()}.singular")]
            )
        );
    }
}
