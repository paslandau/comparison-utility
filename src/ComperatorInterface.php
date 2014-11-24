<?php
  namespace paslandau\ComparisonUtility;

interface ComperatorInterface{
	
	/**
	 * Compares $compareValue to $expectedValue and returns true or false.
	 * Implementing classed should provide a suitable comparison function
	 * @param mixed|null $compareValue
	 * @param mixed|null $expectedValue
	 * @return bool
	 */
	public function compare($compareValue = null, $expectedValue = null);
}