<?php

namespace Repositories;

use Core\Repository;
use Entities\Subscription;
use PDO;

class SubscriptionRepository extends Repository
{
    public function save(Subscription $subscription): bool
    {
        $sql = "INSERT INTO subscriptions 
                (user_id, name, price, currency, billing_cycle, category, next_payment_date, status) 
                VALUES 
                (:user_id, :name, :price, :currency, :billing_cycle, :category, :next_payment_date, :status) 
                RETURNING id";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            'user_id' => $subscription->getUserId(),
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency' => $subscription->getCurrency(),
            'billing_cycle' => $subscription->getBillingCycle(),
            'category' => $subscription->getCategory(),
            'next_payment_date' => $subscription->getNextPaymentDate(),
            'status' => $subscription->getStatus()
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
                ->setCurrency($row['currency'])
                ->setBillingCycle($row['billing_cycle'])
                ->setCategory($row['category'])
                ->setNextPaymentDate($row['next_payment_date'])
                ->setStatus($row['status']);

            $subscriptions[] = $sub;
        }

        return $subscriptions;
    }
}