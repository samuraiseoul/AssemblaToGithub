<?php

namespace Convert\Types\Converters;

use Convert\Types\BaseType;
use Convert\Types\Milestone;

class MilestoneConverter extends BaseConverter
{
    public function convert($row, $map) : BaseType
    {
        return new Milestone($row[0], $row[2], $row[7]);
    }
}