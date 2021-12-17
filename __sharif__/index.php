<?php

session_start();

$people = json_decode(file_get_contents(__DIR__ . "/people.json"), true);
$messages = explode("\n", file_get_contents(__DIR__ . "/messages.txt"));

if ($_POST != null) {
    $question = $_POST['question'];

    if (!preg_match('/^(آیا).*?(\?|؟)/', $question)) {
        echo "سوال درستی پرسیده نشده";
    }

    $en_name = $_POST['person'];
    $fa_name = $people[$en_name];
    $msg = $messages[array_rand($messages)];
    $saveAnswer = true;
    if (isset($_SESSION[$en_name])) {
        foreach ($_SESSION[$en_name] as $item) {
            if ($item['question'] == $question) {
                $msg = $item['answer'];
                $saveAnswer = false;
                break;
            }
        }
    }

    if ($saveAnswer) {
        $_SESSION[$en_name][] = [
            'question' => $question,
            'answer' => $msg
        ];
    }
} else {
    $msg = "سوال خود را بپرس!";
    $question = '';
    $en_name = array_rand($people);;
    $fa_name = $people[$en_name];
}

if ($_GET['s'] == 's') session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <div id="title">
        <span id="label">
            <?php if ($question != null) echo "پرسش:" ?>
        </span>
        <span id="question"><?php echo $question ?></span>
    </div>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form method="post">
            سوال
            <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..."/>
            را از
            <select name="person">
                <?php
                foreach ($people as $enPersonName => $faPersonName) {
                    if ($en_name == $enPersonName)
                        echo "<option value='$enPersonName' selected=''>$faPersonName</option>";
                    else
                        echo "<option value='$enPersonName'>$faPersonName</option>";
                }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>