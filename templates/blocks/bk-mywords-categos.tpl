<{foreach item=cat from=$block.categos}>
	<div class="<{cycle values="even,odd"}>" style="padding-left: <{$cat.indent*5}>px;">
		<a href="<{$cat.link}>" title="<{$cat.name}>"><{$cat.name}></a><{if $cat.posts>0}> (<strong><{$cat.posts}></strong>)<{/if}>
	</div>
<{/foreach}>
