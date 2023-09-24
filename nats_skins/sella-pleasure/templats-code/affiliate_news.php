{*
7271 - Use News Defaults or Affiliate Settings
*}
<!-- START NEWS PAGE -->

{* Setup Javascript necessary for this page *}
{literal}
<script>
    //start the jquery on loads
    $(document).ready(function() {
        //setup the tooltips for this page
        $(".news-headline").tooltip({
            offset: [-10, 150],
            predelay: 600,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();
    });
</script>
{/literal}











{* Page Title *}
<div class="text-block">
    <h1>{$config.NICE_NAME} {#News#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if} (WHERE IS THIS COPY IN THE ADMIN?)</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

{* Setup Two Column View *}
<div class="twocolumn affiliatenewspage">
    <div class="c">
        <div class="box-hold">

            {* Display the latest headlines on left bar *}
            {if $usr.default_news_section}
            {assign var=newss value=$usr.default_news_section}
            {else}
            {* there should be a default news section Announcements *}
            {assign var=newss value=$config.DEFAULT_NEWS_SECTION}
            {/if}
            {display_news section=$newss count="1" tpl="function_display_news_sidebar_headlines"} {* default setting is 15 *}

            {* Check for Selected News Item *}
            {if $smarty.request.newsid}
            {* Display Single News Item *}
            {display_news section=$newss newsid=$smarty.request.newsid tpl="function_display_news_item"}
            {else}
            {* Display All News Items *}
            {if $smarty.get.count}{assign var="count" value=$smarty.get.count}
            {else}{assign var="count" value="10"}{/if}
            {display_news section=$newss count=$count tpl="function_display_news_full" start=$smarty.request.start}
            {/if}

        </div>
    </div>
    <div class="b"></div>
</div>

<!-- END NEWS PAGE -->