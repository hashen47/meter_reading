function save_data() {
    const dateVal = document.getElementById("date").value;
    const readingVal = document.getElementById("reading").value;
    const cidVal = document.getElementById("cid").value;

    $.ajax({
        url: "setread.php",
        type: "POST",
        data : {
            date: dateVal,
            reading: readingVal,
            cid: cidVal,
        },
        success: function (resp) {
            r = JSON.parse(resp);
            alert(r.value);

            switch(r.status) {
                case "success":
                    window.location.reload();
                    break;
                default:
                    break;
            }
        }
    });
}

function reset() {
    document.getElementById("date").value = null;
    document.getElementById("reading").value = null;
    document.getElementById("cid").value = null;
}