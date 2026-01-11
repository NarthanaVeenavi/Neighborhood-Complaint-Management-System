<div class="container">
    <h2><?= htmlspecialchars($page_title) ?></h2>

    <form method="POST" action="<?= htmlspecialchars($form_action) ?>">

        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

        <?php if ($user['role'] === 'resident'): ?>
            <label>Apartment No</label>
            <select name="apartment_id" required>
                <option value="">-- Select Apartment --</option>

                <?php while ($apt = $apartments->fetch_assoc()): ?>
                    <option value="<?= $apt['id'] ?>"
                        <?= ($apt['id'] == $user['apartment_id']) ? 'selected' : '' ?>>

                        <?= htmlspecialchars($apt['name']) ?>
                        | Block <?= htmlspecialchars($apt['block']) ?>
                        | Floor <?= htmlspecialchars($apt['floor']) ?>

                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>

        <label>Joining Date</label>
        <input type="date" name="joining_date" id="joining_date" value="<?= htmlspecialchars(date('Y-m-d', strtotime($user['joining_date']))) ?>" required>

        <div class="form-actions">
            <button><a href="<?= htmlspecialchars($cancel_url) ?>">Cancel</a></button>
            <button type="submit">Update </button>
        </div>
    </form>
</div>
