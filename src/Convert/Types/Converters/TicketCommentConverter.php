<?php

namespace Convert\Types\Converters;

use Convert\Types\BaseType;
use Convert\Types\TicketComment;

class TicketCommentConverter extends BaseConverter
{
    public function convert($row, $map) : BaseType
    {
        return new TicketComment($row[0], $row[1], $row[5], $row[3]);
    }
}