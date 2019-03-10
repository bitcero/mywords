<h1 class="mwtitles"><{$lang_postsincat}></h1>
<{foreach item=post from=$posts}>
    <{include file="db:mywords-single-post.tpl"}>
<{/foreach}>
<{$pagenav}><br>