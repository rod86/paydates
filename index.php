<?php

if (substr(php_sapi_name(), 0, 3) != 'cli') {
	echo 'Only can run in shell' ."\n";
	exit;
}

if (!isset($argv[1]) || !$argv[1]) {
	echo 'Missing output name parameter' ."\n";
	exit;
}

include_once 'Payroll.php';

$outputFile = getcwd() . '/' . $argv[1];

$payroll = new Payroll();
$dates = $payroll->getDates();

$fh = fopen($outputFile, 'w');

fputcsv($fh, array('Month Name', '1st expenses day', '2nd expenses day', 'Salary day'));

foreach ($dates as $row) {
	$line = array($row['month'], $row['expensesDates'][0], $row['expensesDates'][1], $row['salaryDate']);
	fputcsv($fh, $line);
}

fclose($fh);

