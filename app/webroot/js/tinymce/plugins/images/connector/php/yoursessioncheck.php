<?php
if ((isset($_SESSION['user'])) && (isset($_SESSION['user']['id'])) && (in_array($_SESSION['user']['id'], [32, 4, 5, 108, 81, 1773, 3049, 8472, 17865, 18856, 25252]))) {
} else {
    echo 'В доступе отказано';
    exit();
}
