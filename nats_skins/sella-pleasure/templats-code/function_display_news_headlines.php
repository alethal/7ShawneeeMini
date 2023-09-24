{*
11192 - Cleaned PHP notifications
*}
{if !empty($params.ajaxInc)}

{* load the language settings *}
{if empty($smarty.request.page)}
{assign var='page' value='stats'}
{else}
{assign var='page' value=$smarty.request.page}
{/if}
{use_language_file section=$page}

{* Setup Javascript necessary for this page *}
{literal}
<script>
    //start the jquery on loads
    $(document).ready(function() {

        //add the tooltip for remove links
        $(".news-headline_{/literal}{$params.ajaxInc}{literal}").tooltip({
            offset: [-10, 200],
            predelay: 600,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

    });
</script>
{/literal}

{/if}


<ul class="icons-list">

    {* Loop Through News Headlines *}
    {foreach from=$news item="nitem" key="nid" name="nloop"}

    {* Display Headline *}
    <li{if $smarty.foreach.nloop.first} class="list-first" {/if}>
        <a href="{if isset($external_display)}external{else}internal{/if}.php?page=news&newsid={$nid}" class="news-headline{if !empty($params.ajaxInc)}_{$params.ajaxInc}{/if}" title="<b>{$nitem.headline|escape}</b><br><span>{$nitem.publish|nats_local_date}</span><br><hr>{$nitem.body|truncate:200|escape}"><img src="nats_images/ico-news.gif" alt="{$nitem.headline}" /><strong>{if !empty($long_healines)}{$nitem.headline|truncate:78}{else}{$nitem.headline|truncate:65}{/if}</strong></a>
        </li>

        {* End Loop *}
        {/foreach}

        <li class="list-last"></li>
</ul>
<div class="dashbox-link">
    <a href="{if isset($external_display)}external{else}internal{/if}.php?page=news">{#ViewAllNews#}</a>
</div>