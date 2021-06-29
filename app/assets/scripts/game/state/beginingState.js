import { updateRemainingDices, render } from '../render'

// Fonction lancé de dé
export const throwDices = (data) => {
  data.remainingDices = data.remainingDices.map(
    () => Math.floor(Math.random() * 6) + 1
  )
  updateRemainingDices(data)
}

// Fonction début de tour
export const beginingState = (data) => {
  data.state = 'beginingState' 
  render(data)
}
