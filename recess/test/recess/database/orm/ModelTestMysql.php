<?php

Library::import('recess.database.orm.ModelDataSource');
require_once('recess/database/orm/ModelTest.php');
require_once('recess/database/PdoDsnSettings.php');

class ModelTestMysql extends ModelTest {
	function getConnection() {
		require('recess/database/PdoDsnSettings.php');
		$this->source = new ModelDataSource($_ENV['dsn.mysql'][0], $_ENV['dsn.mysql'][2], $_ENV['dsn.mysql'][3]);
		$this->source->beginTransaction();
	 	$this->source->exec('DROP TABLE IF EXISTS persons');
	 	$this->source->exec('DROP TABLE IF EXISTS groups');
	 	$this->source->exec('DROP TABLE IF EXISTS groupships');
		$this->source->exec('DROP TABLE IF EXISTS books');
	 	$this->source->exec('DROP TABLE IF EXISTS chapters');
	 	$this->source->exec('DROP TABLE IF EXISTS cars');
		$this->source->exec('DROP TABLE IF EXISTS movies');
		$this->source->exec('DROP TABLE IF EXISTS generas');
		$this->source->exec('DROP TABLE IF EXISTS movies_generas_joins');
	 	$this->source->exec('DROP TABLE IF EXISTS political_partys');
		$this->source->exec('DROP TABLE IF EXISTS books_generas_joins');
		$this->source->exec('DROP TABLE IF EXISTS pages');
		$this->source->exec('CREATE TABLE persons (id INTEGER PRIMARY KEY AUTO_INCREMENT, firstName TEXT, lastName TEXT, age TEXT, politicalPartyId INTEGER, phone TEXT)');
		$this->source->exec('CREATE TABLE groups (id INTEGER PRIMARY KEY AUTO_INCREMENT, name TEXT, description TEXT)');
		$this->source->exec('CREATE TABLE groupships (id INTEGER PRIMARY KEY AUTO_INCREMENT, groupId INTEGER, personId INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY AUTO_INCREMENT, authorId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE chapters (id INTEGER PRIMARY KEY AUTO_INCREMENT, bookId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE cars (pk INTEGER PRIMARY KEY AUTO_INCREMENT, personId INTEGER, make TEXT, isDriveable BOOLEAN)');
		$this->source->exec('CREATE TABLE movies (id INTEGER PRIMARY KEY AUTO_INCREMENT, authorId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE generas (id INTEGER PRIMARY KEY AUTO_INCREMENT, title TEXT)');
		$this->source->exec('CREATE TABLE books_generas_joins (id INTEGER PRIMARY KEY AUTO_INCREMENT, bookId INTEGER, generaId INTEGER)');
		$this->source->exec('CREATE TABLE political_partys (id INTEGER PRIMARY KEY AUTO_INCREMENT, party TEXT)');
		$this->source->exec('CREATE TABLE movies_generas_joins (id INTEGER PRIMARY KEY AUTO_INCREMENT, movieId INTEGER, generaId INTEGER)');
		$this->source->exec('CREATE TABLE pages (id INTEGER PRIMARY KEY AUTO_INCREMENT, parentId INTEGER, title TEXT)');
		$this->source->commit();
		return $this->createDefaultDBConnection($this->source,$_ENV['dsn.mysql'][1]);
	}
}

?>