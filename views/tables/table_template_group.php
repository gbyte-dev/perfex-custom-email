<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['name','template_id','created_at'];

$sIndexColumn = 'id';
$sTable       = db_prefix().'emailtemplate_group';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $row[]   = $aRow['name'];
     $row[]   = $aRow['created_at'];


    $row[]   =   '<a href="'.admin_url().'custom_email_and_sms_notifications/template/edit_template_group/'.$aRow['id'].'" class="btn btn-default btn-icon" >
    <i class="fa fa-pencil-square-o"></i>Edit</a>
    <a href="'.admin_url().'custom_email_and_sms_notifications/template/delete_template_group/'.$aRow['id'].'" class="btn btn-danger _delete btn-icon">
    <i class="fa fa-icon-remove"></i>Remove
    </a>';
    


    $output['aaData'][] = $row;
}
