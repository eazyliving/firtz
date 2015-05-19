<?php
/**
*	Erstellt die richtigen favicons anhand des original covers.
*
*/
// Pfad
$PicPathIn="./multifavs/"; // Pfad Original Cover
$PicPathOut="./multifavs/"; // Pfad fertige Cover - Chmod 777 !!

	//benötigte Covers
	$picwidth16 = 16;
	$picwidth32 = 32;
	$picwidth57 = 57;
	$picwidth60 = 60;
	$picwidth72 = 72;
	$picwidth76	= 76;
	$picwidth96 = 96;
	$picwidth114 = 114; 
	$picwidth120 = 120;
	$picwidth144 = 144;
	$picwidth152 = 152;
	$picwidth160 = 160;


	// Orginal Cover
	$cover = "cover.png";

	// Neue Favicons
	$new_cover16 = "cover-" . $picwidth16 . "x" . $picwidth16 . ".jpg";
	$new_cover32 = "cover-" . $picwidth32 . "x" . $picwidth32 . ".jpg";
	$new_cover57 = "cover-" . $picwidth57 . "x" . $picwidth57 . ".jpg";
	$new_cover60 = "cover-" . $picwidth60 . "x" . $picwidth60 . ".png"; 
	$new_cover72 = "cover-" . $picwidth72 . "x" . $picwidth72 . ".png"; 
	$new_cover76 = "cover-" . $picwidth76 . "x" . $picwidth76 . ".png"; 
	$new_cover96 = "cover-" . $picwidth96 . "x" . $picwidth96 . ".png";
	$new_cover114 = "cover-" . $picwidth114 . "x" . $picwidth114 . ".png";
	$new_cover120 = "cover-" . $picwidth120 . "x" . $picwidth120 . ".png";
	$new_cover144 = "cover-" . $picwidth144 . "x" . $picwidth144 . ".png";
	$new_cover152 = "cover-" . $picwidth152 . "x" . $picwidth152 . ".png";
	$new_cover160 = "cover-" . $picwidth160 . "x" . $picwidth160 . ".png";

	// Coverdaten feststellen 
	$size = getimagesize( $PicPathIn . $cover ); 
	$width = $size[0]; 
	$height = $size[1]; 
	
	// JPEG
	$newwidth16 = $picwidth16; 
	$newheight16 = intval( $height * $newwidth16 / $width ); 

	$newwidth32 = $picwidth32; 
	$newheight32 = intval( $height * $newwidth32 / $width );
	
	$newwidth57 = $picwidth57; 
	$newheight57 = intval( $height * $newwidth57 / $width );
		
	// PNG
	$newwidth60 = $picwidth60; 
	$newheight60 = intval( $height * $newwidth60 / $width );
	
	$newwidth72 = $picwidth72; 
	$newheight72 = intval( $height * $newwidth72 / $width ); 

	$newwidth76 = $picwidth76; 
	$newheight76 = intval( $height * $newwidth76 / $width ); 

	$newwidth96 = $picwidth96; 
	$newheight96 = intval( $height * $newwidth96 / $width ); 
	
	$newwidth114 = $picwidth114; 
	$newheight114 = intval( $height * $newwidth114 / $width ); 
	
	$newwidth120 = $picwidth120; 
	$newheight120 = intval( $height * $newwidth120 / $width ); 	

	$newwidth144 = $picwidth144; 
	$newheight144 = intval( $height * $newwidth144 / $width ); 
	
	$newwidth152 = $picwidth152; 
	$newheight152 = intval( $height * $newwidth152 / $width ); 
	
	$newwidth160 = $picwidth160; 
	$newheight160 = intval( $height * $newwidth160 / $width ); 

	
