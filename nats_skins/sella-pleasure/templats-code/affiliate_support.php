<!-- START SUPPORT PAGE -->

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>


{* floating news box *}
{*include file="nats:affiliate_news6"*}
<div class="text-block">
    <p>Please email: <a href="mailto:support@sellapleasure.com">support@sellapleasure.com</a><br><br></p>
</div>





<!-- END SUPPORT PAGE -->