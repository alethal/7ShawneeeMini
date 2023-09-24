{literal}
<script>
    $(document).ready(function() {
        $("#campaignid").change(function() {
            var curval = $(this).val();
            if (curval == 'create') {
                $("#new_campaign_form").show();
                $("#new_campaign").focus();
            } else {
                $("#new_campaign_form").hide();
            }
            return false;
        });
        $("#new_campaign_link").click(function() {
            $("#campaignid").val('create');
            $("#new_campaign_form").show();
            $("#new_campaign").focus();
            return false;
        });

        //enable the submit button
        $(".filter-select, .filter-text").change(function() {
            $("#filter-submit").removeClass('DisableSubmit');
            $("#filter-submit").attr('disabled', 0);
        });
    });
</script>
{/literal}



<div class="twocolumn">
    <div class="c">
        <div class="box-hold">
            <div class="box first-child">
                <form action="internal.php" name="link-filter" id="link-filter">
                    <input type="hidden" name="page" value="codes">
                    <div class="left-bar-only">
                        <div class="heading">
                            <div class="hold">
                                <h2>Linkcode Settings</h2>
                            </div>
                        </div>
                        <div class="content">
                            <div class="filter-form">
                                <span>{#Campaign#}: [<a href="#" id="new_campaign_link">Create New</a>]</span>
                                <select name="campaignid" id="campaignid" class="filter-select">
                                    <option value="create">* Create New Campaign *</option>
                                    {html_options options=$campaigns selected=$params.campaign}
                                </select>
                            </div>
                            <div class="filter-form HiddenEdit" id="new_campaign_form">
                                <span>New Campaign:</span>
                                <input type="text" name="create[campaign:1:1:16:::alnum_check]" id="new_campaign" value="" class="filter-text" />
                            </div>
                            <div class="filter-form">
                                <span>{#Program#}:</span>
                                <select name="programid" class="filter-select">
                                    {foreach from=$program_names item=opt key=oid}
                                    <option value="{$oid}" {if $params.program==$oid} selected{/if}>{$opt.name|convlang}</option>

                                    {/foreach}
                                </select>
                            </div>
                            <div class="filter-form">
                                <span>Encoding:</span>
                                <select name="toggle" class="filter-select">
                                    <option value=0>Encoded Links</options>
                                    <option value=1{if $usr.unencoded==1} selected{/if}>Un-Encoded Links</options>
                                    <option value=2{if $usr.unencoded==2} selected{/if}>Shortname Links</options>
                                </select>
                            </div>
                            <div class="filter-form">
                                <div class="tools"><input type="submit" class="button DisableSubmit" disabled="1" id="filter-submit" value="APPLY CHANGES"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="box">
                <div class="heading">
                    <div class="hold">
                        <h2>{if $params.program}{$program_names[$params.program].name|convlang} {/if}Linkcodes</h2>
                    </div>
                </div>
                <div class="content">
                    <div class="c">
                        <div class="standard-block">
                            <div class="display-content">
                                {rebuild_query using="GET" without="orderby" assign="orderLink"}
                                <table class="table-container2" cellpadding=0 cellspacing=0>
                                    <tr class="header-row">
                                        <td class="tab-column left-align">Site (Tour)</td>
                                        <td class="tab-column left-align">Linkcode</td>
                                        <td class="tab-column left-align">Actions</td>
                                    </tr>
                                    {assign var="lCount" value="1"}
                                    {foreach from=$linkcodes item="proglink" key="programid"}
                                    {foreach from=$proglink item="sitelink" key="siteid"}
                                    {foreach from=$sitelink item="tourlink" key="tourid"}
                                    {if $tourlink[0][0][0]}
                                    {math assign="lCount" equation="x+1" x=$lCount}
                                    <tr class="data-row-{if $lCount % 2 == 0}even{else}odd{/if}">
                                        <td class="tab-column left-align">{$sites[$siteid]}<br><span>({$tours[$siteid][$tourid]})</span></td>
                                        <td class="tab-column left-align"><input type="text" value="{$tourlink[0][0][0]}" style="width: 398px;" readonly></td>
                                        <td class="tab-column left-align"><a href="{$tourlink[0][0][0]}" target="_blank" style="text-decoration: underline;">Link</a> <a href="internal.php?page=code_info&natscode={$tourlink[0][0][0]}" target="_blank" style="text-decoration: underline;">Details</a></td>
                                    </tr>
                                    {/if}
                                    {/foreach}
                                    {/foreach}

                                    {/foreach}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="b"></div>
</div>