<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class leerRss{
    public $url;
    public $items;
    public $error;
    
    public function __construct($url) {
        if(@fopen($url, 'r')){
            $this->url= $url;
            $this ->error = true;
            return $this->generarXML();
        }
        else{
            $this ->error = false;
        }
    }
    private function generarXML(){
        if($xml = simplexml_load_file($this->url)){
            $this->items = $xml->channel->item;
            $this->error = true;
        }
        else{
            $this ->error = false;
        }
    }
}
?>
