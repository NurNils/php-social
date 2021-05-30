/**
 * File: topscript.js
 * Functions loaded before anything else
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
/**
 * Opens a snackbar with error or success styling
 * @param string message snackbar message
 * @param boolean error define styling (error or success) of snackbar
 */
function openSnackbar(message, error) {
    const snackBar = document.getElementById("snackbar");
    snackBar.className = "show";
    error ? snackBar.className += " error" : snackBar.className += " success";
    snackBar.innerHTML = message;
    setTimeout(() => {
        snackBar.className = snackBar.className.replace("show", "");
    }, 3000);
}