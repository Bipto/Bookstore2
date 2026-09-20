<div class="form-container">
    <form id="login-form" method="POST" action="/login">
        <h2 class="centre-align">Login</h2>

        <div class="form-entries">
            <label for="email">Email</label>
            <input type="text" name="email" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <div class="right-align">
            <button type="submit">Login</button>
        </div>

        <a href="/create_account" class="centre-align">
            <p>Don't have an account? Create one now!</p>
        </a>

    </form>
</div>