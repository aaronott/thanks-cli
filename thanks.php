#!/usr/bin/env php
<?php

require_once 'ThankYou.php';

define('DBDIR',  $_SERVER['HOME'] . '/Dropbox/.thanks');
$db_file = DBDIR . '/thanks.log';

if (!isset($argv[2]) && (!isset($argv[1]) || $argv[1] != "report")) {
  die("Usage: $argv[0] <name: reason>\n");
}

$db = new DBFile($db_file);

if ($argv[1] == 'report') {
  $report = new ThankYouReport($db);
  $report->print_report();
}
else {
  try {
    $ty = new ThankYou($argv[1], $argv[2]);
    $db->write($ty);
  }
  catch(Exception $e) {
    msg($e, 'error');
  }
}

function msg($msg, $type = 'success') {
  printf("%-60s [%s]\n", $msg, $type);
}

