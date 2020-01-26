<?php 
function tbsuchen() {
    global $sn, $tx;
    return '<form class="tbis-search" action="' . $sn . '" method="post">' . "\n" . "\n" . tag('input type="text" class="text" name="search" size="12"') . "\n" . tag('input type="hidden" name="function" value="search"') . "\n" . ' ' . tag('input type="submit" class="submit" value="' . $tx['search']['button'] . '"') . "\n" . "\n" . '</form>' . "\n";
}

?>