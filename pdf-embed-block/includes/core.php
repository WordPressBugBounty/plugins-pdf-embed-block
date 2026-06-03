<?php

if (!defined('ABSPATH')) exit;

if( !class_exists( 'PEB_PDFEmbed' ) ){
    class PEB_PDFEmbed{
        function __construct(){
            $this -> loaded_classes();
        }
 
        function loaded_classes(){
			require_once PEB_DIR_PATH . 'includes/AdminMenu.php';
			require_once PEB_DIR_PATH . 'includes/CustomColumn.php';
			require_once PEB_DIR_PATH . 'includes/Enqueue.php';
			require_once PEB_DIR_PATH . 'includes/Init.php';
			require_once PEB_DIR_PATH . 'includes/RestAPI.php';
			require_once PEB_DIR_PATH . 'includes/ShortCode.php';

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