<?php
/* Copyright (C) 2014 Alexis Algoud        <support@atm-conuslting.fr>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file       /scrumboard/scrum.php
 *	\ingroup    projet
 *	\brief      Project card
 */

 
	require('config.php');

	llxHeader('', $langs->trans('Tasks') , '','',0,0, array('/scrumboard/script/scrum.js.php'));
	
	$id_projet = (int)GETPOST('id');

	$object = new Project($db);
	$object->fetch($id_projet);

	$head=project_prepare_head($object);
    dol_fiche_head($head, 'scrumboard', $langs->trans("Scrumboard"),0,($object->public?'projectpub':'project'));

	
?>
<link rel="stylesheet" type="text/css" title="default" href="<?=dol_buildpath('/scrumboard/css/scrum.css',1) ?>">

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
