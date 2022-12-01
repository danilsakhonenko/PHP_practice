<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Тест на знание языка Assembler</title>
    </head>
    <style type="text/css">
        .start {
            position:absolute;
            width: 30%;
            left: 35%;
            top: 25%;
            text-align: center;
        }
        .base{
            position:absolute;
            width: 45%;
            left: 25%;
            top: 20%;
            
        }
    </style>
    <body bgcolor="#CCCCCC">
        <?php
        error_reporting(E_ALL ^ E_WARNING);
        $questions = array (
            0 => array (
                "Какой из регистров общего назначения чаще всего используется в качестве счетчика?",
                "AX",
                "BP",
                "CX"
            ),
            1 => array (
                "Почему принцип работы стека называют принципом LIFO?",
                "Элементы стека снимаются в порядке занесения (Lead In First Out)",
                "Элементы стека снимаются в специальном формате (Linear Input Formatted Output)",
                "Элементы стека снимаются в порядке, обратному порядку их занесения (Last In First Out)"
            ),
            2 => array (
                "Вы сохраняете в стеке регистры AX, BX, CX, DX. В каком порядке необходимо извлекать их из стека?",
                "AX, BX, CX, DX",
                "AX, CX, BX, DX",
                "DX, CX, BX, AX"
            ),
            3 => array (
                "Какую команду используют вместо команды mul для умножения регистра на число, являющееся степенью двойки?",
                "shl",
                "shr",
                "rol"
            ),
            4 => array (
                "Чем является оператор cmp?",
                "Командой побитного сравнения числа",
                "Командой вычитания, не сохраняющей результат",
                "Командой сложения, не сохраняющей результат"
            ),
            5 => array (
                "Какая команда обменивает содержимое двух регистров?",
                "trade",
                "swap",
                "xchg"
            ),
            6 => array (
                "Регистр процессора AX, используемый в большинстве математических операций для хранения, как аргумента, так и результата, часто называется…",
                "Аккумулятором",
                "Временным регистром",
                "Ячейкой"
            ),
            7 => array (
                "Какая команда является «парной» для оператора call?",
                "jmp",
                "pop",
                "ret"
            ),
            8 => array (
                "Какие регистры изменятся после перемножения в программе два одинарных слова, находящихся в регистрах AX и CX, командой mul cx?",
                "AX и CX",
                "AX и BX",
                "AX и DX"
            ),
            9 => array (
                "Какой командой можно сохранить в памяти регистры общего назначения?",
                "SAVEREGS",
                "PUSHA/PUSHAD",
                "STOREALL"
            )
        );
            
        $correct_answers = array (
            0 => 3,
            1 => 3,
            2 => 3,
            3 => 1,
            4 => 2,
            5 => 3,
            6 => 1,
            7 => 3,
            8 => 3,
            9 => 2,
        );

        function saveResult($time, $correct) {
            global $questions, $order, $answers, $correct_answers;
            unzip();
            $file = fopen(time() . ".txt", "w");
            $str = "ФИО: " . $_COOKIE["username"] ."\n";
            $str .= "[" . date("d.m.Y, H:i:s") . "]\n";
            $str .= "\nПравильных ответов: " . $correct;
            $str .= "\nВремя прохождения: " . $time . " секунд\n\n";
            for ($i = 0; $i < 10; $i++) {
                $str .= $i + 1 . " Вопрос: \"" . $questions[$order[$i]][0] . "\"\n";
                $str .= "Правильный ответ: \"" .  $questions[$order[$i]][$correct_answers[$order[$i]]] . "\"\n";
                $str .= "Выбранный ответ: \"" .  $questions[$order[$i]][$answers[$i]] . "\"\n";   
                $str .= "\n";
            }
            fwrite($file, $str);
            fclose($file);
            zip();
        }
        function getLastFile() {
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
        function printResult() {
            unzip();
            $max = 0;
            if(!isset($_COOKIE['result_number'])){
                foreach (glob("*.txt") as $filename) {
                    $time = intval(explode(".", $filename)[0]);
                    if ($time > $max) {
                        $max = $time;
                    }
                }
                setcookie("result_number", $max, time() + 3600);
            }
            $max = $_COOKIE['result_number'];
            $result = htmlentities(file_get_contents($max . ".txt"));
            $result = explode("\n", $result);
            $result = implode("<br>", $result);
            zip();
            echo "Ваш результат: \n";
            echo $result;
        }
        function printAllResults() {
            unzip();
            echo "Все результаты: <br/>";
            foreach (glob("*.txt") as $filename) {
                $result = htmlentities(file_get_contents($filename));
                $result = explode("\n", $result);
                $result = implode("<br>", $result);
                echo $result;
                echo '* * * * *<br/><br/>';
            }
            zip();
        }
        function unzip() {
            $zip = new ZipArchive();
            $zip->open("all_results.zip", ZIPARCHIVE::CREATE);
            $zip->extractTo(__DIR__);
            $zip->close();
        }
        function zip() {
            $zip = new ZipArchive();
            $zip->open("all_results.zip", ZIPARCHIVE::CREATE);
            foreach (glob("*.txt") as $filename) {
                $zip->addFile($filename);
            }
            $zip->close();
            foreach (glob("*.txt") as $filename) {
                unlink($filename);
            }
        }
        if(!isset($_COOKIE['username']) && !isset($_POST['username'])){
            ?>
            <div class="start">
                <form method="post">
                    ФИО: <input name="username"/><br><br>
                    <input type="submit" value="Начать"/>
                </form>
            </div>
            <?php
            exit();
        }
        elseif(isset($_POST['username']) && !isset($_COOKIE['username'])){
            setcookie("username", $_POST["username"], time() + 3600);
            $username = $_POST["username"];
        }
        if (!isset($_COOKIE["question"])) {
            $order = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
            shuffle($order);
            $question = 0;
            $stage = 0;
            $correct = 0;
            $end_time = 0;
            $start_time = time();
            $answers = array();
            setcookie("order", join($order), time() + 3600);
            setcookie('question', $question, time() + 3600);
            setcookie("correct", $correct, time() + 3600);
            setcookie("start_time", $start_time, time() + 3600);
            setcookie("end_time", $end_time, time() + 3600);
            setcookie("answers", join($answers), time() + 3600);
            setcookie("stage", $stage, time() + 3600);
        } else {
            $username = $_COOKIE['username'];
            $order = array_map("intval", str_split($_COOKIE["order"]));
            $question = intval($_COOKIE["question"]);
            $correct = intval($_COOKIE["correct"]);
            $start_time = intval($_COOKIE["start_time"]);
            $end_time = intval($_COOKIE["end_time"]);
            $stage = intval($_COOKIE['stage']);
            $answers = str_split($_COOKIE["answers"]);
            if ($answers[0] == "0") {
                unset($answers[0]);
            }
        }
        if(isset($_POST['action'])){
            $action = $_POST["action"];
            switch ($action) {
                case 'next':
                    if ($question < 10) {
                        $answer = $_POST["answer"];
                        array_push($answers, $answer);
                        if ($correct_answers[$order[$question]] == intval($answer)) {
                            $correct++;
                        }
                        $question++;
                        setcookie("answers", join($answers), time() + 3600);
                        setcookie("question", $question, time() + 3600);
                        setcookie("correct", $correct, time() + 3600);
                    } 
                    if ($question == 10) {
                        $end_time = time();
                        $stage = 1;
                        saveResult($end_time - $start_time, $correct);
                        setcookie("end_time", $end_time, time() + 3600);
                        setcookie("stage", $stage, time() + 3600);
                    }
                    header("Refresh: 0");
                    break;
                case 'again':
                    setcookie("result_number", 0, time() - 3600);
                    setcookie("question", $question, time() - 3600);
                    header("Refresh: 0");
                    break;
                case 'results':
                    $stage = 2;
                    setcookie("stage", $stage, time() + 3600);
                    header("Refresh: 0");
                    break;
                case 'back':
                    $stage = 1;
                    setcookie("stage", $stage, time() + 3600);
                    header("Refresh: 0");
                    break;
                case 'download':   
                    ob_end_clean();
                    $file = __DIR__ . '/' . getLastFile();
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="result.txt"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    zip();
                    exit();
                    break;
                case 'download_all':   
                    ob_end_clean();
                    header('Content-Type: application/zip');
                    header('Content-Disposition: attachment; filename="all_results.zip"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize('all_results.zip'));
                    readfile('all_results.zip');
                    exit();
                    break;

            }
        }
        if ($stage == 0){
            ?>
            <div class="base">
                <p align="center"><b>Вопрос № <?php echo $question + 1; ?></b></p>
                <p align="center"><?php echo $questions[$order[$question]][0]; ?></p>
                <form method="POST">
                    <input type="radio" name="answer" value="1" id="first" checked>
                    <label for="first"><?php echo $questions[$order[$question]][1]; ?></label><br><br>
                    <input type="radio" name="answer" value="2" id="second">
                    <label for="second"><?php echo $questions[$order[$question]][2]; ?></label><br><br>
                    <input type="radio" name="answer" value="3" id="third">
                    <label for="third"><?php echo $questions[$order[$question]][3]; ?></label><br><br>
                    <button name="action" value="again">Заново</button>
                    <button name="action" value="next">Дальше</button>
                </form>
            </div>
            <?php
        }else if ($stage == 1) { ?>
            <div align="center">
                <form method="POST">
                    <button name="action" value="again">Заново</button>
                    <button name="action" value="results">Все результаты</button>
                    <button name="action" value="download">Скачать</button>
                </form>
            </div>
            <p align="center"><?php printResult(); ?></p>
            <?php
        }else if ($stage == 2){
            ?>
            <div align="center">
                <form method="POST">
                    <button name="action" value="back">Назад</button>
                    <button name="action" value="download_all">Скачать</button>
                </form>
            </div>
            <p align="center"><?php printAllResults(); ?></p>
            <?php
        }
    ?>
</body>
</html>