<?php

namespace Webhook;

use Webhook\Response\InputFormat;
/**
 * The main decoration element, contains the initial parameters
 */
class InitialParams implements InputFormat
{
    /**
     * @param array ["respose" => jsonResponse].
     * @return array $params
     */
    public function formatParams(array $params): array
    {
        return $params;
    }
}
