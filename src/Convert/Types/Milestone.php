<?php

namespace Convert\Types;

class Milestone extends BaseType
{
    private $title;
    private $githubId;
    
    /**
     * Milestone constructor.
     * @param $id
     * @param $title
     */
    public function __construct($id, $title)
    {
        $this->title = $title;
        parent::__construct($id);
    }
    
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return mixed
     */
    public function getGithubId()
    {
        return $this->githubId;
    }
    
    /**
     * @param mixed $githubId
     * @return Milestone
     */
    public function setGithubId($githubId)
    {
        $this->githubId = $githubId;
        return $this;
    }
}