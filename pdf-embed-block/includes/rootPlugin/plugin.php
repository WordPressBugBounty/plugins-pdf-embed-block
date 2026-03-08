<?php

if (!defined('ABSPATH')) exit;

if( !class_exists( 'PEB_PDFEmbed' ) ){
    class PEB_PDFEmbed{
        function __construct(){
            $this -> loaded_classes();
        }
 
        function loaded_classes(){
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/Init.php';
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/Enqueue.php';
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/AdminMenu.php';
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/ShortCode.php';
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/CustomColumn.php';
			require_once PEB_DIR_PATH . 'includes/rootPlugin/inc/RestAPI.php';

			new PEB\Init();
			new PEB\Enqueue();
			new PEB\AdminMenu();
			new PEB\ShortCode();
			new PEB\CustomColumn();
			new PEB\RestAPI();
		}
        
    }
    new PEB_PDFEmbed();
}