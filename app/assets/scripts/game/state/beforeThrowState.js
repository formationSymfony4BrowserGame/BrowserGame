import render from '../render'
import turnEndState from './turnEndState'
import pickominoState from './pickominoState'

const isHandValid = (data) => new Set(data.hand).has(6)

export const endTurn = (data) => {
  if (isHandValid(data)) { // if the hand is valid the player will choose a pickomino
    pickominoState(data)
  } else { // else the player will git back his top pickomino and end his turn
    turnEndState(true, data)
  }
}

const beforeThrowState = (data) => {
  data.state = 'beforeThrowState'
  render(data)
  if (data.remainingDices.length === 0) { // if there is no dice left to throw
    endTurn(data)
  }
}

export default beforeThrowState