if( $size[2] == 1 ) { 
	// Es ist ein GIF 
	$origcover = ImageCreateFromGIF( $PicPathIn . $cover ); 
	
		//JPEG
	$newscover16 = imagecreatetruecolor( $newwidth16, $newheight16 ); 
	ImageCopyResized( $newscover16, $origcover, 0, 0, 0, 0, $newwidth16, $newheight16, $width, $height ); 
	ImageJPEG( $newscover16, $PicPathOut . $new_cover16 ); 	

	$newscover32 = imagecreatetruecolor( $newwidth32, $newheight32 ); 
	ImageCopyResized( $newscover32, $origcover, 0, 0, 0, 0, $newwidth32, $newheight32, $width, $height ); 
	ImageJPEG( $newscover32, $PicPathOut . $new_cover32 ); 
	
	$newscover57 = imagecreatetruecolor( $newwidth57, $newheight57 ); 
	ImageCopyResized( $newscover57, $origcover, 0, 0, 0, 0, $newwidth57, $newheight57, $width, $height ); 
	ImageJPEG( $newscover57, $PicPathOut . $new_cover57 );
	
	//PNG
	$newscover60 = ImageCreate( $newwidth60, $newheight60 ); 
	ImageCopyResized( $newscover60, $origcover, 0, 0, 0, 0, $newwidth60, $newheight60, $width, $height ); 
	ImagePNG( $newscover60, $PicPathOut . $new_cover60 ); 
	
	$newscover72 = ImageCreate( $newwidth72, $newheight72 ); 
	ImageCopyResized( $newscover72, $origcover, 0, 0, 0, 0, $newwidth72, $newheight72, $width, $height ); 
	ImagePNG( $newscover72, $PicPathOut . $new_cover72 ); 
	
	$newscover76 = ImageCreate( $newwidth76, $newheight76 ); 
	ImageCopyResized( $newscover76, $origcover, 0, 0, 0, 0, $newwidth76, $newheight76, $width, $height ); 
	ImagePNG( $newscover76, $PicPathOut . $new_cover76 ); 	

	$newscover96 = ImageCreate( $newwidth96, $newheight96 ); 
	ImageCopyResized( $newscover96, $origcover, 0, 0, 0, 0, $newwidth96, $newheight96, $width, $height ); 
	ImagePNG( $newscover96, $PicPathOut . $new_cover96 ); 	
	
	$newscover114 = ImageCreate( $newwidth114, $newheight114 ); 
	ImageCopyResized( $newscover114, $origcover, 0, 0, 0, 0, $newwidth114, $newheight114, $width, $height ); 
	ImagePNG( $newscover114, $PicPathOut . $new_cover114 );	
		
	$newscover120 = ImageCreate( $newwidth120, $newheight120 ); 
	ImageCopyResized( $newscover120, $origcover, 0, 0, 0, 0, $newwidth120, $newheight120, $width, $height ); 
	ImagePNG( $newscover120, $PicPathOut . $new_cover120 );
	
	$newscover144 = ImageCreate( $newwidth144, $newheight144 ); 
	ImageCopyResized( $newscover144, $origcover, 0, 0, 0, 0, $newwidth144, $newheight144, $width, $height ); 
	ImagePNG( $newscover144, $PicPathOut . $new_cover144 );	
	
	$newscover152 = ImageCreate( $newwidth152, $newheight152 ); 
	ImageCopyResized( $newscover152, $origcover, 0, 0, 0, 0, $newwidth152, $newheight152, $width, $height ); 
	ImagePNG( $newscover152, $PicPathOut . $new_cover152 );	
	
	$newscover160 = ImageCreate( $newwidth160, $newheight160 ); 
	ImageCopyResized( $newscover160, $origcover, 0, 0, 0, 0, $newwidth160, $newheight160, $width, $height ); 
	ImagePNG( $newscover160, $PicPathOut . $new_cover160 );	 
} 

