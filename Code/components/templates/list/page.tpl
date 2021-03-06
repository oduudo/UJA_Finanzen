{capture assign="HeadBlock"}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">{literal}
        google.charts.load('current', {packages: ['corechart']});
    {/literal}</script>
{/capture}

{capture assign="ContentBlock"}
    {include file="page_header.tpl" pageTitle=$Page->GetTitle()}
    {include file="list/page_navigator_modal.tpl"}

    {include file="page_description_block.tpl" Description=$Page->getDescription()}

    {include file="charts/collection.tpl" charts=$ChartsBeforeGrid chartsClasses=$ChartsBeforeGridClasses}

    {$PageNavigator1}

    {$Grid}

    {$PageNavigator2}

    {include file="charts/collection.tpl" charts=$ChartsAfterGrid chartsClasses=$ChartsAfterGridClasses}

{/capture}

{* Base template *}
{include file="common/list_page_template.tpl"}