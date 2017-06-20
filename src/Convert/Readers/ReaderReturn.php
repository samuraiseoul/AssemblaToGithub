<?php

namespace Convert\Readers;

use Convert\Types\Label;
use Convert\Types\Milestone;
use Convert\Types\Ticket;

class ReaderReturn
{
    /** @var  Ticket[] */
    private
    $tickets;
    /** @var  Milestone[] */
    private
    $milestones;
    /** @var  Label[] */
    private
    $labels;
    
    /**
     * __anonymous$object$@3806 constructor.
     * @param Ticket[] $tickets
     * @param Milestone[] $milestones
     * @param Label[] $labels
     */
    public
    function __construct(array $tickets, array $labels, array $milestones)
    {
        $this->tickets = $tickets;
        $this->milestones = $milestones;
        $this->labels = $labels;
    }
    
    /**
     * @return Ticket[]
     */
    public
    function getTickets(): array
    {
        return $this->tickets;
    }
    
    /**
     * @return Milestone[]
     */
    public
    function getMilestones(): array
    {
        return $this->milestones;
    }
    
    /**
     * @return Label[]
     */
    public
    function getLabels(): array
    {
        return $this->labels;
    }
}