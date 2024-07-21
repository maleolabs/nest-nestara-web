window.onload = 
    setInterval(() => {
        setPendaftaranCount()
        setPenyerahanCount()
    }, 1000)

function setPendaftaranCount() {
    const pendaftaranCount = $('#pendaftaranCount')
    if (pendaftaranCount) {
        $.ajax({
            type: "GET",
            url: "/izin/count/pendaftaran",
            success: function (response) {
                if (response > 0) {
                    pendaftaranCount.text(response)
                }
            }
        })
    }
}

function setPenyerahanCount() {
    const penyerahanCount = $('#penyerahanCount')
    if (penyerahanCount) {
        $.ajax({
            type: "GET",
            url: "/izin/count/penyerahan",
            success: function (response) {
                if (response > 0) {
                    penyerahanCount.text(response)
                }
            }
        })
    }
}