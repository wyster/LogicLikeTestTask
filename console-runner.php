<?php declare(strict_types=1);

while (true) {
    echo date('H:i:s') . ' ';
    system('php public/index.php', $result);
    sleep(1);
}