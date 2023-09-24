{use_language_file section='graphs'}

{* Get our Sticky Settings *}
{if isset($usr.default_dashboard_multichart_comparison_range)}{assign var="compRange" value=$usr.default_dashboard_multichart_comparison_range}
{else}{assign var="compRange" value="0"}{/if}
{if isset($usr.default_dashboard_multichart_traffic_fields)}{assign var="trafficFields" value=$usr.default_dashboard_multichart_traffic_fields}
{else}{assign var="trafficFields" value="0:3:"}{/if}
{add_to_array array="tfields" delim=":" add=$trafficFields}
{if isset($usr.default_dashboard_multichart_income_fields)}{assign var="incomeFields" value=$usr.default_dashboard_multichart_income_fields}
{else}{assign var="incomeFields" value="6:"}{/if}
{add_to_array array="ifields" delim=":" add=$incomeFields}

{* Prep Our data for our Chart *}
{prepare_graph_data stats=$stats.date breakdown=date chart_type=line assign_prefix=line1_ build_params=$params fields='total_join_count,total_rebill_count,total_refund_count,unique_hits,join_hits,join_submits,total_join_payout,total_rebill_payout,total_other_payout,ccbill_total_join_count,ccbill_total_rebill_count,ccbill_total_refund_count'}

{* Get all of our Configs for our Chart *}
{display_flash_chart data=$line1_data labels=$line1_labels chart_type="dashboard_stats_traffic" breakdown="date" fields='ccbill_total_join_count,total_join_count,total_rebill_count,total_refund_count,unique_hits,join_hits,join_submits,total_join_payout,total_rebill_payout,total_other_payout,ccbill_total_join_count,ccbill_total_rebill_count,ccbill_total_refund_count'}

{* Get all of our Configs for our Chart 2 *}
{display_flash_chart assign_prefix="income_" data=$line1_data labels=$line1_labels chart_type="dashboard_stats_income" breakdown="date" fields='total_join_count,total_rebill_count,total_refund_count,unique_hits,join_hits,join_submits,total_join_payout,total_rebill_payout,total_other_payout'}

