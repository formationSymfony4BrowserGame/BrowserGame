import render from './render'
import beginingState from './state/beginingState'
import afterThrowState from './state/afterThrowState'
import beforeThrowState from './state/beforeThrowState'
import pickominoState from './state/pickominoState'
import turnEndState from './state/turnEndState'

const data = {
  idGame: null,
  currentPlayer: 0,
  playerCount: 0,
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
  data.playerCount = data.players.length

  render(data)
  beginingState(data)
}

const load = (game) => {
  data.idGame = game.id
  data.hand = game.hand
  data.remainingDices = game.remainingDices
  data.players = game.players
  data.playerCount = game.playerCount
  // currentPlayer contain the index of the player in the data.player array while currentPlayerId contains the id value of the player from the database
  // so we use findIndex to get the index of the player with it's id equals to currentPlayerId
  data.currentPlayer = data.players.findIndex(player => player.id === game.currentPlayerId)
  // const nextSate = game.gameState
  // we remove from the default skewer each pickominos owned by each players
  data.players.forEach(player => {
    player.pickominos.forEach(pickomino => {
      data.skewer = data.skewer.filter((skewerPickomino) => {
        return skewerPickomino !== pickomino
      })
    })
  })
  render(data)

  switch (game.gameState) {
    case 'beginingState':
      beginingState(data)
      break
    case 'afterThrowState':
      afterThrowState(data)
      break
    case 'beforeThrowState':
      beforeThrowState(data)
      break
    case 'pickominoState':
      pickominoState(data)
      break
    case 'turnEndState':
      turnEndState(data)
      break
  }
}

// allow them to be called from outside webpacked assets
window.start = start
window.load = load
console.log('loaded')
