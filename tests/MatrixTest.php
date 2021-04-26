<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Database\Factories\UserFactory;
use App\Models\User;

class MatrixTest extends TestCase
{
    use DatabaseMigrations;

    CONST BASE_URL = '/v1';

    public function testBaseRoute()
    {
        $this->get('/');

        $this->assertTrue(
            str_contains($this->response->getContent(), $this->app->version())
        );
    }

    public function testWrongVerbOnSignUp()
    {
        $this->get(self::BASE_URL . '/signup');

        $this->assertEquals(405, $this->response->status());
    }

    public function testSignUpNoInput()
    {
        $this->post(self::BASE_URL . '/signup');

        $this->assertEquals(422, $this->response->status());
    }

    public function testSignUpWithValidInput()
    {
        $this->json('POST', self::BASE_URL . '/signup', ['account_name' => 'Sally', 'email_address' => 'xyz@example.com'])
             ->seeJson([
                'status' => 'success'
             ]);
    }

    public function testMatrixWithoutAuth()
    {
        $this->post(self::BASE_URL . '/matrix');

        $this->assertEquals(401, $this->response->status());
    }

    public function testSingleMatrixWithoutAuth()
    {
        $response = $this->get(self::BASE_URL . '/matrix/1');

        $this->assertEquals(401, $this->response->status());
    }

    public function testMatrixWithValidInput()
    {

        $userFactory = new UserFactory();
        $userFactory = $userFactory->definition();
        $user = $this->json('POST', self::BASE_URL . '/signup', ['account_name' => $userFactory['account_name'], 'email_address' => $userFactory['email_address']])->response->getContent();
        $user = json_decode($user, true);
        $header = [
            'PHP_AUTH_USER' => $user['data']['username'],
            'PHP_AUTH_PW' => $user['data']['password']
        ];
        $body = [
            "multiplicand" => [[1,1,1], [1,1,1]],
            "multiplier" => [[1,1,1,1],[1,1,1,1],[1,1,1,1]]
        ];

        $this->call('POST', self::BASE_URL . '/matrix', $body, [], [], $header);
        $this->assertEquals(200, $this->response->status());
        $this->seeJsonEquals([
            'status' => 'success',
            'data' => [
                "id" => 1,
                "matrix_product" => [[3,3,3,3],[3,3,3,3]],
                "transformed_product" => [["C","C","C","C"],["C","C","C","C"]]
            ]
        ]);

        $id = json_decode($this->response->getContent(),true);
        $response = $this->get(self::BASE_URL . '/matrix/' . $id['data']['id'], $header);

        $this->assertEquals(200, $this->response->status());

    }
}
