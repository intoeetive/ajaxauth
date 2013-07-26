<?php

/*
=====================================================
 AJAX Auth
-----------------------------------------------------
 http://www.intoeetive.com/
-----------------------------------------------------
 Copyright (c) 2011 Yuriy Salimovskiy
=====================================================
 This software is based upon and derived from
 ExpressionEngine software protected under
 copyright dated 2004 - 2011. Please see
 http://expressionengine.com/docs/license.html
=====================================================
 File: upd.ajaxauth.php
-----------------------------------------------------
 Purpose: Enables AJAX login and logout (without refreshing the page)
=====================================================
*/

if ( ! defined('BASEPATH'))
{
    exit('Invalid file request');
}



class Ajaxauth_upd {

    var $version = '1.0.4';
    
    function __construct() { 
        // Make a local reference to the ExpressionEngine super object 
        $this->EE =& get_instance(); 
    } 
    
    function install() { 
        
        $this->EE->load->dbforge(); 

        $data = array( 'module_name' => 'Ajaxauth' , 'module_version' => $this->version, 'has_cp_backend' => 'n' ); 
        $this->EE->db->insert('modules', $data); 
        
        $data = array( 'class' => 'Ajaxauth' , 'method' => 'do_login' ); 
        $this->EE->db->insert('actions', $data); 
        
        $data = array( 'class' => 'Ajaxauth' , 'method' => 'do_logout' ); 
        $this->EE->db->insert('actions', $data); 
        
        return TRUE; 
        
    } 
    
    function uninstall() { 
        
        $this->EE->load->dbforge(); 
        
        $this->EE->db->select('module_id'); 
        $query = $this->EE->db->get_where('modules', array('module_name' => 'Ajaxauth')); 
        
        $this->EE->db->where('module_id', $query->row('module_id')); 
        $this->EE->db->delete('module_member_groups'); 
        
        $this->EE->db->where('module_name', 'Ajaxauth'); 
        $this->EE->db->delete('modules'); 
        
        $this->EE->db->where('class', 'Ajaxauth'); 
        $this->EE->db->delete('actions'); 
        
        return TRUE; 
    } 
    
    function update($current='') { 
        if ($current < 2.0) { 
            // Do your 2.0 version update queries 
        } if ($current < 3.0) { 
            // Do your 3.0 v. update queries 
        } 
        return TRUE; 
    } 
	

}
/* END */
?>