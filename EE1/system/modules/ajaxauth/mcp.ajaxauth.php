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



class Ajaxauth_CP {

    var $version = '1.0.4';
    
    function Ajaxauth_CP() {
        //
    }
    
    function Ajaxauth_module_install() { 
        
        global $DB, $PREFS;        
        
        $sql[] = "INSERT INTO ".$PREFS->ini('db_prefix')."_modules 
        		  (module_id, module_name, module_version, has_cp_backend) 
        		  VALUES 
        		  ('', 'Ajaxauth', '$this->version', 'n')";

        $sql[] = "INSERT INTO `".$PREFS->ini('db_prefix')."_actions` (action_id, class, method) VALUES ('', 'Ajaxauth', 'do_login')";
        
        $sql[] = "INSERT INTO `".$PREFS->ini('db_prefix')."_actions` (action_id, class, method) VALUES ('', 'Ajaxauth', 'do_logout')";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
        
    } 
    
    function Ajaxauth_module_deinstall() { 
        
        global $DB, $PREFS;    

        $query = $DB->query("SELECT module_id FROM ".$PREFS->ini('db_prefix')."_modules WHERE module_name = 'Ajaxauth'"); 
                
        $sql[] = "DELETE FROM ".$PREFS->ini('db_prefix')."_module_member_groups WHERE module_id = '".$query->row['module_id']."'";        
        $sql[] = "DELETE FROM ".$PREFS->ini('db_prefix')."_modules WHERE module_name = 'Ajaxauth'";
        $sql[] = "DELETE FROM ".$PREFS->ini('db_prefix')."_actions WHERE class = 'Ajaxauth'";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;

    } 

}
/* END */
?>