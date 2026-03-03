<div class="page-header">
    <h2>User Management</h2>
    <p>Manage system access, roles, and permissions.</p>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card">
        <div class="stat-title">TOTAL USERS</div>
        <div class="stat-value" style="font-size: 24px;">1,248</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">ACTIVE ADMINS</div>
        <div class="stat-value" style="font-size: 24px; color: #10b981;">14</div>
    </div>
    <div class="stat-card">
        <div class="stat-title">PENDING APPROVAL</div>
        <div class="stat-value" style="font-size: 24px; color: #f59e0b;">3</div>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>User</th>
            <th>Role</th>
            <th>Status</th>
            <th>Last Active</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-small avatar-purple">JD</div>
                    <div class="user-details">
                        <strong>jdoe99</strong>
                        <span>john.doe@example.com</span>
                    </div>
                </div>
            </td>
            <td><span class="role-badge role-admin">Admin</span></td>
            <td>
                <div class="status-indicator status-active">
                    <span class="status-dot"></span>
                    <span class="status-text">Active</span>
                </div>
            </td>
            <td style="color: var(--text-muted); font-size: 14px;">2 mins ago</td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-small avatar-pink">SS</div>
                    <div class="user-details">
                        <strong>sarah_smith</strong>
                        <span>s.smith@tech.net</span>
                    </div>
                </div>
            </td>
            <td><span class="role-badge role-user">User</span></td>
            <td>
                <div class="status-indicator status-active">
                    <span class="status-dot"></span>
                    <span class="status-text">Active</span>
                </div>
            </td>
            <td style="color: var(--text-muted); font-size: 14px;">4 hours ago</td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-small avatar-blue">MD</div>
                    <div class="user-details">
                        <strong>mike_dev</strong>
                        <span>mike.w@startup.io</span>
                    </div>
                </div>
            </td>
            <td><span class="role-badge role-user">User</span></td>
            <td>
                <div class="status-indicator status-inactive">
                    <span class="status-dot"></span>
                    <span class="status-text">Inactive</span>
                </div>
            </td>
            <td style="color: var(--text-muted); font-size: 14px;">2 months ago</td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-small avatar-green">ER</div>
                    <div class="user-details">
                        <strong>emily_r</strong>
                        <span>emily.rose@design.co</span>
                    </div>
                </div>
            </td>
            <td><span class="role-badge role-editor">Editor</span></td>
            <td>
                <div class="status-indicator status-active">
                    <span class="status-dot"></span>
                    <span class="status-text">Active</span>
                </div>
            </td>
            <td style="color: var(--text-muted); font-size: 14px;">1 day ago</td>
            <td></td>
        </tr>
        <tr>
            <td>
                <div class="user-cell">
                    <div class="user-avatar-small avatar-orange">AQ</div>
                    <div class="user-details">
                        <strong>alex_q</strong>
                        <span>alex.quinn@web.org</span>
                    </div>
                </div>
            </td>
            <td><span class="role-badge role-user">User</span></td>
            <td>
                <div class="status-indicator status-pending">
                    <span class="status-dot"></span>
                    <span class="status-text">Pending</span>
                </div>
            </td>
            <td style="color: var(--text-muted); font-size: 14px;">Never</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <div class="pagination">
        <span>Showing 1 to 5 of 128 results</span>
        <div class="page-numbers">
            <button class="page-btn">&lsaquo;</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn" style="border: none;">...</button>
            <button class="page-btn">8</button>
            <button class="page-btn">&rsaquo;</button>
        </div>
    </div>
</div>