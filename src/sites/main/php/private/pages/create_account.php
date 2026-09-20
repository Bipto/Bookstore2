<div class="form-container">
    <form id="create-account-form">
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

    <script>
        const form = document.getElementById('create-account-form');

        const emailInput = document.getElementById('email');
        const firstNameInput = document.getElementById('first-name');
        const lastNameInput = document.getElementById('last-name');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (passwordInput.value !== confirmPasswordInput.value) {
                alert('Passwords do not match!');
                return;
            }

            try {
                const response = await fetch("http://api.localhost/users", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        email: emailInput.value,
                        firstName: firstNameInput.value,
                        lastName: lastNameInput.value,
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
                    alert('Failed to create account');
                }

            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</div>