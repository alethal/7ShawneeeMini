{*
8289 - Added Adtool hit columns
12128 - Added landing page reporting
13061 - change /100|currency_format: to |currency_format_cents:
NATS-156 - Updated to include new column
*}
{* function_display_stats *}
{* load the language settings *}
{use_language_file section=$smarty.request.page|default:'stats'}

{* Setup Javascript necessary for this template *}
{literal}
<script>
    //start the jquery on loads
    $(document).ready(function() {

        //set the breakdown options on hover in
        $('.break-btn-on a').hover(function() {
            var boxwidth = $('#main').outerWidth();
            var wrapwidth = $('#wrapper').outerWidth();
            if (boxwidth + 100 > wrapwidth) {
                //we want to go left
                $(this).siblings('ul').addClass('page-limit-goleft');
            } else {
                $(this).siblings('ul').removeClass('page-limit-goleft');
            }
            var colNow = $(this).siblings('ul').css('background-color');
            var colNow2 = $(this).siblings('ul').css('color');
            $(this).css('background-color', colNow);
            $(this).css('color', colNow2);
            $(this).siblings('ul').show();
        });

        //remove the breakdown options on hover out
        $('.breakdown-column').hover(function() {
            //no action on hover in
        }, function() {
            //remove the mouse over color
            $(this).children('.break-btn-on').children('a').css('background-color', '');
            $(this).children('.break-btn-on').children('a').css('color', '');
            //hide the options
            $(this).children('.break-btn-on').children('ul').hide();
        });

        //don't link on the breakdown button
        $('.breakdown-btn .noLink').click(function() {
            return false;
        });

        //add our custom sorting function
        var natsSortTextExct = function(node) {
            return $(node).attr('abbr');
        }

        //include the table sorter
        $("#statTable, #statTable2, #refStatTable").tablesorter({
            textExtraction: natsSortTextExct
        });

        //initiate sort on click
        $(".reorder_link").click(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            var tableName = 'statTable';
            var orderHeaderName = 'table-order-header';
            if (colIdParts[2] == 2) {
                tableName = 'statTable2';
                var orderHeaderName = 'table-order-header2';
            }
            var updateSettingId = colIdParts[3];
            var sortOrder = 1;
            var orderClass = 'table-orderby-field';
            if (column == 0) sortOrder = 0;

            //if we already sort by this, switch order
            if ($(this).children('.table-orderby-wrapper').children('.table-orderby-field').css('display') != 'none') {
                sortOrder = 0;
                if (column == 0) sortOrder = 1;
                orderClass = 'table-orderby-field-reverse';
            }

            //set our sort orders
            var sorting = [
                [column, sortOrder],
                [1, sortOrder],
                [2, sortOrder]
            ];

            //remove the current columns accent
            $("." + orderHeaderName).children().children('.reorder_link').children('.table-orderby-wrapper').children('.table-orderby-field').hide();
            $("." + orderHeaderName).children().children('.reorder_link').children('.table-orderby-wrapper').children('.table-orderby-field-reverse').hide();
            $("." + orderHeaderName).children().removeClass('orderby-field');

            //add this columns accent
            $(this).parent().addClass('orderby-field');
            $(this).children('.table-orderby-wrapper').children('.' + orderClass).show();

            //run the sort
            $("#" + tableName).trigger("sorton", [sorting]);

            //reset the odd/even rows and last
            var rowCount = 1;
            var totalRowCount = $("#" + tableName + " > tbody > tr").size();
            $("#" + tableName + " > tbody > tr").each(function() {
                var curClasses = $(this).attr('class');
                var curPointer = $(this);
                var rowOff = false;

                //remove the current classes
                var classList = curClasses.split(' ');
                $.each(classList, function(cId, cName) {
                    if (cName == 'data-row-even-off' || cName == 'data-row-odd-off') rowOff = true;
                    curPointer.removeClass(cName);
                });

                if (rowCount % 2 == 0) {
                    if (rowOff) $(this).addClass('data-row-even-off');
                    else $(this).addClass('data-row-even');
                } else {
                    if (rowOff) $(this).addClass('data-row-odd-off');
                    else $(this).addClass('data-row-odd');
                }

                if (rowCount == totalRowCount) {
                    $(this).addClass('last-row');
                } else {
                    $(this).addClass('hover-row');
                }

                if (rowCount == 1) $(this).addClass('first-row');

                rowCount++;
            });

            //update the affiliate settings
            if (!sortOrder) updateSettingId = Number(updateSettingId) * -1;
            $.post('ajax_data.php', {
                'function': 'ajax_update_affiliate_setting',
                'setting': 'default_stats_order',
                'value': updateSettingId
            });

            return false;
        });

        //enable the submit button on change
        $("#inline-shortname").change(function() {
            $("#inline-search-submit").removeClass('DisableSubmit');
            $("#inline-search-submit").prop('disabled', false);
        });
        //enable the submit button on keyup
        $("#inline-shortname").keyup(function() {
            $("#inline-search-submit").removeClass('DisableSubmit');
            $("#inline-search-submit").prop('disabled', false);
        });

        //remove the search title
        $("#inline-shortname, #inline-search").focus(function() {
            var curVal = $(this).val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $(this).val('');
                $(this).removeClass('DisableEdit');
            }
        });
        $("#inline-shortname, #inline-search").blur(function() {
            var curVal = $(this).val();
            if (curVal == '') {
                $(this).val('{/literal}{#Search#}{literal}...');
                $(this).addClass('DisableEdit');
            }
        });
        $("#inline-search").quicksearch('table#statTable tbody tr', {
            'stripeRows': ['data-row-odd', 'data-row-even']
        });
        $("#inline-search-submit").click(function() {
            var curVal = $('#inline-shortname').val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $('#inline-shortname').val('');
            }
            return true;
        });

        //setup a floating header for scroll down
        $("#statTable, #statTable2, #refStatTable, #landingpageStatTable").floatHeader({
            fadeIn: 0,
            fadeOut: 0,
            recalculate: 1
        });

        //add tooltips
        $(".otherIncome").tooltip({
            offset: [-10, 30],
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();


        //highlight sortable column on hover
        $(".reorder_link").hover(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            var tableName = 'statTable';
            if (colIdParts[2] == 2) tableName = 'statTable2';
            $("#" + tableName + " > tbody > tr").children(".col_" + column).addClass('orderby-hover');
            return false;
        }, function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            var tableName = 'statTable';
            if (colIdParts[2] == 2) tableName = 'statTable2';
            $("#" + tableName + " > tbody > tr").children(".col_" + column).removeClass('orderby-hover');
        });

        //setup the hover affect for the table rows
        $('.hover-row, .last-row').hover(function() {
            if ($(this).prev().hasClass('hover-row')) {
                $(this).prev().addClass('hover-next-row');
                if ($(this).hasClass('two-layer-top')) {
                    if ($(this).hasClass('light-highlight-row')) $(this).next().addClass('hover-matched-highlight-row');
                    else $(this).next().addClass('hover-matched-row');
                    $(this).prev().prev().addClass('hover-next-row');
                } else if ($(this).hasClass('two-layer-bottom')) {
                    if ($(this).hasClass('light-highlight-row')) $(this).prev().addClass('hover-matched-highlight-row');
                    else $(this).prev().addClass('hover-matched-row');
                    if ($(this).prev().prev().hasClass('hover-row')) {
                        $(this).prev().prev().addClass('hover-next-row');
                        $(this).prev().prev().prev().addClass('hover-next-row');
                    } else if ($(this).parent().prev('thead').children('.hover-row')) {
                        $(this).parent().prev('thead').children('.hover-row').addClass('hover-next-row');
                    }
                }
            } else if ($(this).parent().prev('thead').children('.hover-row')) {
                $(this).parent().prev('thead').children('.hover-row').addClass('hover-next-row');
                if ($(this).hasClass('two-layer-top')) {
                    if ($(this).hasClass('light-highlight-row')) $(this).next().addClass('hover-matched-highlight-row');
                    else $(this).next().addClass('hover-matched-row');
                } else if ($(this).hasClass('two-layer-bottom')) {
                    if ($(this).hasClass('light-highlight-row')) $(this).prev().addClass('hover-matched-highlight-row');
                    else $(this).prev().addClass('hover-matched-row');
                }
            }
        }, function() {
            if ($(this).prev().hasClass('hover-row')) {
                $(this).prev().removeClass('hover-next-row');
                if ($(this).hasClass('two-layer-top')) {
                    $(this).next().removeClass('hover-matched-row hover-matched-highlight-row');
                    $(this).prev().prev().removeClass('hover-next-row');
                } else if ($(this).hasClass('two-layer-bottom')) {
                    $(this).prev().removeClass('hover-matched-row hover-matched-highlight-row');
                    if ($(this).prev().prev().hasClass('hover-row')) {
                        $(this).prev().prev().removeClass('hover-next-row');
                        $(this).prev().prev().prev().removeClass('hover-next-row');
                    } else if ($(this).parent().prev('thead').children('.hover-row')) {
                        $(this).parent().prev('thead').children('.hover-row').removeClass('hover-next-row');
                    }
                }
            } else if ($(this).parent().prev('thead').children('.hover-row')) {
                $(this).parent().prev('thead').children('.hover-row').removeClass('hover-next-row');
                if ($(this).hasClass('two-layer-top')) {
                    $(this).next().removeClass('hover-matched-row hover-matched-highlight-row');
                } else if ($(this).hasClass('two-layer-bottom')) {
                    $(this).prev().removeClass('hover-matched-row hover-matched-highlight-row');
                }
            }
        });

    });
