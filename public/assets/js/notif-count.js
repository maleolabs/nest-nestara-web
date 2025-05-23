window.onload = setInterval(updateCounts, 10000)

function updateCounts() {
    updateCount('pendaftaran')
    updateCount('penyerahan')
    updateCount('pendataan')
    updateCount('rekomendasi')
    updateCount('penetapan')
}

function updateCount(type) {
    const countElement = $(`#${type}Count`)
    if (countElement.length) {
        $.ajax({
            type: "GET",
            url: `/izin/count/${type}`,
            success: function (response) {
                if (response > 0) {
                    countElement.text(response)
                }
            }
        })
    }
}