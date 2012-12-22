<?php
require_once 'ThankYou.php';
class ThankYouTest extends PHPUnit_Framework_TestCase
{

  protected $thankyou;

  public function setUp() {
    $this->thankyou = new ThankYou('TestPerson', 'TestAction');
  }


  /**
   * Tests ThankYou
   *
   * Simply say thank you
   *
   * @covers ThankYou::__construct
   *
   * @test
   */
  public function testSimpleThankYou()
  {
    $this->assertTrue(is_numeric(substr($this->thankyou, 0, 10)), "First field must be a numeric timestamp.");
    $this->assertTrue(substr($this->thankyou, 11, 10) == 'TestPerson');
    $this->assertTrue(substr($this->thankyou, 22) == 'TestAction');
  }
}
?>
