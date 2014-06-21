<?php

class MywordsDesigniaPreload
{
    public function eventDesigniaGetNavItems(){
        echo '<li class=nav_item>
                    <a href="'.XOOPS_URL.'/modules/mywords/admin/">
                        <img src="'.RMCURL.'/themes/designia/images/blog.png" alt="Blog" />
                        <p>Blog</p>
                    </a>
                </li>';
    }
}