#!/usr/bin/env php
<?php

if (!isset($argv[1])) {
  die("Usage: $argv[0] <name: reason>\n");
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
