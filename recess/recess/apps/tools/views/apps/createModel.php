<?php
Layout::extend('layouts/apps');
$title = $app;

Buffer::to($scripts);
include 'newModelJQuery.php';
Buffer::end();
?>

<h1>New <strong>Model</strong> Helper</h1>
<p>The purpose of this helper is to help speed the process of creating Recess Models. Please note <span class="highlight">this form is <strong>not</strong> child proof</span>!</p>
<form class="modelForm" method="POST" action="<?php echo $controller->urlTo('generateModel',$app); ?>">
<h2>Step 1) Name Your Model</h2>
<label for="modelName">Model Class Name:</label> <input id="modelName" type="text" name="modelName" />
<p>The name of your model must be a <span class="highlight">valid PHP class name</span>.</p>
<hr />
<h2>Step 2) Pick Your Database Table</h2>
<div class="span-19" id="tableOptions">
	<div class="span-7">
		<h3><input type="radio" name="tableExists" value="no" /> Table does not exist.</h3>
		<table style="display:none">
		<tr>
		<td><label for="dataSource">Data Source:</label></td>
		<td><select name="dataSource">
				<option value="Default">Default</option>
				<?php
				foreach($sources as $sourceName => $source):
					if($sourceName != "Default"):
				?>
				<option value="<?php echo $sourceName; ?>"><?php echo $sourceName; ?></option>
				<?php
					endif;		
				endforeach;
				?>
		</select></td>
		</tr>
		<tr>
			<td><label for="createTable">Create Table?:</label></td>
			<td><select id="createTable" name="createTable">
					<option value="Yes" selected="selected">Yes</option>
					<option value="No">No</option>
			</select></td>
		</tr>
		<tr id="tableNameRow">
			<td><label for="tableName">Table Name:</label></td>
			<td><input id="tableName" type="text" name="tableName" size="15" /></td>
		</tr>
		</table>
	</div>
	<div class="span-6 last">
		<h3><input type="radio" name="tableExists" value="yes" /> Table already exists.</h3>
		<table style="display:none">
		<tr>
		<td><label for="existingDataSource">Data Source:</label></td>
		<td><select id="existingDataSource" name="existingDataSource">
				<option value="Default" selected>Default</option>
				<?php
				foreach($sources as $sourceName => $source):
					if($sourceName != "Default"):
				?>
				<option value="<?php echo $sourceName; ?>"><?php echo $sourceName; ?></option>
				<?php
					endif;		
				endforeach;
				?>
		</select></td>
		</tr>
		<tr>
			<td><label for="existingTableName">Table:</label></td>
			<td><select id="existingTableName" name="existingTableName">
					<option value="" selected></option>
					<?php
					foreach($tables as $table):
					?>
						<option value="<?php echo $table; ?>"><?php echo $table; ?></option>
					<?php					
					endforeach;
					?>
			</select></td>
		</tr>
		</table>
	</div>
</div>
<hr />
<h2>Step 3) Properties</h2>
<div class="span-8">
	<table>
		<thead>
			<tr>
				<td>PK</td>
				<td>Property Name</td>
				<td>Type</td>
				<td>Remove</td>
			</tr>
		</thead>
		<tbody id="propertiesForm">
		<tr>
			<td><input type="radio" name="primaryKey" class="primaryKey" /></td>
			<td><input type="text" name="fields[]" class="fieldName" /></td>
			<td><select name="types[]" class="type">
			<?php
			Library::import('recess.database.pdo.RecessType');
			?>
					<option value="<?php echo RecessType::STRING; ?>">String</option>
					<option value="<?php echo RecessType::TEXT; ?>">Text</option>
					
					<option value="<?php echo RecessType::INTEGER; ?>">Integer</option>
					<option value="<?php echo RecessType::INTEGER; ?> Autoincrement">Integer (AutoIncrement)</option>
					<option value="<?php echo RecessType::FLOAT; ?>">Float</option>
					
					<option value="<?php echo RecessType::BOOLEAN; ?>">Boolean</option>
					
					<option value="<?php echo RecessType::TIMESTAMP; ?>">Timestamp</option>
					<option value="<?php echo RecessType::DATETIME; ?>">Date/Time</option>
					<option value="<?php echo RecessType::DATE; ?>">Date</option>
					<option value="<?php echo RecessType::TIME; ?>">Time</option>
					<option value="<?php echo RecessType::BLOB; ?>">Blob</option>
			</select></td>
			<td><input class="removeField" type="button" value="X"></input></td>
		</tr>
		</tbody>
	</table>
	<input type="button" class="addField top" value="Add a Property" />
</div>
<hr />
<!--  Coming soon :)
<h2>Step 4) Relationships</h2>
	<table>
		<thead>
			<tr>
				<td>Relation Name</td>
				<td>Type</td>
				<td>Related Class</td>
				<td>Advanced Options</td>
				<td>Remove</td>
			</tr>
		</thead>
		<tbody id="relationsForm">
		<tr>
			<td style="vertical-align:top"><input class="relationName" type="text" name="relationNames[]" /></td>
			<td style="vertical-align:top"><select name="relationTypes[]">
					<option value="hasMany">Has Many</option>
					<option value="belongsTo">Belongs To</option>
			</select></td>
			<td style="vertical-align:top"><select name="relationTypes[]">
					<option value="Books">Books</option>
					<option value="Post">Post</option>
					<option value="MyModel">MyModel</option>
			</select></td>
			<td>
			<a href="javascript:void(0);">Show</a>
				<table style="display:none;">
					<tr>
						<td>Foreign Key:</td>
						<td>
							<input type="text" name="foreignKeys[]" value="" />
						</td>
					</tr>
					<tr>
						<td>Through:</td>
						<td>
							<input type="checkbox" name="throughs[]" value="1" />
							<select name="throughClasses[]">
									<option value="Posts">Posts</option>
									<option value="Comments">Comments</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>On Delete:</td>
						<td>
							<select name="onDeletes[]">
									<option value="Cascade">Cascade</option>
									<option value="Delete">Delete</option>
									<option value="Nullify">Nullify</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td style="vertical-align:top"><input class="removeRelation" type="button" value="X"></input></td>
		</tr>
		</tbody>
	</table>
	<input type="button" class="addRelation" value="Add a Relationship" />
	<hr />
 -->
<h2>Step 4) Go!</h2>
<input id="generateModel" type="submit" value="Generate Model" />
</form>
<table style="visibility:hidden;">
	<tbody id="propertyTemplate">
	</tbody>
</table>
<table style="visibility:hidden;">
	<tbody id="relationTemplate">
	</tbody>
</table>