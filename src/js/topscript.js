/** Opens a snackbar with error or success styling */
function openSnackbar(message, error) {
    const snackBar = document.getElementById("snackbar");
    snackBar.className = "show";
    error ? snackBar.className += " error" : snackBar.className += " success";
    snackBar.innerHTML = message;
    setTimeout(() => {
        snackBar.className = snackBar.className.replace("show", "");
    }, 3000);
}