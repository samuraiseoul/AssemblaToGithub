<?php

namespace Convert\Types;

class Ticket extends BaseType
{
    private $number;
    private $summary;
    private $description;
    private $milestoneId;
    private $status;
    private $created;
    private $assignee;
    
    /** @var  Milestone */
    private $milestone;
    /** @var  TicketComment[] */
    private $comments = [];
    /** @var Label[] */
    private $labels = [];
    
    /**
     * Ticket constructor.
     * @param $id
     * @param $number
     * @param $summary
     * @param $description
     * @param $milestoneId
     * @param $status
     * @param $created
     * @param $assignee
     */
    public function __construct($id, $number, $summary, $description, $milestoneId, $status, $created, $assignee)
    {
        $this->number = $number;
        $this->summary = $summary;
        $this->description = str_replace('\n', PHP_EOL, $description);
        $this->milestoneId = $milestoneId;
        $this->status = $status;
        $this->created = $created;
        $this->assignee = $assignee;
        parent::__construct($id);
    }
    
    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }
    
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @return mixed
     */
    public function getMilestoneId()
    {
        return $this->milestoneId;
    }
    
    /**
     * @return TicketComment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }
    
    /**
     * @param Milestone $milestone
     * @return Ticket
     */
    public function setMilestone(Milestone $milestone): Ticket
    {
        $this->milestone = $milestone;
        return $this;
    }
    
    public function addComment(TicketComment $comment)
    {
        if (!empty($comment->getComment()) && $comment->getComment() != 'null') {
            $this->comments[] = $comment;
        }
        return $this;
    }
    
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;
        return $this;
    }
    
    /**
     * @return Milestone
     */
    public function getMilestone(): Milestone
    {
        return $this->milestone;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * @return Label[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }
    
    function jsonSerialize()
    {
        return [
            'issue' => [
                'title' => $this->summary,
                'body' => empty($this->description) ? 'null' : $this->description,
                'closed' => ($this->getStatus() == 1 ? false : true),
                "milestone" => $this->milestone ? $this->milestone->getGithubId() : "",
                'created_at' => $this->created,
                'labels' => array_map(function(Label $label){
                    return $label->getTitle();
                }, $this->labels),
                'assignee' => $this->assignee
            ],
            'comments' => array_map(function(TicketComment $comment){
                return $comment->jsonSerialize();
            }, $this->comments)
        ];
    }
}