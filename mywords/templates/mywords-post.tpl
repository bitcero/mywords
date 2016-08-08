<{if $shownav}>
<nav class="mwpostnav">
    <{if $prev_post}><span class="prev"><a href="<{$prev_post.link}>">&laquo; <{$prev_post.title}></a></span><{/if}>
    <{if $next_post}><span class="next"><a href="<{$next_post.link}>"><{$next_post.title}> &raquo;</a></span><{/if}>
    <a href="<{$mw_url}>"><{$lang_lang_homemw}></a>
</nav>
<{/if}>

<{include file="db:formats/`$post.format`.tpl"}>

<{$post_navbar}>

<{if $relatedPosts}>
<!-- Related Posts -->
<section class="mw-related-posts">
    <h4><{$lang_related}></h4>
    <ul>
        <{foreach item=post from=$relatedPosts}>
        <li><a href="<{$post.link}>"><{$post.title}></a></li>
        <{/foreach}>
    </ul>
</section>
<!-- End Related Posts -->
<{/if}>

<{if $comments}>
<h2 class="comments_title"><{$lang_numcoms}></h2>

<!-- Start Comments -->
<a name="comments"></a>
<{include file="db:rmc-comments-display.html"}>
<!-- /End comments -->
<{/if}>
<{$comments_form}>

<!-- Trackbacks -->
<{if $trackbacks}>
<h2 class="comments_title"><{$lang_numtracks}></h2>
<div id="trackbacks-list">
<{foreach item=tb from=$trackbacks}>
    <div class="tb_item">
        <span class="title"><{$tb.title}></span>
        <span class="blogdate"><a href="<{$tb.url}>"><{$tb.blog}></a> | <{$tb.date}></span>
        <{$tb.text}>
    </div>
<{/foreach}>
</div>
<{/if}>
<!-- /Trackbacks -->

<{if $pingnow}>
<iframe src="<{$xoops_url}>/modules/mywords/ping.php?post=<{$post.id}>" style="display: none; width: 0; height: 0;"></iframe>
<{/if}>
