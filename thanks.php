#!/usr/bin/env php
<?php

define('DBDIR',  $_SERVER['HOME'] . '/Dropbox/.thanks');

$db_file = DBDIR . '/thanks.log';
$week = array(
  "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
);

$week_starts = "Thursday";



if (!isset($argv[2]) && (!isset($argv[1]) || $argv[1] != "report")) {
  die("Usage: $argv[0] <name: reason>\n");
}

if ($argv[1] == 'report') {

}
else {
  if (!writetodb($argv[1], $argv[2])) {
    msg("There was a problem writing to the dbfile: $db_file.", 'ERROR');
  }
}

function printReport() {

}

function writetodb($name, $reason) {
  global $db_file;

  $line = time();
  $line .= "|$name|$reason";

  return file_put_contents($db_file, $line, FILE_APPEND);
}


function msg($msg, $type = 'success') {
  printf("%-60s [%s]\n", $msg, $type);
}
