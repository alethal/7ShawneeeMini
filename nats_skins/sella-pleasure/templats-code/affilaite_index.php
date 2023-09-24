{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#WelcomeTo#} {$config.NICE_NAME}, {#PageDesc#}</p>
</div>




{*include file="nats:include_clock31" - special featured product*}




{HAGAN_CUSTOM_AFFILIATE_DASHBOARD}

{if 0}
<div id="hagan_data" class="hagan_custom_affiliate_dashboard" onload="initialDash()">
    {HAGAN_CUSTOM_AFFILIATE_DASHBOARD}
</div>
{/if}


{if 0}
<h1>
    <a href="#" onclick="{hagan_ajax_call myvar=1}">hagan test 1</a><br>
    <a href="#" onclick="{hagan_ajax_call myvar=2}">hagan test 2</a><br>
    <a href="#" onclick="{hagan_ajax_call myvar=3}">hagan test 3</a><br>
    <a href="#" onclick="{hagan_ajax_call myvar=4}">hagan test 4</a><br>
    <a href="#" onclick="{hagan_ajax_call myvar=5}">hagan test 5</a><br>
</h1>

<div id="hagan_clicks"></div>
<div id="hagan_refunds"></div>
<div id="hagan_revenue"></div>
{/if}


{literal}
<!--
<script>

$(window).on('load',function(){
    //break at Array
    var wordtobreak = 'Array';
    
    //add break
    $('#hagan_data h3').html($('#hagan_data h3').html().replaceAll(wordtobreak+' ',wordtobreak+'<br>'));
});


</script>
-->

{/literal}


{* Dashboard Revamp *}
{include file="nats:include_clock2"}



<br>

{include file="nats:include_clock33"}

{* floating news box
{include file="nats:affiliate_news6"}
*}


{* Spacer between Boxes *}
<div class="clear-separator"></div>