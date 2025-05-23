<?php

namespace InfyOm\Generator\Events;

use Illuminate\Queue\SerializesModels;

/**
 * Class GeneratorFileCreated.
 */
class GeneratorFileCreated
{
    use SerializesModels;

    public $type;

    public $data;

    /**
     * Create a new event instance.
     *
     * @param string $type
     * @param array  $data
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }
}
