<?php
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;



if(empty($_GET['type'])) die();

$type = $_GET['type'];


if($type == 'all') {
  $query = db_query("SELECT node.nid AS nid FROM  {node} node  ORDER BY nid ASC ");
}

else {
  $query = db_query("SELECT node.nid AS nid FROM  {node} node  WHERE  (node.type =  '".$type."')  ORDER BY nid ASC");
}

$records = $query->fetchAll();
if (empty($records)) {
  print "";die();
}
$data = [];
foreach ($records as $val) {
  $node = node_load($val->nid);
  $url = $base_url . '' . url('node/' . $node->nid);
  $data[] = $node;
}
header('Content-Type: application/json; charset=utf-8');
print json_encode($data);
exit();

