<?php

namespace Webhook\Response;

/**
 * We declare a filtering method for all specific decorators
 */
interface InputFormat
{
    public function formatParams(array $params): array;
}
