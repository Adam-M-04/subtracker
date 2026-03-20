<?php

namespace Repositories;

use Core\Repository;
use Entities\Subscription;
use Enums\Category;
use Enums\BillingCycle;
use Enums\Currency;
use Enums\Status;
use PDO;

class SubscriptionRepository extends Repository
{
    public function findAllByUserId(int $userId, string $search = ''): array
    {
        $sql = "SELECT * FROM subscriptions WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if ($search !== '') {
            $sql .= " AND name ILIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY next_payment_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $subscriptions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sub = new Subscription();
            $sub->setId($row['id'])
                ->setUserId($row['user_id'])
                ->setName($row['name'])
                ->setPrice((float)$row['price'])
                ->setCurrency(Currency::from($row['currency_id']))
                ->setBillingCycle(BillingCycle::from($row['billing_cycle_id']))
                ->setCategory(Category::from($row['category_id']))
                ->setStatus(Status::from($row['status_id']))
                ->setNextPaymentDate($row['next_payment_date']);

            $subscriptions[] = $sub;
        }

        return $subscriptions;
    }

    public function save(Subscription $subscription): bool
    {
        $sql = "INSERT INTO subscriptions (user_id, name, price, currency_id, billing_cycle_id, category_id, next_payment_date) 
                VALUES (:user_id, :name, :price, :currency_id, :billing_cycle_id, :category_id, :next_payment_date)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $subscription->getUserId(),
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency_id' => $subscription->getCurrency()->value,
            'billing_cycle_id' => $subscription->getBillingCycle()->value,
            'category_id' => $subscription->getCategory()->value,
            'next_payment_date' => $subscription->getNextPaymentDate()
        ]);
    }

    public function update(Subscription $subscription): bool
    {
        $sql = "UPDATE subscriptions SET 
                name = :name, 
                price = :price, 
                currency_id = :currency_id, 
                billing_cycle_id = :billing_cycle_id, 
                category_id = :category_id, 
                next_payment_date = :next_payment_date
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency_id' => $subscription->getCurrency()->value,
            'billing_cycle_id' => $subscription->getBillingCycle()->value,
            'category_id' => $subscription->getCategory()->value,
            'next_payment_date' => $subscription->getNextPaymentDate(),
            'id' => $subscription->getId(),
            'user_id' => $subscription->getUserId()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id, int $userId): bool
    {
        $sql = "DELETE FROM subscriptions WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        return $stmt->rowCount() > 0;
    }
}