<?php

namespace Webhook\Decorators;

use  Webhook\Decorators\Base;

/**
 * The class checks the parameter array for merge-request,
 * complements the parameters or leaves them unchanged.
 */
class MergeRequest extends Base
{
    private $statusMrOpen = 'Merge request';
    private $statusMrTest = 'Test';

    public function formatParams(array $params): array
    {
        $params = parent::formatParams($params);
        $isMergeRequest = $params['json']['event_type'] === 'merge_request';
        $isFirstMr = $params['json']['object_attributes']['action'] === 'open';
        $action = $params['json']['object_attributes']['action'];

        if ($isMergeRequest) {
            $idFromTitle = preg_replace(
                parent::GET_NUMBERS_PATTERN,
                '',
                $params['json']['object_attributes']['title']
            );

            $idFromBranch = preg_replace(
                parent::GET_NUMBERS_PATTERN,
                '',
                $params['json']['object_attributes']['source_branch']
            );
            $params["id"] = $idFromTitle ?? $idFromBranch;

            if ($isFirstMr) {
                $params["url"] = $params['json']['object_attributes']['url'];
            }

            switch ($action) {
                case 'open':
                    $params["status"] = $this->statusMrOpen;
                    break;
                case 'merge':
                    $params["status"] = $this->statusMrTest;
                    break;
            }
        }

        return $params;
    }
}
