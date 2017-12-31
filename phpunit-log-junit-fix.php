<?php
/**
 * @author Alefe Souza <contact@alefesouza.com>
 * 
 * Remove nested <testsuite> tags from PHPUnit --log-junit generated file.
 * I've created it after I wanted to run tests on Visual Studio Team Services CI,
 * but an error occurred because of these nested tags.
 * 
 * Use it:
 * 
 * php phpunit-junit-log-fix.php <phpunit junit log>.xml <new file>.xml
 */

$argvCount = count($argv);

if ($argvCount === 1) {
    exit('Please type the name of the generated PHPUnit --log-junit file.');
}

$importFile = $argv[1];
$exportFile = isset($argv[2]) ? $argv[2] : $importFile;

try {
    if(!file_exists($importFile)) {
        exit('File not found.');
    }

    $content = file_get_contents($importFile);

    $xml = new SimpleXMLElement($content, LIBXML_NOERROR);

    if($xml->getName() !== 'testsuites') {
        exit('It does not look to be a PHPUnit --log-junit file.');
    }
} catch(Exception $e) {
    exit('It does not look to be a valid XML file.');
}

$xpath = $xml->xpath('//testcase/parent::testsuite');

$newValue = '';

foreach($xpath as $node){
    $newValue .= $node->asXML();
}

$newXML = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><testsuites>'.$newValue.'</testsuites>');

file_put_contents($exportFile, $newXML->asXML());
