<?php

/**
 * This class computes an expression wrote in Reverse Polish Notation.
 * It happens that expression contains only integer numbers.
 * It works with + * - / and % operators.
 * Example: ReversePolishComputer::compute('2 3 *') returns 6
 */
class ReversePolishComputer {

	const DELIMITER = ' ';

	protected $expression = '';

	protected $expression_value = false;

	protected $values_stack = array();

	/**
	 * Create an object with the expression and immediatly runs the evaluation
	 * @param string $expression expression to compute
	 * */
	public function __construct($expression) {
		$this->expression = strval($expression);
		$this->expression_value = false;
		$this->values_stack = array();
		$this->computeExpression();
	}

	/**
	 * Returns the computed expression or false in case of fail
	 * @return integer|boolean
	 */
	protected function getComputedValue() {
		return $this->expression_value;
	}

	/**
	 * Main computing loop
	 */
	protected function computeExpression() {

		$tokens = explode(static::DELIMITER, $this->expression);

		if (!is_array($tokens)) {
			return;
		}

		foreach ($tokens as $token) {

			if (!$this->isOperator($token)) {
				if ($this->pushToStack($token)) {
					continue;
				} else {
					return;
				}
			}

			$this->computeOperation($token);

		}

		$expression_value = $this->popFromStack();

		// check that stack is empty
		$not_empty_stack = $this->popFromStack();
		if ($not_empty_stack !== false) {
			return;
		}

		$this->expression_value = $expression_value;

	}

	/**
	 * Computes the expression for the passed operater and two operands from the stack.
	 * Puts the result expression to the stack.
	 *
	 * @param string $operator
	 * @return bool result
	 */
	protected function computeOperation($operator) {

		$second_operand = $this->popFromStack();
		$first_operand = $this->popFromStack();

		if ($first_operand === false || $second_operand === false) {
			return false;
		}

		switch ($operator) {

			case '+':
				$computed_value = $first_operand + $second_operand;
				break;

			case '*':
				$computed_value = $first_operand * $second_operand;
				break;

			case '-':
				$computed_value = $first_operand - $second_operand;
				break;

			case '/':
				$computed_value = $first_operand / $second_operand;
				break;

			case '%':
				$computed_value = $first_operand % $second_operand;
				break;

			default:
				return false;

		}

		return $this->pushToStack($computed_value);

	}

	/**
	 * Gets last value from the stack or false if it's empty
	 *
	 * @return integer|bool
	 */
	protected function popFromStack() {

		if (count($this->values_stack) === 0) {
			return false;
		}

		$value = array_shift($this->values_stack);
		return $value;

	}

	/**
	 * Saves value to the stack
	 *
	 * @return bool
	 */
	protected function pushToStack($value) {

		if (!is_numeric($value)) {
			return false;
		}
		return array_unshift($this->values_stack, intval($value));

	}

	/**
	 * Checks if passed token is operator
	 *
	 * @param string $token
	 * @return bool
	 */
	protected function isOperator($token) {

		$operators = array('+', '*', '-', '/', '%');
		if (in_array($token, $operators)) {
			return true;
		}
		return false;

	}


	/**
	 * Static function to use class without it's creation
	 *
	 * @param string $expression
	 * @example '15 100 +'
	 * @return integer|boolean
	 */
	public static function compute($expression) {

		$computer = new static($expression);
		return $computer->getComputedValue();

	}

}
