<!-- BEGIN ACCOUNT TABS -->
{literal}
<style>
    .news_count {
        width: 110px;
    }
</style>
{/literal}
<div class="current">
    <div class="announce_wrapper">
        <div class="announce anpad"><span>&nbsp;</span></div>
        <div class="news_count">&nbsp;</div>
    </div>
    <div class="ticker ticker2" id="newsticket">
        <div class="mya_tab">
            {if $smarty.request.view == "details" || !$smarty.request.view}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=details"><b>Account Detail</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=details">Account Detail</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
        <div class="mya_tab">
            {if $smarty.request.view == "defaults"}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=defaults"><b>Display Defaults</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=defaults">Display Settings</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
        <div class="mya_tab">
            {if $smarty.request.view == "settings"}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=settings"><b>Account Settings</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=settings">Account Settings</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
        <div class="mya_tab">
            {if $smarty.request.view == "changes"}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=changes"><b>Recent Changes</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=changes">Recent Changes</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
        <div class="mya_tab">
            {if $smarty.request.view == "campaign"}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=campaign"><b>Campaigns</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=campaign">Campaigns</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
        <div class="mya_tab">
            {if $smarty.request.view == "notify"}
            <div class="mya_tab_left" style="background-image: url(../nats_images/tab_left.png);"></div>
            <div class="mya_tab_middle" style="background-image: url(../nats_images/tab_bg.png);"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=notify"><b>Messages</b></a></div>
            <div class="mya_tab_right" style="background-image: url(../nats_images/tab_right.png);"></div>
            {else}
            <div class="mya_tab_left"></div>
            <div class="mya_tab_middle"><a href="internal.php?{rebuild_query using=" GET" without="view" }&view=notify">Messages</a></div>
            <div class="mya_tab_right"></div>
            {/if}
        </div>
    </div>
</div>

<!-- END ACCOUNT TABS -->