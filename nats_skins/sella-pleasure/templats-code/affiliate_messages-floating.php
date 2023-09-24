{*
7271 - Use Default News settings
11064 - Corrected XML links
13061 - quoted double variable assignments
*}
{* What is the preset stats view as *}
{if isset($usr.default_dashboard_period_stats_view_as) && isset($possible_stats_view_as[$usr.default_dashboard_period_stats_view_as])}
{assign var="statsViewAs" value=$possible_stats_view_as[$usr.default_dashboard_period_stats_view_as].short}
{else}
{assign var="statsViewAs" value='table'}
{/if}

{* Setup Javascript necessary for this page *}
{literal}
<script>
    //set our starting stats view
    var StatsTpl = 'function_display_stats_dashboard_{/literal}{$statsViewAs}{literal}';
    var mapField = '{/literal}{$mapFieldId}{literal}';
    var curLink = '';

    //setting the date range for the key stats box
    var keyPeriod = '{/literal}{$usr.default_dashboard_key_stats_range|default:2}{literal}';
    var keyPeriodVal = 10;
    if (keyPeriod == '2') keyPeriodVal = 10;
    else if (keyPeriod == '15') keyPeriodVal = 20;
    else if (keyPeriod == '28') keyPeriodVal = 30;
    else if (keyPeriod == '17') keyPeriodVal = 40;
    else if (keyPeriod == '12') keyPeriodVal = 50;
    else if (keyPeriod == '13') keyPeriodVal = 60;

    //function used to update the view for the period stats summary
    function updateStatsView() {
        //reset the div to loading
        $('#stats-loading').show();
        //hide the stats display
        $('#dashboard-stats').hide();
        var curLink = '';

        //hide the options for the stats views
        $(".stats-view-options").hide();
        //show the options for this view
        $("#" + StatsTpl + "-options").show();

        //load the selected template using ajax
        $('#dashboard-stats').load('internal_data.php?function=nats_display_stats&order=name|1&tpl=' + StatsTpl + curLink, function() {
            //switch this to be the active display
            $('#dashboard-stats').show(0, function() {
                $('#stats-loading').hide();
            });
        });
        //set the selected icon
        $('.dashboard-stats-view img').removeClass('current-view');
        $('.' + StatsTpl + " > img").addClass('current-view');
        return false;
    }

    //function to clear quicklinks on the dashboard
    function removeQuicklink(qid) {
        //remove the bookmark
        $.post('ajax_data.php', {
            'function': 'ajax_update_affiliate_bookmark',
            'bookmark_id': qid
        }, function(data) {
            if (data == 'OK') {
                $(".bkmrk_" + qid).remove();
                if ($("#bkmrkid_" + qid)) {
                    $(".edit_bookmark").attr("id", 'create_bkmrk');
                    $(".edit_bookmark").html('+ {/literal}{#BookmarkThisPage#}{literal}');
                    $(".bookmark-set").removeClass('bookmark-unset');
                }
                $(".bookmark-ul li:first-child").addClass("list-first");
            }
        });
        $(".bkrmrk_tooltip").hide();
        return false;
    }

    //function to toggle the messages for being read
    function toggleMessages(formName) {
        var parts = formName.split('_');

        if ($("." + formName + " div").hasClass('entry-status-none')) {
            var chngfunc = 'viewed';
            var newCls = 'entry-status-viewed';
        } else {
            var chngfunc = 'notviewed';
            var newCls = 'entry-status-none';
        }

        $("." + formName + " div").removeClass('entry-status-none entry-status-viewed');
        $("." + formName + " div").addClass(newCls);

        //revert the value through ajax
        $.post("submit.php?submit_function=submit_message_" + chngfunc + "&variable_array[]=message&message[" + parts[1] + "]=" + parts[2]);
        return false;
    }

    //start the jquery on loads
    $(document).ready(function() {

        //load our default view for the stats block
        $('#dashboard-stats').load('internal_data.php?function=nats_display_stats&order=name|1&tpl=' + StatsTpl + curLink, function() {
            $('#stats-loading').hide();
        });

        //setup our changes for the stats block
        $('.dashboard-stats-view').click(function() {
            //select the template to display
            var newTpl = $(this).attr('id');
            var idParts = newTpl.split('_');
            StatsTpl = "function_display_stats_dashboard_" + idParts[0];
            //call the function to update the view
            updateStatsView();
            //ajax update the setting
            $.post('ajax_data.php', {
                'function': 'ajax_update_affiliate_setting',
                'setting': 'default_dashboard_period_stats_view_as',
                'value': idParts[1]
            });
            return false;
        });

        //build all of our tooltips specific to this page
        $(".summary li .sum-box").tooltip({
            offset: [5, 70],
            predelay: 800,
            effect: 'fade',
            fadeInSpeed: 200,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();
        $(".dashboard-stats-view").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });
        $(".dashboard-stats-view2").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });
        $(".news-headline").tooltip({
            offset: [-10, 200],
            predelay: 600,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();
        $(".remove_bookmark").tooltip({
            offset: [-15, 45],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div class="bkrmrk_tooltip"><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 33]
            }
        });
        $(".tag-data").tooltip({
            offset: [-15, 50],
            predelay: 300,
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();
        $(".noteSubject").tooltip({
            offset: [-10, 150],
            predelay: 300,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

        //setup the popup for setting box options
        $(".box-set-options").click(function() {
            var linkId = $(this).attr('id');
            var linkIdParts = linkId.split('_');

            $(".tooltip, .small-tooltip").hide('fast');

            //grab the content box id
            var contAreaId = $("#" + linkIdParts[0] + "-box .content").attr('id');
            var areaIdParts = contAreaId.split('_');

            //is this box hidden?
            var contHidden = $("#" + linkIdParts[0] + "-box .content").css('display');

            //display popup
            var txt = {
                state0: {
                    html: '<div class="title">{/literal}{#DisplayOptions#}{literal}</div><table class="prompt-table"><tr><td align="right">{/literal}{#Display#}{literal}:</td><td><select name="disp_content" id="newDispayContent">{/literal}{foreach from=$possible_dashboard_single_column_views item="conInfo"}<option value="{$conInfo.short}-{$conInfo.viewid}"' + (areaIdParts[1] == '{$conInfo.viewid}' ? ' selected' : '') + '>{if $conInfo.short == '
                    news '}{$config.NICE_NAME} {/if}{$conInfo.name|convlang}</option>{/foreach}{literal}</select></td></tr><tr><td align="right">{/literal}{#Collapse#}{literal}:</td><td><input type="checkbox" name="hideCont" id="hideContent" value="1"' + (contHidden == 'none' ? ' checked' : '') + '></td></tr></table>',
                    buttons: {
                        '{/literal}{#CANCEL#}{literal}': false,
                        '{/literal}{#APPLYCHANGES#}{literal}': true
                    },
                    focus: 1,
                    submit: function(e, v, m, f) {
                        if (!v) $.prompt.close();
                        else {
                            //what is the name of the new content
                            var linkTitle = $("#newDispayContent option:selected").text();
                            var cValParts = f.disp_content.split('-');

                            //rename the header
                            $("#" + linkIdParts[0] + "-box .heading .hold h2").html(linkTitle);

                            //grab the location parts
                            var posParts = linkIdParts[0].split('-');

                            //ajax update the setting
                            $.post('ajax_data.php', {
                                'function': 'ajax_update_affiliate_setting',
                                'setting': 'default_dashboard_' + posParts[1] + '_' + posParts[2] + '_box_contents',
                                'value': cValParts[1]
                            });

                            //set the id of the content
                            $("#" + linkIdParts[0] + "-box .content").attr('id', areaIdParts[0] + '_' + cValParts[1] + '_' + cValParts[0]);
                            if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 0.3);

                            if (cValParts[0] == 'news') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_news',
                                    'tpl': 'function_display_news_headlines',
                                    'section': 'Announcements',
                                    'count': '7',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'quicklinks') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_quicklinks',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'account_preview') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_account_preview',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'payment_history') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_payment_history',
                                    'tpl': 'function_display_payment_history_preview',
                                    'order': '2',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'notifications' || cValParts[0] == 'messages') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_account_notifications',
                                    'tpl': 'function_display_account_notifications_preview',
                                    'count': '8',
                                    'folder': cValParts[0],
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'top_sites') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_stats',
                                    'tpl': 'function_display_top_stats_preview',
                                    'period': '13',
                                    'breakdown': 'site',
                                    'order': 'total_payout',
                                    'trans_count': '8',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'joins') {
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_stats',
                                    'tpl': 'function_display_stats_joins_preview',
                                    'breakdown': 'members',
                                    'order': 'joined',
                                    'trans_count': '5',
                                    'period': '15',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            } else if (cValParts[0] == 'tag_cloud_traffic' || cValParts[0] == 'tag_cloud_income') {
                                if (cValParts[0] == 'tag_cloud_traffic') var statOrder = 'unique_hits';
                                else var statOrder = 'total_payout';
                                //grab the contents for the section
                                $.post('internal_data.php', {
                                    'function': 'nats_display_stats',
                                    'tpl': 'function_display_stats_tag_cloud',
                                    'breakdown': 'tag',
                                    'order': statOrder,
                                    'trans_count': '30',
                                    'ajaxInc': linkIdParts[0]
                                }, function(data) {
                                    if (data) {
                                        //place the data in the contents
                                        $("#" + linkIdParts[0] + "-box .content").html(data);
                                        if (f.hideCont != "1") $("#" + linkIdParts[0] + "-box .content").fadeTo('fast', 1);
                                    }
                                });
                            }

                            //do we want to show or hide the contents
                            if (f.hideCont == "1") {
                                var setVal = "1";
                                $("#" + linkIdParts[0] + "-box .heading .hold .open-close span").addClass("open-plus");
                                $("#" + linkIdParts[0] + "-box .heading .hold .open-close span").html("+");
                                $("#" + linkIdParts[0] + "-box .content").hide();
                            } else {
                                var setVal = "0";
                                $("#" + linkIdParts[0] + "-box .heading .hold .open-close span").removeClass("open-plus");
                                $("#" + linkIdParts[0] + "-box .heading .hold .open-close span").html("-");
                                $("#" + linkIdParts[0] + "-box .content").show();
                            }

                            //ajax update the setting
                            var curId = $("#" + linkIdParts[0] + "-box .heading .hold .open-close").attr('id');
                            $.post('ajax_data.php', {
                                'function': 'ajax_update_affiliate_setting',
                                'setting': curId,
                                'value': setVal
                            });

                        }
                        $.prompt.close();
                        return false;
                    }
                }
            }
            $.prompt(txt);

            $(".tooltip, .small-tooltip").hide('fast');

            return false;
        });

        //setting the slider to change the key stats box date range
        $("#slider").slider({
            value: keyPeriodVal,
            min: 10,
            max: 60,
            step: 10,
            start: function(event, ui) {
                $(".summary li .sum-box").unbind('mouseenter mouseleave');
            },
            slide: function(event, ui) {
                if (ui.value == 10) curKeyStat = "{/literal}{#TodayKeyStatistics#}{literal}";
                else if (ui.value == 20) curKeyStat = "{/literal}{#Past7DaysKeyStatistics#}{literal}";
                else if (ui.value == 30) curKeyStat = "{/literal}{#Past15DaysKeyStatistics#}{literal}";
                else if (ui.value == 40) curKeyStat = "{/literal}{#Past30DaysKeyStatistics#}{literal}";
                else if (ui.value == 50) curKeyStat = "{/literal}{#Past60DaysKeyStatistics#}{literal}";
                else if (ui.value == 60) curKeyStat = "{/literal}{#Past90DaysKeyStatistics#}{literal}";

                $("#key_stats_title").html(curKeyStat);
            },
            stop: function(event, ui) {

                $('#key_stats_content').fadeTo('fast', 0.3);
                if (ui.value == 10) keyPeriod = '2';
                else if (ui.value == 20) keyPeriod = '15';
                else if (ui.value == 30) keyPeriod = '28';
                else if (ui.value == 40) keyPeriod = '17';
                else if (ui.value == 50) keyPeriod = '12';
                else if (ui.value == 60) keyPeriod = '13';
                $.post('internal_data.php', {
                    'function': 'nats_display_stats',
                    'tpl': 'function_display_stats_dashboard_summary',
                    'period': keyPeriod
                }, function(data) {
                    if (data) {
                        //place the data in the contents
                        $("#key_stats_content").html(data);
                        $('#key_stats_content').fadeTo('fast', 1);
                    }
                });
                //update the sticky setting
                $.post('ajax_data.php', {
                    'function': 'ajax_update_affiliate_setting',
                    'setting': 'default_dashboard_key_stats_range',
                    'value': keyPeriod
                });
            }
        });
    });
