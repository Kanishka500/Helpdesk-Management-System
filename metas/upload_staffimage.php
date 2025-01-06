<?php
ob_start();
include("includes/connect.php");
if (isset($_POST['file_name'])) {
    if (!empty($_GET['usid'])) {
        $setid = $_GET['usid'];
    }
    include("includes/akimg_lib.php");
    $file_name = $_POST['file_name'];
    $base64_img = $_POST['cropped_img'];
    //echo $base64_img;
    $image = explode(',', $base64_img);
    //print_r($image);
    $upload_img = base64_decode($image[1]);
    // ---------- Start Universal Image Resizing Function -------- 
    $kaboom = explode(".", $file_name);
    $fileExt = end($kaboom);
    $file_name = imageName() . "." . $fileExt;
    $filename = 'images/profileface/' . $file_name; // output file name
    $file_uploaded = imageResize($upload_img, $filename);
    if ($file_uploaded) {
        $sql = "UPDATE user_infor  SET  user_image=:filename  WHERE user_id=:userid ";
        $stmt = $pdom->prepare($sql);
        $stmt->execute(['filename' => $file_name, 'userid' => $setid]);
        $result = $stmt->rowCount();
        if (!empty($result)) {
            //----------------------tracker service--------------------------------
            $action = "AddNewImg";
            $actid = $setid;
            $actidtype = "userinfor";
            $pdom = null;
            User::tracker($pdo, $action,$userid, $actid, $actidtype);
            $pdo = null;
            header("location:index.php?page=manage_staff");
        } else {
            $adimmess = '<label class="messadd">Not successfuly.Try Agin</label>';
        }
    } else {
        $adimmess = '<label class="messadd">Not successfuly.Try Agin</label>';
    }
}
if (isset($_POST['defimg'])) {
    $defimg = $_POST['defimg'] . '.JPG';
    $setid = $_POST['selectid'];
    $sql = "UPDATE user_infor  SET  user_image=:defimg  WHERE user_id=:setid ";
    $stmt = $pdom->prepare($sql);
    $stmt->execute(['defimg' => $defimg, 'setid' => $setid]);
    $result = $stmt->rowCount();
    if (!empty($result)) {
        //----------------------tracker service--------------------------------
        $action = "AddDefImg";
        $actid = $setid;
        $actidtype = "userinfor";
        $pdom = null;
        User::tracker($pdo, $action,$userid, $actid, $actidtype);
        $pdo = null;
        header("location:index.php?page=view_staff");
    } else {
        $adimmess = '<label class="messadd">Not successfuly.Try Agin</label>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPTA | Upload Staff Image</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/cropper.min.js"></script>
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/popup_profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/cropper.min.css">
    <link href="css/upload_staffimage.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/media.css">
    <script type="text/javascript">
        function relaod() {
            // window.open('index.php?page=upload_staffimage', '_parent');
            location.reload();
        }

        function sendData() {
            var valid = true;
            $(".error").html('');
            //--------------------------fname-----------------------------
            if ($("input[name='defimg']:checked").length == 0) {
                $("#defimg-info").html("<i class='fa fa-times'></i>Please Select Defult Image");
                valid = false;
            }
            return valid;
        }
    </script>
    <script>
        $(document).ready(function() {
            //------------------------image get------------------------
            // preparing canvas variables
            var $canvas = $('#canvas_crop'),
                context = $canvas.get(0).getContext('2d');
            // waiting for a file to be selected
            $('#img_file').on('change', function() {
                if (this.files && this.files[0]) {
                    // checking if a file is selected

                    if (this.files[0].type.match(/^image\//)) {
                        // valid image file is selected
                        $('#file_name').attr('value', this.files[0].name);

                        // process the image
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var img = new Image();
                            img.onload = function() {
                                context.canvas.width = img.width;
                                context.canvas.height = img.height;
                                context.drawImage(img, 0, 0);

                                // instantiate cropper
                                var cropper = $canvas.cropper({
                                    aspectRatio: 1 / 1
                                });
                            };
                            img.src = e.target.result;
                        };

                        $('#crop').click(function() {
                            var croppedImage = $canvas.cropper('getCroppedCanvas').toDataURL('image/jpg');
                            $('#result').append($('<img id="imag1">').attr('src', croppedImage));
                            $('#cropped_img').attr('value', croppedImage);
                            $('#upload_img').removeAttr('disabled');
                        });

                        // reading the selected file
                        reader.readAsDataURL(this.files[0]);


                    } else {
                        alert('Invalid file type');
                    }
                } else {
                    alert('Please select a file.');
                }
            });
            //------------------------image get------------------------
        });
    </script>
</head>