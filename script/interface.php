<?php

require ('../../../inc.php');

$get = GETPOST('get','alpha');
$put = GETPOST('put','alpha');

_put($db, $put);
_get($db, $get);

function _get(&$db, $case) {
	switch ($case) {
		case 'tasks' :
			
			print json_encode(_tasks($db, (int)$_REQUEST['id_project'], (int)$_REQUEST['status']));

			break;
		case 'task' :
			
			$task=new Task($db);
			$task->fetch((int)GETPOST('id'));
			
			print json_encode(_as_array($task));

			break;
	}

}
function _as_array(&$object, $recursif=false) {
	$Tab=array();
		foreach ($object as $key => $value) {
				
			if(is_object($value) || is_array($value)) {
				if($recursif) $Tab[$key] = _as_array($recursif, $value);
				else $Tab[$key] = $value;
			}
			else if(strpos($key,'date_')===0){
				if(empty($value))$Tab[$key] = '0000-00-00 00:00:00';
				else $Tab[$key] = date('Y-m-d H:i:s',$value);
			}
			else{
				$Tab[$key]=$value;
			}
		}
		return $Tab;
	
}

function _put(&$db, $case) {
	switch ($case) {
		case 'task' :
			__out(_task($db, (int)GETPOST('id'), $_REQUEST));
			break;
		case 'sort-task' :
			
			_sort_task($db, $_REQUEST['TTaskID']);
			
			break;
	}

}
function _sort_task(&$db, $TTask) {
	
	foreach($TTask as $rank=>$id) {
		$task=new Task($db);
		$task->fetch($id);
		$task->rank = $rank;
		$task->update($db);
	}
	
}
function _task(&$db, $id_task, $values) {
	$task=new TTask;
	if($id_task) $task->load($db, $id_task);
	$task->set_values($values);
	
	$task->save($db);
	
	if(empty($task->name)) {
		$task->name = __tr("Task").' '.$task->getId();
		$task->save($db);	
	}
	return $task->get_values();
}

function _tasks(&$db, $id_project, $status) {
	
	$TId = TRequeteCore::_get_id_by_sql($db, "SELECT id FROM ".DB_PREFIX."project_task WHERE id_project=".$id_project." AND status='".$status."' ORDER BY rank");
	$TTask = array();
	foreach($TId as $id) {
		$t=new TTask;
		$t->load($db, $id);
		
		$TTask[] = $t->get_values();
	}
	
	return $TTask;
}
