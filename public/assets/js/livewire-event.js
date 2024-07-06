Livewire.on('set-preview-img-modal', data => {
    let btnShowModal = document.getElementById('btnShowPreviewModal')
    if (btnShowModal) {
        setTimeout(() => {
            btnShowModal.click()

            const res = data[0]
            let type = res.type
            if (type == 'pdf') {
                let set_url = res.path
                const pdfContainer = document.getElementById('pdf-container')

                pdfjsLib.getDocument(set_url).promise.then(pdfDoc => {
                    for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                        pdfDoc.getPage(pageNum).then(page => {
                            const scale = 1.5;
                            const viewport = page.getViewport({ scale })

                            const canvasWraper = document.createElement('div')
                            canvasWraper.classList.add('canvas-wraper')

                            const canvas = document.createElement('canvas')
                            const context = canvas.getContext('2d')
                            canvas.height = viewport.height
                            canvas.width = viewport.width

                            pdfContainer.style.width = `${viewport.width + 4}px`
                            pdfContainer.style.height = `${viewport.height}px`

                            const renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            }

                            canvasWraper.appendChild(canvas)
                            pdfContainer.appendChild(canvasWraper)

                            page.render(renderContext)
                        })
                    }
                })
            }
        }, 100)
    }
})