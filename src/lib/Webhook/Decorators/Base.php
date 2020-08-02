<?php

namespace Webhook\Decorators;

use  Webhook\Response\InputFormat;

/**
 * The base decorator class, implements the base parameter structure,
 * array - json-response
 */
class Base implements InputFormat
{
    /**
     * Reg-exp pattern for getting numbers (issue numbers), from title or branch-name
     */
    const GET_NUMBERS_PATTERN = '/[^0-9]/';

    /** @var InputFormat */
    protected $inputFormat;

    public function __construct(InputFormat $inputFormat)
    {
        $this->inputFormat = $inputFormat;
    }

    public function formatParams(array $params): array
    {
        if (!empty($params["response"])) {
            $params["json"] = json_decode($params["response"], true);
            unset($params["response"]);
        }

        return $this->inputFormat->formatParams($params);
    }
}
