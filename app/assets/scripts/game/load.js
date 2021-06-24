import axios from 'axios'
import htmlToElement from 'html-to-element'

const select = document.getElementById('selected_game')

const displayName = document.getElementById('display_name')

const form = document.getElementById('form')

const getPlayers = async (gameId) => {
  const res = await axios.get('/game/load/' + gameId + '/players')
  console.log(res)
  return res.data
}

// Test pour l'affichage des pseudos des joueurs

const displayNameGame = async () => {
  const gameId = select.value
  form.action = '/game/' + gameId
  const playerNames = await getPlayers(gameId)
  const list = htmlToElement(`
    <ul></ul>
  `)
  playerNames.forEach(player => {
    const listElement = (player) => htmlToElement(`
    <li>${player}</li>
    `)
    list.appendChild(listElement(player))
  })
  displayName.innerHTML = ''
  displayName.appendChild(list)
}

displayNameGame()
select.onchange = displayNameGame
