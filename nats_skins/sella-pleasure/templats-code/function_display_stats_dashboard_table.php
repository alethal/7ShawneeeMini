{*
6651 - Added CCBill Ratio calculation
12128 - Added landing page breakdown
13061 - change /100|currency_format: to |currency_format_cents:
*}
{* load the language settings *}
{use_language_file section=$smarty.request.page|default:'dashboard'}
{* Setup Javascript necessary for this template *}
{literal}
<script>
    //start the jquery on loads
    $(document).ready(function() {

        //set the breakdown options on hover in
        $('.break-btn-on a').hover(function() {
            //should this hover to the left instead of right?
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

        //don't link on the header options
        $('.breakdown-btn .noLink').click(function() {
            return false;
        });

        //add tooltips
        $(".otherIncome").tooltip({
            offset: [-10, 30],
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

        //setup the hover affect for the table rows
        $('.hover-row, .last-row').hover(function() {
            if ($(this).prev().hasClass('hover-row')) {
                $(this).prev().addClass('hover-next-row');
            } else if ($(this).parent().prev('thead').children('.hover-row')) {
                $(this).parent().prev('thead').children('.hover-row').addClass('hover-next-row');
            }
        }, function() {
            if ($(this).prev().hasClass('hover-row')) {
                $(this).prev().removeClass('hover-next-row');
            } else if ($(this).parent().prev('thead').children('.hover-row')) {
                $(this).parent().prev('thead').children('.hover-row').removeClass('hover-next-row');
            }
        });

    });
</script>
{/literal}

{* Display table of Statistics (Breakdown By Date) *}
<table class="table-container" cellpadding=0 cellspacing=0>

    {* Display Header Row *}
    <tr class="header-row2">
        <td class="tab-column tab-break header-first orderby-field">{#Date#}</td>
        <td class="tab-column nohover">{#Uniques#}</td>
        <td class="tab-column nohover">{#Submits#}</td>
        {if !$hide_nonccbill_column}<td class="tab-column nohover">{#Joins#}</td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column nohover">CCB {#Joins#}</td>{/if}
        <td class="tab-column nohover">{#Ratio#}</td>
        {if !$hide_nonccbill_column}<td class="tab-column nohover">{#Rebills#}</td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column nohover">CCB {#Rebills#}</td>{/if}
        {if !$hide_nonccbill_column}<td class="tab-column nohover">{#Refunds#}</td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column nohover">CCB {#Refunds#}</td>{/if}
        <td class="tab-column nohover">{#OtherIncome#}</td>
        <td class="tab-column nohover">{#TotalIncome#}</td>
        <td class="tab-column nohover header-last">{#Breakdown#}</td>
    </tr>

    {* Loop through each day of stats *}
    {foreach from=$stats.date item=srow key=sid name=mystats}

    {* Set row design based on even/odd and current date *}
    <tr class="data-row-{if $smarty.foreach.mystats.iteration % 2 == 0}even{else}odd{/if}{if $sid > $smarty.now}-off{elseif $sid >= ($smarty.now - 86400)}-nextoff{/if} {if $smarty.foreach.mystats.last}last-row{else}hover-row{/if}">
        <td class="tab-column tab-break">{$sid|nats_local_date}</td>
        <td class="tab-column">
            {if $srow.unique_hits}
            <a href="internal.php?page=stats&view=refurl&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{$srow.unique_hits|number_format:0}</a>
            {else}
            {$srow.unique_hits|number_format:0}
            {/if}
        </td>
        <td class="tab-column">{$srow.join_submits|number_format:0}</td>
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_join_count}
            <a href="internal.php?page=stats&view=joins&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{$srow.total_join_count|number_format:0}</a>
            {else}
            {$srow.total_join_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">
            {if $srow.ccbill_total_join_count}
            <a href="internal.php?page=stats&view=recurring&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>
            {else}
            {$srow.ccbill_total_join_count|number_format:0}
            {/if}
        </td>{/if}

        {* 6651 - Added CCBill Ratio calculation *}
        {assign var="NATS_total_joins" value=0}
        {if $config.MOD_CCBILL_PAID}
        {math assign="NATS_total_joins" equation="x + y" x=$stat.ccbill_joins|default:0 y=$srow.total_join_count}
        {/if}

        <td class="tab-column">{display_ratio hits=$srow.unique_hits joins=$srow.total_join_count}</td>
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_rebill_count}
            <a href="internal.php?page=stats&view=recurring&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{$srow.total_rebill_count|number_format:0}</a>
            {else}
            {$srow.total_rebill_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">
            {if $srow.ccbill_total_rebill_count}
            <a href="internal.php?page=stats&view=recurring&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>
            {else}
            {$srow.ccbill_total_rebill_count|number_format:0}
            {/if}
        </td>{/if}
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_refund_count}
            <a href="internal.php?page=stats&view=refunds&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{$srow.total_refund_count|number_format:0}</a>
            {else}
            {$srow.total_refund_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">{$srow.ccbill_total_refund_count|number_format:0}</td>{/if}
        <td class="tab-column">
            {if $srow.total_other_payout}
            <a href="internal.php?page=stats&view=other&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if}  {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout|currency_format_cents:2}<br>{/if} ">{$srow.total_other_payout|currency_format_cents:2}</a>
            {else}
            {$srow.total_other_payout|currency_format_cents:2}
            {/if}
        </td>
        <td class="tab-column">{$srow.total_payout|currency_format_cents:2}</td>
        <td class="tab-column breakdown-column">
            {* Display Links to further Breakdown Stats for this day *}
            <div class="breakdown-btn{if $sid <= $smarty.now} break-btn-on{/if}">
                <a href="#" class="noLink">{#BreakdownBy#}</a>
                <ul>
                    <li><a href="internal.php?page=stats&view=site&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Site#}</a></li>
                    <li><a href="internal.php?page=stats&view=program&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Program#}</a></li>
                    <li><a href="internal.php?page=stats&view=campaign&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Campaign#}</a></li>
                    <li><a href="internal.php?page=stats&view=tag&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Tag#}</a></li>
                    <li><a href="internal.php?page=stats&view=country&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Demographic#}</a></li>
                    <li><a href="internal.php?page=stats&view=adtool&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#Adtool#}</a></li>
                    <li><a href="internal.php?page=stats&view=refurl&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#ReferringUrl#}</a></li>
                    {if !empty($config.ALLOW_LANDING_PAGE_REPORTING)}
                    <li>
                        <a href="internal.php?page=stats&view=landing_page&period=8&period_start={$sid|date_format:'%Y-%m-%d'}&period_end={$sid|date_format:'%Y-%m-%d'}">{#LandingPageUrl#}</a>
                    </li>
                    {/if}
                </ul>
            </div>
        </td>
    </tr>
    {/foreach}

    {* Display Totals Row *}
    {assign var=srow value=$total.date}
    <tr class="footer-row">
        <td class="tab-column tab-break">{#Total#}</td>
        <td class="tab-column">
            {if $srow.unique_hits}
            <a href="internal.php?page=stats&view=refurl">{$srow.unique_hits|number_format:0}</a>
            {else}
            {$srow.unique_hits|number_format:0}
            {/if}
        </td>
        <td class="tab-column">{$srow.join_submits|number_format:0}</td>
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_join_count}
            <a href="internal.php?page=stats&view=joins">{$srow.total_join_count|number_format:0}</a>
            {else}
            {$srow.total_join_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">
            {if $srow.ccbill_total_join_count}
            <a href="internal.php?page=stats&view=joins&ccbill_paid=1">{$srow.ccbill_total_join_count|number_format:0}</a>
            {else}
            {$srow.ccbill_total_join_count|number_format:0}
            {/if}

        </td>{/if}
        <td class="tab-column">{display_ratio hits=$srow.unique_hits joins=$srow.total_join_count}</td>
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_rebill_count}
            <a href="internal.php?page=stats&view=recurring">{$srow.total_rebill_count|number_format:0}</a>
            {else}
            {$srow.total_rebill_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">
            {if $srow.ccbill_total_rebill_count}
            <a href="internal.php?page=stats&view=recurring&ccbill_paid=1">{$srow.ccbill_total_rebill_count|number_format:0}</a>
            {else}
            {$srow.ccbill_total_rebill_count|number_format:0}
            {/if}
        </td>{/if}
        {if !$hide_nonccbill_column}<td class="tab-column">
            {if $srow.total_refund_count}
            <a href="internal.php?page=stats&view=refunds">{$srow.total_refund_count|number_format:0}</a>
            {else}
            {$srow.total_refund_count|number_format:0}
            {/if}
        </td>{/if}
        {if $config.MOD_CCBILL_PAID}<td class="tab-column">{$srow.ccbill_total_refund_count|number_format:0}</td>{/if}
        <td class="tab-column">
            {if $srow.total_other_payout}
            <a href="internal.php?page=stats&view=other" class="otherIncome" title="{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if}  {if !empty($srow.seconds_affiliate_payout)}<b>{#Dialer#}:</b> {$srow.seconds_affiliate_payout|currency_format_cents:2}<br>{/if} ">{$srow.total_other_payout|currency_format_cents:2}</a>
            {else}
            {$srow.total_other_payout|currency_format_cents:2}
            {/if}
        </td>
        <td class="tab-column">{$srow.total_payout|currency_format_cents:2}</td>
        <td class="tab-column breakdown-column">
            {* Display Links to further Breakdown Stats for this period *}
            <div class="breakdown-btn break-btn-on">
                <a href="#" class="noLink">{#BreakdownBy#}</a>
                <ul>
                    <li><a href="internal.php?page=stats&view=site">{#Site#}</a></li>
                    <li><a href="internal.php?page=stats&view=program">{#Program#}</a></li>
                    <li><a href="internal.php?page=stats&view=campaign">{#Campaign#}</a></li>
                    <li><a href="internal.php?page=stats&view=tag">{#Tag#}</a></li>
                    <li><a href="internal.php?page=stats&view=country">{#Demographic#}</a></li>
                    <li><a href="internal.php?page=stats&view=adtool">{#Adtool#}</a></li>
                    <li><a href="internal.php?page=stats&view=refurl">{#ReferringUrl#}</a></li>
                </ul>
            </div>
        </td>
    </tr>

    {* End Stats Table *}
</table>