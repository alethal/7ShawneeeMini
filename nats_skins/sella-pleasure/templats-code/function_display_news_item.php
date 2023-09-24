{* Display the Single News Article *}
{*<div class="box">*}
    {*<div class="heading">*}
        {*<div class="hold">*}

            {* Column Header *}
            <h2>{#DisplayNewsArticle#}</h2>

            {*
        </div>*}
        {*</div>*}
    {*<div class="content">*}

        <div class="c">
            <div class="standard-block">
                <div class="display-content">

                    {* News Articles are always returned as Array, Should Be Only one Entry *}
                    {foreach from=$news item=n key=nid}

                    {* Display Headline in Header Bar *}
                    <div class="section_header2">
                        {$n.headline|truncate:90}
                        <span>{$n.publish|nats_local_date}</span>
                    </div>

                    {* Display the News Body *}
                    <div style="display: inline-block; padding: 0 10px 0 0; max-width: 20%;background: rgba(39,39,39,.5); vertical-align: top; padding: 2% 0 0 0; margin-right: 2px;"><img src="https://sellapleasure.com/img-smp/mystery-product-box.png"></div>
                    <div style="display: inline-block; padding: 0 10px 0 0; max-width: 75%; background: rgba(39,39,39,.5);">
                        <div class="news-body">
                            <div class="inner-body" style="vertical-align: middle; padding-top: 1%;">{$n.body}</div>
                        </div>
                    </div>

                    {* Spacer between Boxes *}
                    <div class="inner-clear-separator"></div>

                    {* End Loop *}
                    {/foreach}

                </div>
            </div>
        </div>
        {*
    </div>*}
    {*</div>*}