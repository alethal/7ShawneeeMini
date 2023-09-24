<div style="text-align: center;"><!-- fix ie centering bug -->
    <div class="user">
        <p>{$over.username|default:$usr.username}</p><span>{if $over.fullname|default:$fullname|count_characters <= 13}{$over.fullname|default:$fullname}{else}{$over.lastname|default:$usr.lastname|truncate:13}{/if}</span>
    </div>
</div>

<div class="infobar">
    <span style="float: left;">You have: {if $new_notification_count || $new_msg_count}<a href="internal.php?page=account&view=notify" style="text-decoration: underline;">{/if}{$new_notification_count|default:0} Notifications / {$new_msg_count|default:0} Messages{if $new_notification_count || $new_msg_count}</a>{/if}</span>
    <a href="index.php?logout=1" style="padding: 2px 12px 1px 14px; background: rgb(49, 49, 66) none repeat scroll 0% 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial; color: rgb(255, 255, 255); display: block; float: right;">Logout</a>
    <span>{$over.email|default:$usr.email}</span>
</div>