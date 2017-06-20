#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

use Convert\Commands\ConverterCommand;
use Convert\Readers\AssemblaReader;
use Convert\Types\Converters\LabelConverter;
use Convert\Types\Converters\MilestoneConverter;
use Convert\Types\Converters\TicketCommentConverter;
use Convert\Types\Converters\TicketConverter;
use Convert\Writers\GithubWriter;
use Github\Client;
use Symfony\Component\Console\Application;

$converters = [
    new MilestoneConverter('milestones'),
    new TicketCommentConverter('ticket_comments'),
    new TicketConverter('tickets'),
    new LabelConverter('workflow_property_vals')
];
$client = new Client();
$reader = new AssemblaReader($converters);
$writer = new GithubWriter($client);
$converterCommand = new ConverterCommand($reader, $writer);

$application = new Application();

$application->add($converterCommand);

$application->run();