<!-- START QUICKLINKS PAGE -->

{* Setup necesary JavaScript *}
{literal}
<script>
    //setup the function to reorder the table nicely
    function reorganize_table() {
        //reset the odd/even rows and last
        var rowCount = 1;
        var totalRowCount = $("#link-table > tbody > tr").size();
        $("#link-table > tbody > tr").each(function() {
            var curClasses = $(this).attr('class');
            var curPointer = $(this);
            var highlightRow = false;
            if (rowCount <= 7) highlightRow = true;

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
            if (highlightRow) $(this).addClass('light-highlight-row');
            $(this).addClass('hover-row');

            $(this).children('.tab-column').css('border-bottom-color', '');
            $(this).children('.count-column').html(rowCount);

            rowCount++;
        });
        //$("#link-table").children('thead').find('.data-row-even').addClass('hover-row');

        return false;
    }

    //setup the js events
    $(document).ready(function() {

        //remove the search title
        $("#inline-search").focus(function() {
            var curVal = $('#inline-search').val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $('#inline-search').val('');
                $('#inline-search').removeClass('DisableEdit');
            }
            return false;
        });
        //remove the search title
        $("#inline-search").blur(function() {
            var curVal = $('#inline-search').val();
            if (curVal == '') {
                $('#inline-search').val('{/literal}{#Search#}{literal}...');
                $('#inline-search').addClass('DisableEdit');
            }
            return false;
        });

        //setup the search option for the table
        $("#inline-search").quicksearch('table#link-table tbody tr', {
            'stripeRows': ['data-row-odd', 'data-row-even']
        });

        //drag and droppable reordering
        $("#link-table").tableDnD({
            onDrop: function(table, row) {

                //update the order
                reorganize_table();

                //remote update the order
                var changeData = $.tableDnD.serialize();
                $.get('ajax_data.php?function=ajax_update_affiliate_bookmark&' + changeData);

                return false;
            },
            onDragStart: function(table, row) {
                $("#link-table").find('.highlight-row').removeClass('highlight-row');
                $("#link-table").find('.hover-next-row').removeClass('hover-next-row');
                $("#link-table").find('.hover-row').removeClass('hover-row');
            },
            onDragClass: 'highlight-row'
        });

        //option to remove a quicklink
        $(".removeBkmrk").click(function() {
            var curId = $(this).parent().parent().attr("id");
            var idParts = curId.split('_');
            //remove the bookmark
            $.post('ajax_data.php', {
                'function': 'ajax_update_affiliate_bookmark',
                'bookmark_id': idParts[1]
            }, function(data) {
                if (data == 'OK') {
                    $("#bkmrk_" + idParts[1]).remove();
                    reorganize_table();

                    //is this page bookmarked
                    if ($(".edit_bookmark").attr('id') == "bkmrkid_" + idParts[1]) {
                        $("#bkmrkid_" + idParts[1]).html('+ {/literal}{#BookmarkThisPage#}{literal}')
                        $("#bkmrkid_" + idParts[1]).attr("id", 'create_bkmrk');
                        $(".bookmark-set").removeClass('bookmark-unset');
                    }

                }
            });

            return false;
        });

    });
</script>
{/literal}


{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

{* Setup Two Column Display *}
<div class="twocolumn QuickLinksPage">
    <div class="c">
        <div class="box-hold">

            {* Display the Left Column
            {include file="nats:include_affiliate_account_sidebar"}
            *}

            {* Display Main Column with Account Page *}
            <div class="box">
                <div class="heading">
                    <div class="hold">
                        <a href="/internal.php?page=support&view=NATShelp&section=account&article=ManageQuickLinks#ManageQuickLinks" target="_blank" class="helpbtn" title="{#HelpAccountManageQuickLinks#}"><span>?</span></a>
                        <h2>{#ManageQuickLinks#}</h2>
                    </div>
                </div>
                <div class="content">
                    <div class="c">
                        <div class="standard-block">
                            <div class="display-content">

                                {* Spacer between Boxes *}
                                <div class="clear-separator"></div>

                                {* Begin Table Display *}
                                <table class="table-container2" id="link-table" cellpadding=0 cellspacing=0>

                                    <thead>
                                        {* Display Search Row *}
                                        <tr class="data-row-even no-bottom-line nodrop nodrag" id="searchRow">
                                            <td class="tab-column left-align" colspan="2">&nbsp;</td>
                                            <td class="tab-column left-align" colspan="2">
                                                <div class="tools filter-form">
                                                    <input name="search[name]" id="inline-search" value="{if $search_vars.name}{$search_vars.name}{else}{#Search#}...{/if}" class="filter-text{if empty($search_vars.name)} DisableEdit{/if}">
                                                </div>
                                            </td>
                                        </tr>
                                        {* Display Header Row, with Sortable Columns *}
                                        <tr class="header-row2 table-order-header nodrop nodrag">
                                            <td class="tab-column nohover left-align header-first orderby-field">{#Order#}</td>
                                            <td class="tab-column nohover">{#Icon#}</td>
                                            <td class="tab-column nohover">{#Title#}</td>
                                            <td class="tab-column nohover header-last">{#Actions#}</td>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        {* Loop Through the Quicklinks to Display *}
                                        {foreach from=$usr.bookmarks item="bkmrk" key="bkmrkid" name="qlinks"}

                                        {* Alternate Odd/Even Rows *}
                                        <tr class="data-row-{if $smarty.foreach.qlinks.iteration % 2 == 0}even{else}odd{/if} hover-row{if $smarty.foreach.qlinks.iteration <= 7} light-highlight-row{/if}" id="bkmrk_{$bkmrkid}">
                                            <td class="tab-column center-align col_{counter start=0} count-column" abbr="{$smarty.foreach.qlinks.iteration}">{$smarty.foreach.qlinks.iteration}</td>
                                            <td class="tab-column center-align col_{counter}" abbr="{$bkmrk.icon}"><img src="nats_images/{if $bkmrk.icon}ico{$bkmrk.icon}-quicklinks.gif{else}bookmark-list.png{/if}" alt="image description" /></td>
                                            <td class="tab-column left-align col_{counter}" abbr="{$bkmrk.name|convlang}">{$bkmrk.name|convlang}</td>
                                            <td class="tab-column" nowrap abbr="{$bkmrk.link}">
                                                <a href="{$bkmrk.link}">{#ViewPage#}</a>
                                                | <a href="#" class="removeBkmrk">{#Remove#}</a>
                                            </td>
                                        </tr>

                                        {foreachelse}
                                        <tr class="data-row-even last-row">
                                            <td class="tab-column left-align" colspan="4">{#NoQuicklinks#}</td>
                                        </tr>
                                        {/foreach}
                                    </tbody>

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


<!-- END QUICKLINKS PAGE -->