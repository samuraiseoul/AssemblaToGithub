<?php

namespace Convert\Types;

class Label extends BaseType
{
    /** @var  string */
    private $title;
    private $ticketId;
    
    /**
     * Label constructor.
     * @param string $title
     */
    public function __construct($id, $ticketId, $title)
    {
        $this->title = $title;
        $this->ticketId = $ticketId;
        parent::__construct($id);
    }
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * @return mixed
     */
    public function getTicketId()
    {
        return $this->ticketId;
    }
}