<?php
/**
 * Plugin Name: Repeater Fields for WPForms
 * Plugin URI: https://add-ons.org/plugin/wpforms-repeater-fields/
 * Description: The repeater field allows you to create one or more sets of fields which can be repeated.
 * Version: 1.2.1
 * Author: add-ons.org
 * Author URI: https://add-ons.org/
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
if ( ! defined( 'ABSPATH' ) ) exit;
define('WPFORMS_REPEATER_FIELD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('WPFORMS_REPEATER_FIELD_PATH', plugin_dir_path( __FILE__ ) );
class Superaddons_WPForms_Repeater_Fields { 
    function __construct(){ 
        add_action( 'wpforms_loaded', array($this,'loads') );
    }
    function loads(){
        include WPFORMS_REPEATER_FIELD_PATH."repeater-field.php";
        include WPFORMS_REPEATER_FIELD_PATH."repeater-start-field.php";
        include WPFORMS_REPEATER_FIELD_PATH."superaddons/check_purchase_code.php";
        new Superaddons_Check_Purchase_Code( 
            array("plugin" => "repeater-field-for-wpforms/index.php",
                "id"=>"46490403",
                "pro"=>"https://add-ons.org/plugin/wpforms-repeater-fields/",
                "plugin_name"=> "Repeater Fields for WPForms",
                "document"=> "https://add-ons.org/document-wpfoms-repeater-fields-2/",
            )
        );
    }
}
new  Superaddons_WPForms_Repeater_Fields;
if(!class_exists('Superaddons_List_Addons')) {  
    include WPFORMS_REPEATER_FIELD_PATH."add-ons.php"; 
}