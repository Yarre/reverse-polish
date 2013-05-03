<?php

require_once 'ReversePolishComputerTest.php';

$test = new ReversePolishComputerTest();
$errors = $test->run();

if (empty($errors)) {
	echo 'Tests OK';
} else {
	print_r($errors);
}
