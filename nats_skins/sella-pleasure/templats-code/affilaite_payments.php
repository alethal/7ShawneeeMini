<!-- START PAYMENTS PAGE -->


{* Setup necesary JavaScript *}
{literal}
<script>
    $(document).ready(function() {

        //highlight sortable column
        $(".reorder_link").hover(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#payment-table > tbody > tr").children(".col_" + column).addClass('orderby-hover');
            return false;
        }, function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#payment-table > tbody > tr").children(".col_" + column).removeClass('orderby-hover');
        });

    });
</script>
{/literal}


{* Page Title *}
<div class="text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

{* Setup Two Column Display *}
<div class="twocolumn AffiliatePayments">
    <div class="c">
        <div class="box-hold">

            {* Display the Left Column
            {include file="nats:include_affiliate_account_sidebar"}
            *}

            {*display_payment_history*}
            {display_payment_history order_sticky='default_payments_orderby'}

        </div>
    </div>
    <div class="b"></div>
</div>

<!-- END PAYMENTS PAGE -->