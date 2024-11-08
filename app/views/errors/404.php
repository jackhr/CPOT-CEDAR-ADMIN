<?php include_once __DIR__ . "/../partials/header.php" ?>

<div id="not-found-container">
    <h1>404</h1>
    <h2>Page Not Found...</h2>
    <p>Oops! We can't find the page you're looking for.</p>
    <p>Head back to where you were, <a id="back-link">HERE</a></p>
    <?php if (isset($user)) { ?>
        <p>Or head back to the dashboard <a href="/dashboard">HERE</a></p>
    <?php } ?>
</div>

<script>
    $("#back-link").on("click", () => history.back());
</script>

<?php include_once __DIR__ . "/../partials/footer.php" ?>