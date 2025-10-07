<?php
/**
 * This script forces CSRF protection to be disabled for SSL payment callbacks.
 * It should be included at the top of any page that handles SSL payment callbacks.
 */

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set a session variable to indicate that CSRF should be bypassed
$_SESSION['bypass_csrf_for_payment'] = true;

// Also set a cookie as a backup mechanism
setcookie('bypass_csrf_for_payment', '1', time() + 3600, '/', '', false, false);

// Output a hidden form with the CSRF token
echo '<form id="csrf_form" style="display:none;">';
echo '<input type="hidden" name="_token" value="' . ($_SESSION['_token'] ?? '') . '">';
echo '</form>';

// Add JavaScript to automatically submit forms with the CSRF token
echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get the CSRF token
    var token = document.querySelector(\'input[name="_token"]\').value;
    
    // Add the token to all forms
    var forms = document.querySelectorAll("form");
    forms.forEach(function(form) {
        if (!form.querySelector(\'input[name="_token"]\')) {
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "_token";
            input.value = token;
            form.appendChild(input);
        }
    });
});
</script>';
