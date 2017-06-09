<article class="mwitem mw-normal-post" id="mwitem-<{$post.id}>">
    <header>
        <h1><a href="<{$post.link}>"><{$post.title}></a></h1>
	    <span class="mwinfotop">
	        <span class="mwcomments">
	            <a href="<{$post.link}>#comments"><{$post.lang_comments}></a> |
	        </span>
	        <{$post.published }> <{if ($post.edit)}>| <a href="<{if $xoops_isadmin}><{$xoops_url}>/modules/mywords/admin/posts.php?op=edit&amp;id=<{$post.id}><{else}><{$xoops_url}>/modules/mywords/submit.php?action=edit&amp;id=<{$post.id}><{/if}>"><{$lang_editpost}></a><{/if}>
	    </span>
    </header>
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