<?php

if (!function_exists('random_color')) {
    function random_color($userId = null) {
        $colors = ['#FF5733', '#33B5FF', '#28C76F', '#F012BE', '#FFB400'];
        return $colors[crc32($userId ?? rand()) % count($colors)];
    }
}
