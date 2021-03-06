<?php

namespace Oro\Bundle\CustomerBundle\Tests\Functional\Action;

use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\CustomerBundle\Tests\Functional\DataFixtures\LoadCustomerUserACLData;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class CustomerUserActionTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient();
        $this->client->useHashNavigation(true);
        $this->loadFixtures(
            [
                LoadCustomerUserACLData::class,
            ]
        );
    }

    /**
     * @dataProvider deleteDataProvider
     * @param string $login
     * @param string $resource
     * @param int $status
     */
    public function testDelete($login, $resource, $status)
    {
        $this->loginUser($login);
        $id = $this->getReference($resource)->getId();

        $this->client->request(
            'GET',
            $this->getUrl(
                'oro_frontend_action_operation_execute',
                [
                    'operationName' => 'oro_customer_frontend_user_delete',
                    'entityId' => $id,
                    'entityClass' => CustomerUser::class,
                ]
            ),
            [],
            [],
            ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
        );

        $this->assertJsonResponseStatusCodeEquals($this->client->getResponse(), $status);

        if ($status === 200) {
            static::getContainer()->get('doctrine')->getManagerForClass(CustomerUser::class)->clear();

            $removedCustomer = static::getContainer()
                ->get('doctrine')
                ->getRepository('OroCustomerBundle:CustomerUser')
                ->find($id);

            static::assertNull($removedCustomer);
        }
    }

    /**
     * @return array
     */
    public function deleteDataProvider()
    {
        return [
            'anonymous user' => [
                'login' => '',
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_1_ROLE_LOCAL,
                'status' => 403,
            ],
            'same customer: LOCAL_VIEW_ONLY' => [
                'login' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_LOCAL_VIEW_ONLY,
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_LOCAL,
                'status' => 403,
            ],
            'parent customer: LOCAL' => [
                'login' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_LOCAL,
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_1_ROLE_LOCAL,
                'status' => 403,
            ],
            'parent customer: DEEP_VIEW_ONLY' => [
                'login' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_DEEP_VIEW_ONLY,
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_1_ROLE_LOCAL,
                'status' => 403,
            ],
            'parent customer: DEEP' => [
                'login' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_DEEP,
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_1_ROLE_LOCAL,
                'status' => 200,
            ],
            'same customer: LOCAL' => [
                'login' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_LOCAL,
                'resource' => LoadCustomerUserACLData::USER_ACCOUNT_1_ROLE_DEEP_VIEW_ONLY,
                'status' => 200,
            ],
        ];
    }
}
