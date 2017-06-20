<?php
namespace Convert\Readers;

use Convert\Types\Converters\BaseConverter;
use Convert\Types\Label;
use Convert\Types\Ticket;
use Convert\Types\TicketComment;
use Exception;

class AssemblaReader
{
    /** @var  BaseConverter[] */
    private $converters;
    
    /**
     * AssemblaReader constructor.
     * @param BaseConverter[] $converters
     */
    public function __construct(array $converters)
    {
        $this->converters = $converters;
    }
    
    
    public function read($filename, $map){
        echo "Reading in filename: $filename" . PHP_EOL;
        $strings = [];
        $file = fopen($filename, 'r');
        while(!feof($file)){
            $line = fgets($file);
            $strings[] = $line;
        }
        fclose($file);
        $types = $this->convertToArrays($strings);
        $popos = $this->convertToPopos($types, $map);
        return $this->combine($popos);
    }
    
    private function convertToArrays($strings){
        $types = [];
        foreach ($strings as $string) {
            //pad to prevent parse of non header rows throwing index errors
            list($type, $fields) = array_pad(explode(':', $string), 2, null);
            if (strpos($fields, 'fields, ') !== false) {
                //substr -2 gets rid of new line and ending ']'
                $types[$type] = [
                    'fields' => explode(',', substr(str_replace('fields, [', '', $fields), 0, -2)),
                    'rows' => []
                ];
            } else {
                //must limit to 2 or else anything matching the delimiter causes a bad parsing
                list($type, $fields) = array_pad(explode(', [', $string, 2), 2, null);
                if(empty($type)){ continue; }
                $row = str_getcsv(substr($fields, 0, -2));
                if(count($types[$type]['fields']) != count($row)){
                    throw new Exception("Fields must be equal!");
                }
                $types[$type]['rows'][] = $row;
            }
        }
        return $types;
    }
    
    private function convertToPopos($types, $map){
        $popos = [];
        foreach ($types as $type => $value){
            $typeConverter = null;
            foreach($this->converters as $converter) {
                if($converter->canConvert($type)){
                    $typeConverter = $converter;
                    break;
                }
            }
            if(!$typeConverter){continue;}
            if(!isset($popos[$type])){
                $popos[$type] = [];
            }
            foreach($value['rows'] as $row){
                $popo = $typeConverter->convert($row, $map);
                $popos[$type][$popo->getId()] = $popo;
            }
        }
        return $popos;
    }
    
    private function combine($popos) {
        $tickets = [];
        $labels = [];
        /** @var Label $label */
        foreach($popos['workflow_property_vals'] as $label){
            if(!isset($labels[$label->getTitle()])){
                $labels[$label->getTitle()] = $label;
            }
            /** @var Ticket $ticket */
            $ticket = $popos['tickets'][$label->getTicketId()];
            $ticket->addLabel($label);
        }
        /** @var TicketComment $comment */
        foreach ($popos['ticket_comments'] as $comment) {
            /** @var Ticket $ticket */
            $ticket = $popos['tickets'][$comment->getTicketId()];
            $ticket->addComment($comment);
        }
        /** @var Ticket $ticket */
        foreach($popos['tickets'] as $ticket){
            if(!empty($ticket->getMilestoneId()) && $ticket->getMilestoneId() != 'null'){
                $ticket->setMilestone($popos['milestones'][$ticket->getMilestoneId()]);
            }
            $tickets[] = $ticket;
        }
        return new ReaderReturn($tickets, $labels, $popos['milestones']);
    }
}