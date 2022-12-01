<?php
    if (file_exists("new_file.txt")) {
        if (ob_get_level()) {
            ob_end_clean();
        }  
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=new_file.txt');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('new_file.txt'));
        readfile('new_file.txt');
        exit;
    }
?>