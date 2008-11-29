<?php

Library::import('recess.lang.RecessObjectRegistry');

Library::import('recess.sources.db.DbSources');
Library::import('recess.sources.db.orm.Model');
Library::import('recess.sources.db.orm.ModelDataSource');

/**
 * !BelongsTo owner, Class: Person, ForeignKey: person_id, OnDelete: Cascade
 */
class Car extends Model {
	
	/** !PrimaryKey integer, AutoIncrement: true */
	public $pk;
	
}

/**
 * !HasMany persons
 */
class PoliticalParty extends Model {}

/**
 * !HasMany persons, Through: Groupship, OnDelete: Delete
 */
class Group extends Model {}

/**
 * !BelongsTo group
 * !BelongsTo person
 */
class Groupship extends Model {}

/**
 * !HasMany books, ForeignKey: author_id, OnDelete: Cascade
 * !HasMany novels, ForeignKey: author_id, Class: Book
 * !HasMany cars, OnDelete: Nullify
 * !HasMany groups, Through: Groupship, OnDelete: Nullify
 * !BelongsTo politicalParty
 */
class Person extends Model {}

/**
 * !BelongsTo author, Class: Person, OnDelete: Delete
 * !HasMany chapters, OnDelete: Delete
 * !HasMany generas, Through: BooksGenerasJoin, OnDelete: Delete
 */
class Book extends Model { }

/**
 * !BelongsTo book, OnDelete: Cascade
 * !BelongsTo genera
 */
class BooksGenerasJoin extends Model { }

/**
 * !BelongsTo book, OnDelete: Nullify
 */
class Chapter extends Model { }

/**
 * !HasMany books, Through: BooksGenerasJoin, OnDelete: Cascade
 * !HasMany movies, Through: MoviesGenerasJoin
 */
class Genera extends Model { }

/**
 * !BelongsTo movie
 * !BelongsTo genera
 */
class MoviesGenerasJoin extends Model { }

/**
 * !HasMany generas, Through: MoviesGenerasJoin
 */
class Movie extends Model { }

class ModelTest extends UnitTestCase {
	protected $source;
	
	function __construct() {
		parent::UnitTestCase('Model Test');
	}
	
