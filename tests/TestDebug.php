<?php
class TestDebug extends PHPUnit_Framework_TestCase {

  private static $pdo = null;
  private static $log_file;

  public function setUp(){
    self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USERNAME'], $GLOBALS['DB_PASSWORD'], array(
      \PDO::ATTR_PERSISTENT => true,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ));

    self::$log_file = __DIR__ . "/logSys.log";
  }

  public function testLogFileExists(){
    $this->assertEquals(file_exists(self::$log_file), false);

    $config = array(
      "db" => array(
        "host" => null,
        "port" => 0
      ),
      "features" => array(
        "auto_init" => false,
        "start_session" => false
      ),
      "debug" => array(
        "enable" => true,
        "log_file" => self::$log_file
      ),
    );
    \Fr\LS::config($config);

    $this->assertEquals(true, file_exists(self::$log_file));
    $this->assertContains("Couldn't connect to database", file_get_contents(self::$log_file));
  }

  public static function tearDownAfterClass(){
    unlink(self::$log_file);
  }

}
