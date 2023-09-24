{* Include any Necessary JavaScript *}
{literal}
<script>
    $(document).ready(function() {
        //enable the submit button on change
        $("#srch-shortname, #srch-description").change(function() {
            $("#filter-submit").removeClass('DisableSubmit');
            $("#filter-submit").prop('disabled', false);
        });
        //enable the submit button on keyup
        $("#srch-shortname, #srch-description").keyup(function() {
            $("#filter-submit").removeClass('DisableSubmit');
            $("#filter-submit").prop('disabled', false);
        });
        //enable the submit button on change
        $("#inline-shortname, #inline-description").change(function() {
            $("#inline-search-submit").removeClass('DisableSubmit');
            $("#inline-search-submit").prop('disabled', false);
        });
        //enable the submit button on keyup
        $("#inline-shortname, #inline-description").keyup(function() {
            $("#inline-search-submit").removeClass('DisableSubmit');
            $("#inline-search-submit").prop('disabled', false);
        });
        //enable the submit button on change
        $(".edit-form-text-short, .edit-form-text").change(function() {
            $("#Save_NewCampaign").removeClass('DisableSubmit');
            $("#Save_NewCampaign").prop('disabled', false);
        });
        //enable the submit button on keyup
        $('.edit-form-text-short, .edit-form-text').keyup(function() {
            $("#Save_NewCampaign").removeClass('DisableSubmit');
            $("#Save_NewCampaign").prop('disabled', false);
        });
        //remove the search title
        $("#inline-shortname, #inline-description").focus(function() {
            var curVal = $('#inline-shortname').val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $('#inline-shortname').val('');
                $('#inline-shortname').removeClass('DisableEdit');
            }
            var curVal = $('#inline-description').val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $('#inline-description').val('');
                $('#inline-description').removeClass('DisableEdit');
            }
            return false;
        });
        //remove the search title
        $("#inline-shortname, #inline-description").blur(function() {
            var curVal = $('#inline-shortname').val();
            var curVal2 = $('#inline-description').val();
            if (curVal == '' && curVal2 == '') {
                $('#inline-shortname').val('{/literal}{#Search#}{literal}...');
                $('#inline-description').val('{/literal}{#Search#}{literal}...');
                $('#inline-shortname').addClass('DisableEdit');
                $('#inline-description').addClass('DisableEdit');
            }
            return false;
        });
        $("#inline-search-submit").click(function() {
            var curVal = $('#inline-shortname').val();
            var curVal2 = $('#inline-description').val();
            if (curVal == '{/literal}{#Search#}{literal}...') {
                $('#inline-shortname').val('');
            }
            if (curVal2 == '{/literal}{#Search#}{literal}...') {
                $('#inline-description').val('');
            }
            return true;
        });

        //setup tooltips
        $(".input-entry").tooltip({
            offset: [-10, 100],
            predelay: 600,
            delay: 0,
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

        //setup tooltips for actions
        $(".camp-actions").tooltip({
            offset: [-15, 43],
            predelay: 800,
            delay: 0,
            tipClass: 'small-tooltip',
            layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        }).dynamic();

        //highlight sortable column
        $(".reorder_link").hover(function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#camp-table > tbody > tr").children(".col_" + column).addClass('orderby-hover');
            return false;
        }, function() {
            var colId = $(this).attr('id');
            var colIdParts = colId.split('_');
            var column = colIdParts[1];
            $("#camp-table > tbody > tr").children(".col_" + column).removeClass('orderby-hover');
        });

    });
</script>
{/literal}

{* Page Title *}
<div class="AffiliateCampaignsPage text-block">
    <h1>{#PageTitle#}<a href="#" id="default_minimize_page_description" {if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
    <p{if !empty($usr.default_minimize_page_description)} style="display: none;" {/if}>{#PageDesc#}</p>
</div>

<div class="AffiliateCampaignsFormNav">

    {* What is our default Count *}
    {if $smarty.request.count}{assign var="campCount" value=$smarty.request.count}
    {elseif $usr.default_campaigns_count_per_page}{assign var="campCount" value=$usr.default_campaigns_count_per_page}
    {else}{assign var="campCount" value="25"}{/if}

    {* Display the Create Campaign Form and List Existing Campaigns *}
    {display_account_campaigns count_sticky="default_campaigns_count_per_page" order_sticky="default_campaigns_orderby" start=$smarty.request.camp_start count=$campCount}

</div>