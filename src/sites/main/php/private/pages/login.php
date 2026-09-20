<div class="form-container">
    <form id="login-form">
        <h2 class="centre-align">Login</h2>

        <div class="form-entries">
            <label for="email">Email</label>
            <input type="text" id="email">

            <label for="password">Password</label>
            <input type="password" id="password">
        </div>

        <div class="right-align">
            <button type="submit">Login</button>
        </div>

        <a href="/create_account" class="centre-align">
            <p>Don't have an account? Create one now!</p>
        </a>

    </form>

    <script>
        const form = document.getElementById('login-form');

        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            try {
                const response = await fetch("http://api.localhost/auth/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        email: emailInput.value,
                        password: passwordInput.value
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error: ${response.status}`);
                }

                const data = await response.json();
                if (data['success']) {
                    window.location.href = '/';
                } else {
                    alert('Failed to login');
                }

            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</div>