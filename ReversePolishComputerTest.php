<?php

class ReversePolishComputerTest {

	public function __construct() {
		require_once 'ReversePolishComputer.php';
	}

	protected function getExpressions() {

		return array(
			// простые
			'2 3 +' => 5,
			'2 3 *' => 6,
			'3 2 -' => 1,
			'2 3 -' => -1,
			'6 2 /' => 3,
			'6 5 %' => 1,

			// сложные
			'15 100 +' => 115,
			'10 15 - 3 *' => -15,
			'3 10 15 - *' => -15,
			'5 8 3 + *' => 55,
			'4 2 5 * + 1 3 2 * + /' => 2,
			'2 5 * 4 + 3 2 * 1 + /' => 2,

			// невалидные
			'' => false,
			'*' => false,
			'2 +' => false,
			'2 3 ^' => false,
			'10 15 - 3 ' => false,
			'4 2 * + 1 3 2 * + /' => false,
			'2 5 * 4 3 2 * 1 + /' => false,
		);

	}

	public function run() {

		$valid_expressions = $this->getExpressions();

		$errors = array();

		foreach ($valid_expressions as $expression => $expected_value) {
			$computed_value = ReversePolishComputer::compute($expression);
			if ($computed_value !== $expected_value) {
				$errors[] =
					'Expression ['.$expression.'] computed value ['.$computed_value.'] does not match with expected ['.$expected_value.']';
			}
		}

		return $errors;

	}

}