{* Setup Javascript necessary for this template *}
<script language="JavaScript" src="jscript/FusionCharts.js"></script>
<script>
    {
        if $compRange
    }
    var comparison = "&compareData={$compRange}";
    {
        else
    }
    var comparison = '';
    {
        /if}
        var trafficFields = '{$trafficFields}';
        var incomeFields = '{$incomeFields}';
        var chartId = "{$chart_id}";
        var chart1 = new FusionCharts("charts/MSCombiDY2D.swf", chartId, "940", "250", "0", "1");
        chart1.setXMLUrl("chart_xml.php?chart=" + chartId + comparison + "&dispData=" + trafficFields);
        {
            literal
        }
        chart1.configure({
                    {
                        /literal}
                        "ChartNoDataText": "{#ChartNoDataText#}",
                        "PBarLoadingText": "{#PBarLoadingText#}",
                        "XMLLoadingText": "{#XMLLoadingText#}",
                        "ParsingDataText": "{#ParsingDataText#}",
                        "RenderingChartText": "{#RenderingChartText#}",
                        "LoadDataErrorText": "{#LoadDataErrorText#}",
                        "InvalidXMLText": "{#InvalidXMLText#}" {
                            literal
                        }
                    });
                {
                    /literal}
                    chart1.render("container_" + chartId);

                    var chartId2 = "{$income_chart_id}";
                    var chart2 = new FusionCharts("charts/MSStackedColumn2D.swf", chartId2, "940", "250", "0", "1");
                    chart2.setXMLUrl("chart_xml.php?chart=" + chartId2 + comparison + "&dispData=" + incomeFields);
                    {
                        literal
                    }
                    chart2.configure({
                                {
                                    /literal}
                                    "ChartNoDataText": "{#ChartNoDataText#}",
                                    "PBarLoadingText": "{#PBarLoadingText#}",
                                    "XMLLoadingText": "{#XMLLoadingText#}",
                                    "ParsingDataText": "{#ParsingDataText#}",
                                    "RenderingChartText": "{#RenderingChartText#}",
                                    "LoadDataErrorText": "{#LoadDataErrorText#}",
                                    "InvalidXMLText": "{#InvalidXMLText#}" {
                                        literal
                                    }
                                });
                            {
                                /literal}
                                chart2.render("container_" + chartId2);

                                {
                                    literal
                                }

                                function myChartListener(eventObject, argumentsObject) {
                                    $("#container_" + eventObject.sender.id).fadeTo('fast', 1);
                                }
                                FusionCharts(chartId).addEventListener("DrawComplete", myChartListener);
                                FusionCharts(chartId2).addEventListener("DrawComplete", myChartListener);

                                //start the jquery on loads
                                $(document).ready(function() {
                                    $(".chartOptions").click(function() {
                                        var mainCurId = $(this).attr('id').split('_');

                                        if (mainCurId[0] == 'compare') {
                                            $(".compareOptions").removeClass('opt-enabled');
                                            $(this).addClass('opt-enabled');
                                        } else {
                                            var compViewId = Number(mainCurId[1]) + 13;
                                            if ($(this).hasClass('opt-enabled')) {
                                                $(this).removeClass('opt-enabled');
                                                $(".ckey_" + mainCurId[1] + "_" + mainCurId[2]).removeClass('graph-opt-' + mainCurId[1]);
                                                $(".ckey_" + mainCurId[1] + "_" + mainCurId[2] + "_compare").removeClass('compare-' + compViewId);
                                            } else {
                                                $(this).addClass('opt-enabled');
                                                $(".ckey_" + mainCurId[1] + "_" + mainCurId[2]).addClass('graph-opt-' + mainCurId[1]);
                                                $(".ckey_" + mainCurId[1] + "_" + mainCurId[2] + "_compare").removeClass('compare-' + compViewId);
                                            }
                                        }

                                        var firstView = new Array();
                                        var extraVars = '&dispData=';
                                        var extraVars2 = '&dispData=';
                                        var multView = true;
                                        var fieldVars = '';
                                        var fieldVars2 = '';

                                        $(".compareOptions").each(function() {
                                            if ($(this).hasClass('opt-enabled')) {
                                                var curId = $(this).attr('id').split('_');
                                                if (curId[3] != 'off') {
                                                    extraVars = '&compareData=' + curId[3] + '&dispData=';
                                                    extraVars2 = '&compareData=' + curId[3] + '&dispData=';
                                                    multView = false;
                                                    //ajax update the setting
                                                    $.post('ajax_data.php', {
                                                        'function': 'ajax_update_affiliate_setting',
                                                        'setting': 'default_dashboard_multichart_comparison_range',
                                                        'value': curId[3]
                                                    });
                                                } else {
                                                    //ajax update the setting
                                                    $.post('ajax_data.php', {
                                                        'function': 'ajax_update_affiliate_setting',
                                                        'setting': 'default_dashboard_multichart_comparison_range',
                                                        'value': 0
                                                    });
                                                }
                                            }
                                        });

                                        firstView[1] = '-1';
                                        firstView[2] = '-1';
                                        firstView[3] = '-1';
                                        if (mainCurId[0] == 'copt' && multView == false) {
                                            firstView[mainCurId[2]] = mainCurId[1];
                                        }

                                        if (mainCurId[0] == 'compare' || mainCurId[2] != 3) $("#container_" + chartId).fadeTo('fast', 0.3);
                                        if (mainCurId[0] == 'compare' || mainCurId[2] == 3) $("#container_" + chartId2).fadeTo('fast', 0.3);

                                        //what info are we displaying?
                                        $(".viewOpt_" + chartId).each(function() {
                                            if ($(this).hasClass('opt-enabled')) {
                                                var curId = $(this).attr('id').split('_');
                                                var compViewId = Number(curId[1]) + 13;
                                                if (multView || firstView[curId[2]] < 0 || firstView[curId[2]] == curId[1]) {
                                                    fieldVars += curId[1] + ":";
                                                    firstView[curId[2]] = curId[1];
                                                    if (!multView) {
                                                        $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").addClass('compare-' + compViewId);
                                                    } else {
                                                        $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").removeClass('compare-' + compViewId);
                                                    }
                                                } else {
                                                    $(this).removeClass('opt-enabled');
                                                    $(".ckey_" + curId[1] + "_" + curId[2]).removeClass('graph-opt-' + curId[1]);
                                                    $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").removeClass('compare-' + compViewId);
                                                }
                                            }
                                        });
                                        $(".viewOpt_" + chartId2).each(function() {
                                            var curId = $(this).attr('id').split('_');
                                            var compViewId = Number(curId[1]) + 13;
                                            if ($(this).hasClass('opt-enabled')) {
                                                fieldVars2 += curId[1] + ":";
                                                if (!multView) {
                                                    $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").addClass('compare-' + compViewId);
                                                } else {
                                                    $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").removeClass('compare-' + compViewId);
                                                }
                                            } else {
                                                $(".ckey_" + curId[1] + "_" + curId[2]).removeClass('graph-opt-' + curId[1]);
                                                $(".ckey_" + curId[1] + "_" + curId[2] + "_compare").removeClass('compare-' + compViewId);
                                            }
                                        });


                                        //grab the new xml data
                                        if (mainCurId[0] == 'compare' || mainCurId[2] != 3) {
                                            chart1.setXMLUrl("chart_xml.php?chart=" + chartId + extraVars + fieldVars);
                                            $.post('ajax_data.php', {
                                                'function': 'ajax_update_affiliate_setting',
                                                'setting': 'default_dashboard_multichart_traffic_fields',
                                                'value': fieldVars
                                            });
                                        }
                                        if (mainCurId[0] == 'compare' || mainCurId[2] == 3) {
                                            chart2.setXMLUrl("chart_xml.php?chart=" + chartId2 + extraVars2 + fieldVars2);
                                            $.post('ajax_data.php', {
                                                'function': 'ajax_update_affiliate_setting',
                                                'setting': 'default_dashboard_multichart_income_fields',
                                                'value': fieldVars2
                                            });
                                        }

                                        return false;
                                    });
                                });
                                {
                                    /literal}