</script>
{/literal}


{* Loop Through our Stats Breakdowns (Should Only Be 1) *}
{foreach from=$stats key=curbreak item=data}

{* Build our link to use for Breakdowns *}
{if $curbreak == 'date' || $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}
{rebuild_query using="GET" without="tpl,graph,function,period,period_start,period_end,view,breakdown" assign="curDisplayLink"}
{else}
{assign var="idvar" value="id"}
{rebuild_query using="GET" without="tpl,graph,function,view,breakdown,filter_$curbreak$idvar" assign="curDisplayLink"}
{/if}

{* Build our links for changing order without JS *}
{rebuild_query using="GET" without="order" assign="curOrderLink"}

{* Is this a single day of stats *}
{math assign="tDif" equation="x-y" x=$period_end y=$period_start}
{if $tDif > 100000}{assign var="singleDay" value="0"}
{else}{assign var="singleDay" value="1"}{/if}

{* If this is a demographic or adtool breakdown, show regions/categories *}
{if ($curbreak == 'country' && empty($filters.regionid)) || ($curbreak == 'adtool' && empty($filters.adtypeid))}

{* Build url for drill Down links *}
{rebuild_query using="GET" assign="curFullLink"}

{* Start the table for region or ad type display *}
<table class="table-container small-font tablesorter" cellpadding=0 cellspacing=0 id="statTable2">
    <thead>
        <tr class="header-row2 table-order-header2">
            <td class="tab-column header-first tab-break">
                <a href="#" class="reorder_link" id="reorder_{counter start=0}_2_0">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" style="display: none;"></div>
                        <div class="table-orderby-field-reverse"></div>
                    </div>{if $curbreak == 'country'}{#Region#}{else}{#adtype#}{/if}
                </a>
            </td>
            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid) && $config.SHOW_ADTOOL_HIT_MAIN_BREAKDOWN}
            <td class="tab-column{if $params.order == 'adtool_raw_hit' || $params.order == 'adtool_raw_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_raw_hit" class="reorder_link" id="reorder_{counter}_2_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_raw_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_raw_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'adtool_unique_hit' || $params.order == 'adtool_unique_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_unique_hit" class="reorder_link" id="reorder_{counter}_2_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_unique_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_unique_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Unq#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'raw_hits' || $params.order == 'raw_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=raw_hits" class="reorder_link" id="reorder_{counter}_2_1">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='raw_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='raw_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'unique_hits' || $params.order == 'unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=unique_hits" class="reorder_link" id="reorder_{counter}_2_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Unq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_hits' || $params.order == 'join_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_hits" class="reorder_link" id="reorder_{counter}_2_4">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_hits|1' } style="display: block;" {/if}></div>
                    </div>{#JoinHits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_submits' || $params.order == 'join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_submits" class="reorder_link" id="reorder_{counter}_2_5">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#Submits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:unique_hits:join_submits" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#SubToUnq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#UnqJoinPerc#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_join_count' || $params.order == 'total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_join_count" class="reorder_link" id="reorder_{counter}_2_9">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_join_count|1' } style="display: block;" {/if}></div>
                    </div>{#Joins#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_join_count' || $params.order == 'ccbill_total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_join_count" class="reorder_link" id="reorder_{counter}_2_109">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_join_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Joins#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'ratio:total_join_count:unique_hits' || $params.order == 'ratio:total_join_count:unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:total_join_count:unique_hits" class="reorder_link" id="reorder_{counter}_2_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:total_join_count:unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:total_join_count:unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Ratio#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_rebill_count' || $params.order == 'total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_rebill_count" class="reorder_link" id="reorder_{counter}_2_12">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>{#Rebills#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_rebill_count' || $params.order == 'ccbill_total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_rebill_count" class="reorder_link" id="reorder_{counter}_2_112">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Rebills#}
                </a>
            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_refund_count' || $params.order == 'total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_refund_count" class="reorder_link" id="reorder_{counter}_2_16">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>{#Refunds#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_refund_count' || $params.order == 'ccbill_total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_refund_count" class="reorder_link" id="reorder_{counter}_2_116">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Refunds#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'total_other_payout' || $params.order == 'total_other_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_other_payout" class="reorder_link" id="reorder_{counter}_2_17">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_other_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_other_payout|1' } style="display: block;" {/if}></div>
                    </div>{#OtherIncome#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'total_payout' || $params.order == 'total_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_payout" class="reorder_link" id="reorder_{counter}_2_26">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_payout|1' } style="display: block;" {/if}></div>
                    </div>{#TotalIncome#}
                </a>
            </td>
            <td class="tab-column header-last nohover">{#Breakdown#}</td>
        </tr>
    </thead>
    <tbody>
        {if $curbreak == 'country'}
        {assign var="subtotals" value=$total.continent}
        {assign var="subbreak" value="regionid"}
        {else}
        {assign var=subtotals value=$total.adtype}
        {assign var="subbreak" value="adtypeid"}
        {/if}
        {foreach from=$subtotals item=srow key=sid name=mystats}

        {if !$srow.total_join_count}
        {assign var="all_joins" value="0"}
        {else}
        {assign var="all_joins" value=$srow.total_join_count}
        {/if}

        {if !$srow.ccbill_total_join_count}
        {assign var="all_ccbill_joins" value="0"}
        {else}
        {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
        {/if}

        {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

        {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal data_only=1 assign="unqRatio"}
        {assign var="newLinkProp" value="&filter_$subbreak=$sid"}
        <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if}{if $smarty.foreach.mystats.first} first-row{/if}">
            <td class="tab-column col_{counter start=0} tab-break" abbr="{$srow.breakdown_name|convlang}">
                {if $sid}
                <a href="internal.php?{$curFullLink}{$newLinkProp}">{$srow.breakdown_name|convlang}</a>
                {else}
                {$srow.breakdown_name|convlang}
                {/if}
            </td>
            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid) && $config.SHOW_ADTOOL_HIT_MAIN_BREAKDOWN}
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_raw_hit|default:0}">{$srow.adtool_raw_hit|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_unique_hit|default:0}">
                {if $srow.adtool_unique_hit}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.adtool_unique_hit|number_format:0}</a>
                {else}
                {$srow.adtool_unique_hit|number_format:0}
                {/if}
            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$srow.raw_hits|default:0}">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unique_hits|default:0}">
                {if $srow.unique_hits}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.unique_hits|number_format:0}</a>
                {else}
                {$srow.unique_hits|number_format:0}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_hits|default:0}">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_submits|default:0}">{$srow.join_submits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqSubRatio|default:'0:0'}">{$srow.unqSubRatio|default:'0:0'}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqJoinHitPercent|default:0}">{$srow.unqJoinHitPercent|default:'0%'}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_join_count|default:0}">
                {if $srow.total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}">{$srow.total_join_count|number_format:0}</a>{else}{$srow.total_join_count|number_format:0}{/if}<br>{$srow.total_join_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_join_count|default:0}">
                {$srow.ccbill_total_join_count|number_format:0}
            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$unqRatio}">{$unqRatio}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_rebill_count|default:0}">
                {if $srow.total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring{$newLinkProp}">{$srow.total_rebill_count|number_format:0}</a>{else}{$srow.total_rebill_count|number_format:0}{/if}<br>{$srow.total_rebill_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_rebill_count|default:0}">
                {$srow.ccbill_total_rebill_count|number_format:0}
            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_refund_count|default:0}">
                {if $srow.total_refund_count}<a href="internal.php?{$curDisplayLink}&view=refunds{$newLinkProp}">{$srow.total_refund_count|number_format:0}</a>{else}{$srow.total_refund_count|number_format:0}{/if}<br>{$srow.total_refund_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_refund_count|default:0}">
                {$srow.ccbill_total_refund_count|number_format:0}
            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$srow.total_other_payout|default:0}">
                {if $srow.total_other_payout}
                <a href="internal.php?{$curDisplayLink}&view=other{$newLinkProp}" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if} {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout|currency_format_cents:2}<br>{/if}">{$srow.total_other_payout|currency_format_cents:2}</a>
                {else}
                {$srow.total_other_payout|currency_format_cents:2}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.total_payout|default:0}">{$srow.total_payout|currency_format_cents:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn break-btn-on">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul>
                        {if !$singleDay}
                        <li><a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{#Date#}</a></li>
                        {/if}
                        <li><a href="internal.php?{$curDisplayLink}&view=site{$newLinkProp}">{#Site#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=program{$newLinkProp}">{#Program#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign{$newLinkProp}">{#Campaign#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=tag{$newLinkProp}">{#Tag#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=country{$newLinkProp}">{#Demographic#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool{$newLinkProp}">{#Adtool#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{#ReferringUrl#}</a></li>
                        {if !empty($config.ALLOW_LANDING_PAGE_REPORTING)}
                        <li><a href="internal.php?{$curDisplayLink}&view=landing_page{$newLinkProp}">{#LandingPageUrl#}</a></li>
                        {/if}
                    </ul>
                </div>
            </td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr class="footer-row">
            <td class="tab-column" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">&nbsp;</td>
        </tr>
    </tfoot>
</table>

{* End Region/Adtool Display *}
{/if}

{* Special Display for Referring URL Breakdown *}
{if $curbreak == 'refurl_lookup_'}

{* Display Refurl Count *}
{math equation="x+y" x=$params.trans_start y=$params.trans_count assign='start_count_param'}
{if $total_count[$curbreak] > $start_count_param}
<div class="section_header3">
    {#ShowingURLs#} {$params.trans_start+1} - {if $params.trans_count == 'all' || $params.trans_count == -1 || $start_count_param > $total_count[$curbreak]}{$total_count[$curbreak]}{else}{$start_count_param}{/if} of {$total_count[$curbreak]}
</div>
{/if}

{* Search Stats *}
<form action="internal.php" method="GET" name="inlineSearch">
    {* Include Current Vars *}
    {rebuild_form using=GET without="break_search_refurl_lookup_"}
    <table class="table-container small-font" style="padding-bottom: 0px;" cellpadding=0 cellspacing=0 id="searchTable">
        <tr class="data-row-even no-bottom-line">
            <td class="tab-column left-align tab-break" colspan="{if $config.MOD_CCBILL_PAID && $hide_nonccbill_column}14{else}11{/if}">
                <div class="tools filter-form">
                    <input name="break_search_refurl_lookup_" id="inline-shortname" placeholder="{#Search#}..." value="{if $smarty.request.break_search_refurl_lookup_}{$smarty.request.break_search_refurl_lookup_}{else}{#Search#}...{/if}" class="filter-text">
                </div>
            </td>
            <td class="tab-column left-align tab-break">
                <div class="tools filter-form">
                    <input type=submit class="button DisableSubmit" id="inline-search-submit" disabled="1" value="{#SEARCHSTATS#}">
                </div>
            </td>
        </tr>
    </table>
    {* End Form *}
</form>

{* Start Table for normal Stats Display *}
<table class="table-container small-font tablesorter" cellpadding=0 cellspacing=0 id="statTable">

    {* Display Header Row (Sortable Links) *}
    <thead>
        <tr class="header-row2 table-order-header">
            <td class="tab-column header-first tab-break{if empty($params.order) || $params.order == $curbreak} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}" class="reorder_link" id="reorder_{counter start=0}_0_0">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if !empty($params.order) && $params.order !=$curbreak} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse"></div>
                    </div>{$curbreak|convlang}
                </a>
            </td>
            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column{if $params.order == 'adtool_raw_hit' || $params.order == 'adtool_raw_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_raw_hit" class="reorder_link" id="reorder_{counter}_0_1">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_raw_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_raw_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'adtool_unique_hit' || $params.order == 'adtool_unique_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_unique_hit" class="reorder_link" id="reorder_{counter}_0_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_unique_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_unique_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Unq#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'raw_hits' || $params.order == 'raw_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=raw_hits" class="reorder_link" id="reorder_{counter}_0_1">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='raw_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='raw_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'unique_hits' || $params.order == 'unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=unique_hits" class="reorder_link" id="reorder_{counter}_0_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Unq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_hits' || $params.order == 'join_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_hits" class="reorder_link" id="reorder_{counter}_0_4">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_hits|1' } style="display: block;" {/if}></div>
                    </div>{#JoinHits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_submits' || $params.order == 'join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_submits" class="reorder_link" id="reorder_{counter}_0_5">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#Submits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:unique_hits:join_submits" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#SubToUnq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#UnqJoinPerc#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_join_count' || $params.order == 'total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_join_count" class="reorder_link" id="reorder_{counter}_0_9">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_join_count|1' } style="display: block;" {/if}></div>
                    </div>{#Joins#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_join_count' || $params.order == 'ccbill_total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_join_count" class="reorder_link" id="reorder_{counter}_0_109">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_join_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Joins#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'ratio:total_join_count:unique_hits' || $params.order == 'ratio:total_join_count:unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:total_join_count:unique_hits" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:total_join_count:unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:total_join_count:unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Ratio#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_rebill_count' || $params.order == 'total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_rebill_count" class="reorder_link" id="reorder_{counter}_0_12">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>{#Rebills#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_rebill_count' || $params.order == 'ccbill_total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_rebill_count" class="reorder_link" id="reorder_{counter}_0_112">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Rebills#}
                </a>
            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_refund_count' || $params.order == 'total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_refund_count" class="reorder_link" id="reorder_{counter}_0_16">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>{#Refunds#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_refund_count' || $params.order == 'ccbill_total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_refund_count" class="reorder_link" id="reorder_{counter}_0_116">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Refunds#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'total_other_payout' || $params.order == 'total_other_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_other_payout" class="reorder_link" id="reorder_{counter}_0_17">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_other_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_other_payout|1' } style="display: block;" {/if}></div>
                    </div>{#OtherIncome#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'total_payout' || $params.order == 'total_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_payout" class="reorder_link" id="reorder_{counter}_0_26">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_payout|1' } style="display: block;" {/if}></div>
                    </div>{#TotalIncome#}
                </a>
            </td>
            <td class="tab-column nohover header-last">{#Breakdown#}</td>
        </tr>
    </thead>

    {* Display Table Body (Data Rows) *}
    <tbody>

        {* Loop Through the Data Rows *}
        {foreach from=$data item=srow key=sid name=mystats}

        {* Build the Unique Ratio *}
        {if !$srow.total_join_count}
        {assign var="all_joins" value="0"}
        {else}
        {assign var="all_joins" value=$srow.total_join_count}
        {/if}

        {if !$srow.ccbill_total_join_count}
        {assign var="all_ccbill_joins" value="0"}
        {else}
        {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
        {/if}

        {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

        {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal data_only=1 assign="unqRatio"}


        {* Set this rows link property *}
        {if $curbreak == 'date'}
        {assign var="rowDate" value=$sid|date_format:'%Y-%m-%d'}
        {assign var="newLinkProp" value="&period=8&period_start=$rowDate&period_end=$rowDate"}
        {elseif $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}
        {assign var="rowDate" value=$sid|date_format:'%Y-%m-%d'}
        {time_range_end assign_prefix="rowDate" start=$sid range=$curbreak}
        {assign var="rowDateEnd" value=$rowDateend|date_format:'%Y-%m-%d'}
        {assign var="newLinkProp" value="&period=8&period_start=$rowDate&period_end=$rowDateEnd"}
        {elseif $curbreak == 'tag'}
        {assign var="newLinkProp" value="&tag=$sid"}
        {else}
        {assign var="newLinkProp" value="&filter_$curbreak$idvar=$sid"}
        {/if}

        {* Display Row *}
        <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if}{if $curbreak == 'date'}{if $sid > $smarty.now}-off{elseif $sid >= ($smarty.now - 86400)}-nextoff{/if}{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if}{if $smarty.foreach.mystats.first} first-row{/if}">
            <td class="tab-column col_{counter start=0} tab-break" abbr="{if $curbreak == 'date' || $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}{$sid}{else}{$srow.name}{/if}">
                {* Make sure displaying proper breakdown name *}
                {if $curbreak == 'date' || $curbreak == 'period'}
                {if $sid > $smarty.now}
                {$sid|nats_local_date}
                {else}
                {$sid|nats_local_date}
                {/if}
                {elseif $curbreak == 'month'}
                {$sid|nats_local_date:'%B, %Y'}
                {elseif $curbreak == 'year'}
                {$sid|nats_local_date:'%Y'}
                {elseif $curbreak == 'refurl_lookup_'}
                {if $srow.name != 'No Referring URL'}
                <a href="{$srow.name}" target="_blank">{$srow.name|wordwrap:20:" ":true}</a>
                {else}
                {$srow.name|convlang}
                {/if}
                {elseif isset($srow.name)}
                {if $sid}
                <a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{$srow.name|convlang|replace:",":", "|wordwrap:35:"<br>":true}</a>
                {else}
                {$srow.name|convlang|wordwrap:35:"<br>":true}
                {/if}
                {else}
                {if $sid}
                <a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{$breaks[$sid]|convlang|wordwrap:35:"<br>":true}</a>
                {else}
                {$breaks[$sid]|convlang|wordwrap:35:"<br>":true}
                {/if}
                {/if}
            </td>

            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_raw_hit|default:0}">{$srow.adtool_raw_hit|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_unique_hit|default:0}">
                {if $srow.adtool_unique_hit}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.adtool_unique_hit|number_format:0}</a>
                {else}
                {$srow.adtool_unique_hit|number_format:0}
                {/if}
            </td>
            {/if}


            <td class="tab-column col_{counter}" abbr="{$srow.raw_hits|default:0}">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unique_hits|default:0}">
                {if $srow.unique_hits}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.unique_hits|number_format:0}</a>
                {else}
                {$srow.unique_hits|number_format:0}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_hits|default:0}">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_submits|default:0}">{$srow.join_submits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqSubRatio|default:'0:0'}">{$srow.unqSubRatio|default:'0:0'}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqJoinHitPercent|default:0}">{$srow.unqJoinHitPercent|default:'0%'}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_join_count|default:0}">
                {if $srow.total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}">{$srow.total_join_count|number_format:0}</a>{else}{$srow.total_join_count|number_format:0}{/if}<br>{$srow.total_join_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_join_count|default:0}">{if $srow.ccbill_total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>{else}{$srow.ccbill_total_join_count|number_format:0}{/if}

            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$unqRatio}">{$unqRatio}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_rebill_count|default:0}">
                {if $srow.total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring{$newLinkProp}">{$srow.total_rebill_count|number_format:0}</a>{else}{$srow.total_rebill_count|number_format:0}{/if}<br>{$srow.total_rebill_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_rebill_count|default:0}">
                {if $srow.ccbill_total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring{$newLinkProp}&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>{else}{$srow.ccbill_total_rebill_count|number_format:0}{/if}

            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_refund_count|default:0}">
                {if $srow.total_refund_count}<a href="internal.php?{$curDisplayLink}&view=refunds{$newLinkProp}">{$srow.total_refund_count|number_format:0}</a>{else}{$srow.total_refund_count|number_format:0}{/if}<br>{$srow.total_refund_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_refund_count|default:0}">
                {$srow.ccbill_total_refund_count|number_format:0}
            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$srow.total_other_payout|default:0}">
                {if $srow.total_other_payout}
                <a href="internal.php?{$curDisplayLink}&view=other{$newLinkProp}" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout/100|currency_format:2}<br>{/if} {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout/100|currency_format:2}<br>{/if}">{$srow.total_other_payout/100|currency_format:2}</a>
                {else}
                {$srow.total_other_payout/100|currency_format:2}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.total_payout|default:0}">{$srow.total_payout/100|currency_format:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn{if $curbreak != 'date' || $sid <= $smarty.now} break-btn-on{/if}">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul>
                        {if $curbreak != 'date' && $singleDay == 0}
                        <li><a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{#Date#}</a></li>
                        {/if}
                        {if $curbreak != 'site'}
                        <li><a href="internal.php?{$curDisplayLink}&view=site{$newLinkProp}">{#Site#}</a></li>
                        {/if}
                        {if $curbreak != 'program'}
                        <li><a href="internal.php?{$curDisplayLink}&view=program{$newLinkProp}">{#Program#}</a></li>
                        {/if}
                        {if $curbreak != 'campaign'}
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign{$newLinkProp}">{#Campaign#}</a></li>
                        {/if}
                        {if $curbreak != 'tag'}
                        <li><a href="internal.php?{$curDisplayLink}&view=tag{$newLinkProp}">{#Tag#}</a></li>
                        {/if}
                        {if $curbreak != 'country'}
                        <li><a href="internal.php?{$curDisplayLink}&view=country{$newLinkProp}">{#Demographic#}</a></li>
                        {/if}
                        {if $curbreak != 'adtool'}
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool{$newLinkProp}">{#Adtool#}</a></li>
                        {/if}
                        {if $curbreak != 'refurl'}
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{#ReferringUrl#}</a></li>
                        {/if}
                    </ul>
                </div>
            </td>
        </tr>

        {foreachelse}
        <tr class="data-row-even">
            <td class="tab-column left-align" colspan="{if $config.MOD_CCBILL_PAID && $hide_nonccbill_column}15{else}12{/if}">{#NoStatisticsAvailable#}</td>
        </tr>
        {* End Data Row Loop *}
        {/foreach}

        {* End Table Body *}
    </tbody>


    {* Display Total Row *}
    <tfoot>

        {assign var=srow value=$total[$curbreak]}

        {* Build our link to use for Breakdowns *}
        {if $curbreak == 'date' || $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}
        {rebuild_query using="GET" without="tpl,graph,function,view,breakdown" assign="curDisplayLink"}
        {else}
        {assign var="idvar" value="id"}
        {rebuild_query using="GET" without="tpl,graph,function,view,breakdown,filter_$curbreak$idvar" assign="curDisplayLink"}
        {/if}



        <tr class="footer-row">
            {if $curbreak == 'tag'}
            <td class="tab-column tab-break" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">&nbsp;</td>
            {else}
            <td class="tab-column tab-break">{#Total#}</td>

            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column">{$srow.adtool_raw_hit|number_format:0}</td>
            <td class="tab-column">
                {if $srow.adtool_unique_hit}
                <a href="internal.php?{$curDisplayLink}&view=refurl">{$srow.adtool_unique_hit|number_format:0}</a>
                {else}
                {$srow.adtool_unique_hit|number_format:0}
                {/if}
            </td>
            {/if}


            <td class="tab-column">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column">
                {if $srow.unique_hits}
                <a href="internal.php?{$curDisplayLink}&view=refurl">{$srow.unique_hits|number_format:0}</a>
                {else}
                {$srow.unique_hits|number_format:0}
                {/if}
            </td>
            <td class="tab-column">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column">{$srow.join_submits|number_format:0}</td>
            <td class="tab-column">{$srow.unqSubRatio}</td>
            <td class="tab-column">{$srow.unqJoinHitPercent}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins">{$srow.total_join_count|number_format:0}</a>{else}{$srow.total_join_count|number_format:0}{/if}<br>{$srow.total_join_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{if $srow.ccbill_total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>{else}{$srow.ccbill_total_join_count|number_format:0}{/if}</td>
            {/if}
            <td class="tab-column">
                {if !$srow.total_join_count}
                {assign var="all_joins" value="0"}
                {else}
                {assign var="all_joins" value=$srow.total_join_count}
                {/if}

                {if !$srow.ccbill_total_join_count}
                {assign var="all_ccbill_joins" value="0"}
                {else}
                {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
                {/if}

                {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

                {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring">{$srow.total_rebill_count|number_format:0}</a>{else}{$srow.total_rebill_count|number_format:0}{/if}<br>{$srow.total_rebill_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{if $srow.ccbill_total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>{else}{$srow.ccbill_total_rebill_count|number_format:0}{/if}</td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_refund_count}<a href="internal.php?{$curDisplayLink}&view=refunds">{$srow.total_refund_count|number_format:0}</a>{else}{$srow.total_refund_count|number_format:0}{/if}<br>{$srow.total_refund_payout/100|currency_format:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{$srow.ccbill_total_refund_count|number_format:0}</td>
            {/if}
            <td class="tab-column">
                {if $srow.total_other_payout}
                <a href="internal.php?page=stats&view=other" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout/100|currency_format:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout/100|currency_format:2}<br>{/if} {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout/100|currency_format:2}<br>{/if} ">{$srow.total_other_payout/100|currency_format:2}</a>
                {else}
                {$srow.total_other_payout/100|currency_format:2}
                {/if}
            </td>
            <td class="tab-column">{$srow.total_payout/100|currency_format:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn break-btn-on">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul class="stndrd">
                        {if $curbreak != 'date' && $singleDay == 0}
                        <li><a href="internal.php?{$curDisplayLink}&view=date">{#Date#}</a></li>
                        {/if}
                        {if $curbreak != 'site'}
                        <li><a href="internal.php?{$curDisplayLink}&view=site">{#Site#}</a></li>
                        {/if}
                        {if $curbreak != 'program'}
                        <li><a href="internal.php?{$curDisplayLink}&view=program">{#Program#}</a></li>
                        {/if}
                        {if $curbreak != 'campaign'}
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign">{#Campaign#}</a></li>
                        {/if}
                        {if $curbreak != 'tag'}
                        <li><a href="internal.php?{$curDisplayLink}&view=tag">{#Tag#}</a></li>
                        {/if}
                        {if $curbreak != 'country'}
                        <li><a href="internal.php?{$curDisplayLink}&view=country">{#Demographic#}</a></li>
                        {/if}
                        {if $curbreak != 'adtool'}
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool">{#Adtool#}</a></li>
                        {/if}
                        {if $curbreak != 'refurl'}
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl">{#ReferringUrl#}</a></li>
                        {/if}
                    </ul>
                </div>
            </td>
            {/if}
        </tr>

        <tr class="footer-row">
            <td class="tab-column" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">
                <div class="tools" id="Paginiation">
                    {* Display Pagination *}
                    {assign var="updateFunction" value="updateStatsView"}
                    {pagination start=$params.trans_start count=$params.trans_count total=$total_count[$curbreak] start_field="trans_start" offset="1" tpl="function_display_pagination_ajax"}
                </div>
            </td>
        </tr>
    </tfoot>

    {* End Stats Table *}
</table>

{* End Referring URL Breakdown *}
{* Special Display for Landing Page URL Breakdown *}
{elseif $curbreak == 'landing_page_lookup_' && !empty($config.ALLOW_LANDING_PAGE_REPORTING)}
{* Display Landing Page Count *}
{math equation="x+y" x=$params.trans_start y=$params.trans_count assign='start_count_param'}
{if $total_count[$curbreak] > $start_count_param}
<div class="section_header3">
    {#ShowingURLs#} {$params.trans_start+1} - {if $params.trans_count == 'all' || $params.trans_count == -1 || $start_count_param > $total_count[$curbreak]}{$total_count[$curbreak]}{else}{$start_count_param}{/if} of {$total_count[$curbreak]}
</div>
{/if}

{* Search Stats *}
<form action="internal.php" method="GET" name="inlineSearch">
    {* Include Current Vars *}
    {rebuild_form using=GET without="break_search_landing_page_lookup_"}
    <table class="table-container small-font" style="padding-bottom: 0px;" cellpadding=0 cellspacing=0 id="searchTable">
        <tr class="data-row-even no-bottom-line">
            <td class="tab-column left-align tab-break" colspan="{if $config.MOD_CCBILL_PAID && $hide_nonccbill_column}14{else}11{/if}">
                <div class="tools filter-form">
                    <input name="break_search_landing_page_lookup_" id="inline-shortname" placeholder="{#Search#}..." value="{if $smarty.request.break_search_landing_page_lookup_}{$smarty.request.break_search_landing_page_lookup_}{else}{#Search#}...{/if}" class="filter-text">
                </div>
            </td>
            <td class="tab-column left-align tab-break">
                <div class="tools filter-form">
                    <input type=submit class="button DisableSubmit" id="inline-search-submit" disabled="1" value="{#SEARCHSTATS#}">
                </div>
            </td>
        </tr>
    </table>
    {* End Form *}
</form>

{* Start Table for normal Stats Display *}
<table class="table-container small-font tablesorter" cellpadding=0 cellspacing=0 id="landingpageStatTable" style="width: 100%;">

    <thead>
        <tr class="header-row2 table-order-header">
            {assign var="current_column" value="landing_page_lookup_"}
            <td class="tab-column header-first tab-break{if empty($params.order) || $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if !empty($params.order) && $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{$curbreak|convlang}
                </a>
            </td>
            {assign var="current_column" value="raw_hits"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Raw#}
                </a>
            </td>
            {assign var="current_column" value="unique_hits"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Unq#}
                </a>
            </td>
            {assign var="current_column" value="join_hits"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#JoinHits#}
                </a>
            </td>
            {assign var="current_column" value="join_submits"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Submits#}
                </a>
            </td>
            {if !$hide_nonccbill_column}
            {assign var="current_column" value="total_join_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Joins#}
                </a>
            </td>
            {/if}
            {if $config.MOD_CCBILL_PAID}
            {assign var="current_column" value="ccbill_total_join_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>CCB {#Joins#}
                </a>
            </td>
            {/if}
            {assign var="current_column" value="ratio:total_join_count:unique_hits"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Ratio#}
                </a>
            </td>
            {if !$hide_nonccbill_column}
            {assign var="current_column" value="total_rebill_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Rebills#}
                </a>
            </td>
            {/if}
            {if $config.MOD_CCBILL_PAID}
            {assign var="current_column" value="ccbill_total_rebill_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>CCB {#Rebills#}
                </a>
            </td>
            {/if}
            {if !$hide_nonccbill_column}
            {assign var="current_column" value="total_refund_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#Refunds#}
                </a>
            </td>
            {/if}
            {if $config.MOD_CCBILL_PAID}
            {assign var="current_column" value="ccbill_total_refund_count"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>CCB {#Refunds#}
                </a>
            </td>
            {/if}
            {assign var="current_column" value="total_other_payout"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#OtherIncome#}
                </a>
            </td>
            {assign var="current_column" value="total_payout"}
            <td class="tab-column{if $params.order == $current_column || $params.order == $current_column|cat:'|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order={$current_column}{if $params.order == $current_column}|1{/if}" id="reorder_{counter}">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !=$current_column} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order==$current_column|cat:'|1'} style="display: block;" {/if}></div>
                    </div>{#TotalIncome#}
                </a>
            </td>
            <td class="tab-column nohover header-last">{#Breakdown#}</td>
        </tr>
    </thead>

    <tbody>
        {foreach from=$data item=srow key=sid name=mystats}
        {if !$srow.total_join_count}
        {assign var="all_joins" value="0"}
        {else}
        {assign var="all_joins" value=$srow.total_join_count}
        {/if}

        {if !$srow.ccbill_total_join_count}
        {assign var="all_ccbill_joins" value="0"}
        {else}
        {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
        {/if}

        {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

        {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal data_only=1 assign="unqRatio"}

        {assign var="statid" value=$landing_page_breaks[$sid]}
        {assign var="newLinkProp" value="&filter_landing_page_lookup_id=$statid"}
        <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if} two-layer-top{if $smarty.foreach.mystats.first} first-row{/if}">
            <td class="tab-column tab-break two-layer-top" colspan="{if $config.MOD_CCBILL_PAID && $hide_nonccbill_column}15{else}12{/if}">
                {if $srow.name != 'No Landing Page URL'}
                <a href="{$srow.name}" target="_blank">{$srow.name|wordwrap:200:" ":true}</a>
                {else}
                {$srow.name|convlang}
                {/if}
            </td>
        </tr>
        <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if} two-layer-bottom">
            <td class="tab-column left-align">&nbsp;</td>
            <td class="tab-column" abbr="{$srow.raw_hits|default:0}">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column" abbr="{$srow.unique_hits|default:0}">{$srow.unique_hits|number_format:0}</td>
            <td class="tab-column">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column">{$srow.join_submits|number_format:0}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_join_count}
                <a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}">{$srow.total_join_count|number_format:0}</a>
                {else}
                {$srow.total_join_count|number_format:0}
                {/if}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">
                {if $srow.ccbill_total_join_count}
                <a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>
                {else}
                {$srow.ccbill_total_join_count|number_format:0}
                {/if}
            </td>
            {/if}
            <td class="tab-column">{$unqRatio}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">{$srow.total_rebill_count|number_format:0}</td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{$srow.ccbill_total_rebill_count|number_format:0}</td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column">{$srow.total_refund_count|number_format:0}</td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{$srow.ccbill_total_refund_count|number_format:0}</td>
            {/if}
            <td class="tab-column">{$srow.total_other_payout|currency_format_cents:2}</td>
            <td class="tab-column">{$srow.total_payout|currency_format_cents:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn{if $curbreak != 'date' || $sid <= $smarty.now} break-btn-on{/if}">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul>
                        {if !$singleDay}
                        <li><a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{#Date#}</a></li>
                        {/if}
                        <li><a href="internal.php?{$curDisplayLink}&view=site{$newLinkProp}">{#Site#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=program{$newLinkProp}">{#Program#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign{$newLinkProp}">{#Campaign#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=tag{$newLinkProp}">{#Tag#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=country{$newLinkProp}">{#Demographic#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool{$newLinkProp}">{#Adtool#}</a></li>
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{#ReferringUrl#}</a></li>
                    </ul>
                </div>
            </td>
        </tr>

        {foreachelse}
        <tr class="data-row-even">
            <td class="tab-column left-align" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">{#NoStatisticsAvailable#}</td>
        </tr>
        {/foreach}
    </tbody>
    <tfoot>
        <tr class="footer-row">
            <td class="tab-column" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">
                <div class="tools" id="Paginiation">
                    {* Display Pagination *}
                    {assign var="updateFunction" value="updateStatsView"}
                    {pagination start=$params.trans_start count=$params.trans_count total=$total_count[$curbreak] start_field="trans_start" offset="1" tpl="function_display_pagination_ajax"}
                </div>
            </td>
        </tr>
    </tfoot>
</table>

{* End Landing Page URL Breakdown *}
{else}

{if $curbreak != 'date' && $curbreak != 'period' && $curbreak != 'month' && $curbreak != 'year'}
{* Search Stats *}
<table class="table-container small-font" style="padding-bottom: 0px;" cellpadding=0 cellspacing=0 id="searchTable">
    <tr class="data-row-even no-bottom-line">
        <td class="tab-column left-align tab-break" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">
            <form action="internal.php" method="GET" name="inlineSearch">
                {* Include Current Vars *}
                {assign var="breaksearch" value="break_search_$currentbreak"}
                {rebuild_form using=GET without=$breaksearch}
                <div class="tools filter-form">
                    <input name="break_search_{$curbreak}id}" id="inline-search" placeholder="{#Search#}..." value="{if $smarty.request.$breaksearch}{$smarty.request.$breaksearch}{else}{#Search#}...{/if}" class="filter-text{if empty($smarty.request.$breaksearch)} DisableEdit{/if}">
                </div>
                {* End Form *}
            </form>
        </td>
    </tr>
</table>
{/if}

{* Start Table for normal Stats Display *}
<table class="table-container small-font tablesorter" cellpadding=0 cellspacing=0 id="statTable">

    {* Display Header Row (Sortable Links) *}
    <thead>
        <tr class="header-row2 table-order-header">
            <td class="tab-column header-first tab-break{if empty($params.order) || $params.order == $curbreak} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}" class="reorder_link" id="reorder_{counter start=0}_0_0">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if !empty($params.order) && $params.order !=$curbreak} style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse"></div>
                    </div>{$curbreak|convlang}
                </a>
            </td>
            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column{if $params.order == 'adtool_raw_hit' || $params.order == 'adtool_raw_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_raw_hit" class="reorder_link" id="reorder_{counter}_0_1">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_raw_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_raw_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'adtool_unique_hit' || $params.order == 'adtool_unique_hit|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=adtool_unique_hit" class="reorder_link" id="reorder_{counter}_0_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='adtool_unique_hit' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='adtool_unique_hit|1' } style="display: block;" {/if}></div>
                    </div>{#Adtool#} {#Unq#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'raw_hits' || $params.order == 'raw_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=raw_hits" class="reorder_link" id="reorder_{counter}_0_1">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='raw_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='raw_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Raw#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'unique_hits' || $params.order == 'unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=unique_hits" class="reorder_link" id="reorder_{counter}_0_2">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Unq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_hits' || $params.order == 'join_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_hits" class="reorder_link" id="reorder_{counter}_0_4">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_hits|1' } style="display: block;" {/if}></div>
                    </div>{#JoinHits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'join_submits' || $params.order == 'join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=join_submits" class="reorder_link" id="reorder_{counter}_0_5">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#Submits#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:unique_hits:join_submits" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#SubToUnq#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'ratio:unique_hits:join_submits' || $params.order == 'ratio:unique_hits:join_submits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:unique_hits:join_submits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:unique_hits:join_submits|1' } style="display: block;" {/if}></div>
                    </div>{#UnqJoinPerc#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_join_count' || $params.order == 'total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_join_count" class="reorder_link" id="reorder_{counter}_0_9">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_join_count|1' } style="display: block;" {/if}></div>
                    </div>{#Joins#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_join_count' || $params.order == 'ccbill_total_join_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_join_count" class="reorder_link" id="reorder_{counter}_0_109">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_join_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_join_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Joins#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'ratio:total_join_count:unique_hits' || $params.order == 'ratio:total_join_count:unique_hits|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ratio:total_join_count:unique_hits" class="reorder_link" id="reorder_{counter}_0_19">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ratio:total_join_count:unique_hits' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ratio:total_join_count:unique_hits|1' } style="display: block;" {/if}></div>
                    </div>{#Ratio#}
                </a>
            </td>
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_rebill_count' || $params.order == 'total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_rebill_count" class="reorder_link" id="reorder_{counter}_0_12">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>{#Rebills#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_rebill_count' || $params.order == 'ccbill_total_rebill_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_rebill_count" class="reorder_link" id="reorder_{counter}_0_112">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_rebill_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_rebill_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Rebills#}
                </a>
            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column{if $params.order == 'total_refund_count' || $params.order == 'total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_refund_count" class="reorder_link" id="reorder_{counter}_0_16">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>{#Refunds#}
                </a>
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column{if $params.order == 'ccbill_total_refund_count' || $params.order == 'ccbill_total_refund_count|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=ccbill_total_refund_count" class="reorder_link" id="reorder_{counter}_0_116">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='ccbill_total_refund_count' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='ccbill_total_refund_count|1' } style="display: block;" {/if}></div>
                    </div>CCB {#Refunds#}
                </a>
            </td>
            {/if}
            <td class="tab-column{if $params.order == 'total_other_payout' || $params.order == 'total_other_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_other_payout" class="reorder_link" id="reorder_{counter}_0_17">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_other_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_other_payout|1' } style="display: block;" {/if}></div>
                    </div>{#OtherIncome#}
                </a>
            </td>
            <td class="tab-column{if $params.order == 'total_payout' || $params.order == 'total_payout|1'} orderby-field{/if}">
                <a href="internal.php?{$curOrderLink}&order=total_payout" class="reorder_link" id="reorder_{counter}_0_26">
                    <div class="table-orderby-wrapper">
                        <div class="table-orderby-field" {if $params.order !='total_payout' } style="display: none;" {/if}></div>
                        <div class="table-orderby-field-reverse" {if $params.order=='total_payout|1' } style="display: block;" {/if}></div>
                    </div>{#TotalIncome#}
                </a>
            </td>
            <td class="tab-column nohover header-last">{#Breakdown#}</td>
        </tr>
    </thead>

    {* Display Table Body (Data Rows) *}
    <tbody>

        {* Loop Through the Data Rows *}
        {foreach from=$data item=srow key=sid name=mystats}

        {* Build the Unique Ratio *}
        {if !$srow.total_join_count}
        {assign var="all_joins" value="0"}
        {else}
        {assign var="all_joins" value=$srow.total_join_count}
        {/if}

        {if !$srow.ccbill_total_join_count}
        {assign var="all_ccbill_joins" value="0"}
        {else}
        {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
        {/if}

        {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

        {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal data_only=1 assign="unqRatio"}

        {* Set this rows link property *}
        {if $curbreak == 'date'}
        {assign var="rowDate" value=$sid|date_format:'%Y-%m-%d'}
        {assign var="newLinkProp" value="&period=8&period_start=$rowDate&period_end=$rowDate"}
        {elseif $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}
        {assign var="rowDate" value=$sid|date_format:'%Y-%m-%d'}
        {time_range_end assign_prefix="rowDate" start=$sid range=$curbreak}
        {assign var="rowDateEnd" value=$rowDateend|date_format:'%Y-%m-%d'}
        {assign var="newLinkProp" value="&period=8&period_start=$rowDate&period_end=$rowDateEnd"}
        {elseif $curbreak == 'tag'}
        {assign var="newLinkProp" value="&tag=$sid"}
        {else}
        {assign var="newLinkProp" value="&filter_$curbreak$idvar=$sid"}
        {/if}

        {* Display Row *}
        <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if}{if $curbreak == 'date'}{if $sid > $smarty.now}-off{elseif $sid >= ($smarty.now - 86400)}-nextoff{/if}{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if}{if $smarty.foreach.mystats.first} first-row{/if}">
            <td class="tab-column col_{counter start=0} tab-break" abbr="{if $curbreak == 'date' || $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}{$sid}{else}{$srow.name}{/if}">
                {* Make sure displaying proper breakdown name *}
                {if $curbreak == 'date' || $curbreak == 'period'}
                {if $sid > $smarty.now}
                {$sid|nats_local_date}
                {else}
                {$sid|nats_local_date}
                {/if}
                {elseif $curbreak == 'month'}
                {$sid|nats_local_date:'%B, %Y'}
                {elseif $curbreak == 'year'}
                {$sid|nats_local_date:'%Y'}
                {elseif isset($srow.name)}
                {if $sid}
                <a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{$srow.name|convlang|replace:",":", "|wordwrap:35:"<br>":true}</a>
                {else}
                {$srow.name|convlang|wordwrap:35:"<br>":true}
                {/if}
                {else}
                {if $sid}
                <a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{$breaks[$sid]|convlang|wordwrap:35:"<br>":true}</a>
                {else}
                {$breaks[$sid]|convlang|wordwrap:35:"<br>":true}
                {/if}
                {/if}
            </td>

            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_raw_hit|default:0}">{$srow.adtool_raw_hit|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.adtool_unique_hit|default:0}">
                {if $srow.adtool_unique_hit}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.adtool_unique_hit|number_format:0}</a>
                {else}
                {$srow.adtool_unique_hit|number_format:0}
                {/if}
            </td>
            {/if}


            <td class="tab-column col_{counter}" abbr="{$srow.raw_hits|default:0}">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unique_hits|default:0}">
                {if $srow.unique_hits}
                <a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{$srow.unique_hits|number_format:0}</a>
                {else}
                {$srow.unique_hits|number_format:0}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_hits|default:0}">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.join_submits|default:0}">{$srow.join_submits|number_format:0}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqSubRatio|default:'0:0'}">{$srow.unqSubRatio|default:'0:0'}</td>
            <td class="tab-column col_{counter}" abbr="{$srow.unqJoinHitPercent|default:0}">{$srow.unqJoinHitPercent|default:'0%'}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_join_count|default:0}">
                {if $srow.total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}">{$srow.total_join_count|number_format:0}</a>{else}{$srow.total_join_count|number_format:0}{/if}<br>{$srow.total_join_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_join_count|default:0}">{if $srow.ccbill_total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins{$newLinkProp}&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>{else}{$srow.ccbill_total_join_count|number_format:0}{/if}

            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$unqRatio}">{$unqRatio}</td>
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_rebill_count|default:0}">
                {if $srow.total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring{$newLinkProp}">{$srow.total_rebill_count|number_format:0}</a>{else}{$srow.total_rebill_count|number_format:0}{/if}<br>{$srow.total_rebill_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_rebill_count|default:0}">
                {if $srow.ccbill_total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring{$newLinkProp}&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>{else}{$srow.ccbill_total_rebill_count|number_format:0}{/if}

            </td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column col_{counter}" abbr="{$srow.total_refund_count|default:0}">
                {if $srow.total_refund_count}<a href="internal.php?{$curDisplayLink}&view=refunds{$newLinkProp}">{$srow.total_refund_count|number_format:0}</a>{else}{$srow.total_refund_count|number_format:0}{/if}<br>{$srow.total_refund_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column col_{counter}" abbr="{$srow.ccbill_total_refund_count|default:0}">
                {$srow.ccbill_total_refund_count|number_format:0}
            </td>
            {/if}
            <td class="tab-column col_{counter}" abbr="{$srow.total_other_payout|default:0}">
                {if $srow.total_other_payout}
                <a href="internal.php?{$curDisplayLink}&view=other{$newLinkProp}" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if} {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout|currency_format_cents:2}<br>{/if}">{$srow.total_other_payout|currency_format_cents:2}</a>
                {else}
                {$srow.total_other_payout|currency_format_cents:2}
                {/if}
            </td>
            <td class="tab-column col_{counter}" abbr="{$srow.total_payout|default:0}">{$srow.total_payout|currency_format_cents:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn{if $curbreak != 'date' || $sid <= $smarty.now} break-btn-on{/if}">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul>
                        {if $curbreak != 'date' && $singleDay == 0}
                        <li><a href="internal.php?{$curDisplayLink}&view=date{$newLinkProp}">{#Date#}</a></li>
                        {/if}
                        {if $curbreak != 'site'}
                        <li><a href="internal.php?{$curDisplayLink}&view=site{$newLinkProp}">{#Site#}</a></li>
                        {/if}
                        {if $curbreak != 'program'}
                        <li><a href="internal.php?{$curDisplayLink}&view=program{$newLinkProp}">{#Program#}</a></li>
                        {/if}
                        {if $curbreak != 'campaign'}
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign{$newLinkProp}">{#Campaign#}</a></li>
                        {/if}
                        {if $curbreak != 'tag'}
                        <li><a href="internal.php?{$curDisplayLink}&view=tag{$newLinkProp}">{#Tag#}</a></li>
                        {/if}
                        {if $curbreak != 'country'}
                        <li><a href="internal.php?{$curDisplayLink}&view=country{$newLinkProp}">{#Demographic#}</a></li>
                        {/if}
                        {if $curbreak != 'adtool'}
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool{$newLinkProp}">{#Adtool#}</a></li>
                        {/if}
                        {if $curbreak != 'refurl'}
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl{$newLinkProp}">{#ReferringUrl#}</a></li>
                        {/if}
                        {if !empty($config.ALLOW_LANDING_PAGE_REPORTING) && $curbreak != 'landing_page'}
                        <li><a href="internal.php?{$curDisplayLink}&view=landing_page{$newLinkProp}">{#LandingPageUrl#}</a></li>
                        {/if}
                    </ul>
                </div>
            </td>
        </tr>

        {foreachelse}
        <tr class="data-row-even">
            <td class="tab-column left-align" colspan="{if $config.MOD_CCBILL_PAID && $hide_nonccbill_column}15{else}12{/if}">{#NoStatisticsAvailable#}</td>
        </tr>
        {* End Data Row Loop *}
        {/foreach}

        {* End Table Body *}
    </tbody>


    {* Display Total Row *}
    <tfoot>

        {assign var=srow value=$total[$curbreak]}

        {* Build our link to use for Breakdowns *}
        {if $curbreak == 'date' || $curbreak == 'period' || $curbreak == 'month' || $curbreak == 'year'}
        {rebuild_query using="GET" without="tpl,graph,function,view,breakdown" assign="curDisplayLink"}
        {else}
        {assign var="idvar" value="id"}
        {rebuild_query using="GET" without="tpl,graph,function,view,breakdown,filter_$curbreak$idvar" assign="curDisplayLink"}
        {/if}

        <tr class="footer-row">
            {if $curbreak == 'tag'}
            <td class="tab-column tab-break" colspan="{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}15{else}12{/if}">&nbsp;</td>
            {else}
            <td class="tab-column tab-break">{#Total#}</td>

            {if !$config.DISABLE_GALLERY_HIT_TRACKING && ($smarty.request.view == "adtool" || $smarty.request.filter_adtoolid)}
            <td class="tab-column">{$srow.adtool_raw_hit|number_format:0}</td>
            <td class="tab-column">
                {if $srow.adtool_unique_hit}
                <a href="internal.php?{$curDisplayLink}&view=refurl">{$srow.adtool_unique_hit|number_format:0}</a>
                {else}
                {$srow.adtool_unique_hit|number_format:0}
                {/if}
            </td>
            {/if}


            <td class="tab-column">{$srow.raw_hits|number_format:0}</td>
            <td class="tab-column">
                {if $srow.unique_hits}
                <a href="internal.php?{$curDisplayLink}&view=refurl">{$srow.unique_hits|number_format:0}</a>
                {else}
                {$srow.unique_hits|number_format:0}
                {/if}
            </td>
            <td class="tab-column">{$srow.join_hits|number_format:0}</td>
            <td class="tab-column">{$srow.join_submits|number_format:0}</td>
            <td class="tab-column">{$srow.unqSubRatio}</td>
            <td class="tab-column">{$srow.unqJoinHitPercent}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins">{$srow.total_join_count|number_format:0}</a>{else}{$srow.total_join_count|number_format:0}{/if}<br>{$srow.total_join_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{if $srow.ccbill_total_join_count}<a href="internal.php?{$curDisplayLink}&view=joins&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>{else}{$srow.ccbill_total_join_count|number_format:0}{/if}</td>
            {/if}
            <td class="tab-column">
                {if !$srow.total_join_count}
                {assign var="all_joins" value="0"}
                {else}
                {assign var="all_joins" value=$srow.total_join_count}
                {/if}

                {if !$srow.ccbill_total_join_count}
                {assign var="all_ccbill_joins" value="0"}
                {else}
                {assign var="all_ccbill_joins" value=$srow.ccbill_total_join_count}
                {/if}

                {math assign="total_ccbill_and_normal" equation="x + y" x=$all_joins y=$all_ccbill_joins}

                {display_ratio hits=$srow.unique_hits joins=$total_ccbill_and_normal}</td>
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring">{$srow.total_rebill_count|number_format:0}</a>{else}{$srow.total_rebill_count|number_format:0}{/if}<br>{$srow.total_rebill_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{if $srow.ccbill_total_rebill_count}<a href="internal.php?{$curDisplayLink}&view=recurring&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>{else}{$srow.ccbill_total_rebill_count|number_format:0}{/if}</td>
            {/if}
            {if !$hide_nonccbill_column}<td class="tab-column">
                {if $srow.total_refund_count}<a href="internal.php?{$curDisplayLink}&view=refunds">{$srow.total_refund_count|number_format:0}</a>{else}{$srow.total_refund_count|number_format:0}{/if}<br>{$srow.total_refund_payout|currency_format_cents:2}
            </td>{/if}
            {if $config.MOD_CCBILL_PAID}
            <td class="tab-column">{$srow.ccbill_total_refund_count|number_format:0}</td>
            {/if}
            <td class="tab-column">
                {if $srow.total_other_payout}
                <a href="internal.php?page=stats&view=other" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if} {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout|currency_format_cents:2}<br>{/if} ">{$srow.total_other_payout|currency_format_cents:2}</a>
                {else}
                {$srow.total_other_payout|currency_format_cents:2}
                {/if}
            </td>
            <td class="tab-column">{$srow.total_payout|currency_format_cents:2}</td>
            <td class="tab-column breakdown-column" nowrap>
                {* Display Links to further Breakdown Stats for this day *}
                <div class="breakdown-btn break-btn-on">
                    <a href="#" class="noLink">{#BreakdownBy#}</a>
                    <ul class="stndrd">
                        {if $curbreak != 'date' && $singleDay == 0}
                        <li><a href="internal.php?{$curDisplayLink}&view=date">{#Date#}</a></li>
                        {/if}
                        {if $curbreak != 'site'}
                        <li><a href="internal.php?{$curDisplayLink}&view=site">{#Site#}</a></li>
                        {/if}
                        {if $curbreak != 'program'}
                        <li><a href="internal.php?{$curDisplayLink}&view=program">{#Program#}</a></li>
                        {/if}
                        {if $curbreak != 'campaign'}
                        <li><a href="internal.php?{$curDisplayLink}&view=campaign">{#Campaign#}</a></li>
                        {/if}
                        {if $curbreak != 'tag'}
                        <li><a href="internal.php?{$curDisplayLink}&view=tag">{#Tag#}</a></li>
                        {/if}
                        {if $curbreak != 'country'}
                        <li><a href="internal.php?{$curDisplayLink}&view=country">{#Demographic#}</a></li>
                        {/if}
                        {if $curbreak != 'adtool'}
                        <li><a href="internal.php?{$curDisplayLink}&view=adtool">{#Adtool#}</a></li>
                        {/if}
                        {if $curbreak != 'refurl'}
                        <li><a href="internal.php?{$curDisplayLink}&view=refurl">{#ReferringUrl#}</a></li>
                        {/if}
                        {if !empty($config.ALLOW_LANDING_PAGE_REPORTING) && $curbreak != 'landing_page'}
                        <li><a href="internal.php?{$curDisplayLink}&view=landing_page{$newLinkProp}">{#LandingPageUrl#}</a></li>
                        {/if}
                    </ul>
                </div>
            </td>
            {/if}
        </tr>
    </tfoot>

    {* End Stats Table *}
</table>

{* End Standard Breakdown *}
{/if}

{foreachelse}

<h3>{#NoStatisticsAvailable#}</h3>

{* End Breakdown Loop *}
{/foreach}