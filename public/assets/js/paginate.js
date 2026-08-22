$(function () {
    const dataContainer = $('#data-perizinan-container')
    const dataRow = $('#dataRow')
    const loadingEl = $('#loading-message')
    const loadingRow = $('#loadingRow')
    const emptyState = $('#emptyState')

    const filterWrap = $('#filterDropdownWrap')
    const filterBtn = $('#filterBtn')
    const filterBtnLabel = $('#filterBtnLabel')
    const filterMenu = $('#filterMenu')

    const searchInput = $('#searchInput')
    const searchPreview = $('#searchPreview')
    const searchClear = $('#searchClear')
    const searchUrl = searchInput.data('search-url')

    const url = dataContainer.data('load-url')

    var page = 1
    var filter = 'semua'
    var loading = false
    var searchLoading = false
    var searchTimer = null

    const documentHeigth = $(window).height()
    const documentWidth = $(window).width()
    const countVertical = Math.round(documentHeigth / 100)
    const countHorizontal = documentWidth <= 575 ? 1 : (documentWidth <= 900 ? 2 : 4)
    const loadingCount = countVertical * countHorizontal

    // ---------- infinite scroll ----------
    function loadMoreData() {
        if (loading || !dataContainer.length) return
        showLoading()

        $.ajax({
            url: url,
            type: 'GET',
            data: { page: page, count: loadingCount, filter: filter },
            success: function (response) {
                if (response.length > 0) {
                    var html = ''
                    $.each(response, function (index, item) { html += item })
                    dataRow.append(html)
                    page++
                } else {
                    $(window).off('scroll')
                    if (page === 1) emptyState.removeClass('hidden')
                }
                loadingEl.addClass('hidden')
                loading = false
            },
            error: function () {
                loadingEl.addClass('hidden')
                loading = false
            }
        })
    }

    function showLoading() {
        emptyState.addClass('hidden')
        loadingRow.empty()
        loadingEl.removeClass('hidden')
        loading = true
        for (let i = 0; i < loadingCount; i++) {
            loadingRow.append(`
                <div class="rounded-2xl bg-white ring-1 ring-ink-200 p-4">
                    <div class="loading-line mb-2"></div>
                    <div class="loading-line-sm mb-2"></div>
                    <div class="flex gap-2 mt-3">
                        <div class="loading-line-sm" style="width:70px"></div>
                        <div class="loading-line-sm" style="width:90px"></div>
                    </div>
                </div>`)
        }
    }

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 120) {
            loadMoreData()
        }
    })

    // ---------- filter ----------
    function setFilterUI(value) {
        // active check mark
        $('.filter-option').each(function () {
            const isActive = $(this).data('filter') === value
            $(this).toggleClass('bg-nest-50 text-nest-800', isActive)
            $(this).find('.filter-check').toggleClass('hidden', !isActive)
        })
        // button label
        const labels = { 'semua': 'Semua', 'tersedia': 'Tersedia', 'tidak_tersedia': 'Tidak Tersedia' }
        filterBtnLabel.text(labels[value] || value.replace('_', ' '))
    }

    function applyFilter(value) {
        filter = value
        setFilterUI(value)
        dataRow.empty()
        page = 1
        loadMoreData()
    }

    filterBtn.on('click', function (e) {
        e.stopPropagation()
        filterMenu.toggleClass('hidden')
        filterBtn.attr('aria-expanded', String(!filterMenu.hasClass('hidden')))
    })

    $('.filter-option').on('click', function () {
        applyFilter($(this).data('filter'))
        filterMenu.addClass('hidden')
        filterBtn.attr('aria-expanded', 'false')
    })

    $(document).on('click', function (e) {
        if (!filterWrap.is(e.target) && filterWrap.has(e.target).length === 0) {
            filterMenu.addClass('hidden')
            filterBtn.attr('aria-expanded', 'false')
        }
    })

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') {
            filterMenu.addClass('hidden')
            searchPreview.addClass('hidden')
        }
    })

    // ---------- search ----------
    function showSearchLoading() {
        searchPreview.removeClass('hidden').html(
            `<div class="p-6 flex flex-col items-center justify-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-ink-400"></i>
                <p class="text-xs font-semibold text-ink-500">Mencari…</p>
            </div>`
        )
    }

    function searchData(query) {
        searchLoading = true
        $.ajax({
            url: searchUrl,
            type: 'GET',
            data: { q: query },
            success: function (response) {
                searchLoading = false
                if (response.length > 0) {
                    searchPreview.html(response).removeClass('hidden')
                } else {
                    searchPreview.html(
                        `<div class="p-6 flex flex-col items-center justify-center gap-1 text-center">
                            <i class="fa-solid fa-circle-xmark text-red-400 mb-1"></i>
                            <p class="text-sm font-semibold text-ink-700">Tidak ditemukan</p>
                            <p class="text-xs text-ink-500">Tidak ada jenis perizinan dengan nama “${query}”</p>
                        </div>`
                    ).removeClass('hidden')
                }
            },
            error: function () {
                searchLoading = false
                searchPreview.addClass('hidden')
            }
        })
    }

    searchInput.on('input', function () {
        const value = $(this).val()
        searchClear.toggleClass('hidden', value.trim() === '')
        clearTimeout(searchTimer)
        if (value.trim() !== '') {
            searchTimer = setTimeout(() => {
                showSearchLoading()
                searchData(value.trim())
            }, 300)
        } else {
            searchPreview.addClass('hidden')
        }
    })

    searchClear.on('click', function () {
        searchInput.val('').trigger('input').focus()
    })

    $(document).on('click', function (e) {
        if (!searchPreview.is(e.target) && searchPreview.has(e.target).length === 0 && !searchInput.is(e.target)) {
            searchPreview.addClass('hidden')
        }
    })

    // init
    setFilterUI(filter)
    loadMoreData()
})
