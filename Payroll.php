<?php

define('SECONDS_PER_DAY', 86400);

class Payroll {

	protected $_year;

	protected $_weekendDays = array('Saturday', 'Sunday');
	protected $_expensesDays = array(1, 15);
	protected $_dateFormat = 'Y-m-d';

	public function __construct($year = null) {
		$this->_year = ($year) ? $year : date('Y');
	}

	public function getDates() {
		$results = array();

		for ($month=1; $month<=12; $month++) {
			$results[$month] = array(
				'month' => date('F', mktime(0, 0, 0, $month, 10)),
				'salaryDate' => $this->getSalaryDate($month),
				'expensesDates' => $this->getExpensesDays($month)
			);
		}

		return $results;
	}

	public function getSalaryDate($month) {
		$numDaysMonth = date('t', mktime(0, 0, 0, $month, 01, $this->_year));
		return $this->_getClosestWorkingDay(mktime(0, 0, 0, $month, $numDaysMonth, $this->_year));
	}

	public function getExpensesDays($month) {
		$expensesDates = array();

		foreach ($this->_expensesDays as $day) {
			$expenseTimestamp = mktime(0, 0, 0, $month, $day, $this->_year);
			$expensesDates[] = $this->_getClosestWorkingDay($expenseTimestamp);
		}

		return $expensesDates;
	}

	protected function _isWeekend($timestamp) {
		return in_array(date('l', $timestamp), $this->_weekendDays);
	}

	protected function _formatDate($timestamp) {
		return date($this->_dateFormat, $timestamp);
	}

	protected function _getClosestWorkingDay($timestamp) {
		while ($this->_isWeekend($timestamp)) {
			$timestamp += SECONDS_PER_DAY;
		}

		return $this->_formatDate($timestamp);
	}
}