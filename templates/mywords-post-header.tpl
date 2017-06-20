<header>
    <h1><a href="<{$post.link}>"><{$post.title}></a></h1>
    <span class="mwinfotop">
	        <span class="mwcomments">
	            <a href="<{$post.link}>#comments"><{$post.lang_comments}></a> |
	        </span>
        <{$post.published }> <{if ($post.edit)}>| <a href="<{if $xoops_isadmin}><{$xoops_url}>/modules/mywords/admin/posts.php?op=edit&amp;id=<{$post.id}><{else}><{$xoops_url}>/modules/mywords/submit.php?action=edit&amp;id=<{$post.id}><{/if}>"><{$lang_editpost}></a><{/if}>

        <{if $canReport}>
            | <a href="<{$reportLink}>" class="report-link">
                <{cuIcon icon=svg-rmcommon-report class="text-report"}>
                <{$lang_report}>
            </a>
        <{/if}>
	    </span>
</header>