{*
11064 - Correct XML dump links
12128 - Addeding landing page breakdown
13061 - quoted double variable assignments
*}


{assign var="haganclass" value=""}


<!-- START STATS PAGE -->

{* Make sure we default a view *}
{if isset($smarty.get.view)}
{assign var="view" value=$smarty.get.view}
{elseif isset($usr.default_stats_breakdown) && isset($possible_stats_breakdowns[$usr.default_stats_breakdown])}
{assign var="view" value=$possible_stats_breakdowns[$usr.default_stats_breakdown]}
{else}
{assign var="view" value="date"}
{/if}

{* Setup our Breakdown for Report *}
{if $view == 'refurl'}{assign var="breakdown" value='refurl_lookup_'}
{elseif $view == 'landing_page'}{assign var="breakdown" value='landing_page_lookup_'}
{elseif $view == 'joins' || $view == 'recurring' || $view == 'refunds'}{assign var="breakdown" value='members'}
{elseif $view == 'other'}{assign var="breakdown" value='date'}
{else}{assign var="breakdown" value=$view}{/if}

{* Set the Display Count *}
{if $breakdown == 'refurl_lookup_' || $breakdown == 'landing_page_lookup_'}{assign var="trans_count" value="25"}
{else}{assign var="trans_count" value="0"}{/if}
{if isset($smarty.request.trans_start)}{assign var="trans_start" value=$smarty.request.trans_start}
{else}{assign var="trans_start" value="0"}{/if}

{* Name our View *}
{assign var="niceView" value=$view|ucwords}
{assign var="byview" value="By$niceView"}
{assign var="reportbyview" value="StatisticsReport$byview"}


{if $view == 'joins'}
{assign var="haganclass" value=" statsviewjoin"}
{/if}
{if $view == 'site'}
{assign var="haganclass" value=" statsviewbrand"}
{/if}
{if $view == 'campaign'}
{assign var="haganclass" value=" statsviewcampaign"}
{/if}
{if $view == 'program'}
{assign var="haganclass" value=" statsviewprogram"}
{/if}
{if $view == 'refurl'}
{assign var="haganclass" value=" statsviewrefurl"}
{/if}



{* Select the Template to Display *}
{if $view == 'joins' || $view == 'recurring' || $view == 'members' || $view == 'refunds' || $breakdown == 'members'}
{assign var="dispTpl" value="join_details"}

{elseif $view == 'v3'}
{assign var="dispTpl" value="v3"}
{elseif isset($smarty.request.dispTpl)}
{assign var="dispTpl" value=$smarty.request.dispTpl}
{elseif isset($usr.default_stats_view_as) && isset($possible_stats_view_as[$usr.default_stats_view_as])}
{assign var="dispTpl" value=$possible_stats_view_as[$usr.default_stats_view_as].short}

{* Make sure this View exists for this Breakdown *}
{if $view == 'other' || $breakdown == 'refurl_lookup_' || $breakdown == 'landing_page_lookup_' || (($view == 'date' || $view == 'period' || $view == 'month' || $view == 'year') && $dispTpl != 'linechart') || (($view != 'date' && $view != 'period' && $view != 'month' && $view != 'year') && $dispTpl == 'linechart') || ($breakdown != 'country' && $dispTpl == 'map')}
{assign var="dispTpl" value=""}
{/if}

{else}
{assign var="dispTpl" value=""}
{/if}

