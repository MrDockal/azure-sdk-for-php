<?php
/**
 * Created by PhpStorm.
 * User: Honzik
 * Date: 1.5.2016
 * Time: 17:36
 */
require_once "WindowsAzure/WindowsAzure.php";

use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\Internal\MediaServicesSettings;
use WindowsAzure\Common\Internal\Utilities;
use WindowsAzure\MediaServices\Models\Asset;
use WindowsAzure\MediaServices\Models\AccessPolicy;
use WindowsAzure\MediaServices\Models\Locator;
use WindowsAzure\MediaServices\Models\Task;
use WindowsAzure\MediaServices\Models\Job;
use WindowsAzure\MediaServices\Models\TaskOptions;
use WindowsAzure\MediaServices\Models\ContentKey;
use WindowsAzure\MediaServices\Models\ProtectionKeyTypes;
use WindowsAzure\MediaServices\Models\ContentKeyTypes;
use WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicy;
use WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyOption;
use WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction;
use WindowsAzure\MediaServices\Models\ContentKeyDeliveryType;
use WindowsAzure\MediaServices\Models\ContentKeyRestrictionType;
use WindowsAzure\MediaServices\Models\AssetDeliveryPolicy;
use WindowsAzure\MediaServices\Models\AssetDeliveryProtocol;
use WindowsAzure\MediaServices\Models\AssetDeliveryPolicyType;
use WindowsAzure\MediaServices\Models\AssetDeliveryPolicyConfigurationKey;
use WindowsAzure\MediaServices\Templates\PlayReadyLicenseResponseTemplate;
use WindowsAzure\MediaServices\Templates\PlayReadyLicenseTemplate;
use WindowsAzure\MediaServices\Templates\PlayReadyLicenseType;
use WindowsAzure\MediaServices\Templates\MediaServicesLicenseTemplateSerializer;
use WindowsAzure\MediaServices\Templates\WidevineMessage;
use WindowsAzure\MediaServices\Templates\AllowedTrackTypes;
use WindowsAzure\MediaServices\Templates\ContentKeySpecs;
use WindowsAzure\MediaServices\Templates\RequiredOutputProtection;
use WindowsAzure\MediaServices\Templates\Hdcp;
use WindowsAzure\MediaServices\Templates\TokenRestrictionTemplateSerializer;
use WindowsAzure\MediaServices\Templates\TokenRestrictionTemplate;
use WindowsAzure\MediaServices\Templates\SymmetricVerificationKey;
use WindowsAzure\MediaServices\Templates\TokenClaim;
use WindowsAzure\MediaServices\Templates\TokenType;
use WindowsAzure\MediaServices\Templates\WidevineMessageSerializer;

$account = "hugjanmediaservice";
$secret = "hMzAk4Ri5HAjoQigPZQdf4mTfvdPsukZqLenQR7f+UI=";

/** @var $restProxy \WindowsAzure\MediaServices\MediaServicesRestProxy*/
$restProxy = ServicesBuilder::getInstance()->createMediaServicesService(new MediaServicesSettings($account, $secret));

ini_set('max_execution_time', 300);
//1) Creating asset
$asset = new Asset(Asset::OPTIONS_NONE);
$asset = $restProxy->createAsset($asset);
ini_set('max_execution_time', 300);
$access = new AccessPolicy('uploadAccessPolicy');
$access->setDurationInMinutes(60);
$access->setPermissions(AccessPolicy::PERMISSIONS_WRITE);
$access = $restProxy->createAccessPolicy($access);
ini_set('max_execution_time', 300);
$sasLocator = new Locator($asset,  $access, Locator::TYPE_SAS);
$sasLocator->setStartTime(new \DateTime('now -5 minutes'));
$sasLocator = $restProxy->createLocator($sasLocator);
ini_set('max_execution_time', 300);
$fileContent = file_get_contents('brown3.avi');
ini_set('max_execution_time', 300);
$restProxy->uploadAssetFile($sasLocator, 'azurevideo', 'brown3.avi');
$restProxy->createFileInfos($asset);
ini_set('max_execution_time', 300);
// 2) create asset file?
//$restProxy->

//3) Creating access policy

$outputAssetName = "Encoded " . $asset->getName();
$outputAssetCreationOption = Asset::OPTIONS_NONE;
$taskBody = '<?xml version="1.0" encoding="utf-8"?><taskBody><inputAsset>JobInputAsset(0)</inputAsset><outputAsset assetCreationOptions="' . $outputAssetCreationOption . '" assetName="' . $outputAssetName . '">JobOutputAsset(0)</outputAsset></taskBody>';
ini_set('max_execution_time', 300);


$mediaProcessor = $restProxy->getLatestMediaProcessor('Media Encoder Standard');
ini_set('max_execution_time', 300);
$task = new Task($taskBody, $mediaProcessor->getId(), TaskOptions::NONE);
$task->setConfiguration('H264 Multiple Bitrate 720p');
ini_set('max_execution_time', 300);
$restProxy->createJob(new Job(), array($asset), array($task));
ini_set('max_execution_time', 300);

$accessPolicy = new AccessPolicy('streamingPolicy');
$accessPolicy->setDurationInMinutes(PHP_INT_MAX);
$accessPolicy->setPermissions(AccessPolicy::PERMISSIONS_READ);
$accessPolicy = $restProxy->createAccessPolicy($accessPolicy);
ini_set('max_execution_time', 300);
// Download URL
$sasLocator = new Locator($asset, $accessPolicy, Locator::TYPE_SAS);
$sasLocator->setStartTime(new \DateTime('now -5 minutes'));
$sasLocator = $restProxy->createLocator($sasLocator);
ini_set('max_execution_time', 300);
// Azure needs time to publish media
sleep(30);
ini_set('max_execution_time', 300);
$downloadUrl = $sasLocator->getBaseUri() . '/' . '[File name]' . $sasLocator->getContentAccessComponent();
var_dump($downloadUrl);
// Streaming URL
$originLocator = new Locator($asset, $accessPolicy, Locator::TYPE_ON_DEMAND_ORIGIN);
$originLocator = $restProxy->createLocator($originLocator);
ini_set('max_execution_time', 300);
// Azure needs time to publish media
sleep(30);
ini_set('max_execution_time', 300);
$streamingUrl = $originLocator->getPath() . '[Manifest file name]' . "/manifest";
var_dump($streamingUrl);