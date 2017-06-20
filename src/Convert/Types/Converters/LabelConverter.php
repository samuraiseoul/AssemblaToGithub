<?php

namespace Convert\Types\Converters;

use Convert\Types\BaseType;
use Convert\Types\Label;

class LabelConverter extends BaseConverter
{
    
    public function convert($row, $map): BaseType
    {
        return new Label($row[0], $row[1], $row[4]);
    }
}