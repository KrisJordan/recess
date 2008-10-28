<?php

class Model extends Box {
	public function __construct(){
		if(!isset($this->table))
			$this->table = get_class($this);
	}
	
	public function hasOne($name, RelationMeta $meta) { 

		
		
	}
	
	public function belongsTo($name, RelationMeta $meta) { 
		
		
	} // Returns Core
	
	public function hasMany($name, RelationMeta $meta) {
		$resultSet = new RowSet($this);
		
		// we're in section
		return $resultSet->fromTable('pages')->equal($meta->foreignKey, $this->id);
		
		// many many - we're in people
		// people_groups: person_id, group_id
		return $resultSet->fromTable('groups')->joinTable($meta->throughTable, 'id', $meta->associationForeignKey, $meta->foreignKey, $this->$primaryKey);
		
	} // returns CollectionOfCores
	
	public function hasManyMany($name, RelationMeta $meta){
		$this->hasMany($name, $meta->throughTable('infer'));
	}
}

?>