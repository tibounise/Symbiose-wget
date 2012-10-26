<?php

// Wget for Symbiose WebOS
// Coded by TiBounise (http://tibounise.com)
// Released as GPL v3 software

if (!$this->arguments->isParam(0))
  throw new InvalidArgumentException('Aucune URL spécifiée');

$ParsedUrl = parse_url($this->arguments->getParam(0));
$Url = $this->arguments->getParam(0);
$FileManager = $this->webos->managers()->get('File');

if (!isset($ParsedUrl['host'])) {
  throw new InvalidArgumentException('URL invalide');
}

// Check if the file exists
$fp = @fopen($Url,'r');
if (!$fp) {
  throw new InvalidArgumentException('URL inaccessible');
}
fclose($fp);

// Dirty part to generate a new name for the file
if (isset($ParsedUrl['path'])) {
  $baseFilename = end(explode('/',$ParsedUrl['path']));
} else {
  $baseFilename = $ParsedUrl['host'];
}
    
$filename = $baseFilename;
$i = 1;
    
while ($FileManager->exists($this->terminal->getAbsoluteLocation($filename))) {
  $filename = $baseFilename . ' (' . $i . ')';
  $i++;
}

$FileManager->createFile($this->terminal->getAbsoluteLocation($filename))->setContents(file_get_contents($Url));