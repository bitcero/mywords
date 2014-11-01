<{if $shownav}>
<nav class="mwpostnav">
    <{if $prev_post}><span class="prev"><a href="<{$prev_post.link}>">&laquo; <{$prev_post.title}></a></span><{/if}>
    <{if $next_post}><span class="next"><a href="<{$next_post.link}>"><{$next_post.title}> &raquo;</a></span><{/if}>
    <a href="<{$mw_url}>"><{$lang_lang_homemw}></a>
</nav>
<{/if}>
<article class="mwitem">
    <header>
        <h1><{$post.title}></h1>
        <{if $post.published!=''}>
            <span class="mwinfotop">
            <{$post.published}>. <{if $edit_link}> | <{$edit_link}><{/if}>
            | <{$lang_reads}>
            <{if $post.trackback!=''}>
            | <a href="<{$post.trackback}>"><{$lang_trackback}></a>
            <{/if}>
            </span>
        <{/if}>
    </header>
    <section class="mwtext">
        <{if $enable_images && $post.image != ''}><img src="<{$post.image}>" alt="<{$post.title}>" class="img-responsive full_post_image" /><{/if}>
        <{$post.text}>
        <span class="mwbooks">
        <{if ($post.bookmarks) }>
            <{foreach item=bm from=$post.bookmarks key=i}>
                <a href="javascript:;" onclick="mwOpenWindow('<{$bm.link}>','bookmark',600,400);" title="<{$bm.alt}>"><img src="<{$xoops_url}>/modules/mywords/images/icons/<{$bm.icon}>" alt="<{$bm.alt}>" /></a>
            <{/foreach}>
        <{/if}>
        </span>
    </section>
    <footer class="mwfoot">
        <{$lang_taggedas}>
        <{foreach item=tag from=$post.tags key=i}>
        <a href="<{$tag.permalink}>"><{$tag.tag}></a><{if ($i<count($post.tags)-1)}>, <{else}><{/if}>
        <{/foreach}>
        <{if ($post.cats) }> |
        <{$lang_postedin}>
        <{assign var="i" value=0}>
        <{foreach item=cat from=$post.cats}>
        <a href="<{$cat.permalink}>"><{$cat.name}></a><{if ($i<count($post.cats)-1)}>, <{else}><{/if}>
        <{assign var="i" value=$i+1}>
        <{/foreach}>
        <{/if}>
    </footer>
</article>
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
<{include file="db:rmc-comments-form.html"}>
<!-- /End comments -->
<{/if}>

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