function load_data() {
    const cidVal = document.getElementById("cid").value;

    $.ajax({
        url: "getread.php",
        type: "POST",
        data: {
            cid : cidVal
        },
        success: function(resp) {
            r = JSON.parse(resp);
            switch(r.status) {
                case "success":
                    for (let key in r.value) {
                        document.getElementById(key).value = r.value[key];
                        console.log(key);
                    }
                    break;
                default:
                    alert(r.value);
                    break;
            }
        }
    });
}