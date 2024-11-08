<?php include_once __DIR__ . "/../partials/header.php"; ?>

<div id="login-form-container">
    <form id="login-form" method="post" action="/login">
        <div>
            <img src="/assets/images/logo.png" alt="Main Logo">
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] === "invalid_credentials") { ?>
            <span class="err-msg">Invalid Credentials</span>
        <?php } ?>

        <div class="input-container">
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
        </div>
        <div class="input-container">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

        <button type="submit" class="continue-btn">Login</button>
    </form>
</div>

<?php include_once __DIR__ . "/../partials/footer.php"; ?>