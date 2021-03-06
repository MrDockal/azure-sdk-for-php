<?php
/**
 * LICENSE: Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5
 *
 * @category  Microsoft
 * @package   Tests\Unit\WindowsAzure\MediaServices\Models
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */

namespace Tests\Unit\WindowsAzure\MediaServices\Models;
use Tests\Framework\TestResources;
use WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction;
use WindowsAzure\Common\Internal\Resources;
use WindowsAzure\Common\Internal\Utilities;

/**
 * Unit Tests for ContentKeyAuthorizationPolicyRestriction
 *
 * @category  Microsoft
 * @package   Tests\Unit\WindowsAzure\MediaServices\Models
 * @author    Azure PHP SDK <azurephpsdk@microsoft.com>
 * @copyright Microsoft Corporation
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 * @version   Release: 0.4.1_2015-03
 * @link      https://github.com/windowsazure/azure-sdk-for-php
 */
class ContentKeyAuthorizationRestrictionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::createFromOptions
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::fromArray
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::__construct
     */
    public function testCreateFromOptions() {        
        // Setup
        $options = array(
                'Name'                  => 'restrictionName',
                'KeyRestrictionType'    => 2,
                'Requirements'          => 'testRequirements'
        );

        // Test
        $contentKeyAuthorizationRestriction = ContentKeyAuthorizationPolicyRestriction::createFromOptions($options);

        // Assert
        $this->assertEquals($options['Name'], $contentKeyAuthorizationRestriction->getName());     
        $this->assertEquals($options['KeyRestrictionType'], $contentKeyAuthorizationRestriction->getKeyRestrictionType());    
        $this->assertEquals($options['Requirements'], $contentKeyAuthorizationRestriction->getRequirements());
    }

    /**
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::requiredFields
     */
    public function testRequiredFields() {
        // Setup
        $contentKeyAuthorizationRestriction = new ContentKeyAuthorizationPolicyRestriction();
        $fixture = ['KeyRestrictionType'];

        // Test
        $result = $contentKeyAuthorizationRestriction->requiredFields();

        // Assert
        $this->assertEquals($fixture, $result);
    }

    /**
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::getName
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::setName
     */
    public function testGetSetName() {
        // Setup
        $testNameForContentKeyAuthorizationRestriction = 'testNameForContentKeyAuthorizationRestriction';
        $contentKeyAuthorizationRestriction = new ContentKeyAuthorizationPolicyRestriction();

        // Test
        $contentKeyAuthorizationRestriction->setName($testNameForContentKeyAuthorizationRestriction);
        $result = $contentKeyAuthorizationRestriction->getName();

        // Assert
        $this->assertEquals($testNameForContentKeyAuthorizationRestriction, $result);
    }

    /**
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::getKeyRestrictionType
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::setKeyRestrictionType
     */
    public function testGetSetKeyRestrictionType() {
        // Setup
        $testNameForContentKeyAuthorizationRestriction = 2;
        $contentKeyAuthorizationRestriction = new ContentKeyAuthorizationPolicyRestriction();

        // Test
        $contentKeyAuthorizationRestriction->setKeyRestrictionType($testNameForContentKeyAuthorizationRestriction);
        $result = $contentKeyAuthorizationRestriction->getKeyRestrictionType();

        // Assert
        $this->assertEquals($testNameForContentKeyAuthorizationRestriction, $result);
    }    

    /**
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::getRequirements
     * @covers WindowsAzure\MediaServices\Models\ContentKeyAuthorizationPolicyRestriction::setRequirements
     */
    public function testGetSetRequirements() {
        // Setup
        $testNameForContentKeyAuthorizationRestriction = 'test requirements';
        $contentKeyAuthorizationRestriction = new ContentKeyAuthorizationPolicyRestriction();

        // Test
        $contentKeyAuthorizationRestriction->setRequirements($testNameForContentKeyAuthorizationRestriction);
        $result = $contentKeyAuthorizationRestriction->getRequirements();

        // Assert
        $this->assertEquals($testNameForContentKeyAuthorizationRestriction, $result);
    }    
    
}
