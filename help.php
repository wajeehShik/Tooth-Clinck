<?php
function dd($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    echo '<script>alert(\'test\')</script>';
exit;
    die;
}