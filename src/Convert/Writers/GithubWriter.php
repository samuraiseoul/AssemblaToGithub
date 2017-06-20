<?php

namespace Convert\Writers;

use Convert\Readers\ReaderReturn;
use Convert\Types\Label;
use Convert\Types\Milestone;
use Convert\Types\Ticket;
use Github\Api\Issue;
use Github\Client;

class GithubWriter
{
    /** @var  Client */
    private $client;
    
    /** @var  Issue */
    private $issueApi;
    
    const SLEEPTIME = 1;
    
    /**
     * GithubWriter constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->issueApi = $this->client->api('issue');
    }
    
    /**
     * @param string $username
     * @param string $token
     * @param string $repository
     * @param ReaderReturn $return
     */
    public function write(string $username, string $token, $repository, ReaderReturn $return){
        $this->client->authenticate($token, null, Client::AUTH_URL_TOKEN);
        $this->writeMilestones($username, $repository, $return->getMilestones());
        $this->writeLabels($username, $repository, $return->getLabels());
        $this->writeTickets($username, $repository, $token, $return->getTickets());
    }
    
    private function writeLabels($username, $repository, $labels)
    {
        /** @var Label $label */
        foreach ($labels as $label) {
            //Default to grey
            $this->issueApi->labels()->create($username, $repository, ['name' => $label->getTitle(), 'color' => '606060']);
            sleep(self::SLEEPTIME);
        }
    }
    
    private function writeMilestones($username, $repository, $milestones)
    {
        /** @var Milestone $milestone */
        foreach ($milestones as $milestone) {
            $githubMilestone = $this->issueApi->milestones()->create($username, $repository, ['title' => $milestone->getTitle()]);
            $milestone->setGithubId($githubMilestone['number']);
            sleep(self::SLEEPTIME);
        }
    }
    
    private function writeTickets($username, $repository, $token, $tickets){
        $toWrite = [];
        $toJson = [];
        /** @var Ticket $ticket */
        foreach($tickets as $ticket){
            try {
                $toWrite[] = $ticket->jsonSerialize();
                $toJson[] = json_encode($ticket->jsonSerialize());
                $response = $this->client->getHttpClient()->post("https://api.github.com/repos/$username/$repository/import/issues", [
                    'Authorization' => "token $token",
                    'Accept' => 'application/vnd.github.golden-comet-preview+json'
                ], json_encode($ticket->jsonSerialize()));
                echo $response->getBody()->getContents() . PHP_EOL;
                sleep(self::SLEEPTIME);
            } catch (\Throwable $e) {
                echo $e->getTraceAsString();
            }
        }
    }
}