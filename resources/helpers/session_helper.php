<?php

session_start();

function hello()
{
    echo('hello world');
}

// Group helper
function activeGroup($group_id = '')
{
    // Set up session with group_id
    if(!empty($group_id)){
        $_SESSION['group_id'] = $group_id;
    // Unset session with empty params
    } else{
        unset($_SESSION['group_id']);
    }
}
