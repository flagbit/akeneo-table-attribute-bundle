<?php

namespace Flagbit\Bundle\TableAttributeBundle\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class Select2JsonResponse extends JsonResponse
{
    /**
     * {@inheritdoc}
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {
        $data = (array) $data;

        $select2Options = [];
        foreach ($data as $key => $option) {
            $select2Options[] = ['id' => (string) $key, 'text' => (string) $option];
        }

        parent::__construct(['results' => $select2Options], $status, $headers);
    }
}
