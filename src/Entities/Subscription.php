<?php

namespace Entities;

use Enums\BillingCycle;
use Enums\Category;
use Enums\Currency;
use Enums\Status;

class Subscription
{
    private ?int $id = null;
    private int $userId;
    private string $name;
    private float $price;
    private Currency $currency = Currency::USD;
    private BillingCycle $billingCycle = BillingCycle::MONTHLY;
    private Category $category = Category::GENERAL;
    private string $nextPaymentDate;
    private Status $status = Status::ACTIVE;

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { $this->id = $id; return $this; }

    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userId): self { $this->userId = $userId; return $this; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getCurrency(): Currency { return $this->currency; }
    public function setCurrency(Currency $currency): self { $this->currency = $currency; return $this; }

    public function getBillingCycle(): BillingCycle { return $this->billingCycle; }
    public function setBillingCycle(BillingCycle $billingCycle): self { $this->billingCycle = $billingCycle; return $this; }

    public function getCategory(): Category { return $this->category; }
    public function setCategory(Category $category): self { $this->category = $category; return $this; }

    public function getNextPaymentDate(): string { return $this->nextPaymentDate; }
    public function setNextPaymentDate(string $nextPaymentDate): self { $this->nextPaymentDate = $nextPaymentDate; return $this; }

    public function getStatus(): Status { return $this->status; }
    public function setStatus(Status $status): self { $this->status = $status; return $this; }
}