if( $size[2] == 2 ) { 
	// Es ist ein JPG 
	$origcover = ImageCreateFromJPEG( $PicPathIn . $cover ); 
	
		//JPEG
	$newscover16 = imagecreatetruecolor( $newwidth16, $newheight16 ); 
	ImageCopyResized( $newscover16, $origcover, 0, 0, 0, 0, $newwidth16, $newheight16, $width, $height ); 
	ImageJPEG( $newscover16, $PicPathOut . $new_cover16 ); 	

	$newscover32 = imagecreatetruecolor( $newwidth32, $newheight32 ); 
	ImageCopyResized( $newscover32, $origcover, 0, 0, 0, 0, $newwidth32, $newheight32, $width, $height ); 
	ImageJPEG( $newscover32, $PicPathOut . $new_cover32 ); 
	
	$newscover57 = imagecreatetruecolor( $newwidth57, $newheight57 ); 
	ImageCopyResized( $newscover57, $origcover, 0, 0, 0, 0, $newwidth57, $newheight57, $width, $height ); 
	ImageJPEG( $newscover57, $PicPathOut . $new_cover57 );
	
	//PNG
	$newscover60 = ImageCreate( $newwidth60, $newheight60 ); 
	ImageCopyResized( $newscover60, $origcover, 0, 0, 0, 0, $newwidth60, $newheight60, $width, $height ); 
	ImagePNG( $newscover60, $PicPathOut . $new_cover60 ); 
	
	$newscover72 = ImageCreate( $newwidth72, $newheight72 ); 
	ImageCopyResized( $newscover72, $origcover, 0, 0, 0, 0, $newwidth72, $newheight72, $width, $height ); 
	ImagePNG( $newscover72, $PicPathOut . $new_cover72 ); 
	
	$newscover76 = ImageCreate( $newwidth76, $newheight76 ); 
	ImageCopyResized( $newscover76, $origcover, 0, 0, 0, 0, $newwidth76, $newheight76, $width, $height ); 
	ImagePNG( $newscover76, $PicPathOut . $new_cover76 ); 	

	$newscover96 = ImageCreate( $newwidth96, $newheight96 ); 
	ImageCopyResized( $newscover96, $origcover, 0, 0, 0, 0, $newwidth96, $newheight96, $width, $height ); 
	ImagePNG( $newscover96, $PicPathOut . $new_cover96 ); 	
	
	$newscover114 = ImageCreate( $newwidth114, $newheight114 ); 
	ImageCopyResized( $newscover114, $origcover, 0, 0, 0, 0, $newwidth114, $newheight114, $width, $height ); 
	ImagePNG( $newscover114, $PicPathOut . $new_cover114 );	
		
	$newscover120 = ImageCreate( $newwidth120, $newheight120 ); 
	ImageCopyResized( $newscover120, $origcover, 0, 0, 0, 0, $newwidth120, $newheight120, $width, $height ); 
	ImagePNG( $newscover120, $PicPathOut . $new_cover120 );
	
	$newscover144 = ImageCreate( $newwidth144, $newheight144 ); 
	ImageCopyResized( $newscover144, $origcover, 0, 0, 0, 0, $newwidth144, $newheight144, $width, $height ); 
	ImagePNG( $newscover144, $PicPathOut . $new_cover144 );	
	
	$newscover152 = ImageCreate( $newwidth152, $newheight152 ); 
	ImageCopyResized( $newscover152, $origcover, 0, 0, 0, 0, $newwidth152, $newheight152, $width, $height ); 
	ImagePNG( $newscover152, $PicPathOut . $new_cover152 );	
	
	$newscover160 = ImageCreate( $newwidth160, $newheight160 ); 
	ImageCopyResized( $newscover160, $origcover, 0, 0, 0, 0, $newwidth160, $newheight160, $width, $height ); 
	ImagePNG( $newscover160, $PicPathOut . $new_cover160 );	
} 

