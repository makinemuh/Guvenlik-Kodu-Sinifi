<?php

class Captcha
{
	/**
	 * Gösterilecek karakter uzunluğu
	 * @var integer
	 */
	private $length = 7;

	/**
	 * Gösterilecek karakterler
	 * @var string
	 */
	private $chars = 'abcdefghijklmnoprstuvyzABCDEFGHIJKLMOPRSTUVYZ1234567890';

	/**
	 * Geçerli resim boyutu
	 * @var array
	 */
	private $imgSize = [130, 40];

	/**
	 * Gösterilecek rastgele dizge
	 * @var string
	 */
	private $string = null;

	/**
	 * Sınıf başlatıcı
	 * @param integer $length
	 * @param string $chars
	 */
	public function __construct($length = null, $chars = null)
	{
		if (!session_id())
			throw new \Exception('Oturum (Session) başlatılmamış');

		$this->length = is_null($length) ? $this->length : $length;
		$this->chars = is_null($chars) ? $this->chars : $chars;
	}

	/**
	 * Resimi gösteren metod
	 * @param array $imgSize Resim boyutu
	 * @param integer $fontSize Font boyutu, maksimum 5
	 */
	public function image(array $imgSize = [], $fontSize = 5)
	{
		$this->generate();
		$this->imgSize = empty($imgSize) ? $this->imgSize : $imgSize;

		$width = $this->imgSize[0];
		$height = $this->imgSize[1];
		$im = ImageCreate($width, $height);

		$background = ImageColorAllocate($im, 255, 255, 255);
		$color = ImageColorAllocate($im, mt_rand(0, 255), mt_rand(0,50), mt_rand(200,255));

		$fontWidth = ImageFontWidth($fontSize);
		$fontHeight = ImageFontHeight($fontSize);

		$textWidth = $fontWidth * strlen($this->string);
		$positionCenter = ceil(($width - $textWidth) / 2);

		$textHeight = $fontHeight;
		$positionMiddle = ceil(($height - $textHeight) / 2);

		$imageString = ImageString(
			$im, $fontSize, $positionCenter, $positionMiddle, $this->string, $color
		);

		header('Content-type: image/png');
		ImagePng($im);
		ImageDestroy($im);
	}

	/**
	 * Dizge oluşturur
	 */
	public function generate()
	{
		for ($p = 0; $p < $this->length; $p++) {
			$this->string .= $this->chars[mt_rand(0, strlen($this->chars) - 1)];
		}
		$_SESSION['captcha'] = $this->string;
	}

	/**
	 * Dizgeyi doğrular
	 * @param string $string
	 * @return boolean
	 */
	public function validate($string = null)
	{
		$string = is_null($string) ? $_POST['captcha'] : $string;
		$valid = $string == $_SESSION['captcha'];
		$this->generate();
		return $valid;
	}
}
