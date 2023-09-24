{*
11383 - Hide username if not required for password resets
*}

{* Include JS necessary for this page *}
{literal}
<script>
    $(document).ready(function() {
                //Function to check if the password has a value and enable/disable the submit button
                function check_password_field_populated() {
                    if ({
                            /literal}{if !$config.AFFILIATE_FORGOT_PASSWORD_NO_USERNAME_VERIFICATION_ON_RESET}{literal}$("#verify_username").val() && {/literal
                        } {
                            /if}{literal}$("#verify_email").val()){
                            $("#Save_ResetInfo").removeClass('DisableSubmit');
                            $("#Save_ResetInfo").prop('disabled', false);
                        } else {
                            $("#Save_ResetInfo").addClass('DisableSubmit');
                            $("#Save_ResetInfo").prop('disabled', true);
                        }
                    }

                    //enable the submit button on change
                    $(".ResetInfo_Edit").change(function() {
                        check_password_field_populated();
                    });

                    //enable the submit button on keyup
                    $(".ResetInfo_Edit").keyup(function() {
                        check_password_field_populated();
                    });
                });
</script>
{/literal}


{* Page Title *}
<!--div class="text-block">
	<h1>{#PageTitle#}</h1>
	<p>{#PageDesc#}</p>
</div-->

{if isset($smarty.request.cache) && $smarty.request.action == 'submit_forgot_password'}
<div class="action-message type-success">
    <div class="action-header">
        <div class="action-title">{#EmailSent#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {#EmailPasswordRecoverySent#}
    </div>
</div>
{/if}

{* Display Form Errors *}
{if isset($errors) && $errors|@count}
{foreach from=$errors item="frmErrors" key="formType"}
{if isset($frmErrors.langCodes) && $frmErrors.langCodes|@count}
<div class="action-message type-error">
    <div class="action-header">
        <div class="action-title">{#FormError#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {foreach from=$frmErrors.langCodes item="errcode" key="errid"}
        {* Call the Language Message Display for Error Codes *}
        {display_language_message message=$errcode}<br>
        {/foreach}
    </div>
</div>
{/if}
{/foreach}
{/if}


<div class="mainblock smallblock">
    <div class="heading">
        <div class="hold">
            <span style="text-transform: none; font-weight: 500; text-align: center; display: inline-block; padding: 2px 5px;">Please enter you influencer username and email. A temporary password will be sent to that email.</span>
        </div>
    </div>
    <div class="content" style="margin: 0 auto; max-width: 280px;">
        <div class="c">
            <div class="standard-block">

                <div class="inner-clear-separator"></div>

                {* Begin the Edit Form *}
                <form action="submit.php" method="post" name="ResetInfo_Form" id="ResetInfo_Form">
                    <input type="hidden" name="template" value="external_password">
                    <input type="hidden" name="submit_function" value="submit_forgot_password">
                    <input type="hidden" name="page" value="password">
                    <input type="hidden" name="location" value="external">
                    <input type="hidden" name="variable_array[]" value="verify">

                    {* Begin the Form Table *}
                    <table class="table-container form-table" cellpadding=0 cellspacing=0>
                        {if empty($config.AFFILIATE_FORGOT_PASSWORD_NO_USERNAME_VERIFICATION_ON_RESET)}
                        <tr class="data-row-even form-ro">
                            <td class="tab-column"><b>{#Username#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="verify[username:1:1:128]" value="{$vars.verify.username}" id="verify_username" class="edit-form-text{if isset($errors.verify.username)} edit-form-error{/if} ResetInfo_Edit">
                            </td>
                        </tr>
                        {/if}
                        <tr class="data-row-odd form-ro">
                            <td class="tab-column"><b>{#Email#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="verify[email:1:1:255:::email_check]" value="{$vars.verify.email}" id="verify_email" class="edit-form-text{if isset($errors.verify.email)} edit-form-error{/if} ResetInfo_Edit">
                            </td>
                        </tr>

                        {* Footer with Submit and Cancel Options *}
                        <tr class="footer-row no-foot-line">
                            <td colspan="2" class="tab-column">
                                <div class="tools">
                                    <!--input type="submit" class="button DisableSubmit" id="Save_ResetInfo" value="{#RESETPASSWORD#}" disabled="1"-->
                                    <input type="submit" class="button DisableSubmit" id="Save_ResetInfo" value="Send Temp Password" disabled="1">
                                </div>
                            </td>
                        </tr>

                        {* End Account Table *}
                    </table>

                    {* End Edit Form *}
                </form>

            </div>
        </div>
    </div>
</div>