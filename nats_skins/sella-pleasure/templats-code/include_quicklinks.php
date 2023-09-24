<ul class="icons-list bookmark-ul">
    {foreach from=$usr.bookmarks item="bkmrk" key="bkmrkid" name="qlinks"}
    {if $smarty.foreach.qlinks.iteration <= 7} <li class="bkmrk_{$bkmrkid}{if $smarty.foreach.qlinks.first} list-first{/if}"><a href="{$bkmrk.link}" title="{$bkmrk.name}"><img src="nats_images/{if $bkmrk.icon}ico{$bkmrk.icon}-quicklinks.gif{else}bookmark-list.png{/if}" /><strong>{$bkmrk.name|convlang|truncate:56:"...":true}</strong></a><a href="#" onClick="removeQuicklink('{$bkmrkid}'); return false;" class="remove_bookmark{if $params.ajaxInc} remove_bookmark_{$params.ajaxInc}{/if}" title="{#RemoveBookmarkDesc#}">X</a></li>
        {/if}
        {foreachelse}
        <li class="list-first"><strong>{#NoQuicklinksAvailable#}</strong></li>
        {/foreach}
        <li class="list-last"></li>
</ul>
<div class="dashbox-link">
    <a href="internal.php?page=quicklinks" style="float: right;">{#ManageQuickLinks#}</a>
</div>