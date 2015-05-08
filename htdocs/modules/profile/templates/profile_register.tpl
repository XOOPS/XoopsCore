<{$profile_breadcrumbs}>

<{if $steps|@count > 1 AND $current_step >= 0}>
    <div class='register-steps'>
        <span class='caption'><{$lang_register_steps}></span>
        <{foreach item=step from=$steps key=stepno name=steploop}>
            <{if $stepno == $current_step}>
                <span class='item current'><{$step.step_name}></span>
            <{else}>
                <span class='item'><{$step.step_name}></span>
            <{/if}>
            <{if !$smarty.foreach.steploop.last}>
                <span class='delimiter'>&raquo;</span>
            <{/if}>
        <{/foreach}>
    </div>
<{/if}>

<{if $stop}>
    <div class='errorMsg txtleft'><{$stop}></div>
    <br class='clear'/>
<{/if}>

<{if $confirm}>
    <{foreach item=msg from=$confirm}>
        <div class='confirmMsg txtleft'><{$msg}></div>
        <br class='clear'/>
    <{/foreach}>
<{/if}>

<{if $regform}>
    <h3><{$regform.title}></h3>
    <{include file="module:profile/profile_form.tpl" xoForm=$regform}>
<{elseif $finish}>
    <h1><{$finish}></h1>
    <{if $finish_message}><p><{$finish_message}></p><{/if}>
    <{if $finish_login}>
    <form id='register_login' name='register_login' action='user.php' method='post'>
    <input type='submit' value="<{$finish_login}>">
    <input type='hidden' name="op" id="op" value="login">
    <input type='hidden' name="uname" id="uname" value="<{$finish_uname}>">
    <input type='hidden' name="pass" id="pass" value="<{$finish_pass}>">
    </form>
    <{/if}>
<{/if}>