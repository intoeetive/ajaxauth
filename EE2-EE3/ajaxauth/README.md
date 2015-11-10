# AJAX Auth

AJAX Auth module enables asynchronous (without refreshing the page) login and logout for ExpressionEngine 2.
    
## Requirements

AJAX Auth relies on some files provided by Member module, so it will not work on ExpressionEngine Core.

Do not forget to include jQuery on all pages that contain ajax login &amp; logout functional.

```<script type='text/javascript' src='http://code.jquery.com/jquery-latest.min.js'></script>```


## Usage

### `{exp:ajaxauth:login}`

This tag pair will display AJAX login form. Inside can be placed any fields available for [Member login form](https://ellislab.com/expressionengine/user-guide/member/index.html#login-form-tag). Username and password are mandatory, of course.

#### Example Usage

```
{if logged_out}
{exp:ajaxauth:login name="login_form" class="login_form" id="login_form" return="_includes/log-in-out"}
{error_container}
{loader}Loading...{/loader}
<p><label>Username</label>
<input type="text" name="username" value="" maxlength="32" class="input" size="25" /></p>
<p><label>Password</label>
<input type="password" name="password" value="" maxlength="32" class="input" size="25" /></p>
{if auto_login}
<p><input class='checkbox' type='checkbox' name='auto_login' value='1' /> Auto-login on future visits</p>
{/if}
<p><input class='checkbox' type='checkbox' name='anon' value='1' checked='checked' /> Show my name in the online users list</p>
<p><input type="submit" name="submit" value="Submit" /></p>
<p><a href="{path='member/forgot_password'}">Forgot your password?</a></p>
{/exp:ajaxauth:login}
{/if}
```

#### Parameters

- `secure="yes"` &mdash; optional parameter that will make the form send data over secure connection
- `name` &mdash; form name. Defaults to 'ajaxauth_login'
- `id` &mdash; form ID. Defaults to 'ajaxauth_login'
- `class` &mdash; form class. Defaults to 'ajaxauth'
- `return` &mdash; template which will be used to replace the form after successful login. If omited, the form will be replaced with text found in language/english/lang.ajaxauth.php
- `post_process` &mdash; JavaScript function(s) that will be executed after successful login. Separate multiple functions with pipe, ex. post_process="function1|function2|function3"

#### Variables

- `{error_container}` &mdash; will create a container that will display error message (if any). Place it whereever you want error messages to appear (it is hidden initially)</li>
- `{loader}Please wait...{/loader}` &mdash; will display 'loading' message when form is submitted. Replace the contents with appropriate image and/or text


### `{exp:ajaxauth:logout}`

This tag pair will display the block with AJAX logout link.

#### Example Usage

```
{if logged_in}
{exp:ajaxauth:logout class="logout_form" id="logout_form" return="_includes/log-in-out" post_process="al"}
{loader}Loading...{/loader}
{link}Log me out!{/link}
{/exp:ajaxauth:logout}
<script type="text/javascript">
function al(){
alert('You are logged out!');
}
</script>
{/if}
```

#### Parameters

- `secure="yes"` &mdash; optional parameter that will send the data over secure connection
- `id` &mdash; container block ID. Defaults to 'ajaxauth_logout'
- `class` &mdash; container block  class. Defaults to 'ajaxauth'
- `return` &mdash; template which will be used to replace the container block after successful logout. If omited, the container block will be replaced with text found in language/english/lang.ajaxauth.php
- `post_process` &mdash; JavaScript function(s) that will be executed after successful logout. Separate multiple functions with pipe, ex. post_process="function1|function2|function3"

#### Variables

- `{link}Log out{/link}` &mdash; contains the actual clickable text/image that will be used as logout link. Mandatory.
- `{loader}Please wait...{/loader}` &mdash; will display 'loading' message when form is submitted. Replace the contents with appropriate image and/or text

## Styling guide

Following CSS classes and IDs are available:

- `.ajaxauth` &mdash; login form/outer container of logout block
- `#ajaxauth_login` (or ID defined by id parameter of login tag) &mdash; login form
- `#ajaxauth_logout` (or ID defined by id parameter of logout tag) &mdash; outer container of logout block
- `.ajaxauth_loader` &mdash; block that displays 'loading' message during login/logout
- `#ajaxauth_login_loader` &mdash; block that displays 'loading' message during login
- `#ajaxauth_logout_loader` &mdash; block that displays 'loading' message during logout
- `#ajaxauth_login_error_container` &mdash; block that will display login error messages. NOTE: login error message will be formatted like this: <ul><li>message</li></ul>
- `#ajaxauth_logout_link` &mdash; <a> tag that will contain clickable logout text/image



## Changelog

### 1.1.0

- ExpressionEngine 3.0 support
- Support for work with Custom System Messages

### 1.0.6

- EE 2.10 compatibility

### 1.0.5

- EE 2.8 compatibility release

### 1.0.4

- Support for auto_login conditional

### 1.0.3
- Logout bug fix

### 1.0.2
- Minor bug fix

### 1.0.0
- Initial release

## License

The purchase of the add-on grants you to use it on single production installation of ExpressionEngine. Should you be willing to use it on several production websites, you should purchase additional licenses. The full license agreement is available [here](http://www.intoeetive.com/docs/license.html)

## Support

Should you have any questions, please email [support@intoeetive.com](mailto:support@intoeetive.com) (for official support) or ask questions on [EE StackOverflow](http://expressionengine.stackexchange.com/) (for community support)
