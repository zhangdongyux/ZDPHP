<?php
class Utils_Qrcode_Qrcode
{
	private $data;		//要记录的数据，如果是存储utf-8编码的中文，最多984个
	private $filename;	//生成文件名
	private $errorCorrectionLevel;	//ECC表示纠错级别， 纠错级别越高，生成图片会越大,L、M、Q、H
	private $matrixPointSize;	//图片每个黑点的像素 1-10,手机端 4即可
	private $margin;		//图片外围的白色边框像素
	
	function __construct($data=null, $filename=null, $errorCorrectionLevel=null, $matrixPointSize=null, $margin=null)
	{
		$this->data = $data;
		$this->filename = $filename;
		$this->errorCorrectionLevel = $errorCorrectionLevel;
		$this->matrixPointSize = $matrixPointSize;
		$this->margin = $margin;
	}
	
	
	public function gen()
	{
		require_once "Utils/Qrcode/qrlib.php";
		QRcode::png($this->data,$this->filename,$this->errorCorrectionLevel,$this->matrixPointSize,$this->margin);
		
	}
	
	
	/**
	 * @return the $data
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @return the $filename
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * @return the $errorCorrectionLevel
	 */
	public function getErrorCorrectionLevel() {
		return $this->errorCorrectionLevel;
	}

	/**
	 * @return the $matrixPointSize
	 */
	public function getMatrixPointSize() {
		return $this->matrixPointSize;
	}

	/**
	 * @return the $margin
	 */
	public function getMargin() {
		return $this->margin;
	}

	/**
	 * @param field_type $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @param field_type $filename
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * @param field_type $errorCorrectionLevel
	 */
	public function setErrorCorrectionLevel($errorCorrectionLevel) {
		$this->errorCorrectionLevel = $errorCorrectionLevel;
	}

	/**
	 * @param field_type $matrixPointSize
	 */
	public function setMatrixPointSize($matrixPointSize) {
		$this->matrixPointSize = $matrixPointSize;
	}

	/**
	 * @param field_type $margin
	 */
	public function setMargin($margin) {
		$this->margin = $margin;
	}

}