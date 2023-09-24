{*
7347 - Included Signup_done text
*}
{literal}
<style type="text/css">
    .signup_done_login .external-box {
        float: none;
        margin-top: 50px
    }

    .signup_done_text {
        font-size: 1.2em;
    }
</style>
{/literal}
{* Page Title *}

<div class="text-block">
    <h1>{#PageTitle#}</h1>

    <p class="signup_done_text">
        {#SignupCompleteThankYouForJoining#}{$config.NICE_NAME}<br><br>
        {if isset($config.RESELLER_VERIFY)}
        {#SignupCompleteVerification#}
        {elseif isset($config.RESELLER_MANUAL_ACTIVATION)}
        {#SignupCompleteActivation#}
        {else}
        {#SignupCompleteMessage#}
        {/if}</p>
</div>

{if 0}

<div class="mainblock">

    {* Display Login Form *}
    <div class="signup_done_login">
        {include file="nats:include_affiliate_login.tpl"}
    </div>
</div>

{/if}