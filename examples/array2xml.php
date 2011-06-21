<?php 
	/**
	 * pushOutputXML Class
	 * @author Steve King <steve@stevenking.com.au>
	 *
	 * This is a basic example of using method overloading
	 * It's dirty and I wouldn't use it in production.
	 * 
	 */
	class pushOutputXML {
		
		/**
		 * Coming into the class, this is where we'd store the
		 * array that's passed.
		 * @var array
		 */
		private $_inputObject;
		
		/**
		 * Heading out of the class, this is what we'd pull from
		 * outside in order to get our XML. Returns simplexml object
		 * @var object
		 */
		public $_outputObject;
		
		/**
		 * Our constructor, allows us to pull in an object and begin
		 * work on generating the XML. Builds our base level simplexml
		 * object too;
		 * @param array $inputObject
		 */
		function __construct($inputObject) {
			$this->_inputObject = $inputObject;
			$this->_outputObject = simplexml_load_string('<output></output>');
			$this->_matchOutput();
		}
	
		/**
		 * Detects and matches an array from input, then processes the top level
		 * items;
		 */
		private function _matchOutput() {
			if ( isset($this->_inputObject) && is_array($this->_inputObject) ) {
				foreach ( array_keys($this->_inputObject) as $theInput) {
					$this->{$theInput}($this->_inputObject[$theInput],$this->_outputObject);
				}
			}
		}
		
		/**
		 * Crazy Method
		 * @param mixed $Method
		 * @param array $Args
		 */
		private function __call($Method,$Args) {
			if ($Method != '_matchOutput' ) {
				if ( !isset($Args[1]) ) {
					$iterationXML = $this->_outputObject->addChild($Method);
				}
				else {
					if ( is_array($Args[0]) ) {
						$iterationXML = $Args[1]->addChild($Method);
						// Watchoo - push that in.
						foreach ( array_keys($Args[0]) as $pushDrop ) {
							if ( is_array($Args[0][$pushDrop]) ) {
								if ( !is_string($pushDrop) ) {
									$this->{$Method}($Args[0][$pushDrop],$iterationXML);
								}
								else {
									$this->{$pushDrop}($Args[0][$pushDrop],$iterationXML);
								}
							}
							else {
								$iterationXML->addChild($pushDrop,$Args[0][$pushDrop]);
							}
						}
					}
					else {
						$iterationXML = $Args[1]->addChild($Method,$Args[0]);
					}
					
				}
			}
		}
	}
	
	/* Example Usage
	 * header ( 'Content-type: text/xml');
	 * $OutputXML = new pushOutputXML(array('moo' => array ('foo','boo','coo'), 'chocolate' => 'yummy' ));
	 * print_r($OutputXML->_outputObject->asXML());
	 */
	
	
?>