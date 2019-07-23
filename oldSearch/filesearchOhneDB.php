<?php
foreach (glob("*.php") as $filename) {
    echo "$filename - Größe: " . filesize($filename) . "\n";
}
?>