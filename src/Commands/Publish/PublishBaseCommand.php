<?php

namespace InfyOm\Generator\Commands\Publish;

use File;
use InfyOm\Generator\Commands\BaseCommand;

class PublishBaseCommand extends BaseCommand
{
    public function handle(): void
    {
        //
    }

    public function publishFile($sourceFile, $destinationFile, $fileName): void
    {
        if (file_exists($destinationFile) && !$this->confirmOverwrite($destinationFile)) {
            return;
        }

        copy($sourceFile, $destinationFile);

        $this->comment($fileName . ' published');
        $this->info($destinationFile);
    }

    /**
     * @param $sourceDir
     * @param $destinationDir
     * @param $dirName
     * @param bool $force
     *
     * @return bool|void
     */
    public function publishDirectory($sourceDir, $destinationDir, $dirName, $force = false)
    {
        if (file_exists($destinationDir) && !$force && !$this->confirmOverwrite($destinationDir)) {
            return;
        }

        File::makeDirectory($destinationDir, 493, true, true);
        File::copyDirectory($sourceDir, $destinationDir);

        $this->comment($dirName . ' published');
        $this->info($destinationDir);

        return true;
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
