<?php 
require 'vendor/autoload.php';
use WebPConvert\WebPConvert;
function compressImage($source , $destination , $quality)
{
	$info = getimagesize($source);
	switch ($info['mime']) {
		case 'image/jpeg': $image = imagecreatefromjpeg($source); imagejpeg($image , $destination , $quality); break;
		case 'image/gif': $image = imagecreatefromgif($source); imagejpeg($image , $destination , $quality); break;
		case 'image/png':
			$image = imagecreatefrompng($source);
			imagealphablending($image, false);
			imagesavealpha($image, true);
			$qf = ($quality==100) ? 99 : $quality;
			$qf = $qf / 10;
			$qf = 10 - $qf;
			imagepng($image , $destination , $qf);
 		break;
		default: return false; break;
	}
	return $destination;
}
if (isset($_POST['gonder'])) {
 
  if (isset($_POST['algoritma'])) {
      if ($_POST['algoritma'] > 0 && $_POST['algoritma'] < 5) {
                    if(isset($_FILES['resim'])){
                           $hata = $_FILES['resim']['error'];
                           if($hata != 0) {
                              $image = ['0','algorithm/'.$randName];
                           } else {
                              $boyut = $_FILES['resim']['size'];
                              
                                 $tip = $_FILES['resim']['type'];
                                 $isim = $_FILES['resim']['name'];
                                 $uzanti = explode('.', $isim);
                                 $uzanti = $uzanti[count($uzanti)-1];
                                 if($uzanti == 'jpg' || $uzanti == 'jpeg' || $uzanti == 'png' || $uzanti == 'gif' || $uzanti == 'webp') {
                                   
                                    $dosya = $_FILES['resim']['tmp_name'];
									$saltName = 'serdar-'.rand().rand().rand();
                                    $randName = $saltName.'.'.$uzanti;
                                    copy($dosya, 'uploads/' . $randName);
                                    //algoritma fonksiyonları çalışacak
									 
									 if($_POST['algoritma'] == 1){
										 $obData = \Izica\ProgressiveImages::fromFileSource('uploads/'.$randName)
											->setFileName('algorithm/'.$saltName)
											->convert();
										 $image = ['1','algorithm/'.$saltName.'.jp2'];
									 }
									 if($_POST['algoritma'] == 2){
										 compressImage('uploads/'.$randName,'algorithm/'.$randName,50);
										 $image = ['2','algorithm/'.$randName];

									 }
									 if($_POST['algoritma'] == 3){
										 compressImage('uploads/'.$randName,'algorithm/'.$randName,50);
										 $image = ['3','algorithm/'.$randName];

									 }
									 if($_POST['algoritma'] == 4){

										$source = 'uploads/'.$randName;
										$destination = 'algorithm/'.$saltName .'.webp';
										$options = [];
										WebPConvert::convert($source, $destination, $options);
										$image = ['4','algorithm/'.$saltName .'.webp'];

									 }
										

                                  
                                 } else {
                                    $image = ['0','algorithm/'.$randName];

                                 }
                              
                           }
                        }
      } else {
        $image = ['0','algorithm/'.$randName];
      }
  } else {
    $image = ['0','algorithm/'.$randName];
  }
}
 ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
<style type="text/css">
  /*
*
* ==========================================
* CUSTOM UTIL CLASSES
* ==========================================
*
*/
#upload {
    opacity: 0;
}

#upload-label {
    position: absolute;
    top: 50%;
    left: 1rem;
    transform: translateY(-50%);
}

.image-area {
    border: 2px dashed rgba(255, 255, 255, 0.7);
    padding: 1rem;
    position: relative;
}

.image-area::before {
    content: 'Resim Önizlemesi';
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.8rem;
    z-index: 1;
}

.image-area img {
    z-index: 2;
    position: relative;
}

