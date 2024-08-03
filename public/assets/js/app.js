(function() {
  "use strict";

  // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all)
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  const dashModal = select('.dash-modal', true)
  const dashModalFixOnTop = () => {
    const scrollTop = window.scrollY;
    const windowWidth = window.innerWidth;
    if (dashModal !== null && windowWidth >= 991) {
      dashModal.forEach(modal => {
        if (scrollTop === 0) {
            modal.style.top = '0'
        } else {
            modal.style.top = `${scrollTop}px`
        }
      })
    }
  }
  window.addEventListener('load', dashModalFixOnTop)
  onscroll(document, dashModalFixOnTop)

  const sidebarContainer = select('#sidebarContainer')
  const sideMenuLeftContainer = select('#sideMenuLeftContainer')
  const sidebarRight = select('.sidebar-right')
  const sidebarShow = select('.sidebar-btn-show')
  const sideMenuLeft = select('.side-menu-left')
  const sideMenuLeftShow = select('.btn-show-left-menu')
  document.addEventListener('click', function(event) {
    const closable = select('.dash-modal-closable', true)
    closable.forEach(cl => {
      const closableContainer = cl.parentNode
      if (!cl.contains(event.target) && closableContainer) {
        closableContainer.style.display = 'none'
      }
    })
    const fixed = select('.dash-modal-fixed', true)
    fixed.forEach(fx => {
      if (!fx.contains(event.target)) {
        fx.classList.toggle('not-allowed')
      }
    })
    if (sidebarRight && !sidebarRight.contains(event.target) && sidebarShow && !sidebarShow.contains(event.target)) {
      if (sidebarContainer && sidebarRight) {
        sidebarContainer.classList.add('d-none')
        sidebarRight.style.right = '-300px'
      }
    }
    if (sideMenuLeft && !sideMenuLeft.contains(event.target) && sideMenuLeftShow && !sideMenuLeftShow.contains(event.target)) {
      if (sideMenuLeftContainer && sideMenuLeft) {
        sideMenuLeftContainer.classList.add('d-none')
        sideMenuLeft.style.left = '-300px'
      }
    }
  })
  if (sidebarShow) {
    sidebarShow.addEventListener('click', function() {
      if (sidebarContainer && sidebarRight) {
        sidebarContainer.classList.remove('d-none')
        sidebarRight.style.right = '0'
      }
    })
  }
  if (sideMenuLeftShow) {
    sideMenuLeftShow.addEventListener('click', function() {
      if (sideMenuLeftContainer && sideMenuLeft) {
        sideMenuLeftContainer.classList.remove('d-none')
        sideMenuLeft.style.left = '0'
      }
    })
  }
  const sidebarClose = select('.sidebar-btn-close')
  if (sidebarClose) {
    sidebarClose.addEventListener('click', function() {
      if (sidebarContainer && sidebarRight) {
        sidebarContainer.classList.add('d-none')
        sidebarRight.style.right = '-300px'
      }
    })
  }
  const sideMenuLeftClose = select('.menu-left-btn-close')
  if (sideMenuLeftClose) {
    sideMenuLeftClose.addEventListener('click', function() {
      if (sideMenuLeftContainer && sideMenuLeft) {
        sideMenuLeftContainer.classList.add('d-none')
        sideMenuLeft.style.left = '-300px'
      }
    })
  }

  window.addEventListener('load', appModal())

  function appModal() {
    let modalRight = select('.modal-right')
    let modalRightContent = select('.modal-right-content')
    let modalRightShowBtn = select('#modalRightShowBtn', true)
    document.addEventListener('click', function(event) {
      let isModalRightShowBtnClicked = Array.isArray(modalRightShowBtn) 
          ? modalRightShowBtn.some(btn => btn.contains(event.target))
          : modalRightShowBtn && modalRightShowBtn.contains(event.target)

      if (modalRightContent && !modalRightContent.contains(event.target) && !isModalRightShowBtnClicked) {
        if (modalRight && modalRightContent) {
          modalRight.classList.add('d-none')
          if (modalRightContent.classList.contains('modal-right-content-md')) {
            modalRightContent.style.right = '-70%'
          } else {
            modalRightContent.style.right = '-50%'
          }
        }
      }
    })
    let modalRightCloseBtn = select('#modalRightCloseBtn')
    if (modalRightCloseBtn) {
      modalRightCloseBtn.addEventListener('click', function() {
        if (modalRight && modalRightContent) {
          modalRight.classList.add('d-none')
          if (modalRightContent.classList.contains('modal-right-content-md')) {
            modalRightContent.style.right = '-70%'
          } else {
            modalRightContent.style.right = '-50%'
          }
        }
      })
    }
    if (modalRightShowBtn) {
      if (Array.isArray(modalRightShowBtn)) {
        modalRightShowBtn.forEach(btn => {
          btn.addEventListener('click', function() {
            if (modalRight && modalRightContent) {
              showRightModal()
            }
          })
        })
      } else {
        modalRightShowBtn.addEventListener('click', function() {
          if (modalRight && modalRightContent) {
            showRightModal()
          }
        })
      }
    }
    function showRightModal() {
      modalRight.classList.remove('d-none')
      modalRightContent.style.right = '0'
    }
  }

  const scrollto = (el) => {
    let header = select(el)
    let offset = header.offsetHeight

    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    })
  }

  let selectWelcomeNav = select('.welcome-nav')
  if (selectWelcomeNav) {
    const headerScrolled = () => {
      if (window.scrollY > 10) {
        selectWelcomeNav.classList.add('header-scrolled')
      } else {
        selectWelcomeNav.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  on('click', '.dashboard-mobile-toggle', function(e) {
    select('.sidebar').classList.add('show')
  })

  on('click', '.sidebar-close > i', function(e) {
    select('.sidebar').classList.remove('show')
  })

  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let navbar = select('#navbar')
      if (navbar.classList.contains('navbar-mobile')) {
        navbar.classList.remove('navbar-mobile')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  document.addEventListener('DOMContentLoaded', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  })


})()