<?php
use Enums\Status;
use Enums\BillingCycle;
use Services\CurrencyConverter;
use Services\LogoService;
use Enums\Currency;
use Core\Auth;

$statusValue = $sub->getStatus()->value;
$isActive = $statusValue === Status::ACTIVE->value;
$isPaused = $statusValue === Status::PAUSED->value;
$isInactive = $statusValue === Status::INACTIVE->value;

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
        'status' => $statusValue,
        'next_payment_date' => $sub->getNextPaymentDate()
]));

// Dynamiczne style dla statusów
$cardStyle = '';
if ($isPaused) $cardStyle = 'opacity: 0.6;';
if ($isInactive) $cardStyle = 'opacity: 0.4; filter: grayscale(100%);';
?>

<div class="sub-card" data-status="<?= $statusValue ?>" data-category="<?= strtolower($sub->getCategory()->name) ?>" data-date="<?= $sub->getNextPaymentDate() ?>" data-price="<?= $normalizedPrice ?>" style="<?= $cardStyle ?>">
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
                        <span class="status-badge" style="padding: 2px 6px; font-size: 11px; background: rgba(16, 185, 129, 0.1); color: #10b981;">Active</span>
                    <?php elseif ($isPaused): ?>
                        <span class="status-badge" style="padding: 2px 6px; font-size: 11px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">Paused</span>
                    <?php else: ?>
                        <span class="status-badge" style="padding: 2px 6px; font-size: 11px; background: rgba(148, 163, 184, 0.1); color: #94a3b8;">Inactive</span>
                    <?php endif; ?>

                </div>
                <span style="color: var(--text-muted); font-size: 13px; margin-top: 2px;"><?= ucfirst(strtolower($sub->getCategory()->name)) ?></span>
            </div>
        </div>
        <div style="display: flex; gap: 4px; align-items: center;">
            <?php if ($isInactive): ?>
                <button class="toggle-status-btn" data-id="<?= $sub->getId() ?>" data-status="1" title="Restore Subscription" style="background: rgba(37, 99, 235, 0.1); color: #3b82f6; border: 1px solid rgba(37,99,235,0.2); border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                    Restore
                </button>
            <?php else: ?>
                <?php
                $toggleStatus = $isActive ? Status::PAUSED->value : Status::ACTIVE->value;
                $toggleTitle = $isActive ? 'Pause' : 'Resume';
                ?>
                <button class="toggle-status-btn" data-id="<?= $sub->getId() ?>" data-status="<?= $toggleStatus ?>" title="<?= $toggleTitle ?>" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; transition: color 0.2s;">
                    <?php if ($isActive): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    <?php endif; ?>
                </button>
                <button class="edit-btn" data-sub="<?= $subJson ?>" title="Edit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </button>
                <button class="delete-btn" data-id="<?= $sub->getId() ?>" title="Delete">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="sub-footer" style="border-top: 1px solid var(--border-color); padding-top: 16px;">
        <div class="sub-date">
            <label><?= $isActive ? 'Next billing' : ($isPaused ? 'Paused on' : 'Ended on') ?></label>
            <span><?= date('M d, Y', strtotime($sub->getNextPaymentDate())) ?></span>
        </div>
        <div class="sub-price">
            <?= $sub->getCurrency()->symbol() ?> <?= number_format($sub->getPrice(), 2) ?>
            <span>/<?= $sub->getBillingCycle() === BillingCycle::MONTHLY ? 'mo' : 'yr' ?></span>
        </div>
    </div>
</div>