<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['template_name'];

$sIndexColumn = 'id';
$sTable       = db_prefix().'custom_templates';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        $_data = '<a href="#" data-toggle="modal" data-target="#add_edit_template" data-id="' . $aRow['id'] . '">' . $aRow[$aColumns[$i]] . '</a>';

        $row[] = $_data;
    }
    $row[]   =   '<a href="#" class="btn btn-default btn-icon" data-toggle="modal" data-target="#add_edit_template" data-id="'.$aRow['id'].'">
    <i class="fa fa-pencil-square-o"></i>Edit</a>
    <a href="'.admin_url().'custom_email_and_sms_notifications/template/delete/'.$aRow['id'].'" class="btn btn-danger _delete btn-icon">
    <i class="fa fa-icon-remove"></i>Remove
    </a>';
    


    $output['aaData'][] = $row;
}
