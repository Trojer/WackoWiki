<?php

// info box
echo '<table class="inf">';
echo '<tr><td class="sign">i</td><td class="text">';
include Ut::join_path(FORMATTER_DIR, 'wiki.php');
echo '</td></tr>';
echo '</table>';