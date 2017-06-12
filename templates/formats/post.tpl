<article class="mwitem mw-normal-post" id="mwitem-<{$post.id}>">
    <{include file="db:mywords-post-header.tpl"}>
    <section class="mwtext">
        <{if $enable_images}>
        <a href="<{$post.link}>" title="<{$post.title}>"><img src="<{$post.image}>" alt="<{$post.title}>" class="post_image img-responsive" /></a>
        <{/if}>
        <{$post.text}>
        <{if ($post.continue) }><span class="mwcontinue"><a href="<{$post.link}>#mwmore"><{$post.lang_continue}></a></span><{/if}>
    </section>
    <footer class="mwfoot">
        <span class="mwcats">
        <{if ($post.cats) }>
            <{$lang_postedin}>
            <{assign var="i" value=0}>
            <{foreach item=cat from=$post.cats}>
                <a href="<{$cat.permalink}>"><{$cat.name}></a><{if ($i<count($post.cats)-1)}>, <{else}><{/if}>
                <{assign var="i" value=$i+1}>
            <{/foreach}>
        <{/if}>
        </span>
        <span class="mwtags">
        <{$lang_taggedas}>
        <{foreach item=tag from=$post.tags key=i}>
            <a href="<{$tag.permalink}>"><{$tag.tag}></a><{if ($i<count($post.tags)-1)}>, <{else}><{/if}>
        <{/foreach}>
        </span>
        <span class="mwbooks">
        <{if ($post.bookmarks) }>
            <{foreach item=bm from=$post.bookmarks key=i}>
                <a href="javascript:;" onclick="mwOpenWindow('<{$bm.link}>','bookmark',600,400);" title="<{$bm.alt}>"><img src="<{$xoops_url}>/modules/mywords/images/icons/<{$bm.icon}>" alt="<{$bm.alt}>" /></a>
            <{/foreach}>
        <{/if}>
        </span>
    </footer>

</article>