<?php
/**
 * css_analyser - A PHP class to analyse a string of CSS to produce statistics.
 *
 * @package 	css_analyser
 * @author 		George Buckingham (www.georgebuckingham.com)
 * @copyright 	2014 George Buckingham
 * @license 	http://opensource.org/licenses/MIT MIT
 */

namespace gbuckingham89;

class css_analyser {

	/////////////////////////////////////////////////
	// PROEPRTIES
	/////////////////////////////////////////////////

	/**
	 * _css_string
	 *
	 * Stores the inputted string of CSS
	 *
	 * @var			string
	 * @access 		protected
	 */
	protected $_css_string = '';

	/**
	 * _css_string_cleaned
	 *
	 * Stores the inputted string of CSS after it's been cleaned up
	 *
	 * @var			string
	 * @access 		protected
	 */
	protected $_css_string_cleaned = '';

	/**
	 * _results
	 *
	 * Stores the results of the analysis methods
	 *
	 * @var			array
	 * @access 		protected
	 */
	protected $_results = array();

	/**
	 * _rules
	 *
	 * Stores each individual selector and it's properties
	 *
	 * @var			array
	 * @access 		protected
	 */
	protected $_rules = array();

	/**
	 * _rule_selectors
	 *
	 * Stores the selector(s) for each rule (a rule may have multiple selectors)
	 *
	 * @var			array
	 * @access 		protected
	 */
	protected $_rule_selectors = array();

	/**
	 * _selectors
	 *
	 * Stores each individual selector
	 *
	 * @var			array
	 * @access 		protected
	 */
	protected $_selectors = array();

	/////////////////////////////////////////////////
	// METHODS
	/////////////////////////////////////////////////

	/**
	 * _parse function.
	 *
	 * Parses a string of CSS, by first cleaning it, then removing media query lines (keeps the rules within a query though), then finds rules and selectors
	 *
	 * @access 		protected
	 * @param 		string 		$css_string
	 * @return 		void
	 */
	protected function _parse($css_string) {
		$this->_results = array();
		$this->_css_string = $css_string;
		$this->_css_string_cleaned = $this->_strip_comments($this->_css_string);
		preg_match_all('/(?ims)([a-z0-9, \s\.\:#_\-\(\)\>\\[\\]*=\'\"~|$^@]+)\{([^\}]*)\}/', $this->_strip_media_queries($this->_css_string_cleaned), $regex_matches);
		foreach($regex_matches[0] as $i => $x) {
			$selector = trim($regex_matches[1][$i]);
			$this->_rule_selectors[] = $selector;
			$this->_selectors = array_merge($this->_selectors, explode(",", $selector));
			$rules = explode(';', trim($regex_matches[2][$i]));
			foreach ($rules as $rule) {
				if(!empty($rule)) {
					$rule = explode(":", $rule);
					$this->_rules[$selector][][trim($rule[0])] = trim($rule[1]);
				}
			}
		}
	}

	/**
	 * _strip_comments function.
	 *
	 * Strips out comments from a string of CSS
	 *
	 * @access 		protected
	 * @param 		string 		$css_string
	 * @return 		string
	 */
	protected function _strip_comments($css_string) {
		return preg_replace('/\s*(?!<\")\/\*[^\*]+\*\/(?!\")\s*/' , '' , $css_string);
	}

	/**
	 * _strip_media_queries function.
	 *
	 * Strips out media queries from a string of CSS (but keeps the rules inside them)
	 *
	 * @access 		protected
	 * @param 		string 		$css_string
	 * @return 		string
	 */
	protected function _strip_media_queries($css_string) {
		$css_string = preg_replace('/(@media)[^\{]+\{/', '', $css_string);
		$css_string = preg_replace('/[\s]*}[\s]*}/', '}', $css_string);
		return $css_string;
	}

