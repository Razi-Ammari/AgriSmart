<?php
$pageTitle = '404 - Page Not Found';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-6 text-center">
                <div style="font-size: 8rem; color: var(--primary); font-weight: 700;">404</div>
                <h1 class="mb-4">Page Not Found</h1>
                <p class="lead text-muted mb-4">
                    Oops! The page you're looking for doesn't exist.
                </p>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-house"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
