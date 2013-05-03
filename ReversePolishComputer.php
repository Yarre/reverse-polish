<?php

/**
 * Класс, вычисляющий выражение, записанное в обратной польской нотации.
 * Расчитан на работу с целыми числами и работает только c операторами + * - / %
 * Пример: ReversePolishComputer::compute('2 3 *') вернет 6
 */
class ReversePolishComputer {

	const DELIMITER = ' ';

	protected $expression = '';

	protected $expression_value = false;

	protected $values_stack = array();

	protected function __construct($expression) {
		$this->expression = strval($expression);
		$this->expression_value = false;
		$this->values_stack = array();
		$this->computeExpression();
	}

	/**
	 * Возвращает результат вычисления выражения
	 *
	 * @return integer|boolean
	 */
	protected function getComputedValue() {
		return $this->expression_value;
	}

	/**
	 * Вычисляет выражение
	 *
	 * @return bool
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

		// проверяем, что в стеке не осталось значений
		$not_empty_stack = $this->popFromStack();
		if ($not_empty_stack !== false) {
			return;
		}

		$this->expression_value = $expression_value;

	}

	/**
	 * Вычисляет выражение для переданного оператора
	 *
	 * @param $operator
	 * @return bool
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
	 * Выбирает последнее переданное значение в стек или false, если он пустой
	 *
	 * @return integer|bool
	 */
	protected function popFromStack() {

		if (count($this->values_stack) == 0) {
			return false;
		}

		$value = array_shift($this->values_stack);
		return $value;

	}

	/**
	 * Сохраняет значение в стек
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
	 * Определяет, является переданный символ оператором (true)
	 *
	 * @param $token
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
	 * Вычисляет выражение, записанное в обратной польской нотации
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