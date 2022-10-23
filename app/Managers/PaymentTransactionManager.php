<?php

namespace App\Managers;

use App\Repositories\PaymentMethodRepository;
use App\Repositories\TransactionHistoryRepository;
use App\Models\TransactionHistory;
use Stripe;
use Srmklive\PayPal\Services\PayPal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentTransactionManager extends BaseManager
{

    const TRANSACTION_TYPE_USER_WEBSITE = 1;
    const TRANSACTION_TYPE_DOMAIN = 2;
    const TRANSACTION_TYPE_UPGRADE_PLAN = 3;
    const PAYMENT_METHOD_STRIPE = 1;
    const PAYMENT_METHOD_PAYPAL = 2;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    /**
     * @var TransactionHistoryRepository
     */
    protected $transactionHistoryRepository;

    /**
     * @var PayPal
     */
    protected $payPal;

    /**
     * @var DomainManager
     */
    protected $domainManager;

    /**
     * @var UserWebsiteManager
     */
    protected $userWebsiteManager;


    /**
     * PaymentTransactionManager constructor.
     * @param PaymentMethodRepository $paymentMethodRepository
     * @param TransactionHistoryRepository $transactionHistoryRepository
     * @param PayPal $payPal
     * @param DomainManager $domainManager
     * @param UserWebsiteManager $userWebsiteManager
     */
    public function __construct(
        PaymentMethodRepository $paymentMethodRepository,
        TransactionHistoryRepository $transactionHistoryRepository,
        PayPal $payPal,
        DomainManager $domainManager,
        UserWebsiteManager $userWebsiteManager
    ) {
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->transactionHistoryRepository = $transactionHistoryRepository;
        $this->paypal = $payPal;
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
        $this->domainManager = $domainManager;
        $this->userWebsiteManager = $userWebsiteManager;
    }

    /**
     * @param $data
     * @return TransactionHistory
     */
    public function createTransactionHistory($data): TransactionHistory
    {
        return $this->transactionHistoryRepository->updateOrCreate(null, $data);
    }

    /**
     * @param $data
     * @return TransactionHistory
     * @throws Stripe\Exception\ApiErrorException
     */
    public function createStripeOrder($data): TransactionHistory
    {
        Stripe\Stripe::setApiKey(config('stripe.secret_key'));

        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);

        $dataStripe = Stripe\Charge::create([
            'amount' => $userWebsite->total_price * 100,
            'currency' => config('stripe.currency'),
            'source' => $data['stripeToken'],
            'description' => 'Order by user id ' . $userWebsite->created_id
        ]);

        // update user website status
        $userWebsite->current_tasks = $this->userWebsiteManager::CURRENT_TASK_CREATE_WEBSITE;
        $userWebsite->status = $this->userWebsiteManager::STATUS_INITIAL;
        $userWebsite->save();

        return $this->createTransactionHistory([
            'relation_id' => $userWebsite->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_USER_WEBSITE,
            'order_id' => $dataStripe->id,
            'payment_method_id' => self::PAYMENT_METHOD_STRIPE,
            'amount' => $userWebsite->total_price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $userWebsite->created_id
        ]);
    }

    /**
     * @param $data
     * @return TransactionHistory
     * @throws Stripe\Exception\ApiErrorException
     */
    public function createStripeOrderDomain($data): TransactionHistory
    {
        Stripe\Stripe::setApiKey(config('stripe.secret_key'));

        $domain = $this->domainManager->getDomainById($data['domain_id']);

        $dataStripe = Stripe\Charge::create([
            'amount' => $domain->price * 100,
            'currency' => config('stripe.currency'),
            'source' => $data['stripeToken'],
            'description' => 'Order by user id ' . $domain->created_id
        ]);

        $domain->status = "";
        $domain->save();

        return $this->createTransactionHistory([
            'relation_id' => $domain->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_DOMAIN,
            'order_id' => $dataStripe->id,
            'payment_method_id' => self::PAYMENT_METHOD_STRIPE,
            'amount' => $domain->price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $domain->created_id
        ]);
    }

        /**
     * @param $data
     * @return TransactionHistory
     */
    public function createStripeOrderUpgradePlan($data): TransactionHistory
    {
        Stripe\Stripe::setApiKey(config('stripe.secret_key'));

        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);

        $dataStripe = Stripe\Charge::create([
            'amount' => $userWebsite->total_price * 100,
            'currency' => config('stripe.currency'),
            'source' => $data['stripeToken'],
            'description' => 'Order by user id ' . $userWebsite->created_id
        ]);

        return $this->createTransactionHistory([
            'relation_id' => $userWebsite->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_UPGRADE_PLAN,
            'order_id' => $dataStripe->id,
            'payment_method_id' => self::PAYMENT_METHOD_STRIPE,
            'amount' => $userWebsite->total_price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $userWebsite->created_id
        ]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createPaypalOrder(array $data)
    {
        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);

        $result = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => config('app.name'),
                'landing_page' => 'BILLING',
                "user_action" => "PAY_NOW",
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $userWebsite->total_price
                    ]
                ]
            ]
        ]);

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createPaypalOrderDomain(array $data)
    {
        $domain = $this->domainManager->getDomainById($data['domain_id']);

        $result = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => config('app.name'),
                'landing_page' => 'BILLING',
                "user_action" => "PAY_NOW",
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $domain->price
                    ]
                ]
            ]
        ]);

        return $result;
    }

        /**
     * @param array $data
     * @return mixed
     */
    public function createPaypalOrderUpgradePlan(array $data)
    {
        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);

        $result = $this->paypal->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'brand_name' => config('app.name'),
                'landing_page' => 'BILLING',
                "user_action" => "PAY_NOW",
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $userWebsite->total_price
                    ]
                ]
            ]
        ]);

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function capturePaypalOrder(array $data)
    {
        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);
        $result = $this->paypal->capturePaymentOrder($data['order_id']);

        // update user website status
        $userWebsite->current_tasks = $this->userWebsiteManager::CURRENT_TASK_CREATE_WEBSITE;
        $userWebsite->status = $this->userWebsiteManager::STATUS_INITIAL;
        $userWebsite->save();

        $this->createTransactionHistory([
            'relation_id' => $userWebsite->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_USER_WEBSITE,
            'order_id' => $data['order_id'],
            'payment_method_id' => self::PAYMENT_METHOD_PAYPAL,
            'amount' => $userWebsite->total_price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $userWebsite->created_id
        ]);

        return $result;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function capturePaypalOrderDomain(array $data)
    {
        $domain = $this->domainManager->getDomainById($data['domain_id']);
        $result = $this->paypal->capturePaymentOrder($data['order_id']);

        $domain->status = "";
        $domain->save();

        $this->createTransactionHistory([
            'relation_id' => $domain->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_DOMAIN,
            'order_id' => $data['order_id'],
            'payment_method_id' => self::PAYMENT_METHOD_PAYPAL,
            'amount' => $domain->price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $domain->created_id
        ]);

        return $result;
    }

        /**
     * @param array $data
     * @return mixed
     */
    public function capturePaypalOrderUpgradePlan(array $data)
    {
        $userWebsite = $this->userWebsiteManager->getUserWebsiteById($data['user_website_id']);
        $result = $this->paypal->capturePaymentOrder($data['order_id']);

        $this->createTransactionHistory([
            'relation_id' => $userWebsite->id,
            'transaction_type_id' => self::TRANSACTION_TYPE_UPGRADE_PLAN,
            'order_id' => $data['order_id'],
            'payment_method_id' => self::PAYMENT_METHOD_PAYPAL,
            'amount' => $userWebsite->total_price,
            'currency' => config('stripe.currency'),
            'active' => 1,
            'created_id' => $userWebsite->created_id
        ]);

        return $result;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getInvoices(): LengthAwarePaginator
    {
        return $this->transactionHistoryRepository->getInvoices();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getInvoiceById(int $id)
    {
        return $this->transactionHistoryRepository->getInvoiceById($id);
    }
}
