<?php
/**
 * Created by PhpStorm.
 * User: Honzik
 * Date: 11.4.2016
 * Time: 10:31
 */

echo "
<html>
<body>
";

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Blob\Models\CreateContainerOptions;
use WindowsAzure\Blob\Models\PublicAccessType;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Common\Internal\ServiceManagementSettings;
use WindowsAzure\Common\Internal\MediaServicesSettings;
use WindowsAzure\Blob\Models\CopyBlobOptions;

require_once "WindowsAzure/WindowsAzure.php";


//$connectionString="UseDevelopmentStorage=true";
$connectionString = "DefaultEndpointsProtocol=http;AccountName=mediasvcw270ddb0tqz1t;AccountKey=ydEB8AfOSg5Y5OveLNKiQ1gWMyPIZE6oFyJVAKrFOuscjsc+YUHFA17wRbGLtJZp+jMYTf7tn3BJokJiE2fB0Q==";

if (isset($_GET['createContainer'])) {

    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
// OPTIONAL: Set public access policy and metadata.
// Create container options object.
    $createContainerOptions = new CreateContainerOptions();

// Set public access policy. Possible values are
// PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
// CONTAINER_AND_BLOBS:
// Specifies full public read access for container and blob data.
// proxys can enumerate blobs within the container via anonymous
// request, but cannot enumerate containers within the storage account.
//
// BLOBS_ONLY:
// Specifies public read access for blobs. Blob data within this
// container can be read via anonymous request, but container data is not
// available. proxys cannot enumerate blobs within the container via
// anonymous request.
// If this value is not specified in the request, container data is
// private to the account owner.
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

// Set container metadata.
//    $createContainerOptions->addMetaData("key1", "value1");
//    $createContainerOptions->addMetaData("key2", "value2");

    try {
        // Create container.
        $blobRestProxy->createContainer("mycontainer2", $createContainerOptions);
    } catch (ServiceException $e) {
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code . ": " . $error_message . "<br />";
    }

}


if (isset($_GET['uploadFile'])) {

// Create blob REST proxy.
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);


    $content = fopen("steak.jpg", "r");
    $blob_name = "steak2.jpg";

    try {
        //Upload blob
        $blobRestProxy->createBlockBlob("mycontainer", $blob_name, $content);
    } catch (ServiceException $e) {
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code . ": " . $error_message . "<br />";
    }

}

if(isset($_GET['listOfBlobs'])){
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
    $blobs=$blobRestProxy->listBlobs("mycontainer")->getBlobs();
//    var_dump($blobs);
    foreach($blobs as $blob){
        $url=$blobRestProxy->_getBlobUrl("mycontainer",$blob->getName());
        echo '<img width=200 height=200 src='.$url.'>';
    }
}

if (isset($_GET['uploadVideo'])) {

// Create blob REST proxy.
    $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);


    $content = fopen("Azure-Video.wmv", "r");
    $blob_name = "Azure-Video.wmv";

    try {
        //Upload blob
        $blobRestProxy->createBlockBlob("mycontainer", $blob_name, $content);
    } catch (ServiceException $e) {
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code . ": " . $error_message . "<br />";
    }

}

use WindowsAzure\Blob\Models\Block;
use WindowsAzure\Blob\Models\BlobBlockType;


if (isset($_GET['uploadLargeVideo'])) {
    define('CHUNK_SIZE', 1024 * 1024);//Block Size = 1 MB
    header('Content-type: text/html; charset=utf-8');
    try {
        $instance = ServicesBuilder::getInstance();
        $blobRestProxy = $instance->createBlobService($connectionString);
        $containerName = "mycontainer";
        $blobName = "Azure-Video2";
        $handler = fopen("Azure-Video.wmv", "r");
        $counter = 1;
        $blockIds = array();
        while (!feof($handler)) {
            ini_set('max_execution_time', 300);
            $blockId = str_pad($counter, 6, "0", STR_PAD_LEFT);
            $block = new Block();
            $block->setBlockId(base64_encode($blockId));
            $block->setType("Uncommitted");
            array_push($blockIds, $block);
            $data = fread($handler, CHUNK_SIZE);
            echo " \n ";
            echo " -----------------------------------------";
            echo " \n ";
            echo "Read " . strlen($data) . " of data from file";
            echo " \n ";
            echo " -----------------------------------------";
            echo " \n ";
            echo "Uploading block #: " . $blockId . " into blob storage. Please wait.";
            echo " \n ";
            echo " -----------------------------------------";
            echo " \n ";
            $blobRestProxy->createBlobBlock($containerName, $blobName, base64_encode($blockId), $data);
            echo "Uploaded block: " . $blockId . " into blob storage.";
            echo " \n ";
            echo " -----------------------------------------";
            echo " \n ";
            flush();
            ob_flush();
            $counter = $counter + 1;
        }
        fclose($handler);
        echo "Now committing block list. Please wait.";
        echo " \n ";
        echo " -----------------------------------------";
        echo " \n ";
        $blobRestProxy->commitBlobBlocks($containerName, $blobName, $blockIds);
        echo "Blob created successfully.";



//        $options = new CopyBlobOptions();
//        $options->
//        $blobRestProxy -> copyBlob($containerName."2",$blobName."2",'mycontainer',$blobName);
    } catch (Exception $e) {
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/en-us/library/windowsazure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code . ": " . $error_message . "<br />";
    }
}

if (isset($_GET['listOfAssets'])) {
//    $connectionString="DefaultEndpointsProtocol=http;AccountName=hugjanmediaservices;AccountKey=rP7QLjv85WmnoCYa4WEXp6We6QWpbkYO2zpWjwgb8Yg=";

    $mediaRestProxy = ServicesBuilder::getInstance()->createMediaServicesService(new MediaServicesSettings("hugjanmediaservices", "rP7QLjv85WmnoCYa4WEXp6We6QWpbkYO2zpWjwgb8Yg="));


    try {
        $locators = $mediaRestProxy->getLocatorList();
        foreach ($locators as $locator) {
            $assetId= $locator->getAssetId();
            $files = $mediaRestProxy->getAssetFileList();
            foreach($files as $file) {
                if (endsWith(strtolower($file->getName()), '.ism')) {
                    $manifestFile = $file;
                    $parentAssetId= $manifestFile->getParentAssetId();
                    if($parentAssetId==$assetId){
                        $url= $locator->getPath() . $manifestFile->getName()."/manifest";
                        $url = str_replace(".streaming.",".origin.",$url);
                        print $url."<br>";
                    }
                }
            }
        }

    } catch (ServiceException $e) {
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code . ": " . $error_message . "<br />";
    }

}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}


echo
"
<form method='get'>
<input type='submit' name='createContainer' value='create container'>
<input type='submit' name='uploadFile' value='upload file'>
<input type='submit' name='uploadVideo' value='upload video'>
<input type='submit' name='uploadLargeVideo' value='upload large video'>
<input type='submit' name='listOfAssets' value='list of assets'>
<input type='submit' name='listOfBlobs' value='list of blobs'>
</form>
</a>
</body>
</html>
";