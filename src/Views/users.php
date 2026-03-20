<?php
use Enums\Role;
?>

<div class="page-header" style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 24px;">
    <div>
        <h2 style="margin-top: 0; margin-bottom: 8px;">Users Management</h2>
        <p style="margin: 0;">Manage registered users and view their subscription statistics.</p>
    </div>
</div>

<div class="card" style="background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); overflow-x: auto; -webkit-overflow-scrolling: touch;">
    <table style="width: 100%; min-width: 750px; border-collapse: collapse; text-align: left;">
        <thead style="background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border-color);">
        <tr>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px;">ID</th>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px;">User</th>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px;">Role</th>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px; text-align: center;">Active Subs</th>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px;">Joined</th>
            <th style="padding: 16px; font-weight: 500; color: var(--text-muted); font-size: 14px; text-align: right;">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <?php
            $fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            if (empty($fullName)) $fullName = 'No Name Set';
            $isSelf = $user['id'] === \Core\Auth::id();
            ?>
            <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;">
                <td style="padding: 16px; color: var(--text-muted);"><?= $user['id'] ?></td>
                <td style="padding: 16px;">
                    <div style="font-weight: 600; color: #fff; margin-bottom: 4px;">
                        <?= htmlspecialchars($fullName) ?>
                        <?php if ($isSelf): ?>
                            <span style="font-size: 11px; color: var(--text-muted); font-weight: normal; margin-left: 4px;">(You)</span>
                        <?php endif; ?>
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars($user['email']) ?></div>
                </td>
                <td style="padding: 16px;">
                    <?php if ($isSelf): ?>
                        <span class="status-badge" style="background: rgba(37, 99, 235, 0.1); color: #3b82f6;">Admin</span>
                    <?php else: ?>
                        <select class="form-control role-select" data-id="<?= $user['id'] ?>" style="padding: 4px 8px; width: auto; font-size: 13px; height: auto;">
                            <option value="1" <?= (int)$user['role_id'] === Role::USER->value ? 'selected' : '' ?>>User</option>
                            <option value="2" <?= (int)$user['role_id'] === Role::ADMIN->value ? 'selected' : '' ?>>Admin</option>
                        </select>
                    <?php endif; ?>
                </td>
                <td style="padding: 16px; text-align: center;">
                        <span style="background: var(--bg-color); padding: 4px 10px; border-radius: 20px; font-size: 13px; font-weight: 600; border: 1px solid var(--border-color);">
                            <?= $user['active_subs'] ?>
                        </span>
                </td>
                <td style="padding: 16px; color: var(--text-muted); font-size: 14px;">
                    <?= date('M d, Y', strtotime($user['created_at'])) ?>
                </td>
                <td style="padding: 16px;">
                    <div style="display: flex; justify-content: flex-end;">
                        <button class="delete-btn delete-user-btn" data-id="<?= $user['id'] ?>" title="Delete User" <?= $isSelf ? 'disabled style="opacity: 0.3; cursor: not-allowed;"' : '' ?>>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>