{*
8161 - Added clickTAG for flash banners
8170 - Link style properly changes when overriding an affiliate
8468 - Added ClickTag to hosted embedded banners
10045 - Changed view_banner.php to $config.VIEW_BANNER_SCRIPT
10288 - Default Tour Generates in NATS code when not active
11709 - Use the default tour's link domain when tour is not supplied
11727 - Changed scaling for vertical banners to use max_height when calculating scaled width
11952 - added a check against identifier details before choosing tour for adtool links
*}
{* Setup Javascript necessary for this template *}
{literal}
<script>
    function updateZipPack() {
        var zipCt = 0;
        var zipList = '';
        $(".zip_pack").each(function(index, data) {
            if ($(this).attr('checked')) {
                zipCt++;
                var bParts = $(this).attr('id').split('_');
                if (zipList) zipList += ',' + bParts[2];
                else zipList = 'zip_tool/' + bParts[3] + '/{/literal}{adtool_zip_name bulk="1" adtoolid="1" type_short=$type_details.short}{literal}.zip?pack=' + bParts[2];
            }
        });
        $("#zip_pack_count").html(zipCt);
        if (zipCt) {
            $("#zip_pack_dl_link").attr('href', zipList);
            $("#zip_pack_dl_link").fadeTo("fast", 1);
        } else {
            $("#zip_pack_dl_link").fadeTo("fast", 0.2);
            $("#zip_pack_dl_link").attr('href', '#');
        }
        return false;
    }

    //start the jquery on loads
    $(document).ready(function() {

        $(".zip_check_all").click(function() {
            $(".zip_pack").attr('checked', 1);
            updateZipPack();
            return false;
        });

        $(".zip_check_none").click(function() {
            $(".zip_pack").attr('checked', 0);
            updateZipPack();
            return false;
        });

        $(".zip_pack").change(function() {
            updateZipPack();
            return false;
        });

        $(".display_full_banner").click(function() {
            var idParts = $(this).attr('id').split('_');
            var imgpath = $(this).attr('href');

            if (idParts[4] == 'swf') var largeImage = '<embed height="' + idParts[5] + '" width="' + idParts[6] + '" name="plugin" src="' + imgpath + '" type="application/x-shockwave-flash"/>';
            else var largeImage = '<img src="' + imgpath + '" height="' + idParts[5] + '" width="' + idParts[6] + '">';

            $.prompt(largeImage, {
                buttons: {},
                persistent: false
            });

            return false;
        });

    });
</script>
{/literal}


