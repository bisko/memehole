<?php

function print_meme($serverhost ='', $meme='random') {
	$meme = new Meme($meme, $serverhost);
	$meme->printImage();
}



class Meme {

    /* Basic configuration */
    private $maxSize = 48; // maximum font size
	private $minSize = 16; // minimum font size
	private $fontPath = 'impact.ttf'; // font file (relative to index.php)
	private $padding = 10; // padding around the image, so the text doesn't overflow outside
	private $stroke = 3; // text stroke width

   

  

    

    public $memes = array (
		'boromir' => array (
			'path' => 'images/boromir.jpg',
			'texts' => array(
				'top' => 'one does not simply',
				'bottom' => '%action% %serv% while at work',
			),
		),
		'notsure' => array (
			'path' => 'images/notsure.jpg',
			'texts' => array(
				'top' => 'Not sure if should be %action%ing %serv%',
				'bottom' => 'or should be closing tickets',
			),
		),
		'xallthey' => array (
			'path' => 'images/xallthey.jpg',
			'texts' => array(
				'top' => array('Finish','Do','Complete'),
				'bottom' => array('all the work','all the tickets','all the tasks',)
			),
		),
		'scumbagsteve' => array (
			'path' => 'images/scumbagsteve.jpg',
			'texts' => array(
				'top' => 'Has work',
				'bottom' => '%action%s %serv%',
			),
		),

		'iseewhatyoudidthere' => array (
			'path' => 'images/iseewhatyoudidthere.jpg',
			'texts' => array(
				'top' => 'Check just one page on %serv%',
				'bottom' => 'Suuuuure...',
			),
		),

		'yodawg' => array (
			'path' => 'images/yodawg.jpg',
			'texts' => array(
				'top' => 'Yo dawg, we put meme in your meme',
				'bottom' => "So you can meme while you meme \n for memes on %serv%",
			),
		),

		'firstworldproblems' => array (
			'path' => 'images/firstworldproblems.jpg',
			'texts' => array(
				'top' => 'I want to %action% %serv%',
				'bottom' => "But i have to finish my work",
			),
		),
	);

    private $actions = array('visit','open', 'check', 'surf');

	
    private $resource = null;
    public $path = '';
    private $server = '';
    private $basepath = '';
    private $cWhite = null;
	private $cBlack = null;
	
	
    function __construct($meme, $srv = 'this site') {

    	$this->server = $srv;
    	$this->basepath = dirname(__FILE__).'/';
    	if (empty($meme) || !isset($this->memes[$meme])) {
    		$meme = array_rand($this->memes);
    	}
    	$meme = $this->memes[$meme];
    	$meme['path'] = $this->basepath.$meme['path'];
    	
        $this->open($meme['path']);
        $this->getDimensions();

        $this->prepColors();

        foreach($meme['texts'] as $position=>$text) {
        	if (is_array($text)) {
        		$text = $text[array_rand($text)];
        	}
 
        	$text = str_replace('%action%', $this->actions[array_rand($this->actions)], $text);
        	$text = str_replace('%serv%', $this->server, $text);
        	$text = mb_strtoupper($text);

        	for ($i = $this->maxSize; $i > $this->minSize; $i-=2) {
        		$box = imagettfbbox($i, 0, $this->basepath.$this->fontPath, $text);
        		
        		$bwidth = abs($box[2] - $box[0]);
        		$bheight = abs($box[7] - $box[1]);
        		
        		if ($bwidth >= $this->width-2*$this->padding) {
        			continue;
        		}


        		if ($position == 'top') {
        			$x = $this->width/2 - $bwidth/2;
        			$y = $this->padding + $bheight;
        		}
        		elseif($position = 'bottom') {
        			$x = $this->width/2 - $bwidth/2;
        			$y = $this->height - $this->padding;
        		}

        		break;
        	}

        	// external source, lost the link. TODO: Add it when you find it :) 
        	$xd=0-abs($this->stroke);
            for ($xc=$x-abs($this->stroke);$xc<=$x+abs($this->stroke);$xc++) {
                    // For every Y pixel to the top and the bottom
                    $yd=0-abs($this->stroke);
                    for ($yc=$y-abs($this->stroke);$yc<=$y+abs($this->stroke);$yc++) {
                            //If this y x combo is within the bounds of a circle with a radius of $width
                            //($xc*$xc + $yc*$yc) <= ($width * $width)+999
                            if(($xd*$xd+$yd*$yd)<=$this->stroke*$this->stroke){
                                    // Draw the text in the outline color
                                    $this->addText($text, $xc, $yc, $i, $this->cBlack);
                            }
                            $yd++;
                    }
                    $xd++;
            }
    		
    		$this->addText($text, $x, $y, $i, $this->cWhite);

        }
    }

