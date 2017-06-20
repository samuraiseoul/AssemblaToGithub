<?php

namespace Convert\Types\Converters;

use Convert\Types\BaseType;
use Convert\Types\Ticket;
use Exception;

class TicketConverter extends BaseConverter
{
    
    public function convert($row, $map) : BaseType
    {
        if(($row[20] == "0" && $row[13] == 'null') || ($row[20] == "1" && $row[13] != 'null')){
            throw new Exception('Invalid State!');
        }
        $assignee = isset($map[$row[3]]) ? $map[$row[3]] : null;
        return new Ticket($row[0], $row[1], $row[5], $row[7], $row[10], $row[20], $row[8], $assignee);
    }
}