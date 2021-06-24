// import axios from 'axios'
import htmlToElement from 'html-to-element'

const select = document.getElementById('selected_game')

const displayName = document.getElementById('display_name')

// GET API REST

// axios
// .get('http://localhost:8181/api/players/')
// .then(function (response) {
// })

// Test pour l'affichage des pseudos des joueurs

const displayNameGame = () => {
  const id = select.value
  if (id !== 'without') {
    const display = () => htmlToElement(`
    <h2>Hello</h2>
    `)
    const players = display()
    displayName.appendChild(players)
  } else {
    const players = document.createElement('p')
    displayName.appendChild(players)
  }
}

select.onchange = displayNameGame
