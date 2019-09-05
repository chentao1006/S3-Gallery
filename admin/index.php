<?php
//error_reporting(E_ALL);
include_once $_SERVER["DOCUMENT_ROOT"] . '/include/S3.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/include/DataCache.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '/include/img_functions.php';

include_once $_SERVER['DOCUMENT_ROOT']. '/include/getConfig.php';
$s3 = new S3($config['s3']['access'], $config['s3']['secret']);

$cache = new JG_Cache('cache'); //Make sure it exists and is writeable
?>

<html>
        <head>
                <title><?=$config['site']['title']?> S3 Upload & Management</title>
        </head>
        <body>
                <h1><?=$config['site']['title']?> S3 Upload & Management</h1>
                <form action="" method="post" enctype="multipart/form-data" style="border: dotted 1px #999; padding: 10px;">
                        <p>Select File:
                                <input name="theFile" type="file" />
                                <input name="Upload" type="submit" value="Upload">
                        </p>

                        <?php
//check whether a form was submitted
                        if (isset($_POST['Upload'])) {

                                if (!$_FILES) {
                                        echo "Please choose a file.";
                                } else {
                                        //retreive post variables
                                        $fileTempName = $_FILES['theFile']['tmp_name'];
                                        $fileName = $_FILES['theFile']['name'];
                                        $ext=strrchr($fileName,".");
                                        $fileName=date("siHdmY").$ext;http://random-nude.s3.amazonaws.com/37080520082016.tmp

                                        move_uploaded_file($fileTempName, "upload/" . $_FILES['theFile']['name']);


                                        //move the file
                                        if ($s3->putObjectFile("upload/" . $_FILES['theFile']['name'], $config['s3']['bucket'], $fileName, S3::ACL_PUBLIC_READ)) {
                                                echo "Uploaded:<input value='http://".$config['s3']['bucket'].".s3.amazonaws.com/" . $fileName . "' onclick='this.select();return false;' size=100 /><img src=\"http://".$config['s3']['bucket'].".s3.amazonaws.com/" . $fileName . "\" height='100' />";
                                                unlink("upload/" . $_FILES['theFile']['name']);
                                                $cache->clear('s3_upload_files');
                                        } else {
                                                echo "Error";
                                        }
                                }
                        }
                        ?>

                </form>
                <form action="" method="post" enctype="multipart/form-data" style="border: dotted 1px #999; padding: 10px;">
                        <p>Select File:
                                <input name="theUrl" type="text" size="100" />
                                <input name="Copy" type="submit" value="Copy">
                        </p>

                        <?php
//check whether a form was submitted
			if(isset($_GET['copyurl'])){
				$_POST['Copy']='Copy';
				$_POST['theUrl']=$_GET['copyurl'];
				if(strstr($_POST['theUrl'], '?')){
					$_POST['theUrl']=substr($_POST['theUrl'], 0, strpos($_POST['theUrl'], '?'));
				}
			}
				
                        if (isset($_POST['Copy'])) {

                                if (!$_POST['theUrl']) {
                                        echo "Please choose a url.";
                                } else {
                                        //retreive post variables
                                        $fileName = GrabImage($_POST['theUrl']);

                                        //move the file
                                        if ($s3->putObjectFile($fileName, $config['s3']['bucket'], $fileName, S3::ACL_PUBLIC_READ)) {
                                                echo "Uploaded:<input value='http://".$config['s3']['bucket'].".s3.amazonaws.com/" . baseName($fileName) . "' onclick='this.select();return false;' size=100 /><img src=\"http://".$config['s3']['bucket'].".s3.amazonaws.com/" . baseName($fileName) . "\" height='100' />";
                                                unlink($fileName);
                                                $cache->clear('s3_upload_files');
                                        } else {
                                                echo "Error";
                                        }
                                }
                        }
                        ?>

                </form>
                <form action="" method="post" enctype="multipart/form-data" style="border: dotted 1px #999; padding: 10px;">
                        <p>Delete File:
                                <input name="theUrl" type="text" size="100" />
                                <input name="Delete" type="submit" value="Delete">
                        </p>

                        <?php
//check whether a form was submitted
                        if (isset($_POST['Delete'])) {

                                if (!$_POST['theUrl']) {
                                        echo "Please choose a url.";
                                } else {
                                        //retreive post variables
                                        $fileName = $_POST['theUrl'];
                                        $fileName = substr($fileName, strrpos($fileName, '/')+1);
                                        $fileName = substr($fileName, strrpos($fileName, '%2F')+3);
								
                                        //move the file
                                        if ($s3->deleteObject($config['s3']['bucket'], $fileName)) {
                                                echo "Deleted";
                                                $cache->clear('s3_upload_files');
                                        } else {
                                                echo "Error";
                                        }
                                }
                        }
                        ?>

                </form>

                <hr />
                <p><a href="/?cache=clear">Clear Cache</a></p>
        </body>
</html>