</script>
{/literal}

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#WelcomeTo#} {$config.NICE_NAME}, {#PageDesc#}</p>
</div>



{* moved news block out of main block *}

{* (|)(|)(|)(|)(|)(|)(|)|| NEWS (|)(|)(|)(|)(|)(|)(|)(|)| *}

<div class='news-floater-wrap'>
    {*
    News Floater Box
    *}

    {* added for layout *}

    <div class="newsblock-smp">
        <div class="c">
            <div class="box-hold">



                {* end added for layout *}
                <div class="box" id="news-floater-box">
                    <div class="heading">
                        <div class="hold">
                            {*<a href="#" class="btn box-set-options" id="dash-bottom-left_set-options"><span>{#Options#|upper}</span></a>*}
<a href="#" id=""><span></span></a>{* default_dashboard_minimize_bottom_left *}
			<a href="#" class="open-close" id="default_dashboard_minimize_quick_links">{if empty($usr.default_dashboard_minimize_quick_links)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
						{if $curContent.short == 'quicklinks'}
							<a href="/internal.php?page=support&view=NATShelp&section=account&article=ManageQuickLinks#ManageQuickLinks" target="_blank" class="helpbtn" title="{#HelpDashboardQuickLinks#}"><span>?</span></a>
                            {else}
                            {*<a href="/internal.php?page=support&view=NATShelp&section=dashboard&article=CustomizeDash#CustomizeDash" target="_blank" class="helpbtn" title="{#HelpDashboardBoxOptions#}"><span>?</span></a>*}
                            {/if}
                            <h2>{if $curContent.short == 'news'}{$config.NICE_NAME} {/if}{$curContent.name|convlang}</h2>
                        </div>
                    </div>
                    <div class="content" id="blboxCont_{$curContent.viewid}_{$curContent.short}" {if !empty($usr.default_dashboard_minimize_bottom_left)} style="display: none;" {/if}>


                        <!--  OFF QUICKLINKS AND OTHERS -->

                        {if $curContent.short == 'quicklinks'}
                        {* Display the Quick Links *}
                        {include file="nats:include_quicklinks"}
                        {elseif $curContent.short == 'account_preview'}
                        {* Display the Account Preview *}
                        {display_account_preview}
                        {elseif $curContent.short == 'payment_history'}
                        {* Display the Payment Preview *}
                        {display_payment_history tpl="function_display_payment_history_preview" order="2"}


                        {elseif $curContent.short == 'notifications' || $curContent.short == 'messages'}
                        {* Display the Recent Notifications or Messages *}
                        {display_account_notifications tpl="function_display_account_notifications_preview" folder=$curContent.short count="8"}
                        {elseif $curContent.short == 'top_sites'}
                        {* Display the Payment Preview *}
                        {nats_display_stats tpl="function_display_top_stats_preview" period="13" breakdown="site" order="total_payout" trans_count="8"}
                        {elseif $curContent.short == 'tag_cloud_traffic' || $curContent.short == 'tag_cloud_income'}
                        {if $curContent.short == 'tag_cloud_traffic'}{assign var="statOrder" value="unique_hits"}
                        {else}{assign var="statOrder" value="total_payout"}{/if}
                        {* Display the Payment Preview *}
                        {nats_display_stats tpl="function_display_stats_tag_cloud" breakdown="tag" order=$statOrder trans_count="30"}
                        {elseif $curContent.short == 'joins'}
                        {* Display the Payment Preview *}
                        {nats_display_stats tpl="function_display_stats_joins_preview" breakdown="members" order="joined" trans_count="5" period="15"}
                        {else}

                        <!-- end other than news connections -->
                        {* Display the News Headlines *}
                        {* set our default news *}
                        {if $usr.default_news_section}
                        {assign var=newss value=$usr.default_news_section}
                        {assign var=newsc value=$usr.default_news_count}
                        {else}
                        {* there should be a default news section Announcements set in config*}
                        {assign var=newss value=$config.DEFAULT_NEWS_SECTION}
                        {assign var=newsc value=$config.DEFAULT_NEWS_COUNT}
                        {/if}


                        {display_news section=$newss count=$newsc tpl="function_display_news_headlines"}
                        {/if}
                    </div>
                </div>





                {* added for layout *}

            </div>{* class="mainblock" *}
        </div>{* class="c"*}
    </div>{* class="box-hold"*}



    {* end added for layout *}



</div><!-- end news-floater-wrap -->













{* MAIN DISPLAY *}
<div class="mainblock affiliatewelcomepagestats">
    <div class="c">
        <div class="box-hold">


            {* ORIGINAL LOCATION OF QUICK LINKS - Top Left Box *}
            {* moved quick links decision maker down to 2nd row*}

            {* hide blocks of text *}
            {if 0}

            {/if}{* end hide blocks of text *}







            {* Today's Stats *}
            <div class="box" id="dash-top-right-box">
                <div class="heading">
                    <div class="hold">
                        <a href="#" class="open-close" id="default_dashboard_minimize_key_stats">{if empty($usr.default_dashboard_minimize_key_stats)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
                        <a href="/internal.php?page=support&view=NATShelp&section=dashboard&article=KeyStats#KeyStats" target="_blank" class="helpbtn" title="{#HelpDashboardKeyStats#}"><span>?</span></a>
                        <h2 id="key_stats_title">{if isset($usr.default_dashboard_key_stats_range)}{assign var="perName" value=$available_periods[$usr.default_dashboard_key_stats_range]}{assign var="keystatname" value=" Key Statistics"}{assign var="keystatname" value="$perName$keystatname"}{$keystatname|convlang}{else}{#TodayKeyStatistics#}{/if}</h2>
                    </div>
                </div>
                <div class="content" {if !empty($usr.default_dashboard_minimize_key_stats)} style="display: none;" {/if}>
                    <div class="timerangewrap">
                        <div class="small_title">
                            <div class="desc"><strong>{#TimeRange#}:</strong> <em>(TODAY)</em> </div>
                            <div id="slider"></div>
                        </div>
                    </div>{*timerangewrap*}
                    <div class="c" id="key_stats_content">
                        {* Display summary of today's Stats *}
                        {assign var="StatsSummaryCalled" value="1"}
                        {display_stats tpl="function_display_stats_dashboard_summary" period=$usr.default_dashboard_key_stats_range|default:'2'}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



{*
***************************************************************************
lower - upper
***************************************************************************
*}


{* Spacer between Boxes *}
<div class="clear-separator"></div>


{* Setup Three Column display *}
<div class="threeblocks">
    <div class="c">
        <div class="box-hold">



            {if isset($usr.default_dashboard_top_left_box_contents) && isset($possible_dashboard_single_column_views[$usr.default_dashboard_top_left_box_contents])}
            {assign var="curContent" value=$possible_dashboard_single_column_views[$usr.default_dashboard_top_left_box_contents]}
            {else}

            {assign var="curContent" value=$possible_dashboard_single_column_views.2}
            {/if}

            {*
            Quicklinks - on btm row
            *}
            <div class="box first-child quicklinks" id="dash-bottom-left-box">
                <div class="heading">
                    <div class="hold">


                        <a href="#" class="btn box-set-options" id="dash-bottom-left_set-options"><span>{#Options#|upper}</span></a>
			<a href="#" class="open-close" id="default_dashboard_minimize_bottom_left">{if empty($usr.default_dashboard_minimize_bottom_left)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
						{if $curContent.short == 'quicklinks'}
							<a href="/internal.php?page=support&view=NATShelp&section=account&article=ManageQuickLinks#ManageQuickLinks" target="_blank" class="helpbtn" title="{#HelpDashboardQuickLinks#}"><span>?</span></a>
                        {else}
                        <a href="/internal.php?page=support&view=NATShelp&section=dashboard&article=CustomizeDash#CustomizeDash" target="_blank" class="helpbtn" title="{#HelpDashboardBoxOptions#}"><span>?</span></a>
                        {/if}
                        <h2>{if $curContent.short == 'news'}{$config.NICE_NAME} {/if}{$curContent.name|convlang}</h2>
                    </div>
                </div>
                <div class="content" id="blboxCont_{$curContent.viewid}_{$curContent.short}" {if !empty($usr.default_dashboard_minimize_bottom_left)} style="display: none;" {/if}>


                    <!--  OFF QUICKLINKS AND OTHERS -->

                    {if $curContent.short == 'quicklinks'}
                    {* Display the Quick Links *}
                    {include file="nats:include_quicklinks"}
                    {elseif $curContent.short == 'account_preview'}
                    {* Display the Account Preview *}
                    {display_account_preview}
                    {elseif $curContent.short == 'payment_history'}
                    {* Display the Payment Preview *}
                    {display_payment_history tpl="function_display_payment_history_preview" order="2"}


                    {elseif $curContent.short == 'notifications' || $curContent.short == 'messages'}
                    {* Display the Recent Notifications or Messages *}
                    {display_account_notifications tpl="function_display_account_notifications_preview" folder=$curContent.short count="8"}
                    {elseif $curContent.short == 'top_sites'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_top_stats_preview" period="13" breakdown="site" order="total_payout" trans_count="8"}
                    {elseif $curContent.short == 'tag_cloud_traffic' || $curContent.short == 'tag_cloud_income'}
                    {if $curContent.short == 'tag_cloud_traffic'}{assign var="statOrder" value="unique_hits"}
                    {else}{assign var="statOrder" value="total_payout"}{/if}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_tag_cloud" breakdown="tag" order=$statOrder trans_count="30"}
                    {elseif $curContent.short == 'joins'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_joins_preview" breakdown="members" order="joined" trans_count="5" period="15"}
                    {else}

                    <!-- end other than news connections -->
                    {* Display the News Headlines *}
                    {* set our default news *}
                    {if $usr.default_news_section}
                    {assign var=newss value=$usr.default_news_section}
                    {assign var=newsc value=$usr.default_news_count}
                    {else}
                    {* there should be a default news section Announcements set in config*}
                    {assign var=newss value=$config.DEFAULT_NEWS_SECTION}
                    {assign var=newsc value=$config.DEFAULT_NEWS_COUNT}
                    {/if}


                    {display_news section=$newss count=$newsc tpl="function_display_news_headlines"}
                    {/if}
                </div>
            </div>


























            {if isset($usr.default_dashboard_bottom_left_box_contents) && isset($possible_dashboard_single_column_views[$usr.default_dashboard_bottom_left_box_contents])}
            {assign var="curContent" value=$possible_dashboard_single_column_views[$usr.default_dashboard_bottom_left_box_contents]}
            {else}
            {* Default to News *}
            {assign var="curContent" value=$possible_dashboard_single_column_views.1}
            {/if}
            {* Bottom Left Box *}


            {if isset($usr.default_dashboard_bottom_middle_box_contents) && isset($possible_dashboard_single_column_views[$usr.default_dashboard_bottom_middle_box_contents])}
            {assign var="curContent" value=$possible_dashboard_single_column_views[$usr.default_dashboard_bottom_middle_box_contents]}
            {else}
            {* Default to Account *}
            {assign var="curContent" value=$possible_dashboard_single_column_views.3}
            {/if}
            {* Bottom Middle Box *}
            <div class="box" id="dash-bottom-middle-box">
                <div class="heading">
                    <div class="hold">
                        <a href="#" class="btn box-set-options" id="dash-bottom-middle_set-options"><span>{#Options#|upper}</span></a>
						<a href="#" class="open-close" id="default_dashboard_minimize_bottom_middle">{if empty($usr.default_dashboard_minimize_bottom_middle)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
						{if $curContent.short == 'quicklinks'}
							<a href="/internal.php?page=support&view=NATShelp&section=account&article=ManageQuickLinks#ManageQuickLinks" target="_blank" class="helpbtn" title="{#HelpDashboardQuickLinks#}"><span>?</span></a>
                        {else}
                        <a href="/internal.php?page=support&view=NATShelp&section=dashboard&article=CustomizeDash#CustomizeDash" target="_blank" class="helpbtn" title="{#HelpDashboardBoxOptions#}"><span>?</span></a>
                        {/if}
                        <h2>{if $curContent.short == 'news'}{$config.NICE_NAME} {/if}{$curContent.name|convlang}</h2>
                    </div>
                </div>
                <div class="content" id="bmboxCont_{$curContent.viewid}_{$curContent.short}" {if !empty($usr.default_dashboard_minimize_bottom_middle)} style="display: none;" {/if}>
                    {if $curContent.short == 'quicklinks'}
                    {* Display the Quick Links *}
                    {include file="nats:include_quicklinks"}
                    {elseif $curContent.short == 'account_preview'}
                    {* Display the Account Preview *}
                    {display_account_preview}
                    {elseif $curContent.short == 'payment_history'}
                    {* Display the Payment Preview *}
                    {display_payment_history tpl="function_display_payment_history_preview" order="2"}
                    {elseif $curContent.short == 'notifications' || $curContent.short == 'messages'}
                    {* Display the Recent Notifications or Messages *}
                    {display_account_notifications tpl="function_display_account_notifications_preview" folder=$curContent.short count="8"}
                    {elseif $curContent.short == 'top_sites'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_top_stats_preview" period="13" breakdown="site" order="total_payout" trans_count="8"}
                    {elseif $curContent.short == 'tag_cloud_traffic' || $curContent.short == 'tag_cloud_income'}
                    {if $curContent.short == 'tag_cloud_traffic'}{assign var="statOrder" value="unique_hits"}
                    {else}{assign var="statOrder" value="total_payout"}{/if}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_tag_cloud" breakdown="tag" order=$statOrder trans_count="30"}
                    {elseif $curContent.short == 'joins'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_joins_preview" breakdown="members" order="joined" trans_count="5" period="15"}
                    {else}
                    {* Display the News Headlines *}
                    {* set our default news *}
                    {if $usr.default_news_section}
                    {assign var=newss value=$usr.default_news_section}
                    {assign var=newsc value=$usr.default_news_count}
                    {else}
                    {* there should be a default news section Announcements set in config*}
                    {assign var=newss value=$config.DEFAULT_NEWS_SECTION}
                    {assign var=newsc value=$config.DEFAULT_NEWS_COUNT}
                    {/if}

                    {display_news section=$newss count=$newsc tpl="function_display_news_headlines"}
                    {/if}
                </div>
            </div>

            {if isset($usr.default_dashboard_bottom_right_box_contents) && isset($possible_dashboard_single_column_views[$usr.default_dashboard_bottom_right_box_contents])}
            {assign var="curContent" value=$possible_dashboard_single_column_views[$usr.default_dashboard_bottom_right_box_contents]}
            {else}
            {* Default to Quicklinks *}
            {assign var="curContent" value=$possible_dashboard_single_column_views.4}
            {/if}
            {* Bottom Right Box *}
            <div class="box" id="dash-bottom-right-box">
                <div class="heading">
                    <div class="hold">
                        <a href="#" class="btn box-set-options" id="dash-bottom-right_set-options"><span>{#Options#|upper}</span></a>
						<a href="#" class="open-close" id="default_dashboard_minimize_bottom_right">{if empty($usr.default_dashboard_minimize_bottom_right)}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
						{if $curContent.short == 'quicklinks'}
							<a href="/internal.php?page=support&view=NATShelp&section=account&article=ManageQuickLinks#ManageQuickLinks" target="_blank" class="helpbtn" title="{#HelpDashboardQuickLinks#}"><span>?</span></a>
                        {else}
                        <a href="/internal.php?page=support&view=NATShelp&section=dashboard&article=CustomizeDash#CustomizeDash" target="_blank" class="helpbtn" title="{#HelpDashboardBoxOptions#}"><span>?</span></a>
                        {/if}
                        <h2>{if $curContent.short == 'news'}{$config.NICE_NAME} {/if}{$curContent.name|convlang}</h2>
                    </div>
                </div>
                <div class="content" id="brboxCont_{$curContent.viewid}_{$curContent.short}" {if !empty($usr.default_dashboard_minimize_bottom_right)} style="display: none;" {/if}>
                    {if $curContent.short == 'quicklinks'}
                    {* Display the Quick Links *}
                    {include file="nats:include_quicklinks"}
                    {elseif $curContent.short == 'account_preview'}
                    {* Display the Account Preview *}
                    {display_account_preview}
                    {elseif $curContent.short == 'payment_history'}
                    {* Display the Payment Preview *}
                    {display_payment_history tpl="function_display_payment_history_preview" order="2"}
                    {elseif $curContent.short == 'notifications' || $curContent.short == 'messages'}
                    {* Display the Recent Notifications or Messages *}
                    {display_account_notifications tpl="function_display_account_notifications_preview" folder=$curContent.short count="8"}
                    {elseif $curContent.short == 'top_sites'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_top_stats_preview" period="13" breakdown="site" order="total_payout" trans_count="8"}
                    {elseif $curContent.short == 'tag_cloud_traffic' || $curContent.short == 'tag_cloud_income'}
                    {if $curContent.short == 'tag_cloud_traffic'}{assign var="statOrder" value="unique_hits"}
                    {else}{assign var="statOrder" value="total_payout"}{/if}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_tag_cloud" breakdown="tag" order=$statOrder trans_count="30"}
                    {elseif $curContent.short == 'joins'}
                    {* Display the Payment Preview *}
                    {nats_display_stats tpl="function_display_stats_joins_preview" breakdown="members" order="joined" trans_count="5" period="15"}
                    {else}
                    {* Display the News Headlines *}
                    {* set our default news *}
                    {if $usr.default_news_section}
                    {assign var=newss value=$usr.default_news_section}
                    {assign var=newsc value=$usr.default_news_count}
                    {else}
                    {* there should be a default news section Announcements set in config*}
                    {assign var=newss value=$config.DEFAULT_NEWS_SECTION}
                    {assign var=newsc value=$config.DEFAULT_NEWS_COUNT}
                    {/if}

                    {display_news section=$newss count=$newsc tpl="function_display_news_headlines"}
                    {/if}
                </div>
            </div>
        </div>
    </div>
    <div class="b"></div>
</div>