import render, { updateSkewer, updatePlayersScore } from '../render'
import turnEndState from './turnEndState'

const getHandScore = (data) => {
  let score = 0
  data.hand.forEach(dice => {
    score += (dice === 6 ? 5 : dice)
  })
  return score
}

export const getChoosablePickomino = (data) => {
  return Math.max(-1, ...data.skewer.filter(pickomino => pickomino <= getHandScore(data)))
}

export const getStealablePlayer = (data) => {
  const stealablePlayer = data.players.find(player => (player.ranking !== data.currentPlayer) && player.pickominos[player.pickominos.length - 1] === getHandScore(data))
  if (stealablePlayer) {
    return stealablePlayer.ranking
  }
  return null
}

export const chooseSkewerPickomino = (chosenPickomino, data) => {
  // removing the chosen pickomino from the skewer
  data.skewer = data.skewer.filter(pickomino => pickomino !== chosenPickomino)
  // adding it to the current player pickominos
  const currentPlayerIndex = data.players.findIndex((player) => player.ranking === data.currentPlayer)
  data.players[currentPlayerIndex].pickominos.push(chosenPickomino)
  // update the visuals
  updateSkewer(data)
  updatePlayersScore(data)
  // end the turn
  turnEndState(false, data)
}

export const stealPlayerPickomino = (playerRanking, data) => {
  // remove the pickomino from the player
  const stolenPlayerIndex = data.players.findIndex((player) => player.ranking === playerRanking)
  const stolenPickomino = data.players[stolenPlayerIndex].pickominos.pop()
  // add it to the current player pickominos
  const currentPlayerIndex = data.players.findIndex((player) => player.ranking === data.currentPlayer)
  data.players[currentPlayerIndex].pickominos.push(stolenPickomino)
  // update the visuals
  updateSkewer(data)
  updatePlayersScore(data)
  // end the turn
  turnEndState(false, data)
}

const pickominoState = (data) => {
  data.state = 'pickominoState'
  render(data)
}

export default pickominoState
