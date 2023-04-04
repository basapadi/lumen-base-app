<?php
namespace App\Traits;

trait TerbilangTrait {
    private $_bilangan = [" ", "Satu", "Dua", "Tiga", "Empat", "Lima",
    "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];


	/**
     * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
     * @param integer|double|float $val;
     * Convert integer,double,float into string spelling based on Indonesian
     * @return string
     */
	public function terbilang($val){
		return $this->convert($val);
	}

    private function convert($value){
        if($value < 12){
			return " ". $this->_bilangan[(int)$value];
		}
		else if($value < 20){
			return $this->convert($value - 10) . " Belas ";
		}
		else if($value < 100){
			return ($this->convert($value / 10) . " Puluh ") .  $this->convert($value % 10); 
		}
		else if($value < 200 ){ 
			return "Seratus" . $this->convert($value - 100);
		}
		else if($value < 1000){
			return ($this->convert($value / 100) . " Ratus " ) . $this->convert($value % 100);
		}
		else if($value < 2000){
			return "Seribu" . $this->convert($value - 1000);
		}
		else if($value < 1000000){
			return ($this->convert($value /1000) . " Ribu ") . $this->convert($value % 1000);
		}
		else if($value < 1000000000){
			return ($this->convert($value /1000000) . " Juta ") . $this->convert($value % 1000000);
		}
		else if($value < (double) "1000000000000L"){
			return ($this->convert($value /1000000000) . " Milyar ") . $this->convert($value % 1000000000);
		}
		else if($value < (double) "1000000000000000L"){
			return ($this->convert($value / (double) "1000000000000L") . " Triliun ") . $this->convert($value % (double) "1000000000000L");
		}
		return null;
    }
}