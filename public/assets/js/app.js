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
  const sidebarRight = select('.sidebar-right')
  const sidebarShow = select('.sidebar-btn-show')
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
  })
  const sidebarClose = select('.sidebar-btn-close')
  if (sidebarClose) {
    sidebarClose.addEventListener('click', function() {
      if (sidebarContainer && sidebarRight) {
        sidebarContainer.classList.add('d-none')
        sidebarRight.style.right = '-300px'
      }
    })
  }
  if (sidebarShow) {
    sidebarShow.addEventListener('click', function() {
      if (sidebarContainer && sidebarRight) {
        sidebarContainer.classList.remove('d-none')
        sidebarRight.style.right = '0'
      }
    })
  }


  const modalRight = select('.modal-right')
  const modalRightContent = select('.modal-right-content')
  const modalRightShowBtn = select('#modalRightShowBtn')
  document.addEventListener('click', function(event) {
    if (modalRightContent && !modalRightContent.contains(event.target) && modalRightShowBtn && !modalRightShowBtn.contains(event.target)) {
      if (modalRight && modalRightContent) {
        modalRight.classList.add('d-none')
        modalRightContent.style.right = '-700px'
      }
    }
  })
  const modalRightCloseBtn = select('#modalRightCloseBtn')
  if (modalRightCloseBtn) {
    modalRightCloseBtn.addEventListener('click', function() {
      if (modalRight && modalRightContent) {
        modalRight.classList.add('d-none')
        modalRightContent.style.right = '-700px'
      }
    })
  }
  if (modalRightShowBtn) {
    modalRightShowBtn.addEventListener('click', function() {
      if (modalRight && modalRightContent) {
        showRightModal()
      }
    })
  }

  function showRightModal() {
    modalRight.classList.remove('d-none')
    modalRightContent.style.right = '0'
  }

  const scrollto = (el) => {
    let header = select('#header')
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

  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) {
        scrollto(window.location.hash)
      }
    }
  });

})()