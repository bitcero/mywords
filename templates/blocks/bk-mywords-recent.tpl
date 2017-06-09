<div class="mwrecent_block">
    <ul>
    <{foreach item=post from=$block.posts}>
	    <li>
		    <a href="<{$post.link}>"><{$post.title}></a><br />
            <{if $post.date}><small><{$post.date}></small><{/if}>
            <{if $post.hits!=''}><small><{if $post.date}> - <{/if}><{$post.hits}></small><{/if}>
            <{if $post.comments!=''}><small><{if $post.date}> - <{/if}><{$post.comments}></small><{/if}>
		    <{if $post.content!=''}><br /><{$post.content}><{/if}>
	    </li>
    <{/foreach}>
    </ul>
</div>