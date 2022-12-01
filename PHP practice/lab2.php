<!DOCTYPE html>
<html>
<head>
<title>lab2</title>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="style.css">
</head>
    <body>
    <div class="main">
        <h4>Выбор файла</h4>
        <p>Выберите файл для поиска</p>
        <form method="post" enctype="multipart/form-data" width="500px">
            <input type="file" name="filename"/><br/><br/>
            <input type="submit" value="Найти"/>
        </form>
        <?php
            function getWords($string)
            {
                if (preg_match_all("/\b(\w+)\b/ui", $string, $matches)) {
                    return $matches[1];
                }
                return array();
            }
            if ($_FILES && $_FILES["filename"]["error"]== UPLOAD_ERR_OK)
            {
                $name = $_FILES["filename"]["name"];
                move_uploaded_file($_FILES["filename"]["tmp_name"], $name);
                $file = fopen($name, 'r') or die("Не удалось открыть файл");
                $count = 0;
                echo "<h4>Результаты поиска:</h4>";
                while(!feof($file))
                {
                    $str = htmlentities(fgets($file));
                    $words = getWords($str);
                    for ($i=0; $i < count($words) ; $i++) {
                        $char = mb_substr($words[$i], 0, 1, 'UTF-8');
                        if (preg_match('/^\w+'.$char.'$/u', $words[$i])) {
                            $str = str_replace($words[$i],'<b>'.$words[$i].'</b>',$str);
                            echo $str."<br/>";
                            $count += 1;
                            break;
                        }
                    }
                }
                echo "<br/>";
                if ($count == 0) echo "<p>Строки со словами, которые начинаются и заканчиваются одинаково не найдены</p> <br />";
                fclose($file);
            }
        ?> 
    </div>
</body>
</html>