{* Setup the Necessary Javascript *}
{literal}
<script>
    //set our starting stats view
    var StatsTpl = 'function_display_stats{/literal}{if $view == '
    other '}_other{/if}{if $dispTpl && $dispTpl != "table"}_{$dispTpl}{/if}{literal}';
    var curFilters = '{/literal}&{rebuild_query using="GET" without="breakdown,dispTpl,tpl,graph,trans_count,trans_start,period,period_start,period_end"}{if $view == '
    other '}&no_hits=1&no_transactions=1{elseif $view == '
    recurring '}&recurring=1{elseif $view == '
    refunds '}&refunds=1{/if}{literal}';
    var StatsPeriod = '{/literal}{if isset($smarty.request.period)}{$smarty.request.period}{else}0{/if}{literal}';
    var StatsStartDate = '{/literal}{if isset($smarty.request.period_start)}{$smarty.request.period_start}{else}0{/if}{literal}';
    var StatsEndDate = '{/literal}{if isset($smarty.request.period_end)}{$smarty.request.period_end}{else}0{/if}{literal}';

    //function used to update the view for the period stats summary
    function updateStatsView(pageStart, pageCount) {
        //make sure we have a pageStart
        if (!pageStart) var pageStart = '{/literal}{$trans_start}{literal}';
        if (pageStart) curFilters = curFilters + '&trans_start=' + pageStart;

        //make sure we have a pageCount
        if (!pageCount) var pageCount = '{/literal}{$trans_count}{literal}';
        if (pageCount) curFilters = curFilters + '&trans_count=' + pageCount;

        //reset the div to loading
        $('#stats-loading').show();
        //hide the stats display
        $('#stats-display').hide();

        //make sure we have a valid breakdown
        if (StatsTpl == 'function_display_stats_map') {
            var curLink = curFilters + '&breakdown=country';
        } else {
            var curLink = curFilters + '&breakdown={/literal}{$breakdown}{literal}';
        }

        //add our date setting
        curLink += '&period=' + StatsPeriod + '&period_start=' + StatsStartDate + '&period_end=' + StatsEndDate;

        //load the selected template using ajax
        $('#stats-display').load('internal_data.php?function=nats_display_stats&tpl=' + StatsTpl + curLink, function() {
            //switch this to be the active display
            $('#stats-display').show(0, function() {
                $('#stats-loading').hide();
            });
        });

        //set the selected icon
        $('.stats-view-as img').removeClass('current-view');
        $('.' + StatsTpl + " > img").addClass('current-view');

        return false;
    }

    //start the jquery on loads
    $(document).ready(function() {

        //set the breakdown options on hover in
        $('.view-btn-on a').hover(function() {
            var colNow = $(this).siblings('ul').css('background-color');
            var colNow2 = $(this).siblings('ul').css('color');
            $(this).css('background-color', colNow);
            $(this).css('color', colNow2);
            $(this).siblings('ul').show();
        });

        //remove the breakdown options on hover out
        $('.desc2').hover(function() {
            //no action on hover in
        }, function() {
            //remove the mouse over color
            $(this).children('.view-btn-on').children('a').css('background-color', '');
            $(this).children('.view-btn-on').children('a').css('color', '');
            //hide the options
            $(this).children('.view-btn-on').children('ul').hide();
        });

        //don't link on the breakdown button
        $('.view-btn-on .noLink').click(function() {
            return false;
        });

        //load our default view for the stats block
        $('#stats-display').load('internal_data.php?function=nats_display_stats&breakdown={/literal}{$breakdown}{literal}&trans_count={/literal}{$trans_count}{if $smarty.request.ccbill_paid}&only_ccbill_paid=TRUE{/if}{if $smarty.request.include_ccbill_paid}&include_ccbill_paid=TRUE{/if}{literal}&tpl=' + StatsTpl + curFilters + '&period=' + StatsPeriod + '&period_start=' + StatsStartDate + '&period_end=' + StatsEndDate, function() {
            $('#stats-loading').hide();
        });

        //setup our changes for the stats block
        $('.stats-view-as').click(function() {

            //select the template to display
            var newTpl = $(this).attr('id');
            var idParts = newTpl.split('_');
            if (idParts[0]) StatsTpl = "function_display_stats_" + idParts[0];
            else StatsTpl = {
                /literal}{if $smarty.request.view=='v3'}'function_display_stats_v3'{else}'function_display_stats'{/if
            } {
                literal
            };

            //update the form
            $("#display-tpl").val(StatsTpl);

            //call the function to update the view
            updateStatsView();

            //ajax update the setting
            $.post('ajax_data.php', {
                'function': 'ajax_update_affiliate_setting',
                'setting': 'default_stats_view_as',
                'value': idParts[1]
            });

            return false;
        });

        //build all of our tooltips specific to this page
        $(".stats-view-as").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });
        $(".stats-view-as2").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });

    });
