<?php

namespace InfyOm\Generator\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class GeneratorFileDeleted.
 */
class GeneratorFileDeleted
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string $type
     * @param array  $data
     */
    public function __construct(public $type, public $data)
    {
    }
}