if( $size[2] == 3 ) { 
	// Es ist ein PNG 
	$origcover = ImageCreateFromPNG( $PicPathIn . $cover ); 
	
	//JPEG
	$newscover16 = imagecreatetruecolor( $newwidth16, $newheight16 ); 
	ImageCopyResized( $newscover16, $origcover, 0, 0, 0, 0, $newwidth16, $newheight16, $width, $height ); 
	ImageJPEG( $newscover16, $PicPathOut . $new_cover16 ); 	

	$newscover32 = imagecreatetruecolor( $newwidth32, $newheight32 ); 
	ImageCopyResized( $newscover32, $origcover, 0, 0, 0, 0, $newwidth32, $newheight32, $width, $height ); 
	ImageJPEG( $newscover32, $PicPathOut . $new_cover32 ); 
	
	$newscover57 = imagecreatetruecolor( $newwidth57, $newheight57 ); 
	ImageCopyResized( $newscover57, $origcover, 0, 0, 0, 0, $newwidth57, $newheight57, $width, $height ); 
	ImageJPEG( $newscover57, $PicPathOut . $new_cover57 );
	
	//PNG
	$newscover60 = ImageCreate( $newwidth60, $newheight60 ); 
	ImageCopyResized( $newscover60, $origcover, 0, 0, 0, 0, $newwidth60, $newheight60, $width, $height ); 
	ImagePNG( $newscover60, $PicPathOut . $new_cover60 ); 
	
	$newscover72 = ImageCreate( $newwidth72, $newheight72 ); 
	ImageCopyResized( $newscover72, $origcover, 0, 0, 0, 0, $newwidth72, $newheight72, $width, $height ); 
	ImagePNG( $newscover72, $PicPathOut . $new_cover72 ); 
	
	$newscover76 = ImageCreate( $newwidth76, $newheight76 ); 
	ImageCopyResized( $newscover76, $origcover, 0, 0, 0, 0, $newwidth76, $newheight76, $width, $height ); 
	ImagePNG( $newscover76, $PicPathOut . $new_cover76 ); 	

	$newscover96 = ImageCreate( $newwidth96, $newheight96 ); 
	ImageCopyResized( $newscover96, $origcover, 0, 0, 0, 0, $newwidth96, $newheight96, $width, $height ); 
	ImagePNG( $newscover96, $PicPathOut . $new_cover96 ); 	
	
	$newscover114 = ImageCreate( $newwidth114, $newheight114 ); 
	ImageCopyResized( $newscover114, $origcover, 0, 0, 0, 0, $newwidth114, $newheight114, $width, $height ); 
	ImagePNG( $newscover114, $PicPathOut . $new_cover114 );	
		
	$newscover120 = ImageCreate( $newwidth120, $newheight120 ); 
	ImageCopyResized( $newscover120, $origcover, 0, 0, 0, 0, $newwidth120, $newheight120, $width, $height ); 
	ImagePNG( $newscover120, $PicPathOut . $new_cover120 );
	
	$newscover144 = ImageCreate( $newwidth144, $newheight144 ); 
	ImageCopyResized( $newscover144, $origcover, 0, 0, 0, 0, $newwidth144, $newheight144, $width, $height ); 
	ImagePNG( $newscover144, $PicPathOut . $new_cover144 );	
	
	$newscover152 = ImageCreate( $newwidth152, $newheight152 ); 
	ImageCopyResized( $newscover152, $origcover, 0, 0, 0, 0, $newwidth152, $newheight152, $width, $height ); 
	ImagePNG( $newscover152, $PicPathOut . $new_cover152 );	
	
	$newscover160 = ImageCreate( $newwidth160, $newheight160 ); 
	ImageCopyResized( $newscover160, $origcover, 0, 0, 0, 0, $newwidth160, $newheight160, $width, $height ); 
	ImagePNG( $newscover160, $PicPathOut . $new_cover160 );	
} 

###

// AUSGABE UEBERSICHT
echo 'Original: <br>
<img src="'.$PicPathIn.$cover.'" style="width: 60px;" />
<hr>';
echo '16 Pixel: <br>
<img src="'.$PicPathOut.$new_cover16.'" />
<br>';
echo '32 Pixel: <br>
<img src="'.$PicPathOut.$new_cover32.'" />
<br>';
echo '57 Pixel: <br>
<img src="'.$PicPathOut.$new_cover57.'" />
<br>';
echo '60 Pixel: <br>
<img src="'.$PicPathOut.$new_cover60.'" />
<br>';
echo '72 Pixel: <br>
<img src="'.$PicPathOut.$new_cover72.'" />
<br>';
echo '76 Pixel: <br>
<img src="'.$PicPathOut.$new_cover76.'" />
<br>';
echo '96 Pixel: <br>
<img src="'.$PicPathOut.$new_cover96.'" />
<br>';
echo '114 Pixel: <br>
<img src="'.$PicPathOut.$new_cover114.'" />
<br>';
echo '120 Pixel: <br>
<img src="'.$PicPathOut.$new_cover120.'" />
<br>';
echo '144 Pixel: <br>
<img src="'.$PicPathOut.$new_cover144.'" />
<br>';
echo '152 Pixel: <br>
<img src="'.$PicPathOut.$new_cover152.'" />
<br>';
echo '160 Pixel: <br>
<img src="'.$PicPathOut.$new_cover160.'" />
<br>';
?>