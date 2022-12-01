<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Начертательная геометрия</title>
    </head>
    <style type="text/css">
        .center { 
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 30em;
        }
    </style>

    <body bgcolor="#ffdeb8">
        <?php
        error_reporting(E_ALL ^ E_WARNING);
            {
                $questions = array (
                    0 => array (
                        "Поверхность, образующаяся при движении окружности постоянного или переменного радиуса, центр которой перемещается по криволинейной направляющей, называется:",
                        "циклической",
                        "параллельного переноса",
                        "цилиндрической"
                    ),
                    1 => array (
                        "Задачи на пересечение прямой общего положения с плоскостью общего положения и пересечение двух плоскостей общего положения называются:",
                        "основными позиционными задачами",
                        "метрическими",
                        "общими"
                    ),
                    2 => array (
                        "Поверхности, у которых образующие скрещиваются, называются:",
                        "неразвертываемыми",
                        "развертываемыми",
                        "циклическими"
                    ),
                    3 => array (
                        "Поверхности, которые образуются при некотором закономерном движении прямой линии в пространстве, называются:",
                        "линейчатыми",
                        "циклическими поверхностями",
                        "поверхностями вращения"
                    ),
                    4 => array (
                        "Прямая, параллельная фронтальной плоскости проекции, — есть:",
                        "горизонталь",
                        "фронталь",
                        "проецирующая прямая"
                    ),
                    5 => array (
                        "Окружности, по которым перемещаются все точки образующей в процессе вращения вокруг оси, называются:",
                        "экватором",
                        "параллелями поверхности",
                        "меридианами"
                    ),
                    6 => array (
                        "Прямая, параллельная горизонтальной плоскости проекций, называется:",
                        "фронталью",
                        "горизонталью",
                        "общего положения"
                    ),
                    7 => array (
                        "При графическом выполнении развертки приходится спрямлять или разгибать ________, лежащие на поверхности.",
                        "перпендикулярные линии",
                        "параллельные линии",
                        "кривые линии"
                    ),
                    8 => array (
                        "Плоскость, параллельная какой-либо плоскости проекции, — это:",
                        "проецирующая плоскость",
                        "плоскость общего положения",
                        "плоскость уровня"
                    ),
                    9 => array (
                        "Задачи, решение которых связано с определением значений геометрических величин — длин отрезков, размеров углов, площадей, объемов, расстояний между геометрическими фигурами и т.д., называются:",
                        "общими",
                        "позиционными",
                        "метрическими"
                    )
                );
            
                $answers = array (
                    0 => 1,
                    1 => 1,
                    2 => 1,
                    3 => 1,
                    4 => 2,
                    5 => 2,
                    6 => 2,
                    7 => 3,
                    8 => 3,
                    9 => 3,
                );
            }
            
            { 
                function append_result($time, $correct) {
                    global $questions, $order, $user_answers, $answers;
                    unzip();
                    $file = fopen(time() . ".txt", "w");
                    $str = "[" . date("d.m.Y, H:i:s") . "]\n";
                    $str .= "ФИО: " . $_COOKIE["full_name"];
                    $str .= "\nПравильных ответов: " . $correct;
                    $str .= "\nВремя: " . $time . " секунд\n\n";
                    for ($i = 0; $i < 10; $i++) {
                        $str .= $i + 1 . " вопрос: \"" . $questions[$order[$i]][0] . "\"\n";
                        if ($answers[$order[$i]] == $user_answers[$i]) {
                            $str .= "\"" .  $questions[$order[$i]][$user_answers[$i]] . "\"\n";
                        } else {
                            $str .= "\"" .  $questions[$order[$i]][$answers[$order[$i]]] . "\"\n";
                            $str .= "Выбранный ответ: \"" .  $questions[$order[$i]][$user_answers[$i]] . "\"\n";   
                        }
                        $str .= "\n";
                    }
                    fwrite($file, $str);
                    fclose($file);
                    zip();
                }
            
                function get_results() {
                    unzip();
                    $results = "";
                    foreach (glob("*.txt") as $filename) {
                        $results .= htmlentities(file_get_contents($filename));
                        $results .= "\n";
                    }
                    zip();
                    $results = explode("\n", $results);
                    $results = implode("<br>", $results);
                    return $results;
                }

                function get_last_file() {
                    unzip();
                    $max = 0;
                    foreach (glob("*.txt") as $filename) {
                        $time = intval(explode(".", $filename)[0]);
                        if ($time > $max) {
                            $max = $time;
                        }
                    }
                    return $max . ".txt";
                }

                function get_last_result() {
                    unzip();
                    $max = 0;
                    foreach (glob("*.txt") as $filename) {
                        $time = intval(explode(".", $filename)[0]);
                        if ($time > $max) {
                            $max = $time;
                        }
                    }
                    $result = htmlentities(file_get_contents($max . ".txt"));
                    $result = explode("\n", $result);
                    $result = implode("<br>", $result);
                    zip();
                    return $result;
                }
            
                function unzip() {
                    $zip = new ZipArchive();
                    $zip->open("archive.zip", ZIPARCHIVE::CREATE);
                    $zip->extractTo(__DIR__);
                    $zip->close();
                }
            
                function zip() {
                    $zip = new ZipArchive();
                    $zip->open("archive.zip", ZIPARCHIVE::CREATE);
                    foreach (glob("*.txt") as $filename) {
                        $zip->addFile($filename);
                    }
                    $zip->close();
                    foreach (glob("*.txt") as $filename) {
                        unlink($filename);
                    }
                }
            }

            if (!isset($_COOKIE["current"])) {
                $order = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
                shuffle($order);
                echo "if (!isset(_COOKIEcurrent))";
                $current = 0;
                $correct = 0;
                $start_time = time();
                $end_time = 0;
                $results = 0;
                $full_name = $_COOKIE["full_name"];
                $user_answers = array();

                setcookie("order", join($order), time() + 3600);
                setcookie("current", $current, time() + 3600);
                setcookie("correct", $correct, time() + 3600);
                setcookie("start_time", $start_time, time() + 3600);
                setcookie("end_time", $end_time, time() + 3600);
                setcookie("results", $results, time() + 3600);
                setcookie("answers", join($user_answers), time() + 3600);
            } else {
                echo "else(!isset(_COOKIEcurrent))";
                $order = array_map("intval", str_split($_COOKIE["order"]));
                $current = intval($_COOKIE["current"]);
                $correct = intval($_COOKIE["correct"]);
                $start_time = intval($_COOKIE["start_time"]);
                $end_time = intval($_COOKIE["end_time"]);
                $results = intval($_COOKIE["results"]);
                $full_name = $_COOKIE["full_name"];
                $user_answers = str_split($_COOKIE["answers"]);
                if ($user_answers[0] == "0") {
                    unset($user_answers[0]);
                }
            }

            if ($_POST["full_name"] != "") {
                echo "if (POST[full_name] != )";
                setcookie("full_name", $_POST["full_name"], time() + 3600);
                $full_name = $_POST["full_name"];
            }

            if ($full_name == "") {
                echo "if (full_name == )";
                ?>
                    <div class="right" style="width: 16em">
                        <form method="POST">
                            Введите ФИО: <input name="full_name"></button><br><br>
                            <input type="submit" value="Дальше"></submit>
                        </form>
                    </div>
                <?php
                exit();
            }

            $page = $_GET["page"];

            if ($page == "") {
                echo "if (page == )";
                if ($results == 0) : ?>
                    <p align="center"><b>Вопрос № <?php echo $current + 1; ?></b></p>
                    <p align="center"><?php echo $questions[$order[$current]][0]; ?></p>
                    <div class="center">
                        <form method="POST">
                            <input type="radio" name="answer" value="1" id="first" checked>
                            <label for="first"><?php echo $questions[$order[$current]][1]; ?></label><br><br>
                            <input type="radio" name="answer" value="2" id="second">
                            <label for="second"><?php echo $questions[$order[$current]][2]; ?></label><br><br>
                            <input type="radio" name="answer" value="3" id="third">
                            <label for="third"><?php echo $questions[$order[$current]][3]; ?></label><br><br>
                            <button name="test" value="again">Заново</button>
                            <button name="test" value="next">Дальше</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="center" style="width: 19em">
                        <form method="POST">
                            <button name="test" value="again">Заново</button>
                            <button name="test" value="results">Все результаты</button>
                            <button name="test" value="download">Скачать</button>
                        </form>
                    </div>
                    <p align="center"><?php echo "Ваш результат:\n"; ?></p>
                    <p align="center"><?php echo get_last_result(); ?></p>
                <?php endif;

                $action = $_POST["test"];
                echo "action=post";
                echo 'ction ='.$action;
                if ($action == "next" && $results == 0) {
                    echo "if (action ==next && )";
                    if ($current < 10) {
                        $answer = $_POST["answer"];
                        array_push($user_answers, $answer);

                        if ($answers[$order[$current]] == intval($answer)) {
                            $correct++;
                        }
                        $current++;

                        setcookie("answers", join($user_answers), time() + 3600);
                        setcookie("current", $current, time() + 3600);
                        setcookie("correct", $correct, time() + 3600);
                    } 
                    if ($current == 10) {
                        $end_time = time();
                        $results = 1;
                        
                        append_result($end_time - $start_time, $correct);

                        setcookie("end_time", $end_time, time() + 3600);
                        setcookie("results", $results, time() + 3600);
                    }
                    header("Refresh: 0");
                } else if ($action == "again") {
                    echo "elseif (action ==again )";
                    setcookie("current", "", time() - 1);
                    header("Refresh: 0");
                } else if ($action == "results") {
                    echo "elseif (action == results )";
                    header("Location: ?page=results");
                    die();
                } else if ($action == "download") {
                    echo "elseif (action == download )";
                    ob_end_clean();
                    $file = __DIR__ . '/' . get_last_file();
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="result.txt"');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    zip();
                    exit();
                }
            } else if ($page == "results") {
                echo "elseif (page == results )";
                $results = get_results();
                ?>

                <div class="center" style="width: 5em">
                    <form method="POST">
                        <button name="test" value="again">Назад</button><br><br>
                    </form>
                    <a href="archive.zip" download>Скачать</a>
                </div>
                <p align="center"><?php echo $results; ?></p>

                <?php
                    $action = $_POST["test"];

                    if ($action == "again") {

                        setcookie("current", "", time() - 1);
                        exit("<meta http-equiv='refresh' content='0; url=/4.php'>");
                    }
            }

        ?>
    </body>
</html>
