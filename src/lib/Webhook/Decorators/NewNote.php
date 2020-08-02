<?php

namespace Webhook\Decorators;

use  Webhook\Decorators\Base;

/**
 * The class checks the parameter array for note (new comment),
 * complements the parameters or leaves them unchanged.
 */
class NewNote extends Base
{
    private $statusMrRevision = 'For revision';

    public function formatParams(array $params): array
    {
        $params = parent::formatParams($params);
        $isNote = $params['json']['event_type'] === 'note';;

        if ($isNote) {
            $idFromTitle = preg_replace(
                parent::GET_NUMBERS_PATTERN,
                '',
                $params['json']['merge_request']['title']
            );

            $idFromBranch = preg_replace(
                parent::GET_NUMBERS_PATTERN,
                '',
                $params['json']['merge_request']['source_branch']
            );
            $params["id"] = $idFromTitle ?? $idFromBranch;

            $params["status"] = $this->statusMrRevision;
        }

        return $params;
    }
}
