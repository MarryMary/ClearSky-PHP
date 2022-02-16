<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic Exception</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <style>
        body{
            background-color: lightgray;
        }
        .main{
            background-color: white;
            margin: 10px;
            padding: 10px;
        }

        .side-bar{
            padding: 10px;
            text-align: center;
            background-color: white;
        }

        .code-dump{
            padding: 10px;
            background-color: white;
        }

        code {
            font-size: 14px;
            line-height: 1.4;
            font-family: Menlo, Consolas, 'DejaVu Sans Mono', monospace;
        }

        em {
            font-style: norma;
            margin-left: -16px;
            margin-right: -16px;
            padding-left: 16px;
            padding-right: 16px;
            background-color: orange;
            display: inline-block;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cinderella Magic Exception Traceback</a>
        </div>
    </nav>
    <div class="container">
        <div class="main">
        <h3>Fatal error: Uncaught exception 'PDOException' with message 'SQLSTATE[HY000] [1045] Access denied for user 'staff'@'localhost' (using password: YES)' in /home/user/public_html/honorific_input.php:78 Stack trace: #0 /home/user/public_html/honorific_input.php(78): PDO->__construct('mysql:host=loca...', 'staff', 'password') #1 {main} thrown in /home/user/public_html/honorific_input.php on line 78</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="side-bar">
                        <h4>The PHP Stacktrace</h4>
                        <hr>
                        <b>#0  Parents->__construct() called at [/workspace/Main.php:15]</b>
                        <hr>
                        <b>#1  Child->__construct() called at [/workspace/Main.php:19]</b>
                        <hr>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="code-dump">
                        <h4>Exception Detail -- Exception Hint</h4>
                        <p>The syntax of the SQL statement may be incorrect.</p>
                        <hr>
                        <h4>The Code Highlight</h4>
                        <hr>
                        <pre><code class="prettyprint lang-php linenums">
                            use Cinderella\Magic\Databases\MagicQueryBuilder;
                            class HogeHoge
                            {
                                public static function Exam()
                                {
                                    <em>echo "This is not a test!(大嘘)"</em>
                                }
                            }
                        </code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
</body>
</html>