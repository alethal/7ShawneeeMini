<!-- START LINKCODES PAGE -->

{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>
<div class="LinkCodeAni">

    <object type="image/svg+xml" data="https://sellapleasure.com/img-smp/using-link-codes11.svg">

        <img src="https://sellapleasure.com/img-smp/using-link-codes11.svg" />

    </object>
</div>
{* Display the Linkcodes *}
{list_linkcodes}

<!-- END LINKCODES PAGE -->