    private function addText($text, $x, $y, $size, $color) {
    	$font = $this->basepath.$this->fontPath;
    	imagettftext($this->resource, $size, 0, $x, $y, $color, $font, $text);
	}

    private function open($path = '') {
        $path = trim($path);
        if (empty($path) && empty($this->path)) {
            return false;
        }
        elseif(empty($path)) {
            $path = $this->path;
        }
        # GIF:
        $im = @imagecreatefromgif($path);
        if ($im !== false) {
            $this->resource = $im;
			return true;
		}
        # PNG:
        $im = @imagecreatefrompng($path);
        if ($im !== false) {
            $this->resource = $im;
			return true;
		}
		# JPEG:
        $im = @imagecreatefromjpeg($path);
        if ($im !== false) {
            $this->resource = $im;
			return true;
		}
        return false;
    }


    private function getDimensions($resource = null) {
    	if (empty($resource)) {
    		$resource = $this->resource;
    	}
    	$this->width = imagesx($resource);
    	$this->height = imagesy($resource);
    }


    private function prepColors() {
    	$this->cWhite = imagecolorallocate($this->resource, 255, 255, 255);
		//$this->grey = imagecolorallocate($im, 128, 128, 128);
		$this->cBlack = imagecolorallocate($this->resource, 0, 0, 0);
    }

    private function isValid() {
        return !empty($this->resource);
    }

    private function close() {
        imagedestroy($this->resource);
    }

    public function resize($width, $height, $crop = true, $zoom = false, $return = false) {
        // generate the thumb height width
        $w_orig = imagesx($this->resource);
        $h_orig = imagesy($this->resource);

        $w = $w_orig;
        $h = $h_orig;

        $maxh = $height;
        $maxw = $width;

        if ($h > $maxh) {
            $koef = $maxh/$h;
            $h = $h * $koef;
            $w = $w * $koef;
        }
        if ($w > $maxw) {
            $koef = $maxw/$w;
            $h = $h*$koef;
            $w = $w*$koef;
        }

        if ($zoom && ( ($h >= $maxh || $w >= $maxw) && ($h < $maxh || $w < $maxw) )) {
            if ($w <= $maxw) {
                $koef = $maxw/$w;
                $h = $h * $koef;
                $w = $w * $koef;
            }
            if ($h <= $maxh) {
                $koef = $maxh/$h;
                $h = $h * $koef;
                $w = $w * $koef;
            }
            //$crop = true;
        }
        if ($crop) {
            // crop
            $csh = 0;
            $csw = 0;
            $tmpimg = imagecreatetruecolor($w, $h);
            $destimg = imagecreatetruecolor($maxw, $maxh);
            imagecopyresampled($tmpimg, $this->resource, 0,0,0,0, $w, $h, $w_orig, $h_orig);
            imagecopy($destimg, $tmpimg, 0,0, $csh, $csw, $maxw, $maxh);
            imagedestroy($tmpimg);
            unset($tmpimg);
        }
        else {
            // do the usual thumbing
            $destimg = imagecreatetruecolor($w, $h);
            imagecopyresampled($destimg, $this->resource, 0,0,0,0, $w, $h, $w_orig, $h_orig);
        }

        if ($return == true) {
            return $destimg;
        }
        else {
            imagedestroy($this->resource);
            $this->resource = $destimg;
        }

    }

    public function save($path,$quality = 100, $type = 'jpeg', $resource = null) {
        if (!empty($resource)) {
            $res = $resource;
        }
        else {
            $res = $this->resource;
        }

        $quality = (int)$quality;
        if ($quality < 0) { $quality = 1; }
        if ($quality > 100) { $quality = 100; }

        $path = $path.'.'.$type;
        switch($type) {
            case 'jpeg':
            case 'jpg':
            default:
                imagejpeg($res, $path, $quality);
        }

        return $path;
    }
    public function printImage() {
    	header('Content-Type: image/png');
    	imagepng($this->resource);
	}
}


?>