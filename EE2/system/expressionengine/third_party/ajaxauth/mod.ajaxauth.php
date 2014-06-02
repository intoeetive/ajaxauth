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


class Ajaxauth {

    var $return_data	= ''; 						// Bah!

    /** ----------------------------------------
    /**  Constructor
    /** ----------------------------------------*/

    function __construct()
    {        
    	$this->EE =& get_instance(); 
    	
        $this->EE->lang->loadfile('member');
        $this->EE->lang->loadfile('login');
        $this->EE->lang->loadfile('ajaxauth');
    }
    /* END */







    
    function login()
    { 

        if ($this->EE->session->userdata['member_id']!=0)
        {
            return $this->EE->TMPL->no_results();
        }
        
        $tagdata = $this->EE->TMPL->swap_var_single('error_container', '<div id="ajaxauth_login_error_container" style="display: none"></div>', $this->EE->TMPL->tagdata); 
        $has_loader = preg_match_all("/".LD."loader".RD."(.*?)".LD."\/loader".RD."/s", $tagdata, $loader);
        if ($has_loader > 0)
        {
            $tagdata = str_replace($loader[0][0], "<div id=\"ajaxauth_login_loader\" class=\"ajaxauth_loader\" style=\"display: none\">".$loader[1][0]."</div>", $tagdata);
        }
        
        $act = $this->EE->db->query("SELECT action_id FROM exp_actions WHERE class='Ajaxauth' AND method='do_login'");
        $data['action'] = ($this->EE->TMPL->fetch_param('secure')=='yes') ? str_replace('http:', 'https:', $this->EE->config->item('site_url')."?ACT=".$act->row('action_id')) : $this->EE->config->item('site_url')."?ACT=".$act->row('action_id');

        $data['hidden_fields']['ACT'] = $this->EE->functions->fetch_action_id('Ajaxauth', 'do_login');       
        if ($this->EE->config->item('secure_forms') == 'y') { 
            $data['secure'] =TRUE; 
        }
        
		$data['name']		= ($this->EE->TMPL->fetch_param('name')!='') ? $this->EE->TMPL->fetch_param('name') : 'ajaxauth_login';
        $data['id']		= ($this->EE->TMPL->fetch_param('id')!='') ? $this->EE->TMPL->fetch_param('id') : 'ajaxauth_login';
        $data['class']		= ($this->EE->TMPL->fetch_param('class')!='') ? $this->EE->TMPL->fetch_param('class') : 'ajaxauth';
        
        $post_process_a = ($this->EE->TMPL->fetch_param('post_process')!='') ? explode("|", $this->EE->TMPL->fetch_param('post_process')) : array();

        $cond = array();
        if ($this->EE->config->item('user_session_type') != 'c')
		{
			$cond['auto_login'] = false;
		}
		else
		{
			$cond['auto_login'] = true;
		}
        $tagdata = $this->EE->functions->prep_conditionals($tagdata, $cond);
        
        $out = $this->EE->functions->form_declaration($data)."\n".
                $tagdata."\n".
                "</form>";
                
        $out .= "<script type=\"text/javascript\">
$(document).ready(function(){
    $('#".$data['id']."').live('submit', function(event){
        event.preventDefault();
        $('#ajaxauth_login_error_container').hide();
        ";
        if ($has_loader > 0)
        {
            $out .= "$('#ajaxauth_login_loader').show();";
        }
        $out .= "
        $.post(
            '".$data['action']."',
            $('#".$data['id']."').serialize(),
            function(msg) {
                ";
        if ($has_loader > 0)
        {
            $out .= "$('#ajaxauth_login_loader').hide();";
        }
        $out .= "
                if (msg.indexOf('".$this->EE->lang->line('mbr_you_are_logged_in')."') >= 0)
                {
                    
                ";
        if ($this->EE->TMPL->fetch_param('return')!='')
        {
            $out .= "
                    $.get('".$this->EE->functions->create_url($this->EE->TMPL->fetch_param('return'))."', 
                    function(ret){
                        $('#".$data['id']."').replaceWith(ret);
                    });";
        }
        else
        {
            $out .= "$('#".$data['id']."').replaceWith(\"".$this->EE->lang->line('logged_in')."\");";
        }
        foreach ($post_process_a as $post_process)
        {
            $out .= "
                    ".$post_process."();
                    ";
        }
        $out .= "
                    
                } else {
                    var out = /<ul>[\s\S]*<\/ul>/.exec(msg);
                    $('#ajaxauth_login_error_container').html(''+out);
                    $('#ajaxauth_login_error_container').show();
                }
                return false;
            }
        );
        return false;
    });
});
</script>";

        return $out;
	}



