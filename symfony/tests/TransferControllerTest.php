<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransferControllerTest extends WebTestCase
{
    public function testSuccessTransfer()
    {
        $inputData = [
            'sourceWallet' => 1,
            'destinationWallet' => 2,
            'amount' => 4000
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/transfer',
            ['json' => json_encode($inputData)]
        );

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString(
            ',"sourceWallet":{"id":1,"userId":1,"amount":940,"currency":"USD",',
            $client->getResponse()->getContent()
        );
        $this->assertStringContainsString(
            '"destinationWallet":{"id":2,"userId":2,"amount":4000,"currency":"USD",',
            $client->getResponse()->getContent()
        );
        $this->assertStringContainsString(
            ',"amount":4000,"currency":"USD","commission":60,',
            $client->getResponse()->getContent()
        );

        $client->request(
            'POST',
            '/transfer',
            ['json' => json_encode($inputData)]
        );

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringContainsString(
            '{"errors":["Insufficient funds to perform transaction. Required - 40.6 USD, current - 9.4 USD"]}',
            $client->getResponse()->getContent()
        );
    }

    /**
     * @dataProvider invalidInputProvider
     * @param array $data
     * @param string $expectedMessage
     * @param int $expectedStatusCode
     */
    public function testInvalidInput(array $data, string $expectedMessage, int $expectedStatusCode)
    {
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/transfer',
            ['json' => json_encode($data)]
        );

        $this->assertResponseStatusCodeSame($expectedStatusCode);
        $this->assertStringContainsString($expectedMessage, $client->getResponse()->getContent());
    }

    public function invalidInputProvider()
    {
        return [
            [['sourceWallet' => 1, 'destinationWallet' => 2], 'amount field should be provided', 400],
            [['destinationWallet' => 2, 'amount' => 100], 'sourceWallet field should be provided', 400],
            [['sourceWallet' => 1, 'amount' => 100], 'destinationWallet field should be provided', 400],
            [['sourceWallet' => 1, 'destinationWallet'=> 2 ,'amount' => 0], 'Value of transaction amount (0) should be greater then 0', 422],
        ];
    }
}
