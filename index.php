<!doctype html>
<html lang="en">
<head>
    <title>Document</title>
</head>
<style>
    table {
        border-collapse: collapse;
    }

    td {
        width: 30px;
        height: 30px;
        text-align: center;
        border: 1px solid black;
    }

    .path {
        background-color: red;
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
    }

    .form-container {
        max-width: 400px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-container label {
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
    }

    .form-container input[type="file"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        width: 100%;
        box-sizing: border-box;
        font-size: 16px;
        background-color: #f8f8f8;
    }

    .form-container input[type="submit"] {
        background-color: #4CAF50;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 16px;
    }

    .form-container input[type="submit"]:hover {
        background-color: #45a049;
    }

</style>
<body>
<?php
if (isset($_POST["submit"])) {
    $file = $_FILES["files"]["tmp_name"];
    if ($file) {
        $content = file_get_contents($file);
        $rows = [];
        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $row = explode(' ', trim($line)); // جدا کردن اعداد و حروف در هر ردیف
                    $rows[] = $row; // اضافه کردن ردیف به آرایه
                }
                fclose($handle);
            } else {
                echo "خطا در باز کردن فایل.";
            }
            $fileExists = true;
        } else {
            $fileExists = false;
        }
        $startRow = -1;
        $startCol = -1;
        // پیدا کردن موقعیت شروع (نقطه S)
        for ($i = 0; $i < count($rows); $i++) {
            for ($j = 0; $j < count($rows[$i]); $j++) {
                if ($rows[$i][$j] === 's' || $rows[$i][$j] === 'S') {
                    $startRow = $i;
                    $startCol = $j;
                    break 2;
                }
            }
        }
        if (!$fileExists) {
            echo "فایل وجود ندارد.";
        } elseif ($startRow === -1 || $startCol === -1) {
            echo "نقطه شروع (نقطه S) یافت نشد.";
        } else {
            dfs($rows, $startRow, $startCol);

        }

    }
}

?>
<br>
<br>
<br>
<div class="form-container">
    <form method="post" enctype="multipart/form-data">
        <label for="file">فایل</label>
        <input type="file" id="file" name="files" required>
        <br><br><br>
        <input type="submit" name="submit" value="ارسال">
    </form>
</div>
<?php

echo "<br>"."<br>";

// چاپ ماتریس گرافیکی
echo "<table>";
for ($i = 1; $i < count($rows); $i++) {
    echo "<tr>";
    foreach ($rows[$i] as $j => $value) {
        if ($value === 'x') {
            echo "<td class='path'>$value</td>";
        } else {
            echo "<td>$value</td>";
        }
    }
    echo "</tr>";
}
echo "</table>";

function dfs(&$grid, $row, $col) {
    // بررسی محدودیت‌ها
    if ($row < 0 || $row >= count($grid) || $col < 0 || $col >= count($grid[$row]) || $grid[$row][$col] === '0' || $grid[$row][$col] === 'v') {
        return false;
    }

    // نقطه مقصد (نقطه G) یافت شد
    if ($grid[$row][$col] === 'g' || $grid[$row][$col] === 'G') {
        $grid[$row][$col] = 'x'; // علامت‌گذاری مسیر با x
        return true;
    }
    // علامت‌گذاری نقطه فعلی به عنوان بازدید شده
    $grid[$row][$col] = 'v'; // v برای visited
    if(dfs($grid, $row - 1, $col)){
        //بالا
        $grid[$row][$col] = 'x';
        return true;
    } elseif (dfs($grid, $row + 1, $col)){
        //پایین
        $grid[$row][$col] = 'x';
        return true;
    } elseif (dfs($grid, $row, $col + 1)){
        //چپ
        $grid[$row][$col] = 'x';
        return true;
    } elseif (dfs($grid, $row, $col - 1)){
        //راست
        $grid[$row][$col] = 'x';
        return true;
    }
    echo "nol";
}
?>
<br>
<br>
<span>
    <a href="http://mobinabdi.ir/array.php">پروزه ماشین حساب</a>
</span>
</body>
</html>