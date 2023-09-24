{*
13061 - change /100|currency_format: to |currency_format_cents:
*}
{* function_display_stats_dashboard_summary *}
{if empty($StatsSummaryCalled)}
{* Setup Javascript necessary for this page *}
{literal}
<script>
    $(document).ready(function() {
        //setup tooltips
        $(".summary li .sum-box").tooltip({
            offset: [5, 70],
            predelay: 800,
            effect: 'fade',
            fadeInSpeed: 200,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

    });
</script>
{/literal}

{* Add the Language File *}
{use_language_file section=$smarty.request.page|default:'dashboard'}
{/if}

{assign var=srow value=$total.date}

{* Build the Data for Comparison *}
{get_previous_timerange current_period=$current_period}
{if $prevPeriod}
{display_stats data_only="1" assign_prefix="comp_" no_form_vars="1" period=$prevPeriod start=$prevStart end=$prevEnd}
{assign var=comprow value=$comp_total.date}
{/if}

<ul class="summary">
    <li class="sum-bottom-left statsclicks">
        <a href="internal.php?page=stats&period={$current_period}"><span class="sum-box" title="{#RawHitsDesc#}"><strong>{#RawHits#}:</strong><span>{$srow.raw_hits|number_format:0}</span><em>{display_ratio hits=$srow.raw_hits joins=$srow.total_join_count}</em>
                {if isset($comprow.raw_hits) && isset($srow.raw_hits)}
                {if $comprow.raw_hits}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.raw_hits y=$comprow.raw_hits}
                {elseif $srow.raw_hits}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}"><span class="sum-box" title="{#UniqueHitsDesc#}"><strong>{#UniqueHits#}:</strong><span>{$srow.unique_hits|number_format:0}</span><em>{display_ratio hits=$srow.unique_hits joins=$srow.total_join_count}</em>
                {if isset($comprow.unique_hits) && isset($srow.unique_hits)}
                {if $comprow.unique_hits}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.unique_hits y=$comprow.unique_hits}
                {elseif $srow.unique_hits}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}"><span class="sum-box" title="{#JoinHitsDesc#}"><strong>{#JoinHits#}:</strong><span>{$srow.join_hits|number_format:0}</span><em>{display_ratio hits=$srow.join_hits joins=$srow.total_join_count}</em>
                {if isset($comprow.join_hits) && isset($srow.join_hits)}
                {if $comprow.join_hits}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.join_hits y=$comprow.join_hits}
                {elseif $srow.join_hits}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}"><span class="sum-box" title="{#JoinSubmitsDesc#}"><strong>{#JoinSubmits#}:</strong><span>{$srow.join_submits|number_format:0}</span><em>{display_ratio hits=$srow.join_submits joins=$srow.total_join_count}</em>
                {if isset($comprow.total_join_count) && isset($srow.total_join_count)}
                {if $comprow.total_join_count}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_join_count y=$comprow.total_join_count}
                {elseif $srow.total_join_count}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom-left">
        <a href="internal.php?page=stats&period={$current_period}&view=joins{if $config.MOD_CCBILL_PAID}&include_ccbill_paid=1{/if}"><span class="sum-box" title="{#JoinsDesc#}"><strong>{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}Total {elseif $config.MOD_CCBILL_PAID}CCBill {/if}{#Joins#}:</strong><span>{$srow.total_join_count|number_format:0}</span>
                {if isset($comprow.total_join_count) && isset($srow.total_join_count)}
                {if $comprow.total_join_count}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_join_count y=$comprow.total_join_count}
                {elseif $srow.total_join_count}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}&view=recurring{if $config.MOD_CCBILL_PAID}&include_ccbill_paid=1{/if}"><span class="sum-box" title="{#RebillsDesc#}"><strong>{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}Total {elseif $config.MOD_CCBILL_PAID}CCBill {/if}{#Rebills#}:</strong><span>{$srow.all_recurring_count|number_format:0}</span>
                {if isset($comprow.all_recurring_count) && isset($srow.all_recurring_count)}
                {if $comprow.all_recurring_count}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.all_recurring_count y=$comprow.all_recurring_count}
                {elseif $srow.all_recurring_count}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}&view=refunds{if $config.MOD_CCBILL_PAID}&include_ccbill_paid=1{/if}"><span class="sum-box" title="{#RefundsDesc#}"><strong>{if $config.MOD_CCBILL_PAID && !$hide_nonccbill_column}Total {elseif $config.MOD_CCBILL_PAID}CCBill {/if}{#Refunds#}:</strong><span>{$srow.all_refund_count|number_format:0}</span>
                {if isset($comprow.all_refund_count) && isset($srow.all_refund_count)}
                {if $comprow.all_refund_count}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.all_refund_count y=$comprow.all_refund_count}
                {elseif $srow.all_refund_count}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}&view=other"><span class="sum-box" title="{#OtherIncomeDesc#}<br>{if !empty($srow.total_wm_join_referral_payout)}<b>{#ReferralJoin#}:</b> {$srow.total_wm_join_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_referral_payout)}<b>{#Referral#}:</b> {$srow.total_referral_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_account_rep_payout)}<b>{#AccountRep#}:</b> {$srow.total_account_rep_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_partner_payout)}<b>{#Partner#}:</b> {$srow.total_partner_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_byoa_payout)}<b>{#BYOA#}:</b> {$srow.total_byoa_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_promotional_payout)}<b>{#Promotional#}:</b> {$srow.total_promotional_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_tier_adjustment_payout)}<b>{#TierAdjustment#}:</b> {$srow.total_tier_adjustment_payout|currency_format_cents:2}<br>{/if}{if !empty($srow.total_correction_payout)}<b>{#Correctional#}:</b> {$srow.total_correction_payout|currency_format_cents:2}<br>{/if}"><strong>{#OtherIncome#}:</strong><span>{$srow.total_other_payout|currency_format_cents:2}</span>
                {if isset($comprow.total_other_payout) && isset($srow.total_other_payout)}
                {if $comprow.total_other_payout}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_other_payout y=$comprow.total_other_payout}
                {elseif $srow.total_other_payout}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom-left">
        <a href="internal.php?page=stats&period={$current_period}&view=joins"><span class="sum-box" title="{#JoinIncomeDesc#}"><strong>{#JoinIncome#}:</strong><span>{$srow.total_join_payout|currency_format_cents:2}</span>
                {if isset($comprow.total_join_payout) && isset($srow.total_join_payout)}
                {if $comprow.total_join_payout}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_join_payout y=$comprow.total_join_payout}
                {elseif $srow.total_join_payout}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}&view=recurring"><span class="sum-box" title="{#RebillIncomeDesc#}"><strong>{#RebillIncome#}:</strong><span>{$srow.total_rebill_payout|currency_format_cents:2}</span>
                {if isset($comprow.total_rebill_payout) && isset($srow.total_rebill_payout)}
                {if $comprow.total_rebill_payout}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_rebill_payout y=$comprow.total_rebill_payout}
                {elseif $srow.total_rebill_payout}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}&view=refunds"><span class="sum-box" title="{#RefundLossDesc#}"><strong>{#RefundLoss#}:</strong><span>{$srow.total_refund_payout|currency_format_cents:2}</span>
                {if isset($comprow.total_refund_payout) && isset($srow.total_refund_payout)}
                {if $comprow.total_refund_payout}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_refund_payout y=$comprow.total_refund_payout}
                {elseif $srow.total_refund_payout}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
    <li class="sum-bottom">
        <a href="internal.php?page=stats&period={$current_period}"><span class="sum-box" title="{#TotalIncomeDesc#}"><strong>{#TotalIncome#}:</strong><span>{$srow.total_payout|currency_format_cents:2}</span>
                {if isset($comprow.total_payout) && isset($srow.total_payout)}
                {if $comprow.total_payout}{math assign="pChange" equation="((x-y)/y)*100" x=$srow.total_payout y=$comprow.total_payout}
                {elseif $srow.total_payout}{assign var="pChange" value="100"}
                {else}{assign var="pChange" value="0"}{/if}
                <div class="perc-change-container">
                    <div class="perc-change-{if $pChange > 0}up{elseif $pChange < 0}down{else}no{/if}-arrow"></div>{if $pChange}<span>{$pChange|number_format:1}%</span>{/if}<div class="desciptor">{if $pChange}{#ComparedTo#}{else}{#Sameas#}{/if}<br>{$prevTitle|convlang}</div>
                </div>
                {/if}
            </span></a>
    </li>
</ul>