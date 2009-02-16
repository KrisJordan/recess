<?php
Library::import('recess.lang.RecessObjectRegistry');

Library::import('recess.database.Databases');
Library::import('recess.database.orm.Model');
Library::import('recess.database.orm.ModelDataSource');

/**
 * !BelongsTo owner, Class: Person, Key: personId, OnDelete: Cascade
 * !Table cars
 */
class Car extends Model {
	
	/** !Column PrimaryKey, integer, AutoIncrement */
	public $pk;
	
	/** !Column Boolean */
	public $isDriveable;
	
}

/**
 * !HasMany persons
 * !Table political_partys
 */
class PoliticalParty extends Model {}

/**
 * !HasMany persons, Through: Groupship, OnDelete: Delete
 * !Table groups
 */
class Group extends Model {}

/**
 * !BelongsTo group
 * !BelongsTo person
 * !Table groupships
 */
class Groupship extends Model {}

/**
 * !HasMany books, Key: authorId, OnDelete: Cascade
 * !HasMany novels, Key: authorId, Class: Book
 * !HasMany cars, OnDelete: Nullify
 * !HasMany groups, Through: Groupship, OnDelete: Nullify
 * !BelongsTo politicalParty
 * !Table persons
 */
class Person extends Model {}

/**
 * !BelongsTo author, Class: Person, OnDelete: Delete
 * !HasMany chapters, OnDelete: Delete
 * !HasMany generas, Through: BooksGenerasJoin, OnDelete: Delete
 * !Table books
 */
class Book extends Model { }

/**
 * !BelongsTo book, OnDelete: Cascade
 * !BelongsTo genera
 * !Table books_generas_joins
 */
class BooksGenerasJoin extends Model { }

/**
 * !BelongsTo book, OnDelete: Nullify
 * !Table chapters
 */
class Chapter extends Model { }

/**
 * !HasMany books, Through: BooksGenerasJoin, OnDelete: Cascade
 * !HasMany movies, Through: MoviesGenerasJoin
 * !Table generas
 */
class Genera extends Model { }

/**
 * !BelongsTo movie
 * !BelongsTo genera
 * !Table movies_generas_joins
 */
class MoviesGenerasJoin extends Model { }

