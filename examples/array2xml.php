<?php 
	class pushOutputXML {
		
		private $_inputObject;
		
		function __construct($inputObject) {
			$this->_inputObject = $inputObject;
			$this->_outputObject = simplexml_load_string('<fas></fas>');
			$this->_matchOutput();
		}
	
		private function _matchOutput() {
			if ( isset($this->_inputObject) && is_array($this->_inputObject) ) {
				foreach ( array_keys($this->_inputObject) as $theInput) {
					$this->{$theInput}($this->_inputObject[$theInput],$this->_outputObject);
				}
			}
		}
		
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