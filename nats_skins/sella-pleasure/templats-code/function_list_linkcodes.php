{*
7112 - Added "All programs functionality
8170 - Link style properly changes when overriding an affiliate
8567 - Added check for DEFAULT_PROGRAM_ID when displaying the program being used
10045 - Changed view_banner.php to $config.VIEW_BANNER_SCRIPT
11173 - Grab the thumbnail from the default tour if no thumbnail is available
*}

{* Include the Form for setting Linkcode Settings *}
{include file="nats:include_linkcode_settings"}

{* Get all of the Tours Details *}
{list_tours assign_prefix='detail_' list_details="1" site="-1"}

{* Default Campaign Value *}
{assign var="curCamp" value=$smarty.request.campaignid|default:$params.campaign}

{* Include any Necessary JavaScript *}
{literal}
<script>
    $(document).ready(function() {

        //setup our changes for the linkcode block
        $('.linkcode-view').click(function() {
            //select the template to display
            var newView = $(this).attr('id');
            var idParts = newView.split('_');
            viewDiv = "linkcode_" + idParts[0];

            //change the view to 
            $(".link-view-options").hide('fast');
            $("#" + viewDiv).show('fast');

            //set the selected icon
            $('.linkcode-view img').removeClass('current-view');
            $(this).children('img').addClass('current-view');
            return false;
        });

        //highlight sortable column
        $(".reorder_link").hover(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#linkTable > tbody > tr").children(".col_" + column).addClass('orderby-hover');
            return false;
        }, function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#linkTable > tbody > tr").children(".col_" + column).removeClass('orderby-hover');
        });

        //add view tooltips
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

        //add our custom sorting function
        var natsSortTextExct = function(node) {
            return $(node).attr('abbr');
        }

        //include the table sorter
        $("#linkTable").tablesorter({
            textExtraction: natsSortTextExct
        });

        $(".reorder_link").click(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            var tableName = 'linkTable';
            var orderHeaderName = 'table-order-header';
            var sortOrder = 0;
            var orderClass = 'table-orderby-field';

            //if we already sort by this, switch order
            if ($(this).children('.table-orderby-wrapper').children('.table-orderby-field').css('display') != 'none') {
                sortOrder = 1;
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
                    curPointer.removeClass(cName);
                });

                if (rowCount % 2 == 0) {
                    $(this).addClass('data-row-even');
                } else {
                    $(this).addClass('data-row-odd');
                }
                $(this).addClass('hover-row');
                rowCount++;
            });
            return false;
        });

        //remove the search title
        $("#inline-search").focus(function() {
            var curVal = $(this).val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $(this).val('');
                $(this).removeClass('DisableEdit');
            }
        });
        $("#inline-search").blur(function() {
            var curVal = $(this).val();
            if (curVal == '') {
                $(this).val('{/literal}{#Search#}{literal}...');
                $(this).addClass('DisableEdit');
            }
        });
        $("#inline-search").quicksearch('table#linkTable tbody tr', {
            'stripeRows': ['data-row-odd', 'data-row-even']
        });


    });
</script>
{/literal}


