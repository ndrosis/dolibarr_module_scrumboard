<?php
	require('../config.php');
?>
function project_get_tasks(id_project, liste, status) {
	$('#'+liste).empty();
	
	$.ajax({
		url : "./script/interface.php"
		,data: {
			json:1
			,get : 'tasks'
			,status : status
			,id_project : id_project
			,async:false
		}
		,dataType: 'json'
	})
	.done(function (tasks) {
		
		$.each(tasks, function(i, task) {
			project_draw_task(id_project, task, $('#'+liste));
		});
				
	}); 
}
function project_create_task(id_project) {
	$.ajax({
		url : "./script/interface.php"
		,data: {
			json:1
			,put : 'task'
			,id_project : id_project
			,status:'idea'
		}
		,dataType: 'json'
	})
	.done(function (task) {
	
		project_draw_task(id_project, task, $('#list-task-idea'));
		project_develop_task(task.id);
	}); 
	
}
function project_draw_task(id_project, task, ul) {
	$('#task-blank').clone().attr('id', 'task-'+task.id).appendTo(ul);
	project_refresh_task(id_project, task);
}
function project_refresh_task(id_project, task) {
	
	$item = $('#task-'+task.id);
	
	
	$item.attr('task-id', task.id);
	
	$item.removeClass('idea todo inprogress finish');
	$item.addClass(task.status);
	
	$item.find('[rel=progress]').html(task.progress+'%');
	$item.find('[rel=label]').html(task.label).attr("title", task.description).tipTip({maxWidth: "600px", edgeOffset: 10, delay: 50, fadeIn: 50, fadeOut: 50});;
	$item.find('[rel=ref]').html(task.ref).attr("href", '<?=dol_buildpath('/projet/tasks/task.php?withproject=1&id=',1) ?>'+task.id);
	
}
function project_get_task(id_project, id_task) {
	var taskReturn="";
	$.ajax({
		url : "./script/interface.php"
		,data: {
			json:1
			,get : 'task'
			,id : id_task
			,id_project : id_project
		}
		,dataType: 'json'
		,async:false
	})
	.done(function (lTask) {
		//alert(lTask.name);
		taskReturn = lTask;
	}); 
	
	return taskReturn;
}
function project_init_change_type(id_project) {
	
    $('.task-list').sortable( {
    	connectWith: ".task-list"
    	, placeholder: "ui-state-highlight"
    	,receive: function( event, ui ) {
			task=project_get_task(id_project, ui.item.attr('task-id'));
			task.status = $(this).attr('rel');
			
			$('#task-'+task.id).css('top','');
	        $('#task-'+task.id).css('left','');	
			$('#list-task-'+task.status).prepend( $('#task-'+task.id) );	
			console.log('#task-'+task.id+' --> '+'#list-task-'+task.status);	
			
			project_save_task(id_project, task);
									        
	  }  
	  ,update:function(event,ui) {
	  	var sortedIDs = $( this ).sortable( "toArray" );
	  	
	  	var TTaskID=[];
	  	$.each(sortedIDs, function(i, id) {
	  		
	  		taskid = $('#'+id).attr('task-id');
	  		TTaskID.push( taskid );
	  	});
	  		
	  	$.ajax({
			url : "./script/interface.php"
			,data: {
				json:1
				,put : 'sort-task'
				,TTaskID : TTaskID
			}
			,dataType: 'json'
		});
	  	
	  }
    });

    
    
}
function project_getsave_task(id_project, id_task) {
	
	task = project_get_task(id_project, id_task);
	$item = $('#task-'+task.id);
	
	task.name = $item.find('[rel=name]').val();
	task.status = $item.find('[rel=status]').val();
	task.type = $item.find('[rel=type]').val();
	task.point = $item.find('[rel=point]').val();
	task.description = $item.find('[rel=description]').val();
	
	project_save_task(id_project, task);
}
function project_save_task(id_project, task) {
	$('#task-'+task.id).css({ opacity:.5 });
	
	$.ajax({
		url : "./script/interface.php"
		,data: {
			json:1
			,put : 'task'
			,id : task.id
			,status : task.status
			,id_project : id_project
			,label : task.label
		}
		,dataType: 'json'
		,type:'POST'
	})
	.done(function (task) {
		project_refresh_task(id_project, task);
		$('#task-'+task.id).css({ opacity:1 });
	}); 
	
}
function project_develop_task(id_task) {
	$('#task-'+id_task+' div.view').toggle();
}
function loadTasks(id_projet) {
	
					/*project_get_tasks(id_projet, 'list-task-idea', 'idea');*/
				project_get_tasks(id_projet , 'list-task-todo', 'todo');
				project_get_tasks(id_projet , 'list-task-inprogress', 'inprogress');
				project_get_tasks(id_projet , 'list-task-finish', 'finish');
				
			
	
}
function create_task(id_projet) {
	
	if($('#dialog-create-task').length==0) {
		$('body').append('<div id="dialog-create-task"></div>');
	}
	var url ="<?= dol_buildpath('/projet/tasks.php?action=create&id=',1) ?>"+id_projet
		
	$('#dialog-create-task').load(url+" div.fiche form",function() {
		
		$('#dialog-create-task input[name=cancel]').remove();
		$('#dialog-create-task form').submit(function() {
			
			$.post($(this).attr('action'), $(this).serialize(), function() {
				loadTasks(id_projet);
			});
		
			$('#dialog-create-task').dialog('close');			
			
			return false;
	
			
		});
		
		$(this).dialog({
			title: "<?=$langs->trans('AddTask') ?>"
			,width:800
			,modal:true
		});
		
	});
}
