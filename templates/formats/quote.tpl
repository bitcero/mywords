<article class="mwitem mw-quote-post" id="mwitem-<{$post.id}>">
    <section class="mwtext">
        <blockquote>
            <{$post.text}>
            <footer><{$post.title}></footer>
        </blockquote>
    </section>
    <footer class="mwfoot">
        <span class="mwinfotop">
	        <span class="mwcomments">
	            <a href="<{$post.link}>#comments"><{$post.lang_comments}></a> |
	        </span>
	        <{$post.published }> <{if ($post.edit)}>| <a href="<{if $xoops_isadmin}><{$xoops_url}>/modules/mywords/admin/posts.php?op=edit&amp;id=<{$post.id}><{else}><{$xoops_url}>/modules/mywords/submit.php?action=edit&amp;id=<{$post.id}><{/if}>"><{$lang_editpost}></a><{/if}>
	    </span>
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
                <a href="javascript:;" onclick="mwOpenWindow('<{$bm.link}>','bookmark',600,400);" title="<{$bm.alt}>"><img src="<{$xoops_url}>/modules/mywords/images/icons/<{$bm.icon}>" alt="<{$bm.alt}>"></a>
            <{/foreach}>
        <{/if}>
        </span>
    </footer>

</article>