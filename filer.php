<?php
class filer
{

	static $singleton;

	public $file;

	static function getMe($f){
		if(filer::$singleton===null)filer::$singleton = new filer($f);
		return filer::$singleton;
	}

	function __construct( $file ) {
		$this->file = $file;
		if(!file_exists($file))file_put_contents($file,'');
	}


	function _getFileData(){
		return file_get_contents( $this->file );
	}

	function _searchKey($key){
		$dump = json_decode( $this->_getFileData(), true );
		$c = count($dump);
		$array = array();
		for($i = 0; $i < $c; $i++){
			if( array_key_exists($dump,$key)){
				$array[$i] = $dump[$i];
			}
		}
		if(empty($array)) $array = 0;

		return $array;
	}

	function _setFileData( $array ,$param = null) {
		return file_put_contents ( $this->file, $array, $param );
	}

	function _addFileData( $array ){

		$array = $this->__checkStrToArray( $array );
		$dump = json_decode( $this->_getFileData(), true );
		if ( empty( $dump ) ){
			//$set = array( 'json_db' => array( "0" => $array ) );
			$set = array( "0" => $array ) ;
		}
		elseif( is_array( $dump ) ){
			$count = count( $dump );
			//$count = count( $dump['json_db'] );
			//$dump['json_db'][$count] = $array;
			$dump[$count] = $array;
			$set = $dump;
		}

		return $this->_setFileData( json_encode( $set, JSON_UNESCAPED_SLASHES ,JSON_UNESCAPED_UNICODE ) );
	}

	private function __checkStrToArray( $array ){
		if( !is_array( $array ) ){
			$array = json_decode ( $array, true);
			if( !is_array( $array ) ){
				throw new Exception ('cant convert str to json, maybe thats invalid json str');
			}
		}
		return $array;
	}

	function _clearData( ) {
		return file_put_contents( $this->file, '' );
	}
}
?>
