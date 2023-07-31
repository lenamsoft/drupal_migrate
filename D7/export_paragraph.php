<?php
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
set_time_limit(0);

$_GET['type'] = "course";

$type = (!empty($_GET['type']))?$_GET['type']: 'page';

if(empty($_GET['type'])) {
	$query = db_query("SELECT node.nid AS nid FROM  {node} node  WHERE  (node.status = '1')  ORDER BY nid ASC");
}

else {
	$query = db_query("SELECT node.nid AS nid FROM  {node} node  WHERE  ( node.type =  '".$type."')  ORDER BY nid ASC");
}

$records = $query->fetchAll();
if (empty($records)) {
	print "";die();
}
$data = [];
foreach ($records as $val) {
	$node = node_load($val->nid);
	
	

	if (!empty($node->field_content)) {
		//$data[] = $node;

		foreach ($node->field_content['und'] as $p_value) {
			$pid = $p_value['value'];
			
			// echo $pid;

			//$entity=entity_load('paragraphs_item', array($pid));

			//$a =  $entity->toArray();

			$wrapper = entity_metadata_wrapper('paragraphs_item', $pid);
	
			$info = $wrapper->getPropertyInfo();

			$values = _wrapper_debug($wrapper);

			$values['nid'] = $node->nid;

			$values['bundle'] = $wrapper->getBundle();

			$data[] = $values;


			//print_R($values);die();

		}
	}
}
header('Content-Type: application/json; charset=utf-8');
print json_encode($data);
exit();

function _wrapper_debug($w) {
  $values = array();
  foreach ($w->getPropertyInfo() as $key => $val) {
    $values[$key] = $w->$key->value();
  }
  return $values;
}