{* Display Linkcodes *}
<div class="mainblock">
    <div class="heading">
        <div class="hold">
            <a href="/internal.php?page=support&view=NATShelp&section=adtools&article=Linkcodes#Linkcodes" target="_blank" class="helpbtn" title="{#HelpAdToolsLinkcodes#}"><span>?</span></a>
            <h2>{#YourLinkcodes#}</h2>
        </div>
    </div>
    <div class="content" id="form_content_area">
        <div class="c">
            <div class="standard-block">
                <div class="display-content">

                    {* Display the Current Settings *}
                    <div class="title" style="padding: 10px 0 5px;">
                        <div class="desc2" style="font-size:14px">
                            <div class="title-overflow">
                                {if $smarty.get.programid != 0}
                                {#Showing#} {#Linkcodes#} {#for#} {#Program#} <strong>{$program_names[$smarty.get.programid].name}</strong>
                                {elseif !isset($smarty.get.programid) && $config.DEFAULT_PROGRAM_ID}
                                {#Showing#} {#Linkcodes#} {#for#} {#Program#} <strong>{$program_names[$config.DEFAULT_PROGRAM_ID].name}</strong>
                                {else}
                                {#Showing#} {#Linkcodes#} {#for#} <strong>{#All#} {#Programs#}</strong>
                                {/if}
                            </div>
                        </div>
                        <div class="view">
                            <span>{#ViewAs#}:</span>
                            <ul>
                                <li><a href="#" class="linkcode-view" id="table_0" title="{#ViewAsTable#}"><img src="nats_images/view-as-table.png" {if empty($smarty.request.dump_format)} class="current-view" {/if} alt="{#ViewAsTable#}" width="16" height="16" /></a></li>
                                <li><a href="#" class="linkcode-view" id="dump_0" title="{#ViewAsDump#}"><img src="nats_images/csv-dump.png" {if isset($smarty.request.dump_format)} class="current-view" {/if}alt="{#ViewAsDump#}" width="16" height="16" /></a></li>
                            </ul>
                        </div>
                    </div>

                    {* Default Option, Display Linkcodes as Table *}
                    <div id="linkcode_table" class="link-view-options" {if isset($smarty.request.dump_format)} style="display: none;" {/if}>

                        {* Build Query Vars for Links *}
                        {rebuild_query using="GET" without="order" assign="orderLink"}

                        {* What is the default order? *}
                        {if $smarty.request.order}{assign var="linkOrder" value=$smarty.request.order}
                        {elseif $usr.default_linkcodes_orderby}{assign var="linkOrder" value=$usr.default_linkcodes_orderby}
                        {else}{assign var="linkOrder" value="1"}{/if}

                        {* Start Table of Data *}
                        <table class="table-container" cellpadding=0 cellspacing=0 id="linkTable">

                            <thead>
                                {* Search Linkcodes *}
                                <tr class="data-row-even no-bottom-line">
                                    <td class="tab-column left-align tab-break" colspan="3">
                                        <div class="tools filter-form">
                                            <input name="break_search" id="inline-search" placeholder="{#Search#}..." value="{#Search#}..." class="filter-text DisableEdit">
                                        </div>
                                    </td>
                                </tr>
                            </thead>

                            <thead>
                                <tr class="header-row2 table-order-header">
                                    <td class="tab-column left-align header-first orderby-field">
                                        <a class="reorder_link" id="reorder_0" href="#">
                                            <div class="table-orderby-wrapper">
                                                <div class="table-orderby-field"></div>
                                                <div class="table-orderby-field-reverse"></div>
                                            </div>{#Site#} ({#Tour#})
                                        </a>
                                    </td>
                                    {if $usr.default_program == 0 && (!$smarty.get.programid || $smarty.get.programid == 0)}
                                    <td class="tab-column left-align">
                                        <a class="reorder_link" id="reorder_1" href="#">
                                            <div class="table-orderby-wrapper">
                                                <div class="table-orderby-field" style="display: none;"></div>
                                                <div class="table-orderby-field-reverse"></div>
                                            </div>{#Program#}
                                        </a>
                                    </td>
                                    {/if}
                                    <td class="tab-column left-align">
                                        <a class="reorder_link" id="reorder_2" href="#">
                                            <div class="table-orderby-wrapper">
                                                <div class="table-orderby-field" style="display: none;"></div>
                                                <div class="table-orderby-field-reverse"></div>
                                            </div>{#Linkcode#}
                                        </a>
                                    </td>
                                    <td class="tab-column left-align nohover header-last">{#Actions#}</td>
                                </tr>
                            </thead>

                            <tbody>

                                {* Start Counter for Odd/Even Display *}
                                {counter start="1" print=0}

                                {* Loop Through Each Program *}
                                {foreach from=$linkcodes item="proglink" key="programid"}

                                {* Loop Through Each Site *}
                                {foreach from=$proglink item="sitelink" key="siteid"}

                                {* Reset the niches *}
                                {assign var="nicheList" value=""}
                                {assign var="thumbName" value=""}
                                {assign var="thumbExt" value=""}
                                {assign var="thumbTourid" value=""}

                                {* Loop Through Each Tour *}
                                {foreach from=$sitelink item="tourlink" key="tourid"}

                                {* Set the niches for this site/tour *}
                                {if $detail_tours[$siteid][$tourid].niche}
                                {assign var="nicheList" value=$detail_tours[$siteid][$tourid].niche}
                                {/if}
                                {if $detail_tours[$siteid][$tourid].thumb && $detail_tours[$siteid][$tourid].thumb_ext}
                                {assign var="thumbName" value=$detail_tours[$siteid][$tourid].thumb}
                                {assign var="thumbExt" value=$detail_tours[$siteid][$tourid].thumb_ext}
                                {assign var="thumbTourid" value=$tourid}
                                {else}
                                {* get the thumbnail from the default tour *}
                                {nats_get_first_in_array array=$detail_tours[$siteid]}
                                {assign var="thumbName" value=$entry.thumb}
                                {assign var="thumbExt" value=$entry.thumb_ext}
                                {assign var="thumbTourid" value=$entry.tourid}
                                {/if}

                                {* Only Display the Default for Affiliate/Program/Site/Tour *}
                                {if $tourlink[0][0][0]}

                                {* Increment Counter *}
                                {counter assign="lCount" print=0}

                                {* Display Linkcode *}
                                <tr class="data-row-{if $lCount % 2 == 0}even{else}odd{/if} hover-row{if $lCount == 1} first-row{/if}">
                                    <td abbr="{$sites[$siteid]}{$tours[$siteid][$tourid]}" class="tab-column col_0 left-align">
                                        {if $thumbName && $thumbExt}
                                        <div class="mouseover_display_image">
                                            <span class="mouseover_image"><img src="{$config.VIEW_BANNER_SCRIPT}?id=site_thumb_{$thumbTourid}&type={$thumbExt}&name={$thumbName}&height=150" class="table-img-mouseover"></span>
                                            {$sites[$siteid]}<br><span>({$tours[$siteid][$tourid]})</span>
                                        </div>
                                        {else}
                                        {$sites[$siteid]}<br><span>({$tours[$siteid][$tourid]})</span>
                                        {/if}
                                    </td>
                                    {if $usr.default_program == 0 && (!$smarty.get.programid || $smarty.get.programid == 0)}
                                    <td abbr=">{$program_names[$programid].name}" class="tab-column col_1 left-align">{$program_names[$programid].name}</td>
                                    {/if}
                                    <td abbr="{$tourlink[0][0][0]}" class="tab-column col_2 left-align">

                                        <div class="DisplayLinkWrap">
                                            <div class="DisplayLinkCopy">Link code: <span>(2x click then copy)</span></div>
                                            <input type="text" value='{if $usr.over_unencoded >= 10 || (!isset($usr.over_unencoded) && $usr.unencoded >= 10)}<a href="{$tourlink[0][0][0]}">{$sites[$siteid]}</a>{else}{$tourlink[0][0][0]}{/if}' class="display-link-text">
                                        </div>
                                    </td>
                                    <td class="tab-column left-align"><a href="{$tourlink[0][0][0]}" target="_blank" style="text-decoration: underline;">{#Link#}</a> | <a href="internal.php?page=code_info&natscode={$tourlink[0][0][0]}" style="text-decoration: underline;">{#Details#}</a></td>
                                </tr>

                                {/if}

                                {* End Tour Loop *}
                                {/foreach}

                                {* End Site Loop *}
                                {/foreach}

                                {* End Program Loop *}
                                {/foreach}

                            </tbody>

                        </table>

                    </div>

                    {* Secondary View, Display Linkcodes as Dump *}
                    <div id="linkcode_dump" class="link-view-options" {if empty($smarty.request.dump_format)} style="display: none;" {/if}>

                        {* Preset our Dump Fields *}
                        {if empty($dp0) && empty($smarty.request.dp_field_0)}{assign var=dp0 value='linkcode'}{/if}
                        {if empty($dp1) && empty($smarty.request.dp_field_1)}{assign var=dp1 value='none'}{/if}
                        {if empty($dp2) && empty($smarty.request.dp_field_2)}{assign var=dp2 value='none'}{/if}
                        {if empty($dp3) && empty($smarty.request.dp_field_3)}{assign var=dp3 value='none'}{/if}
                        {if empty($dp4) && empty($smarty.request.dp_field_4)}{assign var=dp4 value='none'}{/if}

                        {display_adtool_dump_form typeFields=$linkCodeFields linkcodes="1"}

                        {* Display Section Header *}
                        <div class="section_header5">
                            {#Linkcodes#}
                        </div>

                        {* Display the Dump area *}
                        <textarea id="dump_details" class="display-dump-textarea" wrap="off">{$dump_header}{display_linkcode_dump linkcodes=$linkcodes beg=$beg sep=$sep end=$end le=$le fields="$dp0,$dp1,$dp2,$dp3,$dp4,$dp5,$dp6,$dp7" topts=$topts}</textarea>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>