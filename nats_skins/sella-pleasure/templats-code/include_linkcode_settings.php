{*
7112 - Added "All Programs" feature

8567 - Added check for DEFAULT_PROGRAM_ID

9647 - Changed the tag checks to allow all characters except ',",[,],{,},(,),<,>

    10235 - 9606 added tour.. making it into one line instead of two

    10523 - Hide hidden sites from drop down menu

    10288 - Default Tour Generates in NATS code when not active

    12733 - Do not keep the start count when submitting the form

    NATS-951 - added no_ids=1 parameter to the ajax_get_sites call

    *}


    {* Default Campaign Value *}
    {assign var="curCamp" value=$smarty.request.campaignid|default:$params.campaign}

    {* Default Site Value *}
    {assign var="curSite" value=$smarty.request.siteid|default:$params.site}

    {* Setup Javascript necessary for this page *}
    {literal}
    <script>
        //test alphanumeric
        //var re = /^[A-Za-z0-9_]+$/;
        // 9647 test for bad characters instead
        var re = /[\'\"\[\]\{\}\(\)\<\>]+/;

        function overlayDisplaySection() {
            $("#filter-submit").removeClass('DisableSubmit');
            $("#filter-submit").prop('disabled', false);
            if ($('#form_content_area .overlayDisplay').length) return false;
            var bHeight = $('#form_content_area').height();
            $('#form_content_area').prepend('<div class="overlayDisplay" style="height: ' + bHeight + 'px;"></div>');
            $('#form_content_area').prepend('<div class="overlayDisplayMessage">{/literal}{#Youhavemadechangespleaseapply#}{literal}</div>');
        }

        function removeTag(spnId, node) {
            //$("#"+spnId).remove(); 9647
            $(node.parentNode).remove();
            overlayDisplaySection();
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
                        //alert("Tags may only contain letters, numbers, and underscores!");
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
                        overlayDisplaySection();
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
                        overlayDisplaySection();
                    });

                    //append Tags
                    $("#clearCurTags").click(function() {
                        $(".tagWrap").remove();
                        $("#currentTags .appending_strings").remove();
                        overlayDisplaySection();
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
                                html: '<div class="filter-form">{/literal}<span>{#ExistingCampaigns#}:</span><select name="cSet" id="cSet" class="filter-select">{foreach from=$campaigns item="campset" key="campsetid"}{if $campsetid}<option value="{$campset|escape:javascript}">{$campset|escape:javascript}</option>{/if}{/foreach}</select>{literal}</div>',
                                buttons: {
                                    '{/literal}{#CANCEL#}{literal}': false,
                                    '{/literal}{#ADDCAMPAIGN#}{literal}': true
                                },
                                focus: 1,
                                submit: function(e, v, m, f) {
                                    if (!v) $.prompt.close();
                                    else {
                                        appendTagSet(f.cSet);
                                        overlayDisplaySection();
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

                    $("#link_program").change(function() {
                        var myProg = $(this).val();
                        var mySite = $('#link_site').val();
                        //10523
                        $('#link_site').load('ajax_data.php?function=ajax_get_sites&no_ids=1&option_all=1&no_hidden_sites=1&programid=' + myProg + '&siteid=' + mySite);
                        return false;
                    });

                    $("#link_site").change(function() {
                        var siteid = $(this).val();
                        var programid = $('#link_program').val();
                        $.post(
                            "ajax_data.php", {
                                "function": "ajax_get_tours_json",
                                "siteid": siteid,
                                "programid": programid
                            },
                            function(data) {
                                data = JSON.parse(data.trim());
                                html = "";
                                html += '<option value="0">All Tours</option>';
                                $.each(data, function(k, v) {
                                    html += '<option value="' + k + '">' + v + '</option>';
                                });
                                $("#tour-options").html(html);
                            }
                        );
                    });

                    {
                        /literal} {
                            if $curCamp && isset($campaigns[$curCamp])
                        }
                        appendTagSet('{$campaigns[$curCamp]}');
                        {
                            /if} {
                                literal
                            }

                        });
    </script>
    {/literal}

    {* Display Error on Create Campaign *}
    {if $errors.langCodes.campaign}
    <div class="action-message type-error">
        <div class="action-header">
            <div class="action-title">{#FormError#}</div>
            <a href="#" class="close-action">{#CLOSE#}</a>
        </div>
        <div class="action-details">
            {* Call the Language Message Display for Error Codes *}
            {display_language_message message=$errors.langCodes.campaign}
        </div>
    </div>
    {elseif $errors.campaign}
    {$errors.campaign}
    {/if}

    {* Setup Form for Linkcode Settings *}
    <form action="internal.php" name="link-filter" id="link-filter">
        {rebuild_form using="GET" without="campaignid,create,programid,toggle,addingTags,siteid,start"}

        <div class="mainblock">
            <div class="heading">
                <div class="hold">

                    {* Link to Minimize Settings / Default to Affiliate Setting *}
                    {*<a href="#" class="open-close" id="default_codes_minimize_filters">{if empty($usr.default_codes_minimize_filters)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>*}

                    <a href="/internal.php?page=support&view=NATShelp&section=adtools&article=LinkcodeSettings#LinkcodeSettings" target="_blank" class="helpbtn" title="{#HelpAdToolsLinkcodeSettings#}"><span>?</span></a>

                    {* Display Header *}
                    <h2>{#LinkcodeSettings#}</h2>

                </div>
            </div>

            {* Display Setting Options / Hide based on Affiliate Setting *}
            <div class="content" {*{if !empty($usr.default_codes_minimize_filters)} style="display: none;" {/if}*}>
                <div class="c">
                    <div class="standard-block block-short-form AffiliateLinkCodeSettings">

                        <div class="block-inner-columns single-column">
                            {* Campaign Selection *}
                            <div class="filter-form newtag-box">
                                <div class="tag-title">{#Campaign#}:</div>
                                <input type="hidden" name="campaignid" value="create">
                                <input type="hidden" name="default_campaign" value="0">
                                <div id="currentTags" class="curTags">
                                    <input type="text" name="addingTags" value="{#AddNewTag#}..." id="appendTag" class="filter-text-medium DisableEdit" />
                                    <a class="append-remove-setting" href="#" id="appendTagPlus">+</a>
                                </div>
                                <span class="tag-set-link"><a href="#" id="appendTagSet">{#addexistingcampaign#}</a> | <a href="#" id="clearCurTags">{#clearalltags#}</a></span>
                            </div>
                        </div>

                        <div class="block-inner-columns four-columns" style="width:21%;">

                            {* Program Selection *}
                            {assign var="programSel" value=$params.program}
                            {if $params.program <= 0} {if $config.DEFAULT_PROGRAM_ID==-1} {get_first array=$program_names} {if $entry}{assign var="programSel" value=$entry.programid}{/if} {else} {assign var="programSel" value=$config.DEFAULT_PROGRAM_ID} {/if} {/if} <div class="filter-form">
                                <div class="filter-title">{#Program#}:</div>
                                <select name="programid" id="link_program" class="filter-select" style="width:150px;">
                                    {if !$disable_all_programs}<option value="0">{#AllPrograms#}</option>{elseif !$programid}<option value="0">{#Choose#}{/if}
                                        {foreach from=$program_names item=opt key=oid}
                                    <option value="{$oid}" {if $programSel==$oid} selected{/if}>{$opt.name|convlang}</option>
                                    {/foreach}
                                </select>
                        </div>



                    </div>
                    <div class="block-inner-columns four-columns" style="width:21%;">

                        {* Link Style Selection *}
                        {if $over}
                        {if $smarty.session.ses_usr.over_unencoded >= 0}
                        {assign var=unenc value=$smarty.session.ses_usr.over_unencoded}
                        {else}
                        {assign var=unenc value=$usr.unencoded}
                        {/if}
                        {else}
                        {assign var=unenc value=$usr.unencoded}
                        {/if}

                        <div class="filter-form">
                            <div class="filter-title">{#LinkStyle#}:</div>
                            <select name="toggle" class="filter-select" style="width:150px;">
                                {foreach from=$possible_link_styles item="lstyle" key="lsid"}
                                {if empty($disable_html_linkstyle) || $lsid < 10} <option value="{$lsid}" {if $lsid==$unenc || (!empty($disable_html_linkstyle) && $lsid==$unenc - 10)} selected{/if}>{$lstyle|convlang}</option>
                                    {/if}
                                    {/foreach}
                            </select>
                        </div>

                    </div>
                    <div class="block-inner-columns four-columns" style="width:21%;">

                        {* Site Selection *}
                        <div class="filter-form">
                            <div class="filter-title">{#Site#}:</div>
                            {list_sites assign_prefix='link_' type=1 program=$programSel}
                            <select name="siteid" id="link_site" class="filter-select" style="width:180px;">
                                <option value="-1">{#AllSites#}</option>
                                {foreach from=$link_sites item=opt key=oid}
                                <option value="{$oid}" {if $curSite==$oid} selected{/if}>{$opt|convlang}</option>
                                {/foreach}
                            </select>
                        </div>

                    </div>
                    <div class="block-inner-columns four-columns" style="width:21%;">
                        {* Tour Selection *}
                        <div class="filter-form">
                            <div class="filter-title">{#Tour#}:</div>
                            <select id="tour-options" name="tourid" id="link_site" class="filter-select" style="width:180px;">
                                <option {if !$smarty.request.tourid} selected {/if} value="0">All Tours</option>
                                {if $tours[$smarty.request.siteid]}
                                {foreach from=$tours[$smarty.request.siteid] item=tour key=tourid}
                                <option {if $smarty.request.tourid==$tourid} selected {/if} value="{$tourid}">{$tour}</option>
                                {/foreach}
                                {/if}
                            </select>
                        </div>
                    </div>

                    <div class="block-inner-columns four-columns-small">
                        <div class="filter-form">
                            <div class="tools"><input type="submit" class="button DisableSubmit" disabled="1" id="filter-submit" value="{#APPLYCHANGES#}"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
        {* End Form *}
    </form>

    {display_program_specific}