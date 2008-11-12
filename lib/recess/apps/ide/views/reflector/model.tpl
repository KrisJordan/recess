{assign var='parent' value=$reflection->getParentClass()}
<h1>{$reflection->name} (extends {$parent->name})</h1>
<div class="orm">
<h2>Model Info</h2>
<h3>Table: {$table}</h3>
<h3>Source: </h3>
<h3>Relationships</h3>
<ul class="relationships">
{foreach from=$relationships item=relationship}
	<li><span class="relationship-type">{$relationship->getType()}</span> {$relationship->name}, Class: <a href="{$relationship->foreignClass}">{$relationship->foreignClass}</a>
	<ul>
		<li>ForeignKey: {$relationship->foreignKey}</li>
		<li>OnDelete: {if $relationship->onDelete == 'unspecified'}{$relationship->getDefaultOnDeleteMode()|ucfirst}{else}{$relationship->onDelete|ucfirst}{/if}</li>
		{if $relationship->through != ''}
			<li>Through: {$relationship->through}</li>
		{/if}
	</ul>
	</li>
{/foreach}
</ul>
<h3>Columns</h3>
<ul>
{foreach from=$columns item=column}
	<li>{$column}</li>
{/foreach}
</ul>
</div>
<h2>Class Info</h2>
<h3>Properties</h3>
<ul class="properties">
{foreach from=$reflection->getProperties() item=property}
{if !$property->isStatic() && $property->isPublic()}
	<li>{$property->name}</li>
{/if}
{/foreach}
</ul>
<h3>Methods</h3>
<h4>Attached Methods</h4>
<ul class="attached-methods">
{foreach from=$reflection->getMethods() item=method}
	{if $method->isPublic() && !$method->isStatic() && $method->isAttached()}
	<li>{$method->name} ({foreach from=$method->getParameters() item=param name=params}
		{if $smarty.foreach.params.first != true}, {/if}
		${$param->name} 
	{/foreach})</li>
	{/if}
{/foreach}
</ul>
<h4>Instance Methods</h4>
<ul class="instance-methods">
{foreach from=$reflection->getMethods() item=method}
	{if $method->isPublic() && !$method->isStatic() && !$method->isAttached() && $method->name != '__call'}
	<li>{$method->name}({foreach from=$method->getParameters() item=param name=params} 
		{if $smarty.foreach.params.first != true}, {/if}
		${$param->name}
	{/foreach})</li>
	{/if}
{/foreach}
</ul>
<h4>Static Methods</h4>
<ul class="static-methods">
{foreach from=$reflection->getMethods() item=method}
	{if $method->isPublic() && $method->isStatic()}
	<li>{$method->name}({foreach from=$method->getParameters() item=param name=params} 
		{if $smarty.foreach.params.first != true}, {/if}
		${$param->name}
	{/foreach})</li>
	{/if}
{/foreach}
</ul>