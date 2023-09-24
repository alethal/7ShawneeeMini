{*
9647 - changed the tag checks to allow all characters except ',",[,],{,},(,),<,>
    *}
    {* Setup Javascript necessary for this page *}
    {literal}
    <script>
        //test alphanumeric
        //var re = /^[A-Za-z0-9_]+$/;
        // 9647 test for bad characters instead
        var re = /[\'\"\[\]\{\}\(\)\<\>]+/;

        function displaySubmitButton() {
            $("#Save_NewCampaign").removeClass('DisableSubmit');
            $("#Save_NewCampaign").prop('disabled', false);
        }

        function removeTag(spnId, node) {
            //$("#"+spnId).remove(); 9647
            $(node.parentNode).remove();
            displaySubmitButton();
            wrapLongTagList();
            return false;
        }

        function addNewTag() {
            var newTagInput = $("#appendTag").val();
            if (newTagInput) {
                var newTags = newTagInput.split(',');
                $.each(newTags, function(index, newTag) {
                    newTag = $.trim(newTag);
                    if (re.test(newTag)) {
                        //alert("Tags may only contain letters, numbers, and underscores!"); 9647
                        alert("The following characters are not allowed: ',\",[,],{,},(,),<,>");
                        newTag = false;
                    }
                    //make sure we don't already have this Tag
                    $(".addedTags").each(function(index) {
                        if (newTag && $(this).val().toLowerCase() == newTag.toLowerCase()) {
                            alert("This tag is already added! (" + newTag + ")");
                            newTag = false;
                        }
                    });
                    if (newTag && newTag.length > 16) {
                        alert("This tag is more than 16 characters! (" + newTag + ")");
                        newTag = false;
                    }
                    if (newTag) {
                        $("#appendTag").val('');
                        $('#appendTag').focus();
                        $('#appendTag').before('<span class="appending_strings" id="nTag_' + newTag + '"><input type="hidden" class="addedTags" name="create[campaign][]" value="' + newTag + '">' + newTag + '<a class="append-remove-setting" href="#" onclick="removeTag(\'nTag_' + newTag + '\',this); return false;">x</a>,</span>');
                        wrapLongTagList();
                        displaySubmitButton();
                    }
                });
            }
            return false;
        }

        function appendTagSet(curTags) {
            curTags = curTags.split(",");
            $.each(curTags, function(index, value) {
                $(".addedTags").each(function(subdex) {
                    if (value && $(this).val().toLowerCase() == value.toLowerCase()) {
                        value = false;
                    }
                });
                if (value) $('#appendTag').before('<span class="appending_strings" id="nTag_' + value + '"><input type="hidden" class="addedTags" name="create[campaign][]" value="' + value + '">' + value + '<a class="append-remove-setting" href="#" onclick="removeTag(\'nTag_' + value + '\',this); return false;">x</a>,</span>');
            });
            wrapLongTagList();
            return false;
        }

        function wrapLongTagList() {
            $(".tagWrap").remove();
            $('#currentTags .appending_strings').each(function(index) {
                if (index && index % 10 == 0) {
                    $(this).append('<br class="tagWrap">');
                }
            });
            return false;
        }

        //start jquery
        $(document).ready(function() {

            //remove the search title
            $("#appendTag").focus(function() {
                var curVal = $(this).val();
                if (curVal == '{/literal}{#AddNewTag#}{literal}...') {
                    $(this).val('');
                    $(this).removeClass('DisableEdit');
                }
                return false;
            });
            $("#appendTag").blur(function() {
                var curVal = $(this).val();
                if (curVal == '') {
                    $(this).val('{/literal}{#AddNewTag#}{literal}...');
                    $(this).addClass('DisableEdit');
                }
                return false;
            });

            //enable the submit button
            $(".filter-select").change(function() {
                displaySubmitButton();
            });

            //append Tags
            $("#clearCurTags").click(function() {
                $(".tagWrap").remove();
                $("#currentTags .appending_strings").remove();
                displaySubmitButton();
                return false;
            });
            $("#appendTagPlus").click(function() {
                addNewTag();
                return false;
            });
            $("#appendTagSet").click(function() {
                //display the popup to submit the bookmark
                var txt = {
                    state0: {
                        html: '<div class="filter-form">{/literal}<span>{#ExistingCampaigns#}:</span><select name="cSet" id="cSet" class="filter-select">{foreach from=$campaigns item="campset" key="campsetid"}{if $campsetid}<option value="{$campset.name|escape:javascript}">{$campset.name|escape:javascript}</option>{/if}{/foreach}</select>{literal}</div>',
                        buttons: {
                            '{/literal}{#CANCEL#}{literal}': false,
                            '{/literal}{#ADDCAMPAIGN#}{literal}': true
                        },
                        focus: 1,
                        submit: function(e, v, m, f) {
                            if (!v) $.prompt.close();
                            else {
                                appendTagSet(f.cSet);
                                displaySubmitButton();
                            }
                            $.prompt.close();
                            return false;
                        }
                    }
                }
                $.prompt(txt);
                return false;
            });

            $("#appendTag").keypress(function(e) {
                if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                    addNewTag();
                    return false;
                }
            });
            var cache = {},
                lastXhr;
            $("#appendTag").autocomplete({
                source: function(request, response) {
                    var term = request.term;

                    //make sure its a valid term
                    if (re.test(term)) return false;

                    if (term in cache) {
                        response(cache[term]);
                        return;
                    }

                    lastXhr = $.getJSON("ajax_data.php", {
                        'function': 'ajax_get_matching_campaign_tags',
                        'search': request.term
                    }, function(data, status, xhr) {
                        cache[term] = data;
                        if (xhr === lastXhr) {
                            response(data);
                        }
                    });
                }
            });

        });
    </script>
    {/literal}


    {* Setup 2 Column Display *}
    <div class="twocolumn">
        <div class="c">
            <div class="box-hold">

                {* Display the Left Column *}
                {if $smarty.request.section == 'adtools'}

                {* Begin Left Column *}
                <div class="box first-child">

                    {* Display Quick Navigation *}
                    <div class="left-bar-only">
                        <div class="heading">
                            <div class="hold">
                                {* Display Nav Header *}
                                <h2>{#Navigation#}</h2>
                            </div>
                        </div>
                        <div class="content no-padding disp-stats_form">
                            {* Display Quick Nav Options *}
                            <ul class="side-bar-nav">
                                {foreach from=$possible_adtools_views item="adcat" key="catid"}
                                <li><a href="internal.php?page=adtools&category={$catid}"><span>{$adcat.description|convlang}</span></a></li>
                                {/foreach}
                                <li><a href="internal.php?page=campaigns&section=adtools" class="side-bar-selected"><span>{#Campaigns#}</span>
                                        <div class="side-sel-arrow"></div>
                                    </a></li>
                                <li><a href="internal.php?page=codes"><span>{#Linkcodes#}</span></a></li>
                            </ul>
                        </div>
                    </div>

                </div>

                {else}
                {include file="nats:include_affiliate_account_sidebar"}
                {/if}

                {* Begin Right Column *}
                <div class="box">
                    <div class="heading">
                        <div class="hold">
                            <a href="/internal.php?page=support&view=NATShelp&section=account&article=Campaigns#Campaigns" target="_blank" class="helpbtn" title="{#HelpAccountCampaigns#}"><span>?</span></a>
                            {* Campaign Header *}
                            <h2>{#Campaigns#}</h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="c">
                            <div class="standard-block">
                                <div class="display-content">

                                    {* Display Error on Create Campaign *}
                                    {if $errors.create.langCodes.campaign}
                                    <div class="action-message type-error">
                                        <div class="action-header">
                                            <div class="action-title">{#FormError#}</div>
                                            <a href="#" class="close-action">{#CLOSE#}</a>
                                        </div>
                                        <div class="action-details">
                                            {* Call the Language Message Display for Error Codes *}
                                            {display_language_message message=$errors.create.langCodes.campaign}
                                        </div>
                                    </div>
                                    {elseif $errors.create.campaign}
                                    {$errors.create.campaign}
                                    {elseif isset($smarty.get.cache) && $smarty.get.action == 'submit_create_campaign'}
                                    <div class="action-message type-success">
                                        <div class="action-header">
                                            <div class="action-title">{#CampaignCreated#}</div>
                                            <a href="#" class="close-action">{#CLOSE#}</a>
                                        </div>
                                        <div class="action-details">{#CampCreateSuccess#}</div>
                                    </div>
                                    {/if}

                                    {* Display Header *}
                                    <div class="section_header">
                                        {#CreateNewCampaign#}
                                    </div>
                                    {* Create New Campaign Form *}
                                    <form action="submit.php" method="post" name="NewCampaign_Form" id="NewCampaign_Form">
                                        <input type="hidden" name="template" value="affiliate_campaigns">
                                        <input type="hidden" name="submit_function" value="submit_create_campaign">
                                        <input type="hidden" name="page" value="campaigns">
                                        <input type="hidden" name="location" value="internal">
                                        <input type="hidden" name="variable_array[]" value="create">

                                        <table class="table-container2 form-table" cellpadding=0 cellspacing=0>
                                            <tr class="data-row-even">
                                                <td class="tab-column" width="15%"><b>{#Campaign#}:</b></td>
                                                <td class="tab-column left-align" colspan="2">
                                                    <div id="currentTags" class="curTags">
                                                        <input type="text" name="addingTags" value="{#AddNewTag#}..." id="appendTag" class="filter-text-medium DisableEdit" />
                                                        <a class="append-remove-setting" href="#" id="appendTagPlus">+</a>
                                                    </div>
                                                    <span class="tag-set-link"><a href="#" id="appendTagSet">{#addexistingcampaign#}</a> | <a href="#" id="clearCurTags">{#clearalltags#}</a></span>
                                                </td>
                                            </tr>
                                            <tr class="data-row-even-last">
                                                <td class="tab-column" width="15%"><b>{#Description#}:</b></td>
                                                <td class="tab-column left-align">
                                                    <input type="text" name="create[description]" value="{$vars.create.description}" id="description" class="edit-form-text-long NewCampaign_Edit">
                                                </td>
                                                <td class="tab-column">
                                                    <div class="tools">
                                                        <input type=submit class="button DisableSubmit" id="Save_NewCampaign" disabled="1" value="{#SAVENEWCAMPAIGN#}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="footer-row">
                                                <td colspan="3" class="tab-column">&nbsp;</td>
                                            </tr>
                                        </table>

                                        {* End Form *}
                                    </form>

                                    {* Display Current Campaigns Page Count *}
                                    <div class="section_header no-section-line">
                                        <span>{#ShowingCampaigns#} {$start+1} - {if $count == 'all' || $count == -1 || $start+$count > $totalcount}{$totalcount}{else}{$start+$count}{/if} {#of#} {$totalcount} </span>
                                    </div>

                                    {* Build Query Vars for Links *}
                                    {rebuild_query using="GET" without="camp_orderby" assign="orderLink"}
                                    {rebuild_query using="GET" without="id" assign="actionLink"}

                                    {* What is the default order? *}
                                    {if $smarty.request.camp_orderby}{assign var="campOrder" value=$smarty.request.camp_orderby}
                                    {elseif $usr.default_campaigns_orderby}{assign var="campOrder" value=$usr.default_campaigns_orderby}
                                    {else}{assign var="campOrder" value="1"}{/if}

                                    {* Begin Table Display *}
                                    <table class="table-container2" id="camp-table" cellpadding=0 cellspacing=0>

                                        <thead>
                                            {* Display Search Row *}
                                            <tr class="data-row-even no-bottom-line">
                                                <form action="internal.php" method="GET" name="inlineSearch">
                                                    {* Include Current Vars *}
                                                    {rebuild_form using=GET without="search,camp_start"}
                                                    <td class="tab-column left-align col_{counter start=0}">
                                                        <div class="tools filter-form">
                                                            <input name="search[name]" id="inline-shortname" value="{if $search_vars.name}{$search_vars.name}{else}{#Search#}...{/if}" class="filter-text{if empty($search_vars.name)} DisableEdit{/if}">
                                                        </div>
                                                    </td>
                                                    <td class="tab-column left-align col_{counter}">
                                                        <div class="tools filter-form">
                                                            <input type="text" name="search[description]" id="inline-description" value="{if $search_vars.description}{$search_vars.description}{else}{#Search#}...{/if}" class="filter-text-long{if empty($search_vars.description)} DisableEdit{/if}" />
                                                        </div>
                                                    </td>
                                                    <td class="tab-column left-align col_{counter}">&nbsp;</td>
                                                    <td class="tab-column col_3">
                                                        <div class="tools filter-form"><input type=submit class="button DisableSubmit" id="inline-search-submit" disabled="1" value="{#APPLYSEARCH#}"></div>
                                                    </td>
                                                    {* End Form *}
                                                </form>
                                            </tr>
                                        </thead>

                                        <thead>
                                            {* Display Header Row, with Sortable Columns *}
                                            <tr class="header-row2">
                                                <td class="tab-column left-align header-first{if $campOrder == '1' || $campOrder == '2'} orderby-field{/if}">
                                                    <a class="reorder_link" id="reorder_{counter start=0}" href="internal.php?{$orderLink}&camp_orderby={if $campOrder == '1'}2{else}1{/if}">
                                                        <div class="table-orderby-wrapper">{if $campOrder == 2}<div class="table-orderby-field-reverse" style="display: block;"></div>{elseif $campOrder == 1}<div class="table-orderby-field"></div>{/if}</div>{#Campaign#}
                                                    </a>
                                                </td>
                                                <td class="tab-column left-align{if $campOrder == '3' || $campOrder == '4'} orderby-field{/if}">
                                                    <a class="reorder_link" id="reorder_{counter}" href="internal.php?{$orderLink}&camp_orderby={if $campOrder == '3'}4{else}3{/if}">
                                                        <div class="table-orderby-wrapper">{if $campOrder == 3}<div class="table-orderby-field"></div>{elseif $campOrder == 4}<div class="table-orderby-field-reverse" style="display: block;"></div>{/if}</div>{#Notes#}
                                                    </a>
                                                </td>
                                                <td class="tab-column left-align{if $campOrder == '5' || $campOrder == '6'} orderby-field{/if}">
                                                    <a class="reorder_link" id="reorder_{counter}" href="internal.php?{$orderLink}&camp_orderby={if $campOrder == '6'}5{else}6{/if}">
                                                        <div class="table-orderby-wrapper">{if $campOrder == 6}<div class="table-orderby-field"></div>{elseif $campOrder == 5}<div class="table-orderby-field-reverse" style="display: block;"></div>{/if}</div>{#Created#}
                                                    </a>
                                                </td>
                                                <td class="tab-column nohover header-last">{#Actions#}</td>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            {* Loop Through the Campaigns to Display *}
                                            {foreach from=$campaigns item="campaign" key="campaignid" name="cloop"}

                                            {* Alternate Odd/Even Rows *}
                                            <tr class="data-row-{if $smarty.foreach.cloop.iteration % 2 == 0}even{else}odd{/if}{if $campaign.hide}-off{elseif $smarty.foreach.cloop.last} last-row{/if}{if $smarty.foreach.cloop.first} first-row{/if}">

                                                {if $campaignid && $smarty.request.id == $campaignid}
                                                {* Setup Form for editing Campaign *}
                                                <form action="submit.php" method="post" name="detchg">
                                                    <input type="hidden" name="template" value="affiliate_campaigns">
                                                    <input type="hidden" name="submit_function" value="submit_edit_campaign">
                                                    <input type="hidden" name="page" value="campaigns">
                                                    <input type="hidden" name="location" value="internal">
                                                    <input type="hidden" name="variable_array[]" value="edit">
                                                    <input type="hidden" name="camp_orderby" value="{$smarty.request.camp_orderby}">
                                                    <input type="hidden" name="camp_start" value="{$smarty.request.camp_start}">
                                                    <input type="hidden" name="count" value="{$smarty.request.count}">
                                                    {* If user is Editing Campaign *}
                                                    <td class="tab-column left-align col_{counter start=0}"><input type="hidden" value="{$campaignid}" name="edit[campaignid]">{$campaign.name|replace:",":", "}</td>
                                                    <td class="tab-column left-align col_{counter}"><input type="text" name="edit[description]" class="edit-form-text" value="{$campaign.description}"></td>
                                                    <td class="tab-column col_{counter}">{$campaign.created_date|nats_local_date}</td>
                                                    <td class="tab-column col_{counter}">
                                                        <div class="tools"><input type=submit class="button" id="edit-submit" value=" {#SAVE#} "></div>
                                                    </td>
                                                    {* End Form *}
                                                </form>
                                                {else}
                                                {* Display Campaign Row *}
                                                <td class="tab-column left-align col_{counter start=0}">{$campaign.name|replace:",":", "}</td>
                                                <td class="tab-column left-align col_{counter}">{if $campaign.description}{$campaign.description}{else}<span>*{#NoNotes#}*</span>{/if}</td>
                                                <td class="tab-column col_{counter}" nowrap>{$campaign.created_date|nats_local_date}</td>
                                                <td class="tab-column col_{counter}" nowrap>
                                                    {* Campaign Links *}
                                                    {if $campaignid}
                                                    {if $campaign.hide}
                                                    <a href="submit.php?{$actionLink}&template=affiliate_campaigns&submit_function=submit_edit_campaign&location=internal&variable_array[0]=edit&edit[hide]=0&edit[campaignid]={$campaignid}" class="camp-actions" title="{#UnHideDesc#}">{#UnHide#}</a>
                                                    {else}
                                                    <a href="internal.php?{$actionLink}&id={$campaignid}" class="camp-actions" title="{#EditDesc#}">{#Edit#}</a> |
                                                    <a href="submit.php?{$actionLink}&template=affiliate_campaigns&submit_function=submit_edit_campaign&location=internal&variable_array[0]=edit&edit[hide]=1&edit[campaignid]={$campaignid}" class="camp-actions" title="{#HideDesc#}">{#Hide#}</a> |
                                                    <a href="internal.php?page=stats&filter_campaignid={$campaignid}" class="camp-actions" title="{#StatsDesc#}">{#Stats#}</a>
                                                    {/if}
                                                    {else}
                                                    <a href="internal.php?page=stats&filter_campaignid=0" class="camp-actions" title="{#StatsDesc#}">{#Stats#}</a>
                                                    {/if}
                                                </td>
                                                {/if}
                                            </tr>

                                            {* End Loop *}
                                            {/foreach}
                                        </tbody>

                                        {* Display Footer with Pagination *}
                                        <tr class="footer-row">
                                            <td class="tab-column" colspan="4">
                                                <div class="tools" id="Paginiation">
                                                    {* Display Pagination *}
                                                    {pagination start=$start count=$count total=$totalcount start_field="camp_start" tpl="function_display_pagination" offset="1"}
                                                </div>
                                            </td>
                                        </tr>

                                        {* End Table *}
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="b"></div>
    </div>