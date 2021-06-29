import render, { updateRemainingDices } from '../render'
import afterThrowState from './afterThrowState'

// Fonction lancé de dé
export const throwDices = (data) => {
  data.remainingDices = data.remainingDices.map(
    () => Math.floor(Math.random() * 6) + 1
  )
  updateRemainingDices(data)
  afterThrowState(data)
}

// Fonction début de tour
const beginingState = (data) => {
  data.state = 'beginingState'
  render(data)
}

export default beginingState
