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


class Ajaxauth {

    var $return_data	= ''; 						// Bah!

    /** ----------------------------------------
    /**  Constructor
    /** ----------------------------------------*/

    function __construct()
    {        
    	
        ee()->lang->loadfile('member');
        ee()->lang->loadfile('login');
        ee()->lang->loadfile('ajaxauth');
    }
    /* END */







    
    function login()
    { 

        if (ee()->session->userdata['member_id']!=0)
        {
            return ee()->TMPL->no_results();
        }
        
        $tagdata = ee()->TMPL->swap_var_single('error_container', '<div id="ajaxauth_login_error_container" style="display: none"></div>', ee()->TMPL->tagdata); 
        $has_loader = preg_match_all("/".LD."loader".RD."(.*?)".LD."\/loader".RD."/s", $tagdata, $loader);
        if ($has_loader > 0)
        {
            $tagdata = str_replace($loader[0][0], "<div id=\"ajaxauth_login_loader\" class=\"ajaxauth_loader\" style=\"display: none\">".$loader[1][0]."</div>", $tagdata);
        }
        
        $act = ee()->db->query("SELECT action_id FROM exp_actions WHERE class='Ajaxauth' AND method='do_login'");
        $data['action'] = (ee()->TMPL->fetch_param('secure')=='yes') ? str_replace('http:', 'https:', ee()->config->item('site_url')."?ACT=".$act->row('action_id')) : ee()->config->item('site_url')."?ACT=".$act->row('action_id');

        $data['hidden_fields']['ACT'] = ee()->functions->fetch_action_id('Ajaxauth', 'do_login');       
        if (ee()->config->item('secure_forms') == 'y') { 
            $data['secure'] =TRUE; 
        }
        
		$data['name']		= (ee()->TMPL->fetch_param('name')!='') ? ee()->TMPL->fetch_param('name') : 'ajaxauth_login';
        $data['id']		= (ee()->TMPL->fetch_param('id')!='') ? ee()->TMPL->fetch_param('id') : 'ajaxauth_login';
        $data['class']		= (ee()->TMPL->fetch_param('class')!='') ? ee()->TMPL->fetch_param('class') : 'ajaxauth';
        
        $post_process_a = (ee()->TMPL->fetch_param('post_process')!='') ? explode("|", ee()->TMPL->fetch_param('post_process')) : array();

        $cond = array();
        if (ee()->config->item('user_session_type') != 'c')
		{
			$cond['auto_login'] = false;
		}
		else
		{
			$cond['auto_login'] = true;
		}
        $tagdata = ee()->functions->prep_conditionals($tagdata, $cond);
        
        $out = ee()->functions->form_declaration($data)."\n".
                $tagdata."\n".
                "</form>";
                
        $out .= "<script type=\"text/javascript\">
$(document).ready(function(){
    $('#".$data['id']."').on('submit', function(event){
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

                var logged_in = false;
                var is_json = false;
                if (typeof(msg['success'])!='undefined')
                {
                    is_json = true;
                    if (msg['success']==true)
                    {
                        logged_in = true;
                    }
                }
                else if (msg.indexOf('".ee()->lang->line('mbr_you_are_logged_in')."') >= 0)
                {
                    logged_in  = true;
                }
                if (logged_in==true)
                {
                ";
        if (ee()->TMPL->fetch_param('return')!='')
        {
            $out .= "
                    $.get('".ee()->functions->create_url(ee()->TMPL->fetch_param('return'))."', 
                    function(ret){
                        $('#".$data['id']."').replaceWith(ret);
                    });";
        }
        else
        {
            $out .= "$('#".$data['id']."').replaceWith(\"".ee()->lang->line('logged_in')."\");";
        }
        foreach ($post_process_a as $post_process)
        {
            $out .= "
                    ".$post_process."();
                    ";
        }
        $out .= "
                    
                } else {
                    if (is_json==true)
                    {
                        var out = msg['content'];
                    } else 
                    {
                        var out = /<ul>[\s\S]*<\/ul>/.exec(msg);
                    }
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

        if (ee()->session->userdata['member_id']==0)
        {
            return ee()->TMPL->no_results();
        }
        
        $tagdata = ee()->TMPL->tagdata;
        $has_loader = preg_match_all("/".LD."loader".RD."(.*?)".LD."\/loader".RD."/s", $tagdata, $loader);
        if ($has_loader > 0)
        {
            $tagdata = str_replace($loader[0][0], "<div id=\"ajaxauth_logout_loader\" class=\"ajaxauth_loader\" style=\"display: none\">".$loader[1][0]."</div>", $tagdata);
        }
        
        $act = ee()->db->query("SELECT action_id FROM exp_actions WHERE class='Ajaxauth' AND method='do_logout'");
        $data['action'] = (ee()->TMPL->fetch_param('secure')=='yes') ? str_replace('http:', 'https:', ee()->config->item('site_url')."?ACT=".$act->row('action_id')) : ee()->config->item('site_url')."?ACT=".$act->row('action_id');
        if (version_compare(APP_VER, '2.8.0', '>='))
        {
            $data['action'] .= '&csrf_token='.CSRF_TOKEN;
        }
        
        preg_match_all("/".LD."link".RD."(.*?)".LD."\/link".RD."/s", $tagdata, $link);
        $tagdata = str_replace($link[0][0], "<a href=\"".$data['action']."\" id=\"ajaxauth_logout_link\">".$link[1][0]."</a>", $tagdata);
        
        $data['id']		= (ee()->TMPL->fetch_param('id')!='') ? ee()->TMPL->fetch_param('id') : 'ajaxauth_logout';
        $data['class']		= (ee()->TMPL->fetch_param('class')!='') ? ee()->TMPL->fetch_param('class') : 'ajaxauth';
        
        $post_process_a = (ee()->TMPL->fetch_param('post_process')!='') ? explode("|", ee()->TMPL->fetch_param('post_process')) : array();
  
        $out = "<div id=\"".$data['id']."\" class=\"".$data['class']."\">".$tagdata."</div>\n";
                
        $out .= "<script type=\"text/javascript\">
$(document).ready(function(){
    $('#ajaxauth_logout_link').on('click', function(event){
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
        if (ee()->TMPL->fetch_param('return')!='')
        {
            $out .= "$.get('".ee()->functions->create_url(ee()->TMPL->fetch_param('return'))."', 
                    function(ret){
                        $('#".$data['id']."').replaceWith(ret);
                    });";
        }
        else
        {
            $out .= "$('#".$data['id']."').replaceWith(\"".ee()->lang->line('logged_out')."\");";
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