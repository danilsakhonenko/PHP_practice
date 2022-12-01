<!DOCTYPE html>
<html>
    <head>
    </head>
    <style type="text/css">
        .main { 
            position:absolute;
            width: 45%;
            left: 30%;
            top: 5%;
            text-align: center;
        }
        .button{
            width: 100px;
            height: 50px;
        }
    </style>
    <body bgcolor="#A4B3B6">
        <div class = "main">
        <?php
        function setText($str, $x, $y,$size){
            global $image;
            $color = imagecolorallocate($image, 0, 0, 0);
            imagettftext($image, $size, 0, $x, $y, $color, 'comic.ttf', $str);
        }
        function createPng($src){
            $image = imagecreatefrompng($src);
            imagesavealpha($image, true);
            return $image;
        }
        $filename = date('m-Y').'.png';
        if (!file_exists($filename)){
            $image = createPng('template.png');
            $backgroundColor = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $backgroundColor);
            setText(date('Y'),210,95,28);
            setText(date('F'),915,90,28);
            $date_string = '01'.date('-m-Y');
            $timestamp = strtotime($date_string);
            $dayNum =intval(date("N", $timestamp));
            $days_count = date('t');
            $y = 360;
            $x = 152;
            for($i = 1; $i<=$days_count; $i++){
                if ($dayNum>7){
                    $dayNum=1;
                    $y = $y+145;
                }
                setText($i, $x*$dayNum , $y,45);
                $dayNum++;
            }
            imagepng($image, $filename);
            imagedestroy($image);
        }
        ?>
        <h2>Лист календаря для текущего месяца:</h2>
        <form method="post">
            <p><?php  echo '<img src="'.$filename.'" width="100%" height="100%"/>'; ?></p>
            <input name='download' type="submit" value="Сохранить" class="button"/>
        </form>
        <?php
        if (isset($_POST['download'])){
            ob_end_clean();
            header('Content-Disposition: Attachment;filename=' . basename($filename));
            $image = createPng($filename);
            header("Content-type: image/x-png");
            imagepng($image);
            imagedestroy($image);
            exit();
        }
        ?>
    </div>
    </body>
</html>
