<?php

namespace Modules\Core\Exceptions;

use Exception;

class InspectionNotEditableException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            'Inspection is no longer editable. Only open inspections can be modified.'
        );
    }
}