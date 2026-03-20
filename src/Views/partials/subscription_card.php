<?php
use Enums\Status;
use Enums\BillingCycle;
use Services\CurrencyConverter;
use Services\LogoService;
use Enums\Currency;
use Core\Auth;

$isActive = $sub->getStatus() === Status::ACTIVE;
$targetCurrency = Currency::from(Auth::currencyId());
$normalizedPrice = CurrencyConverter::convert($sub->getPrice(), $sub->getCurrency(), $targetCurrency);
$logoUrl = LogoService::getLogoUrl($sub->getName());

$subJson = htmlspecialchars(json_encode([
        'id' => $sub->getId(),
        'name' => $sub->getName(),
        'price' => $sub->getPrice(),
        'currency' => $sub->getCurrency()->value,
        'billingCycle' => $sub->getBillingCycle()->value,
        'category' => $sub->getCategory()->value,
        'next_payment_date' => $sub->getNextPaymentDate()
]));
?>

<div class="sub-card" data-category="<?= strtolower($sub->getCategory()->name) ?>" data-date="<?= $sub->getNextPaymentDate() ?>" data-price="<?= $normalizedPrice ?>" style="<?= !$isActive ? 'opacity: 0.6;' : '' ?>">
    <div class="sub-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 12px;">

            <?php if ($logoUrl): ?>
                <div style="width: 44px; height: 44px; min-width: 44px; min-height: 44px; flex-shrink: 0; border-radius: 12px; background-color: #ffffff; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                    <img src="<?= htmlspecialchars($logoUrl) ?>" alt="<?= htmlspecialchars($sub->getName()) ?>" style="width: 32px; height: 32px; object-fit: contain; display: block;">
                </div>
            <?php else: ?>
                <div style="background: var(--primary-color); color: white; width: 44px; height: 44px; min-width: 44px; min-height: 44px; flex-shrink: 0; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 20px;">
                    <?= strtoupper(substr($sub->getName(), 0, 1)) ?>
                </div>
            <?php endif; ?>

            <div style="display: flex; flex-direction: column; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="sub-name" style="font-size: 16px; font-weight: 600; color: #fff;"><?= htmlspecialchars($sub->getName()) ?></span>
                    <?php if ($isActive): ?>
                        <span class="status-badge" style="padding: 2px 6px; font-size: 11px;">Active</span>
                    <?php else: ?>
                        <span class="status-badge" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 2px 6px; font-size: 11px;">
                            <?= ucfirst(strtolower($sub->getStatus()->name)) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <span style="color: var(--text-muted); font-size: 13px; margin-top: 2px;"><?= ucfirst(strtolower($sub->getCategory()->name)) ?></span>
            </div>
        </div>
        <div style="display: flex; gap: 4px;">
            <button class="edit-btn" data-sub="<?= $subJson ?>" title="Edit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </button>
            <button class="delete-btn" data-id="<?= $sub->getId() ?>" title="Delete">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
        </div>
    </div>

    <div class="sub-footer" style="border-top: 1px solid var(--border-color); padding-top: 16px;">
        <div class="sub-date">
            <label><?= $isActive ? 'Next billing' : 'Ended on' ?></label>
            <span><?= date('M d, Y', strtotime($sub->getNextPaymentDate())) ?></span>
        </div>
        <div class="sub-price">
            <?= $sub->getCurrency()->symbol() ?> <?= number_format($sub->getPrice(), 2) ?>
            <span>/<?= $sub->getBillingCycle() === BillingCycle::MONTHLY ? 'mo' : 'yr' ?></span>
        </div>
    </div>
</div>