</script>
{/literal}

{* Page Title *}
<div class="text-block">
    <h1>{$reportbyview|convlang}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{if $smarty.request.view=='v3'}{#PageDescV3#} For reference, any statistics before {$config.DISPLAY_V3_STATS|date_format} can be viewed using this report. For any more recent statistics, please use the normal statistic pages.{else}{#PageDesc#}{/if}</p>
</div>

{* Display the Stats Filters *}
{if $smarty.request.view == 'v3'}{display_stats_form view='v3'}
{else}{display_stats_form}{/if}

{* Box seperator *}
<div class="clear-separator"></div>

{* Display the stats data *}
<div class="mainblock{$haganclass}">
    <div class="heading">
        <div class="hold">
            {* Display Header *}
            <a href="/internal.php?page=support&view=NATShelp&section=stats&article=StatisticDetails#StatisticDetails" target="_blank" class="helpbtn" title="{#HelpStatsStatisticDetails#}"><span>?</span></a>
            <h2 id="main_stats_block_header">{if $period == 8 || $period == 9}{$period_start|date_format:'%Y-%m-%d'} {#thru#} {$period_end|date_format:'%Y-%m-%d'} {#Statistics#}{else}{assign var="niceRange" value=$available_periods[$period]}{assign var="rangeName" value="Statistics"}{assign var="rangeName" value="$niceRange$rangeName"}{$rangeName|convlang}{/if}</h2>
        </div>
    </div>
    <div class="content">
        <div class="c">
            <div class="standard-block">

                {* Header used to describe display and change view *}
                <div class="title title-overflow">
                    <div class="desc2">{if $view == 'date' || $view == 'period' || $view == 'month' || $view == 'year'}

                        <div class="title-overflow">{*{#StatisticsReport#}*} </div>

                        {rebuild_query using="GET" without="tpl,graph,function,view,breakdown" assign="curViewLink"}
                        <div class="breakdown-btn view-btn-on">
                            <a href="#" class="noLink">{if $view == 'period'}{#ByPeriod#}{elseif $view == 'month'}{#ByMonth#}{elseif $view == 'year'}{#ByYear#}{else}{#ByDate#}{/if}</a>
                            <ul>
                                <li><a href="internal.php?{$curViewLink}&view=date">{#ByDate#}</a></li>
                                <li><a href="internal.php?{$curViewLink}&view=period">{#ByPeriod#}</a></li>
                                <li><a href="internal.php?{$curViewLink}&view=month">{#ByMonth#}</a></li>
                                <li><a href="internal.php?{$curViewLink}&view=year">{#ByYear#}</a></li>
                            </ul>
                        </div>


                        {else}{$reportbyview|convlang}{/if}: <strong id="stats_date_display">{if $period_start|date_format == $period_end|date_format}{$period_start|nats_local_date}{else}{$period_start|nats_local_date} - {$period_end|nats_local_date}{/if}</strong>
                    </div>

                    {if $view != 'join' && $breakdown != 'members'}
                    <div class="view">
                        <span>{#ViewAs#}:</span>
                        {* Display the Available Views *}
                        <ul>
                            <li><a href="#" class="stats-view-as function_display_stats" id="_0" title="{#ViewAsTable#}"><img src="nats_images/view-as-table.png" alt="{#ViewAsTable#}" width="16" height="16" {if !$dispTpl || $dispTpl=='table' } class="current-view" {/if} /></a></li>

                            {* Display only the Views for this Breakdown *}
                            {if $view == 'date' || $view == 'period' || $view == 'month' || $view == 'year' || ($view=='v3' && ($smarty.request.grpby=='curdate' || !$smarty.request.grpby))}
                            {* Date Breakdowns Only Use Zoomable Line *}
                            <li><a href="#" class="stats-view-as function_display_stats_linechart" id="linechart_2" title="{#ViewAsLineGraph#}"><img src="nats_images/view-as-line.png" alt="{#ViewAsLineGraph#}" width="16" height="16" {if $dispTpl=='linechart' } class="current-view" {/if} /></a></li>
                            {elseif $breakdown != 'refurl_lookup_' && $breakdown != 'landing_page_lookup_' && $view != 'other'}
                            {* Other Breakdown use Pie Charts *}
                            <li><a href="#" class="stats-view-as function_display_stats_piechart" id="piechart_3" title="{#ViewAsPieChart#}"><img src="nats_images/view-as-pie.png" alt="{#ViewAsPieChart#}" width="16" height="16" {if $dispTpl=='piechart' } class="current-view" {/if} /></a></li>
                            {if $breakdown == 'country'}
                            {* Demographic Breakdown Gets the Maps *}
                            <li><a href="#" class="stats-view-as function_display_stats_map" id="map_4" title="{#ViewAsRegionMap#}"><img src="nats_images/view-as-map.png" alt="{#ViewAsRegionMap#}" width="16" height="16" {if $dispTpl=='map' } class="current-view" {/if} /></a></li>
                            {/if}
                            {/if}

                            {if $usr.rss_pass_code && $usr.remote_access && $view != 'adtool' && $view != 'other'}
                            <li><a href="rss_stats/{$breakdown}/{if isset($over.rss_pass_code)}{$over.rss_pass_code}{else}{$usr.rss_pass_code}{/if}/{if isset($over.usernameclean)}{$over.usernameclean}{else}{$username}{/if}.rss?{rebuild_query using=" GET" without="page" }" class="stats-view-as2" id="feed_0" title="{#ViewAsRSSFeed#}" target="_blank"><img src="nats_images/rssfeed.png" alt="{#ViewAsRSSFeed#}" width="16" height="16" /></a></li>
                            <li><a href="xml_stats/{$breakdown}/{if isset($over.rss_pass_code)}{$over.rss_pass_code}{else}{$usr.rss_pass_code}{/if}/{if isset($over.usernameclean)}{$over.usernameclean}{else}{$username}{/if}.xml?{rebuild_query using=" GET" without="page" }&breakdown={$breakdown}&function=display_stats" class="stats-view-as2" id="xml_1" title="{#ViewAsXMLDump#}" target="_blank"><img src="nats_images/view-as-xml.png" alt="{#ViewAsXMLDump#}" width="16" height="16" /></a></li>
                            {else}
                            <li><a href="internal.php?page=account&enable_rss=1" class="stats-view-as2" id="feed_0" title="{#ViewAsRSSFeed#}" target="_blank"><img src="nats_images/rssfeed.png" alt="{#ViewAsRSSFeed#}" width="16" height="16" /></a></li>
                            <li><a href="internal.php?{rebuild_query using=" GET" without="page" }&page=dump&breakdown={$breakdown}" class="stats-view-as2" id="xml_1" title="{#ViewAsXMLDump#}" target="_blank"><img src="nats_images/view-as-xml.png" alt="{#ViewAsXMLDump#}" width="16" height="16" /></a></li>
                            {/if}

                        </ul>
                    </div>
                    {/if}
                </div>

                {* Load the report data (AJAX LOADED) *}
                <div class="display-content">
                    <img src="nats_images/loading.gif" class="loading" id="stats-loading">
                    <div id="stats-display"></div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- END STATS PAGE -->