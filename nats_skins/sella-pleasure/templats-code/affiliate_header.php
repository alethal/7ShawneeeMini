{*
10045 - Changed view_banner.php to $config.VIEW_BANNER_SCRIPT
10441 - added 'make start page' link
12138 - Added deeplink generator page
12128 - Added landing page breakdown for stats
NATS-106 - Added Error checks for Terms of Service configuration
*}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=10" />
    <title>{$config.NICE_NAME}</title>
    <link rel="stylesheet" type="text/css" href="nats_builder.css?skinid={$_skinid}" />
    {* Inlcude JS File with all includes *}
    <script type="text/javascript" src="jscript/aff_all.js"></script>
    <script type="text/javascript" src="jscript/jquery.main.js"></script>
    <link rel="icon" type="image/x-icon" href="https://sellapleasure.com/img-smp/favicon/favicon.ico">
    <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css" /><![endif]-->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap" rel="stylesheet">


    {literal}
    <style>
        .showToday.affiliateResult {
            display: block;
        }
    </style>
    {/literal}



    {include file="nats:include_analytics-doc-head"}



    {use_language_file section=$smarty.request.page|default:'dashboard'}
    <script>
        var linkurl = "{$smarty.server.REQUEST_URI}";
        var linkname = "{$smarty.request.page|default:'dashboard'|ucwords}";
        {
            literal
        }

        function removeBookmarkAction(bkmrkid) {
            //remove the bookmark
            $.post('ajax_data.php', {
                'function': 'ajax_update_affiliate_bookmark',
                'bookmark_id': bkmrkid
            }, function(data) {
                if (data == 'OK') {
                    $("#bkmrk_" + bkmrkid).remove();
                    $(".edit_bookmark").attr("id", 'create_bkmrk');
                    $(".edit_bookmark").html('+ {/literal}{#BookmarkThisPage#}{literal}');
                    $(".bookmark-set").removeClass('bookmark-unset');
                    {
                        /literal}{if $smarty.request.page == 'quicklinks'}location.reload();{/if
                    } {
                        literal
                    }
                }
            });
            return false;
        }

        function notificationPopup(url, subject, body, notification) {

            //display the popup to submit the bookmark
            var txt = {
                state0: {
                    html: '<div class="title">' + notification + ' ' + subject + '</div><table class="prompt-table" sty;e="width:500px;"><tr><td align="left">' + body + '</td></tr></table>',
                    buttons: {
                        '{/literal}{#CLOSE#}{literal}': true
                    },
                    focus: 1,
                    submit: function(e, v, m, f) {
                        $.prompt.close();
                        return true;
                    }
                }
            }
            $.prompt(txt);
        }

        var mospacer = false;

        function hide_navigation() {
            if (!mospacer) return false;
            //still on the spacer, hide the nav
            $("#nav li .sub-nav").each(function() {
                $(this).children("ul").hide();
                $(this).hide();
            });
            return true;
        }

        $(document).ready(function() {
                    //set/remove bookmark
                    $(".edit_bookmark").click(function() {
                        var curId = $(this).attr("id");
                        if (curId == 'create_bkmrk') {

                            //display the popup to submit the bookmark
                            var txt = {
                                state0: {
                                    html: '<div class="title">{/literal}{#QuicklinkDetails#}{literal}</div><table class="prompt-table"><tr><td align="left" colspan="2"><span id="title_error" class="error-text"></span></td></tr><tr><td align="right">{/literal}{#Title#}{literal}:</td><td><input type="text" id="titleField" name="title" value="' + linkname + '" style="width: 200px;"></td></tr><tr><td align="right">{/literal}{#Icon#}{literal}:</td><td><div class="quick-icon-option"><input type="radio" name="icon" value="1"></div><div class="quick-icon-opt-img"><img src="nats_images/ico1-quicklinks.gif"></div><div class="quick-icon-option"><input type="radio" name="icon" value="2"></div><div class="quick-icon-opt-img"><img src="nats_images/ico2-quicklinks.gif"></div><div class="quick-icon-option"><input type="radio" name="icon" value="3"></div><div class="quick-icon-opt-img"><img src="nats_images/ico3-quicklinks.gif"></div><div class="quick-icon-option"><input type="radio" name="icon" value="4"></div><div class="quick-icon-opt-img"><img src="nats_images/ico4-quicklinks.gif"></div><div class="quick-icon-option"><input type="radio" name="icon" value="5"></div><div class="quick-icon-opt-img"><img src="nats_images/ico5-quicklinks.gif"></div><div class="quick-icon-option"><input type="radio" name="icon" value="6"></div><div class="quick-icon-opt-img"><img src="nats_images/ico6-quicklinks.gif"></div></td></tr></table>',
                                    buttons: {
                                        '{/literal}{#CANCEL#}{literal}': false,
                                        '{/literal}{#SAVEQUICKLINK#}{literal}': true
                                    },
                                    focus: 1,
                                    submit: function(e, v, m, f) {
                                        if (!v) $.prompt.close();
                                        else if (!f.title) {
                                            $("#titleField").addClass('edit-form-error');
                                            $("#title_error").html('{/literal}{#TitleRequired#}{literal}');
                                            return false;
                                        } else {
                                            $.post('ajax_data.php', {
                                                'function': 'ajax_update_affiliate_bookmark',
                                                'url': linkurl,
                                                'name': f.title,
                                                'icon': f.icon
                                            }, function(data) {

                                                if (data == 'FAIL URL') {
                                                    $.prompt.getStateContent('state0').find('#titleField').addClass('edit-form-error');
                                                    $.prompt.getStateContent('state0').find('#title_error').html('{/literal}{display_language_message message="ERRORCODE64::Link"}{literal}');

                                                    return false;
                                                } else if (data == 'FAIL TITLE') {

                                                    $.prompt.getStateContent('state0').find('#titleField').addClass('edit-form-error');
                                                    $.prompt.getStateContent('state0').find('#title_error').html('{/literal}{display_language_message message="ERRORCODE52:Title"}{literal}');

                                                    return false;
                                                } else if (data != 'EXISTS' && data != 'FAIL') {
                                                    if (f.icon) var linkImage = 'ico' + f.icon + '-quicklinks.gif';
                                                    else var linkImage = 'bookmark-list.png';
                                                    $(".bookmark-ul").append('<li id="bkmrk_' + data + '"><a href="' + linkurl + '" title="' + f.title + '"><img src="nats_images/' + linkImage + '" /><strong>' + f.title.substring(0, 36) + '</strong></a><a href="#" class="remove_bookmark" onClick="removeBookmarkAction(' + "'" + data + "'" + ')" id="remove_' + data + '" title="{/literal}{#RemoveBookmarkDesc#}{literal}">X</a></li>');
                                                    $(".edit_bookmark").attr("id", 'bkmrkid_' + data);
                                                    $(".edit_bookmark").html('- {/literal}{#RemoveBookmark#}{literal}');
                                                    $(".bookmark-set").addClass('bookmark-unset');
                                                    if ($("#bkmrk_0")) $("#bkmrk_0").remove();
                                                    {
                                                        /literal}{if $smarty.request.page == 'quicklinks'}location.reload();{/if
                                                    } {
                                                        literal
                                                    }
                                                    $.prompt.close();
                                                }
                                            });
                                            return false;
                                        }
                                        $.prompt.close();
                                        return false;
                                    }
                                }
                            }
                            $.prompt(txt);

                        } else {
                            var idParts = curId.split('_');
                            removeBookmarkAction(idParts[1]);
                        }
                        return false;
                    });
                    $(".mopad-up").each(function() {
                        var dnct = $(this).parents(".sub-nav-child").parent().prevAll().size();
                        var setht = (dnct * 100) + 10;
                        $(this).css('height', setht + '%');
                        $(this).css('top', (setht * -1) + '%');
                    });
                    $(".mopad-up, .mopad-left").hover(function() {
                        mospacer = true;
                        setTimeout("hide_navigation()", 500);
                    }, function() {
                        mospacer = false;
                    });
                    $("#setStartPageLink").click(function() {
                            {
                                /literal}
                                var currentURL = "{$script}?{rebuild_query type="
                                both " without="
                                make_start_page "}&make_start_page=1";
                                $.get(currentURL);
                                $("#setStartPageDiv").html("");
                                $(".close-action").unbind('click');
                                var noticeHTML = '<div class="action-message type-notice"><div class="action-header"><div class="action-title">{#SetStartPageNotice#}</div><a href="#" class="close-action">{#CLOSE#}</a></div><div class="action-details"><br></div></div>';
                                {
                                    literal
                                }
                                $("#main").prepend(noticeHTML);
                                $(".close-action").bind('click', function() {
                                    $(this).parent().parent().remove();
                                    return false;
                                });
                            });
                    });
                {
                    /literal}
    </script>
    {include file="nats:include_google_analytics"}



    <!-- Styles -->
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/style.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/style-responsive.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/vertical-rhythm.min.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/magnific-popup.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/owl.carousel.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/animate.min.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/splitting.css">
    <link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/YTPlayer.css">



    <link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/sellapleasure.css" />
    <link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/news-floater.css" />
    <link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/stats.css" />
    <!-- !Styles -->


