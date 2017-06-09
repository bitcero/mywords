<?php
// $Id$
// --------------------------------------------------------------
// MyWords
// Blogging System
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$mywords_permissions = array(

    /**
     * Submission privileges
     */
    'submit' => array(
        'caption' => __('Submit Posts', 'mywords'),
        'default' => 'allow'
    )

);

return $mywords_permissions;