<?php
class FileUploadComponent extends Component {
	public $Controller;
	public $extensions = array (
			"image" => array (
					"jpg",
					"png" 
			),
			"data" => array (
					"pdf",
					"doc",
					"txt" 
			) 
	);
	public $namesArray = array ();
	public $typesArray = array ();
	public $tmpNames = array ();
	public $errors = array ();
	public $sizes = array ();
	public $discardedFiles = array ();
	public function initialize(Controller $conroller) {
		$this->Controller = $conroller;
	}
	public function checkFormat($filename, $type) {
		$explodedArray = explode ( ".", $filename );
		$extension = end ( $explodedArray );
		if (in_array ( $extension, $this->extensions [$type] ))
			return true;
		else
			return false;
	}
	public function __construct() {
		if (isset ( $_FILES ['data'] )) {
			$files = $_FILES ['data'];
			$this->namesArray = $files ['name'] ['Upload'];
			$this->typesArray = $files ['type'] ['Upload'];
			$this->tmpNames = $files ['tmp_name'] ['Upload'];
			$this->errors = $files ['error'] ['Upload'];
			$this->sizes = $files ['size'] ['Upload'];
		}
	}
	function checkSize($actualSize, $maxSize) {
		if (! $maxSize) {
			return true;
		} else {
			return ($actualSize < $maxSize);
		}
	}
	public function uploadCount($group, $type = "image", $size = false) {
		if (! is_array ( $this->namesArray [$group] )) {
			if ($this->errors [$group] == 0) {
				if ($this->checkFormat ( $this->namesArray [$group], $type ))
					return 1;
				else {
					$this->discardedFiles [$group] [] = $this->namesArray [$group];
					return 0;
				}
			} else {
				return 0;
			}
		}
		
		for($i = 0; $i < count ( $this->namesArray [$group] ); $i ++) {
			
			if ($this->errors [$group] [$i] != 0 || ! is_uploaded_file ( $this->tmpNames [$group] [$i] ) || ! $this->checkFormat ( $this->namesArray [$group] [$i], $type ) || ! $this->checkSize ( $this->sizes [$group] [$i], $size )) {
				$this->discardedFiles [$group] = $this->namesArray [$group] [$i];
				unset ( $this->namesArray [$group] [$i] );
				unset ( $this->typesArray [$group] [$i] );
				unset ( $this->errors [$group] [$i] );
				unset ( $this->sizes [$group] [$i] );
				unset ( $this->tmpNames [$group] [$i] );
			}
		}
		
		$this->namesArray [$group] = array_merge ( array (), $this->namesArray [$group] );
		$this->typesArray [$group] = array_merge ( array (), $this->typesArray [$group] );
		$this->errors [$group] = array_merge ( array (), $this->errors [$group] );
		$this->sizes [$group] = array_merge ( array (), $this->sizes [$group] );
		$this->tmpNames [$group] = array_merge ( array (), $this->tmpNames [$group] );
		
		return count ( $this->namesArray [$group] );
	}
	
