<{if $video.type == 'other'}>
<div class="embed-responsive embed-responsive-16by9">
    <{$video.src|replace:'%class%':'embed-responsive-item'}>
</div>
<{else}>
    <div class="embed-responsive embed-responsive-16by9">
        <iframe class="embed-responsive-item" src="<{$video.src}>" <{$video.attrs}>></iframe>
    </div>
<{/if}>