{*
6841 - Utilize CLOSED_AFFILIATE_SIGNUP config variable
7112 - Added "All Programs" feature
7509 - Changed how payvia details are retrieved
7347 - Changed 'page' location to signup_done
7210 - Added CAPTCHA Image code
8853 - Added support for custom affiliate contacts
NATS-106 - Added support for Terms of Service Agreement
*}
{* Allow the PayVia options to change with the Method *}
{literal}
<script>
    var curPayVia = "{/literal}{$vars.signup.payvia|default:1}{literal}";
    var curMin = "{/literal}{$vars.signup.minimum_payout}{literal}";

    function update_payvia_fields() {

        curMin = $("#minimum_payout").val();
        curPayVia = $("#payvia_type_id").val();

        //remove the old option
        $('.PayViaOptions').remove();
        $("#payvia_start_row").after('<tr class="data-row-odd PayViaOptions"><td class="tab-column left-align" colspan="2"><b>{/literal}{#LoadingPaymentOptionFields#}{literal}...</b></td></tr>');

        //grab the details for this payvia method
        $.get('/ajax_data.php?function=ajax_payvia_method_details&payvia=' + curPayVia, function(data) {

            if (data == 'NONE') {
                //hide the sub options
                $('#minimum_payout').html('<option value="1000">{/literal}{#Unavailable#}{literal}</option>');
                $('.PayViaOptions').remove();
                $("#payvia_start_row").after('<tr class="data-row-odd PayViaOptions"><td class="tab-column left-align" colspan="2"><b>{/literal}{#NoFieldsAvailable#}{literal}</b></td></tr>');
            } else {
                var availInfo = jQuery.parseJSON(data);

                //set the minimum_payout options
                var optOrder = '';
                $.each(availInfo.myMins, function(fieldid, field) {
                    if (fieldid == curMin) optOrder += '<option value="' + fieldid + '" selected>' + field + '</option>';
                    else optOrder += '<option value="' + fieldid + '">' + field + '</option>';
                });
                $('#minimum_payout').html(optOrder);

                //set the detail fields in the form
                var newFields = '<tr class="data-row-odd PayViaOptions">';
                var fCount = 0;
                $.each(availInfo.myForm, function(index, formField) {
                    fCount++;
                    if ((fCount + 1) % 2 == 0) {
                        if ((fCount + 1) % 4 == 0) {
                            newFields += '</tr><tr class="data-row-odd PayViaOptions">';
                        } else {
                            newFields += '</tr><tr class="data-row-even PayViaOptions">';
                        }
                    }
                    newFields += '<td class="tab-column"><b>' + formField.lang_name + ':</b></td><td class="tab-column left-align">';
                    if (formField.type == 1) {
                        if (formField.options) {
                            var selOpts = '';
                            var selValue = '';
                            $.each(formField.options, function(fieldid, field) {
                                if (fieldid == formField.value) {
                                    selOpts += '<option value="' + fieldid + '" selected>' + field + '</option>';
                                    selValue = field;
                                } else selOpts += '<option value="' + fieldid + '">' + field + '</option>';
                            });
                            newFields += '<select name="payvia[' + formField.payvia_field_id + ':' + formField.required + ':::::' + formField.check_function + '::' + formField.name + ']" id="pvid_' + formField.payvia_field_id + '" class="edit-form-select PayViaInfo_Edit">' + selOpts + '</select>';
                        }
                    } else if (formField.type == 2) {
                        newFields += '<input type="file" name="payvia[' + formField.payvia_field_id + ']" id="pvid_' + formField.payvia_field_id + '" class="edit-form-text PayViaInfo_Edit">';
                    } else {
                        var pvName = formField.name.toLowerCase().replace(" ", "");
                        if (formField.value == '') {
                            if (pvName == 'address') var pvVal = $("#address1").val();
                            else if (pvName == 'zipcode') var pvVal = $("#zip_code").val();
                            else if ($("#" + pvName).val()) var pvVal = $("#" + pvName).val();
                            else var pvVal = '';
                        } else {
                            if (pvName == 'country') var pvVal = $("#country option:selected").text();
                            else var pvVal = formField.value;
                        }
                        newFields += '<input type="text" name="payvia[' + formField.payvia_field_id + ':' + formField.required + ':' + formField.min + ':' + formField.max + ':::' + formField.check_function + '::' + formField.name + ']" value="' + pvVal + '" id="pvid_' + formField.payvia_field_id + '" class="edit-form-text PayViaInfo_Edit payViaFill_' + pvName + '">';
                    }
                    newFields += '</td>';
                });
                if ((fCount + 1) % 2 == 0) {
                    newFields += '<td class="tab-column tablespacer">&nbsp;</td><td class="tab-column tablespacer">&nbsp;</td>';
                }
                newFields += '</tr>';

                $('.PayViaOptions').remove();
                $("#payvia_start_row").after(newFields);
            }

        });

        return false;
    }

    $(document).ready(function() {
        $("#payvia_type_id").change(function() {
            update_payvia_fields(false);
            return false;
        });

        $("#address1").change(function() {
            var curVal = $(this).val();
            if ($(".payViaFill_address").val() == '') {
                $(".payViaFill_address").val(curVal);
            }
            if ($(".payViaFill_address1").val() == '') {
                $(".payViaFill_address1").val(curVal);
            }
        });

        $("#zip_code").change(function() {
            var curVal = $(this).val();
            if ($(".payViaFill_zip_code").val() == '') {
                $(".payViaFill_zip_code").val(curVal);
            }
            if ($(".payViaFill_zipcode").val() == '') {
                $(".payViaFill_zipcode").val(curVal);
            }
        });

        $(".addressSetting").change(function() {
            var curId = $(this).attr('id');
            var curVal = $(this).val();
            if (curId == 'country') {
                $(".payViaFill_" + curId).val($("#country option:selected").text());
            } else if ($(".payViaFill_" + curId).val() == '') {
                $(".payViaFill_" + curId).val(curVal);
            }
        });

    });