    function logout()
    { 

        if ($this->EE->session->userdata['member_id']==0)
        {
            return $this->EE->TMPL->no_results();
        }
        
        $tagdata = $this->EE->TMPL->tagdata;
        $has_loader = preg_match_all("/".LD."loader".RD."(.*?)".LD."\/loader".RD."/s", $tagdata, $loader);
        if ($has_loader > 0)
        {
            $tagdata = str_replace($loader[0][0], "<div id=\"ajaxauth_logout_loader\" class=\"ajaxauth_loader\" style=\"display: none\">".$loader[1][0]."</div>", $tagdata);
        }
        
        $act = $this->EE->db->query("SELECT action_id FROM exp_actions WHERE class='Ajaxauth' AND method='do_logout'");
        $data['action'] = ($this->EE->TMPL->fetch_param('secure')=='yes') ? str_replace('http:', 'https:', $this->EE->config->item('site_url')."?ACT=".$act->row('action_id')) : $this->EE->config->item('site_url')."?ACT=".$act->row('action_id');
        if ($this->EE->config->item('app_version')>=280)
        {
            $data['action'] .= '&csrf_token='.CSRF_TOKEN;
        }
        
        preg_match_all("/".LD."link".RD."(.*?)".LD."\/link".RD."/s", $tagdata, $link);
        $tagdata = str_replace($link[0][0], "<a href=\"".$data['action']."\" id=\"ajaxauth_logout_link\">".$link[1][0]."</a>", $tagdata);
        
        $data['id']		= ($this->EE->TMPL->fetch_param('id')!='') ? $this->EE->TMPL->fetch_param('id') : 'ajaxauth_logout';
        $data['class']		= ($this->EE->TMPL->fetch_param('class')!='') ? $this->EE->TMPL->fetch_param('class') : 'ajaxauth';
        
        $post_process_a = ($this->EE->TMPL->fetch_param('post_process')!='') ? explode("|", $this->EE->TMPL->fetch_param('post_process')) : array();
  
        $out = "<div id=\"".$data['id']."\" class=\"".$data['class']."\">".$tagdata."</div>\n";
                
        $out .= "<script type=\"text/javascript\">
$(document).ready(function(){
    $('#ajaxauth_logout_link').live('click', function(event){
        event.preventDefault();
        ";
        if ($has_loader > 0)
        {
            $out .= "$('#ajaxauth_logout_loader').show();";
        }
        $out .= "
        $.get(
            '".$data['action']."',
            function(msg) {
                ";
        if ($has_loader > 0)
        {
            $out .= "$('#ajaxauth_logout_loader').hide();";
        }
        $out .= "
                ";
        if ($this->EE->TMPL->fetch_param('return')!='')
        {
            $out .= "$.get('".$this->EE->functions->create_url($this->EE->TMPL->fetch_param('return'))."', 
                    function(ret){
                        $('#".$data['id']."').replaceWith(ret);
                    });";
        }
        else
        {
            $out .= "$('#".$data['id']."').replaceWith(\"".$this->EE->lang->line('logged_out')."\");";
        }
        foreach ($post_process_a as $post_process)
        {
            $out .= "
                    ".$post_process."();
                    ";
        }
        $out .= "
                
                return false;
            }
        );
        return false;
    });
});
</script>";
        
        return $out;
	}


    
    function do_login()
    { 

        if ( ! class_exists('Member'))
    	{
    		require_once PATH_MOD.'member/mod.member.php';
    	}
        
        if ( ! class_exists('Member_settings'))
    	{
    		require PATH_MOD.'member/mod.member_auth.php';
    	}
    	
    	$MA = new Member_auth();
    	
    	foreach(get_object_vars($this) as $key => $value)
		{
			$MA->{$key} = $value;
		}

    	$MA->member_login();
        
    }
    
    
    function do_logout()
    { 

        if ( ! class_exists('Member'))
    	{
    		require_once PATH_MOD.'member/mod.member.php';
    	}
        
        if ( ! class_exists('Member_settings'))
    	{
    		require PATH_MOD.'member/mod.member_auth.php';
    	}
    	
    	$MA = new Member_auth();
    	
    	foreach(get_object_vars($this) as $key => $value)
		{
			$MA->{$key} = $value;
		}
        
    	$MA->member_logout();

    }
    
    

}
/* END */
?>