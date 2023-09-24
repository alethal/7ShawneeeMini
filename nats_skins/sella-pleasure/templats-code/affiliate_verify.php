<!-- START SUPPORT PAGE -->

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

{* Display Error on Support *}
{if $errors.message.langCodes}
<div class="action-message type-error">
    <div class="action-header">
        <div class="action-title">{#FormError#}</div>
        <a href="#" class="close-action">{#CLOSE#}</a>
    </div>
    <div class="action-details">
        {* Call the Language Message Display for Error Codes *}
        {foreach from=$errors.message.langCodes item="errcode" key="errid"}
        {* Call the Language Message Display for Error Codes *}
        {display_language_message message=$errcode}<br>
        {/foreach}
    </div>
</div>
{/if}


{* floating news box *}
{include file="nats:affiliate_news6"}

<div class="twocolumn AffiliateSupport">
    <div class="c">
        <div class="box-hold">





            <div class="box first-child">
                {* (|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|)(|) hiding all this
                <div class="left-bar-first">
                    <div class="heading">
                        <div class="hold">
                            <h2>{#Navigation#}</h2>
                        </div>
                    </div>
                    <div class="content no-padding disp-stats_form">
                        <ul class="side-bar-nav">
                            <li><a href="internal.php?page=support" {if empty($smarty.request.view) || $smarty.request.view=="request" }class="side-bar-selected" {/if}><span>{#SupportRequest#}</span>{if empty($smarty.request.view) || $smarty.request.view == "request"}<div class="side-sel-arrow"></div>{/if}</a></li>
                            <li><a href="internal.php?page=support&view=help" {if $smarty.request.view=="help" }class="side-bar-selected" {/if}><span>{#HelpFAQ#}</span>{if $smarty.request.view == "help"}<div class="side-sel-arrow"></div>{/if}</a></li>
                            <li><a href="internal.php?page=support&view=NATShelp" {if $smarty.request.view=="NATShelp" }class="side-bar-selected" {/if}><span>NATS {#HelpFAQ#}</span>{if $smarty.request.view == "NATShelp"}<div class="side-sel-arrow"></div>{/if}</a></li>
                            <li><a href="internal.php?page=support&view=resources" {if $smarty.request.view=="resources" }class="side-bar-selected" {/if}><span>{#Resources#}</span>{if $smarty.request.view == "resources"}<div class="side-sel-arrow"></div>{/if}</a></li>
                            <li><a href="internal.php?page=support&view=terms" {if $smarty.request.view=="terms" }class="side-bar-selected" {/if}><span>{#TermsConditions#}</span>{if $smarty.request.view == "terms"}<div class="side-sel-arrow"></div>{/if}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="left-bar-last">
                    <div class="heading">
                        <div class="hold">
                            <h2>{#ContactInfo#}</h2>
                        </div>
                    </div>

                    <div class="content full-padding">
                        <!-- Display the Contact Info Template -->
                        {include file="nats:include_support_contact_info"}
                    </div>
                </div>
            </div>

            (|)(|)(|)(|)(|)(|)(|)(|)(|)| end hiding *}


            <div class="box">
                <div class="heading">
                    <div class="hold">
                        <a href="#" class="open-close"><span>-</span></a>
                        <h2>
                            {if $smarty.request.view == 'help'}{*{#HelpFAQ#}*}ABOUT US
                            {elseif $smarty.request.view == 'NATShelp'}NATS {#HelpFAQ#}
                            {elseif $smarty.request.view == 'resources'}{#Resources#}
                            {elseif $smarty.request.view == 'terms'}{#TermsConditions#}
                            {else}{*{#SupportRequest#}*} CONTACT US{/if}
                        </h2>
                    </div>
                </div>

                <div class="content">
                    <div class="c">
                        <div class="standard-block">
                            <div class="display-content">

                                {if $smarty.request.view == 'help'}
                                {* Display the FAQ for program *}
                                {include file="nats:include_support_program_help"}

                                {elseif $smarty.request.view == 'NATShelp'}
                                {* Display the FAQ/Help for NATS *}
                                {display_nats_help}

                                {elseif $smarty.request.view == 'NATShelp3'}
                                {* Display the FAQ/Help for NATS *}
                                {include file="nats:function_display_nats_help3"}
                                {* display_nats_help3 *}


                                {elseif $smarty.request.view == 'resources'}
                                {* Display the Resources *}
                                {include file="nats:include_support_resource"}

                                {elseif $smarty.request.view == 'terms'}
                                {* Display the Terms and Conditions *}
                                {include file="nats:include_support_terms"}
                                {*NATS-106: Added display of Terms of Service Agreement if not set*}
                                {if !$usr.tos_check && !$usr.tos_time }
                                <form action="submit.php" method="post" name="AccountInfo_Form" id="AccountInfo_Form">
                                    <input type="hidden" name="template" value="affiliate_account">
                                    <input type="hidden" name="submit_function" value="submit_edit_account_details">
                                    <input type="hidden" name="page" value="account">
                                    <input type="hidden" name="location" value="internal">
                                    <input type="hidden" name="view" value="details">
                                    <input type="hidden" name="variable_array[]" value="edit">
                                    {#AgreeToTerms#}:
                                    <input type="checkbox" name="edit[tos_check]" value="1" {if $usr.tos_time> 0}checked{/if} >

                                    <input type="submit" value="{#IAGREE#}" style="background:#CC0000; color:#FFF; font-size: 120%; font-weight: bold; padding: 3px 8px 5px 8px; margin-left: 10px; cursor: pointer;">

                                </form>
                                {/if}

                                {else}
                                {* Display the Support Form *}

                                {* Include any Necessary JavaScript *}
                                {literal}
                                <script>
                                    $(document).ready(function() {

                                        //enable the submit button on change
                                        $(".SupportReq_Edit").change(function() {
                                            if ($("#subject").val() && $("#message").val()) {
                                                $("#Save_SupportReq").removeClass('DisableSubmit');
                                                $("#Save_SupportReq").prop('disabled', false);
                                            } else {
                                                $("#Save_SupportReq").addClass('DisableSubmit');
                                                $("#Save_SupportReq").prop('disabled', true);
                                            }
                                        });
                                        //enable the submit button on keyup
                                        $(".SupportReq_Edit").keyup(function() {
                                            if ($("#subject").val() && $("#message").val()) {
                                                $("#Save_SupportReq").removeClass('DisableSubmit');
                                                $("#Save_SupportReq").prop('disabled', false);
                                            } else {
                                                $("#Save_SupportReq").addClass('DisableSubmit');
                                                $("#Save_SupportReq").prop('disabled', true);
                                            }
                                        });

                                    });
                                </script>
                                {/literal}

                                {* Display Confirmation for Submition *}
                                {if isset($smarty.get.cache) && isset($smarty.get.action) && $smarty.get.action == 'submit_affiliate_message'}
                                <div class="action-message type-success">
                                    <div class="action-header">
                                        <div class="action-title">{#RequestSent#}</div>
                                        <a href="#" class="close-action">{#CLOSE#}</a>
                                    </div>
                                    <div class="action-details">
                                        {#RequestThankYou#}
                                        {$config.NICE_NAME}
                                    </div>
                                </div>
                                {/if}

                                {* Display Header *}
                                <div class="section_header">
                                    {#SupportRequest#}
                                </div>
                                <form action="submit.php" method="post" name="SupportReq_Form" id="SupportReq_Form">
                                    <input type="hidden" name="template" value="affiliate_support">
                                    <input type="hidden" name="submit_function" value="submit_affiliate_message">
                                    <input type="hidden" name="page" value="support">
                                    <input type="hidden" name="location" value="internal">
                                    <input type="hidden" name="view" value="request">
                                    <input type="hidden" name="variable_array[]" value="message">
                                    <table class="table-container2 form-table" cellpadding=0 cellspacing=0>
                                        <tr class="data-row-even">
                                            <td class="tab-column"><b>{#Subject#}:</b></td>
                                            <td class="tab-column left-align">
                                                <input type="text" name="message[subject:1:2:64]" value="{$vars.message.subject}" id="subject" class="edit-form-text SupportReq_Edit">
                                            </td>
                                        </tr>
                                        <tr class="data-row-even">
                                            <td class="tab-column"><b>{#Topic#}:</b></td>
                                            <td class="tab-column left-align">
                                                <select name="message[topic]" class="edit-form-select SupportReq_Edit" id="topic">
                                                    {foreach from=$affiliate_support_topics item="opt" key="oid"}
                                                    <option value="{$oid}" {if $oid==$vars.message.topic} selected{/if}>{$opt|convlang}</option>
                                                    {/foreach}
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="data-row-even-last">
                                            <td class="tab-column"><b>{#Message#}:</b></td>
                                            <td class="tab-column left-align">
                                                <textarea class="edit-form-textarea-short SupportReq_Edit" id="message" name="message[question:1:2]">{$vars.message.question}</textarea>
                                            </td>
                                        </tr>
                                        <tr class="footer-row">
                                            <td colspan="4" class="tab-column">
                                                <div class="tools">
                                                    <input type=submit class="button DisableSubmit" id="Save_SupportReq" disabled="1" value="{#SENDREQUEST#}">
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                                {/if}


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b"></div>
</div>

<!-- END SUPPORT PAGE -->