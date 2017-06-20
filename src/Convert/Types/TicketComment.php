<?php

namespace Convert\Types;

class TicketComment extends BaseType
{
    private $comment;
    private $ticketId;
    private $created;
    
    /**
     * TicketComment constructor.
     * @param $id
     * @param $comment
     */
    public function __construct($id, $ticketId, $comment, $created)
    {
        $this->comment = str_replace('\n', PHP_EOL, $comment);
        $this->ticketId = $ticketId;
        $this->created = $created;
        parent::__construct($id);
    }
    
    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }
    
    function jsonSerialize()
    {
        return [
            'created_at' => $this->created,
            'body' => empty($this->comment) ? '' : $this->comment,
        ];
    }
}