	/*
	 * clearPrevious - if set to true, it will delete the existing files in that category. useful for profile images
	 */
	public function upload($group, $id = null, $type = "image", $size = false, $clearPrevious = false) {
		App::import ( "Model" );
		
		$uploadDirectory = Configure::read ( "FileUpload.uploads_dir" );
		if ($uploadDirectory == "") {
			Configure::write ( "uploads_dir", "uploads/" );
			$uploadDirectory = "uploads/";
		}
		
		if (! is_dir ( $uploadDirectory ) && (file_exists ( $uploadDirectory ))) {
			throw new InternalErrorException ( __ ( "The upload directory is not a directory but exists" ) );
		}
		
		if (! file_exists ( $uploadDirectory )) {
			mkdir ( $uploadDirectory );
		}
		
		$uploadDirectory .= "$group/";
		
		if (! is_dir ( $uploadDirectory ) && (file_exists ( $uploadDirectory ))) {
			throw new InternalErrorException ( __ ( "The categoy directory is not a directory but exists" ) );
		}
		
		if (! file_exists ( $uploadDirectory )) {
			mkdir ( $uploadDirectory );
		}
		
		if ($id != null) {
			$uploadDirectory .= "$id/";
			
			if (! is_dir ( $uploadDirectory ) && (file_exists ( $uploadDirectory ))) {
				throw new InternalErrorException ( __ ( "The item directory is not a directory but exists" ) );
			}
			
			if (! file_exists ( $uploadDirectory )) {
				mkdir ( $uploadDirectory );
			}
		}
		
		if ($clearPrevious) {
			$this->deleteAll ( $group, $id, null, $type );
		}
		
		if (! is_array ( $this->namesArray [$group] )) {
			if (! move_uploaded_file ( $this->tmpNames [$group], $uploadDirectory . $this->namesArray [$group] )) {
				throw new InternalErrorException ( __ ( "Could not save the file due to some internal problems" ) );
			} else {
				return true;
			}
		}
		
		foreach ( $this->namesArray [$group] as $i => $name ) {
			if (! is_array ( $name )) {
				if (! move_uploaded_file ( $this->tmpNames [$group] [$i], $uploadDirectory . $name )) {
					throw new InternalErrorException ( __ ( "Could not save the file due to some internal problems" ) );
				}
			} else {
				foreach ( $name as $f => $filename ) {
					if ($filename != '') {
						if (! move_uploaded_file ( $this->tmpNames [$group] [$f], $uploadDirectory . $filename )) {
							throw new InternalErrorException ( __ ( "Could not save the file due to some internal problems" ) );
						}
					}
				}
			}
		}
		
		return true;
	}
	public function getMedia($group, $id = null, $type = "image") {
		App::import ( "Model" );
		
		$uploadDirectory = Configure::read ( "FileUpload.uploads_dir" );
		
		if ($uploadDirectory == "") {
			return array ();
		}
		
		if (! is_dir ( "{$uploadDirectory}$group" )) {
			return array ();
		}
		
		$directoryContainer = "{$uploadDirectory}$group";
		if ($id) {
			$directoryContainer .= "/$id";
		}
		
		if (! file_exists ( $directoryContainer ) || ! is_dir ( $directoryContainer ))
			return array ();
		
		$dit = new DirectoryIterator ( $directoryContainer );
		$files = array ();
		
		while ( $dit->valid () ) {
			if (! $dit->isDot () && $this->checkFormat ( $directoryContainer . "/" . $dit->getFilename (), $type ))
				$files [] = $this->_getNames ( $directoryContainer . "/" . $dit->getFilename () );
			$dit->next ();
		}
		
		return $files;
	}
	public function getMediaCount($group, $id = null, $type = "image") {
		$media = $this->getMedia ( $group, $id, $type );
		return count ( $media );
	}
	public function getMediaFirst($group, $id = null, $type = "image") {
		$media = $this->getMedia ( $group, $id, $type );
		if (! isset ( $media [0] ))
			return false;
		else
			return $media [0];
	}
	private function _getNames($path) {
		if (! file_exists ( $path ))
			return false;
		
		if (is_file ( $path ))
			return $path;
			
			// Folder
		$name = end ( explode ( "/", $path ) );
		$dit = new DirectoryIterator ( $path );
		$returnArray = array ();
		while ( $dit->valid () ) {
			if (! $dit->isDot ())
				$returnArray [] = $this->_getNames ( $path . "/" . $dit->getFilename () );
			$dit->next ();
		}
		return array (
				$name => $returnArray 
		);
	}
	public function deleteFile($group, $id = null, $filename) {
		App::import ( "Model" );
		
		$uploadDirectory = Configure::read ( "FileUpload.uploads_dir" );
		
		if ($uploadDirectory == "") {
			return false;
		}
		
		if (! is_dir ( $uploadDirectory ) && (file_exists ( $uploadDirectory ))) {
			throw new InternalErrorException ( __ ( "The upload directory is not a directory but exists" ) );
		}
		
		if (! file_exists ( $uploadDirectory )) {
			return false;
		}
		
		$uploadDirectory .= "$group/";
		if ($id)
			$uploadDirectory .= "$id/";
		
		$uploadDirectory .= "$filename";
		
		if (file_exists ( $uploadDirectory ) && unlink ( $uploadDirectory ) && ! is_dir ( $uploadDirectory )) {
			return true;
		} else {
			return false;
		}
	}
	public function deleteAll($group, $id = null, $size = null, $type = "image") {
		App::import ( "Model" );
		
		$uploadDirectory = Configure::read ( "FileUpload.uploads_dir" );
		
		if ($uploadDirectory == "") {
			return false;
		}
		
		// $target = Router::url('/');die ($target);
		$target = $uploadDirectory . $group . "/";
		if ($id)
			$target .= "$id/";
			
			// if($filename)
			// $target.="$filename/";
		
		if (! file_exists ( $target ))
			return false;
		
		if (is_file ( $target ) && $this->checkFormat ( $target, $type ))
			return unlink ( $target );
		
		return $this->_iterativeDelete ( $target, $type );
	}
	private function _iterativeDelete($directory, $type = "image") {
		if (! file_exists ( $directory ) || ! is_dir ( $directory )) {
			return false;
		}
		
		$dit = new DirectoryIterator ( $directory );
		while ( $dit->valid () ) {
			if ($dit->isDir () && ! $dit->isDot ()) {
				if (! $this->_iterativeDelete ( $directory . "/" . $dit->getFilename () ))
					return false;
			} else if ($this->checkFormat ( $directory . "/" . $dit->getFilename (), $type )) {
				if (! unlink ( $directory . "/" . $dit->getFilename () ))
					return false;
			}
			$dit->next ();
		}
		return true;
	}
}