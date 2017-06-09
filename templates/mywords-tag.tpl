<h1 class="mwtitles"><{$lang_taggedtitle}></h1>
<{foreach item=post from=$posts}>
    <{include file="db:mywords-single-post.tpl"}>
<{/foreach}>
<{$nav_pages}>