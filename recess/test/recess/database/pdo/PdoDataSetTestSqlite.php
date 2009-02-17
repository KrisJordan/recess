<?php
Library::import('recess.database.pdo.PdoDataSource');

require_once('PHPUnit/Extensions/Database/TestCase.php');
require_once('recess/database/pdo/PdoDataSetTest.php');

/**
 * Unit Tests for recess.database.pdo.PdoDataSet
 * @author Kris Jordan <krisjordan@gmail.com>
 * @see recess/sources/db/SelectedSet.class.php
 */
class PdoDataSetTestSqlite extends PdoDataSetTest {
	function getConnection() {
		require('recess/database/PdoDsnSettings.php');
		$this->source = new PdoDataSource($_ENV['dsn.sqlite'][0]);
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE people (id INTEGER PRIMARY KEY AUTOINCREMENT, first_name TEXT, last_name TEXT, age INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE genera (id INTEGER PRIMARY KEY AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_genera (id INTEGER PRIMARY KEY AUTOINCREMENT, book_id INTEGER, genera_id INTEGER)');
		return $this->createDefaultDBConnection($this->source,'memory');
	}
}

?>