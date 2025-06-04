<?php
define('DRUPAL_ROOT', getcwd());
include_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;

$type = (!empty($_GET['type']))?$_GET['type']:'';

if($type) {
    $sql = "SELECT node.nid AS nid FROM {node} node WHERE node.type = '$type' ORDER BY nid ASC ";
     $query = db_query($sql);


    $records = $query->fetchAll();

    if (empty($records)) {
      print "";die();
    }

    

    foreach ($records as $val) {
      $nid = $val->nid;
      $node = node_load($val->nid);
      $url = url('node/' . $node->nid);

      $node->url = $url;
      

      $data[] = $node;
    }



    header('Content-Type: application/json; charset=utf-8');
    print json_encode($data);
    exit();

}
else {
    display_form();
}

function display_form() {

        $all_node_types = get_all_node_types();
        //print_r($all_node_types);

        $output .= '<form method="GET">';

          foreach ($all_node_types as $type) {
            $output .= '<label>';
            $output .= '<input type="radio" name="type" value="' . check_plain($type->type) . '">';
            $output .= check_plain($type->type) . " - " .check_plain($type->name);
            $output .= '</label><br>';
          }

          $output .= '<input type="submit" value="Export">';
          $output .= '</form>';

        echo $output;
}

function get_all_node_types() {
  $types = node_type_get_types();
  
  return $types;
}


