<div class="form-container">
    <form id="create-account-form" method="POST" action="/create_account">
        <h2 class="centre-align">Create Account</h2>

        <div class="form-entries">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="first-name">First Name</label>
            <input type="text" name="first-name" id="first-name" required>

            <label for="last-name">Last Name</label>
            <input type="text" name="last-name" id="last-name" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" name="confirm-password" id="confirm-password" required>
        </div>

        <div class="right-align">
            <button type="submit">Create Account</button>
        </div>
    </form>
</div>