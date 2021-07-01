import render from '../render'
import beginingState from './beginingState'

const loosePickomino = (data) => {
  const currentPlayerIndex = data.players.findIndex((player) => player.ranking === data.currentPlayer)
  // remove the last pickomino of the player and return it.
  // return undefinded if he didn't have any
  const lostPickomino = data.players[currentPlayerIndex].pickominos.pop()
  if (lostPickomino) { // if the player did have a pickomino to loose
    data.skewer.push(lostPickomino) // add it to the skewer
    data.skewer.sort((a, b) => a - b) // sort pickominoes in ascending order
  }
}

const turnEndState = (isHandNull, data) => {
  data.state = 'turnEndState'
  // the player loose a pickomino if his hand is not valid
  if (isHandNull) {
    loosePickomino(data)
  }
  // we put back the dices in RemainingDices
  data.hand.forEach((dice) => {
    data.remainingDices.push(dice)
  })
  data.hand = []
  // render dices and pickominos + uncolor current player
  render(data)
  // change currentPlayer to the next player ranking
  data.currentPlayer = (data.currentPlayer + 1) % data.players.length
  beginingState(data)
}

export default turnEndState
