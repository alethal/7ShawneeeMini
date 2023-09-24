{*
	12831	- Added postback tab
*}
<!-- START MY ACCOUNT PAGE -->

{* Include JS necessary for this page *}
{literal}
	<script>
		$(document).ready(function(){
			
			//setup our form toggle
			$('.ModifyOn').click(function(){
				var formName = $(this).attr('id');
				var parts = formName.split('_');
				
				$(this).addClass('DisableSubmit');
				$(this).prop('disabled', true);

				$('.'+parts[1]+'_Display').hide();
				$('.'+parts[1]+'_Edit').show();
				$('#Save_'+parts[1]).show();
				$('#Cancel_'+parts[1]).show();
				return false;
			});
			
			//setup cancel form edit
			$('.CancelModify').click(function(){
				var formName = $(this).attr('id');
				var parts = formName.split('_');
				
				$(this).hide();
				$('.'+parts[1]+'_Edit').hide();
				$('#Save_'+parts[1]).hide();

				$('.'+parts[1]+'_Display').show();
				$('#Modify_'+parts[1]).removeClass('DisableSubmit');
				$('#Modify_'+parts[1]).prop('disabled', false);
				
				$('.'+parts[1]+'_Display').each(function(){
					var elemid = $(this).attr('id');
					var elParts = elemid.split('-');
					var origVal = $("#"+elemid+"-val").val();
					if(origVal) $("#"+elParts[1]).val(origVal);
					else $("#"+elParts[1]).val('');
				});
				
				return false;
			});
			
			//perform revert pending change (ajax)
			$('.remove-pending-change').click(function(){
				var formName = $(this).attr('id');
				var parts = formName.split('-');
				var origVal = $("#"+formName+"-val").val();
				$("#disp-"+parts[1]).html(origVal);
				
				var pendCount = $('#'+parts[3]+'_Form .remove-pending-change').size();
				if(!pendCount){
					$('#Modify_'+parts[3]+'On').show();
					$('#'+parts[3]+'_Warning').hide();
				}
				
				//revert the value through ajax
				$.post("submit.php?variable_array[0]=verify&submit_function=submit_revert_change&verify[change_type]="+parts[2]+"&verify[field_name]="+parts[1]);
				
				//reset payvia options
				if(parts[2] == 2){
					update_payvia_fields(true);
				}
				return false;
			});
			
		});
	</script>
{/literal}

