{* Page Title
<div class="text-block">
    <h1>{#PageTitle#}</h1>
    <p>{#PageDesc#}</p>
</div>
*}



{* Display Form Errors *}
{if isset($errors) && $errors|@count}
<div class="action-message type-error">
    <div class="action-header">
        <div class="action-title">{#FormError#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {foreach from=$errors item="frmErrors" key="formType"}
        {if isset($frmErrors.langCodes) && $frmErrors.langCodes|@count}
        {foreach from=$frmErrors.langCodes item="errcode" key="errid"}
        {* Call the Language Message Display for Error Codes *}
        {display_language_message message=$errcode}<br>
        {/foreach}
        {/if}
        {/foreach}
    </div>
</div>
{/if}

{if $config.ENABLE_TMMID_LOGIN && $TMMid}
{* We are granting access to a TMMid *}
<div class="sellasignupform">
    {display_signup_tmmid}
</div>
{elseif $config.ADVANCED_SIGNUP}
{* We have the advanced signup form turned on *}
<div class="sellasignupform">
    {display_signup_advanced}
</div>
{else}
{* We have the basic signup form turned on *}
<div class="sellasignupform">
    <div class="sellasignupformpart1">
        BASIC
    </div>
    {display_signup_tmmid}
</div>
{/if}