	/**
	 * get_analysis_results function.
	 *
	 * Returns the number of selectors
	 *
	 * @access 		public
	 * @return 		array
	 */
	public function get_analysis_results() {
		if(!isset($this->_results) || !is_array($this->_results) || !count($this->_results)) {
			$this->_results = array();
			$methods = array('get_media_queries_count', 'get_rules_count', 'get_property_definitions_count', 'get_selectors_count', 'get_size', 'get_size_formatted');
			foreach($methods as $method) {
				$this->$method();
			}
		}
		return $this->_results;
	}

	/**
	 * get_media_queries_count function.
	 *
	 * Returns the number of media queries in the inputted CSS
	 *
	 * @access 		public
	 * @return 		int
	 */
	public function get_media_queries_count() {
		if(!isset($this->_results['media_queries_count'])) {
			$this->_results['media_queries_count'] = 0;
			preg_match_all('/(@media)[^\{]+\{/', $this->_css_string_cleaned, $regex_matches);
			if(isset($regex_matches[0]) && count($regex_matches[0])) {
				$this->_results['media_queries_count'] = count($regex_matches[0]);
			}
		}
		return $this->_results['media_queries_count'];
	}

	/**
	 * get_rules_count function.
	 *
	 * Returns the number of rules in the inputted CSS
	 *
	 * @access 		public
	 * @return 		int
	 */
	public function get_rules_count() {
		if(!isset($this->_results['rules_count'])) {
			$this->_results['rules_count'] = count($this->_rule_selectors);
		}
		return $this->_results['rules_count'];
	}

	/**
	 * get_property_definitions_count function.
	 *
	 * Returns the number of property definitions in the inputted CSS
	 *
	 * @access 		public
	 * @return 		int
	 */
	public function get_property_definitions_count() {
		if(!isset($this->_results['property_definitions_count'])) {
			$this->_results['property_definitions_count'] = 0;
			if(count($this->_rules)) {
				foreach($this->_rules as $rule_group) {
					foreach($rule_group as $rule) {
						$this->_results['property_definitions_count'] += count($rule);
					}
				}
			}
		}
		return $this->_results['property_definitions_count'];
	}

	/**
	 * get_selectors_count function.
	 *
	 * Returns the number of selectors in the inputted CSS
	 *
	 * @access 		public
	 * @return 		int
	 */
	public function get_selectors_count() {
		if(!isset($this->_results['selectors_count'])) {
			$this->_results['selectors_count'] = count($this->_selectors);
		}
		return $this->_results['selectors_count'];
	}

	/**
	 * get_size function.
	 *
	 * Returns size of the inputted CSS
	 *
	 * @access 		public
	 * @return 		int
	 */
	public function get_size() {
		if(!isset($this->_results['size'])) {
			$this->_results['size'] = strlen($this->_css_string);
		}
		return $this->_results['size'];
	}

	/**
	 * get_size_formatted function.
	 *
	 * Returns size of the inputted CSS, but formatted nicely
	 *
	 * @access 		public
	 * @return 		string
	 */
	public function get_size_formatted() {
		if(!isset($this->_results['size_formatted'])) {
			$this->_results['size_formatted'] = $this->get_size().' B';
			if($this->get_size()>=1024 && $this->get_size()<(1024*1024)) {
				$this->_results['size_formatted'] =  round($this->get_size()/1024, 2).' KB';
			}
			elseif($this->get_size()>=(1024*1024) && $this->get_size()<$gigabyte) {
				$this->_results['size_formatted'] =  round($this->get_size()/(1024*1024), 2).' MB';
			}
		}
		return $this->_results['size_formatted'];
	}

	/**
	 * process function.
	 *
	 * Passes a string onto the main parse method and return the results of the analysis methods in an array, if needed
	 *
	 * @access 		public
	 * @param 		string 		$css_string
	 * @param 		bool 		$get_analysis_results
	 * @return 		array
	 */
	public function process($css_string, $get_analysis_results=true) {
		$this->_parse($css_string);
		if($get_analysis_results) {
			return $this->get_analysis_results();
		}
	}

}
?>