{* Page Title *}
<div class="text-block">
	<h1>{#PageTitle#}<a href="#" id="default_minimize_page_description"{if empty($usr.default_minimize_page_description)} class="min-page-desc">-</a>{else} class="min-page-desc min-page-desc-plus">+</a>{/if}</h1>
	{if $smarty.request.view == 'display_settings' || $smarty.request.view == 'email_settings' || $smarty.request.view == 'notify_settings'}
		<p{if !empty($usr.default_minimize_page_description)} style="display: none;"{/if}>{#SettingPageDesc#}</p>
	{elseif $smarty.request.view == 'loginlog'}
		<p{if !empty($usr.default_minimize_page_description)} style="display: none;"{/if}>{#LoginPageDesc#}</p>
	{elseif $smarty.request.view == 'changes'}
		<p{if !empty($usr.default_minimize_page_description)} style="display: none;"{/if}>{#ChangesPageDesc#}</p>
	{else}
		<p{if !empty($usr.default_minimize_page_description)} style="display: none;"{/if}>{#PageDesc#}</p>
	{/if}
</div>

{* Display Form Errors *}
{if isset($errors) && $errors|@count}
	{foreach from=$errors item="frmErrors" key="formType"}
		{if isset($frmErrors.langCodes) && $frmErrors.langCodes|@count}
			<div class="action-message type-error">
				<div class="action-header">
					<div class="action-title">{#FormError#}</div>
					<a href="#" class="close-action">{#CLOSE#}</a>
				</div>
				<div class="action-details">
					{foreach from=$frmErrors.langCodes item="errcode" key="errid"}
						{* Call the Language Message Display for Error Codes *}
						{display_language_message message=$errcode}<br>
					{/foreach}
				</div>
			</div>
		{/if}
	{/foreach}
{elseif isset($smarty.request.cache) && $smarty.request.action == 'submit_export_to_tmmid'}
	<div class="action-message type-success">
		<div class="action-header">
			<div class="action-title">{#ExportSuccess#}</div>
			<a href="#" class="close-action">{#CLOSE#}</a>
		</div>
		<div class="action-details">
			{#ExportSuccessMessage#}
		</div>
	</div>	
{elseif isset($smarty.request.cache) && $smarty.request.action == 'submit_import_from_tmmid'}
	<div class="action-message type-success">
		<div class="action-header">
			<div class="action-title">{#ImportSuccess#}</div>
			<a href="#" class="close-action">{#CLOSE#}</a>
		</div>
		<div class="action-details">
			{#ImportSuccessMessage#}
		</div>
	</div>	
{elseif isset($smarty.request.cache) && $smarty.request.action == 'submit_verify_changes'}
	<div class="action-message type-success">
		<div class="action-header">
			<div class="action-title">{#VerificationComplete#}</div>
			<a href="#" class="close-action">{#CLOSE#}</a>
		</div>
		<div class="action-details">
			{if isset($smarty.request.denied)}
				{#VerificationCompleteDeniedMessage#}
			{else}
				{#VerificationCompleteMessage#}
			{/if}
		</div>
	</div>	
{/if}

{* Setup two column display *}
<div class="twocolumn AffiliateMyAccount">
	<div class="c">
		<div class="box-hold">


{* floating news box 
{include file="nats:affiliate_news2"}
*}

		
			{* Display the Left Column 
			{include file="nats:include_affiliate_account_sidebar"}
*}
			
			{* Display Main Column with Account Page *}
			<div class="box">
				{if !empty($smarty.request.view) && in_array($smarty.request.view, ','|explode:'display_settings,email_settings,notify_settings,verification_settings,postback_settings')}
					<div class="section-nav">
						<div class="section-tab{if $smarty.request.view == 'display_settings'} active-tab{/if}">
							{if $smarty.request.view != 'display_settings'}<a href="internal.php?page=account&view=display_settings">{/if}
							{#DisplaySettings#}
							{if $smarty.request.view != 'display_settings'}</a>{/if}
						</div>
						
						{if $smarty.request.view != 'display_settings' && $smarty.request.view != 'email_settings'}
							<div class="section-tab-separator">&nbsp;</div>
						{/if}
						
						<div class="section-tab{if $smarty.request.view == 'email_settings'} active-tab{/if}">
							{if $smarty.request.view != 'email_settings'}<a href="internal.php?page=account&view=email_settings">{/if}
							{#EmailSettings#}
							{if $smarty.request.view != 'email_settings'}</a>{/if}
						</div>
						
						{if $smarty.request.view != 'notify_settings' && $smarty.request.view != 'email_settings'}
							<div class="section-tab-separator">&nbsp;</div>
						{/if}
						
						<div class="section-tab{if $smarty.request.view == 'notify_settings'} active-tab{/if}">
							{if $smarty.request.view != 'notify_settings'}<a href="internal.php?page=account&view=notify_settings">{/if}
							{#NotificationSettings#}
							{if $smarty.request.view != 'notify_settings'}</a>{/if}
						</div>
						
						{* used for showing the separator with tabs that will not always display *}
						{assign var="last_tab" value="notify_settings"}
						
						{* Get all of the postback details to know if we should show the next sections *}
						{display_edit_account_settings data_only="1"}

						{if !empty($verify)}
							{if $smarty.request.view != 'verification_settings' && $smarty.request.view != 'notify_settings'}
								<div class="section-tab-separator">&nbsp;</div>
							{/if}
							
							<div class="section-tab{if $smarty.request.view == 'verification_settings'} active-tab{/if}">
								{if $smarty.request.view != 'verification_settings'}<a href="internal.php?page=account&view=verification_settings">{/if}
								{#VerificationSettings#}
								{if $smarty.request.view != 'verification_settings'}</a>{/if}
							</div>
							
							{assign var="last_tab" value="verification_settings"}
						{/if}
						
						{if !empty($allowed_postback_types)}
							{if $smarty.request.view != $last_tab && $smarty.request.view != 'postback_settings'}
								<div class="section-tab-separator">&nbsp;</div>
							{/if}
							
							<div class="section-tab{if $smarty.request.view == 'postback_settings'} active-tab{/if}">
								{if $smarty.request.view != 'postback_settings'}<a href="internal.php?page=account&view=postback_settings">{/if}
								{#PostbackSettings#}
								{if $smarty.request.view != 'postback_settings'}</a>{/if}
							</div>
						
							{assign var="last_tab" value="postback_settings"}
						{/if}
					</div>
					<div class="heading">
						<div class="hold">
							{if $smarty.request.view == 'display_settings'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=SettingsDisplay#SettingsDisplay" target="_blank" class="helpbtn" title="{#HelpAccountSettingsDisplay#}"><span>?</span></a>
							{elseif $smarty.request.view == 'email_settings'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=SettingsEmail#SettingsEmail" target="_blank" class="helpbtn" title="{#HelpAccountSettingsEmail#}"><span>?</span></a>
							{elseif $smarty.request.view == 'notify_settings'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=SettingsNotify#SettingsNotify" target="_blank" class="helpbtn" title="{#HelpAccountSettingsNotify#}"><span>?</span></a>
							{elseif $smarty.request.view == 'verification_settings'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=SettingsVerify#SettingsVerify" target="_blank" class="helpbtn" title="{#HelpAccountSettingsVerification#}"><span>?</span></a>
							{elseif $smarty.request.view == 'postback_settings'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=SettingsPostback#SettingsPostback" target="_blank" class="helpbtn" title="ADD THIS TITLE"><span>?</span></a>
							{/if}
						</div>
					</div>
				{else}
					<div class="heading">
						<div class="hold">
							{if $smarty.request.view == 'loginlog'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=LoginHistory#LoginHistory" target="_blank" class="helpbtn" title="{#HelpAccountLoginHistory#}"><span>?</span></a>
								<h2>{#LoginHistory#}</h2>
							{elseif $smarty.request.view == 'changes'}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=AcctChangeLog#AcctChangeLog" target="_blank" class="helpbtn" title="{#HelpAccountAcctChangeLog#}"><span>?</span></a>
								<h2>{*{#AccountChanges#}*} PRIVACY &amp; SECURITY</h2>
							{else}
								<a href="/internal.php?page=support&view=NATShelp&section=account&article=AccountDetails#AccountDetails" target="_blank" class="helpbtn" title="{#HelpAccountAccountDetails#}"><span>?</span></a>
								<h2>{#AccountDetails#}</h2>
							{/if}
						</div>
					</div>
				{/if}
				<div class="content">
					<div class="c">
						<div class="standard-block">
							<div class="display-content">
								{* Which view are we on *}
								{if $smarty.request.view == 'display_settings'}
									{* Display all of the display settings *}
									{display_edit_defaults}
									
								{elseif $smarty.request.view == 'email_settings' || $smarty.request.view == 'notify_settings' || $smarty.request.view == 'verification_settings'}
									{* Display all of the email settings *}
									{display_edit_account_settings}
									
								{elseif $smarty.request.view == 'postback_settings'}
									{* Display all of the email settings *}
									{display_edit_account_settings tpl="function_edit_account_postback_settings"}
									
								{elseif $smarty.request.view == 'loginlog'}
									{* Display all of the recent account logins *}
									{display_account_loginlog count=$smarty.request.count|default:25 start=$smarty.request.start}
									
								{elseif $smarty.request.view == 'changes'}

{* Display the Change Password Form *}
<h2 style="color: #1D2838; text-transform: uppercase; font-size: 1.5em; text-align: left; padding: 20px 0 10px 0;">Change your password</h2>
{display_edit_password}

<h2 style="color: #1D2838; text-transform: uppercase; font-size: 1.5em; text-align: left; padding: 20px 0 10px 0;">Account Changes</h2>													
									{* What is the default count? *}
									{if $smarty.request.count}{assign var="changeCount" value=$smarty.request.count}
									{elseif $usr.default_account_changes_count_per_page}{assign var="changeCount" value=$usr.default_account_changes_count_per_page}
									{else}{assign var="changeCount" value="25"}{/if}
								
									{* Display all of the recent account changes *}
									{display_account_change_history count=$changeCount start=$smarty.request.start count_sticky="default_account_changes_count_per_page" order_sticky="default_account_changes_orderby"}


<h2 style="color: #1D2838; text-transform: uppercase; font-size: 1.5em; text-align: left; padding: 20px 0 10px 0;">Login Log</h2>
{* Display all of the recent account logins *}
									{display_account_loginlog count=$smarty.request.count|default:25 start=$smarty.request.start}
									



<p{if !empty($usr.default_minimize_page_description)} style="display: none;"{/if}>{#LoginPageDesc#}</p>
									
								{else}
									{* Display the linked TMMid *}
									{display_linked_tmmid_info}
								
									{* Display the Account Details Form *}
									{display_edit_account_details}
									
									{assign var="unverified" value="0"}
									{assign var="unapproved" value="0"}
									
									{* Display the Edit PayVia Form *}
									{display_edit_payvia}
									
									{* If a US Affiliate, provide the W9 Form and Upload *}
									{display_edit_w9}
									
									{assign var="unverified" value="0"}
									{assign var="unapproved" value="0"}
									
									{if empty($TMMid)}
										{* Display the Change Password Form *}
										{display_edit_password}
									
										{* Display the Avitar Setup *}
										{display_edit_avitar}
									{/if}
								{/if}
								
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
	<div class="b"></div>
</div>

<!-- END MY ACCOUNT PAGE -->