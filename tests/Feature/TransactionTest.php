<?php

namespace Tests\Feature;

use App\Events\TransactionCreated;
use App\Jobs\SendTransactionReceivedNotification;
use App\Listeners\UpdatePayeeWallet;
use App\Listeners\UpdatePayerWallet;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private $user1;

    private $user2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        Wallet::query()->create([
            'user_id' => $this->user1->id,
            'total_amount' => 10,
        ]);

        Wallet::query()->create([
            'user_id' => $this->user2->id,
            'total_amount' => 0,
        ]);

        $this->post('/login', [
            'email' => $this->user1->email,
            'password' => 'password',
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_new_transaction()
    {
         $response = $this->postJson(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
            'value' => 2,
        ]);

        $data = json_decode($response->getContent());

        $response->assertStatus(200);

        $this->assertEquals('Transaction successfully created', $data->message);
        $this->assertEquals($this->user1->id, $data->data->transaction->payer);
        $this->assertEquals($this->user2->id, $data->data->transaction->payee);
        $this->assertEquals(2, $data->data->transaction->value);
    }

    public function test_wallets_update_values()
    {
        $this->postJson(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
            'value' => 1,
        ]);

        /** @var Wallet $walletPayer */
        $walletPayer = Wallet::query()->where('user_id', $this->user1->id)->first();
        $actualTotalAmountPayer = $walletPayer->total_amount;
        /** @var Wallet $walletPayee */
        $walletPayee = Wallet::query()->where('user_id', $this->user2->id)->first();
        $actualTotalAmountPayee = $walletPayee->total_amount;

        $this->assertEquals(9, $actualTotalAmountPayer);
        $this->assertEquals(1, $actualTotalAmountPayee);
    }

    public function test_pass_incorrect_payer_email()
    {
        $response = $this->post(route('transactions.save'), [
            'payer' => 'wrongemail@test.com',
            'payee' => $this->user2->email,
            'value' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_pass_incorrect_payee_email()
    {
        $response = $this->post(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => 'wrongemail@test.com',
            'value' => 1,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_pass_no_value()
    {
        $response = $this->post(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_pass_all_correct_fields()
    {
        $response = $this->post(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
            'value' => 1,
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_event_dispatched()
    {
        Event::fake();

        $this->post(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
            'value' => 1,
        ]);

        Event::assertDispatched(TransactionCreated::class);
        Event::assertListening(
            TransactionCreated::class,
            UpdatePayerWallet::class,
        );
        Event::assertListening(
            TransactionCreated::class,
            UpdatePayeeWallet::class,
        );
    }

    public function test_job_dispatched()
    {
        Queue::fake();

        $this->post(route('transactions.save'), [
            'payer' => $this->user1->email,
            'payee' => $this->user2->email,
            'value' => 1,
        ]);

        Queue::assertPushed(SendTransactionReceivedNotification::class);
    }
}
