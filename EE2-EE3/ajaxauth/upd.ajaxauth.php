<?php

/*
=====================================================
 AJAX Auth
-----------------------------------------------------
 http://www.intoeetive.com/
-----------------------------------------------------
 Copyright (c) 2011-2016 Yuri Salimovskiy
=====================================================
*/

if ( ! defined('BASEPATH'))
{
    exit('Invalid file request');
}

require_once PATH_THIRD.'ajaxauth/config.php';

class Ajaxauth_upd {

    var $version = AJAXAUTH_ADDON_VERSION;
    
    function __construct() { 

    } 
    
    function install() { 
        
        ee()->load->dbforge(); 

        $data = array( 'module_name' => 'Ajaxauth' , 'module_version' => $this->version, 'has_cp_backend' => 'n' ); 
        ee()->db->insert('modules', $data); 
        
        $data = array( 'class' => 'Ajaxauth' , 'method' => 'do_login' ); 
        ee()->db->insert('actions', $data); 
        
        $data = array( 'class' => 'Ajaxauth' , 'method' => 'do_logout' ); 
        ee()->db->insert('actions', $data); 
        
        return TRUE; 
        
    } 
    
    function uninstall() { 
        
        ee()->load->dbforge(); 
        
        ee()->db->select('module_id'); 
        $query = ee()->db->get_where('modules', array('module_name' => 'Ajaxauth')); 
        
        ee()->db->where('module_id', $query->row('module_id')); 
        ee()->db->delete(version_compare(APP_VER, '6.0', '>=') ? 'module_member_roles' : 'module_member_groups');
        
        ee()->db->where('module_name', 'Ajaxauth'); 
        ee()->db->delete('modules'); 
        
        ee()->db->where('class', 'Ajaxauth'); 
        ee()->db->delete('actions'); 
        
        return TRUE; 
    } 
    
    function update($current='') { 

        return TRUE; 
    } 
	

}
/* END */
?>
