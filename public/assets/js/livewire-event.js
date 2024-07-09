Livewire.on('set-preview-img-modal', data => {
    const res = data[0]
    const temp = res.temp
    let btnShowModal = document.getElementById('btnShowPreviewModal')
    if (btnShowModal || temp) {
        setTimeout(() => {
            if (btnShowModal) {
                btnShowModal.click()
            }
            let type = res.type
            if (type == 'pdf') {
                let set_url = res.path
                const pdfContainer = document.getElementById('pdf-container')

                pdfjsLib.getDocument(set_url).promise.then(pdfDoc => {
                    let promises = [];
                    for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                        promises.push(pdfDoc.getPage(pageNum).then(page => {
                            if (temp && pageNum > 1) {
                                return
                            }
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
                        }))
                    }
                    Promise.all(promises).then(() => {
                        setTimeout(() => {
                            const first_canvas = document.querySelector('canvas')
                            const image = first_canvas.toDataURL('image/png')
                            Livewire.dispatchTo('dashboard.dashboard-util', 'save-preview-img', { 
                                image_data: image,
                                pemohon_id: res.pemohon_id,
                                syarat_id: res.syarat_id,
                                izin_id: res.izin_id,
                                temp: temp
                            })
                        }, 500);
                    })
                })
            }
        }, 100)
    }
})
Livewire.hook('morph.updated', ({el, component}) => {
    setInputsHandle()
    setIconLineHeight()
})

Livewire.on('component-rendered', ({el, component}) => {
    setIconLineHeight()
})

window.onload = setIconLineHeight()

function setIconLineHeight() {
    const iconWraper = document.querySelectorAll('.icon-status-wraper')
    iconWraper.forEach(wraper => {
        const height = wraper.offsetHeight;
        var icon = wraper.querySelector('i')

        var lineIcon = icon.querySelector('.line-icon')
        if (lineIcon) {
            icon.removeChild(lineIcon)
        }
        var lineIcon = document.createElement('span')
        lineIcon.classList.add('line-icon')
        lineIcon.style.height = (height - 20) + "px"

        icon.appendChild(lineIcon)
    })

}

function setInputsHandle() {
    setTimeout(() => {
        var inputs = document.querySelectorAll('input');
        for (var i = 0; i < inputs.length; i++) {
            input = inputs[i]
            if (input.value != "") {
                input.parentElement.classList.add('is-filled');
            } else {
                input.parentElement.classList.remove('is-filled');
            }
            
            inputs[i].addEventListener('focus', function(e) {
                this.parentElement.classList.add('is-focused');
            }, false);

            inputs[i].onkeyup = function(e) {
                if (this.value != "") {
                    this.parentElement.classList.add('is-filled');
                } else {
                    this.parentElement.classList.remove('is-filled');
                }
            };

            inputs[i].addEventListener('focusout', function(e) {
                if (this.value != "") {
                    this.parentElement.classList.add('is-filled');
                }
                this.parentElement.classList.remove('is-focused');
            }, false);
        }
        var textareas = document.querySelectorAll('textarea');
        for (var i = 0; i < textareas.length; i++) {
            textarea = textareas[i]
            if (textarea.value != "") {
                textarea.parentElement.classList.add('is-filled');
            } else {
                textarea.parentElement.classList.remove('is-filled');
            }
            
            textareas[i].addEventListener('focus', function(e) {
                this.parentElement.classList.add('is-focused');
            }, false);

            textareas[i].onkeyup = function(e) {
                if (this.value != "") {
                    this.parentElement.classList.add('is-filled');
                } else {
                    this.parentElement.classList.remove('is-filled');
                }
            };

            textareas[i].addEventListener('focusout', function(e) {
                if (this.value != "") {
                    this.parentElement.classList.add('is-filled');
                }
                this.parentElement.classList.remove('is-focused');
            }, false);
        }
    }, 500);
}