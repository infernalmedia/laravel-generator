<?php

namespace InfyOm\Generator\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class GeneratorFileDeleting.
 */
class GeneratorFileDeleting
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
