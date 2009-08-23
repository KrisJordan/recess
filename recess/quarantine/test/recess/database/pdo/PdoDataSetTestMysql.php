<?php
Library::import('recess.database.pdo.PdoDataSource');
require_once('recess/database/pdo/PdoDataSetTest.php');
/**
 * Unit Tests for recess.database.pdo.PdoDataSet
 * @author Kris Jordan <krisjordan@gmail.com>
 * @see recess/sources/db/SelectedSet.class.php
 */
class PdoDataSetTestMysql extends PdoDataSetTest {
	
	function getConnection() {
		require('recess/database/PdoDsnSettings.php');
		$this->source = new PdoDataSource($_ENV['dsn.mysql'][0], $_ENV['dsn.mysql'][2], $_ENV['dsn.mysql'][3]);
		$this->source->beginTransaction();
		$this->source->exec('DROP TABLE IF EXISTS people');
		$this->source->exec('DROP TABLE IF EXISTS books');
		$this->source->exec('DROP TABLE IF EXISTS genera');
		$this->source->exec('DROP TABLE IF EXISTS books_genera');
		$this->source->exec('CREATE TABLE people (id INTEGER PRIMARY KEY AUTO_INCREMENT, first_name TEXT, last_name TEXT, age INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY AUTO_INCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE genera (id INTEGER PRIMARY KEY AUTO_INCREMENT, title TEXT)');
		$this->source->exec('CREATE TABLE books_genera (id INTEGER PRIMARY KEY AUTO_INCREMENT, book_id INTEGER, genera_id INTEGER)');
		return $this->createDefaultDBConnection($this->source,$_ENV['dsn.mysql'][1]);
	}
	
}

?>