	function setUp() {
		$this->source = new ModelDataSource('sqlite::memory:');
		$this->source->beginTransaction();
		$this->source->exec('CREATE TABLE persons (id INTEGER PRIMARY KEY AUTOINCREMENT, first_name TEXT, last_name TEXT, age TEXT, political_party_id INTEGER)');
		$this->source->exec('CREATE TABLE groups (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT)');
		$this->source->exec('CREATE TABLE groupships (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, group_id INTEGER, person_id INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE chapters (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, book_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE cars (pk INTEGER PRIMARY KEY ASC AUTOINCREMENT, person_id INTEGER, make TEXT)');
		$this->source->exec('CREATE TABLE movies (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, author_id INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE generas (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_generas_joins (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, book_id INTEGER, genera_id INTEGER)');
		$this->source->exec('CREATE TABLE political_partys (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, party TEXT)');
		$this->source->exec('CREATE TABLE movies_generas_joins (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, movie_id INTEGER, genera_id INTEGER)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("Kris", "Jordan", 23, 1)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("Joel", "Sutherland", 23, 1)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("Clay", "Schossow", 22, 2)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("Barack", "Obama", 47, 1)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("Josh", "Lockhart", 22, 1)');
		$this->source->exec('INSERT INTO persons (first_name, last_name, age, political_party_id) VALUES ("John", "McCain", 72, 3)');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Democrat")');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Independent")');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Republican")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"The Audacity of Hope: Thoughts on Reclaiming the American Dream")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Republicans and Democrats")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Values")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Our Constitution")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Politics")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Opportunity")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Faith")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Race")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"The World Beyond our Borders")');
		$this->source->exec('INSERT INTO chapters (book_id, title) VALUES (1,"Family")');
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"How to Be a Sketch Ball")'); // 1
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (2,"Steve Nash: A Modern Day Hero")'); // 2
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")'); // 3
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Dreams from My Father: A Story of Race and Inheritance")'); // 4
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (4,"Barack Obama: What He Believes In - From His Own Works")'); // 5
		$this->source->exec('INSERT INTO books (author_id, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story")'); // 6
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story, The Movie")');
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"Clay Schossow: Unleashed")');
		$this->source->exec('INSERT INTO movies (author_id, title) VALUES (3,"LeBron James and Other Assorted Dreams of Clay Schossow")');
		$this->source->exec('INSERT INTO generas (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO generas (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO generas (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO generas (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (1,3)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (2,3)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (3,1)'); // 4: 4, 6: 3
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (4,4)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (5,3)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (6,3)');
		$this->source->exec('INSERT INTO books_generas_joins (book_id,genera_id) VALUES (7,1)');
		$this->source->exec('INSERT INTO movies_generas_joins (movie_id,genera_id) VALUES (1,1)');
		$this->source->exec('INSERT INTO movies_generas_joins (movie_id,genera_id) VALUES (1,4)');
		$this->source->exec('INSERT INTO movies_generas_joins (movie_id,genera_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO movies_generas_joins (movie_id,genera_id) VALUES (3,1)');
		$this->source->exec('INSERT INTO groups (name) VALUES ("NRA")'); // 1
		$this->source->exec('INSERT INTO groups (name) VALUES ("Tree Huggers")'); // 2
		$this->source->exec('INSERT INTO groups (name) VALUES ("Hackers")'); // 3
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (2,1)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (3,1)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (3,2)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (2,2)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (1,3)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (2,4)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (2,5)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (3,5)');
		$this->source->exec('INSERT INTO groupships (group_id,person_id) VALUES (1,6)');
		$this->source->exec('INSERT INTO cars (person_id,make) VALUES (1,"VW")');
		$this->source->exec('INSERT INTO cars (person_id,make) VALUES (2,"Toyota")');
		$this->source->commit();
		$this->source->beginTransaction();
		DbSources::setDefaultSource($this->source);
		RecessObject::clearDescriptors();
	}

	function testAll() {
		$person = new Person();
		$people = $person->all();
		$this->assertEqual(count($people),6);	

		$genera = Make::a('Genera')->equal('title','Social Healing')->first();
		$this->assertEqual($genera->title, 'Social Healing');
		$this->assertEqual($genera->id, 3);
	}

	function testFindCriteria() {
		$person = new Person();
		$person->age = 23;
		$people = $person->find()->orderBy('last_name DESC');
		$this->assertEqual(count($people),2);
		$this->assertEqual($people[0]->last_name, 'Sutherland');
		$this->assertEqual(get_class($people[0]), 'Person');
	}
	
	function testFindTailCriteria() {
		$person = new Person();
		$people = $person->find()->greaterThan('age',22);
		$this->assertEqual(count($people),4);
	}
	
	function testHasManyRelationship() {
		$person = new Person();
		$person->id = 4;
		$books = $person->books()->orderBy('title');
		$this->assertEqual(count($books), 3);
	}
	
	function testHasManyRelationshipCriteria() {
		$person = new Person();
		$person->first_name = 'Barack';
		$person->last_name = 'Obama';
		$books = $person->books()->like('title','%Dream%')->orderBy('title ASC');
		$this->assertEqual(count($books), 2);	
		$this->assertEqual($books[0]->title, 'Dreams from My Father: A Story of Race and Inheritance');
	}
	
	function testAliasHasManyRelationship() {
		$person = new Person();
		$person->id = 4;
		$novels = $person->novels();
		$this->assertEqual(count($novels),3);
	}
	
	function testChainMultipleRelationships() {
		$person = new Person();
		$person->id = 3;
		$generas = $person->books()->generas();
		$this->assertEqual(count($generas), 3);
		
		$person = new Person();
		$generas = $person->equal('age',22)->books()->generas();
		$this->assertEqual(count($generas), 3);
		
		$person = new Person();
		$generas = $person->equal('age',22)->books()->like('title','%Dream%')->generas();
		$this->assertEqual(count($generas), 1);
	}
	
	function testMultipleJoinTables() {		
		$movies = Make::a('Person')
						->equal('age',22)
						->books()
						->like('title','%Dream%') 
						->generas()
						->movies();
		
		$this->assertEqual(count($movies),2);
	}
	
	function testBelongsTo() {
		$book = new Book();
		$book->id = 1;
		$barack = $book->author();
		$this->assertEqual(get_class($barack), 'Person');
		$this->assertEqual($barack->first_name, 'Barack');
		$baracksBooks = $barack->books();
		$this->assertEqual(count($baracksBooks), 3);
	}
	
	function testHasAndBelongsToMany() {
		$book = new Book();
		$book->id = 2;
		$generas = $book->generas();
		$this->assertEqual(count($generas), 2);
		
		$books = $generas[0]->books();
		$this->assertEqual(count($books),4);
	}
	
	function testHasAndBelongsToManyWithCriteria() {
		$book = new Book();
		$generas = 
			$book
				->generas()
				->like('books.title', '%Dream%');
		$this->assertEqual(count($generas),2);
	}
	
	function testUpdate() {
		$people = Make::a('Person')->all();
		$person = $people[0];
		$name = $person->first_name;
		
		$person->first_name = 'UPDATE!';
		$person->update();
		
		$people = Make::a('Person')->all();
		
		$this->assertEqual($person->first_name, $people[0]->first_name);
	}
	
	function testUpdateOnCollection() {
		$person = new Person();
		$person->age = 23;
		$person->lessThan('age',23)->update();
		
		$people = $person->find();
		$this->assertEqual(count($people), 4);
	
		$person = new Person();
		$person->age = 100;
		$person->all()->update();
		
		$people = $person->find();
		$this->assertEqual(count($people),6);
	}
	
	function testInsert() {
		$people = Make::a('Person')->all();
		$people_count = count($people);
		
		$person = new Person();
		$person->first_name = 'Joe';
		$person->last_name = 'Biden';
		$person->age = 65;
		$person->insert();
		
		$people = Make::a('Person')->all();
		$this->assertEqual($people_count + 1, count($people));
	}
	
	function testDelete() {
		$people = Make::a('Person')->all();
		$people_count = count($people);
		$this->assertTrue($people[0]->delete());
		
		$people = Make::a('Person')->all();
		$this->assertEqual($people_count - 1, count($people));
	}
	
	function testDeleteOnCollection() {
		Make::a('Person')->greaterThanOrEqualTo('age',23)->delete();
		$people = Make::a('Person')->all();
		$this->assertEqual(count($people), 2);
		
		Make::a('Person')->all()->delete();
		$people = Make::a('Person')->all();
		$this->assertEqual(count($people), 0);
	}
	
	function testAddToHasManyRelationship() {
		$person = new Person;
		$person->first_name = 'John';
		$person->age = 22; 
		
		$book = new Book();
		$book->title = 'Obama Wins!';
		
		$person->addToBooks($book);
		
		$this->assertEqual($book->author()->id, $person->id);
	}
	
	function testRemoveFromHasManyRelationship() {
		$barack = Make::a('Person')->equal('first_name', 'Barack')->first();
		
		$barackBooksCount = count($barack->books());
		
		$book = Make::a('Book')->like('title', '%Audacity%')->first();
		$barack->removeFromBooks($book);
		
		$this->assertEqual(count($barack->books()), $barackBooksCount - 1);
	}
	
	function testSetOnBelongsToRelationship() {
		$person = new Person;
		$person->first_name = 'John';
		$person->age = 22; 
		
		$book = new Book();
		$book->title = 'Obama Wins!';
		$book->setAuthor($person);
		
		$this->assertEqual($book->author()->id, $person->id);
	}
	
	function testUnsetOnBelongsToRelationship() {
		$barack = Make::a('Person')->equal('first_name', 'Barack')->first();
		
		$barackBooksCount = count($barack->books());
		
		$book = Make::a('Book')->like('title', '%Audacity%')->first();
		$book->unsetAuthor();
		
		$this->assertEqual(count($barack->books()), $barackBooksCount - 1);
	}
	
	function testNonDefaultPrimaryKey() {
		$person = new Person;
		$person->first_name = 'Katie';
		$person->age = 22;
		
		$car = new Car();
		$car->make = 'Honda';
		$car->insert();
		
		$person->addToCars($car);
		
		$this->assertEqual($car->pk, 3);
		$this->assertEqual($car->owner()->id, $person->id);
	}
	
	function testAddToHasAndBelongsToManyRelationship() {
		$book = new Book();
		$book->title = 'Hello world.';
		
		$genera = new Genera();
		$genera->title = 'Scary';
		
		$book->addToGeneras($genera);
		
		$resultBook = $genera->books()->first();
		
		$this->assertEqual($book->id, $resultBook->id);
	}
	
	function testRemoveFromHasAndBelongsToManyRelationship() {
		$book = Make::a('Book')->like('title','%Sketch%')->first();
		$generas = $book->generas();
		$generasCount = count($generas);
		
		$genera = $generas->first();
		$book->removeFromGeneras($genera);
		
		$this->assertEqual($generasCount - 1, count($book->generas()));
	}
	
	function testHasManyThrough() {
		$kris = Make::a('Person')->equal('first_name','Kris')->first();
		$groups = $kris->groups();
		$this->assertEqual(count($groups), 2);
	}
	
	function testHasManyOnDeleteDelete() {
		$audacity = Make::a('Book')->like('title', '%Audacity%')->first();
		
		$allChaptersCount = count(Make::a('Chapter')->all());
		$audacityChaptersCount = count($audacity->chapters());

		$audacity->delete();
		
		$this->assertEqual(count(Make::a('Chapter')->all()), $allChaptersCount - $audacityChaptersCount);
	}
	
	function testHasManyOnDeleteCascade() {
		$barack = Make::a('Person')->like('first_name','%Barack%')->first();
		$barack->delete();
		$this->assertEqual(count(Make::a('Chapter')->all()), 0);
	}
	
	function testHasManyOnDeleteNullify() {
		$kris = Make::a('Person')->equal('first_name', 'Kris')->first();
		
		$car = Make::a('Car');
		
		$all = $car->all();
		
		$allCarsCount = count($all);
		
		$kris->delete();
		
		$this->assertEqual($allCarsCount, count(Make::a('Car')->all()));
	}
	
	function testBelongsToDeleteNullify() {
		$audacity = Make::a('Book')->like('title','%Audacity%')->first();
		$audacity->chapters()->first()->delete();
		
		$audacity = Make::a('Book')->like('title','%Audacity%');
		$this->assertEqual(1, count($audacity));
	}
	
	function testBelongsToDeleteDelete() {
		$audacity = Make::a('Book')->like('title','%Audacity%')->first();
		$audacity->delete();
		$barack = Make::a('Person')->equal('first_name','Barack');
		$this->assertEqual(0, count($barack));
	}
	
	function testBelongsToDeleteCascade() {
		$car = Make::a('Car')->equal('make','VW')->first();
		$car->delete();
		
		$book = Make::a('Book')->like('title','%Michael Scott%');
		$this->assertEqual(0, count($book));
	}
	
	function testHasManyThroughDeleteNullify() {
		$groupshipsCount = count(Make::a('Groupship')->all());
		
		$kris = Make::a('Person')->equal('first_name','Kris')->first();
		$krisGroupsCount = count($kris->groups());
		
		$kris = Make::a('Person')->equal('first_name','Kris')->first();
		$kris->delete();
		
		$this->assertEqual($groupshipsCount - $krisGroupsCount, count(Make::a('Groupship')->all()));
	}
	
	function testHasManyThroughDeleteDelete() {
		$groupshipsCount = count(Make::a('Groupship')->all());
		
		$members = Make::a('Group')->equal('name','NRA')->persons();
		$nraPeopleCount = count($members);
			
		$nra = Make::a('Group')->equal('name','NRA')->first();
		$nra->delete();
		
		$this->assertEqual($groupshipsCount - $nraPeopleCount, count(Make::a('Groupship')->all()));
	}
	
	function testHasManyThroughOnFind() {
		$nra = Make::a('Group')->equal('name','NRA');
		$nraMembers = $nra->persons();
		$nraMembers2 = $nra->first()->persons();
		$this->assertEqual(count($nraMembers), count($nraMembers2));
	}
	
	function testHasManyThroughDeleteCascade() {
		$booksCount = count(Make::a('Book')->all());
		$sports = Make::a('Genera')->like('title','%Sports%');
		$sportsCount = count($sports->books());
		$sports->delete();
		$this->assertEqual($booksCount - $sportsCount, count(Make::a('Book')->all()));
	}
	
	function testNoLongerByReference() {
		$people = Make::a('Person')->all();
		$kris = $people->equal('first_name','Kris');
		$this->assertNotEqual(count($people),count($kris));
	}
	
	function tearDown() {
		$this->source->commit();
		$this->source->beginTransaction();
	 	$this->source->exec('DROP TABLE persons');
		$this->source->exec('DROP TABLE books');
		$this->source->exec('DROP TABLE movies');
		$this->source->exec('DROP TABLE generas');
		$this->source->exec('DROP TABLE movies_generas_joins');
		$this->source->exec('DROP TABLE books_generas_joins');
		$this->source->commit();
		unset($this->source);
	}
}

?>