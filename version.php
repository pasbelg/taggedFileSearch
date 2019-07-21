<?php
    var_dump(extension_loaded('PDO' )); //should return a boolean value
    var_dump(extension_loaded('pdo_mysql')); //should return a boolean value
    var_dump(get_loaded_extensions());
?>