/*
*
* ==========================================
* FOR DEMO PURPOSES
* ==========================================
*
*/
body {
    min-height: 100vh;
    background-color: #757f9a;
    background-image: linear-gradient(147deg, #757f9a 0%, #d7dde8 100%);
}

/*

</style>
    <title>Proje Ödevi İleri Programlama Teknikleri</title>
  </head>
  <body>

    <!-- For demo purpose -->
   <div class="container py-5">

	  <header class="text-white text-center">
        <h1 class="display-4">İleri Programlama Teknikleri</h1>
        <p class="lead mb-0">Fırat Üniversitesi Teknoloji Fakültesi Yazılım Mühendisliği İleri Programlama Teknikleri Proje Ödevi</p>
        <p class="mb-5 font-weight-light">
            <a href="http://www.serdarbatihan.com.tr/?portfolio=watch" class="text-white">
                <u>SSH Grup</u>
            </a> Tarafından Yapıldı
        </p>
        <img src="https://res.cloudinary.com/mhmd/image/upload/v1564991372/image_pxlho1.svg" alt="" width="150" class="mb-4">
    </header>


    <div class="row py-4">
        <div class="col-lg-6 mx-auto">
			<?php
			if(!isset($image)){
			?>


			<form action="" method="post" enctype="multipart/form-data">
            <div class="image-area mt-4"><img id="imageResult" src="#" alt="" class="img-fluid rounded shadow-sm mx-auto d-block"></div>
            <br><br>
           
            <div class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
                <input id="upload" type="file" onchange="readURL(this);" name="resim" class="form-control border-0">
                <label id="upload-label" for="upload" class="font-weight-light text-muted">Resim Seç</label>
                <div class="input-group-append">
                    <label for="upload" class="btn btn-light m-0 rounded-pill px-4"> <i class="fa fa-cloud-upload mr-2 text-muted"></i><small class="text-uppercase font-weight-bold text-muted">Resim Seç</small></label>
                </div>
            </div>

              <div class="form-group">
    <label for="exampleFormControlSelect1">Algoritma Seç</label>
    <select class="form-control" id="exampleFormControlSelect1" name="algoritma" style="border-radius: 20px; padding-right:25px; height: 50px;">
      <option value="1">Jpeg 2000</option>
      <option value="2">Jpeg</option>
      <option value="3">Jpg</option>
      <option value="4">Webp</option>
    </select>
  </div>
<button type="submit"  name="gonder" class="btn btn-info" style="width:100%; border-radius: 20px; font-weight: 500; font-size: 20px;">Çalıştır</button>

 </form>
			
      
			<?php
			} else {
				if($image[0] == 0){
							$yol = $image[1];

				?>
	  
			<center>
				<h4 style="color:red">Başarısız Bir Hata Oluştu!</h4>
				<a href="" ><button type="button" class="btn btn-primary">Yeniden İşlem Yap</button></a>
			</center>
		

		
	 
			<?php
			}
			if($image[0] == 1){
							$yol = $image[1];

				?>
	  
			<center>
				<h4 style="color:green">Başarılı</h4>
				<a href="<?php echo $yol ?>" download><button type="button" class="btn btn-success">J2 Algoritmasıyla Oluşturuldu Tıklayıp İndir.</button></a>
				<a href="" ><button type="button" class="btn btn-primary">Yeniden İşlem Yap</button></a>
			</center>
		

		
	 
			<?php
			}
			
			if($image[0] == 2){
								$yol = $image[1];

				?>
	  
			<center>
						<a href="<?php echo $yol ?>" download><img src="<?php echo $yol ?>" id="imageResult" class="img-fluid rounded shadow-sm mx-auto d-block"></a>
				<br>
				<a href="<?php echo $yol ?>" download><button type="button" class="btn btn-success">Jpeg Algoritmasıyla Oluşturuldu Tıklayıp İndir.</button></a>
				<a href="" ><button type="button" class="btn btn-primary">Yeniden İşlem Yap</button></a>
			</center>
		
			<?php
			}
				
			if($image[0] == 3){
			$yol = $image[1];
				//JPG
						?>

			<center>
						<a href="<?php echo $yol ?>" download><img src="<?php echo $yol ?>" id="imageResult" class="img-fluid rounded shadow-sm mx-auto d-block"></a>
				<br>
				<a href="<?php echo $yol ?>" download><button type="button" class="btn btn-success">Jpg Algoritmasıyla Oluşturuldu Tıklayıp İndir.</button></a>
				<a href="" ><button type="button" class="btn btn-primary">Yeniden İşlem Yap</button></a>
			</center>
					<?php

			}
				
			if($image[0] == 4){
			$yol = $image[1];
				//Webp
				?>

			<center>
						<a href="<?php echo $yol ?>" download><img src="<?php echo $yol ?>" id="imageResult" class="img-fluid rounded shadow-sm mx-auto d-block"></a>
				<br>
				<a href="<?php echo $yol ?>" download><button type="button" class="btn btn-success">Webp Algoritmasıyla Oluşturuldu Tıklayıp İndir.</button></a>
				<a href="" ><button type="button" class="btn btn-primary">Yeniden İşlem Yap</button></a>
			</center>
					<?php
			}
				
			}
			?>
            <!-- Upload image input-->
            
            <!-- Uploaded image area-->
            
		</div>
	   </div>
	  </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script>

/*  ==========================================
    SHOW UPLOADED IMAGE
* ========================================== */
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#imageResult')
                .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

$(function () {
    $('#upload').on('change', function () {
        readURL(input);
    });
});

/*  ==========================================
    SHOW UPLOADED IMAGE NAME
* ========================================== */
var input = document.getElementById( 'upload' );
var infoArea = document.getElementById( 'upload-label' );

input.addEventListener( 'change', showFileName );
function showFileName( event ) {
  var input = event.srcElement;
  var fileName = input.files[0].name;
  infoArea.textContent = 'Resim Adı: ' + fileName;
}

</script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