</head>
<body{if $severe_popup} onload="javascript:notificationPopup('internal.php?page=messages&folder=notifications&id={$severe_data.notificationid}', '{$severe_data.subject}','{$severe_data.body}','{$severe_data.notificationid}');" {/if}">
    {include file="nats:include_analytics-doc-body-tagmanager"}
    <div id="wrapper">
        <div id="header">
            <div class="bar">
                <div class="holder">
                    <strong class="logo"><a href="index.php"><!--sellapleasure-700x80-cssv2.svg-->
                            <img src="https://sellapleasure.com/img-smp/sellapleasure-ani-logoRdec2022.svg" alt="Sell A Pleasure" width="100%" height="auto" class="logo-white"></a>{*{$config.NICE_NAME}*}</a></strong>
                    <div class="info-box">
                        <div class="company-logo">
                            <a href="/internal.php?page=account"><img src="{if isset($over.fullname)}{if $over.avatar_ext}{$config.VIEW_BANNER_SCRIPT}?filename=avatar_{$loginid}.{$over.avatar_ext}{else}/nats_images/avatar_0.jpg{/if}{elseif empty($TMMid) && $usr.avatar_ext}{$config.VIEW_BANNER_SCRIPT}?filename=avatar_{$loginid}.{$usr.avatar_ext}{elseif $usr.avatar_path}https://{$usr.avatar_path}{else}/nats_images/avatar_0.jpg{/if}" alt="" width="48" height="48" /></a>
                        </div>
                        <div class="box">
                            <h4>{$usr.company}</h4>
                            <ul>
                                {if $TMMid && $TMMid_username}<li>{#TMMidLoginNotice#}: <strong>{$TMMid_username}</strong></li>
                                {else}<li>{#LoginNotice#} <strong>{$over.fullname|default:$fullname}</strong></li>{/if}
                                <li><a href="internal.php?page=account&view=display_settings">{#Settings#}</a></li>
                                <li><a href="index.php?logout=1">{#Logout#}</a></li>
                            </ul>
                            <p class="messages"><span>{#YouHave#} {if $new_msg_count}<a href="internal.php?page=messages">{/if}{$new_msg_count|default:0} {#UnreadMsg#}{if $new_msg_count}</a>{/if} | {if $new_notification_count}<a href="internal.php?page=messages&folder=notifications">{/if}{$new_notification_count|default:0} {#UnreadNotify#}{if $new_notification_count}</a>{/if}</span></p>
                            {foreach from=$usr.bookmarks item="bkmrk" key="bkmrkid"}
                            {if $smarty.server.REQUEST_URI == $bkmrk.link|escape}{assign var="bkMarked" value=$bkmrkid}{/if}
                            {/foreach}
                            {if $bkMarked}<div class="bookmark-set bookmark-unset"><a href="#" class="edit_bookmark" id="bkmrkid_{$bkMarked}">- {#RemoveBookmark#}</a></div>
                            {else}<div class="bookmark-set"><a href="#" class="edit_bookmark" id="create_bkmrk">+ {#BookmarkThisPage#}</a></div>{/if}
                            {if !$is_startpage}<div class="start-page-set" id="setStartPageDiv"><a href="javascript:;" id="setStartPageLink"><img src="nats_images/home_16.gif" title="Set Start Page" alt="Set Start Page"></a></div>{/if}
                        </div>
                    </div>
                    <div class="nav-bar">
                        <div class="hold">
                            <ul id="nav" class="internal-nav">
                                <li>
                                    <a href="internal.php"><strong>{#Dashboard#}</strong><span>{#DashboardDesc#}</span></a>
                                </li>
                                <li>
                                    <a href="internal.php?page=stats"><strong>{#Statistics#}</strong><span>{#StatisticsDesc#}</span></a>
                                    <ul class="sub-nav">
                                        <li>
                                            <a href="internal.php?page=stats&view=date" class="isParent"><strong>{#ByDate#}<span class="parent-triangle"></span></strong></a>
                                            <ul class="sub-nav-child">
                                                <li>
                                                    <div class="mopad-up"></div><a href="internal.php?page=stats&view=date&period=2"><strong>{#Today#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=3"><strong>{#Yesterday#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=0"><strong>{#ThisPeriod#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=1"><strong>{#LastPeriod#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=5"><strong>{#ThisMonth#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=11"><strong>{#LastMonth#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=date&period=17"><strong>{#Past30Days#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=period&period=12"><strong>{#Past60Days#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=period&period=13"><strong>{#Past90Days#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=stats&view=month&period=6"><strong>{#ThisYear#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div>
                                                    <div class="mopad-down"></div><a href="internal.php?page=stats&view=year&period=7"><strong>{#AllTime#}</strong></a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="internal.php?page=stats&view=site" class=""><!-- isParent --><strong>{#BySite#}<!--span class="parent-triangle"></span--></strong></a>
                                            <!--ul class="sub-nav-child">
											<li><div class="mopad-up"></div><a href="internal.php?page=stats&view=site"><strong>{#BySite#}</strong></a></li>
											<li><div class="mopad-left"></div><div class="mopad-down"></div><a href="internal.php?page=stats&view=tour"><strong>{#ByTour#}</strong></a></li>-->
                                            </ul-->
                                        </li>
                                        <li>
                                            <a href="internal.php?page=stats&view=campaign" class=""><!-- isParent --><strong>{#ByCampaign#}<!--span class="parent-triangle"></span--></strong></a>
                                            <!--ul class="sub-nav-child">
											<li><div class="mopad-up"></div><a href="internal.php?page=stats&view=campaign"><strong>{#ByCampaign#}</strong></a></li>
											<li><div class="mopad-left"></div><div class="mopad-down"></div><a href="internal.php?page=stats&view=tag"><strong>{#ByTag#}</strong></a></li>
										</ul>-->
                                        </li>
                                        <li><a href="internal.php?page=stats&view=program"><strong>{#ByProgram#}</strong></a></li>


                                        <!--<li><a href="internal.php?page=stats&view=country"><strong>{#ByDemographic#}</strong></a></li>
									<li>
										<a href="internal.php?page=stats&view=adtool"{* class="isParent"*}><strong>{#ByAdtool#}{*<span class="parent-triangle"></span>*}</strong></a>
										{*
										<ul class="sub-nav-child">
											{foreach from=$possible_adtools_types item=adtype key=tid}
												<li><a href="internal.php?page=stats&view=adtool&filter_adtypeid={$tid}"><strong>{$adtype.name|convlang}</strong></a></li>
											{/foreach}
										</ul>
										*}
									</li>-->
                                        <li><a href="internal.php?page=stats&view=refurl"><strong>{#ByReferringUrl#}</strong></a></li>

                                        <!--{if !empty($config.ALLOW_LANDING_PAGE_REPORTING)}
										<li><a href="internal.php?page=stats&view=landing_page"><strong>{#ByLandingPageUrl#}</strong></a></li>
									{/if}
									<li><a href="internal.php?page=stats&view=other"><strong>{#OtherIncome#}</strong></a></li>
-->

                                        <li><a href="internal.php?page=referrals"><strong>{#AffiliateReferrals#}</strong></a></li>
                                        {if !empty($config.DISPLAY_V3_STATS)}<li><a href="internal.php?page=stats&view=v3"><strong>v3{#Statistics#}</strong></a></li>{/if}
                                    </ul>
                                </li>
                                <li>
                                    <a href="internal.php?page=adtools"><strong>{#AdTools#}</strong><span>{#AdToolsDesc#}</span></a>
                                    <ul class="sub-nav">
                                        <!--li><a href="internal.php?page=campaigns&section=adtools"><strong>{#Campaigns#}</strong></a></li-->
                                        <li><a href="internal.php?page=codes"><strong>{#Linkcodes#}</strong></a></li>
                                        {if !empty($config.ALLOW_LANDING_PAGE_REPORTING)}
                                        <li><a href="internal.php?page=deep_links"><strong>{#DeepLinkGenerator#}</strong></a></li>
                                        {/if}
                                        {foreach from=$possible_adtools_views item="adcat" key="catid"}
                                        {if $catid != 101}
                                        {assign var="topset" value="0"}
                                        <li>
                                            <a href="internal.php?page=adtools&category={$catid}{if $catid == 2}&typeid=2{/if}" class="isParent"><strong>{$adcat.description|convlang}<span class="parent-triangle"></span></strong></a>
                                            <ul class="sub-nav-child">
                                                {foreach from=$possible_adtools_types item="adtype" key="tid" name="adlist"}
                                                {if $adtype.adtool_category_id == $catid || ($catid == 2 && $tid == 101)}
                                                {if $tid == 2}
                                                <li>{if empty($topset)}<div class="mopad-up"></div>{assign var="topset" value="1"}{else}<div class="mopad-left"></div>{/if}<a href="internal.php?page=adtools&category={$adtype.adtool_category_id}&typeid={$tid}"><strong>{$adtype.description|convlang} ({#List#})</strong></a></li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=adtools&category={$adtype.adtool_category_id}&typeid={$tid}&view=dump"><strong>{$adtype.description|convlang} ({#Dump#})</strong></a>
                                                </li>
                                                {else}
                                                <li>{if empty($topset)}<div class="mopad-up"></div>{assign var="topset" value="1"}{else}<div class="mopad-left"></div>{/if}<a href="internal.php?page=adtools&category={$adtype.adtool_category_id}&typeid={$tid}"><strong>{$adtype.description|convlang}</strong></a></li>
                                                {/if}
                                                {/if}
                                                {/foreach}
                                            </ul>
                                        </li>
                                        {/if}
                                        {/foreach}
                                    </ul>
                                </li>
                                <li>
                                    <a href="internal.php?page=account"><strong>{#MyAccount#}</strong><span>{#MyAccountDesc#}</span></a>
                                    <ul class="sub-nav">
                                        <li><a href="internal.php?page=account&view=details"><strong>{#AccountDetails#}</strong></a></li>
                                        <li>
                                            <a href="internal.php?page=account&view=display_settings" class="isParent"><strong>{#Settings#}</strong><span class="parent-triangle"></span></a>
                                            <ul class="sub-nav-child">
                                                <li>
                                                    <div class="mopad-up"></div><a href="internal.php?page=account&view=display_settings"><strong>{#DisplaySettings#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=account&view=email_settings"><strong>{#EmailSettings#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div>
                                                    <div class="mopad-down"></div><a href="internal.php?page=account&view=notify_settings"><strong>{#NotificationSettings#}</strong></a>
                                                </li>
                                                {if $config.AFFILIATE_VERIFY_DETAILS==2 || $config.AFFILIATE_VERIFY_DEFAULTS==2 || $config.AFFILIATE_VERIFY_SETTINGS==2 ||$config.AFFILIATE_VERIFY_PAYVIA==2 || $config.AFFILIATE_VERIFY_PAYVIA_INFO==2}<li>
                                                    <div class="mopad-left"></div>
                                                    <div class="mopad-down"></div><a href="internal.php?page=account&view=verification_settings"><strong>{#VerificationSettings#}</strong></a>
                                                </li>{/if}
                                            </ul>
                                        </li>
                                        <li><a href="internal.php?page=account&view=changes"><!--strong>{#AccountChanges#}</strong--><strong>Privacy &amp; Security</strong></a></li>
                                        <!--li><a href="internal.php?page=account&view=loginlog"><strong>{#LoginHistory#}</strong></a></li-->
                                        <li>
                                            <a href="internal.php?page=messages" class="isParent"><strong>{#Messages#}</strong><span class="parent-triangle"></span></a>
                                            <ul class="sub-nav-child">
                                                <li>
                                                    <div class="mopad-up"></div><a href="internal.php?page=messages&folder=messages"><strong>{#Inbox#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=messages&folder=notifications"><strong>{#SystemNotifications#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=messages&folder=sent"><strong>{#Sent#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div>
                                                    <div class="mopad-down"></div><a href="internal.php?page=messages&folder=trash"><strong>{#Trash#}</strong></a>
                                                </li>
                                            </ul>
                                        </li>
                                        <!--li><a href="internal.php?page=quicklinks"><strong>{#ManageQuickLinks#}</strong></a></li-->
                                        <li><a href="internal.php?page=campaigns"><strong>{#Campaigns#}</strong></a></li>
                                        <li><a href="internal.php?page=payments"><strong>{#PaymentHistory#}</strong></a></li>
                                        {* Check if we have rewards *}
                                        {reward_points assign="myPoints"}
                                        {if $bonus_earned}
                                        <li>
                                            <a href="internal.php?page=rewards" class="isParent"><strong>{#BonusRewards#}</strong><span class="parent-triangle"></span></a>
                                            <ul class="sub-nav-child">
                                                <li>
                                                    <div class="mopad-up"></div><a href="internal.php?page=rewards"><strong>{#AvailableRewards#}</strong></a>
                                                </li>
                                                <li>
                                                    <div class="mopad-left"></div><a href="internal.php?page=rewards&view=purchases"><strong>{#PreviousRewardPurchases#}</strong></a>
                                                </li>
                                            </ul>
                                        </li>
                                        {/if}
                                    </ul>
                                </li>
                                <li>
                                    <a href="internal.php?page=support"><strong>{#Support#}</strong><span>{#SupportDesc#}</span></a>
                                    <ul class="sub-nav">
                                        <li><a href="internal.php?page=support"><strong>{*{#SupportRequest#}*}CONTACT US</strong></a></li>
                                        <li><a href="internal.php?page=support&view=help"><strong>{*{#HelpFAQ#}*}ABOUT US</strong></a></li>
                                        <li><a href="internal.php?page=support&view=NATShelp"><strong>FAQ</strong></a></li>
                                        <li><a href="/internal.php?page=support&view=NATShelp3"><strong>How to use sellapleasure</strong></a></li>
                                        {*<li><a href="internal.php?page=support&view=resources"><strong>{#Resources#}</strong></a></li>*}
                                        <li><a href="internal.php?page=support&view=terms"><strong>{#TermsConditions#}</strong></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="main">

            {* If the Affiliate is Unable to Receive Payments, lets Warn Them *}
            {if isset($withheldPayments.reasons) && $withheldPayments.reasons|@count}
            <div class="action-message type-notice">
                <div class="action-header">
                    <div class="action-title">{#PaymentWarning#}</div>
                    <a href="#" class="close-action">{#CLOSE#}</a>
                </div>
                <div class="action-details">
                    {foreach from=$withheldPayments.reasons key="key" item="details"}
                    {if $key == 'w9'}
                    {if $withheldPayments.reasons.$key == 1}{#MissingW9Info#}{/if}
                    {if $withheldPayments.reasons.$key == 2}{#ReceivedW9#}{/if}
                    {elseif $key == 'manual_approval'}{#AccountNeedsApproval#}
                    {elseif $key == 'no_payvia'}{#MissingPayviaInfo#}
                    {elseif $key == 'tos'}
                    {if $withheldPayments.reasons.$key == 1}{#ToSNeedsAgree#}{/if}
                    {if $withheldPayments.reasons.$key == 2}{#ToSMUSTAgree#}{/if}
                    {/if}
                    <br>
                    {/foreach}
                </div>
            </div>
            {/if}

            {* If the Affiliate is Unable to promote with CCBill Paid, lets Warn Them *}
            {if isset($missing_ccbill_program_ids)&& $missing_ccbill_program_ids|@count}
            <div class="action-message type-notice">
                <div class="action-header">
                    <div class="action-title">{#MissingCCBillWarning#}</div>
                    <a href="#" class="close-action">{#CLOSE#}</a>
                </div>
                <div class="action-details">
                    {foreach from=$missing_ccbill_program_ids key="key" item="program"}
                    {#MissingCCBillID#} {$program.account_id} ({$program.name}). {#MissingCCBillIDLink#}<a href="/internal.php?page=codes&programid={$key}">{#MissingCCBillIDLinkText#}</a>
                    <br>
                    {/foreach}
                </div>
            </div>
            {/if}

            {* If the You are Overridden, Lets make it clear *}
            {if isset($over.username)}
            <div class="action-message type-notice">
                <div class="action-header">
                    <div class="action-title">{#YouareOverridden#}</div>
                    <a href="#" class="close-action">{#CLOSE#}</a>
                </div>
                <div class="action-details">
                    {#YouareOverriddenAs#}: <b>{$over.username}</b><br><br>
                    {#YouareOverriddenMessage#}
                </div>
            </div>
            {/if}