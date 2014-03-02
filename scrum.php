<?php
	require('config.php');

	llxHeader('', $langs->trans('Tasks') , '','',0,0, array('/scrumboard/script/scrum.js.php'));
	
	$id_projet = (int)GETPOST('id');
	
?>
<link rel="stylesheet" type="text/css" title="default" href="<?=dol_buildpath('/scrumboard/css/scrum.css') ?>">

		<div class="content">
			<table id="scrum">
				<tr>
					<td><?=$langs->trans('Ideas'); ?></td></td>
					<td><?=$langs->trans('toDo'); ?>		</td></td>
					<td><?=$langs->trans('inProgress'); ?></td></td>
					<td><?=$langs->trans('finish'); ?></td></td>
				</tr>
				<tr>
					<td class="projectDrag droppable" id="task-idea" rel="idea">
						<ul id="list-task-idea" class="task-list" rel="idea">
						
						</ul>
					</td>
					<td class="projectDrag droppable" id="task-todo" rel="todo">
						<ul id="list-task-todo" class="task-list" rel="todo">
						
						</ul>
					</td>
					<td class="projectDrag droppable" id="task-inprogress" rel="inprogress">
						<ul id="list-task-inprogress" class="task-list" rel="inprogress">
						
						</ul>
					</td>
					<td class="projectDrag droppable" id="task-finish" rel="finish">
						<ul id="list-task-finish" class="task-list" rel="finish">
						
						</ul>
					</td>
				</tr>
			</table>
		
		</div>
		
		<div style="display:none">
			
			<ul><li id="task-blank">
				<div class="min-view">
				<a class="title">title</a> 
				</div>
				<div class="view">
					<input name="title" rel="name" value=""/>
					<div>
						<select name="point" rel="point"><option value="[point.$; block=option]">[point.val]</option></select>
						<select name="status" rel="status"><option value="[status.$; block=option]">[status.val]</option></select> 
						<select name="type" rel="type"><option value="[type.$; block=option]">[type.val]</option></select> 
					 </div>
					
					<textarea name="description" rel="description" rows="3"></textarea>
					<div>
					<a class="addTime"><?=$langs->trans('addTime'); ?></a>
					<a class="save"><?=$langs->trans('Save'); ?></a>
					</div>
				</div>
				</li>
			</ul>
			
		</div>
		
		<script type="text/javascript">
			$(document).ready(function() {
				
				project_get_tasks(<?=$id_projet ?>, 'list-task-idea', 'idea');
				project_get_tasks(<?=$id_projet ?>, 'list-task-todo', 'todo');
				project_get_tasks(<?=$id_projet ?>, 'list-task-inprogress', 'inprogress');
				project_get_tasks(<?=$id_projet ?>, 'list-task-finish', 'finish');
				
				project_init_change_type(<?=$id_projet ?>);
			});
		</script>
		
<?

	llxFooter();
