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
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — Battle of Shadows</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Crimson+Text:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/battle.css">
  <script src="https://www.google.com/recaptcha/enterprise.js?render=6LdP24wnAAAAAAmZwDAxHLZy3J6gMoK7bV8mFzYP"></script>
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
</head>
<body>
  <?php require "navbar.php"; ?>

  <div class="contact-page">
    <div class="contact-card">
      <h1 class="contact-title">✉ Envoyer un Message</h1>
      <p class="contact-subtitle">Parlez, aventurier...</p>

      <form id="contactForm" method="POST" action="contact.php">
        <?php if ($errorCaptcha) { ?>
          <div class="contact-error">
            ⚠ Erreur de Captcha — veuillez réessayer, voyageur.
          </div>
        <?php } ?>

        <?php if ($success) { ?>
          <div class="contact-success">
            <div style="font-size: 2rem; margin-bottom: 10px;">✦</div>
            <p>Votre message a été transmis aux ombres.</p>
            <p style="font-family:'Crimson Text',serif; font-style:italic; margin-top:8px;">Merci de votre participation.</p>
          </div>
        <?php } else { ?>
          <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" placeholder="Votre nom, aventurier" required>
          </div>

          <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" placeholder="votre@email.com" required>
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Vos mots résonnent dans les ténèbres..." required></textarea>
          </div>

          <input type="hidden" id="recaptchaResponse" name="recaptcha-response">

          <button class="btn-fight" id="submitButton" type="submit" style="width:100%; margin-top:8px;">
            Envoyer le Message
          </button>
        <?php } ?>
      </form>
    </div>
  </div>
</body>
</html>
