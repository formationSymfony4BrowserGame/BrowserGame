import { render } from './game/render'

const data = {
  currentPlayer: 0,
  hand: [],
  remainingDices: [],
  skewer: [21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36],
  state: 'loading',
  players: []
}

// return a number between min and max both included
const random = (min, max) => {
  return Math.floor(Math.random() * (max - min + 1)) + min
}

const start = (players) => {
  players = Object.values(players)
  // populating data.players
  for (let i = 0; i < players.length; i++) {
    const playerName = players[i]
    const playerData = {
      pseudo: playerName,
      ranking: i,
      pickominos: []
    }
    data.players.push(playerData)
  }
  // setting dices in play area
  for (let i = 0; i < 8; i++) {
    data.remainingDices.push(random(1, 6))
  }

  console.log(data)
  render(data)
}

const load = (game) => {
  data.hand = game.hand
  data.remainingDices = game.remainingDices
  data.players = game.players
  // currentPlayer contain the index of the player in the data.player array while currentPlayerId contains the id value of the player from the database
  // so we use findIndex to get the index of the player with it's id equals to currentPlayerId
  data.currentPlayer = data.players.findIndex(player => player.id === game.currentPlayerId)
  const nextSate = game.gameState
  // we remove from the default skewer each pickominos owned by each players
  data.players.forEach(player => {
    player.pickominos.forEach(pickomino => {
      data.skewer = data.skewer.filter((skewerPickomino) => {
        return skewerPickomino !== pickomino
      })
    })
  })
  console.log(data)
  render(data)
}

// allow them to be called from outside webpacked assets
window.start = start
window.load = load
console.log('loaded')
