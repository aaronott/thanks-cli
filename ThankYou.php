<?php
/**
 * @file
 *
 * This file contains the classes needed to run the ThankYou script. Currently
 * all classes are in the same file. I might think about splitting them up in
 * the future but probably not.
 */

class ThankYou {
  private $person;
  private $action;
  private $timestamp;

  /**
   * __construct
   *
   * Build the thankyou and set the vars.
   *
   * @param string
   *   Name of the person you would like to thank
   *
   * @param string
   *   This is the action the person did that was worthy of thanks.
   *
   * @param int
   *   The timestamp to be used.
   */
  public function __construct($person, $action, $timestamp = NULL) {
    $this->person = $person;
    $this->action = $action;
    $this->timestamp = $timestamp != NULL ? $timestamp : time();
  }

  /**
   * __toString() Magic function.
   *
   * This allows the object to be printed as a string.
   */
  public function __toString() {
    return $this->timestamp . "|" . $this->person . "|" . $this->action;
  }
}

/**
 * Implements the read/write functionality to the DBFile.
 *
 * The DBFile is a simple flat file used to store the information.
 */
class DBFile {

  /**
   * @param string $filepath
   *
   * The path and file to store the data in.
   */
  private $filepath;

  /**
   * __construct
   *
   * @param string $filepath
   *   This is the path to the DBFile.
   */
  public function __construct($filepath) {

    if (empty($filepath)) {
      throw new Exception('DBFile cannot be empty');
    }
    $this->filepath = $filepath;
  }

  /**
   * write
   *
   * Write the data to the DBFile
   *
   * @param string $line
   *   String of data to be written to the DBFile
   */
  public function write($line) {
    $line .= "\n";
    return file_put_contents($this->filepath, $line, FILE_APPEND);
  }

  /**
   * getReadItr
   *
   * This method currently returns an open read-only filehandle to the DBFile.
   *
   * @TODO: This needs to be changed to simply iterate over the file instead
   * of handing that off.
   */
  public function getReadItr() {
    if (!$this->_exists()) {
      throw new Exception('DBFile does not exist:' . $this->filepath);
    }

    $fh = fopen($this->filepath, 'r');
    if (!$fh) {
      throw new Exception('Unable to open DBFile for read:' . $this->filepath);
    }

    return $fh;
  }

  /**
   * _exists
   *
   * Check if this filepath exists or not.
   *
   * @return boolean
   */
  private function _exists() {
    return file_exists($this->filepath);
  }
}

/**
 * A formatted report for the thankyou list
 */
class ThankYouReport {
  private $dbobj;

  private $date_format;

  public function __construct(DBFile $dbobj, $date_format = "F j, Y") {
    $this->dbobj = $dbobj;
    $this->date_format = $date_format;
  }

  public function print_report() {
    $fh = $this->dbobj->getReadItr();

    $date_header = "";
    $hr = "--------------------------------------------------------------\n";

    while(!feof($fh)) {
      $line = fgets($fh, 1024);

      // don't need empty lines screwing us up
      if ($line == '') { continue; }
      list($ts, $name, $action) = explode('|', $line);

      if (date($this->date_format, $ts) != $date_header) {
        $date_header = date($this->date_format, $ts);

        echo $hr;
        echo " $date_header\n";
        echo $hr;
      }

      echo "$name\n";
      echo " - $action\n";
    }

    fclose($fh);
  }
}
