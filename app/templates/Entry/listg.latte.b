{var vendorPath = $basePath . "/vendor"}
{block content}
<h1 n:block=title>Závodníci závodu {$raceid}</h1>
{control grid}

{define morestyle}
{if $ajax}
	<link href="{$vendorPath}/nprogress/nprogress.css" rel="stylesheet">
	<link href="{$vendorPath}/typeahead_bs3_less/typeahead.css" rel="stylesheet">
{/if}
<link href="{$vendorPath}/grido/css/grido.css" rel="stylesheet">
{/define}

{define morescripts}
{if $ajax}
    <script src="{$vendorPath}/js/jquery.history.js"></script>
    <script src="{$vendorPath}/typeahead/typeahead.bundle.min.js"></script>
	<script src="{$vendorPath}/nette_ajax_js/nette.ajax.js"></script>
	<script src="{$vendorPath}/nprogress/nprogress.js"></script>
	<script src="{$basePath}/js/nette.nprogress.js"></script>
{/if}
<script src="{$vendorPath}/grido/js/grido.js" type="text/javascript"></script>
{if $ajax}
    <script src="{$vendorPath}/grido/js/plugins/grido.typeahead.js"></script>
    <script src="{$vendorPath}/grido/js/plugins/grido.history.js"></script>
    <script src="{$vendorPath}/grido/js/plugins/grido.nette.ajax.js"></script>
{/if}
<script n:if="$ajax"  src="{$basePath}/js/main.ajax.js"></script>
<script n:if="!$ajax" src="{$basePath}/js/main.js" type="text/javascript"></script>
{/define}

