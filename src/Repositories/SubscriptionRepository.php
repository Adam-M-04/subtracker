<?php

namespace Repositories;

use Core\Repository;
use Entities\Subscription;
use Enums\Category;
use Enums\BillingCycle;
use Enums\Currency;
use Enums\Status;
use PDO;
use DateTime;

class SubscriptionRepository extends Repository
{
    public function autoRenewSubscriptions(int $userId): void
    {
        $sql = "SELECT id, next_payment_date, billing_cycle_id FROM subscriptions 
                WHERE user_id = :user_id AND status_id = 1 AND next_payment_date < CURRENT_DATE";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        $overdue = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($overdue)) {
            return;
        }

        $updateStmt = $this->db->prepare("UPDATE subscriptions SET next_payment_date = :new_date WHERE id = :id");
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        foreach ($overdue as $sub) {
            $paymentDate = new DateTime($sub['next_payment_date']);
            $cycle = (int)$sub['billing_cycle_id'];

            while ($paymentDate < $today) {
                if ($cycle === 1) {
                    $paymentDate->modify('+1 month');
                } else {
                    $paymentDate->modify('+1 year');
                }
            }

            $updateStmt->execute([
                'new_date' => $paymentDate->format('Y-m-d'),
                'id' => $sub['id']
            ]);
        }
    }

    public function findAllByUserId(int $userId, string $search = '', bool $includeInactive = false): array
    {
        $sql = "SELECT * FROM subscriptions WHERE user_id = :user_id";
        $params = ['user_id' => $userId];

        if (!$includeInactive) {
            $sql .= " AND status_id != 3";
        }

        if ($search !== '') {
            $sql .= " AND name ILIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        $sql .= " ORDER BY next_payment_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $subscriptions = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $sub = new \Entities\Subscription();
            $sub->setId($row['id'])
                ->setUserId($row['user_id'])
                ->setName($row['name'])
                ->setPrice((float)$row['price'])
                ->setCurrency(\Enums\Currency::from($row['currency_id']))
                ->setBillingCycle(\Enums\BillingCycle::from($row['billing_cycle_id']))
                ->setCategory(\Enums\Category::from($row['category_id']))
                ->setStatus(\Enums\Status::from($row['status_id']))
                ->setNextPaymentDate($row['next_payment_date']);

            $subscriptions[] = $sub;
        }

        return $subscriptions;
    }

    public function save(Subscription $subscription): bool
    {
        $sql = "INSERT INTO subscriptions (user_id, name, price, currency_id, billing_cycle_id, category_id, status_id, next_payment_date) 
                VALUES (:user_id, :name, :price, :currency_id, :billing_cycle_id, :category_id, :status_id, :next_payment_date)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'user_id' => $subscription->getUserId(),
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency_id' => $subscription->getCurrency()->value,
            'billing_cycle_id' => $subscription->getBillingCycle()->value,
            'category_id' => $subscription->getCategory()->value,
            'status_id' => $subscription->getStatus()->value,
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
                status_id = :status_id,
                next_payment_date = :next_payment_date
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'name' => $subscription->getName(),
            'price' => $subscription->getPrice(),
            'currency_id' => $subscription->getCurrency()->value,
            'billing_cycle_id' => $subscription->getBillingCycle()->value,
            'category_id' => $subscription->getCategory()->value,
            'status_id' => $subscription->getStatus()->value,
            'next_payment_date' => $subscription->getNextPaymentDate(),
            'id' => $subscription->getId(),
            'user_id' => $subscription->getUserId()
        ]);

        return $stmt->rowCount() > 0;
    }

    public function updateStatus(int $id, int $userId, Status $status): bool
    {
        $stmt = $this->db->prepare("UPDATE subscriptions SET status_id = :status_id WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([
            'status_id' => $status->value,
            'id' => $id,
            'user_id' => $userId
        ]);
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