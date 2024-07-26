$(document).ready(function() {
    const dataContainer = $('#data-perizinan-container')

    var page = 1
    var loading = false
    var loadingEl = $('#loading-message')

    const documentHeigth = $(window).height()
    const documentWidth = $(window).width()

    const countVertical = Math.round(documentHeigth / 100)
    const countHorizontal = documentWidth <= 575 ? 1 : (documentWidth <= 900 ? 3 : 4)

    const loadingCount = countVertical * countHorizontal

    function loadMoreData() {
        if (loading || !dataContainer) return
        showLoading()

        const url = dataContainer.data('load-url')
        const dataRow = dataContainer.children('.row').eq(0)

        $.ajax({
            url: url,
            type: 'GET',
            data: { 
                page: page,
                count: loadingCount
             },
            success: function (response) {
                if (response.length > 0) {
                    var html = ''
                    $.each(response, function(index, item) {
                        html += item
                    })
                    dataRow.append(html)
                    page++
                } else {
                    $(window).off('scroll')
                }
                loading = false
                loadingEl.hide()
            }, 
            error: function() {
                loading = false
                loadingEl.hide()
            }
        })
    }

    function showLoading() {
        const loadingRow = loadingEl.children('.row').eq(0)
        if (!loadingRow) return
        loading = true
        loadingEl.show()
        
        for (let index = 0; index < loadingCount; index++) {
            const html = `<div class="col-md-3 col-sm-4 mb-4">
                <div class="card mb-0 w-100">
                    <div class="card-body">
                        <div class="loading-line mb-2"></div>
                        <div class="loading-line mb-2"></div>
                        <div class="loading-line"></div>
                    </div>
                </div>
            </div>`
            loadingRow.append(html)
        }
    }

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() == $(document).height()) {
            loadMoreData()
        }
    })

    loadMoreData()
})