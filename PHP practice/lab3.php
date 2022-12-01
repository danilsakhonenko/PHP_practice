<!DOCTYPE html>
<html>
<head>
    <title>lab3</title>
    <meta content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="base.css">
</head>
<body>
    <div class="base">
        <h4>Выбор файла</h4>
        <p>Выберите файл для замены</p>
        <form method="post" enctype="multipart/form-data" width="300px">
            <input type="file" name="filename"/><br/><br/>
            <input type="submit" value="Найти и заменить"/>
        </form>
        <div class="result">
            <?php
            function getEnglishWords($string)
            {
                if (preg_match_all("/\b([a-zA-z]{1,})\b/ui", $string, $matches)) {
                    return $matches[1];
                }
                return array();
            }
            function change_register($word){
                if(strlen($word)<2){
                    return $word;
                }
                for ($i=1; $i < strlen($word); $i+=2) {
                    $char = strtoupper(mb_substr($word, $i, 1)); 
                    $word = substr_replace($word, $char, $i,1);
                }
                return $word;
            }
            if ($_FILES && $_FILES["filename"]["error"]== UPLOAD_ERR_OK)
            {
                move_uploaded_file($_FILES["filename"]["tmp_name"], 'new_file.txt');
                $file_array = file('new_file.txt');
                $file = fopen('new_file.txt', 'w') or die("Не удалось открыть файл");
                echo "<h5>Результаты замены:</h5>  <p>";
                foreach ($file_array as $line) {
                    $words = getEnglishWords($line);
                    foreach ($words as $word) {
                        $new_word = change_register($word);
                        $line = preg_replace('/\b'.$word.'\b/', $new_word, $line);
                    }
                    echo $line."<br/>";
                    fwrite($file,$line);
                }
                fclose($file);
                echo "</p><p></p><a href='download.php'>Сохранить файл</button><p></p>";
            }
            ?> 
        </div>
    </div>
</body>
</html>