<div class="mainblock" id="displayTools">
    <div class="heading">
        <div class="hold">
            <a href="#" class="open-close"><span>-</span></a>
            {if $typeid == 1}
            <a href="/internal.php?page=support&view=NATShelp&section=adtools&article=ImageBanners#ImageBanners" target="_blank" class="helpbtn" title="{#HelpAdToolsImageBanners#}"><span>?</span></a>
            {elseif $typeid == 9}
            <a href="/internal.php?page=support&view=NATShelp&section=adtools&article=FlashBanners#FlashBanners" target="_blank" class="helpbtn" title="{#HelpAdToolsFlashBanners#}"><span>?</span></a>
            {/if}
            <h2>{$type_details.description|convlang}</h2>
        </div>
    </div>
    <div class="content" id="form_content_area">
        <div class="c">
            <div class="standard-block">
                <div class="display-content">

                    {* Include the header *}
                    {assign var="oD" value="_old_date"}
                    {assign var="oldField" value="default_adtools_$category$oD"}
                    {assign var="fullImagesView" value="1"}
                    {include file="nats:include_adtool_header_details"}

                    {rebuild_query using="GET" without="orderby" assign="curOrderLink"}
                    <table class="table-container" cellpadding=0 cellspacing=0 id="toolTable">

                        {* Display the Inline Search Form *}
                        {display_adtool_form get_bounds="height,width"}

                        {* Display Header Row (Sortable Links) *}
                        <thead>
                            <tr class="header-row2 table-order-header">
                                <td class="tab-column nohover header-first" colspan="2">{#Banner#}</td>
                                <td class="tab-column nohover zip_col_head">{#Zip#}</td>
                                <td class="tab-column{if $orderby == '999' || $orderby == '-999'} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != '-999'}-{/if}999#displayTools" class="reorder_link" id="reorder_{counter start=0}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !='-999' } style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby=='999' } style="display: block;" {/if}></div>
                                        </div>{#Added#}
                                    </a>
                                </td>
                                <td class="tab-column nohover">{#Group#}</td>
                                <td class="tab-column{if $orderby == '998' || $orderby == '-998'} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != '-998'}-{/if}998#displayTools" class="reorder_link" id="reorder_{counter}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !='-998' } style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby=='998' } style="display: block;" {/if}></div>
                                        </div>{#Layout#}
                                    </a>
                                </td>
                                {assign var="ordPlus" value=$adtool_types[$type.short].fields.size.adtool_field_id}
                                {math assign="ordMinus" equation="x*-1" x=$adtool_types[$type.short].fields.size.adtool_field_id}
                                <td class="tab-column{if $orderby == $ordPlus || $orderby == $ordMinus} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != $ordMinus}-{/if}{$ordPlus}#displayTools" class="reorder_link" id="reorder_{counter}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !=$ordMinus} style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby==$ordPlus} style="display: block;" {/if}></div>
                                        </div>{#FileSize#}
                                    </a>
                                </td>
                                {assign var="ordPlus" value=$adtool_types[$type.short].fields.height.adtool_field_id}
                                {math assign="ordMinus" equation="x*-1" x=$adtool_types[$type.short].fields.height.adtool_field_id}
                                <td class="tab-column{if $orderby == $ordPlus || $orderby == $ordMinus} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != $ordMinus}-{/if}{$ordPlus}#displayTools" class="reorder_link" id="reorder_{counter}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !=$ordMinus} style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby==$ordPlus} style="display: block;" {/if}></div>
                                        </div>{#Height#}
                                    </a>
                                </td>
                                {assign var="ordPlus" value=$adtool_types[$type.short].fields.width.adtool_field_id}
                                {math assign="ordMinus" equation="x*-1" x=$adtool_types[$type.short].fields.width.adtool_field_id}
                                <td class="tab-column{if $orderby == $ordPlus || $orderby == $ordMinus} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != $ordMinus}-{/if}{$ordPlus}#displayTools" class="reorder_link" id="reorder_{counter}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !=$ordMinus} style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby==$ordPlus} style="display: block;" {/if}></div>
                                        </div>{#Width#}
                                    </a>
                                </td>
                                {if isset($adtool_types[$type.short].fields.type.adtool_field_id)}
                                {assign var="ordPlus" value=$adtool_types[$type.short].fields.type.adtool_field_id}
                                {math assign="ordMinus" equation="x*-1" x=$adtool_types[$type.short].fields.type.adtool_field_id}
                                <td class="tab-column{if $orderby == $ordPlus || $orderby == $ordMinus} orderby-field{/if}">
                                    <a href="internal.php?{$curOrderLink}&orderby={if $orderby != $ordMinus}-{/if}{$ordPlus}#displayTools" class="reorder_link" id="reorder_{counter}">
                                        <div class="table-orderby-wrapper">
                                            <div class="table-orderby-field" {if $orderby !=$ordMinus} style="display: none;" {/if}></div>
                                            <div class="table-orderby-field-reverse" {if $orderby==$ordPlus} style="display: block;" {/if}></div>
                                        </div>{#Type#}
                                    </a>
                                </td>
                                {/if}
                                <td class="tab-column nohover header-last">{#Download#}</td>
                            </tr>
                        </thead>

                        {* Set the Dimensions for Display *}
                        {assign var=max_height value=1200}
                        {assign var=max_width value=1200}

                        <tbody>
                            {foreach from=$adtools item="tool" key="adid" name="ads"}
                            {if $smarty.request.siteid == -1}
                            {assign var=mysiteid value=$tool.ids.siteid}
                            {else}
                            {assign var=mysiteid value=$smarty.request.siteid}
                            {/if}

                            {* 11952 *}
                            {if !$smarty.request.tourid && !empty($tool.ident_details.tourid)}
                            {assign var=mytourid value=$tool.ident_details.tourid}
                            {else}
                            {assign var=mytourid value=$smarty.request.tourid|default:0}
                            {/if}

                            {nats_encode programid=$programid adtoolid=$tool.adtoolid siteid=$mysiteid tourid=$mytourid url="1" banner="1" assign="image_url"}
                            {nats_encode programid=$programid adtoolid=$tool.adtoolid siteid=$mysiteid tourid=$mytourid url="1" tour="1" assign="url"}
                            {nats_encode programid=$programid adtoolid=$tool.adtoolid siteid=$mysiteid tourid=$mytourid}
                            <tr class="data-row-{if $smarty.foreach.ads.iteration % 2 == 0}even{else}odd{/if} two-layer-top {if $smarty.foreach.ads.last}last-row{else}hover-row{/if}{if empty($usr[$oldField]) || $usr[$oldField] <= $tool.published_date} light-highlight-row{/if}{if $smarty.foreach.ads.first} first-row{/if}">
                                <td class="tab-column left-align-nopad two-layer-rowspan" rowspan="2" valign="top">
                                    {if empty($usr[$oldField]) || $usr[$oldField] <= $tool.published_date} <img src="nats_images/newItem.png" class="newItemDisplay">
                                        {/if}
                                </td>
                                <td class="tab-column center-align two-layer-rowspan" rowspan="2">
                                    <a style="background: transparent !important; background-color: transparent !important;" class="display_full_banner bannerimage" id="banner_small_img_{$tool.adtoolid}_{$tool.thumb_ext}_{$tool.height}_{$tool.width}" href="{$config.VIEW_BANNER_SCRIPT}?name={$tool.name}&filename={$tool.adtoolid}_name.{$tool.thumb_ext}&type={$tool.thumb_ext}">
                                        {if $tool.thumb_ext == 'swf'}
                                        {if $tool.height <= $max_height && $tool.width <=$max_width} {assign var=height value=$tool.height} {assign var=width value=$tool.width} {elseif $tool.height <=$max_height} {math assign=height equation="(x/y)*z" x=$tool.height y=$tool.width z=$max_width} {assign var=width value=$max_width} {elseif $tool.width <=$max_width} {math assign=width equation="(y/x)*z" x=$tool.height y=$tool.width z=$max_height} {assign var=height value=$max_height} {else} {math assign=width equation="(x/y)*z" x=$tool.width y=$tool.height z=$max_height} {assign var=height value=$max_height} {if $width> $max_width}
                                            {math assign=height equation="(x/y)*z" x=$tool.height y=$tool.width z=$max_width}
                                            {assign var=width value=$max_width}
                                            {/if}
                                            {/if}
                                            <embed height="{$height}" width="{$width}" name="plugin" src="{$config.VIEW_BANNER_SCRIPT}?name={$tool.name}&filename={$tool.adtoolid}_name.{$tool.thumb_ext}&type={$tool.thumb_ext}" class="table-img-small" type="application/x-shockwave-flash" />
                                            {else}
                                            {if $tool.height <= $max_height && $tool.width <=$max_width} {assign var=height value=$tool.height} {assign var=width value=$tool.width} {else} {assign var=height value=$max_height} {assign var=width value=$max_width} {/if} <img src="{$config.VIEW_BANNER_SCRIPT}?name={$tool.name}&filename={$tool.adtoolid}_name.{$tool.thumb_ext}&height={$height}&width={$width}&type={$tool.thumb_ext}" class="table-img-small">
                                                {/if}
                                    </a>
                                </td>
                                <td class="tab-column center-align two-layer-rowspan" rowspan="2"><input type="checkbox" name="zip_pack_{$tool.adtoolid}" id="zip_pack_{$tool.adtoolid}_{$encoded}" class="zip_pack"></td>
                                <td class="tab-column col_{counter start=0} center-align">{$tool.published_date|nats_local_date}</td>
                                <td class="tab-column center-align">{if isset($tool.groups) && $tool.groups|@count}{foreach from=$tool.groups item="grp" name="glist"}{if !$smarty.foreach.glist.first}, {/if}{$grp}{/foreach}{else}<span>{#None#}</span>{/if}</td>
                                <td class="tab-column col_{counter} center-align">{$adtool_types[$type.short].types.options[$tool.image_layout]|convlang}</td>
                                <td class="tab-column col_{counter}">{convert_bytes bytes=$tool.size}</td>
                                <td class="tab-column col_{counter}">{$tool.height}px</td>
                                <td class="tab-column col_{counter}">{$tool.width}px</td>
                                {if isset($adtool_types[$type.short].fields.type.adtool_field_id)}
                                <td class="tab-column center-align col_{counter}">{$adtool_types[$type_details.short].fields.type.mc_options[$tool.type]|convlang}</td>
                                {/if}
                                <td class="tab-column center-align"><a href="{$config.VIEW_BANNER_SCRIPT}?name={$tool.name}&filename={$tool.adtoolid}_name.{$tool.thumb_ext}&type={$tool.thumb_ext}&download=1">{#DownloadBanner#}</a></td>
                            </tr>
                            <tr class="data-row-{if $smarty.foreach.ads.iteration % 2 == 0}even{else}odd{/if} two-layer-bottom {if $smarty.foreach.ads.last}last-row{else}hover-row{/if}{if empty($usr[$oldField]) || $usr[$oldField] <= $tool.published_date} light-highlight-row{/if}">
                                <td class="tab-column" colspan="{if isset($adtool_types[$type.short].fields.type.adtool_field_id)}8{else}7{/if}">

                                    {if $config.TRACK_BANNER_IMPRESSIONS}
                                    <table style="float: right;">
                                        <tr>
                                            <td align="right">{#Image#}:</td>
                                            <td>


                                                <input class="display-link-text" type="text" id="fsloc_{$tool.adtoolid}" name="{$image_url}" value='{if $usr.over_unencoded >= 10 || (!isset($usr.over_unencoded) && $usr.unencoded >= 10)}<a href="{$url}">{if $tool.thumb_ext == ' swf'}<embed height="{$tool.height}" width="{$tool.width}" name="plugin" allowScriptAccess="always" src="{$image_url}?clickTAG={$url}" type="application/x-shockwave-flash" />{else}<img src="{$image_url}">{/if}</a>{else}{$image_url}{/if}'>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right">{#TrackingLink#}:</td>
                                            <td><input class="display-link-text" type="text" value='{$url}'></td>
                                        </tr>
                                    </table>
                                    {else}
                                    <div class="DisplayLinkWrap">
                                        <div class="DisplayLinkCopy">Link code: <span>(2x click then copy)</span></div>
                                        <input class="display-link-text" type="text" id="CopyMeValue" value='{if $usr.over_unencoded >= 10 || (!isset($usr.over_unencoded) && $usr.unencoded >= 10)}<a href="{$url}">{if $tool.thumb_ext == ' swf'}<embed height="{$tool.height}" width="{$tool.width}" name="plugin" allowScriptAccess="always" src="{$tool.name}?clickTAG={$url}" type="application/x-shockwave-flash" />{else}<img src="{$tool.name}">{/if}</a>{else}{$url}{$tool.deeplink}{/if}' readonly>
                                    </div>
                                    {/if}


                                </td>
                            </tr>
                            {foreachelse}
                            <tr class="data-row-even last-row">
                                <td class="tab-column left-align" colspan="{if isset($adtool_types[$type.short].fields.type.adtool_field_id)}11{else}10{/if}">{#NoAdtoolsFound#}</td>
                            </tr>
                            {/foreach}
                        </tbody>
                        <tfoot>
                            <tr class="footer-row">
                                <td class="tab-column" colspan="{if isset($adtool_types[$type.short].fields.type.adtool_field_id)}11{else}10{/if}">
                                    <div class="tools" id="Paginiation">
                                        {if $count == 'all'}{assign var="count" value=$adcount}{/if}
                                        {pagination start=$start count=$count total=$adcount start_field="start" tpl="function_display_pagination" offset="1"}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>