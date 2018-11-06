<?php

require_once 'AutoSignature.php';
$as = new AutoSignature();

$action = strip_tags($_POST['action']);

switch($action)
{
    case 'ajax_get_templates';
        $as->ajax_get_templates();
    break;

    case 'ajax_get_template_tags';
        $template = strip_tags($_POST['template']);
        $as->ajax_get_template_tags($template);
    break;
    case 'ajax_preview_signature';
        $template = strip_tags($_POST['template']);
        $form = $_REQUEST['form'];
        $as->ajax_preview_signature($template, $form);
    break;


    case 'ajax_create_signature';
        $template = strip_tags($_POST['template']);
        $as->ajax_create_signature($template);
    break;
}

?>
