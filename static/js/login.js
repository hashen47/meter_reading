function login() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    $.ajax({
        url: "login.php",
        type: "POST",
        data : {
            username: username,
            password: password,
        },
        success: function(resp) {
            r = JSON.parse(resp);
            alert(r.value)
            if (r.status == "success") {
                window.open("setread.php", "_self");
            }
        }
    });
}