<?php
// Railsian

/**
 * !HasMany tags, Through: TagsPosts
 * !BelongsTo author, Class: Person
 */
class Post { }

/**
 * !HasMany posts
 */
class Person { }

/**
 * !HasMany posts, Through: TagsPosts
 */
class Tag { }

/**
 * !BelongsTo post
 * !BelongsTo tag
 */
class TagsPosts { }

/**
 * !HasMany classes, Class: RecessReflectorClass,Key: packageId
 * !HasMany children, Class: RecessReflectorPackage,Key: parentId
 * !BelongsTo parent, Class: RecessReflectorPackage,Key: parentId
 * !Table packages
 */
class RecessReflectorPackage extends Model { }

////////////// 
// Djangonian
//////////////

class Post { 
	/** !ForeignKey author, Class: Person */
	public $authorId;
}

class Person { }

class Tag { }

class TagsPosts {	
	/** !ForeignKey post */
	public $postId;
	
	/** !ForeignKey tag */
	public $tag;
}

/**
 * !Table packages
 */
class RecessReflectorPackage extends Model {
	/** !Column Integer */
	/** !ForeignKey parent, Class: RecessReflectorPackage, RelatedName: children */ 
	public $parentId;
}

$post->tagSet();

////////////// 
// Recess'ian
//////////////

class Post { 
	/** !ForeignKey author, Class: Person */
	public $authorId;
}

/** !Many people */
class Person { }

/** !Many tags */
class Tag { }

class TagsPosts {	
	/** !ForeignKey RelatedName: tags, JoinThrough: tag */
	public $postId;
	
	/**
	 * !ForeignKey Class: Post
	 */
	public $oldPostId;
	
	/** !ForeignKey tag, Class: Tag, RelatedName: posts, JoinThrough: post */
	public $tagId;
}








/**
 * Plural: posts
 */
class Post extends Model {
	
	/** !Column PrimaryKey, Integer, AutoIncrement */
	public $id;
	
	/** !Column Boolean */
	public $isHistorical;
	
	/** !Column String */
	public $title;
	
	/** !Column Text */
	public $body;
	
	/**
	 * !Column Integer
	 * !ForeignKey Post, RelatedName: versions
	 */
	public $originalId;
	
	/**
	 * !Column Integer
	 * !ForeignKey Name: author, Class: User
	 */
	public $writerId;
	
	/**
	 * !Column Integer
	 * !ForeignKey
	 */
	public $categoryId;
	
}

/** !Plural: users */
class User extends Model {
	
}
















/**
 * !Table packages
 */
class RecessReflectorPackage extends Model {
	/** !Column Integer */
	/** !ForeignKey parent, Class: RecessReflectorPackage, RelatedName: children */ 
	public $parentId;
}


?>