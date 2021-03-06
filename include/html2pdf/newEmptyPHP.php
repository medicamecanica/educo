<?php

/* 
 * Copyright (C) 2018 ander
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include './src/ConverterInterface.php';
include './src/BaseConverter.php';
include './src/Manager.php';
$html = <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<p>Simple Content</p>
</body>
</html>
HTML;

// create PDF file from HTML content :
Yii::$app->html2pdf
    ->convert($html)
    ->saveAs('/path/to/output.pdf');

// convert HTML file to PDF file :
Yii::$app->html2pdf
    ->convertFile('/path/to/source.html')
    ->saveAs('/path/to/output.pdf');