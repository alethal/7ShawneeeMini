{*
10045 - Changed view_banner.php to $config.VIEW_BANNER_SCRIPT
12114 - Use lastPayDate and lastPayAmount
*}
{* load the language settings *}
{use_language_file section=$smarty.request.page|default:'account'}

{display_payment_summary data_only=1}

<div class="user-box">
    <div class="photo">
        <a href="/internal.php?page=account"><img src="{if isset($over.fullname)}{if $over.avatar_ext}{$config.VIEW_BANNER_SCRIPT}?filename=avatar_{$loginid}.{$over.avatar_ext}{else}/nats_images/avatar_0.jpg{/if}{elseif empty($TMMid) && $usr.avatar_ext}{$config.VIEW_BANNER_SCRIPT}?filename=avatar_{$loginid}.{$usr.avatar_ext}{elseif $usr.avatar_path}https://{$usr.avatar_path}{else}/nats_images/avatar_0.jpg{/if}" alt="image description" width="48" height="48" /></a>
    </div>
    <div class="info">
        <strong class="name">{$fullname}</strong>
        <p><a href="mailto:{$account_info.email}">{$account_info.email}</a></p>
        <address>{$account_info.city}, {$account_info.state}</address>
    </div>
</div>
<ul class="info-list">
    <li>
        <dl>
            <dt>{#PaymentMethod#}:</dt>
            <dd>{$account_info.payvia_info_nice}</dd>
        </dl>
        <div class="link"><a href="/internal.php?page=account#PayViaForm">{#Change#}</a></div>
    </li>
    <li>
        <dl>
            <dt>{#AccountBalance#}:</dt>
            <dd>{$myPaymentSummary.earned-$myPaymentSummary.paid|currency_format:2}</dd>
        </dl>
        <div class="link"><a href="/internal.php?page=payments">{#Viewmore#}</a></div>
    </li>
    <li>
        <dl>
            <dt>{#MinimumPayout#}:</dt>
            <dd>{$account_info.minimum_payout|currency_format:2}</dd>
        </dl>
        <div class="link"><a href="/internal.php?page=account#PayViaForm">{#Change#}</a></div>
    </li>
    <li>
        <dl>
            <dt>{#LastLogin#}:</dt>
            <dd>{$account_info.last_login|nats_local_date}</dd>
        </dl>
        <div class="login">{$account_info.last_login_ip}</div>
    </li>
    <li>
        <dl>
            <dt>{#LastPayment#}:</dt>
            <dd>{if $lastPayDate}{$lastPayDate|nats_local_date}{else}{#Never#}{/if}</dd>
        </dl>
        <div class="price">{$lastPayAmount|currency_format:2}</div>
    </li>
    <li class="list-last"></li>
</ul>
<div class="dashbox-link">
    <a href="internal.php?page=account">{#ViewMyAccount#}</a>
</div>