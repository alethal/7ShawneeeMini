<!-- START AFFILITE REFERRAL PAGE -->

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#} {$byview|convlang}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

{* is there an affiliate id being passed in? *}
{if $smarty.request.view && empty($config.DISABLE_REFERRAL_USERNAME_DISPLAY)}
{* lookup the affiliate info *}
{display_referral_details affid=$smarty.request.view}
{/if}

{if empty($affiliate)}
{* We didn't display a specific affilate, Display the referral List *}
{display_stats_referrals order="join_date" period="7"}
{/if}

<!-- END AFFILITE REFERRAL PAGE -->