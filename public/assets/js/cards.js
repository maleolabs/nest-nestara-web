(function() {
  "use strict";

  function getRandomBg() {
    const classes = ["card-mono-secondary", "card-mono-tertiary", "card-mono-quaternary"]
    const randomIndex = Math.floor(Math.random() * classes.length)
    return classes[randomIndex]
  }

  Livewire.on('component-loaded', function() {
    const cards = document.querySelectorAll('.card-random-bg')
    if (cards) {
      cards.forEach(card => {
          const randomBg = getRandomBg()
          card.classList.add(randomBg)
      })
    }
  })

})