</script>


<div class="filter-set-display graph_options_box5">
    <strong style="padding-left: 15px;">{#TimeRangeComparison#}:</strong>
    <a class="graph-options small-option chartOptions compareOptions{if !$compRange} opt-enabled{/if}" id="compare_{$chart_id}_off" href="#">{#Off#}</a>
    <a class="graph-options large-option chartOptions compareOptions{if $compRange == 1} opt-enabled{/if}" id="compare_{$chart_id}_1" href="#">{#LastPeriod#}</a>
    <a class="graph-options large-option chartOptions compareOptions{if $compRange == 32} opt-enabled{/if}" id="compare_{$chart_id}_32" href="#">{#OneMonthAgo#}</a>
    <a class="graph-options large-option chartOptions compareOptions{if $compRange == 30} opt-enabled{/if}" id="compare_{$chart_id}_30" href="#">{#OneYearAgo#}</a>
</div>

{* Display the Form and Chart *}
<div class="filter-set-display graph_options_box4">

    <table class="table-container2 small-font" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
        <tr>
            <td class="graph_option_title" nowrap>{#ChartData#}</td>
            <td class="graph_option_title" nowrap>{#ThisPeriod#}</td>
            <td class="graph_option_title" nowrap>{#Comparison#}</td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.3)} opt-enabled{/if}" id="copt_3_1_{$chart_id}" href="#">{#UniqueHits#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_3_1{if isset($tfields.3)} graph-opt-3{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_3_1_compare{if $compRange && isset($tfields.3)} compare-16{/if}">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.4)} opt-enabled{/if}" id="copt_4_1_{$chart_id}" href="#">{#JoinHits#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_4_1{if isset($tfields.4)} graph-opt-4{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_4_1_compare{if $compRange && isset($tfields.4)} compare-17{/if}">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.5)} opt-enabled{/if}" id="copt_5_1_{$chart_id}" href="#">{#Submits#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_5_1{if isset($tfields.5)} graph-opt-5{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-line-display ckey_5_1_compare{if $compRange && isset($tfields.5)} compare-18{/if}">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td class="graph_option_row" style="line-height: 10px;" colspan="3">&nbsp;</td>
        </tr>
        {if !$hide_nonccbill_column}<tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.0)} opt-enabled{/if}" id="copt_0_2_{$chart_id}" href="#">{#Joins#}</a></td>

            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_0_2{if isset($tfields.0)} graph-opt-0{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_0_2{if isset($tfields.0)} graph-opt-0{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_0_2{if isset($tfields.0)} graph-opt-0{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_0_2_compare{if $compRange && isset($tfields.0)} compare-13{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_0_2_compare{if $compRange && isset($tfields.0)} compare-13{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_0_2_compare{if $compRange && isset($tfields.0)} compare-13{/if}">&nbsp;</div>
            </td>
        </tr>{/if}
        {if $config.MOD_CCBILL_PAID} <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.10)} opt-enabled{/if}" id="copt_10_2_{$chart_id}" href="#">CCBill {#Joins#}</a></td>

            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_10_2{if isset($tfields.10)} graph-opt-10{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_10_2{if isset($tfields.10)} graph-opt-10{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_10_2{if isset($tfields.10)} graph-opt-10{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_10_2_compare{if $compRange && isset($tfields.10)} compare-23{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_10_2_compare{if $compRange && isset($tfields.10)} compare-23{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_10_2_compare{if $compRange && isset($tfields.10)} compare-23{/if}">&nbsp;</div>
            </td>
        </tr>
        {/if}
        {if !$hide_nonccbill_column} <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.1)} opt-enabled{/if}" id="copt_1_2_{$chart_id}" href="#">{#Rebills#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_1_2{if isset($tfields.1)} graph-opt-1{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_1_2{if isset($tfields.1)} graph-opt-1{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_1_2{if isset($tfields.1)} graph-opt-1{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_1_2_compare{if $compRange && isset($tfields.1)} compare-14{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_1_2_compare{if $compRange && isset($tfields.1)} compare-14{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_1_2_compare{if $compRange && isset($tfields.1)} compare-14{/if}">&nbsp;</div>
            </td>
        </tr>{/if}
        {if $config.MOD_CCBILL_PAID} <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.1)} opt-enabled{/if}" id="copt_11_2_{$chart_id}" href="#">CCBill {#Rebills#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_11_2{if isset($tfields.11)} graph-opt-11{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_11_2{if isset($tfields.11)} graph-opt-11{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_11_2{if isset($tfields.11)} graph-opt-11{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_11_2_compare{if $compRange && isset($tfields.11)} compare-24{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_11_2_compare{if $compRange && isset($tfields.11)} compare-24{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_11_2_compare{if $compRange && isset($tfields.11)} compare-24{/if}">&nbsp;</div>
            </td>
        </tr>{/if}
        {if !$hide_nonccbill_column} <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.2)} opt-enabled{/if}" id="copt_2_2_{$chart_id}" href="#">{#Refunds#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_2_2{if isset($tfields.2)} graph-opt-2{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_2_2{if isset($tfields.2)} graph-opt-2{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_2_2{if isset($tfields.2)} graph-opt-2{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_2_2_compare{if $compRange && isset($tfields.2)} compare-15{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_2_2_compare{if $compRange && isset($tfields.2)} compare-15{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_2_2_compare{if $compRange && isset($tfields.2)} compare-15{/if}">&nbsp;</div>
            </td>
        </tr>{/if}
        {if $config.MOD_CCBILL_PAID} <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$chart_id}{if isset($tfields.12)} opt-enabled{/if}" id="copt_12_2_{$chart_id}" href="#">CCBill {#Refunds#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_12_2{if isset($tfields.12)} graph-opt-12{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_12_2{if isset($tfields.12)} graph-opt-12{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_12_2{if isset($tfields.12)} graph-opt-12{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_12_2_compare{if $compRange && isset($tfields.12)} compare-25{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_12_2_compare{if $compRange && isset($tfields.12)} compare-25{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_12_2_compare{if $compRange && isset($tfields.12)} compare-25{/if}">&nbsp;</div>
            </td>
        </tr>{/if}

    </table>

    <table class="table-container2 small-font" cellpadding="0" cellspacing="0" style="margin-top: 60px;">
        <tr>
            <td class="graph_option_title" nowrap>{#ChartData#}</td>
            <td class="graph_option_title" nowrap>{#ThisPeriod#}</td>
            <td class="graph_option_title" nowrap>{#Comparison#}</td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$income_chart_id}{if isset($ifields.6)} opt-enabled{/if}" id="copt_6_3_{$income_chart_id}" href="#">{#JoinIncome#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_6_3{if isset($ifields.6)} graph-opt-6{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_6_3{if isset($ifields.6)} graph-opt-6{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_6_3{if isset($ifields.6)} graph-opt-6{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_6_3_compare{if $compRange && isset($ifields.6)} compare-19{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_6_3_compare{if $compRange && isset($ifields.6)} compare-19{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_6_3_compare{if $compRange && isset($ifields.6)} compare-19{/if}">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$income_chart_id}{if isset($ifields.7)} opt-enabled{/if}" id="copt_7_3_{$income_chart_id}" href="#">{#RebillIncome#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_7_3{if isset($ifields.7)} graph-opt-7{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_7_3{if isset($ifields.7)} graph-opt-7{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_7_3{if isset($ifields.7)} graph-opt-7{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_7_3_compare{if $compRange && isset($ifields.7)} compare-20{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_7_3_compare{if $compRange && isset($ifields.7)} compare-20{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_7_3_compare{if $compRange && isset($ifields.7)} compare-20{/if}">&nbsp;</div>
            </td>
        </tr>
        <tr>
            <td class="graph_option_row"><a class="graph-options chartOptions viewOpt_{$income_chart_id}{if isset($ifields.8)} opt-enabled{/if}" id="copt_8_3_{$income_chart_id}" href="#">{#OtherIncome#}</a></td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_8_3{if isset($ifields.8)} graph-opt-8{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_8_3{if isset($ifields.8)} graph-opt-8{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_8_3{if isset($ifields.8)} graph-opt-8{/if}">&nbsp;</div>
            </td>
            <td class="graph_option_row" align="center" valign="center">
                <div class="graphKey-bar1-display ckey_8_3_compare{if $compRange && isset($ifields.8)} compare-21{/if}">&nbsp;</div>
                <div class="graphKey-bar2-display ckey_8_3_compare{if $compRange && isset($ifields.8)} compare-21{/if}">&nbsp;</div>
                <div class="graphKey-bar3-display ckey_8_3_compare{if $compRange && isset($ifields.8)} compare-21{/if}">&nbsp;</div>
            </td>
        </tr>

    </table>

</div>

<div class="graph_box">
    <div id="container_{$chart_id}">{#PBarLoadingText#}</div>
    <div id="container_{$income_chart_id}">{#PBarLoadingText#}</div>
</div>