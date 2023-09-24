{*
11383 - Added success message when resetting password

12307 - Changed if test to display errors for codes 10 and 11

NATS-868 - added error code 12

*}

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}</h1>
    {* <p>{#PageDesc#}</p> *}
</div>

{if isset($smarty.request.cache) && $smarty.request.action == 'submit_reset_password'}
<div class="action-message type-success">
    <div class="action-header">
        <div class="action-title">{#PasswordReset#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {#PleaseLogIn#}
    </div>
</div>
{/if}

{* Grab our Access Error Code *}
{assign var=code value=$smarty.request.code|default:0}
{if $code && $code >= 1 && $code <= 11} <div class="action-message type-notice">
    <div class="action-header">
        <div class="action-title">{#AccessDenied#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {if ($code == 1) || ($code == 2) || ($code == 3)}{#InvalidPassword#}
        {elseif $code == 4}{#MissingUsernamePassword#}
        {elseif $code == 5}{#InvalidVerificationString#}
        {elseif $code == 6}{#AccessDisabledFromNetwork#}
        {elseif $code == 7}{#NotSignedUpNetwork#}
        {elseif $code == 8}{if $smarty.request.reason}{$smarty.request.reason}{else}{#YourAccountDisabled#}{/if}
        {elseif $code == 9}{#YourSessionExpired#}
        {elseif $code == 10}{#EmailVerificationRequired#}
        {elseif $code == 11}{#ManaulActivationRequired#}
        {elseif $code == 12}{#MultiFactorRequired#}
        {/if}
        <br>{#CookiesRequired#}
    </div>
    </div>
    {/if}

    {if 0}
    <div class="mainblock">

        <div class="external-wrap-center">
            {* Display Login Form *}
            {include file="nats:include_affiliate_login.tpl"}
        </div>

    </div>
    {/if}