
{* file to extend *}
{extends file='parent:frontend/detail/buy.tpl'}

{* our namespace *}
{namespace name="frontend/AtsdArticleAccessoryDirectBuy"}



{* replace default shopware accessory placeholder *}
{block name="frontend_detail_buy_variant" append}

    {* hidden quantity field to be filled via jquery *}
    <input type="hidden" name="sAddAccessoriesQuantity" id="sAddAccessoriesQuantity" value="" />

    {* main container *}
    <div class="atsd-accessory--container"{if ( ( !is_array( $atsdAccessories ) ) or ( count( $atsdAccessories ) == 0 ) )} style="display: none !important;"{/if}>

        {* loop every group *}
        {foreach $atsdAccessories as $group}

            {* group container *}
            <div class="atsd-accessory--group-container{if $group@last} is--last{/if}">

                {* group header *}
                <div class="block-group group--header">
                    <div class="block header--column-name">{$group.name}</div>
                    <div class="block header--column-surcharge">{s name="LabelSurcharge"}Aufpreis{/s}</div>
                </div>

                {* article container *}
                <div class="group--article-container">

                    {* loop every article *}
                    {foreach $group.articles as $article}

                        {* single article container *}
                        <div class="block-group group--article">

                            {* popup with image*}
                            <div class="article--popup">
                                <div class="image-container">
                                    <img src="{$article.product->getAttribute( "atsd_accessory" )->get( "image" )}">
                                </div>
                                {$article.product->getName()}
                            </div>

                            {* quantity *}
                            <div class="block article--column-quantity">
                                {$article.quantity}x
                            </div>

                            {* name *}
                            <div class="block article--column-name">
                                <a href="{url controller='detail' action='index' sArticle=$article.product->getId()}" target="_blank">{$article.product->getName()}</a>
                            </div>

                            {* price *}
                            <div class="block article--column-price">
                                {$article.product->getAttribute( "atsd_accessory" )->get( "formattedPrice" )|currency}{s namespace="frontend/detail/buy" name="Star"}*{/s}
                            </div>

                            {* selection *}
                            <div class="block article--column-selection">

                                {* hidden quantity field *}
                                <input type="hidden" id="quantity_{$group.id}_{$article.number}" value="{$article.quantity}" />

                                {* checkbox or radio *}
                                {if $group.multiple == true}
                                    <input type="checkbox" name="checkbox_{$group.id}_{$article.number}" class="article--checkbox" id="checkbox_{$group.id}_{$article.number}" value="{$article.number}" />
                                {else}
                                    <input type="radio" name="radio_{$group.id}" class="article--radio" id="radio_{$group.id}" value="{$article.number}" />
                                {/if}

                            </div>

                        </div>

                    {/foreach}

                </div>

            </div>

        {/foreach}

    </div>

{/block}


