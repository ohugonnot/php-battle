<?php
require_once __DIR__ . '/vendor/autoload.php';
require "./class/Contact.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$errorCaptcha = false;
$success = false;

if (isset($_POST["recaptcha-response"])) {
    $contact = new Contact($_POST);
    if ($contact->isValid()) {
        if ($contact->captchaIsValid()) {
            $contact->save();
            $success = true;
        } else {
            $errorCaptcha = true;
        }
    } else {
        // gerer les errors qui sont des $contact->errors
    }
}
?>

<html>
<head>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LdP24wnAAAAAAmZwDAxHLZy3J6gMoK7bV8mFzYP"></script>
</head>

<script>
    window.addEventListener('load', () => {
        document.getElementById("contactForm").addEventListener("submit", (e) => {
            e.preventDefault();
            grecaptcha.enterprise.ready(async () => {
                const token = await grecaptcha.enterprise.execute('6LdP24wnAAAAAAmZwDAxHLZy3J6gMoK7bV8mFzYP', {action: 'LOGIN'});
                document.getElementById('recaptchaResponse').value = token;
                document.getElementById('contactForm').submit();
            });
        })
    })
</script>
<body>
<div class="container">
    <?php require "navbar.php" ?>

    <div class="container-fluid px-5 my-5">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0 rounded-3 shadow-lg overflow-hidden">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-sm-6 d-none d-sm-block bg-image"></div>
                            <div class="col-sm-6 p-4">
                                <div class="text-center">
                                    <div class="h3 fw-light">Contact Form</div>
                                    <p class="mb-4 text-muted">Nous laisser un message</p>
                                </div>
                                <form id="contactForm" method="POST" action="contact.php">
                                    <?php if ($errorCaptcha) { ?>
                                        <div class="alert alert-danger">
                                            Erreur de Capatcha veuillez réessayer !
                                        </div>
                                    <?php } ?>
                                    <?php if ($success) { ?>
                                        <div class="alert alert-success">
                                            Le message à bien été sauvegardé.
                                            <br>
                                            Merci de votre participation.
                                        </div>
                                    <?php } else { ?>
                                        <!-- Name Input -->
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="name" type="text" placeholder="Name"
                                            />
                                            <label for="name">Name</label>
                                        </div>

                                        <!-- Email Input -->
                                        <div class="form-floating mb-3">
                                            <input class="form-control" name="email" type="email"
                                                   placeholder="Email Address" required>
                                            <label for="emailAddress">Email Address</label>
                                        </div>

                                        <!-- Message Input -->
                                        <div class="form-floating mb-3">
                                        <textarea class="form-control" name="message" type="text" placeholder="Message"
                                                  style="height: 10rem;" required></textarea>
                                            <label for="message">Message</label>
                                        </div>
                                        <input type="hidden" id="recaptchaResponse" name="recaptcha-response">
                                        <!-- Submit button -->
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" id="submitButton">
                                                Submit
                                            </button>
                                        </div>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {
        }

        .bg-image {
            background-image: url('https://source.unsplash.com/kKvQJ6rK6S4/660x1000');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</div>

</body>
</html>