/**
 * !HasMany generas, Through: MoviesGenerasJoin
 * !Table movies
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
		$this->source->exec('CREATE TABLE persons (id INTEGER PRIMARY KEY AUTOINCREMENT, firstName TEXT, lastName TEXT, age TEXT, politicalPartyId INTEGER, phone TEXT)');
		$this->source->exec('CREATE TABLE groups (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT)');
		$this->source->exec('CREATE TABLE groupships (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, groupId INTEGER, personId INTEGER)');
		$this->source->exec('CREATE TABLE books (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, authorId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE chapters (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, bookId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE cars (pk INTEGER PRIMARY KEY ASC AUTOINCREMENT, personId INTEGER, make TEXT, isDriveable BOOLEAN)');
		$this->source->exec('CREATE TABLE movies (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, authorId INTEGER, title TEXT)');
		$this->source->exec('CREATE TABLE generas (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, title INTEGER)');
		$this->source->exec('CREATE TABLE books_generas_joins (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, bookId INTEGER, generaId INTEGER)');
		$this->source->exec('CREATE TABLE political_partys (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, party TEXT)');
		$this->source->exec('CREATE TABLE movies_generas_joins (id INTEGER PRIMARY KEY ASC AUTOINCREMENT, movieId INTEGER, generaId INTEGER)');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("Kris", "Jordan", 23, 1, "321-456-7890")');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("Joel", "Sutherland", 23, 1, "919-485-5387")');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("Clay", "Schossow", 22, 2, "917-228-5749")');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("Barack", "Obama", 47, 1, "203-507-4577")');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("Josh", "Lockhart", 22, 1, "")');
		$this->source->exec('INSERT INTO persons (firstName, lastName, age, politicalPartyId, phone) VALUES ("John", "McCain", 72, 3, "")');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Democrat")');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Independent")');
		$this->source->exec('INSERT INTO political_partys (party) VALUES ("Republican")');
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (4,"The Audacity of Hope: Thoughts on Reclaiming the American Dream")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Republicans and Democrats")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Values")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Our Constitution")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Politics")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Opportunity")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Faith")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Race")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"The World Beyond our Borders")');
		$this->source->exec('INSERT INTO chapters (bookId, title) VALUES (1,"Family")');
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (3,"How to Be a Sketch Ball")'); // 1
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (2,"Steve Nash: A Modern Day Hero")'); // 2
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (1,"How Michael Scott Touched My Life, and Could Touch Yours Too")'); // 3
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (4,"Dreams from My Father: A Story of Race and Inheritance")'); // 4
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (4,"Barack Obama: What He Believes In - From His Own Works")'); // 5
		$this->source->exec('INSERT INTO books (authorId, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story")'); // 6
		$this->source->exec('INSERT INTO movies (authorId, title) VALUES (3,"Hoop Dreams, The Clay Schossow Story, The Movie")');
		$this->source->exec('INSERT INTO movies (authorId, title) VALUES (3,"Clay Schossow: Unleashed")');
		$this->source->exec('INSERT INTO movies (authorId, title) VALUES (3,"LeBron James and Other Assorted Dreams of Clay Schossow")');
		$this->source->exec('INSERT INTO generas (title) VALUES ("Sports Healing")'); // 1
		$this->source->exec('INSERT INTO generas (title) VALUES ("Political Healing")'); // 2
		$this->source->exec('INSERT INTO generas (title) VALUES ("Social Healing")'); // 3
		$this->source->exec('INSERT INTO generas (title) VALUES ("Comedy Healing")'); // 4
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (1,3)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (2,3)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (2,4)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (3,1)'); // 4: 4, 6: 3
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (4,4)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (5,3)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (6,3)');
		$this->source->exec('INSERT INTO books_generas_joins (bookId,generaId) VALUES (7,1)');
		$this->source->exec('INSERT INTO movies_generas_joins (movieId,generaId) VALUES (1,1)');
		$this->source->exec('INSERT INTO movies_generas_joins (movieId,generaId) VALUES (1,4)');
		$this->source->exec('INSERT INTO movies_generas_joins (movieId,generaId) VALUES (2,4)');
		$this->source->exec('INSERT INTO movies_generas_joins (movieId,generaId) VALUES (3,1)');
		$this->source->exec('INSERT INTO groups (name) VALUES ("NRA")'); // 1
		$this->source->exec('INSERT INTO groups (name) VALUES ("Tree Huggers")'); // 2
		$this->source->exec('INSERT INTO groups (name) VALUES ("Hackers")'); // 3
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (2,1)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (3,1)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (3,2)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (2,2)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (1,3)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (2,4)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (2,5)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (3,5)');
		$this->source->exec('INSERT INTO groupships (groupId,personId) VALUES (1,6)');
		$this->source->exec('INSERT INTO cars (personId,make,isDriveable) VALUES (1,"VW",1)');
		$this->source->exec('INSERT INTO cars (personId,make,isDriveable) VALUES (2,"Toyota",1)');
		$this->source->commit();
		$this->source->beginTransaction();
		Databases::setDefaultSource($this->source);
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
		$people = $person->find()->orderBy('lastName DESC');
		$this->assertEqual(count($people),2);
		$this->assertEqual($people[0]->lastName, 'Sutherland');
		$this->assertEqual(get_class($people[0]), 'Person');
	}
	
	function testBooleanType() {
		$car = new Car();
		$car->isDriveable = true;
		$car->insert();
		$carId = $car->pk;
		
		$car = new Car();
		$car->pk = $carId;
		$driveable =  $car->find()->first()->isDriveable;
		
		$this->assertEqual($driveable === true, true);
	}
	
	function testFindTailCriteria() {
		$person = new Person();
		$people = $person->find()->greaterThan('age',22);
		$this->assertEqual(count($people),4);
	}
	
	function testBetween() {
		$people = Make::a('Person')->all()->between('age',21,25);
		$this->assertEqual(count($people), 4);
	}
	
	function testMultipleConditionsOnSingleProperty() {
		$people = Make::a('Person')->greaterThan('age',21)->lessThan('age',25);
		$this->assertEqual(count($people), 4);
	}
	
	function testMultipleConditionsOnStrings() {
		$people = Make::a('Person')->like('firstName','%K%')->like('firstName','%s%');
		$this->assertEqual(count($people), 1);
	}
	
	function testHasManyRelationship() {
		$person = new Person();
		$person->id = 4;
		$books = $person->books()->orderBy('title');
		$this->assertEqual(count($books), 3);
	}
	
	function testHasManyRelationshipCriteria() {
		$person = new Person();
		$person->firstName = 'Barack';
		$person->lastName = 'Obama';
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
		$this->assertEqual($barack->firstName, 'Barack');
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
		$name = $person->firstName;
		
		$person->firstName = 'UPDATE!';
		$person->update();
		
		$people = Make::a('Person')->all();
		
		$this->assertEqual($person->firstName, $people[0]->firstName);
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
		$person->firstName = 'Joe';
		$person->lastName = 'Biden';
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
		$person->firstName = 'John';
		$person->age = 22; 
		
		$book = new Book();
		$book->title = 'Obama Wins!';
		
		$person->addToBooks($book);
		
		$this->assertEqual($book->author()->id, $person->id);
	}
	
	function testRemoveFromHasManyRelationship() {
		$barack = Make::a('Person')->equal('firstName', 'Barack')->first();
		
		$barackBooksCount = count($barack->books());
		
		$book = Make::a('Book')->like('title', '%Audacity%')->first();
		$barack->removeFromBooks($book);
		
		$this->assertEqual(count($barack->books()), $barackBooksCount - 1);
	}
	
	function testSetOnBelongsToRelationship() {
		$person = new Person;
		$person->firstName = 'John';
		$person->age = 22; 
		
		$book = new Book();
		$book->title = 'Obama Wins!';
		$book->setAuthor($person);
		
		$this->assertEqual($book->author()->id, $person->id);
	}
	
	function testUnsetOnBelongsToRelationship() {
		$barack = Make::a('Person')->equal('firstName', 'Barack')->first();
		
		$barackBooksCount = count($barack->books());
		
		$book = Make::a('Book')->like('title', '%Audacity%')->first();
		$book->unsetAuthor();
		
		$this->assertEqual(count($barack->books()), $barackBooksCount - 1);
	}
	
	function testNonDefaultPrimaryKey() {
		$person = new Person;
		$person->firstName = 'Katie';
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
		$kris = Make::a('Person')->equal('firstName','Kris')->first();
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
		$barack = Make::a('Person')->like('firstName','%Barack%')->first();
		$barack->delete();
		$this->assertEqual(count(Make::a('Chapter')->all()), 0);
	}
	
	function testHasManyOnDeleteNullify() {
		$kris = Make::a('Person')->equal('firstName', 'Kris')->first();
		
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
		$barack = Make::a('Person')->equal('firstName','Barack');
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
		
		$kris = Make::a('Person')->equal('firstName','Kris')->first();
		$krisGroupsCount = count($kris->groups());
		
		$kris = Make::a('Person')->equal('firstName','Kris')->first();
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
		$kris = $people->equal('firstName','Kris');
		$this->assertNotEqual(count($people),count($kris));
	}
	
	function testTicket68FirstAfterEqual() {
		$person = Make::a('Person')->equal('phone', '321-456-7890')->first();
		$this->assertEqual($person->id, 1);
		$this->assertEqual($person->phone, '321-456-7890');
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