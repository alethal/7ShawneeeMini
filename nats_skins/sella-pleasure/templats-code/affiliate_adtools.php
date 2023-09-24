<!-- START ADTOOLS PAGE -->

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}{if isset($smarty.request.typeid) && isset($possible_adtools_types[$smarty.request.typeid])} - {$possible_adtools_types[$smarty.request.typeid].description|convlang}{/if}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>


{* Include any Necessary JavaScript *}
{literal}
<script>
    $(document).ready(function() {

        //highlight sortable column
        $(".reorder_link").hover(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#toolTable > tbody > tr").children(".col_" + column).addClass('orderby-hover');
            return false;
        }, function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#toolTable > tbody > tr").children(".col_" + column).removeClass('orderby-hover');
        });

        //any select text area links
        $(".selectAllText").click(function() {
            var txtId = $(this).attr('id');
            var txtIdParts = txtId.split('_');
            $("#select-all_" + txtIdParts[1]).focus();
            $("#select-all_" + txtIdParts[1]).select();
            return false;
        });

        //build all of our tooltips specific to this page
        $(".linkcode-view").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });
        $(".search-new").tooltip({
            offset: [-15, 43],
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic({
            left: {
                offset: [-15, 37]
            }
        });

        //update adtool marked date
        $(".markAdtoolsOld").click(function() {
            var curId = $(this).attr('id');

            $.post('submit.php', {
                'tpl': 'affiliate_adtools',
                'page': 'adtools',
                'ajax_return': 'YES',
                'submit_function': 'submit_mark_adtools_old',
                'variable_array[]': 'adtool',
                'adtool[setting]': curId
            }, function(data) {
                if (data) {
                    var dParts = data.split(':');
                    $("#" + curId + "_view").html('{/literal}{#LastMarked#}{literal}: <a href="internal.php?{/literal}{rebuild_query using="GET" without="time,time_setting"}{literal}&time=' + dParts[1] + '&time_setting=3&submit_adtool_limits=1">' + dParts[2] + '</a>');
                    $(".newItemDisplay").hide();
                    $(".light-highlight-row").removeClass('light-highlight-row');
                }
            });

            return false;
        });

    });
</script>
{/literal}

{assign var="category" value=$smarty.request.category}
{assign var="typeid" value=$smarty.request.typeid}

{list_linkcodes data_only=1}
{if $params.program}
{assign var="programid" value=$params.program}
{else}
{if $config.DEFAULT_PROGRAM_ID}
{assign var="programid" value=$config.DEFAULT_PROGRAM_ID}
{else}
{foreach from=$program_names item=program_name name=program_list}
{if $smarty.foreach.program_list.first && $program_name.type == 0}
{assign var="programid" value=$program_name.id}
{/if}
{/foreach}
{/if}
{/if}
{assign var="campaignid" value=$params.campaign}
{assign var="siteid" value=$params.site}
{assign var="curCamp" value=$smarty.request.campaignid|default:$params.campaign}

{* Display Type List *}
<div class="mainblock AffiliateBrandSelection">
    <div class="heading">
        <div class="hold">

            {if $typeid}
            {* Link to Minimize Settings *}
            <a href="#" class="open-close" id="default_adtools_minimize_site_selection">{if isset($usr.default_adtools_minimize_site_selection) && !$usr.default_adtools_minimize_site_selection}<span>-</span>{else}<span class="open-plus">+</span>{/if}</a>
            {/if}
            <a href="/internal.php?page=support&view=NATShelp&section=adtools&article=SiteSelection#SiteSelection" target="_blank" class="helpbtn" title="{#HelpAdToolsSiteSelection#}"><span>?</span></a>

            <h2>{#SiteSelection#}</h2>
        </div>
    </div>
    <div class="content" {if $typeid && (!isset($usr.default_adtools_minimize_site_selection) || $usr.default_adtools_minimize_site_selection)} style="display: none;" {/if}>
        <div class="c">
            <div class="standard-block">
                {* Display the Grid of Sites with Types *}
                {display_adtool_types category=$category program=$programid campaign=$campaignid}
            </div>
        </div>
    </div>
</div>

{* Make sure we default a view *}
{if isset($smarty.request.category) && isset($smarty.request.typeid)}
{assign var="typeid" value=$smarty.request.typeid}

{* Don't show html options for specific adtool types *}
{if $typeid == 2 || $typeid == 3 || $typeid == 5 || $typeid == 6}{assign var="disable_html_linkstyle" value="1"}{/if}
{assign var="disable_all_programs" value=TRUE}
{* Include the Form for setting Linkcode Settings *}
{include file="nats:include_linkcode_settings"}

{* What is our default Count *}
{if $smarty.request.count}{assign var="adCount" value=$smarty.request.count}
{elseif $usr.default_messages_count_per_page}{assign var="adCount" value=$usr.default_adtools_count_per_page}
{else}{assign var="adCount" value="25"}{/if}

{* What is our default Order *}
{assign var="orderbydefaultname" value="default_adtools_orderby_$typeid"}
{if $smarty.request.orderby}{assign var="adOrder" value=$smarty.request.orderby}
{elseif $usr[$orderbydefaultname]}{assign var="adOrder" value=$usr[$orderbydefaultname]}
{else}{assign var="adOrder" value="-999"}{/if}

{if $programid}
{* Display the desired adtools *}
{display_adtools category=$category program=$programid campaign=$campaignid orderby=$adOrder count=$adCount count_sticky='default_adtools_count_per_page' order_sticky=$orderbydefaultname}
{/if}
{/if}

<!-- END ADTOOLS PAGE -->