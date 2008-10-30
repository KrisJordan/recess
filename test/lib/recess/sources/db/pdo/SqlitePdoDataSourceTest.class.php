<?php

Library::import('recess.sources.db.pdo.PdoDataSource');

/**
 * Unit Tests for recess/sources/db/pdo/PdoDataSource using SQLite provider
 * @author Kris Jordan
 * @see recess/sources/db/pdo/PdoDataSource.class.php
 */
class SqlitePdoDataSourceTest extends UnitTestCase  {
	
	const DSN = 'sqlite:test.db';
	const FILE = 'test.db';
	const FAIL_DSN = 'slqite:test.db';
	
	protected $source = null;
	
	function setUp() {
		@unlink(self::FILE);
		
		$pdo = new PDO(self::DSN);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();
		$pdo->exec('CREATE TABLE table_b (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, column_b STRING)');
		$pdo->exec('CREATE TABLE table_a (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, column_a STRING)');
		$pdo->commit();
		unset($pdo);
	}

	function testConstructor() {
		$this->source = new PdoDataSource(self::DSN);
		$this->assertTrue(file_exists(self::FILE), 'DB file should exist.');
	}
	
	function testConstructorException() {
		try {
			$this->source = new PdoDataSource(self::FAIL_DSN);
		} catch(DataSourceCouldNotConnectException $e) {
			$this->assertTrue(true);
			return;
		}
		$this->fail('Constructor should have thrown failed DSN.');
	}
	
	function testGetTables() {
		$this->source = new PdoDataSource(self::DSN);
		$tables = $this->source->getTables();
		
		$expected = array('table_a', 'table_b');
		
		$this->assertEqual($tables, $expected);
	}
	
	function testGetColumns() {
		$this->source = new PdoDataSource(self::DSN);
		$table_a_columns = $this->source->getColumns('table_a');
		$table_b_columns = $this->source->getColumns('table_b');
		$table_a_columns_expected = array('column_a', 'id');
		$table_b_columns_expected = array('column_b', 'id');
		
		$this->assertEqual($table_a_columns, $table_a_columns_expected);
		$this->assertEqual($table_b_columns, $table_b_columns_expected);
	}
	
	function tearDown() {
		$pdo = new PDO(self::DSN);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->beginTransaction();
		$pdo->exec('DROP TABLE table_b');
		$pdo->exec('DROP TABLE table_a');
		$pdo->commit();
		
		unset($pdo);
		unset($this->source);
		@unlink(self::FILE);
	}	
}
?>