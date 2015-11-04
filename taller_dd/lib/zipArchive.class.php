<?php

/*
	***** AlfaZoneSoft PHP program *****
	[program]
	Function or script name:
		Zip Library
	Project: 
		Xerowon
	Maked: 2005 September - 2006 May
	Wrote: 
		AlfaZonesoft, Szegedi Zolt�n (Alias: M�gus, PHPMagus)
		Original: PHPMyAdmin (GNU)
		* http://www.pkware.com/business_and_developers/developer/popups/appnote.txt - .ZIP File Format Specification
	[accessible]
	E-mail: 
		magus@mailpont.hu, magus@root.hu, phpmagus@gmail.com
	Website: 
		http://alfazonesoft.ath.cx
	MSN: 
		magus@mailpont.hu
	***** AlfaZoneSoft PHP program *****
*/

class zipArchive  {  
	var $compressedData = array(); 
	var $centralDirectory = array();
	var $endOfCentralDirectory = "\x50\x4b\x05\x06\x00\x00\x00\x00";
	var $oldOffset = 0;

	function addDir($directoryName) {
		$directoryName = str_replace("\\", "/", $directoryName);  

		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x0a\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x00\x00\x00\x00"; 

		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("v", strlen($directoryName) ); 
		$feedArrayRow .= pack("v", 0 ); 
		$feedArrayRow .= $directoryName;  

		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 
		$feedArrayRow .= pack("V",0); 

		$this -> compressedData[] = $feedArrayRow;
		
		$newOffset = strlen(implode("", $this->compressedData));

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x0a\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x00\x00\x00\x00"; 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("V",0); 
		$addCentralRecord .= pack("v", strlen($directoryName) ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$addCentralRecord .= pack("v", 0 ); 
		$ext = "\x00\x00\x10\x00";
		$ext = "\xff\xff\xff\xff";  
		$addCentralRecord .= pack("V", 16 ); 

		$addCentralRecord .= pack("V", $this -> oldOffset ); 
		$this -> oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;  

		$this -> centralDirectory[] = $addCentralRecord;  
	}	 
	
	function addFile($filename, $directoryName)   {
		$data=file_get_contents($filename);
 		$directoryName = str_replace("\\", "/", $directoryName);  
	
		$feedArrayRow = "\x50\x4b\x03\x04";
		$feedArrayRow .= "\x14\x00";    
		$feedArrayRow .= "\x00\x00";    
		$feedArrayRow .= "\x08\x00";    
		$feedArrayRow .= "\x00\x00\x00\x00"; 

		$uncompressedLength = strlen($data);  
		$compression = crc32($data);  
		$gzCompressedData = gzcompress($data);  
		$gzCompressedData = substr( substr($gzCompressedData, 0, strlen($gzCompressedData) - 4), 2); 
		$compressedLength = strlen($gzCompressedData);  
		$feedArrayRow .= pack("V",$compression); 
		$feedArrayRow .= pack("V",$compressedLength); 
		$feedArrayRow .= pack("V",$uncompressedLength); 
		$feedArrayRow .= pack("v", strlen($directoryName) ); 
		$feedArrayRow .= pack("v", 0 ); 
		$feedArrayRow .= $directoryName;  

		$feedArrayRow .= $gzCompressedData;  

		$feedArrayRow .= pack("V",$compression); 
		$feedArrayRow .= pack("V",$compressedLength); 
		$feedArrayRow .= pack("V",$uncompressedLength); 

		$this -> compressedData[] = $feedArrayRow;

		$newOffset = strlen(implode("", $this->compressedData));

		$addCentralRecord = "\x50\x4b\x01\x02";
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x14\x00";    
		$addCentralRecord .="\x00\x00";    
		$addCentralRecord .="\x08\x00";    
		$addCentralRecord .="\x00\x00\x00\x00"; 
		$addCentralRecord .= pack("V",$compression); 
		$addCentralRecord .= pack("V",$compressedLength); 
		$addCentralRecord .= pack("V",$uncompressedLength); 
		$addCentralRecord .= pack("v", strlen($directoryName) ); 
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("v", 0 );
		$addCentralRecord .= pack("V", 32 ); 

		$addCentralRecord .= pack("V", $this -> oldOffset ); 
		$this -> oldOffset = $newOffset;

		$addCentralRecord .= $directoryName;  

		$this -> centralDirectory[] = $addCentralRecord;  
	}

	function addAuthor() {
		/*$fp = fopen("zip_author_deletefile.deletefie_author_zip","w");
		fwrite($fp, "UMVista 360\n");
		fwrite($fp, "\n");
		fwrite($fp, "Web: http://alfazonesoft.ath.cx\n\n");
		fwrite($fp, "A f�jl elk�sz�lt: ".date("Y.m.d. H:i:s")."\n");
		fclose($fp);
		$this -> addFile("zip_author_deletefile.deletefie_author_zip","zipinfo.txt");
		unlink("zip_author_deletefile.deletefie_author_zip");*/
	}

	function getZip() { 
		$this -> addAuthor();
		$data = implode("", $this -> compressedData);  
		$controlDirectory = implode("", $this -> centralDirectory);  

		return   
			$data.  
			$controlDirectory.  
			$this -> endOfCentralDirectory.  
			pack("v", sizeof($this -> centralDirectory)).     
			pack("v", sizeof($this -> centralDirectory)).     
			pack("V", strlen($controlDirectory)).             
			pack("V", strlen($data)).                
			"\x00\x00";                             
	}
	function saveZip($filename) {
		$fp = fopen ($filename, "wb");
		fwrite ($fp, $this -> getZip());
		fclose ($fp);
	}
	
	function downloadZip($filename) {
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}
		elseif ( ! file_exists( $filename ) ) {
			echo "<html><title>Archivo no encontrado</title><body><b>Error::</b> El archivo no fue encontrado.</body></html>";
			exit;
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=".basename($filename).";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($filename));
		readfile("$filename");
		
	 }
}

?>