</script>
{/literal}


<div class="mainblock mediumblock">
    <div class="heading">
        <div class="hold">
            <h2>{$config.NICE_NAME} {#AffiliateSignup#}</h2>
        </div>
    </div>
    <div class="content">
        <div class="c">
            <div class="standard-block">

                <div class="inner-clear-separator"></div>



                <div class="inner-clear-separator"></div>

                <form action="submit.php" method="POST" name="signup_form">
                    <input type="hidden" value="external_signup" name="template" />
                    <input type="hidden" value="submit_signup_basic" name="submit_function" />
                    <input type="hidden" value="signup_done" name="page" />
                    <input type="hidden" value="signup" name="original_page" />
                    <input type="hidden" value="external" name="location" />
                    <input type="hidden" value="signup" name="variable_array[]" />
                    <input type="hidden" value="payvia" name="variable_array[]" />
                    <input type="hidden" value="{$smarty.request.ref}" name="signup[ref]" />
                    <input type="hidden" value="{$smarty.request.rep}" name="signup[rep]" />
                    <input type="hidden" value="{$smarty.request.nats}" name="signup[nats]" />

                    <table class="table-container form-table" cellpadding="0" cellspacing="0" id="signupForm">
                        {if $config.CLOSED_AFFILIATE_SIGNUP}

                        <tr class="data-row-{cycle name='data_row' values='odd,even'}-last form-ro">
                            <td class="tab-column"><b>{#SignupPassword#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="signup[closed_password:1]" value="{$vars.signup.closed_password}" id="closed_password" class="edit-form-text{if isset($errors.signup.closed_password)} edit-form-error{/if}" />
                            </td>
                            <td class="tab-column tablespacer">&nbsp;</td>
                        </tr>
                        {/if}

                        <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                            <td class="tab-column"><b>{#Username#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="signup[username:1:6:16:::easy_username_check]" value="{$vars.signup.username}" id="username" class="edit-form-text{if isset($errors.signup.username)} edit-form-error{/if}">
                            </td>

                            <td class="tab-column company-class"><b>{#Company#}:</b></td>
                            <td class="tab-column left-align company-class">
                                <input type="text" name="signup[company:0:2:64]" value="{$vars.signup.company}" id="company" class="edit-form-text{if isset($errors.signup.company)} edit-form-error{/if}">
                            </td>
                        </tr>
                        <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                            <td class="tab-column"><b>{#Password#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="password" name="signup[password:1:6:16:::easy_password_check]" value="{$vars.signup.password}" id="password" class="edit-form-text{if isset($errors.signup.password)} edit-form-error{/if}">
                            </td>
                            <td class="tab-column"><b>{#VerifyPassword#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="password" name="signup[nostore_verify_password:1:6:16:password]" value="{$vars.signup.nostore_verify_password}" id="nostore_verify_password" class="edit-form-text{if isset($errors.signup.nostore_verify_password)} edit-form-error{/if}">
                            </td>
                        </tr>
                        <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                            <td class="tab-column"><b>{#Email#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="signup[email:1:5:64:::email_check]" value="{$vars.signup.email}" id="email" class="edit-form-text{if isset($errors.signup.email)} edit-form-error{/if}">
                            </td>
                            <td class="tab-column"><b>{#VerifyEMail#}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="signup[nostore_verify_email:1:5:64:email]" value="{$vars.signup.nostore_verify_email}" id="nostore_verify_email" class="edit-form-text{if isset($errors.signup.nostore_verify_email)} edit-form-error{/if}">
                            </td>
                        </tr>
                    </table>
                    <br>
                    <div class="progress tpl-progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;">
                            <div>% COMPLETE</div> <span>33</span>
                        </div>
                    </div><br>


                    <a id="toggleformpart2" class="signupformtoggle" href="#">NEXT &#8595;</a>
                    <div class="hideform">

                        <table class="table-container form-table" cellpadding="0" cellspacing="0" id="signupForm">
                            <!--tr>
							<td colspan="4"><div class="full-width-header section_header5">{#Preferences#}</div></td>
						</tr-->
                            {list_programs type=" 0"}
                            <tr class="data-row-{cycle name='data_row' values='odd,even'}-last form-ro">
                                <td class="tab-column"><b>{#AcceptMailings#}:</b>&nbsp;</td>
                                <td class="tab-column left-align">
                                    <input type="checkbox" name="signup[mailok]" value="1" checked>
                                    &nbsp;
                                </td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                            </tr>

                            <tr>

                                {if $programs|@count > 1}
                                <td class="tab-column"><b>{#DefaultProgram#}:</b>&nbsp;</td>
                                <td class="tab-column left-align">
                                    <select name="signup[default_program]" class="edit-form-select">
                                        <option value="0" {if $vars.signup.default_program==$pid} selected{/if}>{#AllPrograms#}</option>
                                        {foreach from=$programs item="program" key="pid"}
                                        <option value="{$pid}" {if $vars.signup.default_program==$pid} selected{/if}>{$program}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                {elseif $programs|@count == 1}
                                {foreach from=$programs item="program" key="pid"}
                                <input type=hidden value="{$pid}" name="signup[default_program]">
                                {/foreach}
                                <!--td class="tab-column" colspan="2">&nbsp;</td-->
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                {else}
                                <!--td class="tab-column" colspan="2">&nbsp;</td-->

                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>
                                <td class="tab-column tablespacer">&nbsp;</td>>
                                {/if}
                            </tr>
                            <!--tr>
							<td colspan="4"><div class="full-width-header section_header5">{#ContactInfo#}</div></td>
						</tr-->

                            <tr class="data-row-{cycle name='data_row' values='odd,even'}{if empty($config.ADMIN_RESELLER_CONTACT1) && empty($config.ADMIN_RESELLER_CONTACT2) && empty($config.ADMIN_RESELLER_CONTACT3) && empty($config.ADMIN_RESELLER_CONTACT4) && empty($config.ADMIN_RESELLER_CONTACT5)}-last{/if} form-ro">
                                <td class="tab-column"><b>{#ICQ#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[icq:0]" value="{$vars.signup.icq}" id="icq" class="edit-form-text{if isset($errors.signup.icq)} edit-form-error{/if}">
                                </td>
                                <td class="tab-column"><b>{#AIM#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[aim:0]" value="{$vars.signup.aim}" id="aim" class="edit-form-text{if isset($errors.signup.aim)} edit-form-error{/if}">
                                </td>
                            </tr>
                            {section name="affiliate_contacts" loop="5"}
                            {assign var="config_name" value="ADMIN_RESELLER_CONTACT`$smarty.section.affiliate_contacts.iteration`"}
                            {assign var="variable_name" value="affiliate_contact`$smarty.section.affiliate_contacts.iteration`"}
                            {if !empty($config[$config_name])}
                            {capture append="custom_contacts"}
                            <td class="tab-column"><b>{$config[$config_name]|convlang}:</b></td>
                            <td class="tab-column left-align">
                                <input type="text" name="signup[{$variable_name}:0]" value="{$vars.signup[$variable_name]}" id="{$variable_name}" class="edit-form-text{if isset($errors.signup[$variable_name])} edit-form-error{/if}" />
                            </td>
                            {/capture}
                            {/if}
                            {/section}
                            {if !empty($custom_contacts)}
                            {section name="affiliate_contacts" loop=$custom_contacts|@count}
                            {if $smarty.section.affiliate_contacts.iteration mod 2}
                            <tr class="data-row-{cycle name='data_row' values='odd,even'}{if ($smarty.section.affiliate_contacts.index_next+1) >= $smarty.section.affiliate_contacts.total}-last{/if} form-ro">
                                {/if}
                                {$custom_contacts[$smarty.section.affiliate_contacts.index]}
                                {if !$smarty.section.affiliate_contacts.iteration mod 2}
                            </tr>
                            {/if}
                            {/section}
                            {/if}




                            <tr class="data-row-{cycle name='data_row' values='odd,even'}-last form-ro">
                                <td class="tab-column"><b>{#URL#}:</b></td>
                                <td class="tab-column left-align" colspan="3">
                                    <textarea cols="85" rows="4" name="signup[url:1:2:256]" value="please enter correct value" id="url" class="{if isset($errors.signup.url)} edit-form-error{/if}">{$vars.signup.url}</textarea>
                                </td>
                                {* <td class="tab-column" colspan="2">&nbsp;</td> *}
                            </tr>



                            <!--tr>
							<td colspan="4"><div class="full-width-header section_header5">{#MailingAddress#}</div></td>
						</tr-->
                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                                <td class="tab-column"><b>{#FirstName#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[firstname:1:2:32]" value="{$vars.signup.firstname}" value="please enter correct value" id="firstname" class="edit-form-text{if isset($errors.signup.firstname)} edit-form-error{/if}">
                                </td>
                                <td class="tab-column"><b>{#LastName#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[lastname:1:2:32]" value="{$vars.signup.lastname}" value="please enter correct value" id="lastname" class="edit-form-text{if isset($errors.signup.lastname)} edit-form-error{/if}">
                                </td>
                            </tr>
                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                                <td class="tab-column"><b>{#Address#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[address1:1:6:128]" value="{$vars.signup.address1}" value="please enter correct value" id="address1" class="edit-form-text{if isset($errors.signup.address1)} edit-form-error{/if}">
                                </td>
                                <td class="tab-column"><b>{#Address2#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[address2:0:0:128]" value="{$vars.signup.address2}" value="please enter correct value" id="address2" class="edit-form-text{if isset($errors.signup.address2)} edit-form-error{/if} addressSetting">
                                </td>
                            </tr>
                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                                <td class="tab-column"><b>{#City#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[city:1:2:64]" value="{$vars.signup.city}" value="please enter correct value" id="city" class="edit-form-text{if isset($errors.signup.city)} edit-form-error{/if} addressSetting">
                                </td>
                                <td class="tab-column"><b>{#State#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[state:0:0:32]" value="{$vars.signup.state}" value="please enter correct value" id="state" class="edit-form-text{if isset($errors.signup.state)} edit-form-error{/if} addressSetting">
                                </td>
                            </tr>
                            <tr class="data-row-{cycle name='data_row' values='odd,even'}-last form-ro">
                                <td class="tab-column"><b>{#ZipCode#}:</b></td>
                                <td class="tab-column left-align">
                                    <input type="text" name="signup[zip_code:1]" value="{$vars.signup.zip_code}" value="please enter correct value" id="zip_code" class="edit-form-text{if isset($errors.signup.zip_code)} edit-form-error{/if}">
                                </td>
                                <td class="tab-column"><b>{#Country#}:</b></td>
                                <td class="tab-column left-align">
                                    {if $vars.signup.country}{assign var='country_default' value=$vars.signup.country}{else}{assign var='country_default' value='US'}{/if}
                                    <select name="signup[country]" class="edit-form-select addressSetting" id="country">
                                        {foreach from=$countries item="country" key="short"}
                                        <option value="{$short}" label="{$country}" {if $country_default==$short}selected{/if}>{$country}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>


                        </table>

                        <br>
                        <div class="progress tpl-progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style="width: 66%;">
                                <div>% COMPLETE</div> <span>66</span>
                            </div>
                        </div><br>
                        <a id="toggleformpart3" class="signupformtoggle" href="#">NEXT &#8595;</a>
                    </div><!-- end hide form -->

                    <div class="hideformpart3">

                        <table class="table-container form-table" cellpadding="0" cellspacing="0" id="signupForm">

                            <!--tr>
							<td colspan="4"><div class="full-width-header section_header5">{#PaymentDetails#}</div></td>
						</tr-->
                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro" id="payvia_start_row">
                                <td class="tab-column"><b>{#PaymentMethod#}:</b></td>
                                <td class="tab-column left-align">
                                    <select name="signup[payvia]" id="payvia_type_id" class="edit-form-select">
                                        {html_options options=$payvias selected=$vars.signup.payvia}
                                    </select>
                                </td>
                                {* Get the Current PayVia Options *}
                                {display_signup_payvia data_only="1" payvia=$vars.signup.payvia}
                                <td class="tab-column"><b>{#MinimumPayout#}:</b></td>
                                <td class="tab-column left-align">
                                    <select name="signup[minimum_payout:1]" id="minimum_payout" class="edit-form-select">
                                        {html_options options=$myMins selected=$vars.signup.minimum_payout}
                                    </select>
                                </td>
                            </tr>

                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro PayViaOptions">
                                {foreach from=$myForm item="formField" name="pvLoop"}
                                {if ($smarty.foreach.pvLoop.iteration+1) % 2 == 0}
                            </tr>
                            <tr class="data-row-{if ($smarty.foreach.pvLoop.iteration+1) % 4 == 0}odd{else}even{/if} form-ro PayViaOptions">
                                {/if}
                                <td class="tab-column"><b>{$formField.name|convlang}:</b></td>
                                <td class="tab-column left-align">
                                    {if $formField.type == 1}
                                    {* multiple choice *}
                                    {if $formField.options}
                                    <select name="payvia[{$formField.payvia_field_id}:{$formField.required}:::::{$formField.check_function}::{$formField.name}]" value="please enter correct value" id="pvid_{$formField.payvia_field_id}" class="edit-form-select{if isset($errors.payvia[$formField.payvia_field_id])} edit-form-error{/if} PayViaInfo_Edit">
                                        {html_options options=$formField.options selected=$vars.signup[$formField.name]}
                                    </select>
                                    {/if}
                                    {elseif $formField.type == 2}
                                    {* file upload *}
                                    <input type="file" name="payvia[{$formField.payvia_field_id}]" value="please enter correct value" id="pvid_{$formField.payvia_field_id}" class="edit-form-text{if isset($errors.payvia[$formField.payvia_field_id])} edit-form-error{/if} PayViaInfo_Edit">
                                    {else}
                                    {* text *}
                                    {if $formField.name == "Country" && !isset($vars.payvia[$formField.payvia_field_id])}
                                    <input type="text" value="{$countries[$country_default]}" name="payvia[{$formField.payvia_field_id}:{$formField.required}:{$formField.min}:{$formField.max}:::{$formField.check_function}::{$formField.name}]" value="{$vars.payvia[$formField.payvia_field_id]}" id="pvid_{$formField.payvia_field_id}" class="edit-form-text{if isset($errors.payvia[$formField.payvia_field_id])} edit-form-error{/if} PayViaInfo_Edit payViaFill_{$formField.name|lower|replace:' ':''}">
                                    {else}
                                    <input type="text" name="payvia[{$formField.payvia_field_id}:{$formField.required}:{$formField.min}:{$formField.max}:::{$formField.check_function}::{$formField.name}]" value="{$vars.payvia[$formField.payvia_field_id]}" id="pvid_{$formField.payvia_field_id}" class="edit-form-text{if isset($errors.payvia[$formField.payvia_field_id])} edit-form-error{/if} PayViaInfo_Edit payViaFill_{$formField.name|lower|replace:' ':''}">
                                    {/if}
                                    {/if}
                                </td>
                                {/foreach}
                                {if ($smarty.foreach.pvLoop.iteration+1) % 2 == 0}
                                <td class="tab-column" colspan="2"><span class="tablespacer">&nbsp;</td>
                                {/if}
                            </tr>
                            <tr class="data-row-{cycle name='data_row' values='odd,even'} form-ro">
                                <td class="tab-column"><b>{#TaxIDSSN#}:</b></td>
                                <td class="tab-column left-align" colspan="2">
                                    <input type="text" name="signup[tax_id_or_ssn:0]" value="{$vars.signup.tax_id_or_ssn}" value="000000000" id="tax_id_or_ssn" class="edit-form-text{if isset($errors.signup.tax_id_or_ssn)} edit-form-error{/if}">
                                    <div class="onlyinus">{#OnlyinUS#}</div>
                                </td>
                                <td class="tab-column"><span class="tablespacer">&nbsp;</td>
                            </tr>



                            {if !empty($config.AFFILIATE_TOS_AGREE) && $config.AFFILIATE_TOS_AGREE > 0}
                            <tr>
                                <td colspan="4"><!--div class="full-width-header section_header5">Terms of Service</div-->
                                    <div style="display: block; width: 100%;">
                                        <div style="display: block; margin: 0 auto;">
                                            <div style="width: 24px; display: inline-block; margin-top: -10px;"><input type="checkbox" name="signup[tos_check:1]" value="1"></div>
                                            <div style="display: inline-block; padding: 5px 0 0 10px;"><b>{#IagreeTOS#}</b></div>
                                        </div>
                                    </div>


                                </td>
                            </tr>
                            <td class="tab-column">

                            </td>
                            <td class="tab-column left-align"></td>
                            {/if}


                            {if $config.AFFILIATE_SIGNUP_CAPTCHA}
                            <tr class="data-row-{cycle name='data_row' values='odd,even'}-last">
                                {* VERIFICATION IMAGE *}

                                <td colspan="4">
                                    <div class="full-width-header section_header5">{#VerificationImage#}</div>
                                </td>

                            </tr>
                            <tr class="data-row-{cycle name='data_row' values='odd,even'}-last">
                                <td class="tab-column"><b>{#VerificationImage#}</b></td>
                                <td class="tab-column left-align">
                                    <img src="captcha_image.php" width="150" height="45" border="0" style="padding: 3px; padding-left: 10px; padding-top: 6px">
                                </td>
                                <td class="tab-column"><b>{#Input#}</b></td>
                                <td class="tab-column left-align">
                                    <input class="edit-form-text{if isset($errors.signup.captcha)} edit-form-error{/if}" name="signup[captcha]" size="35" value="{$vars.captcha}">

                                </td>


                                {* END VERIFICATION IMAGE *}

                            </tr>
                            {/if}



                            {* Footer with Submit *}
                            <tr class="footer-row no-foot-line">
                                <td colspan="4" class="tab-column">
                                    <div class="tools">
                                        <input type="submit" class="button" id="Submit_SignupInfo" value="{#SUBMITSIGNUP#}">
                                    </div>
                                </td>
                            </tr>

                    </div><!-- end hideform -->
                    </table>

                </form>


            </div>
        </div>
    </div>
</div>

{literal}


<!--
<script>

document.getElementById("icq").defaultValue = "Please Update";
document.getElementById("aim").defaultValue = "Please Update";
document.getElementById("affiliate_contact1").defaultValue = "Please Update";
document.getElementById("affiliate_contact2").defaultValue = "Please Update";
document.getElementById("affiliate_contact3").defaultValue = "Please Update";
document.getElementById("affiliate_contact4").defaultValue = "Please Update";
document.getElementById("url").defaultValue = "Please Update";
document.getElementById("firstname").defaultValue = "Please Update";
document.getElementById("lastname").defaultValue = "Please Update";
document.getElementById("address1").defaultValue = "Please Update";
document.getElementById("address2").defaultValue = "Please Update";
document.getElementById("city").defaultValue = "Please Update";
document.getElementById("state").defaultValue = "Please Update";
document.getElementById("zip_code").defaultValue = "Please Update";

document.getElementById("pvid_1").defaultValue = "Please Update";
document.getElementById("pvid_30").defaultValue = "Please Update";
document.getElementById("pvid_31").defaultValue = "Please Update";
document.getElementById("pvid_32").defaultValue = "Please Update";
document.getElementById("pvid_33").defaultValue = "Please Update";
document.getElementById("pvid_34").defaultValue = "Please Update";
document.getElementById("tax_id_or_ssn").defaultValue = "Please Update";


</script>
-->

{/literal}