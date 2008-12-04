<script language="javascript">
	window.onload = function() {
		dp.SyntaxHighlighter.ClipboardSwf = '/content/flash/clipboard.swf';
		dp.SyntaxHighlighter.HighlightAll('code');
		
		function addField() {
			$("#propertiesForm .removeField:last").unbind('blur');
			$("#propertyTemplate").children().clone().appendTo("#propertiesForm");
			$("#propertiesForm .removeField:last").click(function() { 
				$(this).parent().parent().remove(); 
				colorTables();
			});
			setPropertyFocus();
			colorTables();
		}
		
		function addRelation() {
			$("#relationTemplate").children().clone().appendTo("#relationsForm");
			$("#relationsForm .removeRelation:last").click(function() { $(this).parent().parent().remove(); });
			$("#relationsForm a").click( function() {
				$(this).siblings().show();
				$(this).remove();
				colorTables();
			});
			$("#relationsForm .relationName:last").focus();
			colorTables();
		}
		
		function colorTables() {
			$("#relationsForm").children(":odd").css("background-color", "#f5f5fa");
			$("#relationsForm").children(":even").css("background-color", "#fff");
			$("#propertiesForm").children(":odd").css("background-color", "#f5f5fa");
			$("#propertiesForm").children(":even").css("background-color", "#fff");
		}
		
		function setPropertyFocus() {
			$("#propertiesForm .removeField:last").blur( addField );
			$("#propertiesForm .fieldName:last").focus();
		}
		
		$("#tableOptions :radio").change( function() {
			if($(this).attr("checked") == true) {
				$("#tableOptions table").css({display: "none"});
				$(this).parent().parent().children("table").css({display: "block"});
			}
		} );
		
		$("#createTable").change( function() {
			if($(this).val() == "Yes") {
				$("#tableNameRow").show();
			} else {
				$("#tableNameRow").hide();
			}
		});
		
		$(".addField").click( addField );
		$(".addRelation").click( addRelation );
		
		$("#propertiesForm .removeField:last").blur( addField );
		$("#modelName").focus();
		
		$("#propertiesForm .removeField:last").click(function() { $(this).parent().parent().remove(); });
			
		$("#propertiesForm").children().clone().appendTo("#propertyTemplate");
		
		$("#relationsForm").children().appendTo("#relationTemplate");
	}
</script>
<script type="text/javascript" src="<?php echo $_ENV['url.content']; ?>js/jquery/jquery-1.2.6.js"></script> 