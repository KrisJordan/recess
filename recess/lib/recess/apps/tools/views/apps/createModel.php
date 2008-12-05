<?php
$title = 'New Model Helper';
$selectedNav = 'apps';
$scripts = array('apps/newModelJQuery.php');
include_once($viewsDir . 'common/header.php');
?>
<h1>New <strong>Model</strong> Helper</h1>
<p>The purpose of this helper is to help speed the process of creating Recess! Models. Please note <span class="highlight">this form is <strong>not</strong> child proof</span>!</p>
<form class="modelForm" method="POST" action="<?php echo $controller->urlToMethod('generateModel',$app); ?>">
<h2>Step 1) Name Your Model</h2>
<label for="modelName">Model Class Name:</label> <input id="modelName" type="text" name="modelName" />
<p>The name of your model must be a <span class="highlight">valid PHP class name</span>.</p>
<hr />
<h2>Step 2) Pick Your Database Table</h2>
<div class="span-19" id="tableOptions">
	<div class="span-7">
		<h3><input type="radio" name="tableExists" value="yes" /> Table does not exist.</h3>
		<table style="display:none">
		<tr>
		<td><label for="dataSource">Data Source:</label></td>
		<td><select name="dataSource">
				<option value="Default">Default</option>
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
		<h3><input type="radio" name="tableExists" value="no" /> Table already exists.</h3>
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
	<table>
		<thead>
			<tr>
				<td>Property Name</td>
				<td>Type</td>
				<td>Nullable?</td>
				<td>Default Value</td>
				<td>Remove</td>
			</tr>
		</thead>
		<tbody id="propertiesForm">
		<tr>
			<td><input type="text" name="fields[]" class="fieldName" /></td>
			<td><select name="types[]" class="type">
					<option value="string">String</option>
					<option value="text">Text</option>
					
					<option value="integer">Integer</option>
					<option value="decimal">Decimal</option>
					<option value="float">Float</option>
					
					<option value="time">Time</option>
					<option value="timestamp">Timestamp</option>
					<option value="date">Date</option>
					<option value="datetime">Date/Time</option>
					
					<option value="blob">Blob</option>
					<option value="boolean">Boolean</option>
			</select></td>
			<td><input class="nullable" type="checkbox" name="nullables[]" value="1" checked="checked" /></td>
			<td><input class="defaultValue" type="text" name="defaultValues[]" /></td>
			<td><input class="removeField" type="button" value="X"></input></td>
		</tr>
		</tbody>
	</table>
	<input type="button" class="addField" value="Add a Property" />
</p>
<hr />
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
<h2>Step 5) Go!</h2>
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
<?php
include_once($viewsDir . 'common/footer.php');
?>