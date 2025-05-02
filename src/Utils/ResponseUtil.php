<?php

namespace InfyOm\Generator\Utils;

class ResponseUtil
{
    /**
     * @param string $message
     * @param mixed  $data
     */
    public static function makeResponse($message, $data): array
    {
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     *
     */
    public static function makeError($message, array $data = []): array
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }
}
