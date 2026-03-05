<?php

namespace Repositories;

use Core\Repository;
use Entities\Subscription;
use Enums\BillingCycle;
use Enums\Category;
use Enums\Currency;
use Enums\Status;
use PDO;

class SubscriptionRepository extends Repository
{
    public function save(Subscription $subscription): bool
    {
        $sql = "INSERT INTO subscriptions 
                (user_id, name, price, currency_id, billing_cycle_id, category_id, next_payment_date, status_id) 
                VALUES 
                (:user_id, :name, :price, :currency_id, :billing_cycle_id, :category_id, :next_payment_date, :status_id) 
                RETURNING id";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            'user_id' => $subscription->getUserId(),
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency_id' => $subscription->getCurrency()->value,
            'billing_cycle_id' => $subscription->getBillingCycle()->value,
            'category_id' => $subscription->getCategory()->value,
            'next_payment_date' => $subscription->getNextPaymentDate(),
            'status_id' => $subscription->getStatus()->value
        ]);

        if ($success) {
            $subscription->setId($stmt->fetchColumn());
            return true;
        }

        return false;
    }

    public function findAllByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM subscriptions WHERE user_id = :user_id ORDER BY next_payment_date ASC");
        $stmt->execute(['user_id' => $userId]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $subscriptions = [];

        foreach ($results as $row) {
            $sub = new Subscription();
            $sub->setId($row['id'])
                ->setUserId($row['user_id'])
                ->setName($row['name'])
                ->setPrice((float)$row['price'])
                ->setCurrency(Currency::from($row['currency_id']))
                ->setBillingCycle(BillingCycle::from($row['billing_cycle_id']))
                ->setCategory(Category::from($row['category_id']))
                ->setNextPaymentDate($row['next_payment_date'])
                ->setStatus(Status::from($row['status_id']));

            $subscriptions[] = $sub;
        }

        return $subscriptions;
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM subscriptions WHERE id = :id AND user_id = :user_id");
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);

        // Returns true only if a row was actually deleted
        return $stmt->rowCount() > 0;
    }
}